<?php
//require_once(dirname(__FILE__) . '/prepend.cli.php');

//cache GA script to improve pagespeed
//script should be run once a day
$ga = file_get_contents('http://www.google-analytics.com/analytics.js');
$file =  __DIR__ . '/../js/analytics.js';
//validate
$test = '(function(){';
if(substr($ga, 0, strlen($test)) == $test)
{
  file_put_contents($file, $ga);
}
else
{
  //todo: error reporting
}
