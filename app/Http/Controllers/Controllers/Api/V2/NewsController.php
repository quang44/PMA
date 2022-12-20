<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\V2\NewsCollection;
use App\Models\News;
use App\Models\Page;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::orderBy('created_at', 'desc')->paginate($request->limit ?? 10);
        return new NewsCollection($news);
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

    public function show($id)
    {
        $news = News::find($id);
        if($news){
            $news->icon = uploaded_asset($news->icon);
            $news->images = get_images_path($news->imagges);
        }
        return response([
            'result' => true,
            'data' => $news,
        ]);
    }
}
