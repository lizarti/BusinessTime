# BusinessTime


BusinessTime is a Static class that use the native PHP DateTime class for operation within business hours.

---

### Usage

First of all, you need to configure the working hours for each days of week.



```php
/* 
  '$days' is an array of days of week (0: sunday), and each of then can have one or more range of working time.
  All of BusinessTime logic is based on these ranges.

  If a day is without any range, it's considered not a working day.

 */

 // For instance, in a case where weekdays have two ranges (08:00 - 12:00 and 13:30 - 17:30):

$days = [
  [
    
  ],
  [
    ['08:00', '12:00'], ['13:30', '17:30']
  ],
  [
    ['08:00', '12:00'], ['13:30', '17:30']
  ],
  [
    ['08:00', '12:00'], ['13:30', '17:30']
  ],
  [
    ['08:00', '12:00'], ['13:30', '17:30']
  ],
  [
    ['08:00', '12:00'], ['13:30', '17:30']
  ],
  [

  ]
];

/* Then, configure the BusinessTime with 'setDays' method */

BusinessTime::setDays($days);

```

---

### Methods


#### `getWorkingTime (DateTime $from, DateTime $to)`

>Get the working hours between two working DateTime.

return `Float`


#### `addWorkingHours (DateTime $datetime, Float $hours)`

>Add working hours to a DateTime.

return `DateTime`


#### `isWorkingDay (DateTime $datetime)`

>Check if a DateTime is a working day.

*This method check if this DateTime is a holiday and if it has at least one range of working time setted. <br>
Return false if one of these conditions is satisfied.*

return `Bool`

---

### Licence

#### MIT