<?php

namespace App\Jobs\Videos;

use FFMpeg;
use App\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * When a new video is uploaded we'll push the job to the queue, so that a thumbnail can be generated for the video.
 * Make sure to change the value of QUEUE_CONNECTION from sync to database, so that the queue jobs 
 * will run in the background instead of in sync.
 */

class CreateVideoThumbnail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Takes the disk where the video is saved.
        FFMpeg::fromDisk('local')
            ->open($this->video->path)
            ->getFrameFromSeconds(1) // This gets the frame we want to use for the thumbnail.
            ->export()
            ->toDisk('local')
            ->save("public/thumbnails/{$this->video->id}.png");

        // We update the thumbnail field after the thumbnail has been created from the video, so it can be displayed in the front-end.
        $this->video->update([
            'thumbnail' => Storage::url("public/thumbnails/{$this->video->id}.png")
        ]);
    }
}
