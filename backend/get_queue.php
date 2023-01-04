<?php
// include database configurations
include_once('database/initial_db_config.php');

session_start();

if(isset($_SESSION["id"])){
    // get all links before the current time
    $past = "SELECT links.* 
            FROM links JOIN user_links ON user_links.link_id = links.id 
            WHERE user_links.user_id = ". $_SESSION["id"] ." 
            AND links.open_at <= NOW()
            ORDER BY links.open_at ASC;";
    $result = $conn->query($past);
    $past = $result->fetch_all(MYSQLI_ASSOC);
    
    // get all links after the current time
    $future = "SELECT links.* 
            FROM links JOIN user_links ON user_links.link_id = links.id 
            WHERE user_links.user_id = ". $_SESSION["id"] ." 
            AND links.open_at > NOW()
            ORDER BY links.open_at ASC;";
    $result = $conn->query($future);
    $future = $result->fetch_all(MYSQLI_ASSOC);

    // merge two array
    $all = array_merge($future, $past);

    // format open_at
    for($x = 0; $x < count($all); $x++){
        $all[$x]["open_at"] = date("h:i A", strtotime($all[$x]["open_at"]));
    }

    /**
     * arrangement of queue
     * 1: links from the future ASCENDING order
     * 2: links from the past ASCENDING order
     */

    echo json_encode($all);
}
?>