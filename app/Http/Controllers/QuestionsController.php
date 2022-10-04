<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\PageTranslation;


class QuestionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = Question::orderBy('created_at', 'desc')->get();
        return view('backend.questions.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.questions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $question = new Question();
        $question->question = $request->question;
        $question->type = $request->type;
        $question->answer = $request->answer;
        $question->priority = (int)$request->priority;
        /* $page->meta_title       = $request->meta_title;
         $page->meta_description = $request->meta_description;
         $page->keywords         = $request->keywords;
         $page->meta_image       = $request->meta_image;*/
        $question->save();
        /*$page_translation = PageTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'page_id' => $page->id]);
        $page_translation->title = $request->title;
        $page_translation->content = $request->content;
        $page_translation->save();*/

        flash(translate('Question has been created successfully'))->success();
        return redirect()->route('questions.index');
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
        $question = Question::where('id', $id)->first();
        /*if($page != null){
          if ($page_name == 'home') {
            return view('backend.website_settings.pages.home_page_edit', compact('page','lang'));
          }
          else{

          }
        }*/
        return view('backend.questions.edit', compact('question'));
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
        $question = Question::findOrFail($id);
        /*if (Page::where('id','!=', $id)->where('slug', preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug)))->first() == null) {

        }*/
        /*if($page->type == 'custom_page'){
            $page->slug           = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        }*/
        $question->question = $request->question;
        $question->type = $request->type;
        $question->answer = $request->answer;
        $question->priority = (int)$request->priority;
        /*$page->meta_title       = $request->meta_title;
        $page->meta_description = $request->meta_description;
        $page->keywords         = $request->keywords;
        $page->meta_image       = $request->meta_image;*/
        $question->save();

        /*$page_translation = PageTranslation::firstOrNew(['lang' => $request->lang, 'page_id' => $page->id]);
        $page_translation->title = $request->title;
        $page_translation->content = $request->content;
        $page_translation->save();*/

        flash(translate('News has been updated successfully'))->success();
        return redirect()->route('questions.index');

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
        $question = Question::findOrFail($id);

        if ($question::destroy($id)) {
            flash(translate('Question has been deleted successfully'))->success();
            return redirect()->back();
        }
        return back();
    }

}
