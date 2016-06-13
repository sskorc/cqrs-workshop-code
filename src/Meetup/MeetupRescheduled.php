<?php

namespace Meetup;

final class MeetupRescheduled
{
    /**
     * @var \DateTimeInterface
     */
    private $newDate;

    public function __construct(\DateTimeInterface $newDate)
    {
        $this->newDate = $newDate;
    }

    /**
     * @return \DateTimeInterface
     */
    public function newDate()
    {
        return $this->newDate;
    }
}
