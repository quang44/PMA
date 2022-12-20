<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\CustomerResource;
use App\Models\Address;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
class CustomerController extends Controller
{

    function index(Request $request)
    {
    $customer= User::query()
        ->select('name','id','email','phone','created_at','updated_at')
        ->where('user_type','customer');

        if($request->search){
            $customer=$customer->where('name',"%$request->search%");
        }
        $customer= $customer->paginate($request->limit??15);
        $customer=$customer->makeHidden(['created_at','updated_at']);


        return $this->sendSuccess($customer);
    }


    public function show($id)
    {
        $customer= User::query()
            ->select(['id','name','email','phone'])
            ->findOrFail($id);

        return $this->sendSuccess($customer);
    }

    function getAgent($id)
    {
        $result = true;
        $data = User::query()->where('user_type', 'employee')->where('provider_id', $id)->get();
        if (count($data) <= 0) {
            $result = false;
        }
        return response()->json([
            'result'=>$result,
            'data'=>$data
        ]);
    }
    function getAddressAgent($id){
        $result=true;
        $data=Address::where('user_id',$id)->with('district','province')->get();
        if(count($data)<=0){
            $result=false;
        }
        return response()->json([
            'result'=>$result,
            'data'=>$data
        ]);
    }
}
