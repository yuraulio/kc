<?php

namespace App\Services\Event;

use App\Contracts\Api\v1\Event\IEventSettingsService;
use App\Dto\Api\v1\Event\Participants\SettingsDto;
use App\Model\Career;
use App\Model\City;
use App\Model\Dropbox;
use App\Model\DynamicAds;
use App\Model\Event;
use App\Model\Exam;
use App\Model\Language;
use App\Model\Partner;
use App\Model\PaymentMethod;
use App\Model\PaymentOption;
use App\Model\Skill;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class EventSettingsService implements IEventSettingsService
{
    public function getEventSettings(Event $event): array
    {
        return [
            'absences' => $this->prepareEventAbsencesSettings($event),
            'access' => $this->prepareEventAccessesSettings($event),
            'administration' => $this->prepareEventAdministrationSettings($event),
            'career_path_and_skills' => $this->prepareEventCareerPathAnsSkillsSettings($event),
            'certification' => $this->prepareEventCertificationSettings($event),
            'delivery' => $this->prepareEventDeliverySettings($event),
            'discount_coupons' => $this->prepareEventDiscountCouponsSettings($event),
            'dynamic_advertising' => $this->prepareEventDynamicAdvertisingSettings($event),
            'e_learning_bonus' => $this->prepareEventBonusSettings($event),
            'files' => $this->prepareEventFilesSettings($event),
            'language' => $this->prepareEventLanguageSettings($event),
            'partnerships' => $this->prepareEventPartnershipSettings($event),
            'payments' => $this->prepareEventPaymentsSettings($event),
            'exams' => $this->prepareEventExamsSettings($event),
            'search_engine_optimisation' => $this->prepareEventSearchSettings($event),
            'support_group' => $this->prepareEventSupportGroupSettings($event),
            'surveys' => $this->prepareEventSurveySettings($event),
            'call_to_action_buttons' => $this->prepareEventCTASettings($event),
        ];
    }

    public function updateSettings(Event $event, SettingsDto $settingsDto): void
    {
        $data = $settingsDto->getData();

        $this->updateEventData($event, $data['event'] ?? []);
        $this->updateEventInfoData($event, $data['eventInfo'] ?? []);
        $this->updateEventInfoDeliveryData($event, $data['eventInfoDelivery'] ?? []);
        $this->updateEventDynamicAdsData($event, $data['dynamicAds'] ?? []);
        $this->updateEventBonusCourseData($event, $data['bonusCourse'] ?? []);
        $this->updateEventMetaData($event, $data['metable'] ?? []);
        $this->updateEventSkillsData($event, $data['skills'] ?? null);
        $this->updateEventPathsData($event, $data['paths'] ?? null);
        $this->updateEventCityData($event, $data['city'] ?? null);
        $this->updateEventPartnersData($event, $data['partners'] ?? null);
        $this->updateEventPaymentGatewaysData($event, $data['paymentMethods'] ?? null);
        $this->updateEventPaymentOptionsData($event, $data['paymentOptions'] ?? null);
        $this->updateEventExamsData($event, $data['exams'] ?? null);
        $this->updateEventFilesData($event, $data['files'] ?? null);
    }

    private function updateEventData(Event $event, array $data): void
    {
        if ($data) {
            $event->update($data);

            if (isset($data['slug'])) {
                $event->slugable()->update(['slug' => $data['slug']]);
            }
        }
    }

    private function updateEventInfoData(Event $event, array $data): void
    {
        if ($data) {
            $event->eventInfo()->update($data);
        }
    }

    private function updateEventInfoDeliveryData(Event $event, array $data): void
    {
        if ($data) {
            $eventInfo = $event->eventInfo()->first();
            $eventInfo?->delivery()?->update($data);
        }
    }

    private function updateEventDynamicAdsData(Event $event, array $data): void
    {
        if ($data) {
            $event->dynamicAds()->exists()
                ? $event->dynamicAds()->update($data)
                : $event->dynamicAds()->create($data);
        }
    }

    private function updateEventBonusCourseData(Event $event, array $data): void
    {
        if ($data && isset($data['ids'])) {
            $pivotData = [];

            foreach ($data['ids'] as $id) {
                $pivotData[$id] = ['exams_required' => $data['exams_required'] ?? false];
            }

            $event->bonusCourse()->sync($pivotData);
        }
    }

    private function updateEventMetaData(Event $event, array $data): void
    {
        if ($data) {
            $event->metable()->exists()
                ? $event->metable()->update($data)
                : $event->metable()->create($data);
        }
    }

    private function updateEventSkillsData(Event $event, ?array $data = null): void
    {
        if (is_array($data)) {
            $event->skills()->sync($data);
        }
    }

    private function updateEventPathsData(Event $event, ?array $data = null): void
    {
        if (is_array($data)) {
            $event->career()->sync($data);
        }
    }

    private function updateEventCityData(Event $event, ?array $data = null): void
    {
        if (is_array($data)) {
            $event->city()->sync($data);
        }
    }

    private function updateEventPartnersData(Event $event, ?array $data = null): void
    {
        if (is_array($data)) {
            $event->partners()->sync($data);
        }
    }

    private function updateEventPaymentGatewaysData(Event $event, ?array $data = null): void
    {
        if (is_array($data)) {
            $event->paymentMethod()->sync($data);
        }
    }

    private function updateEventPaymentOptionsData(Event $event, ?array $data = null): void
    {
        if (is_array($data)) {
            $event->paymentOptions()->sync($data);
        }
    }

    private function updateEventExamsData(Event $event, ?array $data = null): void
    {
        if (is_array($data)) {
            $event->exam()->sync($data);
        }
    }

    private function updateEventFilesData(Event $event, ?array $data = null): void
    {
        if (is_array($data)) {
            $dataToSync = [];
            $folders = $event->dropbox()->first();

            if ($folders) {
                $dataToSync[$folders->id] = ['selectedFolders' => json_encode($data['selectedFolders'])];
                $event->dropbox()->sync($dataToSync);
            }
        }
    }

    private function prepareEventAbsencesSettings(Event $event): array
    {
        return [
            'limit' => $event->absences_limit,
            'starting_hours' => $event->absences_start_hours,
        ];
    }

    private function prepareEventAccessesSettings(Event $event): array
    {
        return [
            'access_duration' => $event->access_duration,
            'files_access_till' => $event->files_access_till,
        ];
    }

    private function prepareEventAdministrationSettings(Event $event): array
    {
        return [
            'admin_title' => $event->admin_title ?? $this->generateEventAdminTitle($event),
            'created_on' => $event->created_at->format('d M Y'),
        ];
    }

    private function generateEventAdminTitle(Event $event): string
    {
        $title = $event->created_at->format('Y.m');

        if ($city = $event->city?->first()?->name) {
            $title .= '-' . $city;
        }

        $title .= ' ' . $event->title;

        $event->update(['admin_title' => $title]);

        return $title;
    }

    private function prepareEventCareerPathAnsSkillsSettings(Event $event): array
    {
        $event->loadMissing(['career', 'skills']);

        return [
            'selected_paths' => $event->career?->pluck('id')->toArray(),
            'career_paths' => Career::all(),
            'selected_skills' => $event->skills?->pluck('id')->toArray(),
            'skills' => Skill::all(),
        ];
    }

    private function prepareEventCertificationSettings(Event $event): array
    {
        $event->loadMissing('eventInfo');

        return [
            'has_certification' => $event->hasCertificate(),
            'completion_title' => $event->eventInfo->course_certification_completion,
            'diploma_title' => $event->eventInfo->diploma_title,
        ];
    }

    private function prepareEventDeliverySettings(Event $event): array
    {
        $event->loadMissing(['eventInfo.delivery', 'city']);

        return [
            'course_delivery' => $event->eventInfo?->delivery?->delivery_type,
            'course_city' => $event->city->first()?->id,
            'cities_list' => City::with('country')->get(),
        ];
    }

    private function prepareEventDiscountCouponsSettings(Event $event): Collection
    {
        $event->loadMissing('coupons');

        return $event->coupons;
    }

    private function prepareEventDynamicAdvertisingSettings(Event $event): ?DynamicAds
    {
        $event->loadMissing('dynamicAds');

        return $event->dynamicAds;
    }

    private function prepareEventBonusSettings(Event $event): array
    {
        $event->loadMissing('bonusCourse');

        return [
            'selected_course' => $event->bonusCourse->count() ? $event->bonusCourse?->first()->id : null,
            'available_courses' => Event::whereStatus(0)->get(),
            'exams_required' => $event->bonusCourse->count() ? (bool) $event->bonusCourse?->first()->pivot?->exams_required : null,
        ];
    }

    private function prepareEventFilesSettings(Event $event): array
    {
        $event->loadMissing('dropbox');

        $folders = $event->dropbox->first();

        return [
            'attached_files' => $folders ? json_decode($folders->pivot?->selectedFolders) : null,
            'available_files' => Dropbox::all(),
        ];
    }

    private function prepareEventLanguageSettings(Event $event): array
    {
        $event->loadMissing('eventInfo');

        return [
            'selected_language' => $event->eventInfo->language_id,
            'languages' => Language::all(),
        ];
    }

    private function prepareEventPartnershipSettings(Event $event): array
    {
        $event->loadMissing('partners');

        return [
            'selected_partner' => $event->partners->pluck('id')->toArray(),
            'available_partners' => Partner::all(),
        ];
    }

    private function prepareEventPaymentsSettings(Event $event): array
    {
        $event->loadMissing(['paymentMethod', 'paymentOptions']);

        return [
            'is_free' => $event->isFree(),
            'selected_gateway' => $event->paymentMethod?->pluck('id')->toArray(),
            'gateways' => PaymentMethod::all(),
            'selected_payment_options' => $event->paymentOptions,
            'options' => PaymentOption::all(),
        ];
    }

    private function prepareEventExamsSettings(Event $event): array
    {
        $event->loadMissing('exam');

        return [
            'has_exam' => $event->hasCertificateExam(),
            'selected_exams' => $event->exam,
            'exams' => Exam::all(),
        ];
    }

    private function prepareEventSearchSettings(Event $event): array
    {
        $event->loadMissing('metable');

        return [
            'slug' => $event->getSlug() ?? $this->generateEventSlug($event),
            'meta_title' => $event->metable?->meta_title,
            'meta_description' => $event->metable?->meta_description,
        ];
    }

    private function generateEventSlug(Event $event): string
    {
        $slug = Str::slug($event->title);

        $event->update(['slug' => $slug]);

        return $slug;
    }

    private function prepareEventSupportGroupSettings(Event $event): ?string
    {
        return $event->fb_group;
    }

    private function prepareEventSurveySettings(Event $event): array
    {
        $event->loadMissing('eventInfo');

        return [
            'course_satisfaction_url' => $event->eventInfo->course_satisfaction_url,
            'instructors_url' => $event->eventInfo->instructors_url,
            'send_after_days' => $event->eventInfo->send_after_days,
        ];
    }

    private function prepareEventCTASettings(Event $event): array
    {
        $event->loadMissing('eventInfo');

        return [
            'course_page' => $event->eventInfo->cta_course_page,
            'course_page_re_enroll' => $event->eventInfo->cta_course_page_re_enroll,
            'home_page' => $event->eventInfo->cta_home_page,
            'lists' => $event->eventInfo->cta_lists,
            'is_price_visible_on_button' => $event->eventInfo->cta_price_visible_on_button,
            'is_discount_price_visible' => $event->eventInfo->cta_discount_price_visible,
        ];
    }
}
