<?php
session_start();
include_once('backend/database/initial_db_config.php');
include_once('backend/general_functions.php');

// if user is already loggged in, redirect to home page
if (!isset($_SESSION["id"])) {
    header('location: login.php');
    exit();
} else {
    $user = user_exists($conn, $_SESSION["username"]);
}

$message = "";
$color = "green";
// SUCCESS MESSAGES
if(isset($_GET["success"])){
    $message = "<br>";

    if($_GET["success"] == "linkadded"){
        $message = $message . "Link successfully added";
    }
    if($_GET["success"] == "linkupdated"){
        $message = $message . "Link successfully updated";
    }
    if($_GET["success"] == "linkdeleted"){
        $message = $message . "Link successfully deleted";
    }
}

// ERROR MESSAGES
if(isset($_GET["error"])){
    $message = "<br>";
    $color = "red";

    if($_GET["error"] == "emptyfields"){
        $message = $message . "Please fill in all the fields";
    }
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
    <!-- Include CDN JavaScript -->
    <script src="https://unpkg.com/tailwindcss-jit-cdn"></script>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <style>
        svg,
        a {
            cursor: pointer;
        }

        @media only screen and (max-width: 600px) {
            .ellipsis {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 80px;
            }
        }
    </style>

</head>

<body>
    <!-- ADD/EDIT MODAL -->
    <div class="bg-stone-700/75 h-screen w-screen flex items-center justify-center fixed z-10 modal-bg hidden" id="modal">
        <div class="w-3/4 lg:w-1/2 modal-body">
            <!-- FORM -->
            <form action="backend/crud_links.php" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <br>
                <div class="mb-4">
                    <input type="hidden" name="mode">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Name
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" name="name" placeholder="Meeting 1">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="link">
                        Link
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="link" type="text" name="link" placeholder="https://LinkManager.com">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="time">
                        Time
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="time" type="time" name="time" placeholder="******************">
                </div>
                <div class="flex items-center justify-center">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white w-1/2 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="submit" value="Submit">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- DELETE MODAL -->
    <div class="bg-stone-700/75 h-screen w-full flex items-center justify-center fixed z-10 modal-bg hidden" id="modal-delete">
        <div class="w-3/4 lg:w-1/2 modal-body">
            <!-- FORM -->
            <form action="backend/crud_links.php" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 flex flex-col justify-center">
                <input type="hidden" name="mode">
                <input type="hidden" name="id">
                <br>
                <p class="text-center text-xl font-semibold">Are you sure you want to delete <span id="link_name"></span>?</p>
                <br>
                <br>
                <div class="flex items-center justify-center">
                    <button class="bg-red-500 hover:bg-red-700 w-1/4 mx-2 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="submit" value="Submit">
                        Yes
                    </button>
                    <button class="bg-green-500 hover:bg-green-700 w-1/4 mx-2 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline close" type="button" name="submit">
                        No
                    </button>
                </div>
                <br>
            </form>
        </div>
    </div>

    <div class="flex flex-col h-screen w-screen">
        <!-- NAVBAR -->
        <nav class="bg-blue-500 h-10 w-screen flex items-center p-5 px-10 justify-between text-white fixed">
            <div class="font-semibold">Hi, <?= $user["username"] ?>!</div>
            <a href="backend/logout.php">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
            </a>
        </nav>

        
        <div class="h-screen w-screen flex flex-col lg:flex-row items-center justify-center mt-10">
            <!-- LEFT -->
            <div class="bg-[#1a202e] h-full w-full text-white flex flex-col items-start justify-start p-10 outline outline-offset-0 outline-1 outline-sky-100">

                <div class="flex w-full justify-between sm:flex-row">
                <!-- <input class="shadow appearance-none border rounded w-full sm:w-1/4 py-2 px-3 mr-5 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" name="name" placeholder="Search"> -->

                    <button id="add" class="outline outline-offset-2 outline-blue-500 hover:bg-blue-500 outline-1 flex justify-between rounded-lg px-5 py-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- ERROR MESSAGE -->
                <p class="text-<?= $color ?>-500 italic"><?= $message ?></p>
                <br>

                <table class="w-full">
                    <tr class=" bg-blue-500 p3 outline outline-offset-0 outline-1 outline-blue-500">
                        <th>Name</th>
                        <th>Link</th>
                        <th>Time</th>
                        <th class="w-12 sm:w-24"></th>
                    </tr>

                    <?php
                    $query = "SELECT links.* FROM links JOIN user_links ON user_links.link_id = links.id WHERE user_links.user_id = " . $_SESSION["id"];
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) :
                    ?>
                        <?php foreach ($result as $row) : ?>
                            <tr class="outline outline-offset-0 outline-1 outline-blue-500">
                                <td class="p-3 text-center hidden"><?= $row["id"] ?></td>
                                <td class="p-3 text-center"><?= $row["name"] ?></td>
                                <td class="p-3 text-center ellipsis"><?= $row["link"] ?></td>
                                <td class="p-3 text-center hidden"><?= $row["open_at"] ?></td>
                                <td class="p-3 text-center"><?= date("h:i A", strtotime($row["open_at"])) ?></td>
                                <td class="p-3 w-12 flex flex-col sm:w-24 sm:flex-row justify-between">
                                    <svg class="w-6 h-6 hover:stroke-blue-500 edit" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <svg class="w-6 h-6 hover:stroke-blue-500 delete" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php else : ?>
                        <tr class="outline outline-offset-0 outline-1 outline-blue-500">
                            <td class="p-3 text-center" colspan="4">No links added yet!</td>
                        </tr>
                    <?php endif ?>
                </table>
            </div>

            <!-- RIGHT -->
            <div class="bg-[#1a202e] h-full w-full text-white flex flex-col items-start justify-start p-10 outline outline-offset-0 outline-1 outline-sky-100">
                <div class="flex flex-col justify-start w-full sm:justify-between sm:flex-row">
                    <h1 class="text-2xl font-semibold tracking-wider">Queue</h1>
                    <h1 class="text-md tracking-wider time-info">Time left for <span id="current">CIS 3102</span>: <span id="time-left">01:30:00</span></h1>
                </div>

                <br>

                <table class="w-full" id="queue">
                    <tr class=" bg-blue-500 p3 outline outline-offset-0 outline-1 outline-blue-500">
                        <th>Name</th>
                        <th>Link</th>
                        <th>Time</th>
                    </tr>
                    <tr class="outline outline-offset-0 outline-1 outline-blue-500">
                        <td class="p-3 text-center">Jose Rizal</td>
                        <td class="p-3 text-center ellipsis hover:text-blue-500">
                            <a href="https://tailwindcss.com/docs/outline-color" target="_blank" rel="noopener noreferrer">https://tailwindcss.com/docs/outline-color</a>
                        </td>
                        <td class="p-3 text-center">1:20 AM</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>

