<?php

namespace App\Http\Controllers\Website\About;

use App\Http\Controllers\Controller;
use App\Models\Management;


class AboutController extends Controller
{

    public function __construct()
    {
        View()->share('menu', 'About');
    }

    public function index()
    {
        $data       = Management::About()->first();
        $data->url = $data->image;
        $data->image = asset($data->image);

        return response()->json([
            'status' => 'success',
            'message' => 'Succesffully Get Resources!',
            'data' => $data
        ], 200);
    }
}
