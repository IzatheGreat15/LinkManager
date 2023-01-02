<?php

$sql = "CREATE TABLE IF NOT EXISTS users(
    `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `username` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "\nTable users created successfully";
} else {
    echo "\nError creating table: " . $conn->error;
}
?>