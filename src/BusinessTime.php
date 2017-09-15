<?php

namespace BusinessTime;

use \DateTime AS DateTime;

class BusinessTime {
  
  /**
   * HOUR MULTIPLIER CONSTANTS
   */

  const HOURS = 1;
  const MINUTES = 60;
  const SECONDS = 3600;

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
   * @param String $unit
   * @return DateTime
   */
  public static function addWorkingHours (DateTime $from, Float $hours, String $unit = 'minute') : DateTime {


    switch ($unit) {
      case 'hour': 
        $multiplier = 1;
        break;
      case 'minute': 
        $multiplier = 60;
        break;
      case 'second': 
        $multiplier = 3600;
        break;
      default: 
        $multiplier = 60;
    }

    $interval = floor($hours * $multiplier);

    while ($interval > 0) {
      $decrement = self::getIntervalFromPeriod($from, $multiplier, $interval);
      
      if ($decrement == 0) {
        $from = self::moveToNextWorkingDay($from);
      }
      $interval -= $decrement;
      $from->modify('+' . $decrement . ' ' . $unit);
      
    }

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
   * @param Int $multiplier
   * @param Int $interval
   * @return Int
   */
  private static function getIntervalFromPeriod (Datetime $datetime, Int $multiplier, Int $interval) : Int {

    $currentTime = strtotime($datetime->format('H:i'));
    $dayOfWeek = self::dayOfWeek($datetime);

    if (isSet(self::$days[$dayOfWeek])) {
      $periods = self::$days[$dayOfWeek];
    } else {
      return 0;
      // throw new \InvalidArgumentException('There is no working time configuration for this day.');
    }

    foreach ($periods AS $period) {

      $start = strtotime($period[0]);
      $end = strtotime($period[1]);

      if ($currentTime >= $start && $currentTime <= $end) {        

        $diffInterval = floor(($end - $currentTime) / $multiplier);

        if ($interval >= $diffInterval) {
          return floor(($end - $currentTime) / $multiplier);
        } else {
          return $interval - floor(($currentTime - $start) / $multiplier);
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
