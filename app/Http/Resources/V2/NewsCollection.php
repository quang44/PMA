<?php

namespace App\Http\Resources\V2;

use Html2Text\Html2Text;
use Illuminate\Http\Resources\Json\ResourceCollection;

class NewsCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                $text = new Html2Text($data->content);
                $text = $text->getHtml();

                $images = get_images_path($data->images);
                $video = $data->link ? explode(PHP_EOL, $data->link) : [];
                return [
                    'id' => $data->id,
                    'title' => $data->title,
                    'icon' => uploaded_asset($data->icon),
                    'images' => array_merge($images, $video),
                    'content' => "<div style='font-size: larger'> $data->content</div>" ,
                    'content_text' =>  str_replace(['&nbsp',';'],'',strip_tags($text)) ,
                    'url' => route('home').'/news/'. $data->slug,
                    'created_at' => $data->created_at
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
