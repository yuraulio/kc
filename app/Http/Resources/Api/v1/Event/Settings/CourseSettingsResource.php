<?php

namespace App\Http\Resources\Api\v1\Event\Settings;

use App\Http\Resources\Api\v1\Event\Settings\Participants\AbsencesResource;
use App\Http\Resources\Api\v1\Event\Settings\Participants\AccessResource;
use App\Http\Resources\Api\v1\Event\Settings\Participants\AdministrationResource;
use App\Http\Resources\Api\v1\Event\Settings\Participants\AudienceSettingsResource;
use App\Http\Resources\Api\v1\Event\Settings\Participants\CareerPathAndSkillsResource;
use App\Http\Resources\Api\v1\Event\Settings\Participants\CertificationResource;
use App\Http\Resources\Api\v1\Event\Settings\Participants\CTAResource;
use App\Http\Resources\Api\v1\Event\Settings\Participants\DeliveryResource;
use App\Http\Resources\Api\v1\Event\Settings\Participants\DiscountCouponsResource;
use App\Http\Resources\Api\v1\Event\Settings\Participants\DynamicAdvertisingResource;
use App\Http\Resources\Api\v1\Event\Settings\Participants\ExamSettingsResource;
use App\Http\Resources\Api\v1\Event\Settings\Participants\FilesSettingsResource;
use App\Http\Resources\Api\v1\Event\Settings\Participants\LanguageSettingsResource;
use App\Http\Resources\Api\v1\Event\Settings\Participants\LearningBonusResource;
use App\Http\Resources\Api\v1\Event\Settings\Participants\PartnershipSettingsResource;
use App\Http\Resources\Api\v1\Event\Settings\Participants\PaymentsSettingsResource;
use App\Http\Resources\Api\v1\Event\Settings\Participants\RelatedCoursesSettingsResource;
use App\Http\Resources\Api\v1\Event\Settings\Participants\SearchEngineOptimisationResource;
use App\Http\Resources\Api\v1\Event\Settings\Participants\SurveyResource;
use App\Http\Resources\Api\v1\Event\Settings\Participants\TagsSettingsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseSettingsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'absences' => AbsencesResource::make($this->resource['absences'] ?? []),
            'access' => AccessResource::make($this->resource['access'] ?? []),
            'administration' => AdministrationResource::make($this->resource['administration'] ?? []),
            'audience' => AudienceSettingsResource::make($this->resource['audience'] ?? []),
            'career_path_and_skills' => CareerPathAndSkillsResource::make($this->resource['career_path_and_skills'] ?? []),
            'certification' => CertificationResource::make($this->resource['certification'] ?? []),
            'delivery' => DeliveryResource::make($this->resource['delivery'] ?? []),
            'discount_coupons' => DiscountCouponsResource::collection($this->resource['discount_coupons'] ?? collect([])),
            'dynamic_advertising' => DynamicAdvertisingResource::make($this->resource['dynamic_advertising'] ?? collect([])),
            'e_learning_bonus' => LearningBonusResource::make($this->resource['e_learning_bonus'] ?? []),
            'files' => FilesSettingsResource::make($this->resource['files'] ?? []),
            'language' => LanguageSettingsResource::make($this->resource['language'] ?? []),
            'partnerships' => PartnershipSettingsResource::make($this->resource['partnerships'] ?? []),
            'payments' => PaymentsSettingsResource::make($this->resource['payments'] ?? []),
            'exams' => ExamSettingsResource::make($this->resource['exams'] ?? []),
            'search_engine_optimisation' => SearchEngineOptimisationResource::make($this->resource['search_engine_optimisation'] ?? []),
            'support_group' => $this->resource['support_group'] ?? null,
            'surveys' => SurveyResource::make($this->resource['surveys'] ?? []),
            'call_to_action' => CTAResource::make($this->resource['call_to_action_buttons'] ?? []),
            'related_courses' => RelatedCoursesSettingsResource::make($this->resource['related_courses'] ?? []),
            'tags' => TagsSettingsResource::make($this->resource['tags'] ?? []),
        ];
    }
}
