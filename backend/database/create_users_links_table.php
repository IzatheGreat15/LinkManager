<?php

$sql = "CREATE TABLE IF NOT EXISTS user_links(
    `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `user_id` int(11) UNSIGNED NOT NULL,
    `link_id` int(11) UNSIGNED DEFAULT NULL,
    CONSTRAINT FK_UserID FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
)";

$query = "ALTER TABLE orders 
                ADD CONSTRAINT FK_LinkID
                FOREIGN KEY IF NOT EXISTS(link_id) REFERENCES link(id) 
                ON DELETE CASCADE ON UPDATE CASCADE";

if ($conn->query($sql) === TRUE) {
    echo "\nTable user_links created successfully";
} else {
    echo "\nError creating table: " . $conn->error;
}
?>