<?php

namespace Sourcebox\OpeningHours\Override;

use Sourcebox\OpeningHours\Day;
use Sourcebox\OpeningHours\OpeningHourChecker;
use Sourcebox\OpeningHours\OpeningHours;
use Sourcebox\OpeningHours\TimePeriod;

class DatePeriodOverrideTest extends \PHPUnit_Framework_TestCase
{
    public function holidayPeriodDataProvider()
    {
        $timezone = new \DateTimeZone('Europe/Brussels');

        return [
            [\DateTime::createFromFormat('Y-m-d H:i:s', '2016-31-12 00:00:00', $timezone), true],
            [\DateTime::createFromFormat('Y-m-d H:i:s', '2017-01-01 00:00:00', $timezone), false],
            [\DateTime::createFromFormat('Y-m-d H:i:s', '2017-01-02 00:00:00', $timezone), false],
            [\DateTime::createFromFormat('Y-m-d H:i:s', '2017-01-03 00:00:00', $timezone), false],
            [\DateTime::createFromFormat('Y-m-d H:i:s', '2017-01-04 00:00:00', $timezone), false],
            [\DateTime::createFromFormat('Y-m-d H:i:s', '2017-01-05 00:00:00', $timezone), false],
            [\DateTime::createFromFormat('Y-m-d H:i:s', '2017-01-06 00:00:00', $timezone), false],
            [\DateTime::createFromFormat('Y-m-d H:i:s', '2017-01-07 00:00:00', $timezone), false],
            [\DateTime::createFromFormat('Y-m-d H:i:s', '2017-01-08 00:00:00', $timezone), false],
            [\DateTime::createFromFormat('Y-m-d H:i:s', '2017-01-09 00:00:00', $timezone), false],
            [\DateTime::createFromFormat('Y-m-d H:i:s', '2017-01-10 00:00:00', $timezone), false],
            [\DateTime::createFromFormat('Y-m-d H:i:s', '2017-01-12 00:00:00', $timezone), true],
            [\DateTime::createFromFormat('Y-m-d H:i:s', '2017-01-11 00:00:00', $timezone), true],
        ];
    }

    /**
     * @dataProvider holidayPeriodDataProvider
     * @param \DateTime $check
     * @param bool $expected
     */
    public function testHolidayPeriod(\DateTime $check, bool $expected)
    {
        $timezone = new \DateTimeZone('Europe/Brussels');

        $openingHourChecker = new OpeningHourChecker(new OpeningHours([
            new Day(Day::SUNDAY, [new TimePeriod('00:00', '24:00')]),
            new Day(Day::MONDAY, [new TimePeriod('00:00', '24:00')]),
            new Day(Day::TUESDAY, [new TimePeriod('00:00', '24:00')]),
            new Day(Day::WEDNESDAY, [new TimePeriod('00:00', '24:00')]),
            new Day(Day::THURSDAY, [new TimePeriod('00:00', '24:00')]),
            new Day(Day::FRIDAY, [new TimePeriod('00:00', '24:00')]),
            new Day(Day::SATURDAY, [new TimePeriod('00:00', '24:00')]),
        ], $timezone));

        $holidayPeriodStart = \DateTime::createFromFormat('Y-m-d H:i:s', '2017-01-01 00:00:00', $timezone);
        $holidayPeriodEnd = \DateTime::createFromFormat('Y-m-d H:i:s', '2017-01-10 00:00:00', $timezone);
        $holidayPeriod = new DatePeriodOverride($holidayPeriodStart, $holidayPeriodEnd);
        $holidayPeriod->setType(OverrideInterface::TYPE_EXCLUDE);

        $openingHourChecker->addOverride($holidayPeriod);

        $this->assertEquals($expected, $openingHourChecker->isOpenAt($check));
    }

    /**
     * @dataProvider holidayPeriodDataProvider
     * @param \DateTime $check
     * @param bool $expected
     */
    public function testDateTimeInHolidayPeriod(\DateTime $check, bool $expected)
    {
        $timezone = new \DateTimeZone('Europe/Brussels');

        $holidayPeriodStart = \DateTime::createFromFormat('Y-m-d H:i:s', '2017-01-01 00:00:00', $timezone);
        $holidayPeriodEnd = \DateTime::createFromFormat('Y-m-d H:i:s', '2017-01-10 00:00:00', $timezone);
        $holidayPeriodExclusion = new DatePeriodOverride($holidayPeriodStart, $holidayPeriodEnd);
        $holidayPeriodExclusion->setType(OverrideInterface::TYPE_EXCLUDE);

        $this->assertEquals($expected, !$holidayPeriodExclusion->isOverridden($check));
    }
}
