<?php

namespace Sourcebox\OpeningHours;

class TimeTable
{
    /**
     * @var Day[]
     */
    private $days;

    /**
     * @var \DateTimeZone
     */
    private $timezone;

    /**
     * TimeTable constructor.
     * @param array $days
     * @param \DateTimeZone $timezone
     */
    public function __construct(array $days, \DateTimeZone $timezone = null)
    {
        if (!$timezone) {
            $timezone = new \DateTimeZone(date_default_timezone_get());
        }

        $this->timezone = $timezone;
        $this->setDays($days);
    }

    /**
     * @return Day[]
     */
    public function getDays(): array
    {
        return $this->days;
    }

    /**
     * @param Day[] $days
     *
     * @return TimeTable
     */
    public function setDays(array $days): TimeTable
    {
        foreach ($days as $day) {
            $this->days[$day->getNumber()] = $day;
        }

        return $this;
    }

    /**
     * @param int|string $name
     * @return null|Day
     */
    public function getDay(int $name)
    {
        if (array_key_exists($name, $this->days)) {
            return $this->days[$name];
        }

        return null;
    }

    /**
     * @return \DateTimeZone
     */
    public function getTimezone(): \DateTimeZone
    {
        return $this->timezone;
    }

    /**
     * @param \DateTimeZone $timezone
     *
     * @return TimeTable
     */
    public function setTimezone(\DateTimeZone $timezone): TimeTable
    {
        $this->timezone = $timezone;

        return $this;
    }
}
