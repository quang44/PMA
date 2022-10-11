<?php

namespace App\Http\Controllers;

use App\Models\CustomerGroup;
use App\Models\CustomerPackage;
use App\Models\CustomerPackageTranslation;
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
        //
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
        $customer_group->full_name = $request->full_name;
        $customer_group->avatar = $request->avatar;
        $customer_group->bonus = $request->bonus;
        $customer_group->save();
        flash(translate('Customer group has been inserted successfully'))->success();
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

        $customer_group->full_name = $request->full_name;
        $customer_group->avatar = $request->avatar;
        $customer_group->bonus = $request->bonus;
        $customer_group->save();

        flash(translate('Customer group has been updated successfully'))->success();
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
        CustomerPackage::destroy($id);

        flash(translate('Customer group has been deleted successfully'))->success();
        return redirect()->route('customer_groups.index');
    }
}
