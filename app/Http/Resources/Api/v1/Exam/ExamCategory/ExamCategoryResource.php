<?php

namespace App\Http\Resources\Api\v1\Exam\ExamCategory;

use App\Http\Resources\Api\v1\Event\Instructor\InstructorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'created_at' => $this->resource->created_at,
            'description' => $this->resource->description,
        ];
    }
}
