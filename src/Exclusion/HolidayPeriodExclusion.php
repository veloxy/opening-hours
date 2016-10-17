<?php

namespace Sourcebox\OpeningHours\Exclusion;

/**
 * Class HolidayPeriodExclusion
 * @package Sourcebox\OpeningHours\Exclusion
 */
class HolidayPeriodExclusion implements ExclusionInterface
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
     * HolidayPeriodExclusion constructor.
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
    public function isExcluded(\DateTime $dateTime) : bool
    {
        if ($dateTime >= $this->startDateTime && $dateTime <= $this->endDateTime) {
            return true;
        }

        return false;
    }
}