<script>
    var flag = 0;

    $(document).ready((e) => {
        update_queue();
    });

    function open_link(link) {
        var win = window.open(link, '_blank');
        if (win) {
            //Browser has allowed it to be opened
            win.focus();
        } else {
            //Browser has blocked it
            alert('Please allow popups for this website');
        }
    }

    function check_timer() {
        var time = $("#queue").find("tr:eq(1)").find("td:eq(2)").text();
        var link = $("#queue").find("tr:eq(1)").find("td:eq(1)").text();

        $.ajax({
            type: "GET",
            url: "backend/timer.php",
            data: {
                time: time
            },
            success: (data) => {
                $("#time-left").text(data);

                if (data === "00:00:00" && flag == 0) {
                    open_link(link);
                    flag = 1;
                    update_queue();
                }
            }
        });
    }

    setInterval(check_timer, 1000);

    function update_queue() {
        flag = 0;
        $.ajax({
            type: "GET",
            url: "backend/get_queue.php",
            success: (data) => {
                console.log(data);
                data = JSON.parse(data);
                var append = '<tr class=" bg-blue-500 p3 outline outline-offset-0 outline-1 outline-blue-500">' +
                    '<th>Name</th>' +
                    '<th>Link</th>' +
                    '<th>Time</th>' +
                    '</tr>';

                if (data.length > 0) {
                    $.each(data, (key, value) => {
                        append += '<tr class="outline outline-offset-0 outline-1 outline-blue-500">' +
                            '<td class="p-3 text-center">' + value.name + '</td>' +
                            '<td class="p-3 text-center ellipsis hover:text-blue-500">' +
                            '<a href="' + value.link + '" target="_blank" rel="noopener noreferrer">' + value.link + '</a>' +
                            '</td>' +
                            '<td class="p-3 text-center">' + value.open_at + '</td>' +
                            '</tr>';
                    });
                } else {
                    append += '<tr class="outline outline-offset-0 outline-1 outline-blue-500">' +
                        '<td class="p-3 text-center" colspan="4">No links added yet!</td>' +
                        '</tr>';
                    $(".time-info").hide();
                }
                $("#queue").empty();
                $("#queue").html(append);

                var first = $("#queue").find("tr:eq(1)").find("td:eq(0)").text();
                $("#current").text(first);

                check_timer();
            }
        });
    }

    /* CLOSING OF THE MODAL */
    // close the modal using the close button and when outside the modal is clicked
    $(".close, .modal-bg").click(function() {
        $(".modal-bg").hide();
    });

    // stop the close modal when inside the modal is clicked
    $(".modal-body").click(function(e) {
        e.stopPropagation();
    })

    /**
     * mode of operations
     * 0 => add
     * actual ID of entry => edit
     * -1 => delete
     *  */
    // OPENING OF MODAL
    $(document).on("click", "#add", (e) => {
        $("#modal").css('display', 'flex');

        $("input").val('');
        $("input[name='mode']").val(0);
    });

    $(document).on("click", ".edit", (e) => {
        $("#modal").css('display', 'flex');

        var id = $(e.currentTarget).parent("td").parent("tr").find("td:eq(0)").text();
        var name = $(e.currentTarget).parent("td").parent("tr").find("td:eq(1)").text();
        var link = $(e.currentTarget).parent("td").parent("tr").find("td:eq(2)").text();
        var time = $(e.currentTarget).parent("td").parent("tr").find("td:eq(3)").text();

        $("input[name='mode']").val(id);
        $("input[name='name']").val(name);
        $("input[name='link']").val(link);
        $("input[name='time']").val(time);
    });

    $(document).on("click", ".delete", (e) => {
        var name = $(e.currentTarget).parent("td").parent("tr").find("td:eq(1)").text();
        var id = $(e.currentTarget).parent("td").parent("tr").find("td:eq(0)").text();

        $("#link_name").text(name);
        $("#modal-delete").css('display', 'flex');

        $("input[name='mode']").val(-1);
        $("input[name='id']").val(id);
    });
</script>

</html>