<?php

namespace App\Dto\Api\v1\Lesson;

use App\Contracts\Api\v1\Dto\IDto;

final readonly class LessonDto implements IDto
{
    private ?int $status;
    private ?string $htmlTitle;
    private ?string $title;
    private ?string $subtitle;
    private ?string $header;
    private ?string $summary;
    private ?string $body;
    private ?string $vimeoVideo;
    private ?string $vimeoDuration;
    private ?array $courses;
    private ?int $categoryId;
    private ?string $created;

    public function __construct(
        array $data,
        private int $creatorId,
        private int $authorId,
    ) {
        $this->status = $data['status'] ?? 0;
        $this->htmlTitle = $data['htmlTitle'] ?? null;
        $this->title = $data['title'] ?? null;
        $this->subtitle = $data['subtitle'] ?? null;
        $this->header = $data['header'] ?? null;
        $this->summary = $data['summary'] ?? null;
        $this->body = $data['body'] ?? null;
        $this->vimeoVideo = $data['vimeo_video'] ?? null;
        $this->vimeoDuration = $data['vimeo_duration'] ?? null;
        $this->courses = array_unique(array_merge(
            ($data['classroom_courses'] ?? []),
            ($data['video_courses'] ?? []),
            ($data['live_streaming_courses'] ?? [])
        ));
        $this->categoryId = $data['category_id'] ?? [];
        $this->created = $data['created_at'] ?? now()->toDateTimeString();
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getHtmlTitle(): ?string
    {
        return $this->htmlTitle;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function getHeader(): ?string
    {
        return $this->header;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function getVimeoVideo(): ?string
    {
        return $this->vimeoVideo;
    }

    public function getVimeoDuration(): ?string
    {
        return $this->vimeoDuration;
    }

    public function getCreatorId(): ?int
    {
        return $this->creatorId;
    }

    public function getAuthorId(): ?int
    {
        return $this->authorId;
    }

    public function getData(): array
    {
        $data = [
            'status' => $this->getStatus(),
            'htmlTitle' => $this->getHtmlTitle(),
            'title' => $this->getTitle(),
            'subtitle' => $this->getSubtitle(),
            'header' => $this->getHeader(),
            'summary' => $this->getSummary(),
            'body' => $this->getBody(),
            'vimeo_video' => $this->getVimeoVideo(),
            'vimeo_duration' => $this->getVimeoDuration(),
            'creator_id' => $this->getCreatorId(),
            'author_id' => $this->getAuthorId(),
            'created_at' => $this->getCreated(),
        ];

        return array_filter($data, function ($item) {
            return $item !== null;
        });
    }

    public function getCourses(): ?array
    {
        return $this->courses;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function getCreated(): ?string
    {
        return $this->created;
    }
}
