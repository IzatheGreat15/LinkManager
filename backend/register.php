<?php 
// include database configurations
include_once('database/initial_db_config.php');
include_once('general_functions.php');

session_start();

if(isset($_POST["submit"])){
    if(empty($_POST["username"]) || empty($_POST["password"])){
        // error
        header('location: ../register.php?error=emptyfields');
        exit();
    }

    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); //don't forget to hash the password

    $user = user_exists($conn, $username);

    if($user){
        // error
        header('location: ../register.php?error=duplicateusername');
        exit();
    }

    $query = "INSERT INTO users(username, password) VALUES (?, ?)";
    $sql = $conn->prepare($query);

    $sql->bind_param('ss', $username, $password);

    if($sql->execute()){
        // success
        // start session
        $_SESSION["id"] = $conn->insert_id;
        $_SESSION["username"] = $username;

        header('location: ../home.php');
        exit();
    }else{
        // error
        echo $conn->error;
    }
}