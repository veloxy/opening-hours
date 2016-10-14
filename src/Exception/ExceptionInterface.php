<?php

namespace Sourcebox\OpeningHours\Exception;


interface ExceptionInterface
{
    /**
     * Check if passed $dateTime is an exception (not available).
     *
     * @param \DateTime $dateTime
     * @return bool
     */
    public function isException(\DateTime $dateTime) : bool;
}
