<?php
require('./database/call_database.php');
date_default_timezone_set("Asia/Seoul");
session_start();
unset($_SESSION['id']);

if(isset($_POST['email'])) {
  if($form_errors = process_form()) {
    show_form($form_errors);
  }
} else {
  show_form();
}


function show_form($errors = '') {
?>
<!DOCTYPE HTML>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>로그인 화면</title>
  <link rel="stylesheet" href="./css/login.css"/>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body class="text-center">
  <form class="form-signin" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div class="form-group">
    <h1 class="h3 mb-3 font-weight-normal">로그인 화면</h1>
    <label style="float:left;">Email</label>
    <input type="email" id="inputEmail" class="form-control" placeholder=
    "이메일을 입력해주세요" required autofocus name="email">
    <label style="float:left;">Password</label>
    <input type="password" id="inputPassword" class="form-control" placeholder=
    "비밀번호를 입력해주세요" required autofocus name="password">
    <?php if(!$errors == '') {
      echo "<p><b>{$errors}</b></p>";
    } ?>
  </div>
    <button class="btn btn-lg btn-primary btn-block" type="submit"
    >접속하기</button>
    <a style="margin-top: 10px;" href="signup.php">아이디가 없으신가요?</a>
    <p class="mt-5 mb-3 text-muted">© 2019</p>

  </form>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script type="text/javascript" src="./js/login.js">
  </script>
</body>
<?php
}

function process_form() {
  global $conn;
  $email = $_POST['email'];
  $password = $_POST['password'];
  $sql = "SELECT email, password, nickname FROM member WHERE email ='$email';";
  $result = mysqli_query($conn, $sql);
  $exist = mysqli_num_rows($result);
  $row = mysqli_fetch_assoc($result);

  $errors;

  if($exist > 0) {
    if(password_verify($password, $row['password'])) {
      $_SESSION['id'] = $row['email'];
      $_SESSION['login_details_id'] = $row['nickname'];



      
      header("Location: /03project/site/mypage.php");
    } else {
      $errors = "아이디 또는 비밀번호가 다릅니다.";
    }
  } else {
    $errors = "아이디 또는 비밀번호가 다릅니다.";
  }

  return $errors;

}
?>
