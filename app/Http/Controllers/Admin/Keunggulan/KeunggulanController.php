<?php

namespace App\Http\Controllers\Admin\Keunggulan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\KatalogResource;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class KeunggulanController extends Controller
{

    // public function __construct() {
    //     $this->middleware('permission:services read');
    //     $this->middleware('permission:services create')->only('create', 'store');
    //     $this->middleware('permission:services update')->only('edit', 'update');
    //     $this->middleware('permission:services delete')->only('destroy');

    //     view()->share('menuActive', 'landing-page');
    //     view()->share('subMenuActive', 'keunggulan');
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keunggulan = Service::Keunggulan()->orderBy('id', 'desc')->when($request->search, function (Builder $query, string $key) {
            $query->search($key);
        })->latest()->paginate(9);
        return KatalogResource::collection($keunggulan);

        // $data = Service::orderBy('id', 'desc')->Keunggulan()->paginate(10);
        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Get successfully',
        //     'data' => $data
        // ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'           => 'required|max:250',
            'description_short' => 'required',
            'description'     => 'required',
            'image'           => 'required|image'
        ]);

        $keunggulan = new Service($request->all());
        $keunggulan->type = 'keunggulan';

        $image = $keunggulan->uploadImage($request->file('image'), 'ugc/service');
        $keunggulan->image = $image->lg;

        if ($keunggulan->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Store successfully',
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Service $keunggulan)
    {
        return response()->json([
            'success' => true,
            'katalog' => new KatalogResource($keunggulan)
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Service  $keunggulan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $keunggulan)
    {
        $request->validate([
            'title'           => 'required|max:250',
            'description_short' => 'required',
            'description'     => 'required',
        ]);

        if ($request->hasFile('image')) {
            $newImage = $keunggulan->uploadImage($request->file('image'), 'ugc/service');
            $payload = $request->all();

            if ($newImage) {
                $payload['image'] = $newImage->lg;
                $keunggulan->deleteImage();
            }

            $keunggulan->update($payload);
        } else {
            $keunggulan->update($request->all());
        }

        if ($keunggulan) {
            return response()->json([
                'status' => 'success',
                'message' => 'Update successfully',
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Service $keunggulan
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Service $keunggulan)
    {
        if (Storage::exists($keunggulan->image)) {
            Storage::delete($keunggulan->image);
        }

        if ($keunggulan->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Delete successfully',
            ], 200);
        }
    }
}
