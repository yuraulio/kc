<?php

namespace App\Enums\Report;

enum ReportEnum: string
{
    case Tags = 'tags';
    case CourseDelivery = '`deliveries`.id';
    case CourseName = '`events`.id';
    case CoursePaymentStatus = '`event_user`.paid';
    case CoursePricingStatus = '`event_info`.course_payment_method';
    case SubscriptionPlans = '`subscriptions`.stripe_price';
    case SubscriptionPaymentStatus = '`subscriptions`.stripe_status';
    case CourseDurationStatus = '`event_user`.expiration';
    case ContestStatus = '';
    case EnrollmentStatus = '`event_user`.comment';
    case Transaction = "`transactionables`.`transactionable_type` LIKE '%User' AND `transactions`.status";
    case UserRole = '`role_users`.role_id';
    case UserAccountActivity = '`activations`.completed';
    case CouponName = "`transactionables`.`transactionable_type` LIKE '%User' AND `transactions`.coupon_code";

    public static function getEnumKeyNames(): array
    {
        return array_map(fn (self $case) => $case->name, self::cases());
    }

    public static function hasFilter($key)
    {
        $enumKeyNames = ReportEnum::getEnumKeyNames();

        return in_array(str_replace(' ', '', ucwords($key)), $enumKeyNames);
    }

    public static function getFilterSQL($key)
    {
        return self::fromValue($key)->value;
    }

    public static function getFilterKey($key)
    {
        return str_replace(' ', '', ucwords($key));
    }

    public static function fromValue(string $value): self
    {
        foreach (self::cases() as $case) {
            if ($case->name === str_replace(' ', '', ucwords($value))) {
                return $case;
            }
        }

        throw new \Exception("Invalid ReportEnum value: $value");
    }

    public static function getExportColumnNames(): array
    {
        return [
            ['key' => 'lastname', 'label' => 'Last name', 'column_name' => '`users`.lastname'],
            ['key' => 'firstname', 'label' => 'First name', 'column_name' => '`users`.firstname'],
            ['key' => 'email', 'label' => 'Email', 'column_name' => '`users`.email'],
            ['key' => 'mobile', 'label' => 'Phone', 'column_name' => '`users`.mobile'],
            ['key' => 'birthday', 'label' => 'Birthday', 'column_name' => '`users`.birthday'],
            ['key' => 'user_role', 'label' => 'User Role', 'column_name' => '`roles`.name as role_name'],
            ['key' => 'career_path', 'label' => 'Career paths', 'column_name' => "(SELECT GROUP_CONCAT(career_paths.name, ',') from event_career_paths JOIN career_paths ON career_paths.id = event_career_paths.career_path_id WHERE event_career_paths.event_id = `events`.id) as career_paths"],
            ['key' => 'course_name', 'label' => 'Course name', 'column_name' => '`events`.title as event_title'],
            ['key' => 'course_audience', 'label' => 'Course target audience', 'column_name' => '`audiences`.name as audience_name'],
            ['key' => 'delivery', 'label' => 'Course delivery', 'column_name' => '`deliveries`.name as delivery_name'],
            ['key' => 'ticket_type', 'label' => 'Ticket type', 'column_name' => '`tickets`.title as ticket_title'],
            ['key' => 'ticket_price', 'label' => 'Ticket price', 'column_name' => '(SELECT event_tickets.price from event_tickets where event_tickets.ticket_id = `tickets`.id AND event_tickets.event_id = `events`.id LIMIT 1) as ticket_price'],
            ['key' => 'coupon_name', 'label' => 'Coupon name', 'column_name' => '`transactions`.coupon_code'],
            ['key' => 'life_time_revenue', 'label' => 'Lifetime revenue', 'column_name' => "(SELECT SUM(transactions.total_amount) from transactions JOIN transactionables on transactions.id = transactionables.transaction_id WHERE transactionables.transactionable_id = `users`.id AND `transactionables`.`transactionable_type` LIKE '%User') as life_time_revenue"],
            ['key' => 'payment_status', 'label' => 'Transaction status', 'column_name' => '`transactions`.status as transaction_status'],
            ['key' => 'total_amount', 'label' => 'Total amount', 'column_name' => '`transactions`.total_amount as transaction_amount'],
            ['key' => 'subscription_plan', 'label' => 'Plan name', 'column_name' => '`plans`.name as plan_name'],
            ['key' => 'subscription_status', 'label' => 'Subscription status', 'column_name' => '`latest_subscription`.stripe_status as subscription_status'],
            ['key' => 'city', 'label' => 'City', 'column_name' => '`transactions`.billing_city'],
            ['key' => 'country', 'label' => 'Country', 'column_name' => '`transactions`.billing_country'],
            ['key' => 'zip_code', 'label' => 'Zip code', 'column_name' => '`transactions`.billing_zipcode'],
            ['key' => 'giveaway_status', 'label' => 'Contest name', 'column_name' => " CASE WHEN give_aways.email IS NOT NULL THEN 'Participated' ELSE 'Not Participated'  END AS giveaway_status "],
        ];
    }
}
