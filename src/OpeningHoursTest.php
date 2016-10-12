<?php


namespace Sourcebox\OpeningHours;


class OpeningHoursTest extends \PHPUnit_Framework_TestCase
{
    public function testOpeningHours()
    {
        $openingHours = new OpeningHours([
            new Day(Day::MONDAY, [
                new TimePeriod('12:00', '15:00'),
                new TimePeriod('16:00', '17:00'),
                new TimePeriod('20:00', '24:00'),
            ])
        ]);

        $openingHours->getDay(Day::MONDAY);

        $checker = new OpeningHourChecker();
        $checker->isOpenOnDay(Day::MONDAY);
    }
}
