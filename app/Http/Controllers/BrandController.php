<?php

namespace App\Http\Controllers;

use App\Models\WarrantyCard;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\BrandTranslation;
use App\Models\Product;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search =null;
        $brands = Brand::orderBy('name', 'asc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $brands = $brands->where('name', 'like', '%'.$sort_search.'%');
        }
        $brands = $brands->paginate(15);
        return view('backend.product.brands.index', compact('brands', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'code'=>'required|unique:brands'
        ],[
            'name.required'=>'Trường tên là bắt buộc',
            'code.required'=>'Trường code là bắt buộc',
            'code.unique'=>'code đã tồn tại',

        ]);

        $brand = new Brand;
        $brand->name = $request->name;
        $brand->meta_title = $request->meta_title;
        $brand->meta_description = $request->meta_description;
        if ($request->slug != null) {
            $brand->slug = str_replace(' ', '-', $request->slug);
        }
        else {
            $brand->slug = makeSlug($request->name);
        }
        $brand->code= $request->code;
        $brand->logo = $request->logo;

        $brand->save();

        $brand_translation = BrandTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'brand_id' => $brand->id]);
        $brand_translation->name = $request->name;
        $brand_translation->save();

        flash(translate('Hãng sản xuất đã được tạo thành công'))->success();
        return redirect()->route('brands.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $lang   = $request->lang;
        $brand  = Brand::findOrFail($id);
        return view('backend.product.brands.edit', compact('brand','lang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            'name'=>'required',
            'code'=>'required|unique:brands,code,'.$id
        ],[
            'name.required'=>'Trường tên là bắt buộc',
            'code.required'=>'Trường code là bắt buộc',
            'code.unique'=>'code đã tồn tại',

        ]);
        $brand = Brand::findOrFail($id);

        if($request->lang == env("DEFAULT_LANGUAGE")){
            $brand->name = $request->name;
        }
        $brand->meta_title = $request->meta_title;
        $brand->meta_description = $request->meta_description;
        if ($request->slug != null) {
            $brand->slug = strtolower($request->slug);
        } else {
            $brand->slug = makeSlug($request->name);
        }
        $brand->code=$request->code;
//            preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $request->name)).Str::random(5);
        $brand->logo = $request->logo;
        $brand->save();

        $brand_translation = BrandTranslation::firstOrNew(['lang' => $request->lang, 'brand_id' => $brand->id]);
        $brand_translation->name = $request->name;
        $brand_translation->save();

        flash(translate('Hãng sản xuất đã được cập nhật thành công'))->success();
        return back();

    }


    function updateStatus(Request $request){
        $brand=Brand::find($request->id);
        if($brand!=null){
            $brand->status= $request->status;
            $brand->save();
            return response([
                'result'=>1,
            ]);
        }else{
            return response([
                'result'=>0,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        Product::where('brand_id', $brand->id)->delete();
        WarrantyCard::where('brand_id', $brand->id)->delete();
        foreach ($brand->brand_translations as $key => $brand_translation) {
            $brand_translation->delete();
        }

        Brand::destroy($id);
        flash(translate('Hãng sản xuất đã bị xóa thành công'))->success();
        return redirect()->route('brands.index');

    }
}
