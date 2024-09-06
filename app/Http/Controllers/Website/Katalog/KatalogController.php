<?php

namespace App\Http\Controllers\Website\Katalog;

use App\Models\Service;
use App\Models\Management;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\KatalogResource;
use Illuminate\Database\Eloquent\Builder;


class KatalogController extends Controller
{

    public function __construct()
    {
        View()->share('menu', 'Katalog');
    }

    public function index(Request $request)
    {
        $keunggulan = Service::Keunggulan()->orderBy('id', 'desc')->when($request->search, function (Builder $query, string $key) {
            $query->search($key);
        })->latest()->paginate(9);
        return KatalogResource::collection($keunggulan);
    }

    public function info()
    {
        $data['info']       = Management::Katalog()->first();
        $data['info']->url = $data['info']->image;
        $data['info']->image = asset($data['info']->image);

        return response()->json([
            'status' => 'success',
            'message' => 'Succesffully Get Resources!',
            'data' => $data
        ], 200);
    }
}
