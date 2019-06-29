<?php

require("./database/call_database.php");
date_default_timezone_set("Asia/Seoul");
session_start();

global $conn;

$id = $_SESSION["login_details_id"];
$query = "UPDATE login_details
SET last_activity = now()
WHERE user_id = '$id'
";

$statement = mysqli_query($conn, $query);

?>
