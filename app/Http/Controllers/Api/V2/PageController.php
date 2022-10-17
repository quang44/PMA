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
                'url' => route('home') . '/' . $page->slug
            ];
        }
        return response([
            'result' => true,
            'data' => $data,
        ]);
    }

    public function show($id)
    {
        $page = Page::where('id', $id)->first();
        if ($page == null) {
            return response([
                'result' => false,
                'message' => 'Trang không tồn tại !',
            ]);
        }
        $data[] = [
            "id" => $page->id,
            "type" => $page->type,
            "icon" => $page->icon,
            "title" => $page->title,
            "slug" => $page->slug,
            "content" => $page->content,
            "meta_title" => $page->meta_title,
            "meta_description" => $page->meta_description,
            "keywords" => $page->keywords,
            "meta_image" => $page->meta_image,
            "priority" => $page->priority,
            'created_at' => date('d-m-Y h:i:s', strtotime($page->created_at)),
            'updated_at' => date('d-m-Y h:i:s', strtotime($page->updated_at)),
        ];
        return response([
            'result' => true,
            'data' => $data,
        ]);
    }
}
