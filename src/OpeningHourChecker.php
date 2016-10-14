<?php

namespace Sourcebox\OpeningHours;

use Sourcebox\OpeningHours\Exception\ExceptionInterface;

/**
 * Class OpeningHourChecker
 * @package Sourcebox\OpeningHours
 */
class OpeningHourChecker
{
    /**
     * @var OpeningHours
     */
    private $openingHours;

    /**
     * @var ExceptionInterface[]
     */
    private $exceptions = [];

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

        if ($this->hasExceptions($dateTime)) {
            return false;
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
     * @return OpeningHours
     */
    public function getOpeningHours(): OpeningHours
    {
        return $this->openingHours;
    }

    /**
     * @param OpeningHours $openingHours
     * @return OpeningHourChecker
     */
    public function setOpeningHours(OpeningHours $openingHours): OpeningHourChecker
    {
        $this->openingHours = $openingHours;

        return $this;
    }

    /**
     * Checks if datetime has exceptions.
     * @param \DateTime $dateTime
     * @return bool
     */
    public function hasExceptions(\DateTime $dateTime)
    {
        foreach ($this->exceptions as $exception) {
            if ($exception->isException($dateTime)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return ExceptionInterface[]
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }

    /**
     * @param ExceptionInterface[] $exceptions
     * @return OpeningHourChecker
     */
    public function setExceptions(array $exceptions): OpeningHourChecker
    {
        $this->exceptions = $exceptions;

        return $this;
    }

    /**
     * @param ExceptionInterface $exception
     */
    public function addException(ExceptionInterface $exception)
    {
        $this->exceptions[] = $exception;
    }
}
