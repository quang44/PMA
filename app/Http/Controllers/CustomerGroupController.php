<?php

namespace App\Http\Controllers;

use App\Models\CustomerGroup;
use App\Models\CustomerPackage;
use Illuminate\Http\Request;

class CustomerGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer_groups = CustomerGroup::all();
        return view('backend.customer.customer_groups.index', compact('customer_groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.customer.customer_groups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $customer_group = new CustomerGroup();
        $customer_group->name = $request->name;
        $customer_group->avatar = $request->avatar;
        $customer_group->bonus = $request->bonus;
        $customer_group->description = $request->description;
        $customer_group->default = 0;
        $customer_group->status = 0;
        $customer_group->save();
        flash(translate('Nhóm người dùng đã thêm thành công !'))->success();
        return redirect()->route('customer_groups.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $customer_group = CustomerGroup::findOrFail(decrypt($id));
        return view('backend.customer.customer_groups.edit', compact('customer_group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $customer_group = CustomerGroup::findOrFail($id);
        $customer_group->name = $request->name;
        $customer_group->avatar = $request->avatar;
        $customer_group->bonus = $request->bonus;
        $customer_group->description = $request->description;
        $customer_group->save();

        flash(translate('Nhóm người dùng đã được cập nhật'))->success();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer_group = CustomerGroup::findOrFail($id);
        $customer_group->delete();
        flash(translate('Nhóm người dùng đã được xóa !'))->success();
        return redirect()->route('customer_groups.index');
    }

    public function setup_hidden(Request $request){
        $customer_group = CustomerGroup::findOrFail($request->id);
        if($customer_group->default !=0){
            return 0;
        }
        $customer_group->status = (int) $request->status;
        if($customer_group->save()){
            flash(translate('Trạng thái nhóm người dùng đã cập nhật !'))->success();
            return 1;
        }
        return 0;
    }

    public function setup_default(Request $request){

        $customer_group = CustomerGroup::findOrFail($request->id);
        if($customer_group->status != 0){
            return 0;
        }else{
            if($request->status == 1){
                CustomerGroup::where('id', '>', 0)->update(['default' => 0]);
            }
        }
        $customer_group->default = (int) $request->status;
        if($customer_group->save()){
            flash(translate('Nhóm người dùng đã được cài mặc định !'))->success();
            return 1;
        }
        return 0;
    }

    public function config(){
        return view('backend.customer.affiliate.con', compact('customer_group'));
    }
}
