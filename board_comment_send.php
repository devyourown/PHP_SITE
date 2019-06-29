<?php

require('./database/call_database.php');
date_default_timezone_set("Asia/Seoul");
session_start();
$id = $_SESSION['login_details_id'];

if(!isset($id)) {
    header("Location:/03project/site/login.php");
}

if(isset($_POST['alert_num'])) {
  $alert_num = $_POST['alert_num'];
  $sql = "UPDATE board_comment SET checked = '1' WHERE board_comment_id = '$alert_num'";
  if(mysqli_query($conn, $sql)) {
    $sql = "SELECT board_id FROM board_comment WHERE board_comment_id = '$alert_num'";
    $statement = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($statement);
    $boardNumber = $row['board_id'];
    echo $boardNumber;
  }
}
if(isset($_POST['text'])) {
  $text = $_POST['text'];
  $owner_id = $_POST['userid'];
  $board_num = $_POST['num'];

  $sql = "SELECT title, content, wdate FROM list WHERE id='$board_num'";
  $statement = mysqli_query($conn, $sql);
  $board_data = mysqli_fetch_assoc($statement);
  $title = $board_data['title'];

  $sql = "SELECT thumb_path FROM member WHERE nickname = '$id'";
  $statement = mysqli_query($conn, $sql);
  $img_row = mysqli_fetch_assoc($statement);
  $img = $img_row['thumb_path'];

  $sql = "INSERT INTO board_comment (board_id, comment_id, comment, comment_path, checked, board_own_id,
  title)
  VALUES ('$board_num', '$id', '$text', '$img', '0', '$owner_id', '$title')";

  if(mysqli_query($conn,$sql)) {
    echo '<div class="row" id="comment_back">';
    echo   '<div class="col-sm-3">';
    echo    '<div class="well">';
    echo     '<p>'.$id.'</p>';
    echo     '<img src="'.$img.'" class="img-circle" height="55" width="55" alt="Avatar">';
    echo    '</div>';
    echo  '</div>';
    echo  '<div class="col-sm-9">';
    echo    '<div class="well">';
    echo      '<p>'.$text.'</p>';
    echo    '</div>';
    echo  '</div>';
    echo '</div>';
  }
}

?>
