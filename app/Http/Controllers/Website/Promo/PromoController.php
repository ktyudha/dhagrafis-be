<?php

namespace App\Http\Controllers\Website\Promo;

use App\Http\Controllers\Controller;
use App\Models\Management;


class PromoController extends Controller
{

    public function __construct()
    {
        View()->share('menu', 'Promo');
    }

    public function index()
    {
        $data['info']       = Management::Promo()->first();
        $data['info']->url = $data['info']->image;
        $data['info']->image = asset($data['info']->image);

        

        return response()->json([
            'status' => 'success',
            'message' => 'Succesffully Get Resources!',
            'data' => $data
        ], 200);
    }
}
