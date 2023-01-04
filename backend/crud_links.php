<?php
// include database configurations
include_once('database/initial_db_config.php');

session_start();

if(isset($_POST["submit"])){
    $mode = $_POST["mode"];
    $user_id = $_SESSION["id"];

    /**
     * mode of operations
     * 0 => add
     * actual ID of entry => edit
     * -1 => delete
     *  */

    //  check if all inputs are filled out
    foreach($_POST as $key => $value){
        if($value == ""){
            header("location: ../home.php?error=emptyfields");
            exit();
        }
    }

    if($mode == -1){
        $link_id = $_POST["id"];

        // delete
        $query = "DELETE FROM links WHERE id = ". $link_id;

        if ($conn->query($query)) {
            header("location: ../home.php?success=linkdeleted");
            exit();
        } else {
            echo $conn->error;
        }
    }else{
        $name = $_POST["name"];
        $link = $_POST["link"];
        $time = $_POST["time"];

        if($mode == 0){
            // add
            $query = "INSERT INTO links(name, link, open_at) VALUES ('". $name ."', '". $link ."', '". $time ."')";

            if($conn->query($query)){
                $link_id = $conn->insert_id;

                $query = "INSERT INTO user_links(user_id, link_id) VALUES (". $user_id .", ". $link_id .")";
                $conn->query($query);

                header("location: ../home.php?success=linkadded");
                exit();
            }else{
                echo $conn->error;
            }
        }else{
            // edit
            $query = "UPDATE links SET name = '". $name ."', link = '". $link ."', open_at = '". $time ."' WHERE id = ". $mode;

            if($conn->query($query)){
                header("location: ../home.php?success=linkupdated");
                exit();
            }else{
                echo $conn->error;
            }
        }
    }
}
?>