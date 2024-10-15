<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\YoutubeUpload;
use Illuminate\Http\Request;
use Auth;
use Youtube;

class YoutubeUploadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $youtubeuploads = YoutubeUpload::where('video', 'LIKE', "%$keyword%")
                ->orWhere('title', 'LIKE', "%$keyword%")
                ->orWhere('description', 'LIKE', "%$keyword%")
                ->orWhere('tags', 'LIKE', "%$keyword%")
                ->orWhere('category_id', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $youtubeuploads = YoutubeUpload::latest()->paginate($perPage);
        }

        return view('youtube-uploads.index', compact('youtubeuploads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('youtube-uploads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $title = $request->title;
        $description = $request->description;
        $tags =  $request->tags;
        $videoPath = $request->file('video');
        $video = Youtube::upload($videoPath, [
            'title'       => $title,
            'description' => $description,
            'tags'        => $tags,
            'category_id' => 10
        ]);

        return $video->getVideoId();
        // return redirect('youtube-uploads')->with('flash_message', 'YoutubeUpload added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $youtubeupload = YoutubeUpload::findOrFail($id);

        return view('youtube-uploads.show', compact('youtubeupload'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $youtubeupload = YoutubeUpload::findOrFail($id);

        return view('youtube-uploads.edit', compact('youtubeupload'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        
        $requestData = $request->all();
        
        $youtubeupload = YoutubeUpload::findOrFail($id);
        $youtubeupload->update($requestData);

        return redirect('youtube-uploads')->with('flash_message', 'YoutubeUpload updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        YoutubeUpload::destroy($id);

        return redirect('youtube-uploads')->with('flash_message', 'YoutubeUpload deleted!');
    }
}
