<?php

namespace App\Http\Controllers\Front;

use App\Models\Video;

class VideoController extends FrontController
{
    public function index()
    {
        $this->bootFront();

        $videos = Video::query()
            ->where('language_id', $this->pageContext()->languageId())
            ->paginate(8);

        return view('front.pages.video_gallery', compact('videos'));
    }
}
