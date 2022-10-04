<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\PageTranslation;


class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = News::orderBy('created_at', 'desc')->get();
        return view('backend.news.index', compact('news'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.news.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $news = new News();
        $news->title = $request->title;
        $news->icon = $request->icon;
        $news->images = $request->images;
        $news->content = $request->content;
        $news->link = $request->link;
        $news->slug = \Str::slug($request->title);
        /* $page->meta_title       = $request->meta_title;
         $page->meta_description = $request->meta_description;
         $page->keywords         = $request->keywords;
         $page->meta_image       = $request->meta_image;*/
        $news->save();

        /*$page_translation = PageTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'page_id' => $page->id]);
        $page_translation->title = $request->title;
        $page_translation->content = $request->content;
        $page_translation->save();*/

        flash(translate('News has been created successfully'))->success();
        return redirect()->route('news.index');
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
        $news = News::where('id', $id)->first();
        /*if($page != null){
          if ($page_name == 'home') {
            return view('backend.website_settings.pages.home_page_edit', compact('page','lang'));
          }
          else{

          }
        }*/
        return view('backend.news.edit', compact('news'));
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
        $news = News::findOrFail($id);
        /*if (Page::where('id','!=', $id)->where('slug', preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug)))->first() == null) {

        }*/
        /*if($page->type == 'custom_page'){
            $page->slug           = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        }*/
        $news->title = $request->title;
        $news->content = $request->content;
        $news->link = $request->link;
        $news->images = $request->images;
        $news->icon = $request->icon;
        $news->slug = \Str::slug($request->title);
        /*$page->meta_title       = $request->meta_title;
        $page->meta_description = $request->meta_description;
        $page->keywords         = $request->keywords;
        $page->meta_image       = $request->meta_image;*/
        $news->save();

        /*$page_translation = PageTranslation::firstOrNew(['lang' => $request->lang, 'page_id' => $page->id]);
        $page_translation->title = $request->title;
        $page_translation->content = $request->content;
        $page_translation->save();*/

        flash(translate('News has been updated successfully'))->success();
        return redirect()->route('news.index');

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
        $news = News::findOrFail($id);

        if ($news::destroy($id)) {
            flash(translate('News has been deleted successfully'))->success();
            return redirect()->back();
        }
        return back();
    }

}
