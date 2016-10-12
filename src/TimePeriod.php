<?php

namespace Sourcebox\OpeningHours;


class TimePeriod
{
    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $until;

    /**
     * TimePeriod constructor.
     * @param string $from
     * @param string $until
     */
    public function __construct(string $from, string $until)
    {
        $this->from = $from;
        $this->until = $until;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @param string $from
     * @return TimePeriod
     */
    public function setFrom(string $from): TimePeriod
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return string
     */
    public function getUntil(): string
    {
        return $this->until;
    }

    /**
     * @param string $until
     * @return TimePeriod
     */
    public function setUntil(string $until): TimePeriod
    {
        $this->until = $until;

        return $this;
    }
}
