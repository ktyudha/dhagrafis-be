<?php

namespace App\Http\Controllers\Website\Jobs;

use App\Models\Service;
use App\Models\Management;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\KatalogResource;
use Illuminate\Database\Eloquent\Builder;


class JobsController extends Controller
{

    public function __construct()
    {
        View()->share('menu', 'Jobs');
    }

    // public function index(Request $request)
    // {
    //     $keunggulan = Service::Keunggulan()->orderBy('id', 'desc')->when($request->search, function (Builder $query, string $key) {
    //         $query->search($key);
    //     })->latest()->paginate(9);
    //     return KatalogResource::collection($keunggulan);
    // }

    public function index()
    {
        $data['info']       = Management::Jobs()->first();
        $data['info']->url = $data['info']->image;
        $data['info']->image = asset($data['info']->image);

        return response()->json([
            'status' => 'success',
            'message' => 'Succesffully Get Resources!',
            'data' => $data
        ], 200);
    }
}
