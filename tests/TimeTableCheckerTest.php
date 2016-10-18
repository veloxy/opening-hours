<?php

namespace Sourcebox\OpeningHours;

class TimeTableCheckerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSetTimeTable()
    {
        $timeTable = new TimeTable([
            new Day(Day::MONDAY, [
                new TimePeriod('08:00', '12:00'),
                new TimePeriod('15:00', '17:00'),
            ]),
        ]);

        $checker = new TimeTableChecker($timeTable);
        $this->assertEquals($timeTable, $checker->getTimeTable());

        $checker->setTimeTable(new TimeTable([]));
        $this->assertEquals(new TimeTable([]), $checker->getTimeTable());
    }

    public function testCreateDateTimeForHour()
    {
        $timezone = new \DateTimeZone('Europe/Brussels');
        $checker = new TimeTableChecker(new TimeTable([], $timezone));

        $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', '2016-10-10 02:12:00', $timezone);

        $expectedDateTime = \DateTime::createFromFormat('Y-m-d H:i:s', '2016-10-10 01:00:00', $timezone);
        $actualDateTime = $checker->createDateTimeForHour($dateTime, '01:00');

        $this->assertEquals($expectedDateTime, $actualDateTime);
    }

    public function testGetTimePeriodsForDay()
    {
        $timePeriods = [
            new TimePeriod('08:00', '12:00'),
            new TimePeriod('15:00', '17:00'),
        ];

        $checker = new TimeTableChecker(new TimeTable([
            new Day(Day::MONDAY, $timePeriods)
        ]));

        $this->assertEquals($timePeriods, $checker->getTimePeriodsForDay(Day::MONDAY));
        $this->assertEquals([], $checker->getTimePeriodsForDay(Day::TUESDAY));
    }
}
