<?php

namespace App\Console\Commands\Emails;

use App\Events\EmailSent;
use App\Model\CartCache;
use App\Model\Event;
use App\Notifications\AbandonedCart;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RemindAbandonedUsers extends Command
{
    // Command name and description
    protected $signature = 'email:remindAbandonedUser';
    protected $description = 'Send automated email reminders about the abandoned users';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Call the private function to execute the email sending logic
        $this->remindAbandonedUser();
    }

    private function remindAbandonedUser()
    {
        $abandoneds = CartCache::where('send_email', 0)->get();
        $nowTime = now()->subMinutes(60);
        if (isBlackFriday() || isCyberMonday()) {
            $nowTime = now()->subMinutes(120);
        }

        foreach ($abandoneds as $abandoned) {
            if ($abandoned->created_at >= $nowTime) {
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
            $data['emailEvent'] = 'AbandonedCart';
            if (isBlackFriday()) {
                $data['emailEvent'] = 'AbandonedCartBF1';
                $data['DiscountedPrice'] = ($abandoned->price * 0.5); //50% Off Black Friday
            } elseif (isCyberMonday()) {
                $data['emailEvent'] = 'AbandonedCartCM1';
                $data['DiscountedPrice'] = ($abandoned->price * 0.6); //40% Off Cyber Monday
            }
            $data['eventId'] = $event->id;
            $data['faqs'] = url('/') . '/' . $event->slugable->slug . '/#faq';
            $data['slug'] = url('/') . '/registration?cart=' . $abandoned->slug;
            if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                $user->notify(new AbandonedCart($data, $user));
                event(new EmailSent($user->email, 'AbandonedCart'));
                $subject = $user->firstname . ' - do you need help with your enrollment';
                event(new ActivityEvent($user, ActivityEventEnum::AbandonedCart->value, $abandoned->product_title . ', ' . Carbon::now()->format('d F Y')));

                $abandoned->send_email = 1;
                $abandoned->save();
            } else {
                $abandoned->send_email = 1;
                $abandoned->save();
            }
        }
    }
}
