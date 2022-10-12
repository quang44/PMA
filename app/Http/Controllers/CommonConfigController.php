<?php

namespace App\Http\Controllers;

use App\Models\c;
use App\Models\CommonConfig;
use Illuminate\Http\Request;

class CommonConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $common_configs = CommonConfig::all();
        return view('backend.setup_configurations.common_configuration.index', compact('common_configs'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $common_config = CommonConfig::findOrFail(decrypt($id));
        return view('backend.setup_configurations.common_configuration.edit', compact('common_config'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $common_config = CommonConfig::findOrFail($id);
        $common_config ->logo = $request->logo;
        $common_config-> unit = $request->unit;
        $common_config->for_referrer = $request->for_referrer;
        $common_config->for_activator = $request->for_activator;
        $common_config->contact_info = $request->contact_info;
        $common_config->save();
        flash(translate('Common Config has been updated successfully'))->success();
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
        //
    }
}
