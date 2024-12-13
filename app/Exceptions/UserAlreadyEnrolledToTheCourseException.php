<?php

namespace App\Exceptions;

class UserAlreadyEnrolledToTheCourseException extends \Exception
{
    public function __construct(
        private readonly array $emails,
        private readonly array $events,
    ) {
        parent::__construct();
    }

    public function getEmails(): array
    {
        return $this->emails;
    }

    public function getEvents(): array
    {
        return $this->events;
    }
}
