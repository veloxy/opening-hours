# Opening Hours

[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![SensioLabs Insight][ico-sensio]][link-sensio]
[![StyleCI][ico-styleci]][link-styleci]

This is a small library that helps you do several checks on opening hours.

## Requirements

- PHP 7.0+

## Installation

This package is not yet submitted to composer because it is still in development.

## Quick Usage
 
You can easily figure out the usage by checking out the tests, but here's a quick example:

```php
# Create a new checker with a timetable.
$checker = new OpeningHourChecker(new TimeTable([
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
]));

# Check if it's open on a certain day.
$checker->isOpenOn(Day::TUESDAY); // true
$checker->isOpenOn(Day::SUNDAY); // false

# Check if it's open on a certain date and time (2016-10-10 is on a monday).
$checker->isOpenAt(\DateTime::createFromFormat('Y-m-d H:i:s', '2016-10-10 10:00:00'))); // returns true
$checker->isOpenAt(\DateTime::createFromFormat('Y-m-d H:i:s', '2016-10-10 12:30:00'))); // returns false
```

[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/veloxy/opening-hours/master.svg?style=flat-square
[ico-sensio]: https://img.shields.io/sensiolabs/i/7d757865-5835-414c-9591-06ce50bb15a7.svg?maxAge=3600&style=flat-square
[ico-styleci]: https://styleci.io/repos/70743137/shield?branch=master

[link-travis]: https://travis-ci.org/veloxy/opening-hours
[link-sensio]: https://insight.sensiolabs.com/projects/7d757865-5835-414c-9591-06ce50bb15a7
[link-styleci]: https://styleci.io/repos/70743137
