<?php

namespace Sourcebox\OpeningHours\Exclusion;

class HolidayExclusion implements ExclusionInterface
{
    /**
     * @var \DateTime
     */
    public $holidayDateTime;

    /**
     * HolidayExclusion constructor.
     * @param $holidayDateTime
     */
    public function __construct(\DateTime $holidayDateTime)
    {
        $this->holidayDateTime = $holidayDateTime;
    }

    /**
     * {@inheritdoc}
     */
    public function isExcluded(\DateTime $dateTime) : bool
    {
        if ($dateTime->format('Y-m-d') == $this->holidayDateTime->format('Y-m-d')) {
            return true;
        }

        return false;
    }
}
