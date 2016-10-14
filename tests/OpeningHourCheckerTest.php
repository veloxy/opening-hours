<?php

namespace Sourcebox\OpeningHours;

use Sourcebox\OpeningHours\Exception\HolidayException;

class OpeningHourCheckerTest extends \PHPUnit_Framework_TestCase
{
    public function testIsOpenOnDay()
    {
        $timezone = new \DateTimeZone('Europe/Brussels');

        $openingHourChecker = new OpeningHourChecker(new OpeningHours([
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

        $openingHourChecker = new OpeningHourChecker(new OpeningHours([
            new Day(Day::MONDAY, [
                new TimePeriod('08:00', '12:00'),
            ]),
            new Day(Day::SUNDAY, [])
        ], $timezone));

        $this->assertFalse($openingHourChecker->isClosedOn(Day::MONDAY));
        $this->assertTrue($openingHourChecker->isClosedOn(Day::TUESDAY));
        $this->assertTrue($openingHourChecker->isClosedOn(Day::SUNDAY));
    }

    public function testIsOpenAtIsClosedAt()
    {
        $timezone = new \DateTimeZone('Europe/Brussels');

        $openingHourChecker = new OpeningHourChecker(new OpeningHours([
            new Day(Day::MONDAY, [
                new TimePeriod('02:00', '04:00'),
                new TimePeriod('08:00', '12:00'),
                new TimePeriod('13:00', '14:00'),
            ])
        ], $timezone));

        $this->assertTrue($openingHourChecker->isOpenAt(
            \DateTime::createFromFormat('Y-m-d H:i:s', '2016-10-10 10:00:00', $timezone)
        ));

        $this->assertTrue($openingHourChecker->isOpenAt(
            \DateTime::createFromFormat('Y-m-d H:i:s', '2016-10-10 13:00:00', $timezone)
        ));

        $this->assertTrue($openingHourChecker->isOpenAt(
            \DateTime::createFromFormat('Y-m-d H:i:s', '2016-10-10 02:12:00', $timezone)
        ));

        $this->assertFalse($openingHourChecker->isOpenAt(
            \DateTime::createFromFormat('Y-m-d H:i:s', '2016-10-10 05:00:00', $timezone)
        ));

        $this->assertFalse($openingHourChecker->isOpenAt(
            \DateTime::createFromFormat('Y-m-d H:i:s', '2016-10-11 05:00:00', $timezone)
        ));

        $this->assertTrue($openingHourChecker->isClosedAt(
            \DateTime::createFromFormat('Y-m-d H:i:s', '2016-10-11 05:00:00', $timezone)
        ));
    }

    public function testIsOpenAtDifferentTimeZone()
    {
        $timezone = new \DateTimeZone('Europe/Brussels');

        $openingHourChecker = new OpeningHourChecker(new OpeningHours([
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

    public function testGetDateTimeAtHour()
    {
        $timezone = new \DateTimeZone('Europe/Brussels');
        $openingHourChecker = new OpeningHourChecker(new OpeningHours([], $timezone));

        $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', '2016-10-10 02:12:00', $timezone);

        $expectedDateTime = \DateTime::createFromFormat('Y-m-d H:i:s', '2016-10-10 01:00:00', $timezone);
        $actualDateTime = $openingHourChecker->getDateTimeAtHour($dateTime, '01:00');

        $this->assertEquals($expectedDateTime, $actualDateTime);
    }

    public function testGetOpeningHoursForDay()
    {
        $timePeriods = [
            new TimePeriod('08:00', '12:00'),
            new TimePeriod('15:00', '17:00'),
        ];

        $openingHourChecker = new OpeningHourChecker(new OpeningHours([
            new Day(Day::MONDAY, $timePeriods)
        ]));

        $this->assertEquals($timePeriods, $openingHourChecker->getOpeningHoursForDay(Day::MONDAY));
        $this->assertEquals([], $openingHourChecker->getOpeningHoursForDay(Day::TUESDAY));
    }

    public function testGetOpeningHours()
    {
        $openingHours = new OpeningHours([
            new Day(Day::MONDAY, [
                new TimePeriod('08:00', '12:00'),
                new TimePeriod('15:00', '17:00'),
            ]),
        ]);

        $openingHourChecker = new OpeningHourChecker($openingHours);
        $this->assertEquals($openingHours, $openingHourChecker->getOpeningHours());

        $openingHourChecker->setOpeningHours(new OpeningHours([]));
        $this->assertEquals(new OpeningHours([]), $openingHourChecker->getOpeningHours());
    }

    public function testGetSetAddExceptions()
    {
        $exception = new HolidayException(new \DateTime('now'));
        $openingHourChecker = new OpeningHourChecker(new OpeningHours([]));
        $openingHourChecker->addException($exception);

        $this->assertEquals([$exception], $openingHourChecker->getExceptions());

        $exceptions = [
            $exception,
            $exception,
        ];

        $openingHourChecker->setExceptions($exceptions);
        $this->assertEquals($exceptions, $openingHourChecker->getExceptions());
    }
}
