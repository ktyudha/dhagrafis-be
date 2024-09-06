<?php

namespace App\Http\Controllers\Admin\About;

use App\Models\Management;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;


class AboutManagementController extends Controller
{
    // public function __construct()
    // {
    //     $this->authorizeResource(Management::class, 'management');
    // }
    // public function __construct()
    // {
    //     $this->middleware('can:management read')->only('index');
    //     $this->middleware('can:management create')->only('create', 'store');
    //     $this->middleware('can:management update')->only('edit', 'update');
    //     $this->middleware('can:management delete')->only('destroy');
    // }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $about = @Management::type(Management::ABOUT)->first();
        $about->url = $about->image;
        $about->image = asset($about->image);
        return response()->json([
            'status' => 'success',
            'message' => 'Get about management resource',
            'about' => $about
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Management $management)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Management $management)
    {
        if ($request->hasFile('image')) {
            // $type  = $request->type;
            $info = @Management::type(@Management::ABOUT)->first();

            if ($info) {
                if ($info->image != null) {
                    Storage::delete($info->image);
                }
            }

            $path = $request->file('image')->store('ugc/about');

            Management::updateOrCreate([
                'type' =>  $request->type,
            ], ['image' => $path]);
        }

        Management::updateOrCreate([
            'type'       => $request->type,
        ], [
            'title' => $request->title,
            'description' => $request->description,
            'additional_info' => json_encode($request->additional_info),
        ]);

        $management = Management::type(@Management::ABOUT)->first();

        return response()->json([
            'status' => "success",
            'message' => 'Saving Successfully!',
            'data' => $management,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Management $management)
    {
        //
    }
}
