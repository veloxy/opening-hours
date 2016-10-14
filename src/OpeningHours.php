<?php

namespace Sourcebox\OpeningHours;

use Sourcebox\OpeningHours\Exception\ExceptionInterface;

class OpeningHours
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
     * OpeningHours constructor.
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
     * @return OpeningHours
     */
    public function setDays(array $days): OpeningHours
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
     * @return OpeningHours
     */
    public function setTimezone(\DateTimeZone $timezone): OpeningHours
    {
        $this->timezone = $timezone;

        return $this;
    }
}
