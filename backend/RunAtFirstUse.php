<?php

include_once("database/initial_db_config.php");

// check connection
if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

// create tables
include_once("database/create_users_table.php");
include_once("database/create_links_table.php");
include_once("database/create_users_links_table.php");