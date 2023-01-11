<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index(Request $request){
        $sort_search = null;
        $banners = Banner::orderBy('created_at', 'desc');

        if ($request->search != null){
            $banners = $banners->where('name', 'like', '%'.$request->search.'%');
            $sort_search = $request->search;
        }

        $banners = $banners->paginate(15);
        $aryType = [
            'link' => trans('Link'),
            'content' => trans('Content'),
        ];

        return view('backend.marketing.banner.index', compact('banners','sort_search', 'aryType'));
    }

    public function change_status(Request $request) {
        $banner = Banner::find($request->id);
        $banner->status = $request->status;
        $banner->save();
        return 1;
    }

    public function create(){
        return view('backend.marketing.banner.create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            //'image' => 'required',
            'date_range' => 'required'
        ]);
        $banner = new Banner();
        $banner->name = $request->name;
        $banner->image = $request->image;
        $banner->type = $request->type??'link';
        $banner->link = $request->link;
        $banner->subject = $request->subject;
        $banner->content = $request->content;
        $banner->status = 1;
        $date_var               = explode(" to ", $request->date_range);
        $banner->start_time = strtotime($date_var[0]);
        $banner->end_time   = strtotime( $date_var[1]);
        $banner->save();

        flash('Banner đã được thêm mới thành công')->success();
        return redirect()->route('banner.index');
    }

    public function edit($id){
        $banner = Banner::find($id);

        return view('backend.marketing.banner.edit', compact('banner'));
    }

    public function update($id, Request $request){
        $request->validate([
            'name' => 'required',
            //'image' => 'required',
            'date_range' => 'required'
        ]);
        $banner = Banner::find($id);
        $banner->name = $request->name;
        $banner->image = $request->image;
        $banner->type = $request->type??'link';
        $banner->link = $request->link;
        $banner->subject = $request->subject;
        $banner->content = $request->content;
        $banner->status = 1;
        $date_var               = explode(" to ", $request->date_range);
        $banner->start_time = strtotime($date_var[0]);
        $banner->end_time   = strtotime( $date_var[1]);
        $banner->save();

        flash('Banner đã được cập nhật thành công')->success();
        return redirect()->route('banner.index');
    }

    public function delete(){

    }

    public function destroy($id)
    {
        Banner::find($id)->delete();
        flash('Banner đã được xóa thành công')->info();
        return redirect()->route('banner.index');
    }
}
