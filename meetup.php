<?php

use Meetup\Meetup;
use Meetup\MeetupCancelled;
use Meetup\MeetupRescheduled;
use Meetup\MeetupScheduled;
use Meetup\ReadModel\UpcomingMeetup;

require __DIR__ . '/vendor/autoload.php';

function it($m, $p)
{
    echo ($p ? '✔︎' : '✘') . " It $m\n";
    if (!$p) {
        $GLOBALS['f'] = 1;
    }
}

function done()
{
    if (@$GLOBALS['f']) {
        die(1);
    }
}

$date = new \DateTimeImmutable('2016-06-13');
$title = 'An evening with CQRS';
$meetup = Meetup::schedule($date, $title);
it('should have recorded the right events', $meetup->recordedEvents() == [
        new MeetupScheduled($date, $title),
    ]);

$newDate = new \DateTimeImmutable('2016-07-13');
$meetup->reschedule($newDate);
it('should have recorded the right events', $meetup->recordedEvents() == [
        new MeetupScheduled($date, $title),
        new MeetupRescheduled($newDate),
    ]);

$meetup->cancel();
it('should have recorded the right events', $meetup->recordedEvents() == [
        new MeetupScheduled($date, $title),
        new MeetupRescheduled($newDate),
        new MeetupCancelled(),
    ]);

$meetup->cancel();
it('is impossible to cancel twice', $meetup->recordedEvents() == [
        new MeetupScheduled($date, $title),
        new MeetupRescheduled($newDate),
        new MeetupCancelled(),
    ]);

$date = new \DateTimeImmutable('2016-06-13');
$title = 'An evening with CQRS';
$meetup = Meetup::schedule($date, $title);
$meetup->reschedule($newDate);

$createReadModel = function (array $events) {
    $upcomingMeetup = new UpcomingMeetup();
    foreach ($events as $event) {
        switch (get_class($event)) {
            case MeetupScheduled::class:
                $upcomingMeetup->title = $event->title();
                $upcomingMeetup->date = $event->date();
                if ($event->date() < new DateTimeImmutable()) {
                    // something has to happen!
                }
                break;
            case MeetupRescheduled::class:
                $upcomingMeetup->date = $event->newDate();
                if ($event->newDate() < new DateTimeImmutable()) {
                    // delete the meetup
                }
                break;
            case MeetupCancelled::class:
                // delete the meetup
                break;
        }
    }
    return $upcomingMeetup;
};
$upcomingMeetup = $createReadModel($meetup->recordedEvents());
$expectedUpcomingMeetup = new UpcomingMeetup();
$expectedUpcomingMeetup->date = $newDate;
$expectedUpcomingMeetup->title = $title;
it('should have created the upcoming meetup read model', $upcomingMeetup == $expectedUpcomingMeetup);
