<?php

namespace App\Http\Controllers;

use App\Model\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $videos = Video::all();

        return view('admin.videos.main.index', ['videos' => $videos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $video = new Video;

        $data['video'] = $video;
        //$data['event_coupons'] = $coupon->event->pluck('id')->toArray();

        return view('admin.videos.main.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $video = new Video;

        $video->title = $request->title;
        $video->description = $request->description;
        $video->url = $request->url;
        $video->save();

        return redirect('/admin/video/edit/'.$video->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function edit(Video $video)
    {
        $data['video'] = $video;

        return view('admin.videos.main.create', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Video $video)
    {
        dd($video);
        if($video){

            $video->title = $request->title;

            $video->description = $request->description;
            $video->url = $request->url;

            $video->save();

            return redirect('admin/video/edit/'.$video->id);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        //
    }
}
