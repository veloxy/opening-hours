<?php


namespace Sourcebox\OpeningHours;

class DayTest extends \PHPUnit_Framework_TestCase
{
    public function testAddOpeningHour()
    {
        $timePeriods = [
            new TimePeriod('01:00', '02:00')
        ];

        $timePeriod = new TimePeriod('03:00', '05:00');

        $day = new Day(Day::FRIDAY, $timePeriods);
        $day->addTimePeriod($timePeriod);

        $expected = $timePeriods;
        $expected[] = $timePeriod;

        $this->assertEquals($expected, $day->getTimePeriods());
    }
}
