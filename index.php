<?php

require_once "./vendor/autoload.php";

use BusinessTime\BusinessTime;

$now = new DateTime;

echo BusinessTime::isBusinessDay($now);
