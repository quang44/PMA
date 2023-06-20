<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyCard extends Model
{
    use HasFactory;
    protected $fillable=['user_id','user_name','address','phone',
        'warranty_code','video_url', 'point',
        'note','reason','active_time','status','create_time','accept_by','province_id','district_id','ward_id','latlng','project_photo'];

    function brand(){
        return $this->belongsTo(Brand::class);
    }
    function user(){
        return $this->belongsTo(User::class)->select('name','id');
    }
    function uploads(){
        return $this->hasMany(Upload::class,'object_id','id');
    }

    function cardDetail(){
        return $this->hasMany(WarrantyCardDetail::class)->with('product');
    }
    public function province(){
        return $this->belongsTo(Province::class)->select('name','id');
    }
    public function district(){
        return $this->belongsTo(District::class)->select('name','id');
    }
    public function ward(){
        return $this->belongsTo(Ward::class)->select('name','id');
    }
    public function code(){
        return $this->belongsTo(WarrantyCode::class,'warranty_code','code')->select('code','id');
    }


    function active_user_id(){
        return $this->belongsTo(User::class,'accept_by','id');
    }

    function uploadFile($imageName, $path){
        $image = $imageName;
        $realImage = $image->hashName();
        $newPath = $path . "/$realImage";
        $image->store($path, 'local');
        return $newPath;
    }

}
