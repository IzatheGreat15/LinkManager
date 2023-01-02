<?php

session_start();

include_once("backend/RunAtFirstUse.php");

// redirect to login page
header("location: login.php");
?>