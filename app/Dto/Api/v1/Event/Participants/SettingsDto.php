<?php

namespace App\Dto\Api\v1\Event\Participants;

use App\Contracts\Api\v1\Dto\IDto;
use Illuminate\Support\Arr;

readonly class SettingsDto implements IDto
{
    private array $event;
    private array $eventInfo;
    private array $eventDeliveryType;
    private array $dynamicAds;
    private array $bonusCourse;
    private array $files;
    private array $meta;
    private ?array $skills;
    private ?array $paths;
    private ?array $city;
    private ?array $partners;
    private ?array $paymentMethods;
    private ?array $paymentOptions;
    private ?array $exams;

    public function __construct(array $data)
    {
        $this->event = [
            'absences_limit' => $data['limit'] ?? null,
            'absences_start_hours' => $data['starting_hours'] ?? null,
            'access_duration' => $data['access_duration'] ?? null,
            'files_access_till' => $data['files_access_till'] ?? null,
            'admin_title' => $data['admin_title'] ?? null,
            'slug' => $data['slug'] ?? null,
            'fb_group' => $data['support_group'] ?? null,
        ];
        $this->eventInfo = [
            'course_certification_completion' => $data['completion_title'] ?? null,
            'diploma_title' => $data['diploma_title'] ?? null,
            'language_id' => $data['selected_language'] ?? null,
            'course_satisfaction_url' => $data['course_satisfaction_url'] ?? null,
            'instructors_url' => $data['instructors_url'] ?? null,
            'send_after_days' => $data['send_after_days'] ?? null,
            'cta_course_page' => $data['course_page'] ?? null,
            'cta_course_page_re_enroll' => $data['course_page_re_enroll'] ?? null,
            'cta_home_page' => $data['home_page'] ?? null,
            'cta_lists' => $data['lists'] ?? null,
            'cta_price_visible_on_button' => $data['is_price_visible_on_button'] ?? null,
            'cta_discount_price_visible' => $data['is_discount_price_visible'] ?? null,
        ];
        $this->eventDeliveryType = [
            'delivery_type' => $data['course_delivery'] ?? null,
        ];
        $this->dynamicAds = [
            'headline' => $data['headline'] ?? null,
            'short_description' => $data['short_description'] ?? null,
            'long_description' => $data['long_description'] ?? null,
        ];
        $this->bonusCourse = [
            'ids' => isset($data['selected_course']) ? Arr::wrap($data['selected_course']) : null,
            'exams_required' => $data['exams_required'] ?? null,
        ];
        $this->files = [
            'selectedFolders' => $data['attached_files'] ?? null,
        ];
        $this->meta = [
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
        ];
        $this->skills = $data['selected_skills'] ?? null;
        $this->paths = $data['selected_paths'] ?? null;
        $this->city = $data['course_city'] ?? null;
        $this->partners = $data['selected_partner'] ?? null;
        $this->paymentMethods = $data['selected_gateway'] ?? null;
        $this->paymentOptions = $data['selected_payment_options'] ?? null;
        $this->exams = $data['selected_exams'] ?? null;
    }

    public function getData(): array
    {
        $data = [
            'event' => $this->filterNullable($this->event),
            'eventInfo' => $this->filterNullable($this->eventInfo),
            'eventInfoDelivery' => $this->filterNullable($this->eventDeliveryType),
            'dynamicAds' => $this->filterNullable($this->dynamicAds),
            'bonusCourse' => $this->filterNullable($this->bonusCourse),
            'files' => $this->filterNullable($this->files),
            'metable' => $this->filterNullable($this->meta),
        ];

        if (is_array($this->skills)) {
            $data['skills'] = $this->skills;
        }

        if (is_array($this->paths)) {
            $data['paths'] = $this->paths;
        }

        if (is_array($this->city)) {
            $data['city'] = $this->city;
        }

        if (is_array($this->partners)) {
            $data['partners'] = $this->partners;
        }

        if (is_array($this->paymentMethods)) {
            $data['paymentMethods'] = $this->paymentMethods;
        }

        if (is_array($this->paymentOptions)) {
            $data['paymentOptions'] = $this->paymentOptions;
        }

        if (is_array($this->exams)) {
            $data['exams'] = $this->exams;
        }

        return $data;
    }

    private function filterNullable(array $data): array
    {
        return array_filter($data, function ($item) {
            return $item !== null;
        });
    }
}
