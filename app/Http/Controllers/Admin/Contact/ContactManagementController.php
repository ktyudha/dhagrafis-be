<?php

namespace App\Http\Controllers\Admin\Contact;

use App\Models\Management;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ContactManagementController extends Controller
{
    // public function __construct()
    // {
    // $this->middleware('can:management read')->only('index');
    // $this->middleware('can:management create')->only('create', 'store');
    // $this->middleware('can:management update')->only('edit', 'update');
    // $this->middleware('can:management delete')->only('destroy');
    // }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contact = @Management::type(Management::CONTACT)->first();
        $contact->url = $contact->image;
        $contact->image = asset($contact->image);
        return response()->json([
            'status' => 'success',
            'message' => 'Get contact management resource',
            'contact' => $contact
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
            $info = @Management::type(Management::CONTACT)->first();

            if ($info) {
                if ($info->image != null) {
                    Storage::delete($info->image);
                }
            }

            $path = $request->file('image')->store('ugc/contact');
            Management::updateOrCreate([
                'type' =>  Management::CONTACT,
            ], ['image' => $path]);
        }

        Management::updateOrCreate([
            'type'       => Management::CONTACT,
        ], [
            'title' => $request->title,
            'description' => $request->description,
            'additional_info' => json_encode($request->additional_info),
        ]);

        $management = Management::type(Management::CONTACT)->first();

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
