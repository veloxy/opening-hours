<?php

namespace Sourcebox\OpeningHours;

/**
 * Class Day
 * @package Sourcebox\OpeningHours
 */
class Day
{
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;
    const SUNDAY = 7;

    /**
     * @var int
     */
    private $number;

    /**
     * @var TimePeriod[]
     */
    private $timePeriods = [];

    public function __construct(int $number, array $timePeriods)
    {
        $this->setNumber($number);
        $this->setTimePeriods($timePeriods);
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @param string $number
     * @return Day
     */
    public function setNumber(string $number): Day
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return TimePeriod[]
     */
    public function getTimePeriods() : array
    {
        return $this->timePeriods;
    }

    /**
     * @param TimePeriod[] $timePeriods
     * @return Day
     */
    public function setTimePeriods(array $timePeriods) : Day
    {
        $this->timePeriods = $timePeriods;

        return $this;
    }

    /**
     * @param TimePeriod $openingHour
     * @return Day
     */
    public function addOpeningHour(TimePeriod $openingHour) : Day
    {
        $this->timePeriods[] = $openingHour;

        return $this;
    }
}
