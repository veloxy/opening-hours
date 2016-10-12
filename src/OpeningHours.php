<?php

namespace Sourcebox\OpeningHours;

class OpeningHours
{
    /**
     * @var Day[]
     */
    private $days;

    /**
     * OpeningHours constructor.
     * @param array $days
     */
    public function __construct(array $days)
    {
        $this->setDays($days);
    }

    /**
     * @return Day[]
     */
    public function getDays(): array
    {
        return $this->days;
    }

    /**
     * @param Day[] $days
     * @return OpeningHours
     */
    public function setDays(array $days): OpeningHours
    {
        foreach ($days as $day) {
            $this->days[$day->getNumber()] = $day;
        }

        return $this;
    }

    /**
     * @param int|string $name
     * @return null|Day
     */
    public function getDay(int $name)
    {
        if (array_key_exists($name, $this->days)) {
            return $this->days[$name];
        }

        return null;
    }
}
