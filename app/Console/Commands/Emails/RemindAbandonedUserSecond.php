<?php

namespace App\Console\Commands\Emails;

use App\Events\EmailSent;
use App\Model\CartCache;
use App\Model\Event;
use App\Notifications\AbandonedCart;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RemindAbandonedUserSecond extends Command
{
    // Command name and description
    protected $signature = 'email:remindAbandonedUserSecond';
    protected $description = 'Send automated email reminders about the abandoned users second time';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Call the private function to execute the email sending logic
        $this->remindAbandonedUserSecond();
    }

    private function remindAbandonedUserSecond()
    {
        $now_date = now();
        $now_date = date_format($now_date, 'Y-m-d');

        if (isBlackFriday() || isCyberMonday()) {
            $abandoneds = CartCache::where('send_email', '=', 1)->get();

            foreach ($abandoneds as $abandoned) {
                if ($abandoned->created_at <= now()->subMinutes(240)) {
                    continue;
                }

                if ($abandoned->updated_at >= now()->subMinutes(120)) {
                    continue;
                }

                if (!$user = $abandoned->user) {
                    continue;
                }

                if (!$event = $abandoned->eventt) {
                    continue;
                }

                if (!$event->published || $event->status != 0) {
                    continue;
                }

                $data['firstName'] = $user->firstname;
                $data['eventTitle'] = $event->title;
                if (isBlackFriday()) {
                    $data['emailEvent'] = 'AbandonedCartBF2';
                    $data['DiscountedPrice'] = ($abandoned->price * 0.5); //50% Off Black Friday
                } elseif (isCyberMonday()) {
                    $data['emailEvent'] = 'AbandonedCartCM2';
                    $data['DiscountedPrice'] = ($abandoned->price * 0.6); //40% Off Cyber Monday
                }
                $data['eventId'] = $event->id;
                $data['faqs'] = url('/') . '/' . $event->slugable->slug . '/#faq';
                $data['slug'] = url('/') . '/registration?cart=' . $abandoned->slug;

                $user->notify(new AbandonedCart($data, $user, true));
                event(new EmailSent($user->email, 'AbandonedCart'));
                event(new ActivityEvent($user, ActivityEventEnum::AbandonedCart->value, $abandoned->product_title . ', ' . Carbon::now()->format('d F Y')));
                $abandoned->send_email = 2;
                $abandoned->save();
            }
        }
    }
}
