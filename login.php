<?php
session_start();
// if user is already loggged in, redirect to home page
if(isset($_SESSION["id"])){
    header('location: home.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>LinkManager</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>
    <!-- ERROR MESSAGES -->
    <?php
    $error = "";
    if(isset($_GET["error"])){
        if($_GET["error"] == "emptyfields"){
            $error = "Please fill in all the fields";
        }
        if($_GET["error"] == "invalidaccount"){
            $error = "Account does not exist";
        }
        if($_GET["error"] == "incorrectpassword"){
            $error = "Incorrect Password";
        }
    }
    ?>
    <div class="bg-blue-500 h-screen w-full flex items-center justify-center">
        <div class="w-3/4 lg:w-1/2">
            <!-- FORM -->
            <form action="backend/login.php" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <p class="text-red-500 text-xs italic"><?= $error ?></p>
                <br>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                        Username
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" type="text" name="username" placeholder="Username">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Password
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" name="password" placeholder="******************">
                </div>
                <div class="flex items-center justify-between">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="submit">
                        Login
                    </button>
                    <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="register.php">
                        Create an Account?
                    </a>
                </div>
            </form>
            <p class="text-center text-white text-xs">
                &copy;2022 IzatheGreat. All rights reserved.
            </p>
        </div>
    </div>
</body>

</html>