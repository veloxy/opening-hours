<?php

namespace Sourcebox\OpeningHours\Exclusion;

use Sourcebox\OpeningHours\Day;
use Sourcebox\OpeningHours\OpeningHourChecker;
use Sourcebox\OpeningHours\OpeningHours;
use Sourcebox\OpeningHours\TimePeriod;

class HolidayExclusionTest extends \PHPUnit_Framework_TestCase
{
    public function testClosedOnHoliday()
    {
        $timezone = new \DateTimeZone('Europe/Brussels');

        $openingHourChecker = new OpeningHourChecker(new OpeningHours([
            new Day(Day::SUNDAY, [
                new TimePeriod('08:00', '21:00'),
            ]),
        ], $timezone));

        $christmasDateTime = \DateTime::createFromFormat('Y-m-d H:i:s', '2016-12-25 10:00:00', $timezone);

        $this->assertTrue($openingHourChecker->isOpenAt($christmasDateTime));

        $openingHourChecker->addExclusion(new HolidayExclusion($christmasDateTime));
        $this->assertFalse($openingHourChecker->isOpenAt($christmasDateTime));
    }

    public function testIsHoliday()
    {
        $holidayDateTime = \DateTime::createFromFormat('Y-m-d H:i:s', '2016-12-25 10:00:00');
        $holidayExclusion = new HolidayExclusion($holidayDateTime);
        $this->assertTrue($holidayExclusion->isExcluded($holidayDateTime));
    }

    public function testIsNotHoliday()
    {
        $holidayDateTime = \DateTime::createFromFormat('Y-m-d H:i:s', '2016-12-25 10:00:00');
        $holidayExclusion = new HolidayExclusion($holidayDateTime);
        $testDateTime = \DateTime::createFromFormat('Y-m-d H:i:s', '2016-12-26 10:00:00');
        $this->assertFalse($holidayExclusion->isExcluded($testDateTime));
    }
}
