<?php

require_once './vendor/autoload.php';
require_once './src/helpers.php';

use BusinessTime\BusinessTime;

set_time_limit(2);

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

$days = BusinessTime::setDays($days);

$now = new DateTime('2018-02-23 10:40');

$time = '08:00';


// $next = BusinessTime::moveToNextWorkingDay($now);

// print_r($next);

$start = microtime(true);

// for ($n = 0; $n < 5000; $n++) {
//   if ($n % 1000 != 0) {
//     continue;
//   }
  // $later = BusinessTime::addWorkingHours($now, $n);
//   $log[$n]['diff'] = microtime(true) - $start;
// }

// dd($log);


$later = BusinessTime::addWorkingHours($now, 5000);
// 2022-12-09 10:40:00
dd(microtime(true) - $start);

// dd($later);


