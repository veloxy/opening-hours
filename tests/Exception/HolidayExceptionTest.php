<?php

namespace Sourcebox\OpeningHours\Exception;

use Sourcebox\OpeningHours\Day;
use Sourcebox\OpeningHours\OpeningHourChecker;
use Sourcebox\OpeningHours\OpeningHours;
use Sourcebox\OpeningHours\TimePeriod;

class HolidayExceptionTest extends \PHPUnit_Framework_TestCase
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

        $openingHourChecker->addException(new HolidayException($christmasDateTime));
        $this->assertFalse($openingHourChecker->isOpenAt($christmasDateTime));
    }

    public function testIsHoliday()
    {
        $holidayDateTime = \DateTime::createFromFormat('Y-m-d H:i:s', '2016-12-25 10:00:00');
        $holidayException = new HolidayException($holidayDateTime);
        $this->assertTrue($holidayException->isException($holidayDateTime));
    }

    public function testIsNotHoliday()
    {
        $holidayDateTime = \DateTime::createFromFormat('Y-m-d H:i:s', '2016-12-25 10:00:00');
        $holidayException = new HolidayException($holidayDateTime);
        $testDateTime = \DateTime::createFromFormat('Y-m-d H:i:s', '2016-12-26 10:00:00');
        $this->assertFalse($holidayException->isException($testDateTime));
    }
}
