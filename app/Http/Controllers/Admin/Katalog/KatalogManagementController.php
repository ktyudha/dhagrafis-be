<?php

namespace App\Http\Controllers\Admin\Katalog;

use App\Models\Management;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class KatalogManagementController extends Controller
{
    // public function __construct()
    // {
    //     $this->authorizeResource(Management::class, 'management');
    // }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $katalog = @Management::type(Management::KATALOG)->first();
        $katalog->url = $katalog->image;
        $katalog->image = asset($katalog->image);
        return response()->json([
            'status' => 'success',
            'message' => 'Get katalog management resource',
            'katalog' => $katalog
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
            $info = @Management::type(@Management::KATALOG)->first();

            if ($info) {
                if ($info->image != null) {
                    Storage::delete($info->image);
                }
            }

            $path = $request->file('image')->store('ugc/katalog');
            Management::updateOrCreate([
                'type' =>  Management::KATALOG,
            ], ['image' =>  $path]);
        }

        Management::updateOrCreate([
            'type'       => Management::KATALOG,
        ], [
            'title' => $request->title,
            'description' => $request->description,
            'additional_info' => json_encode($request->additional_info),
        ]);

        $management = Management::type(@Management::KATALOG)->first();

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
