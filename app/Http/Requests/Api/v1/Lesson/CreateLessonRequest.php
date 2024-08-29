<?php

namespace App\Http\Requests\Api\v1\Lesson;

use App\Contracts\Api\v1\Dto\IDtoRequest;
use App\Dto\Api\v1\Lesson\LessonDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateLessonRequest extends FormRequest implements IDtoRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'htmlTitle' => ['required', 'string', 'min:3'],
            'title' => ['required', 'string', 'min:3'],
            'subtitle' => ['required', 'string'],
            'header' => ['required', 'string'],
            'summary' => ['required', 'string'],
            'body' => ['required', 'string'],
            'vimeo_video' => ['nullable', 'string'],
            'vimeo_duration' => ['nullable', 'string'],
        ];
    }

    public function toDto(): LessonDto
    {
        return new LessonDto(
            $this->validated(),
            Auth::id(),
            Auth::id(),
        );
    }
}
