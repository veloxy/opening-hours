<?php

namespace Sourcebox\OpeningHours\Exclusion;

interface ExclusionInterface
{
    /**
     * Check if passed $dateTime is excluded (not available).
     *
     * @param \DateTime $dateTime
     * @return bool
     */
    public function isExcluded(\DateTime $dateTime) : bool;
}
