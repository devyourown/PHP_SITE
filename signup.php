<?php
require("./database/call_database.php");
date_default_timezone_set("Asia/Seoul");

if(isset($_POST['signup_email'])) {
  if($form_errors = validate_form()) {
    show_form($form_errors);
  } else {
    process_form();
  }
} else {
  show_form();
}

function show_form($error = '') {
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
  <form class="form-signin" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <h1 class="h3 mb-3 font-weight-normal">회원가입 화면</h1>
    <div>
      <div class="form-group">
        <label style="float:left;">Email</label>
        <input type="email" id="inputEmail" class="form-control" placeholder=
      "이메일을 입력해주세요" name="signup_email" required autofocus>
      </div>
      <div class="form-group">
        <label style="float:left;">닉네임</label>
        <input type="text" id="inputName" placeholder="개성있게 지어보세요!"
        class="form-control" name="signup_nick" required autofocus>
      </div>
    </div>
    <div>
      <div class="form-group">
        <label style="float:left">생년월일</label>
        <input type="date" class="form-control" name="birthDay">
      </div>
    </div>
    <div>
      <div class="form-group">
      <label style="float:left;">비밀번호</label>
    <input style="padding-bottom:0;" type="password" id="inputPassword" class="form-control" placeholder=
    "비밀번호를 입력해주세요" name="signup_pass" required autofocus>
    <input type="password" id="inputPassword" class="form-control" placeholder=
    "비밀번호를 다시 입력해주세요" name="repeat_pass" required autofocus>
    </div>
  </div>
    <button class="btn btn-lg btn-primary btn-block" type="submit"
    >가입하기</button>
    <p class="mt-5 mb-3 text-muted">© 2019</p>
  </form>
  <?php
  if(!$error == '') {
    ?><script>alert(<?php echo $error ?>)</script><?php
  }
  ?>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script type="text/javascript" src="./js/login.js">
  </script>
</body>
<?php
}
function validate_form() {
  $errors = "0";
  $email = $_POST['signup_email'];
  $password = $_POST['repeat_pass'];
  $repeat_password = $_POST['signup_pass'];
  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        if ($password === $repeat_password) {

        } else {
            $errors = "비밀번호가 다릅니다.";
        }
  } else {
        $errors = "이메일을 제대로 입력해주세요.";
  }
  return $errors;
}

function process_form() {
  global $conn;
  $email = $_POST['signup_email'];
  $nickname = $_POST['signup_nick'];
  $birthday = $_POST['birthDay'];
  $password = $_POST['signup_pass'];
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  $sql = "INSERT INTO member (email, birthday, nickname, password) VALUES ('$email',
  '$birthday', '$nickname', '$hashed_password')";
  if(mysqli_query($conn, $sql)) {
    $d = 0;
    $sql = "INSERT INTO login_details (user_id, is_type) VALUES ('$nickname', '$d')";
    $_SESSION['id'] = $email;
    $_SESSION['login_details_id'] = $nickname;
    mysqli_query($conn, $sql);
    $sql = "INSERT INTO manage_profile (home_id) VALUES ('$nickname')";
    if(mysqli_query($conn, $sql)) {
      ?><script>alert("회원가입 성공");</script><?php
      header("Location: /03project/site/login.php");
    }
  } else {
    ?><script>alert("회원가입 실패");</script><?php
    header("Location: /03project/site/signup.php");
  }
}
?>
