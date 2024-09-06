<?php

namespace App\Http\Controllers\Admin\Jobs;

use App\Models\Management;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class JobsManagementController extends Controller
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
        $jobs = @Management::type(Management::JOBS)->first();
        $jobs->url = $jobs->image;
        $jobs->image = asset($jobs->image);
        return response()->json([
            'status' => 'success',
            'message' => 'Get jobs management resource',
            'jobs' => $jobs
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
            $info = @Management::type(@Management::JOBS)->first();

            if ($info) {
                if ($info->image != null) {
                    Storage::delete($info->image);
                }
            }

            $path = $request->file('image')->store('ugc/jobs');
            Management::updateOrCreate([
                'type' =>  Management::JOBS,
            ], ['image' =>  $path]);
        }

        Management::updateOrCreate([
            'type'       => Management::JOBS,
        ], [
            'title' => $request->title,
            'description' => $request->description,
            'additional_info' => json_encode($request->additional_info),
        ]);

        $management = Management::type(@Management::JOBS)->first();

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
