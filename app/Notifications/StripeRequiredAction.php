<?php

namespace App\Notifications;

use App\Jobs\SendEmail;
use App\Model\Event;
use App\Model\PaymentMethod;
use App\Model\User;
use App\Notifications\SendMailchimpMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Laravel\Cashier\Payment;

class StripeRequiredAction extends Notification
{
    use Queueable;

    /**
     * The PaymentIntent identifier.
     *
     * @var string
     */
    public $paymentId;

    /**
     * The payment amount.
     *
     * @var string
     */
    private $amount;
    private $paymentMethod;
    private $event;
    private $subscriptionCheckout;
    private $user;

    /**
     * Create a new payment confirmation notification.
     *
     * @param  \Laravel\Cashier\Payment  $payment
     * @return void
     */
    public function __construct(Payment $payment, $paymentMethod, $eventId, $subscriptionCheckout, User $user)
    {
        $this->paymentId = $payment->id;
        $this->amount = $payment->amount();
        $this->paymentMethod = $paymentMethod;
        $this->event = $eventId;
        $this->subscriptionCheckout = $subscriptionCheckout;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return SendMailchimpMail::class;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMailchimp($notifiable)
    {
        $url = route('payment.required', ['id' => $this->paymentId, 'event'=>$this->event, 'paymentMethod' => encrypt($this->paymentMethod), 'subscriptionCheckout' => $this->subscriptionCheckout]);

        $data = [];
        $data['url'] = $url;
        $data['firstName'] = $this->user['firstname'];
        $data['amount'] = $this->amount;
        $data['eventTitle'] = Event::find($this->event)->title;
        $data['eventId'] = Event::find($this->event)->id;
        $data['footer'] = ($pm = PaymentMethod::find($this->paymentMethod)) ? $pm->footer : '';

        $subject = 'Knowcrunch -' . $data['firstName'] . ' please confirm your payment';

        SendEmail::dispatch('StripeRequiredAction', $this->user->toArray(), $subject, [
            'FNAME'=> $this->data['firstName'],
            'CourseName'=>$this->data['eventTitle'],
            'url'=>$this->data['url'],
            'amount'=>$this->data['amount'],
            'footer'=>$this->data['footer'],
        ], ['event_id'=>$this->data['eventId']]);
    }
}
