<?php

namespace Sourcebox\OpeningHours\Override;

/**
 * Class DatePeriodOverride
 * @package Sourcebox\TimeTable\Override
 */
class DatePeriodOverride implements OverrideInterface
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
     * @var string
     */
    private $type;

    /**
     * DatePeriodOverride constructor.
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
    public function isOverridden(\DateTime $dateTime) : bool
    {
        if ($dateTime >= $this->startDateTime && $dateTime <= $this->endDateTime) {
            return true;
        }

        return false;
    }

    /**
     * Returns the type of override.
     *
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }
}
