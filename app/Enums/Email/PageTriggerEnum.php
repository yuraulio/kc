<?php

namespace App\Enums\Email;

enum PageTriggerEnum: string
{
    public static function getPageTriggers(): array
    {
        return [
            'Home Page',
            'Course Page',
            'Grid Content Listing Page',
            'Content Page',
            'Career Path Page',
            'Terms Page',
            'Lead Form Landing Page',
            'Pricing Plans Page',
            'Search Results Page',
            'Account Login Page',
            'My Account Page',
            'Account Personal Data Page',
            'Account Password Page',
            'Account Public Profile Page',
            'Account My Courses Page',
            'Account Billing Information Page',
            'Account Subscriptions Page',
            'Account Payments & Invoices Page',
            'Account Payment Methods Page',
            'Account Watching Video Page',
            'Public Profile Page',
            'Check Out Registration Step Page',
            'Check Out Billing Step Page',
            'Check Out Payment Step Page',
            'System Response Page',
            'Talent Find Page',
        ];
    }
}
