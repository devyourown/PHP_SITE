<?php
require("./database/call_database.php");
date_default_timezone_set("Asia/Seoul");
session_start();
$id = $_SESSION['login_details_id'];

if(!isset($id)) {
  header("Location:/03project/site/login.php");
}

$title = $_POST['title'];
$text = $_POST['text'];
$date = date("Y-m-d H:i:s", time());
$user_ip = $_SERVER['REMOTE_ADDR'];

$sql = "SELECT thumb_path from member WHERE nickname = '$id'";
$statement = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($statement);
$path = $row['thumb_path'];

$sql = "INSERT INTO list (title, nickname, content, wdate, ip, view, recommend, against, thumb_path) VALUES
        ('$title', '$id', '$text', '$date', '$user_ip', 0, 0, 0, '$path')";
if(mysqli_query($conn, $sql)) {
  $sql = "SELECT id FROM list where (nickname = '$id') and (wdate = '$date')";
  $statement = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($statement);
  $article_id = $row['id'];
  $result = '<tbody>
    <tr id="article_click" data-tonum="'.$article_id.'">
      <th scope="row"><img src="'.$path.'" height="55" width="55"></th>
      <td>'.$title.'</td>
      <td>'.$id.'</td>
      <td>'.$date.'</td>
      <td>0 / 0</td>
      <td>0</td>
    </tr>
  </tbody>';
}

?>
