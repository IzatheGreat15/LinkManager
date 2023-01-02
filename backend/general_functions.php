<?php 

/**
 * check if the given username already exists in the database
 * @param mixed $conn
 * @param mixed $username
 * @return mixed false if none, else return the entry
 */
function user_exists($conn, $username){
    $query = "SELECT * FROM users WHERE username = '" . $username . "'";
    $result = $conn->query($query);

    return $result->num_rows > 0 ? $result->fetch_assoc() : false;
}