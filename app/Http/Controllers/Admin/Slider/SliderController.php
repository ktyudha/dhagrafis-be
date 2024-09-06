<?php

namespace App\Http\Controllers\Admin\Slider;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CarouselResource;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class SliderController extends Controller
{

    /**
     * SliderController constructor.
     */
    // public function __construct()
    // {
    //     // $this->slider = new Slider(); # <-- iki gae opo, kan hanya digunakan di fungsi `store` tok?

    //     $this->middleware('permission:sliders read')->only('index');
    //     $this->middleware('permission:sliders create')->only('create', 'store');
    //     $this->middleware('permission:sliders update')->only('edit', 'update');
    //     $this->middleware('permission:sliders delete')->only('destroy');

    //     view()->share('menuActive', 'landing-page');
    //     view()->share('subMenuActive', 'sliders');
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $carousel = Slider::orderBy('id', 'desc')->when($request->search, function (Builder $query, string $key) {
            $query->search($key);
        })->latest()->paginate(9);
        return CarouselResource::collection($carousel);
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
            'name' => 'required|max:200',
            'description' => 'required',
            'image' => 'required|image'
        ]);

        // $path = $request->file('image')->store('slider');

        $slider = new Slider($request->all());

        $image = $slider->uploadImage($request->file('image'), 'ugc/sliders');
        $slider->image = $image->lg;


        if ($slider->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Get successfully',
                'data' => $slider
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Slider $slider)
    {
        return response()->json([
            'success' => true,
            'carousel' => new CarouselResource($slider)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'name' => 'required|max:200',
            'description' => 'required',
            // 'image' => 'image'
        ]);

        if ($request->hasFile('image')) {

            if (Storage::exists($slider->image->path)) {
                Storage::delete($slider->image->path);
            }

            $path = $request->file('image')->store('slider');
            $update = $slider->update($request->only('name', 'description') + ['image' => $path]);
        } else {
            $update = $slider->update($request->only('name', 'description'));
        }

        if ($update) {
            return response()->json([
                'status' => 'success',
                'message' => 'Update succesfully',
                'data' => $slider
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Slider $slider
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Slider $slider)
    {
        if (Storage::exists($slider->image->path)) {
            Storage::delete($slider->image->path);
        }

        if ($slider->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Delete successfully',
            ], 200);
        }
    }
}
