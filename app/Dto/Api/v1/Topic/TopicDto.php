<?php

namespace App\Dto\Api\v1\Topic;

use App\Contracts\Api\v1\Dto\IDto;

final readonly class TopicDto implements IDto
{
    private ?int $status;
    private ?string $title;
    private ?string $shortTitle;
    private ?string $subtitle;
    private ?string $header;
    private ?string $summary;
    private ?string $body;
    private ?string $emailTemplate;

    public function __construct(
        array $data,
        private int $creatorId,
        private int $authorId,
    ) {
        $this->status = $data['status'] ?? null;
        $this->title = $data['title'] ?? null;
        $this->shortTitle = $data['short_title'] ?? null;
        $this->subtitle = $data['subtitle'] ?? null;
        $this->body = $data['body'] ?? null;
        $this->header = $data['header'] ?? null;
        $this->summary = $data['summary'] ?? null;
        $this->emailTemplate = $data['email_template'] ?? null;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function getShortTitle(): ?string
    {
        return $this->shortTitle;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function getEmailTemplate(): ?string
    {
        return $this->emailTemplate;
    }

    public function getHeader(): ?string
    {
        return $this->header;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
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
            'title' => $this->getTitle(),
            'short_title' => $this->getShortTitle(),
            'subtitle' => $this->getSubtitle(),
            'header' => $this->getHeader(),
            'summary' => $this->getSummary(),
            'body' => $this->getBody(),
            'email_template' => $this->getEmailTemplate(),
            'creator_id' => $this->getCreatorId(),
            'author_id' => $this->getAuthorId(),
        ];

        return array_filter($data, function ($item) {
            return $item !== null;
        });
    }
}
