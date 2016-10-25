<?php
declare(strict_types=1);

namespace Sourcebox\OpeningHours\Checker;

use Sourcebox\OpeningHours\Override\OverrideInterface;

/**
 * Class OpeningHourChecker
 * @package Sourcebox\TimeTable
 */
class OpeningHourChecker extends TimeTableChecker
{
    /**
     * Checks the time table whether there are opening time periods
     * for the given day.
     *
     * @param $dayId
     *
     * @return bool
     */
    public function isOpenOn($dayId) : bool
    {
        return count($this->getTimePeriodsForDay($dayId)) > 0;
    }

    /**
     * Checks the time table whether there are no opening time periods
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
     * Checks time table for open time periods on a certain date and time.
     *
     * @param \DateTime $dateTime
     * @return bool
     */
    public function isOpenAt(\DateTime $dateTime) : bool
    {
        if ($this->isOverridden($dateTime, OverrideInterface::TYPE_EXCLUDE)) {
            return false;
        } elseif ($this->isOverridden($dateTime, OverrideInterface::TYPE_INCLUDE)) {
            return true;
        }

        return $this->isDateTimeWithinDayTimePeriods($dateTime);
    }

    /**
     * Checks time table if there are no open time periods on a certain date and time.
     * @param \DateTime $dateTime
     * @return bool
     */
    public function isClosedAt(\DateTime $dateTime) : bool
    {
        return !$this->isOpenAt($dateTime);
    }
}
