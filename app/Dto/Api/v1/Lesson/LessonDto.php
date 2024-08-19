<?php

namespace App\Dto\Api\v1\Lesson;

use App\Contracts\Api\v1\Dto\IDto;

final readonly class LessonDto implements IDto
{
    private int $status;
    private string $htmlTitle;
    private string $title;
    private string $subtitle;
    private string $header;
    private string $summary;
    private string $body;
    private ?string $vimeoVideo;
    private ?string $vimeoDuration;

    public function __construct(
        array $data,
        private int $creatorId,
        private int $authorId,
    ) {
        $this->status = $data['status'] ?? 1;
        $this->htmlTitle = $data['htmlTitle'];
        $this->title = $data['title'];
        $this->subtitle = $data['subtitle'];
        $this->header = $data['header'];
        $this->summary = $data['summary'];
        $this->body = $data['body'];
        $this->vimeoVideo = $data['vimeo_video'] ?? null;
        $this->vimeoDuration = $data['vimeo_duration'] ?? null;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getHtmlTitle(): string
    {
        return $this->htmlTitle;
    }

    public function getSubtitle(): string
    {
        return $this->subtitle;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getHeader(): string
    {
        return $this->header;
    }

    public function getSummary(): string
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

    public function getCreatorId(): int
    {
        return $this->creatorId;
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function getData(): array
    {
        return [
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
        ];
    }
}
