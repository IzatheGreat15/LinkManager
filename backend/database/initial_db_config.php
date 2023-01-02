<?php 

// define all the database configurations

$server = "localhost";
$uname = "root";
$pass = "";
$database = "linkmanager_db";

// create connection
$conn = new mysqli($server, $uname, $pass);

// check the connection
if($conn->connect_error){
    die("Connection failed " . $conn->connect_error);
}

// create the database automatically
$sql = "CREATE DATABASE IF NOT EXISTS " . $database;
$conn->query($sql);

// connect to the database
$conn = new mysqli($server, $uname, $pass, $database);
// check the connection
if($conn->connect_error){
    die("Connection failed " . $conn->connect_error);
}
