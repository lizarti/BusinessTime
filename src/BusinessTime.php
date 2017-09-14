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

  protected static $days = [];

  /**
   * Holidays
   *
   * @var array
   */

  protected static $holidays = [];

  /**
   * Get the working hours between two DateTime instances
   *
   * @param DateTime $from
   * @param DateTime $to
   * @return void
   */
  
  public static function getWorkingHours (DateTime $from, DateTime $to) {

  }

  /**
   * Add working hours to a DateTime
   *
   * @param DateTime $from
   * @param Number $hours
   * @param String $type
   * @return DateTime
   */
  public static function addWorkingHours (DateTime $from, Float $hours, String $type = 'minute') {

    switch ($type) {
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
      $from->modify('+1 ' . $type);
      if (self::isWorkingTime($from)) {
        $interval--;
      }
    }

    return $from;

  }

  /**
   * Check if is a working day
   *
   * @param DateTime $datetime
   * @return Boolean
   */

  public static function isWorkingDay (DateTime $datetime) {

    $dayOfWeek = self::dayOfWeek($datetime);
    if ($dayOfWeek === 0 || $dayOfWeek === 6) {
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

  public static function dayOfWeek (DateTime $datetime) {

    return $datetime->format('w');

  }

  /**
   * Check if a time is a working time
   *
   * @param DateTime $datetime
   * @return Boolean
   */
  public static function isWorkingTime (DateTime $datetime) {

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

  private static function isTimeBetweenPeriods (DateTime $datetime) {

    $currentTime = strtotime($datetime->format('H:ii'));
    $dayOfWeek = self::dayOfWeek($datetime);

    if (isSet(self::$days[$dayOfWeek])) {
      $periods = $days[$dayOfWeek];
    } else {
      throw new \InvalidArgumentException('There is no working time configuration for this day.');
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
   * Check if a date is a holiday
   *
   * @param DateTime $datetime
   * @return boolean
   */

  public static function isHoliday (DateTime $datetime) {
    $date = $datetime->format('Y-m-d');
    if (in_array($date, self::$holidays)) {
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

  public static function setDays (Array $days) {
    
    self::$days = $days;
    return self::$days;

  }
  
  /**
   * Set holidays
   *
   * @param Array $days
   * @return void
   */

  public static function setHolidays (Array $days) {
    self::$days = $days;
    return self::$days;
  }


}
