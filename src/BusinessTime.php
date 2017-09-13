<?php

namespace BusinessTime;

use \DateTime AS DateTime;

class BusinessTime {

  /**
   * Days openings
   *
   * @var array
   */

  protected $days = [];

  /**
   * Get the working hours between two DateTime instances 
   *
   * @param DateTime $from
   * @param DateTime $to
   * @return void
   */

  public static function getWorkingHours (DateTime $from, DateTime $to) {

  }
  
  public static function isBusinessDay (DateTime $datetime) {
    return true; 
  }

} 