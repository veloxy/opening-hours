<?php

namespace Sourcebox\OpeningHours;

use Sourcebox\OpeningHours\Checker\OpeningHourChecker;
use Sourcebox\OpeningHours\Override\DateOverride;

class OpeningHourCheckerTest extends \PHPUnit_Framework_TestCase
{
    public function testIsOpenOnDay()
    {
        $timezone = new \DateTimeZone('Europe/Brussels');

        $openingHourChecker = new OpeningHourChecker(new TimeTable([
            new Day(Day::MONDAY, [
                new TimePeriod('08:00', '12:00'),
            ])
        ], $timezone));

        $this->assertTrue($openingHourChecker->isOpenOn(Day::MONDAY));
        $this->assertFalse($openingHourChecker->isOpenOn(Day::TUESDAY));
    }

    public function testIsClosedOnDay()
    {
        $timezone = new \DateTimeZone('Europe/Brussels');

        $openingHourChecker = new OpeningHourChecker(new TimeTable([
            new Day(Day::MONDAY, [
                new TimePeriod('08:00', '12:00'),
            ]),
            new Day(Day::SUNDAY, [])
        ], $timezone));

        $this->assertFalse($openingHourChecker->isClosedOn(Day::MONDAY));
        $this->assertTrue($openingHourChecker->isClosedOn(Day::TUESDAY));
        $this->assertTrue($openingHourChecker->isClosedOn(Day::SUNDAY));
    }

    public function isOpenIsClosedDataProvider()
    {
        return [
            [\DateTime::createFromFormat('Y-m-d H:i:s', '2016-10-10 10:00:00'), true], # Monday
            [\DateTime::createFromFormat('Y-m-d H:i:s', '2016-10-10 13:00:00'), true], # Monday
            [\DateTime::createFromFormat('Y-m-d H:i:s', '2016-10-10 02:12:00'), true], # Monday
            [\DateTime::createFromFormat('Y-m-d H:i:s', '2016-10-10 05:00:00'), false], # Monday
            [\DateTime::createFromFormat('Y-m-d H:i:s', '2016-10-11 05:00:00'), false], # Tuesday
        ];
    }

    /**
     * @dataProvider isOpenIsClosedDataProvider
     * @param $expected
     * @param $dateTime
     */
    public function testIsOpenAtIsClosedAt($dateTime, $expected)
    {
        $openingHourChecker = new OpeningHourChecker(new TimeTable([
            new Day(Day::MONDAY, [
                new TimePeriod('02:00', '04:00'),
                new TimePeriod('08:00', '12:00'),
                new TimePeriod('13:00', '14:00'),
            ])
        ]));

        $this->assertEquals($expected, $openingHourChecker->isOpenAt($dateTime));
    }

    public function testIsOpenAtDifferentTimeZone()
    {
        $timezone = new \DateTimeZone('Europe/Brussels');

        $openingHourChecker = new OpeningHourChecker(new TimeTable([
            new Day(Day::MONDAY, [
                new TimePeriod('08:00', '12:00'),
                new TimePeriod('15:00', '17:00'),
            ])
        ], $timezone));

        # 8:00 to 12:00 in Europe/Brussels is 14:00 to 18:00 in Asia/Kuching
        $this->assertFalse($openingHourChecker->isOpenAt(
            \DateTime::createFromFormat('Y-m-d H:i:s', '2016-10-10 10:00:00', new \DateTimeZone('Asia/Kuching'))
        ));

        # 15:00 to 17:00 in Europe/Brussels is 21:00 to 23:00 in Asia/Kuching
        $this->assertTrue($openingHourChecker->isOpenAt(
            \DateTime::createFromFormat('Y-m-d H:i:s', '2016-10-10 21:00:00', new \DateTimeZone('Asia/Kuching'))
        ));
    }

    public function testGetSetAddOverrides()
    {
        $override = new DateOverride(new \DateTime('now'));
        $openingHourChecker = new OpeningHourChecker(new TimeTable([]));
        $openingHourChecker->addOverride($override);

        $this->assertEquals([$override], $openingHourChecker->getOverrides());

        $overrides = [
            $override,
            $override,
        ];

        $openingHourChecker->setOverrides($overrides);
        $this->assertEquals($overrides, $openingHourChecker->getOverrides());
    }
}
