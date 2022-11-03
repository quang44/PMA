<?php

    namespace App\Http\Controllers\Api\V2;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Api\V2\WarrantyCard\WarrantyCardRequest;
    use App\Http\Resources\V2\WarrantyCard as WarrantyCardCollection;
    use App\Models\Upload;
    use App\Utility\CustomerBillUtility;
    use Illuminate\Support\Facades\File;
    use App\Models\WarrantyCard;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    class WarrantyCardController extends Controller
    {
        function index()
        {
            $warrantyCard = WarrantyCard::with('brand', 'uploads')
                ->where('user_id', auth()->id())->latest()->paginate(15);
            return new WarrantyCardCollection($warrantyCard);
        }

        function search(Request $request)
        {
            $warranty = WarrantyCard::with('brand', 'uploads')
                ->where('user_name', 'like', '%' . $request->user_name . '%')
                ->get();
            return new WarrantyCardCollection($warranty);
        }

        function show($id)
        {
            $warranty = WarrantyCard::where('id', $id)->get();
            return new WarrantyCardCollection($warranty);
        }

        function destroy($id)
        {
            $WarrantyCard = WarrantyCard::findOrFail($id);
            $WarrantyCard->reason = "Khách hàng tự hủy yêu cầu";
            $WarrantyCard->status = 2;
            $WarrantyCard->save();

//        $uploads=Upload::where('object_id',$WarrantyCard->id)->get();
//        foreach ($uploads as $key=>$upload){
//            if (file_exists(base_path('public/').$upload->file_name)) {
//                unlink(base_path('public/') . $upload->file_name);
//            }
//            $upload->delete();
//        }

//        $WarrantyCard->delete();

            return [
                'result' => true,
                'message' => "Thẻ của bạn đã được hủy thành công"
            ];
        }


// i test req from FormData in javascript
        function store(WarrantyCardRequest $request)
        {

            $warrantyCard = new WarrantyCard;
            $warrantyCard->user_id = auth()->user()->id;
            $warrantyCard->user_name = $request->user_name;
            $warrantyCard->address = $request->address;
            $warrantyCard->seri = $request->seri;
            $warrantyCard->brand_id = $request->brand;
            $warrantyCard->active_time = null;
            $warrantyCard->note = $request->note;
            $warrantyCard->save();
            $id = $warrantyCard->id;
            uploadMultipleImage($request->image, $id, $path = 'uploads/warranty');

            NewNotification([
                'type' => CustomerBillUtility::TYPE_NOTIFICATION_WARRANTY,
                'data' => '$content',
                'user_id' => auth()->user()->id,
                'amount_first' => 0,
                'amount_later' => 0,
                'accept_by' => 0,
                'notifiable_type' => CustomerBillUtility::TYPE_NOTIFICATION_USER,
            ]);
            return response([
                'result' => true,
                'message' => 'Thẻ bảo hành của bạ đã được tạo thành công'
            ]);
        }


        function update(Request $request, $id)
        {
            $warrantyCard = WarrantyCard::with('uploads')->findOrFail($id);
            $validate = Validator::make($request->all(), [
                'user_name' => 'required|max:255',
                'address' => 'required',
                'seri' => 'required|numeric|unique:warranty_cards,seri,' . $warrantyCard,
                'brand' => 'required',
            ]);
            if ($validate->failed()) {
                return response([
                    'result' => false,
                    'message' => $validate->errors()
                ]);
            }

            $warrantyCard->user_id = auth()->user()->id;
            $warrantyCard->user_name = $request->user_name;
            $warrantyCard->address = $request->address;
            $warrantyCard->seri = $request->seri == null ? $warrantyCard->seri : $request->seri;
            $warrantyCard->brand_id = $request->brand==null? $warrantyCard->brand_id:$request->brand;
            $warrantyCard->active_time = null;
            $warrantyCard->status = 0;
            $warrantyCard->reason='';
            $warrantyCard->save();
            $id = $warrantyCard->id;
            if ($request->image && $request->image != null) {
                foreach ($warrantyCard->uploads as $upload) {
                    if (file_exists(public_path('') . $upload->file_name)) {
                        unlink(public_path('') . $upload->file_name);
                    }
                    $upload->delete();
                }
                uploadMultipleImage($request->image, $id, $path = 'uploads/warranty');

            }


            return response([
                'result' => true,
                'message' => 'Thẻ bảo hành của bạ đẫ được cập nhật'
            ]);
        }

    }
