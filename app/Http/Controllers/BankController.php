<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index()
    {
        $banks = Bank::orderBy('created_at','DESC')->get();
        return view('backend.bank.index', compact('banks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.bank.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bank = new Bank();
        $bank->name = $request->name;
        $bank->icon = $request->icon;
        /*$page->type = "custom_page";
        $page->content = $request->content;
        $page->slug = \Str::slug($request->title);*/
        /* $page->meta_title       = $request->meta_title;
         $page->meta_description = $request->meta_description;
         $page->keywords         = $request->keywords;
         $page->meta_image       = $request->meta_image;*/
        $bank->save();

        /*$page_translation = PageTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'page_id' => $page->id]);
        $page_translation->title = $request->title;
        $page_translation->content = $request->content;
        $page_translation->save();*/

        flash(translate('Thêm mới ngân hàng thành công'))->success();
        return redirect()->route('banks.index');
        /*if (Page::where('slug', preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug)))->first() == null) {
            $page->slug             = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));

        }*/

        /*flash(translate('Slug has been used already'))->warning();
        return back();*/
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
        /*$lang = $request->lang;*/
        //$page_name = $request->page;
        $bank = Bank::where('id', $id)->first();
        /*if($page != null){
          if ($page_name == 'home') {
            return view('backend.website_settings.pages.home_page_edit', compact('page','lang'));
          }
          else{

          }
        }*/
        return view('backend.bank.edit', compact('bank'));
        //abort(404);
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
        $bank = Bank::findOrFail($id);
        /*if (Page::where('id','!=', $id)->where('slug', preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug)))->first() == null) {

        }*/
        /*if($page->type == 'custom_page'){
            $page->slug           = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        }*/
        $bank->name = $request->name;
        //$page->content = $request->content;
        $bank->icon = $request->icon;
        //$page->slug = \Str::slug($request->title);
        /*$page->meta_title       = $request->meta_title;
        $page->meta_description = $request->meta_description;
        $page->keywords         = $request->keywords;
        $page->meta_image       = $request->meta_image;*/
        $bank->save();

        /*$page_translation = PageTranslation::firstOrNew(['lang' => $request->lang, 'page_id' => $page->id]);
        $page_translation->title = $request->title;
        $page_translation->content = $request->content;
        $page_translation->save();*/

        flash(translate('Cập nhật  thông tin ngân hàng thành công'))->success();
        return redirect()->route('banks.index');

        /*flash(translate('Slug has been used already'))->warning();
        return back();*/

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $page = Bank::findOrFail($id);
        /*foreach ($page->page_translations as $key => $page_translation) {
            $page_translation->delete();
        }*/
        if (Bank::destroy($id)) {
            flash(translate('Xóa ngân hàng thành công'))->success();
            return redirect()->back();
        }
        return back();
    }
}
