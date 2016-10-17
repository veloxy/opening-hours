<?php

namespace Sourcebox\OpeningHours\Inclusion;

interface InclusionInterface
{
    /**
     * Checks if date is included.
     *
     * @param \DateTime $dateTime
     *
     * @return bool
     */
    public function isIncluded(\DateTime $dateTime) : bool;
}
