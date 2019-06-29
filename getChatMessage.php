<?php
require("./database/call_database.php");
date_default_timezone_set("Asia/Seoul");
session_start();
$id = $_SESSION['login_details_id'];

if(!isset($id)) {
  header("Location:/03project/site/login.php");
}
$toId = $_POST['toId'];

$query = "SELECT * FROM chat_message WHERE (to_user_id = '$toId' and from_user_id='$id') OR (from_user_id = '$toId' and to_user_id = '$id') ORDER BY timestamp ASC";

$statement = mysqli_query($conn, $query);

$query = "SELECT thumb_path FROM member WHERE nickname='$toId'";

$image_state = mysqli_query($conn, $query);
$image_row = mysqli_fetch_assoc($image_state);

$result = "";
$result .= '<div class="collapse multi-collapse" id="chat_monitor_'.$toId.'" style="max-height:400px; overflow:hidden|auto">';
while($row = $statement->fetch_array()) {
  if($row['to_user_id'] == $toId) {
    $result .= '<div class="outgoing_msg">';
    $result .= '<div class="sent_msg">';
    $result .= '<p>'.$row["chat_message"].'</p>';
    $result .= '<span class="time_date">'.$row["timestamp"].'</span>';
    $result .= "</div>";
    $result .= '</div>';
  } else {
    $result .= '<div class="incoming_msg">';
    $result .= '<div class="incoming_msg_img"><img src="'.$image_row['thumb_path'].'" width="30" height="30"></div>';
    $result .= '<div class="received_msg">';
    $result .= '<div class="received_withd_msg">';
    $result .= '<p>'.$row['chat_message'].'</p>';
    $result .= '<span class="time_date">'.$row["timestamp"].'</span';
    $result .= "</div>";
    $result .= '</div>';
    $result .= "</div>";
  }
}

$result .= '<div class="type_msg">';
$result .= '<div class="input_msg_write">';
$result .= '<input type="text" class="write_msg" placeholder="Type a message"/>';
$result .= '<button id="sendButton" class="btn btn-primary btn-lg btn-block" data-touserid="'.$toId.'" disabled="true">
  전송하기
</button>';
$result .= '</div>';
$result .= '</div>';
$result .= '</div>';
/*$result .= "<script>function sendMessage(){
  alert('complete');
  var toUserId = $(this).data(touserid);
  var chat_message = $('.write_msg').val();
  $.ajax({
    url:'insert_chat.php',
    method:'POST',
    data:{toUserId:toUserId, chat_message:chat_message},
    success:function(data) {
      $('.write_msg').val('');
      $('#chat_monitor_'+toUserId).html(data);
    }
  })
}</script>";*/

echo $result;
?>
