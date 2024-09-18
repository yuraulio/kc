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
    private array $bonusCourses;
    private array $files;
    private array $meta;
    private ?array $skills;
    private ?array $paths;
    private ?array $city;
    private ?int $partner;
    private ?int $paymentMethod;
    private ?array $paymentOptions;
    private ?array $exam;
    private ?array $audiences;
    private ?array $relatedCourses;
    private ?array $tags;

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
        $this->bonusCourses = $data['bonus_courses'] ?? null;
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
        $this->partner = $data['selected_partner'] ?? null;
        $this->paymentMethod = $data['selected_gateway'] ?? null;
        $this->paymentOptions = $data['selected_payment_options'] ?? null;
        $this->exam = $data['exam'] ?? null;
        $this->audiences = $data['selected_audiences'] ?? null;
        $this->relatedCourses = $data['related_courses'] ?? null;
        $this->tags = $data['tags'] ?? null;
    }

    public function getData(): array
    {
        $data = [
            'event' => $this->filterNullable($this->event),
            'eventInfo' => $this->filterNullable($this->eventInfo),
            'eventInfoDelivery' => $this->filterNullable($this->eventDeliveryType),
            'dynamicAds' => $this->filterNullable($this->dynamicAds),
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

        if ($this->partner) {
            $data['partner'] = $this->partner;
        }

        if ($this->paymentMethod) {
            $data['paymentMethod'] = $this->paymentMethod;
        }

        if ($this->paymentOptions) {
            $data['paymentOptions'] = $this->paymentOptions;
        }

        if ($this->exam) {
            $data['exam'] = $this->exam;
        }

        if ($this->audiences) {
            $data['audiences'] = $this->audiences;
        }

        if ($this->relatedCourses) {
            $data['related_courses'] = $this->relatedCourses;
        }

        if ($this->tags) {
            $data['tags'] = $this->tags;
        }

        if ($this->bonusCourses) {
            $data['bonus_courses'] = $this->bonusCourses;
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
