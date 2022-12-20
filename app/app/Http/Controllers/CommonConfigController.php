<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommonConfigRequest;
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
        $common_configs = CommonConfig::all();
        if($common_configs !=null){
            return redirect()->back();
        }
        return view('backend.setup_configurations.common_configuration.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommonConfigRequest $request)
    {
        $common_config = new CommonConfig();
        $common_config ->logo = $request->logo;
        $common_config-> unit = $request->unit;
        $common_config->for_referrer = $request->for_referrer;
        $common_config->for_activator = $request->for_activator;
        $common_config->contact_info = $request->contact_info;
        $common_config ->exchange = $request->exchange;
        $common_config ->rules = $request->rules;
        $common_config->save();
        flash(translate('Cấu hình chung đã được thiết lập !'))->success();
        return redirect()->route('common_configs.index');
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
    public function edit($id)
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
    public function update(CommonConfigRequest $request,  $id)
    {
        $common_config = CommonConfig::findOrFail($id);
        $common_config ->logo = $request->logo;
        $common_config-> unit = $request->unit;
        $common_config->for_referrer = $request->for_referrer;
        $common_config->for_activator = $request->for_activator;
        $common_config->contact_info = $request->contact_info;
        $common_config ->exchange = $request->exchange;
        $common_config ->rules = $request->rules;
        $common_config->save();
        flash(translate('Cấu hình chung đã được thiết lập !'))->success();
        return redirect()->route('common_configs.index');
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
