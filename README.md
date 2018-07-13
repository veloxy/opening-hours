# Opening Hours

[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![SensioLabs Insight][ico-sensio]][link-sensio]
[![StyleCI][ico-styleci]][link-styleci]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]

This is a small library that helps you do several checks on opening hours.

## Requirements

- PHP 7.0+

## Installation

You can install this library using composer, note that it's still in development and may or may not change drastically before the first version is released.

```
composer require sourcebox/opening-hours
```

## Quick Usage
 
You can easily figure out the usage by checking out the tests, but here's a quick example:

### Create a TimeTable

A TimeTable contains your opening hours, it consists of an array of days that each have a set of TimePeriods.
A day that doesn't have time periods or a day that doesn't exist in the time table is considered a closed day. 

```php
$timeTable = new TimeTable([
   new Day(Day::MONDAY, [
       new TimePeriod('08:00', '12:00'),
       new TimePeriod('13:00', '17:00'),
   ]),
   new Day(Day::TUESDAY, [
       new TimePeriod('08:00', '12:00'),
       new TimePeriod('13:00', '17:00'),
   ]),
   new Day(Day::WEDNESDAY, [
       new TimePeriod('08:00', '12:00'),
       new TimePeriod('13:00', '17:00'),
   ]),
   new Day(Day::THURSDAY, [
       new TimePeriod('08:00', '12:00'),
       new TimePeriod('13:00', '17:00'),
   ]),
   new Day(Day::FRIDAY, [
       new TimePeriod('08:00', '12:00'),
       new TimePeriod('13:00', '17:00'),
   ]),
   new Day(Day::SATURDAY, [
       new TimePeriod('08:00', '12:00'),
   ]),
]);

$checker = new OpeningHourChecker($timeTable);
```

The timetable is passed to the opening hour checker so we can start checking stuff.

### Basic checks

The OpeningHourChecker consists of a few basic checks.

#### Check if it's open on a certain day.

This is a very simple check, only checks if there are time periods for the given day.

```php
$checker->isOpenOn(Day::TUESDAY); // true
$checker->isClosedOn(Day::TUESDAY); // false
```

#### Check if it's open on a certain date and time

This check will check the time periods for a given day and time.

```php
$checker->isOpenAt(\DateTime::createFromFormat('Y-m-d H:i:s', '2016-10-10 10:00:00'))); // returns true
$checker->isClosedAt(\DateTime::createFromFormat('Y-m-d H:i:s', '2016-10-10 10:00:00'))); // returns false
```

#### Overrides

Overrides are basically exceptions to the timetable. There's two types of overrides, includes and excludes.
Include overrides are dates that are included, exclude overrides are dates that are excluded.

There are currently two override classes included.

##### Example

This example adds christmas date as an exclusion override, which means that christmas day is excluded from the timetable. 
This is handy when you want your store to be closed on certain dates.

```php
$dateOverride = new DateOverride($christmasDateTime);
$dateOverride->setType(OverrideInterface::TYPE_EXCLUDE);

$openingHourChecker->addOverride($dateOverride);
$openingHourChecker->isOpenAt($christmasDateTime); // return false
```

The reverse is also possible, say you want to open all day on black friday, regardless of opening hours. 
You'd use the same `DateOverride` but set the type to `TYPE_INCLUDE`. Not that the overrides prioritize excludes over includes.

There's also a DatePeriodOverride, which does the same as the DateOverride but for a period.

```php
$holidayPeriodStart = \DateTime::createFromFormat('Y-m-d H:i:s', '2017-01-01 00:00:00', $timezone);
$holidayPeriodEnd = \DateTime::createFromFormat('Y-m-d H:i:s', '2017-01-10 00:00:00', $timezone);
$holidayPeriod = new DatePeriodOverride($holidayPeriodStart, $holidayPeriodEnd);
$holidayPeriod->setType(OverrideInterface::TYPE_EXCLUDE);

$openingHourChecker->addOverride($holidayPeriod);
```

In this example, store is closed from `$holidayPeriodStart` until `$holidayPeriodEnd`. 

[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/veloxy/opening-hours/master.svg?style=flat-square
[ico-sensio]: https://img.shields.io/sensiolabs/i/7d757865-5835-414c-9591-06ce50bb15a7.svg?maxAge=3600&style=flat-square
[ico-styleci]: https://styleci.io/repos/70743137/shield?branch=master
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/veloxy/opening-hours.svg?style=flat-square

[link-scrutinizer]: https://scrutinizer-ci.com/g/veloxy/opening-hours/code-structure
[link-travis]: https://travis-ci.org/veloxy/opening-hours
[link-sensio]: https://insight.sensiolabs.com/projects/7d757865-5835-414c-9591-06ce50bb15a7
[link-styleci]: https://styleci.io/repos/70743137
