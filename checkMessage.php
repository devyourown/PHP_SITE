<?php

require("./database/call_database.php");
date_default_timezone_set("Asia/Seoul");
session_start();
$id = $_SESSION['login_details_id'];

if(!isset($id)) {
  header("Location:/03project/site/login.php");
}
$toId = $_POST['toId'];
$time = $_POST['time'];

$query = "SELECT * FROM chat_message WHERE (to_user_id = '$toId' and from_user_id='$id') OR (from_user_id = '$toId' and to_user_id = '$id') ORDER BY timestamp ASC";

$statement = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($statement);


$query = "SELECT thumb_path FROM member WHERE nickname='$toId'";

$image_state = mysqli_query($conn, $query);
$image_row = mysqli_fetch_assoc($image_state);


$result = "";
while($row = $statement->fetch_array()) {
  if($time < $row["timestamp"]) {
    if($row['to_user_id'] == $toId) {
      $result .= '<div class="outgoing_msg">';
      $result .= '<div class="sent_msg">';
      $result .= '<p>'.$row["chat_message"].'</p>';
      $result .= '<span class="time_date">'.$row["timestamp"].'</span>';
      $result .= "</div>";
      $result .= '</div>';
    } else {
      $result .= '<div class="incoming_msg">';
      $result .= '<div class="incoming_msg_img"><img src="'.$image_row['thumb_path'].'" width="40" height="40"></div>';
      $result .= '<div class="received_msg">';
      $result .= '<div class="received_withd_msg">';
      $result .= '<p>'.$row['chat_message'].'</p>';
      $result .= '<span class="time_date">'.$row["timestamp"].'</span';
      $result .= "</div>";
      $result .= '</div>';
      $result .= "</div>";
    }
  }
}

echo $result;

?>
