<?php

require_once "./vendor/autoload.php";

use BusinessTime\BusinessTime;

$now = new DateTime('2017-09-13 21:00');

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
    ['07:30', '12:00'], ['13:30', '17:18']
  ],
  [
    ['07:30', '12:00'], ['13:30', '17:18']
  ],
  [
    ['07:30', '12:00']
  ]
];

$days = BusinessTime::setDays($days);

$later = BusinessTime::addWorkingHours($now, 1.5);

print_r($later);


