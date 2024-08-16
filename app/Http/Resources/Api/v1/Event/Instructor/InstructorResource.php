<?php

namespace App\Http\Resources\Api\v1\Event\Instructor;

use Illuminate\Http\Resources\Json\JsonResource;

class InstructorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'priority' => $this->priority,
            'status' => $this->status,
            //            'comment_status' => $this->comment_status,
            'title' => $this->title,
            'short_title' => $this->short_title,
            'subtitle' => $this->subtitle,
            'header' => $this->header,
            'company' => $this->company,
            //            'summary' => $this->summary,
            //            'body' => $this->body,
            //            'ext_url' => $this->ext_url,
            //            'mobile' => $this->mobile,
            //            'social_media' => $this->social_media,
            //            'cache_income' => $this->cache_income,
            //            'author_id' => $this->author_id,
            //            'creator_id' => $this->creator_id,
            //            'created_at' => $this->created_at,
            //            'updated_at' => $this->updated_at,
            //            'pivot' => $this->whenPivotLoaded('event_topic_lesson_instructor', [
            //                'lesson_id' => $this->pivot->lesson_id,
            //                'instructor_id' => $this->pivot->instructor_id,
            //            ]),
            //            'medias' => [
            //                'id' => $this->medias->id,
            //                'original_name' => $this->medias->original_name,
            //                'name' => $this->medias->name,
            //                'path' => $this->medias->path,
            //                'ext' => $this->medias->ext,
            //                'file_info' => $this->medias->file_info,
            //                'size' => $this->medias->size,
            //                'height' => $this->medias->height,
            //                'width' => $this->medias->width,
            //                'dpi' => $this->medias->dpi,
            //                'mediable_id' => $this->medias->mediable_id,
            //                'mediable_type' => $this->medias->mediable_type,
            //                'details' => $this->medias->details,
            //                'created_at' => $this->medias->created_at,
            //                'updated_at' => $this->medias->updated_at,
            //            ],
            'slugable' => [
                'id' => $this->slugable->id,
                'slug' => $this->slugable->slug,
                'slugable_id' => $this->slugable->slugable_id,
                //                'slugable_type' => $this->slugable->slugable_type,
                //                'created_at' => $this->slugable->created_at,
                //                'updated_at' => $this->slugable->updated_at,
            ],
        ];
    }
}
