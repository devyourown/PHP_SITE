<?php

require("./database/call_database.php");
date_default_timezone_set("Asia/Seoul");
session_start();
$id = $_SESSION['login_details_id'];

if(!isset($id)) {
  header("Location:/03project/site/login.php");
}

if(isset($_POST['text'])) {
  $text = $_POST['text'];
  $user_id = $_POST['userid'];
  $sql = "SELECT thumb_path FROM member WHERE nickname = '$id'";
  $statement = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($statement);
  $url = $row['thumb_path'];
  $sql = "INSERT INTO friends_comment (home_id, comment_id, comment, friend_path) VALUES ('$user_id', '$id',
  '$text', '$url')";
  if(mysqli_query($conn,$sql)) {
    echo '<div class="row" id="comment_back">';
    echo   '<div class="col-sm-3">';
    echo    '<div class="well">';
    echo     '<p>'.$id.'</p>';
    echo     '<img src="'.$url.'" class="img-circle" height="55" width="55" alt="Avatar">';
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

if(isset($_POST['sns_type'])) {
  $type = $_POST['sns_type'];
  $url = $_POST['sns_url'];
  if(strlen($url) > 100) {
    echo "url이 너무 깁니다.";
  } else {
    $t = strtolower($type);
    switch ($t) {
      case 'instagram':
        $sql = "UPDATE manage_profile SET instagram = '$url' WHERE home_id = '$id'";
        echo "in";
        break;
      case 'facebook':
        $sql = "UPDATE manage_profile SET facebook = '$url' WHERE home_id = '$id'";
        break;
      case 'twitter':
        $sql = "UPDATE manage_profile SET twitter = '$url' WHERE home_id = '$id'";
        break;

    }
    if(mysqli_query($conn, $sql)) {
      echo "등록 완료";
    } else {
      echo "실패";
    }
  }
} else if(isset($_POST['interests'])) {
  $intere = $_POST['interests'];
  if(strlen($intere) > 20) {
    echo "글자 수가 너무 많습니다.";
  } else {
    $sql = "INSERT INTO interests (home_id, interest) VALUES ('$id', '$intere')";
    if(mysqli_query($conn, $sql)) {
      echo "등록 완료";
    } else {
      echo "실패";
    }
  }
} else if(isset($_FILES['image_file'])) {
  $target = "uploads/" . basename($_FILES["image_file"]["name"]);
  $imageFileType = strtolower(pathinfo($target, PATHINFO_EXTENSION));
  $uploadOk = true;

  if(file_exists($target)) {
    echo "이미 존재하는 파일입니다.";
    $uploadOk = false;
  }

  if(strlen($target) > 300) {
    echo "글자수가 너무 많습니다. 줄여주세요.";
    $uploadOk = false;
  }

  if(preg_match("/[\xE0-\xFF][\x80-\xFF][\x80-\xFF]/", $target)) {
    echo "한글을 포함하지 말아주세요.";
    $uploadOk = false;
  }

  if($_FILES['image_file']['size'] > 50000000) {
    echo "파일이 너무 큽니다.";
    $uploadOk = false;
  }

  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
    echo "확장자가 다릅니다.";
    $uploadOk = false;
  }

  if($uploadOk) {
    if(move_uploaded_file($_FILES["image_file"]["tmp_name"], $target)) {
      $sql = "UPDATE member SET thumb_path = '$target' WHERE nickname = '$id'";
      $statement = mysqli_query($conn, $sql);
      mysqli_fetch_assoc($statement);
      $sql = "UPDATE friends_comment SET friend_path = $target WHERE comment_id = '$id'";
      mysqli_query($conn, $sql);
      header("Location:/03project/site/mypage.php");
    }
  } else {
    echo "업로드 실패";
  }
} else if($_POST['text']){
  $text = $_POST['text'];
  $comment_id = $id;
}

?>
