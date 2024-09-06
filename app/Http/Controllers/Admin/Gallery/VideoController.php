<?php

namespace App\Http\Controllers\Admin\Gallery;

use App\Models\Gallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VideoController extends Controller
{
    /**
     * @var Gallery $gallery
     */
    protected $gallery;

    /**
     * VideoController constructor.
     */
    public function __construct()
    {
        $this->gallery = new Gallery();
        
        $this->middleware('permission:gallery videos read');
        $this->middleware('permission:gallery videos create')->only('create', 'store');
        $this->middleware('permission:gallery videos update')->only('edit', 'update');
        $this->middleware('permission:gallery videos delete')->only('destroy');

        view()->share('menuActive', 'galleries');
        view()->share('subMenuActive', 'videos');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['models'] = $this->gallery->video()->orderBy('id', 'desc')->paginate(10);
        return view('admin.gallery.video.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.gallery.video.create');
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
            'url' => 'required|active_url'
        ]);

        if ($gallery = $this->gallery->create($request->all())) {
            return redirect()->route('admin.gallery_video.index')->with(['status' => 'success', 'message' => 'Save Successfully']);
        }
        return redirect()->route('admin.gallery_video.create')->with(['status' => 'danger', 'message' => 'Save Failed, Contact Developer']);
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
     * Show the form for editing the specified resource.
     *
     * @param  Gallery  $gallery_video
     * @return \Illuminate\Http\Response
     */
    public function edit(Gallery $gallery_video)
    {
        return view('admin.gallery.video.edit', ['model' => $gallery_video]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Gallery  $gallery_video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gallery $gallery_video)
    {
        $request->validate([
            'title' => 'required|max:200',
            'description' => 'required',
            'url' => 'required|active_url'
        ]);

        if ($gallery_video->update($request->all())) {
            return redirect()->route('admin.gallery_video.index')->with(['status' => 'success', 'message' => 'Update Successfully']);
        }
        return redirect()->route('admin.gallery_video.edit', $gallery_video->id)->with(['status' => 'danger', 'message' => 'Save Failed, Contact Developer']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Gallery $gallery_video
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Gallery $gallery_video)
    {
        if ($gallery_video->delete()) {
            return redirect()->route('admin.gallery_video.index')->with(['status' => 'success', 'message' => 'Delete Successfully']);
        }
        return redirect()->route('admin.gallery_video.index')->with(['status' => 'danger', 'message' => 'Delete Failed, Contact Developer']);
    }
}
