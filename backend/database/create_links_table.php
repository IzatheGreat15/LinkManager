<?php

$sql = "CREATE TABLE IF NOT EXISTS links(
    `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `name` varchar(255) NOT NULL,
    `link` varchar(255) NOT NULL,
    `open_at` time DEFAULT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "\nTable links created successfully";
} else {
    echo "\nError creating table: " . $conn->error;
}
?>