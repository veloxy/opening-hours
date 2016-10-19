<?php

namespace Sourcebox\OpeningHours\Checker;

use Sourcebox\OpeningHours\Day;
use Sourcebox\OpeningHours\Override;
use Sourcebox\OpeningHours\Override\OverrideInterface;
use Sourcebox\OpeningHours\TimePeriod;
use Sourcebox\OpeningHours\TimeTable;

/**
 * Class TimeTableChecker
 * @package Sourcebox\OpeningHours\Checker
 */
class TimeTableChecker
{
    /**
     * @var TimeTable
     */
    private $timeTable;

    /**
     * @var OverrideInterface[]
     */
    private $overrides = [];

    /**
     * TimeTableChecker constructor.
     *
     * @param TimeTable $timeTable
     * @param Override\OverrideInterface[] $overrides
     */
    public function __construct(TimeTable $timeTable, array $overrides = [])
    {
        $this->timeTable = $timeTable;
        $this->overrides = $overrides;
    }

    /**
     * @return TimeTable
     */
    public function getTimeTable(): TimeTable
    {
        return $this->timeTable;
    }

    /**
     * @param TimeTable $timeTable
     *
     * @return TimeTableChecker
     */
    public function setTimeTable(TimeTable $timeTable): TimeTableChecker
    {
        $this->timeTable = $timeTable;

        return $this;
    }

    /**
     * @return Override\OverrideInterface[]
     */
    public function getOverrides(): array
    {
        return $this->overrides;
    }

    /**
     * @param Override\OverrideInterface[] $overrides
     *
     * @return TimeTableChecker
     */
    public function setOverrides(array $overrides): TimeTableChecker
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

    /**
     * Checks if certain day has time periods.
     *
     * @param string $dayId
     *
     * @return bool
     */
    public function getTimePeriodsForDay(string $dayId)
    {
        $day = $this->timeTable->getDay($dayId);

        if ($day instanceof Day && count($day->getTimePeriods()) >= 1) {
            return $day->getTimePeriods();
        }

        return [];
    }

    /**
     * Check if a date's time is within the day's time period.
     * @param \DateTime $dateTime
     *
     * @return bool
     */
    public function isDateTimeWithinDayTimePeriods(\DateTime $dateTime)
    {
        $timePeriods = $this->getTimePeriodsForDay($dateTime->format('N'));

        foreach ($timePeriods as $timePeriod) {
            if ($this->isDateTimeBetweenTimePeriod($dateTime, $timePeriod)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the time of the given DateTime is within TimePeriod's from and until time.
     *
     * @param \DateTime $dateTime
     * @param TimePeriod $timePeriod
     *
     * @return bool
     */
    public function isDateTimeBetweenTimePeriod(\DateTime $dateTime, TimePeriod $timePeriod) : bool
    {
        if ($dateTime >= $this->createDateTimeForHour($dateTime, $timePeriod->getFrom())
            && $dateTime <= $this->createDateTimeForHour($dateTime, $timePeriod->getUntil())
        ) {
            return true;
        }

        return false;
    }

    /**
     * Creates a DateTime object based on given DateTime's date and given hour.
     *
     * @param \DateTime $dateTime
     * @param string $time
     *
     * @return \DateTime
     */
    public function createDateTimeForHour(\DateTime $dateTime, string $time) : \DateTime
    {
        list($hours, $minutes) = explode(':', $time);

        $dateTime = \DateTime::createFromFormat(
            'Y-m-d H:i:s',
            $dateTime->format('Y-m-d H:i:s'),
            $this->getTimeTable()->getTimezone()
        );

        $dateTime->setTime($hours, $minutes, 0);

        return $dateTime;
    }

    /**
     * Checks if datetime is overridden.
     *
     * @param \DateTime $dateTime
     * @param string $type
     *
     * @return bool
     */
    public function isOverridden(\DateTime $dateTime, string $type)
    {
        foreach ($this->overrides as $override) {
            if ($override->getType() !== $type) {
                continue;
            }

            if ($override->isOverridden($dateTime)) {
                return true;
            }
        }

        return false;
    }
}
