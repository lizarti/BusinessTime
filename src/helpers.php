<?php

if (!function_exists('dd')) {
  /**
   * Dump the passed variables and end the script.
   *
   * @param  mixed
   * @return void
   */
  function dd($arg)
  {
    var_dump($arg);
    exit;
  }
}
