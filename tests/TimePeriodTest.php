<?php
declare(strict_types=1);

namespace Sourcebox\OpeningHours;

class TimePeriodTest extends \PHPUnit_Framework_TestCase
{
    public function testSetFromUntil()
    {
        $timePeriod = new TimePeriod('02:00', '03:00');

        $fromTime = '10:00';
        $untilTime = '10:00';

        $timePeriod->setUntil($untilTime);
        $timePeriod->setFrom($fromTime);

        $this->assertEquals($fromTime, $timePeriod->getFrom());
        $this->assertEquals($untilTime, $timePeriod->getUntil());
    }
}
