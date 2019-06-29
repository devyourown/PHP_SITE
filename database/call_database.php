<?php
date_default_timezone_set("Asia/Seoul");
$servername = "localhost";
$username = "root";
$password = "gywns6947";
$dbname = "schoolproject";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function fetch_user_last_activity($user_id, $connect) {
  global $conn;
  $query = "SELECT * from login_details WHERE user_id = '$user_id'
  ";
  $statement = mysqli_query($conn, $query);

  while($row = $statement->fetch_array()) {
    return $row['last_activity'];
  }
}
?>
