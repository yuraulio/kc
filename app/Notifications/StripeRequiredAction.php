<?php

namespace App\Notifications;

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
    public $amount;
    public $paymentMethod;
    public $event;
    public $subscriptionCheckout;

    /**
     * Create a new payment confirmation notification.
     *
     * @param  \Laravel\Cashier\Payment  $payment
     * @return void
     */
    public function __construct(Payment $payment,$paymentMethod,$eventId,$subscriptionCheckout)
    {
        $this->paymentId = $payment->id;
        $this->amount = $payment->amount();
        $this->paymentMethod = $paymentMethod;
        $this->event $eventId;
        $this->$subscriptionCheckout = $subscriptionCheckout;
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
        $url = route('payment.required', ['id' => $this->paymentId, 'event'=>$this->event, 'paymentMethod' => encrypt($this->paymentMethod),'$subscriptionCheckout' => $subscriptionCheckout]);

        return (new MailMessage)
            ->subject(__('Confirm Payment'))
            ->greeting(__('Confirm your :amount paymenttt', ['amount' => $this->amount]))
            ->line(__('Extra confirmation is needed to process your payment. Please continue to the payment page by clicking on the button below.'))
            ->action(__('Confirm Payment'), $url);
    }
}
