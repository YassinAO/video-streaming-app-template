<?php

namespace App\Jobs\Videos;

use FFMpeg;
use App\Video;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * When a new video is uploaded we'll push the job to the queue, so that video can be converted.
 * Make sure to change the value of QUEUE_CONNECTION from sync to database, so that the queue jobs 
 * will run in the background instead of in sync.
 */

class ConvertForStreaming implements ShouldQueue
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
        /**
         * We want users on different internet connections to still be able to stream/watch the video, so we create
         * multiple bitrates.
         */
        $low = (new X264('aac'))->setKiloBitrate(100);
        $medium = (new X264('aac'))->setKiloBitrate(250);
        $high = (new X264('aac'))->setKiloBitrate(500);

        // Takes the disk where the video is saved.
        FFMpeg::fromDisk('local')
            ->open($this->video->path)
            ->exportForHLS() // (HTTP Live Streaming) exports to the specific format required for live streaming.
            ->onProgress(function ($percentage) {
                $this->video->update([
                    'percentage' => $percentage // We want to keep track the percentage field during the process, so we can tell when the conversion is done.
                ]);
            })
            ->addFormat($low)
            ->addFormat($medium)
            ->addFormat($high)
            ->save("public/videos/{$this->video->id}/{$this->video->id}.m3u8"); // .m3u8 is an extension required for streamable files.
    }
}
