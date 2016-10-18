# Opening Hours

This is a small library that helps you do several checks on opening hours.

## Requirements

- PHP 7.0+

## Installation

This package is not yet submitted to composer because it is still in development.

## Usage
 
You can easily figure out the usage by checking out the tests, but here's a quick example:

```php
$openingHourChecker = new OpeningHourChecker(new TimeTable([
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

$openingHourChecker->isOpenOn(Day::TUESDAY); // true
$openingHourChecker->isOpenOn(Day::SUNDAY); // false

# 2016-10-10 is on a monday.
$openingHourChecker->isOpenAt(
    \DateTime::createFromFormat('Y-m-d H:i:s', '2016-10-10 10:00:00')
)); // returns true

$openingHourChecker->isOpenAt(
    \DateTime::createFromFormat('Y-m-d H:i:s', '2016-10-10 12:30:00')
)); // returns false
```
