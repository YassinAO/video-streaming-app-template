<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Channel;

class UploadVideoController extends Controller
{
    public function index(Channel $channel){
        return view('channels.upload',[
            'channel' => $channel
        ]);
    }

    public function store(Channel $channel){
        return $channel->videos()->create([
            'title' => request()->title,
            'path'  => request()->video->store("channels/{$channel->id}/videos")
        ]);
    }
}
