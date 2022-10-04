<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::all();
        $data = [];
        foreach ($pages as $page) {
            $data[] = [
                'id' => $page->id,
                'title' => $page->title,
                'icon' => uploaded_asset($page->icon),
                'url' => route('home').'/'. $page->slug
            ];
        }
        return response([
            'result' => true,
            'data' => $data,
        ]);
    }

    public function show($id)
    {
        $page = Page::find($id);
        return response([
            'result' => true,
            'data' => $page,
        ]);
    }
}
