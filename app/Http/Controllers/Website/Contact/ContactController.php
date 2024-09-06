<?php

namespace App\Http\Controllers\Website\Contact;

use App\Http\Controllers\Controller;
use App\Models\Management;


class ContactController extends Controller
{

    public function __construct()
    {
        View()->share('menu', 'Contact');
    }

    public function index()
    {
        $data        = Management::Contact()->first();
        $data->url = $data->image;
        $data->image = asset($data->image);

        return response()->json([
            'status' => 'success',
            'message' => 'Succesffully Get Resources!',
            'contact' => $data
        ], 200);
    }
}
