<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Popup;
use Illuminate\Http\Request;

class PopupController extends Controller
{
    public function index(Request $request){
        //$sort_search = null;
        $popups = Popup::orderBy('created_at', 'desc');

        /*if ($request->search != null){
            $banners = $banners->where('name', 'like', '%'.$request->search.'%');
            $sort_search = $request->search;
        }*/

        $popups = $popups->paginate(15);
        /*$aryType = [
            'link' => trans('Link'),
            'content' => trans('Content'),
        ];*/

        return view('backend.marketing.popup.index', compact('popups'));
    }

    public function change_status(Request $request) {
        $popup = Popup::find($request->id);
        $popup->status = $request->status;
        $popup->save();
        return 1;
    }

    public function create(){
        return view('backend.marketing.popup.create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'image' => 'required',
            'link' => 'required',
            'date_range' => 'required_if:type,all_user',
            'day' => 'required_if:type,new_user',
        ]);
        $popup = new Popup();
        $popup->name = $request->name;
        $popup->image = $request->image;
        $popup->type = $request->type;
        $popup->link = $request->link;
        //$banner->content = $request->content;
        $popup->status = 1;
        if($popup->type == 'new_user'){
            $popup->day   = (int)$request->day;
        }else{
            $date_var               = explode(" to ", $request->date_range);
            $popup->start_time = strtotime($date_var[0]);
            $popup->end_time   = strtotime( $date_var[1]);
        }
        /*if($popup->type == 'all_user'){

        }*/
        $popup->save();

        flash(translate('Popup has been created successfully'))->success();
        return redirect()->route('popup.index');
    }

    public function edit($id){
        $popup = Popup::find($id);

        return view('backend.marketing.popup.edit', compact('popup'));
    }

    public function update($id, Request $request){
        $request->validate([
            'name' => 'required',
            //'image' => 'required',
            'link' => 'required',
            'date_range' => 'required_if:type,all_user',
            'day' => 'required_if:type,new_user',
        ]);
        $popup = Popup::find($id);
        $popup->name = $request->name;
        $popup->image = $request->image;
        $popup->type = $request->type;
        $popup->link = $request->link;
        //$banner->content = $request->content;
        $popup->status = 1;
        if($popup->type == 'new_user'){
            $popup->day   = (int)$request->day;
        }else{
            $date_var               = explode(" to ", $request->date_range);
            $popup->start_time = strtotime($date_var[0]);
            $popup->end_time   = strtotime( $date_var[1]);
        }
        /*if($popup->type == 'all_user'){

        }*/
        $popup->save();

        flash(translate('Popup has been updated successfully'))->success();
        return redirect()->route('popup.index');
    }

    public function delete(){

    }

    public function destroy($id)
    {
        Popup::find($id)->delete();

        return redirect()->route('popup.index');
    }
}
