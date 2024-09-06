<?php

namespace App\Http\Controllers\Admin\Gallery;

use App\Models\Gallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PromoResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * @var Gallery $gallery
     */
    protected $gallery;

    /**
     * ImageController constructor.
     */
    public function __construct()
    {
        $this->gallery = new Gallery();

        // $this->middleware('permission:gallery images read');
        // $this->middleware('permission:gallery images create')->only('create', 'store');
        // $this->middleware('permission:gallery images update')->only('edit', 'update');
        // $this->middleware('permission:gallery images delete')->only('destroy');

        // view()->share('menuActive', 'galleries');
        // view()->share('subMenuActive', 'images');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $galleries = Gallery::image()->orderBy('id', 'desc')->when($request->search, function (Builder $query, string $key) {
            $query->search($key);
        })->latest()->paginate(9);

        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Get contact management resource',
        //     'data' => $galleries
        // ], 200);
        return PromoResource::collection($galleries);
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
            'title' => 'required|max:200',
            'description' => 'required',
            'image' => 'required|image'
        ]);

        $path = $request->file('image')->store('gallery');
        $payload = $request->only('title', 'description') + ['url' => $path];

        if ($this->gallery->create($payload)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Store gallery management resource',
                'data' => $payload
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Gallery $gallery)
    {
        return response()->json([
            'success' => true,
            'promo' => new PromoResource($gallery)
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'title' => 'required|max:200',
            'description' => 'required',
            // 'image' => 'image'
        ]);

        if ($request->hasFile('image')) {

            if (Storage::exists($gallery->url)) {
                Storage::delete($gallery->url);
            }

            $path = $request->file('image')->store('gallery');
            $update = $gallery->update($request->only('title', 'description') + ['url' => $path]);
        } else {
            $update = $gallery->update($request->only('title', 'description'));
        }

        if ($update) {
            return response()->json([
                'status' => 'success',
                'message' => 'Update gallery resource',
                'data' => $request
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Gallery $gallery
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Gallery $gallery)
    {
        if (Storage::exists($gallery->url)) {
            Storage::delete($gallery->url);
        }

        if ($gallery->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Delete Succesfully',
            ], 200);
        }
    }
}
