<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductWarrantyController extends Controller
{
    //
    function index(Request $request){
        $sort_search=null;
        $sort_status=null;
        $type=null;
        $products=Product::query()->where('wholesale_product',1);
        if(!empty($request->search)){
            $sort_search  =$request->search;
            $products=$products->where('name',$sort_search);
        }
        $products=  $products->latest()->paginate(15);
        return view('backend.product.product_warranty.index',compact('products','type','sort_search'));
    }


    function create(){
        return view('backend.product.product_warranty.create');
    }


    function store(Request $request){
        $product=new Product;
        $product->name=$request->name;
        $product->unit=$request->unit;
        $product->thumbnail_img=$request->thumbnail_img;
        $product->wholesale_product=1;
        $product->save();
        flash('Sản phẩm đã được thêm thành công')->success();
        return redirect()-> route('product_warranty.index');
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

    function edit($id){
        $product=Product::findOrFail(decrypt($id));
        return view('backend.product.product_warranty.edit',compact('product'));
    }



    function update(Request $request, $id){
        $product=Product::findOrFail($id);
        $product->name=$request->name;
        $product->unit=$request->unit;
        $product->thumbnail_img=$request->thumbnail_img;
        $product->wholesale_product=1;
        $product->save();
        flash('Sản phẩm đã được sửa thành công')->success();
        return redirect()->route('product_warranty.index');
    }

    function destroy($id){
        Product::findOrFail($id)->delete();
        flash('Xóa sản phẩm thành công')->success();
        return redirect()->route('product_warranty.index');
    }
}
