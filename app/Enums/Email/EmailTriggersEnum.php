<?php

namespace App\Enums\Email;

enum EmailTriggersEnum: string
{
    public static function getEmailTriggers(): array
    {
        return [
            ['key' => 'registration_welcome_email', 'label' => 'User registration - Welcome email'],
            ['key' => 'password_forgot', 'label' => 'User forgot password'],
            ['key' => 'password_reset', 'label' => 'User password successfully reset'],
            ['key' => 'exam_completed', 'label' => 'User completed the exam'],
            ['key' => 'admin_invites_student', 'label' => 'Admin invites a student'],
        ];
    }
}
