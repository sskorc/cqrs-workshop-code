<?php

namespace Meetup;

final class MeetupScheduled
{
    /**
     * @var \DateTimeInterface
     */
    private $date;
    private $title;

    public function __construct(\DateTimeInterface $date, $title)
    {
        $this->date = $date;
        $this->title = $title;
    }

    /**
     * @return \DateTimeInterface
     */
    public function date()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function title()
    {
        return $this->title;
    }
}
