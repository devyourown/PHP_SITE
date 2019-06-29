<?php
require("./database/call_database.php");
date_default_timezone_set("Asia/Seoul");
session_start();
$id = $_SESSION['login_details_id'];

if(!isset($id)) {
  header("Location:/03project/site/login.php");
}


$toId = $_POST['toUserId'];
$m = $_POST['chat_message'];
$status = '1';

$query = "INSERT INTO chat_message (to_user_id, from_user_id, chat_message, status) VALUES
('$toId', '$id', '$m', '$status')";

$statement = mysqli_query($conn, $query);

$query = "SELECT timestamp FROM chat_message WHERE to_user_id = '$toId' and from_user_id = '$id' and chat_message = '$m'";

$statement = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($statement);

$result = "";
$result .= '<div class="outgoing_msg">';
$result .= '<div class="sent_msg">';
$result .= '<p>'.$_POST["chat_message"].'</p>';
$result .= '<span class="time_date">'.$row["timestamp"].'</span>';
$result .= "</div>";
$result .= '</div>';

echo $result;

?>
