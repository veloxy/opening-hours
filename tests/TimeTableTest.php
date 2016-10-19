<?php


namespace Sourcebox\OpeningHours;


class TimeTableTest extends \PHPUnit_Framework_TestCase
{
    public function testSetTimeZone()
    {
        $timeZone = new \DateTimeZone('Africa/Accra');
        $timeTable = new TimeTable([]);
        $timeTable->setTimezone($timeZone);

        $this->assertEquals($timeZone, $timeTable->getTimezone());
    }

    public function testGetDays()
    {
        $days = [
            Day::FRIDAY => new Day(Day::FRIDAY, []),
            Day::MONDAY => new Day(Day::MONDAY, []),
            Day::TUESDAY => new Day(Day::TUESDAY, []),
        ];

        $timeTable = new TimeTable($days);

        $this->assertEquals($days, $timeTable->getDays());
    }
}
