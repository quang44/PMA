<?php



namespace App\Http\Controllers\Api\V2;



use App\Http\Resources\V2\BannerCollection;

use App\Http\Resources\V2\BannerResource;

use App\Models\Banner;

use Illuminate\Http\Request;



class BannerController extends Controller

{



    public function index(Request $request)

    {

        $subject = $request->subject ?? 'customer';

        $banner = Banner::query()->where('status', 1)
            ->where('subject', 'customer')
            ->where('start_time', '<=', time())
            ->where('end_time', '>=', time())
            ->get();

        return new BannerCollection($banner);

    }



    public function show($id){

        $banner = Banner::find($id);

        return new BannerResource($banner);

    }

}

