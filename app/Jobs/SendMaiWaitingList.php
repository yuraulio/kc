<?php

namespace App\Jobs;

use App\Enums\ActivityEventEnum;
use App\Events\ActivityEvent;
use App\Events\EmailSent;
use App\Model\Event;
use App\Notifications\SendWaitingListEmail;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMaiWaitingList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $event;

    public function __construct($event)
    {
        $this->event = Event::find($event);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->event) {
            foreach ($this->event->waitingList()->where('mail_sent', false)->get() as $list) {
                $list->user->notify(new SendWaitingListEmail($list->user_id, $list->event_id));
                event(new EmailSent($list->user->email, 'SendWaitingListEmail'));
                $list->mail_sent = true;
                $list->save();
            }
        }
    }
}
