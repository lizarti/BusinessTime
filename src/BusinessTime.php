<?php

namespace BusinessTime;

use \DateTime AS DateTime;

class BusinessTime {

  /**
   * Working time days configuration
   *
   * @var Array
   */

  static $days = [
    [
      
    ],
    [
      ['09:00', '17:00']
    ],
    [
      ['09:00', '17:00']
    ],
    [
      ['09:00', '17:00']
    ],
    [
      ['09:00', '12:00']
    ],
    [
      ['09:00', '17:00']
    ],
    [
  
    ]
  ];

  /**
   * Holidays
   *
   * @var array
   */

  protected static $holidays = [];

  /**
   * Get the working hours between two working DateTime
   *
   * @param DateTime $from
   * @param DateTime $to
   * @return Float
   */
  
  public static function getWorkingHours (DateTime $from, DateTime $to) : Float {

  }

  /**
   * Add working hours to a DateTime
   *
   * @param DateTime $from
   * @param Number $hours
   * @return DateTime
   */
  public static function addWorkingHours (DateTime $from, Float $hours) : DateTime {

    $amount = $hours * 3600;
    $qtds = 0;
    while ($amount > 0) {
      $decrement = self::getIntervalFromPeriod($from, $amount);
      
      if ($decrement == 0) {
        $s = microtime(true);
        $from = self::moveToNextWorkingDay($from);
        $t = microtime(true);
      }

      // dd($decrement);
      // $from->modify('+' . $decrement . ' ' . $unit);
      // $from->modify('+' . $decrement . $unit);

      $amount -= $decrement;
      
      $from->modify('+' . $decrement . ' second');

      // $decrement = intval($decrement / 60);
      // $from->modify('+' . $decrement . ' minute');

      // dd($amount);
      
      $qtds++;
    }

    // dd($qtds);
    dd(($t - $s));

    return $from;

  }

  /**
   * Check if a DateTime is a working day
   *
   * @param DateTime $datetime
   * @return Boolean
   */

  public static function isWorkingDay (DateTime $datetime) : Bool {

    if (!self::hasWorkingTime($datetime)) {
      return false;
    }
    return true;

  }

  /**
   * Get the day of the week (0: sunday)
   *
   * @param DateTime $datetime
   * @return Integer
   */

  public static function dayOfWeek (DateTime $datetime) : Int {

    return $datetime->format('w');

  }

  /**
   * Check if a time is a working time
   *
   * @param DateTime $datetime
   * @return Boolean
   */
  public static function isWorkingTime (DateTime $datetime) : Bool {

    if (self::isWorkingDay($datetime)) {
      return self::isTimeBetweenPeriods($datetime);
    }
    return false;

  }

  /**
   * Check if a time is between any of the days periods
   *
   * @param DateTime $datetime
   * @return Boolean
   */

  private static function isTimeBetweenPeriods (DateTime $datetime) : Bool {

    $currentTime = strtotime($datetime->format('H:i'));
    $dayOfWeek = self::dayOfWeek($datetime);

    if (isSet(self::$days[$dayOfWeek])) {
      $periods = self::$days[$dayOfWeek];
    } else {
      return false;
      // throw new \InvalidArgumentException('There is no working time configuration for this day.');
    }

    foreach ($periods AS $period) {

      $start = strtotime($period[0]);
      $end = strtotime($period[1]);

      if ($currentTime >= $start && $currentTime <= $end) {
        return true;
      }
    }

    return false;

  }

  /**
   * Get how much intervals a DateTime has in a period of working hours
   *
   * @param Datetime $datetime
   * @param Int $interval
   * @return Int
   */
  private static function getIntervalFromPeriod (Datetime $datetime, Float $amount) : Float {

    $s = microtime(true);

    $hour = $datetime->format('H');
    $minute = $datetime->format('i');
    $current = clone $datetime->setTime($hour, $minute);

    $dayOfWeek = self::dayOfWeek($datetime);

    if (self::hasWorkingTime($datetime)) {
      $periods = self::$days[$dayOfWeek];
    } else {
      return 0;
      // throw new \InvalidArgumentException('There is no working time configuration for this day.');
    }

    foreach ($periods AS $period) {

      $startHour = DateTime::createFromFormat('H:i', $period[0])->format('H');
      $startMinute = DateTime::createFromFormat('H:i', $period[0])->format('i');

      $endHour = DateTime::createFromFormat('H:i', $period[1])->format('H');
      $endMinute = DateTime::createFromFormat('H:i', $period[1])->format('i');

      $start = clone $current;
      $start->setTime($startHour, $startMinute);
      $end = clone $current;
      $end->setTime($endHour, $endMinute);
      
      if ($current >= $start && $current <= $end) {    

        $interval = ($end->getTimestamp() - $current->getTimestamp());

        $timer = (microtime(true) - $s);
        // dd($timer * 2501);

        if ($amount > $interval) {
          return $interval;
        } else {
          return $amount;
        }

      }
    }

    return 0;

  }

  /**
   * Check if a date is a holiday
   *
   * @param DateTime $datetime
   * @return boolean
   */

  public static function isHoliday (DateTime $datetime) : Bool {
    $date = $datetime->format('Y-m-d');
    if (in_array($date, self::$holidays)) {
      return true;
    }
    return false;
  }


  /**
   * Go to the beginning of the next working day
   *
   * @param Datetime $datetime
   * @return Datetime
   */
  
  public static function moveToNextWorkingDay (Datetime $datetime) : Datetime {

    $datetime->modify('+1 day');

    while (!self::isWorkingDay($datetime)) {
      $datetime->modify('+1 day');
    }

    $dayOfWeek = self::dayOfWeek($datetime);

    $beginningWorkingtime = self::beginningOfWorkingTime($datetime);

    if ($beginningWorkingtime) {
      $hour = date('H', strtotime($beginningWorkingtime));
      $minute = date('i', strtotime($beginningWorkingtime));
      $nextWorkingDay = $datetime->setTime($hour, $minute);
      return $nextWorkingDay;
    }
  }

  /**
   * Get the time of the beggining of the working time
   *
   * @param Datetime $datetime
   * @return String
   */
  private static function beginningOfWorkingTime (Datetime $datetime) : String {
    
    if (!self::hasWorkingTime($datetime)) {
      return false;
    }

    $dayOfWeek = self::dayOfWeek($datetime);

    return self::$days[$dayOfWeek][0][0];
  } 
  
  private static function hasWorkingTime (Datetime $datetime) : Bool {

    $dayOfWeek = self::dayOfWeek($datetime);

    if (count(self::$days[$dayOfWeek]) != 0) {
      return true;
    }

    return false;
  }

    /**
   * Set days and working times;
   *
   * @param Array $days
   * @return Array
   */
  
  public static function setDays (Array $days) : Array {
    
    self::$days = $days;
    return self::$days;

  }
  
  /**
   * Set holidays
   *
   * @param Array $days
   * @return void
   */

  public static function setHolidays (Array $days) : Array {
    self::$days = $days;
    return self::$days;
  }

}
