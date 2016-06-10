<?php

namespace Twitsup\Domain\Model\Tweet;

use EventSourcing\Aggregate\Event;
use EventSourcing\Aggregate\EventCapabilities;
use Ramsey\Uuid\UuidInterface;

final class Tweeted implements Event
{
    use EventCapabilities;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $text;

    /**
     * @return UuidInterface
     */
    public function id() : UuidInterface
    {
        return $this->id;
    }

    public function __construct(UuidInterface $id, string $text)
    {
        $this->text = $text;
        $this->id = $id;
    }

    public function text() : string
    {
        return $this->text;
    }
}