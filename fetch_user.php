<?php
require("./database/call_database.php");
date_default_timezone_set("Asia/Seoul");
session_start();

$id = $_SESSION['id'];

if(!isset($id)) {
  header("Location:/03project/site/login.php");
}

$query = "SELECT * FROM member WHERE email != '$id'
";

$statement = mysqli_query($conn, $query);


$output = '
<table class="table table-striped" style="text-align:left;">
<tr>친구 목록</tr>
<thead>
  <tr>
    <th scope="col">상태</th>
    <th scope="col">닉네임</th>
  </tr>
</thead>
';

while($row = $statement->fetch_array()) {
  global $output;
  $status = '';
  $current_timestamp = strtotime(date("Y-m-d H:i:s") . '-100 second');
  $current_timestamp = date("Y-m-d H:i:s", $current_timestamp);
  $user_last_activity = fetch_user_last_activity($row['nickname'], $conn);
#여기 부분 확인
  if($user_last_activity > $current_timestamp) {
    $status = '<span class="badge badge-success">온라인</span>';
  } else {
    $status = '<span class="badge badge-danger">오프라인</span>';
  }
  $name = $row['nickname'];
  $output .= '
  <tbody>
  <tr>
    <td>'.$status.'</td>
    <td><button id="moving_friend_home" type="button">'.$name.'</button></td>
  </tr>
  ';
}

$output .= '
</tbody>
</table>';

echo $output;
/*
<tbody>
  <tr>
    <td><span class="badge badge-success">온라인</span></td>
    <td>효준</td>
  </tr>
  <tr>
    <td><span class="badge badge-danger">오프라인</span></td>
    <td>수현</td>
  </tr>
  <tr>
    <td><span class="badge badge-warning">대기중</span></td>
    <td>은정</td>
</tbody>
</table>
'*/

?>
