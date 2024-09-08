<?php

namespace App\Http\Controllers\Website\Home;

use App\Models\Client;
use App\Models\Slider;
use App\Models\Gallery;
use App\Models\Service;
use App\Models\Testimoni;
use App\Models\Management;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GalleryResource;
use App\Http\Resources\CarouselResource;

class HomeController extends Controller
{

    public function __construct()
    {
        View()->share('menu', 'Home');
    }

    public function index(Request $request)
    {
        $data['sliders']           = CarouselResource::collection(Slider::all());
        $data['galleries']         = GalleryResource::collection(Gallery::take('10')->get());
        // $data['services']          = Service::Service()->take('3')->get();
        $data['keunggulan']        = Service::Keunggulan()->take('10')->latest()->get();
        // $data['about_info']        = Management::About()->first();
        // $data['contact_info']      = Management::Contact()->first();
        // $data['social_info']      = Management::Social()->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Succesffully Get Resources!',
            'data' => $data
        ], 200);
    }
}
