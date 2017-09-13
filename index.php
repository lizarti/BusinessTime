<?php

use BusinessTime\BusinessTime;

$now = new DateTime;

echo BusinessTime::isBusinessDay($now);
