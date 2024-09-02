<?php

namespace App\Http\Resources\Api\v1\Exam;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'exam_name' => $this->resource->title,
            'created_at' => $this->resource->created_at,
            'category' =>   $this->resource->category,
            'creator_id'=> $this->resource->creator_id,
            'display_crt_answers'=> $this->resource->display_crt_answers,
            'duration' => $this->resource->duration,
            'end_of_time_text' => $this->resource->end_of_time_text,
            'examCheckbox' => $this->resource->examCheckbox,
            'examMethods' => $this->resource->examMethods,
            'exam_category' => $this->resource->exam_category,
            'failure_text' => $this->resource->failure_text,
            'indicate_crt_incrt_answers' => $this->resource->indicate_crt_incrt_answers,
            'intro_text' => $this->resource->intro_text,
            'publish_time' => $this->resource->publish_time,
            'q_limit' => $this->resource->q_limit,
            'random_answers' => $this->resource->random_answers,
            'repeat_exam' => $this->resource->repeat_exam,
            'random_questions' => $this->resource->random_questions,
            'status' => $this->resource->status,
            'success_text' => $this->resource->success_text,
            'repeat_exam_in_failure' => $this->resource->repeat_exam_in_failure,
            'course_elearning_exam_activate_months' => $this->resource->course_elearning_exam_activate_months,
            'minutes_after_completion' => $this->resource->minutes_after_completion,
            'exam_activation_datetime' => $this->resource->exam_activation_datetime,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
