# Basic Usage

To start using the checker, you must first create a TimeTable containing the opening days and times.

## Creating a `TimeTable`

A TimeTable contains your opening hours, it consists of an array of days that each have a set of TimePeriods.
A day that does not have time periods or a day that does not exist in the time table is considered a closed day. 

The following example shows a timetable for a store that is open from monday til friday from 08:00 to 12:00 and 
from 13:00 to 17:00. On saturday it's only open from 08:00 to 12:00 and on sunday it is closed the whole day.

```php
use Sourcebox\OpeningHours\TimeTable;
use Sourcebox\OpeningHours\Day;
use Sourcebox\OpeningHours\TimePeriod;

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
```

## Using the `OpeningHourChecker`

To start checking things, simply pass the `TimeTable` to the `OpeningHourChecker`.

```php
use Sourcebox\OpeningHours\Checker;

$checker = new OpeningHourChecker($timeTable);
```

### Day checks

Check if the store is open on wednesday:

```php
$checker->isOpenOn(Day::WEDNESDAY); // Returns true
```

Check if the store is closed on wednesday:

```php
$checker->isClosedOn(Day::WEDNESDAY); // Returns false
```

### DateTime checks

The following check requires a `\DateTime` object because it checks if the time is between 
any of the days `TimePeriod`'s

Given the data from our TimeTable:

```php
new Day(Day::MONDAY, [
   new TimePeriod('08:00', '12:00'),
   new TimePeriod('13:00', '17:00'),
]),
```

Using the following code we can check if the store is open/closed on the given date.

```php
$nextMonday = new \DateTime('next monday');
$nextMonday->setTime(15, 30); // e.g 31/10/2016 15:30:00

$checker->isOpenAt($nextMonday); // returns true
$checker->isClosedAt($nextMonday); // returns false
```
