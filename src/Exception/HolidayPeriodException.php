<?php

namespace Sourcebox\OpeningHours\Exception;

/**
 * Class HolidayPeriodException
 * @package Sourcebox\OpeningHours\Exception
 */
class HolidayPeriodException implements ExceptionInterface
{
    /**
     * @var \DateTime
     */
    private $startDateTime;

    /**
     * @var \DateTime
     */
    private $endDateTime;

    /**
     * HolidayPeriodException constructor.
     * @param \DateTime $startDateTime
     * @param \DateTime $endDateTime
     */
    public function __construct(\DateTime $startDateTime, \DateTime $endDateTime)
    {
        $this->startDateTime = $startDateTime;
        $this->endDateTime = $endDateTime;
    }

    /**
     * {@inheritdoc}
     */
    public function isException(\DateTime $dateTime) : bool
    {
        if ($dateTime >= $this->startDateTime && $dateTime <= $this->endDateTime) {
            return true;
        }

        return false;
    }
}
