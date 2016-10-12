<?php

namespace Sourcebox\OpeningHours;


class OpeningHourChecker
{
    private $openingHours;

    /**
     * OpeningHourChecker constructor.
     * @param OpeningHours $openingHours
     */
    public function __construct(OpeningHours $openingHours)
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
     * Checks opening hours for open time periods on a certain date and time.
     * @param \DateTime $dateTime
     * @return bool
     */
    public function isOpenAt(\DateTime $dateTime) : bool 
    {
        $day = $this->openingHours->getDay($dateTime->format('N'));

        if (!$day instanceof Day || !$day->getTimePeriods()) {
            return false;
        }

        foreach ($day->getTimePeriods() as $timePeriod) {
            $from = \DateTime::createFromFormat('Y-m-d H:i:s', $dateTime->format(sprintf('Y-m-d %s:00', $timePeriod->getFrom())));
            $until = \DateTime::createFromFormat('Y-m-d H:i:s', $dateTime->format(sprintf('Y-m-d %s:00', $timePeriod->getFrom())));
            if ($dateTime >= $from && $dateTime <= $until) {
                return true;
            }
        }

        return false;
    }
}
