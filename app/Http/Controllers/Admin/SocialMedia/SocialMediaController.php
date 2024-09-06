<?php

namespace App\Http\Controllers\Admin\SocialMedia;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Management;

class SocialMediaController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('permission:social read')->only('index');
    //     $this->middleware('permission:social create')->only('create', 'store');
    //     $this->middleware('permission:social update')->only('edit', 'update');
    //     $this->middleware('permission:social delete')->only('destroy');

    //     view()->share('menuActive', 'settings');
    //     view()->share('subMenuActive', 'social-media');
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $social = (object) [
            'youtube' => @Management::type(Management::YOUTUBE)->first(),
            'linkedin' => @Management::type(Management::LINKEDIN)->first(),
            'instagram' => @Management::type(Management::INSTAGRAM)->first(),
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Get contact management resource',
            'data' => $social
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.social.create');
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
            'title' => 'required|max:250',
            'type'  => 'required',
            'url'   => 'required'
        ]);

        $social = new Management($request->all());

        if ($social->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Save Succesfully',
                'data' => $social
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  SocialMedia  $social
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $social =    @Management::updateOrCreate([
            'type'       => $request->type,
        ], [
            'title' => $request->title,
            'type' => $request->type,
            'url' => $request->url,
        ]);

        if ($social) {
            return response()->json([
                'status' => 'success',
                'message' => 'Update Succesfully',
                'data' => $social
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  SocialMedia $social
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Management $management) {}
}
