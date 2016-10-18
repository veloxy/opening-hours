<?php

namespace Sourcebox\OpeningHours\Override;

use Sourcebox\OpeningHours\Day;
use Sourcebox\OpeningHours\Checker\OpeningHourChecker;
use Sourcebox\OpeningHours\TimeTable;
use Sourcebox\OpeningHours\TimePeriod;

class DateOverrideTest extends \PHPUnit_Framework_TestCase
{
    public function testClosedOnHoliday()
    {
        $timezone = new \DateTimeZone('Europe/Brussels');

        $openingHourChecker = new OpeningHourChecker(new TimeTable([
            new Day(Day::SUNDAY, [
                new TimePeriod('08:00', '21:00'),
            ]),
        ], $timezone));

        $christmasDateTime = \DateTime::createFromFormat('Y-m-d H:i:s', '2016-12-25 10:00:00', $timezone);

        $this->assertTrue($openingHourChecker->isOpenAt($christmasDateTime));

        $dateOverride = new DateOverride($christmasDateTime);
        $dateOverride->setType(OverrideInterface::TYPE_EXCLUDE);

        $openingHourChecker->addOverride($dateOverride);
        $this->assertFalse($openingHourChecker->isOpenAt($christmasDateTime));
    }

    public function testIsOverriddenTypeInclude()
    {
        $openingHourChecker = new OpeningHourChecker(new TimeTable([]));

        $date = \DateTime::createFromFormat('Y-m-d H:i:s', '2016-12-25 10:00:00');

        $dateOverride = new DateOverride($date);
        $dateOverride->setType(OverrideInterface::TYPE_INCLUDE);

        $openingHourChecker->addOverride($dateOverride);

        $this->assertTrue($openingHourChecker->isOverridden($date, OverrideInterface::TYPE_INCLUDE));
        $this->assertFalse($openingHourChecker->isOverridden($date, OverrideInterface::TYPE_EXCLUDE));
    }

    public function testIsOverriddenTypeExclude()
    {
        $openingHourChecker = new OpeningHourChecker(new TimeTable([]));

        $date = \DateTime::createFromFormat('Y-m-d H:i:s', '2016-12-25 10:00:00');

        $dateOverride = new DateOverride($date);
        $dateOverride->setType(OverrideInterface::TYPE_EXCLUDE);

        $openingHourChecker->addOverride($dateOverride);

        $this->assertTrue($openingHourChecker->isOverridden($date, OverrideInterface::TYPE_EXCLUDE));
        $this->assertFalse($openingHourChecker->isOverridden($date, OverrideInterface::TYPE_INCLUDE));
    }

    public function testIsHoliday()
    {
        $holidayDateTime = \DateTime::createFromFormat('Y-m-d H:i:s', '2016-12-25 10:00:00');
        $holidayExclusion = new DateOverride($holidayDateTime);
        $holidayExclusion->setType(OverrideInterface::TYPE_EXCLUDE);

        $this->assertTrue($holidayExclusion->isOverridden($holidayDateTime));
    }

    public function testIsNotHoliday()
    {
        $holidayDateTime = \DateTime::createFromFormat('Y-m-d H:i:s', '2016-12-25 10:00:00');
        $holidayExclusion = new DateOverride($holidayDateTime);
        $holidayExclusion->setType(OverrideInterface::TYPE_EXCLUDE);

        $testDateTime = \DateTime::createFromFormat('Y-m-d H:i:s', '2016-12-26 10:00:00');

        $this->assertFalse($holidayExclusion->isOverridden($testDateTime));
    }
}
