<?php

require_once "./vendor/autoload.php";

use BusinessTime\BusinessTime;

$days = [
  [
    
  ],
  [
    ['07:30', '12:00'], ['13:30', '17:18']
  ],
  [
    ['07:30', '12:00'], ['13:30', '17:18']
  ],
  [
    ['07:30', '12:00'], ['13:30', '22:00']
  ],
  [
    ['08:00', '12:00'], ['13:30', '17:18']
  ],
  [
    ['07:30', '12:00'], ['13:30', '17:18']
  ],
  [

  ]
];

// $log["start"] = microtime(true);
$now = new DateTime('2018-02-23 11:00');
$days = BusinessTime::setDays($days);

$next = BusinessTime::moveToNextWorkingDay($now);

print_r($next);

// $later = BusinessTime::addWorkingHours($now, 400);


// print_r(BusinessTime::moveToNextWorkingDay($now));

// $log["end"] = microtime(true);
// $log["diff"] = ($log["end"] - $log["start"]);

// echo json_encode($log);

// print_r($later);


