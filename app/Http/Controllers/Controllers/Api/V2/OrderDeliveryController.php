<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V2\OrderDelivery\CreateRequest;
use App\Http\Requests\Api\V2\OrderDelivery\GetFeeRequest;
use App\Http\Requests\Api\V2\OrderDelivery\GetFeeShopRequest;
use App\Http\Requests\Api\V2\OrderDelivery\UpdateAddressRequest;
use App\Http\Requests\Api\V2\OrderDelivery\UpdateRequest;
use App\Http\Resources\V2\OrderDeliveryCollection;
use App\Models\CustomerPackage;
use App\Models\District;
use App\Models\OrderDelivery;
use App\Models\OrderDeliveryLog;
use App\Models\Province;
use App\Models\User;
use App\Models\Ward;
use App\Services\Delivery\BestExpressService;
use App\Utility\OrderDeliveryUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class OrderDeliveryController extends Controller
{
    private $bestExpressService;

    public function __construct(BestExpressService $bestExpressService)
    {
        $this->bestExpressService = $bestExpressService;
    }

    public function index(Request $request)
    {
        $orders = OrderDelivery::query();
        $orders = $orders->where('user_id', auth()->id())->where('status', '>', 0);
        if ($request->id != "") {
            $id = $request->id;
            $orders = $orders->where(function ($q) use ($id) {
                $q->where('id', $id)->orWhere('partner_code', $id);
            });
        }
        if ($request->type != '') {
            $types = explode(',', $request->type);
            $orders = $orders->whereIn('type', $types);
        }
        if ($request->status != '') {
            $status = explode(',', $request->status);
            $orders = $orders->whereIn('status', $status);
        }
        if ($request->status_payment != '') {
            $status_payment = explode(',', $request->status_payment);
            $orders = $orders->whereIn('status_payment', $status_payment);
        }

        if ((int)$request->start_time > 0) {
            $orders = $orders->where('created_time', '>=', (int)$request->start_time);
        }
        if ((int)$request->end_time > 0) {
            $orders = $orders->where('created_time', '<=', (int)$request->end_time);
        }
        if (!empty($request->search)) {
            $search = $request->search;
            $orders = $orders->where(function ($q) use ($search) {
                $q->where('id', 'like', '%'.$search.'%')
                    ->orWhere('partner_code', 'like', '%'.$search.'%')
                    ->orWhere('source_phone', 'like', '%'.$search.'%')
                    ->orWhere('source_name', 'like', '%'.$search.'%')
                    ->orWhere('dest_name', 'like', '%'.$search.'%')
                    ->orWhere('dest_phone', 'like', '%'.$search.'%');
            });
        }
        $orders = $orders->orderBy('created_at', 'desc')->paginate($request->limit ?? 10);
        return new OrderDeliveryCollection($orders);
    }

    public function show($id)
    {
        $order = OrderDelivery::where(function ($q) use ($id) {
            $q->where('id', $id)->orWhere('partner_code', $id);
        })->where('user_id', auth()->id())->first();
        $history = OrderDeliveryLog::where('order_delivery_id', $id)->get();
        $order->logs = $history;
        if (!$order) {
            return response([
                'result' => false,
                'message' => trans('Không tìm thấy đơn hàng')
            ]);
        }
        return response([
            'result' => true,
            'data' => $order,
        ]);
    }

    public function store(CreateRequest $request)
    {
        $param = $request->only([
            'product_name',
            'product_price',
            'product_number',
            'collect_amount',
            'type',
            'pickup_type',
            'service_id',
            'weight',
            'width',
            'height',
            'length',
            'note',
            'source_province',
            'source_district',
            'source_ward',
            'source_address',
            'source_name',
            'source_phone',
            'dest_province',
            'dest_district',
            'dest_ward',
            'dest_address',
            'dest_name',
            'dest_phone',
        ]);
        switch ($param['type']) {
            case OrderDeliveryUtility::TYPE_GH:
            case OrderDeliveryUtility::TYPE_DH:
                $param['return_province'] = $param['source_province'];
                $param['return_district'] = $param['source_district'];
                $param['return_ward'] = $param['source_ward'];
                $param['return_address'] = $param['source_address'];
                $param['return_name'] = $param['source_name'];
                $param['return_phone'] = $param['source_phone'];
                break;
            case OrderDeliveryUtility::TYPE_TH:
                $param['return_province'] = $param['dest_province'];
                $param['return_district'] = $param['dest_district'];
                $param['return_ward'] = $param['dest_ward'];
                $param['return_address'] = $param['dest_address'];
                $param['return_name'] = $param['dest_name'];
                $param['return_phone'] = $param['dest_phone'];
                break;
        }

        $dataFee = [
            'ServiceId' => $param['service_id'],
            'Cod' => $param['collect_amount'],
            'ProductPrice' => $param['product_price'],
            'Weight' => $param['weight'],
            'DestCity' => $param['dest_province'],
            'DestDistrict' => $param['dest_district'],
            'DestWard' => $param['dest_ward'],
            'SourceCity' => $param['source_province'],
            'SourceDistrict' => $param['source_district'],
            'SourceWard' => $param['source_ward'],
        ];
        $resultFee = $this->bestExpressService->fee($dataFee);
        if ($resultFee['Result'] == 1) {
            $param['delivery_fee'] = (int)$resultFee['data']['FreightFeeVAT'];
            $param['cod_fee'] = (int)$resultFee['data']['CODFeeVAT'];
            $param['insurance_fee'] = (int)$resultFee['data']['InsuranceFeeVAT'];
            $param['total_fee'] = (int)$resultFee['data']['TotalFeeVATWithDiscount'];
        }
        $package_id = auth()->user()->customer_package_id;
        $package = CustomerPackage::find($package_id);
        $order = new OrderDelivery();
        $order->user_id = auth()->id();
        foreach ($param as $key => $value) {
            $order->$key = $value;
        }
        $fee_plus = $this->feePlus($param['weight'] ?? 0, $param['width'] ?? 0, $param['height'] ?? 0, $param['length'] ?? 0);
        $order->customer_delivery_fee = $package->fee + $fee_plus;
        //$order->customer_total_fee = $order->customer_delivery_fee;
        $order->customer_insurance_fee = $order->customer_cod_fee = 0;
        if ($param['collect_amount'] > OrderDeliveryUtility::MIN_FEE) {
            $order->customer_cod_fee = (int)round(0.005 * $param['collect_amount'], 0);
        }
        if( $param['product_price'] > OrderDeliveryUtility::MIN_FEE){
            $order->customer_insurance_fee = (int)round(0.005 * $param['product_price'], 0);
        }
        $order->customer_total_fee = $order->customer_delivery_fee + $order->customer_insurance_fee + $order->customer_cod_fee;
        //$order->customer_total_fee = $package->fee;
        $order->status = OrderDeliveryUtility::STATUS_NEW;
        $order->partner_id = OrderDeliveryUtility::PARTNER_BEST_EXPRESS;
        $order->created_time = time();
        $order->day = date('d', time());
        $order->month = date('m', time());
        $order->year = date('Y', time());
        $kol_id = auth()->user()->referred_by;
        if (!empty($kol_id)) {
            $order->kol_id = $kol_id;
            $order->kol_value = get_setting('affiliate_kol_value') ?? 0;
            $kol = User::find($kol_id);
            if($kol){
                $employee_id = $kol->referred_by;
                if (!empty($employee_id)) {
                    $order->employee_id = $employee_id;
                    $order->employee_value = get_setting('affiliate_employee_value') ?? 0;
                }
            }
        }
        if($order->collect_amount < $order->customer_total_fee){
            $order->collect_amount = $order->collect_amount +  $order->customer_total_fee;
        }
        $order->save();
        $order->code = $order->id . '-' . $order->user_id . '-' . Str::slug(auth()->user()->name, '_');
        //$order->save();
        $data = [
            'Code' => $order->code,
            'ProductName' => $order->product_name,
            'ProductPrice' => $order->product_price,
            'CollectAmount' => $order->collect_amount,
            'JourneyType' => $order->type,
            'PickupType' => $order->pickup_type,
            'ServiceId' => $order->service_id,
            'Weight' => $order->weight,
            'Width' => $order->width,
            'Height' => $order->height,
            'Length' => $order->length,
            'Note' => $order->note,
            'NumberOfProducts' => $order->product_number,
            'SourceCity' => $order->source_province,
            'SourceDistrict' => $order->source_district,
            'SourceWard' => $order->source_ward,
            'SourceAddress' => implode(', ', [
                $order->source_address,
                $order->source_ward,
                $order->source_district,
                $order->source_province
            ]),
            'SourceName' => $order->source_name,
            'SourcePhoneNumber' => $order->source_phone,
            'DestCity' => $order->dest_province,
            'DestDistrict' => $order->dest_district,
            'DestWard' => $order->dest_ward,
            'DestAddress' => implode(', ', [
                $order->dest_address,
                $order->dest_ward,
                $order->dest_district,
                $order->dest_province
            ]),
            'DestName' => $order->dest_name,
            'DestPhoneNumber' => $order->dest_phone,
            'ReturnCity' => $order->return_province,
            'ReturnDistrict' => $order->return_district,
            'ReturnWard' => $order->return_ward,
            'ReturnAddress' => implode(', ', [
                $order->return_address,
                $order->return_ward,
                $order->return_district,
                $order->return_province
            ]),
            'ReturnName' => $order->return_name,
            'ReturnPhoneNumber' => $order->return_phone,
        ];

        $result = $this->bestExpressService->create($data);

        if ($result['Result'] == 1) {
            $order->status = OrderDeliveryUtility::STATUS_SUCCESS;
            $order->status_payment = OrderDeliveryUtility::STATUS_PAYMENT_NEW;
            $order->partner_code = $result['Code'];
            $order->save();
            return response([
                'result' => true,
                'data' => $order
            ]);
        } else {
            $order->status = OrderDeliveryUtility::STATUS_FAIL;
            $order->partner_message = $result['Message'];
            $order->save();
            return response([
                'result' => false,
                'message' => $result['Message'] ?? trans('Đơn hàng chuyển đi không thành công')
            ]);
        }
    }

    public function cancel($id)
    {
        $order = OrderDelivery::where('id', $id)->where('user_id', auth()->id())->first();
        if (!$order) {
            return response([
                'result' => false,
                'message' => trans('Không tìm thấy đơn hàng')
            ]);
        }
        $data = [
            'Code' => $order->partner_code
        ];
        $result = $this->bestExpressService->cancel($data);
        if ($result['Result'] == 1) {
            $order->status = OrderDeliveryUtility::STATUS_CANCEL;
            $order->status_payment = 0;
            $order->cancel_time = time();
            $order->save();
            return response([
                'result' => true,
                'message' => trans('Hủy đơn hàng thành công')
            ]);
        } else {
            return response([
                'result' => false,
                'message' => trans($result['Message'])
            ]);
        }
    }

    public function update($id, UpdateRequest $request)
    {
        $order = OrderDelivery::where('id', $id)->where('user_id', auth()->id())->first();
        $package_id = auth()->user()->customer_package_id;
        $package = CustomerPackage::find($package_id);
        $param = $request->only(['product_name', 'product_price', 'collect_amount', 'service_id', 'length', 'width', 'height', 'weight']);
        if (!$order) {
            return response([
                'result' => false,
                'message' => trans('Không tìm thấy đơn hàng')
            ]);
        }
        $data = [
            'Code' => $order->partner_code
        ];
        if (!empty($param['product_name'])) {
            $data['ProductName'] = $param['product_name'];
        }

        $customer_insurance_fee = 0;
        $product_price = isset($param['product_price']) ? (int)$param['product_price'] : $order->product_price;
        if($product_price > OrderDeliveryUtility::MIN_FEE){
            $customer_insurance_fee = (int)round(0.005 * $product_price, 0);
        }
        if (isset($param['product_price'])) {
            $data['ProductPrice'] = $product_price;
        }
        $customer_cod_fee = 0;
        $collect_amount = isset($param['collect_amount']) ? (int)$param['collect_amount'] : $order->collect_amount;
        if ($collect_amount > OrderDeliveryUtility::MIN_FEE) {
            $customer_cod_fee = (int)round(0.005 * $collect_amount, 0);
        }
        if (isset($param['collect_amount'])) {
            $data['CollectAmount'] = $collect_amount;
        }
        if (!empty($param['service_id'])) {
            $data['ServiceId'] = $param['service_id'];
        }
        if (isset($param['length'])) {
            $data['Length'] = (int)$param['length'];
        }
        if (isset($param['width'])) {
            $data['Width'] = (int)$param['width'];
        }
        if (isset($param['height'])) {
            $data['Height'] = (int)$param['height'];
        }
        if (isset($param['weight'])) {
            $data['Weight'] = (int)$param['weight'];
        }
        $weight = isset($param['weight']) ? (int)$param['weight'] : $order->weight;
        $width = isset($param['width']) ? (int)$param['width'] : $order->width;
        $height = isset($param['height']) ? (int)$param['height'] : $order->height;
        $length = isset($param['length']) ? (int)$param['length'] : $order->length;
        $fee_plus = $this->feePlus($weight, $width, $height, $length);
        $customer_delivery_fee = $fee_plus + $package->fee;
        $customer_total_fee = $customer_delivery_fee + $customer_insurance_fee + $customer_cod_fee;
        $result = $this->bestExpressService->update($data);
        if ($result['Result'] == 1) {
            /*$old_data = [];
            if($customer_delivery_fee != $order->customer_delivery_fee || $customer_insurance_fee != $order->customer_insurance_fee || $customer_cod_fee != $order->customer_cod_fee){
                $old_data = [
                    'product_price' => $order->product_price,
                    'collect_amount' => $order->collect_amount,
                    'weight' => $order->weight,
                    'width' => $order->width,
                    'height' => $order->height,
                    'length' => $order->length,
                ];
            }*/
            $customer_reason_fee = 0;
            if($customer_delivery_fee != $order->customer_delivery_fee){
                $customer_reason_fee = OrderDeliveryUtility::REASON_CUSTOMER_DELIVERY;
            }
            if($customer_insurance_fee != $order->customer_insurance_fee){
                $customer_reason_fee = OrderDeliveryUtility::REASON_CUSTOMER_INSURANCE;
            }
            if($customer_cod_fee != $order->customer_cod_fee){
                $customer_reason_fee = OrderDeliveryUtility::REASON_CUSTOMER_COD;
            }

            $order->customer_total_fee = $customer_total_fee;
            $order->customer_delivery_fee = $customer_delivery_fee;
            $order->customer_insurance_fee = $customer_insurance_fee;
            $order->customer_cod_fee = $customer_cod_fee;
            $order->customer_reason_fee = $customer_reason_fee;
            foreach ($param as $key => $value) {
                $order->$key = $value;
            }
            $order->save();
            return response([
                'result' => true,
                'message' => trans('Cập nhật thông tin đơn hàng thành công')
            ]);
        } else {
            return response([
                'result' => false,
                'message' => translate($result['Message'])
            ]);
        }
    }

    public function updateAddress($id, UpdateAddressRequest $request)
    {
        $order = OrderDelivery::where('id', $id)->where('user_id', auth()->id())->first();
        if (!$order) {
            return response([
                'result' => false,
                'message' => trans('Không tìm thấy đơn hàng')
            ]);
        }
        $data = [
            'Code' => $order->partner_code,
            'AddressTypeId' => $request->address_type,
            'CityName' => $request->province,
            'DistrictName' => $request->district,
            'WardName' => $request->ward,
            'Address' => implode(', ', [
                $request->address,
                $request->ward,
                $request->district,
                $request->province
            ]),
            'ContactName' => $request->name,
            'PhoneNumber1' => $request->phone,
        ];
        $result = $this->bestExpressService->updateAddress($data);
        if ($result['Result'] == 1) {
            switch ($request->address_type) {
                case OrderDeliveryUtility::ADDRESS_TYPE_SOURCE:
                    $order->source_province = $request->province;
                    $order->source_district = $request->district;
                    $order->source_ward = $request->ward;
                    $order->source_address = $request->address;
                    $order->source_phone = $request->phone;
                    $order->source_name = $request->name;
                    break;
                case OrderDeliveryUtility::ADDRESS_TYPE_DEST:
                    $order->dest_province = $request->province;
                    $order->dest_district = $request->district;
                    $order->dest_ward = $request->ward;
                    $order->dest_address = $request->address;
                    $order->dest_phone = $request->phone;
                    $order->dest_name = $request->name;
                    break;
                case OrderDeliveryUtility::ADDRESS_TYPE_RETURN:
                    $order->return_province = $request->province;
                    $order->return_district = $request->district;
                    $order->return_ward = $request->ward;
                    $order->return_address = $request->address;
                    $order->return_phone = $request->phone;
                    $order->return_name = $request->name;
                    break;
            }
            $order->save();
            return response([
                'result' => true,
                'message' => trans('Cập nhật địa chỉ đơn hàng thành công')
            ]);
        } else {
            return response([
                'result' => false,
                'message' => trans($result['Message'])
            ]);
        }

    }

    public function fee(GetFeeRequest $request)
    {
        $data = [
            'ServiceId' => $request->service_id,
            'Cod' => $request->collect_amount,
            'ProductPrice' => $request->product_price,
            'Weight' => $request->weight,
            'DestCity' => $request->dest_province,
            'DestDistrict' => $request->dest_district,
            'DestWard' => $request->dest_ward,
            'SourceCity' => $request->source_province,
            'SourceDistrict' => $request->source_district,
            'SourceWard' => $request->source_ward,
        ];
        $result = $this->bestExpressService->fee($data);
        if ($result['Result'] == 1) {
            return response([
                'result' => true,
                'data' => $result['data']
            ]);
        } else {
            return response([
                'result' => false,
                'message' => trans($result['Message'])
            ]);
        }
    }

    public function feeShop(GetFeeShopRequest $request)
    {
        $package_id = auth()->user()->customer_package_id;
        $package = CustomerPackage::find($package_id);
        $collect_amount = $request->collect_amount ?? 0;
        $product_price = $request->product_price ?? 0;
        $customer_insurance_fee = $customer_cod_fee = 0;
        if ($collect_amount > OrderDeliveryUtility::MIN_FEE) {
            $customer_cod_fee = (int)round(0.005 * $collect_amount, 0);
        }
        if($product_price > OrderDeliveryUtility::MIN_FEE){
            $customer_insurance_fee = (int)round(0.005 * $product_price, 0);
        }
        $weight = $request->weight ?? 0;
        $width = $request->width ?? 0;
        $height = $request->height ?? 0;
        $length = $request->length ?? 0;

        $fee_plus = $this->feePlus($weight, $width, $height, $length);
        $delivery_fee = $fee_plus + $package->fee;
        $total_fee = $delivery_fee + $customer_insurance_fee + $customer_cod_fee;
        return response([
            'result' => true,
            'data' => $total_fee
                /*[
                    'delivery_fee' => $delivery_fee,
                    'insurance_fee' => $customer_insurance_fee,
                    'cod_fee' => $customer_cod_fee,
                ]*/
        ]);
    }

    public function feePlus($weight, $width, $height, $length){
        $weight_2 = (int)(((int)($width/10) * (int)($height/10) * (int)($length/10)) / 6);
        $weight = ($weight >= $weight_2) ? $weight : $weight_2;
        $fee_plus = 0;
        if($weight > 5000){
            $wf = $weight - 5000;
            $fee_plus = ceil($wf/500)*4000;
        }
        return $fee_plus;
    }

    public function printBill(Request $request)
    {
        $ids = explode(',', $request->ids);
        $orders = OrderDelivery::whereIn('id', $ids)->where('user_id', auth()->id())->get();
        if($orders){
            $pdf = Pdf::loadView('pdf.order', ['orders' => $orders]);
            $id = $orders->pluck('id')->toArray();
            $sid = implode('-', $id);
            $path = 'public/pdf/orders/invoice-' . $sid . '.pdf';
            $pdf->save($path);
            $links = url('/') . '/' . $path;
            return response([
                'result' => true,
                'data' => $links
            ]);
        }

        return response([
            'result' => false,
            'message' => 'Không tìm thấy thông tin đơn hàng'
        ]);

        /*$result = $this->bestExpressService->printBill($data);
        if ($result['Result'] == 1) {
            return response([
                'result' => true,
                'data' => $result['Url']
            ]);
        } else {
            return response([
                'result' => false,
                'message' => trans($result['Message'])
            ]);
        }*/
    }

    public function report()
    {
        $count = OrderDelivery::where('user_id', auth()->id())->where('status', '>', 0)
            ->select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
        return response([
            'result' => true,
            'data' => $count
        ]);
    }

    public function reportMonthly(Request $request)
    {
        $dataTotal = [
            'total_pending' => 0,
            'count_pending' => 0,
            'total_confirm' => 0,
            'count_confirm' => 0,
            'total_success' => 0,
            'count_success' => 0,
            /*'total_not_pay' => 0,
            'count_not_pay' => 0,*/
        ];
        $data = [];
        $orders = OrderDelivery::where('user_id', auth()->id())->where('month', $request->month)->where('year', $request->year)->orderBy('day', 'ASC')->get();
        foreach ($orders as $order) {
            if (!isset($data[$order->day])) {
                $data[$order->day] = [
                    'total_pending' => 0,
                    'total_confirm' => 0,
                    'total_success' => 0,
                    /*'total_not_pay' => 0*/
                ];
            }
            switch ($order->status_payment) {
                case OrderDeliveryUtility::STATUS_PAYMENT_NEW:
                case OrderDeliveryUtility::STATUS_PAYMENT_PENDING:
                    $data[$order->day]['total_pending'] += ($order->collect_amount - $order->customer_total_fee);
                    $dataTotal['total_pending'] += ($order->collect_amount - $order->customer_total_fee);
                    $dataTotal['count_pending']++;
                    break;
                case OrderDeliveryUtility::STATUS_PAYMENT_CONFIRM:
                    $data[$order->day]['total_confirm'] += ($order->collect_amount - $order->customer_total_fee);
                    $dataTotal['total_confirm'] += ($order->collect_amount - $order->customer_total_fee);
                    $dataTotal['count_confirm']++;
                    break;
                case OrderDeliveryUtility::STATUS_PAYMENT_SUCCESS:
                    $data[$order->day]['total_confirm'] += ($order->collect_amount - $order->customer_total_fee);
                    $dataTotal['total_success'] += ($order->collect_amount - $order->customer_total_fee);
                    $dataTotal['count_success']++;
                    break;
                /*case OrderDeliveryUtility::STATUS_NOT_PAYMENT:
                    $data[$order->day]['total_not_pay'] += ($order->collect_amount - $order->customer_total_fee);
                    $dataTotal['total_not_pay'] += ($order->collect_amount - $order->customer_total_fee);
                    $dataTotal['count_not_pay'] ++;
                    break;*/
            }
        }
        return response([
            'result' => true,
            'data' => [
                'total' => $dataTotal,
                'daily' => $data
            ]
        ]);
    }



}
