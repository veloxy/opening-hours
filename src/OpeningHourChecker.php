<?php

namespace Sourcebox\OpeningHours;

use Sourcebox\OpeningHours\Override\OverrideInterface;

/**
 * Class OpeningHourChecker
 * @package Sourcebox\TimeTable
 */
class OpeningHourChecker
{
    /**
     * @var TimeTable
     */
    private $openingHours;

    /**
     * @var OverrideInterface[]
     */
    private $overrides = [];

    /**
     * OpeningHourChecker constructor.
     *
     * @param TimeTable $openingHours
     */
    public function __construct(TimeTable $openingHours)
    {
        $this->openingHours = $openingHours;
    }

    /**
     * Checks the opening hours whether there are opening time periods
     * for the given day.
     *
     * @param $dayName
     * @return bool
     */
    public function isOpenOn($dayName) : bool
    {
        $day = $this->openingHours->getDay($dayName);

        if ($day instanceof Day && $day->getTimePeriods()) {
            return true;
        }

        return false;
    }

    /**
     * Checks the opening hours whether there are no opening time periods
     * for the given day.
     *
     * @param $dayName
     * @return bool
     */
    public function isClosedOn($dayName)
    {
        return !$this->isOpenOn($dayName);
    }

    /**
     * Checks opening hours for open time periods on a certain date and time.
     *
     * @param \DateTime $dateTime
     * @return bool
     */
    public function isOpenAt(\DateTime $dateTime) : bool
    {
        $day = $this->openingHours->getDay($dateTime->format('N'));

        if (!$day instanceof Day || !$day->getTimePeriods()) {
            return false;
        }

        if ($this->isOverridden($dateTime, OverrideInterface::TYPE_EXCLUDE)) {
            return false;
        } elseif ($this->isOverridden($dateTime, OverrideInterface::TYPE_INCLUDE)) {
            return true;
        }

        foreach ($day->getTimePeriods() as $timePeriod) {
            if ($this->isDateTimeBetweenTimePeriod($dateTime, $timePeriod)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks opening hours if there are no open time periods on a certain date and time.
     * @param \DateTime $dateTime
     * @return bool
     */
    public function isClosedAt(\DateTime $dateTime) : bool
    {
        return !$this->isOpenAt($dateTime);
    }

    /**
     * @param \DateTime $date
     * @param string $hour
     * @return \DateTime
     */
    public function getDateTimeAtHour(\DateTime $date, string $hour) : \DateTime
    {
        return \DateTime::createFromFormat(
            'Y-m-d H:i:s',
            $date->format(sprintf('Y-m-d %s:00', $hour)),
            $this->openingHours->getTimezone()
        );
    }

    /**
     * Check if date with time is within time period.
     *
     * @param \DateTime $dateTime
     * @param TimePeriod $timePeriod
     * @return bool
     */
    private function isDateTimeBetweenTimePeriod(\DateTime $dateTime, TimePeriod $timePeriod) : bool
    {
        if ($dateTime >= $this->getDateTimeAtHour($dateTime, $timePeriod->getFrom())
            && $dateTime <= $this->getDateTimeAtHour($dateTime, $timePeriod->getUntil())
        ) {
            return true;
        }

        return false;
    }

    /**
     * Returns opening hours for given day.
     *
     * @param $dayName
     * @return array
     */
    public function getOpeningHoursForDay($dayName) : array
    {
        $day = $this->openingHours->getDay($dayName);

        if ($day instanceof Day) {
            return $day->getTimePeriods();
        }

        return [];
    }

    /**
     * @return TimeTable
     */
    public function getOpeningHours(): TimeTable
    {
        return $this->openingHours;
    }

    /**
     * @param TimeTable $openingHours
     *
     * @return OpeningHourChecker
     */
    public function setOpeningHours(TimeTable $openingHours): OpeningHourChecker
    {
        $this->openingHours = $openingHours;

        return $this;
    }

    /**
     * Checks if datetime has exclusion.
     *
     * @param \DateTime $dateTime
     * @param string $type
     *
     * @return bool
     */
    public function isOverridden(\DateTime $dateTime, string $type)
    {
        foreach ($this->overrides as $override) {
            if ($override->getType() === $type && $override->isOverridden($dateTime)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return OverrideInterface[]
     */
    public function getOverrides(): array
    {
        return $this->overrides;
    }

    /**
     * @param OverrideInterface[] $overrides
     *
     * @return OpeningHourChecker
     */
    public function setOverrides(array $overrides): OpeningHourChecker
    {
        $this->overrides = $overrides;

        return $this;
    }

    /**
     * @param OverrideInterface $override
     */
    public function addOverride(OverrideInterface $override)
    {
        $this->overrides[] = $override;
    }
}
