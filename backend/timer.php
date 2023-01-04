<?php
// include database configurations
include_once('database/initial_db_config.php');

session_start();

if(isset($_REQUEST["time"])){
    // set timezone
    date_default_timezone_set('Asia/Manila');

    // format the opening time of the link
    $time = new DateTime(date("H:i:s", strtotime($_REQUEST["time"])));

    // get current time
    $current_time = new DateTime(date("H:i:s", time()));

    $difference = $current_time->diff($time);

    $hour = strval($difference->h);
    $minute = strval($difference->i);
    $second = strval($difference->s);

    $hour = strlen($hour) == 1 ? "0" . $hour : $hour;
    $minute = strlen($minute) == 1 ? "0" . $minute : $minute;
    $second = strlen($second) == 1 ? "0" . $second : $second;

    echo $hour . ":" . $minute . ":" . $second;
}
?>