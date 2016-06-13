<?php

namespace Meetup;

final class Meetup
{
    private $recordedEvents = [];
    private $cancelled = false;

    public static function schedule(\DateTimeInterface $date, $title)
    {
        $meetup = new self();
        $meetup->recordThat(new MeetupScheduled($date, $title));

        return $meetup;
    }

    public function reschedule(\DateTimeInterface $newDate)
    {
        $this->recordedEvents[] = new MeetupRescheduled($newDate);
    }

    public function cancel()
    {
        if (!$this->cancelled) {
            $this->recordedEvents[] = new MeetupCancelled();
            $this->cancelled = true;
        }
    }

    public function recordedEvents()
    {
        return $this->recordedEvents;
    }

    public static function reconstituteFromHistory(array $events)
    {
        foreach ($events as $event) {

        }
    }

    private function recordThat($event)
    {
        $this->recordedEvents[] = $event;
    }
}
