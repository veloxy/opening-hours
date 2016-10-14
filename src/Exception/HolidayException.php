<?php

namespace Sourcebox\OpeningHours\Exception;

class HolidayException implements ExceptionInterface
{
    /**
     * @var \DateTime
     */
    public $holidayDateTime;

    /**
     * HolidayException constructor.
     * @param $holidayDateTime
     */
    public function __construct(\DateTime $holidayDateTime)
    {
        $this->holidayDateTime = $holidayDateTime;
    }

    /**
     * @param \DateTime $dateTime
     * @return bool
     */
    public function isException(\DateTime $dateTime) : bool
    {
        if ($dateTime->format('Y-m-d') == $this->holidayDateTime->format('Y-m-d')) {
            return true;
        }

        return false;
    }
}
