<?php

namespace App\Notifications;

use App\Jobs\SendEmail;
use App\Model\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SendTopicAutomateMail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        private readonly array $data,
        private readonly User $user
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return SendBrevoMail::class;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toBrevo($notifiable)
    {
        $emailEvent = '';
        if ($this->data['email_template'] == 'activate_social_media_account_email') {
            $emailEvent = 'SendTopicAutomateMailSocialAccount';
        } elseif ($this->data['email_template'] == 'activate_advertising_account_email') {
            $emailEvent = 'SendTopicAutomateMailAdAccount';
        } elseif ($this->data['email_template'] == 'activate_production_content_account_email') {
            $emailEvent = 'SendTopicAutomateMailContentAccount';
        } elseif ($this->data['email_template'] == 'instructor_course_graduation_reminder_email') {
            $emailEvent = 'InstructorCourseGraduationReminder';
        } elseif ($this->data['email_template'] == 'instructor_course_kickoff_reminder_email') {
            $emailEvent = 'InstructorCourseKickoffReminder';
        } elseif ($this->data['email_template'] == 'instructor_class_workshop_reminder') {
            $emailEvent = 'InstructorWorkshopReminder';
        } elseif ($this->data['email_template'] == 'student_course_kickoff_reminder_email') {
            $emailEvent = 'StudentCourseKickoffReminder';
        }

        SendEmail::dispatch($emailEvent, $this->user->toArray(), null, array_merge([
            'FIRST_NAME'=> $this->data['firstname'],
            'CourseName'=>$this->data['eventTitle'],
            'SubscriptionPrice'=>isset($this->data['subscription_price']) ? $this->data['subscription_price'] : '0',
        ], $this->data), ['event_id'=>$this->data['eventId']]);
    }
}
