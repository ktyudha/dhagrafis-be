<?php

namespace App\Http\Controllers\Admin\Service;

use App\Models\Management;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PromoManagementController extends Controller
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
        $promo = @Management::type(Management::PROMO)->first();
        $promo->url = $promo->image;
        $promo->image = asset($promo->image);
        return response()->json([
            'status' => 'success',
            'message' => 'Get about management resource',
            'promo' => $promo
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
            $info = @Management::type(@Management::PROMO)->first();

            if ($info) {
                if ($info->image != null) {
                    Storage::delete($info->image);
                }
            }

            $path = $request->file('image')->store('ugc/promo');
            Management::updateOrCreate([
                'type' =>  @Management::PROMO,
            ], ['image' => $path]);
        }

        Management::updateOrCreate([
            'type'       => @Management::PROMO,
        ], [
            'title' => $request->title,
            'description' => $request->description,
            'additional_info' => json_encode($request->additional_info),
        ]);

        $management = Management::type(@Management::PROMO)->first();

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
