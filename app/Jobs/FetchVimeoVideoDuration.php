<?php

namespace App\Jobs;

use App\Model\Lesson;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vimeo\Vimeo;

class FetchVimeoVideoDuration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Lesson $lesson)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(Vimeo $client): void
    {
        if (!$this->lesson->vimeo_video) {
            return;
        }

        $vimeoVideo = explode('/', $this->lesson->vimeo_video);

        $response = $client->request('/videos/' . end($vimeoVideo) . '/?password=' . config('services.vimeo.password'), [], 'GET');

        if ($response['status'] !== 200) {
            return;
        }

        $this->lesson->update([
            'vimeo_duration' => $this->formatDuration($response['body']['duration']),
        ]);
    }

    private function formatDuration($duration)
    {
        $duration = gmdate('H:i:s', $duration);
        $duration = explode(':', $duration);

        $finalFormat = '';

        if ($duration[0] != '00') {
            $finalFormat = $finalFormat . $duration[0] . 'h ';
        }
        if ($duration[1] != '00') {
            $finalFormat = $finalFormat . $duration[1] . 'm ';
        }

        if ($duration[2] != '00') {
            $finalFormat = $finalFormat . $duration[2] . 's';
        }

        return trim($finalFormat);
    }
}
