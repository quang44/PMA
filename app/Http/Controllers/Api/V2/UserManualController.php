<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\V2\NewsCollection;
use App\Models\News;
use App\Models\Page;
use App\Models\Question;
use App\Models\UserManual;
use Illuminate\Http\Request;

class UserManualController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->type ?? 'customer';
        if($type == 'customer' || $type == 'kol'){
            $question = Question::where('type', $type)->orderBy('priority', 'asc')->get();
            //$manual->url_file = uploaded_asset($manual->file);
            return response([
                'result' => true,
                'data' => $question,
            ]);
        }
        $manual = UserManual::where('type', $type)->first();
        $manual->url_file = uploaded_asset($manual->file);
        return response([
            'result' => true,
            'data' => $manual,
        ]);
        /*$data = [];
        foreach ($pages as $page) {
            $data[] = [
                'id' => $page->id,
                'title' => $page->title,
                'icon' => uploaded_asset($page->icon),
                //'url' => route('home').'/'. $page->slug
            ];
        }
        return response([
            'result' => true,
            'data' => $data,
        ]);*/
    }

    public function question(){
        $type = $request->type ?? 'customer';
        $manual = Question::where('type', $type)->get();
        //$manual->url_file = uploaded_asset($manual->file);
        return response([
            'result' => true,
            'data' => $manual,
        ]);
    }
}
