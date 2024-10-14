<?php

namespace App\Notifications;

use App\Model\Event;
use App\Model\PaymentMethod;
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
    public function __construct(Payment $payment, $paymentMethod, $eventId, $subscriptionCheckout, $user)
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = route('payment.required', ['id' => $this->paymentId, 'event'=>$this->event, 'paymentMethod' => encrypt($this->paymentMethod), 'subscriptionCheckout' => $this->subscriptionCheckout]);

        $data = [];
        $data['url'] = $url;
        $data['firstName'] = $this->user['firstname'];
        $data['amount'] = $this->amount;
        $data['eventTitle'] = Event::find($this->event)->title;
        $data['footer'] = ($pm = PaymentMethod::find($this->paymentMethod)) ? $pm->footer : '';

        $subject = 'Knowcrunch -' . $data['firstName'] . ' please confirm your payment';

        return (new MailMessage)
            ->from('info@knowcrunch.com', 'Knowcrunch')
            ->subject(__($subject))
            ->view('emails.stripe.require_action', $data);
    }
}
