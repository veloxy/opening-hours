<?php
declare(strict_types=1);

namespace Sourcebox\OpeningHours\Override;

/**
 * Class DateOverride
 * @package Sourcebox\OpeningHours\Override
 */
class DateOverride implements OverrideInterface
{
    /**
     * @var \DateTime
     */
    public $holidayDateTime;

    /**
     * @var string
     */
    private $type;

    /**
     * DateOverride constructor.
     * @param $holidayDateTime
     */
    public function __construct(\DateTime $holidayDateTime)
    {
        $this->holidayDateTime = $holidayDateTime;
    }

    /**
     * {@inheritdoc}
     */
    public function isOverridden(\DateTime $dateTime) : bool
    {
        return $dateTime->format('Y-m-d') === $this->holidayDateTime->format('Y-m-d');
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
