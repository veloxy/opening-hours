<?php

namespace Sourcebox\OpeningHours\Override;

interface OverrideInterface
{
    const TYPE_INCLUDE = 'include';

    const TYPE_EXCLUDE = 'exclude';

    /**
     * Returns the type of override.
     *
     * @return string
     */
    public function getType() : string;

    /**
     * Return true if given date is overridden.
     *
     * @param \DateTime $dateTime
     * @return bool
     */
    public function isOverridden(\DateTime $dateTime) : bool;
}
