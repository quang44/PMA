@extends('backend.layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">{{ translate('Chi tiết đơn hàng') }}</h1>
        </div>
        <div class="card-body">
            <div class="gutters-5">
                <div><h6>Thông tin cơ bản</h6></div>
                <div class="mt-1">
                    <span><b>{{ translate('Mã đơn hàng') }}</b>: {{ $order->id }}</span>
                </div>
                <div class="mt-1">
                    <span><b>{{ translate('Mã vận đơn') }}</b>: {{ $order->partner_code }}</span>
                </div>
                <div class="mt-1">
                    <span><b>{{ translate('Mã đối soát khách hàng') }}</b>: {{ !empty($order->bill_id) ? $order->bill_id : '' }}</span>
                </div>
                <div class="mt-1">
                    <span><b>{{ translate('Mã đối soát giao vận') }}</b>: </span>
                </div>
                <div class="mt-1">
                    <span><b>{{ translate('Loại đơn hàng') }}</b>: {{ \App\Utility\OrderDeliveryUtility::$aryType[$order->type] }}</span>
                </div>
                <div class="mt-1">
                    <span><b>{{ translate('Hình thức lấy hàng') }}</b>: {{ \App\Utility\OrderDeliveryUtility::$aryPickUpType[$order->pickup_type] ?? '' }}</span>
                </div>
                <div class="mt-1">
                    <span><b>{{ translate('Thời gian tạo đơn hàng') }}</b>: {{ date('d-m-Y H:i:s', $order->created_time) }}</span>
                </div>
                <div class="mt-1">
                    <span><b>{{ translate('Tình trạng giao hàng') }}</b>: {{ $status_delivery[$order->status] ?? '' }}</span>
                </div>
                <div class="mt-1">
                    <span><b>{{ translate('Tình trạng thanh toán khách hàng ') }}</b>: {{ $status_payment[$order->status_payment] ?? '' }}</span>
                </div>
                <div class="mt-1">
                    <span><b>{{ translate('Tình trạng thanh toán đối tác ') }}</b>: {{ $partner_status_payment[$order->partner_status_payment] ?? '' }}</span>
                </div>
            </div>
            <div class="row gutters-5 mt-3">
                <div class="col-md-4 ml-auto">
                    <div><h6>Thông tin lấy hàng</h6></div>
                    <div>
                        <span>{{ translate('Tên') }}: {{ $order->source_name }}</span>
                    </div>
                    <div>
                        <span>{{ translate('Số điện thoại') }}: {{ $order->source_phone }}</span>
                    </div>
                    <div>
                        <span>{{ translate('Địa chỉ') }}: {{ $order->source_address . ', ' . $order->source_ward . ', ' . $order->source_district . ', ' . $order->source_province }}</span>
                    </div>
                </div>
                <div class="col-md-4 ml-auto">
                    <div><h6>Thông tin giao hàng</h6></div>
                    <div>
                        <span>{{ translate('Tên') }}: {{ $order->dest_name }}</span>
                    </div>
                    <div>
                        <span>{{ translate('Số điện thoại') }}: {{ $order->dest_phone }}</span>
                    </div>
                    <div>
                        <span>{{ translate('Địa chỉ') }}: {{ $order->dest_address . ', ' . $order->dest_ward . ', ' . $order->dest_district . ', ' . $order->dest_province }}</span>
                    </div>
                </div>
                <div class="col-md-4 ml-auto">
                    <div><h6>Thông tin hoàn hàng</h6></div>
                    <div>
                        <span>{{ translate('Tên') }}: {{ $order->return_name }}</span>
                    </div>
                    <div>
                        <span>{{ translate('Số điện thoại') }}: {{ $order->return_phone }}</span>
                    </div>
                    <div>
                        <span>{{ translate('Địa chỉ') }}: {{ $order->return_address . ', ' . $order->return_ward . ', ' . $order->return_district . ', ' . $order->return_province }}</span>
                    </div>
                </div>
            </div>
            <div class="row gutters-5 mt-3">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th class="text-right">Giá trị gói hàng</th>
                            <th class="text-right">Tiền thu hô</th>
                            <th class="text-right">Phí vận chuyển</th>
                            <th class="text-right">Phí bảo hiểm</th>
                            <th class="text-right">Phí hoàn</th>
                            <th class="text-right">Phí thu hộ</th>
                            <th class="text-right">Tổng phí</th>
                            <th class="text-right">Tổng phí thu khách</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="text-right">
                            {{ single_price($order->product_price) }}
                        </td>
                        <td class="text-right">
                            {{ single_price($order->collect_amount) }}
                        </td>
                        <td class="text-right">
                            {{ single_price($order->delivery_fee) }}
                        </td>
                        <td class="text-right">
                            {{ single_price($order->insurance_fee) }}
                        </td>
                        <td class="text-right">
                            {{ single_price($order->return_fee) }}
                        </td>
                        <td class="text-right">
                            {{ single_price($order->cod_fee) }}
                        </td>
                        <td class="text-right">
                            {{ single_price($order->total_fee) }}
                        </td>
                        <td class="text-right">
                            {{ single_price($order->customer_total_fee) }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="row gutters-5 mt-3">
                <div><h6>Lịch sử giao hàng</h6></div>
                <table class="table aiz-table mb-0">
                    <thead>
                    <tr>
                        <th>
                            Thời gian
                        </th>
                        <th>
                            Mô tả
                        </th>
                        <th>
                            Ghi chú
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($history as $value)
                            <tr>
                                <td>
                                    {{ date('d-m-Y H:i:s', $value->created_time) }}
                                </td>
                                <td>
                                    {{ $value->status_description }}
                                </td>
                                <td>
                                    {{ $value->status_content }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">

    </script>
@endsection
