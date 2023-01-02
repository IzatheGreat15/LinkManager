<?php 
// include database configurations
include_once('database/initial_db_config.php');
include_once('general_functions.php');

session_start();

if(isset($_POST["submit"])){
    if(empty($_POST["username"]) || empty($_POST["password"])){
        // error
        header('location: ../login.php?error=emptyfields');
        exit();
    }

    $username = $_POST["username"];
    $password = $_POST["password"];

    $user = user_exists($conn, $username);
    var_dump($user);
    if(!$user){
        // error
        header('location: ../login.php?error=invalidaccount');
        exit();
    }

    if(!password_verify($password, $user["password"])){
        // error
        header('location: ../login.php?error=incorrectpassword');
        exit();
    }

    // start session
    $_SESSION["id"] = $user["id"];
    $_SESSION["username"] = $username;

    // success
    header('location: ../home.php');
}