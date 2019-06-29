<?php
require('./database/call_database.php');
date_default_timezone_set("Asia/Seoul");
session_start();
$id = $_SESSION['id'];
$article_num = $_GET['article_num'];

if(!isset($id)) {
    header("Location:/03project/site/login.php");
}

//조회수를 위한 쿠키 생성
if(!isset($article_num)) {
  ?><script>alert("글이 존재하지 않습니다.");</script><?php
  header("Location:/03project/site/list.php");
} else if(!isset($_COOKIE['board_cookie_'.$article_num])) {
  $sql = "UPDATE list SET view = view + 1 WHERE id='$article_num'";
  if($result = mysqli_query($conn, $sql)) {
    setcookie('board_cookie_'.$article_num, TRUE, time()+(60*60*24), '/');
  }
}

$sql = "SELECT * FROM list WHERE id = '$article_num'";
if($statement = mysqli_query($conn, $sql)) {
  $row = mysqli_fetch_assoc($statement);
  $board_owner = $row['nickname'];
} else {
  ?><script>alert("글이 존재하지 않습니다.");</script><?php
  header("Location:/03project/site/list.php");
}

$query = "SELECT * FROM member WHERE email != '$id'";

$statement = mysqli_query($conn, $query);

$sql = "SELECT * FROM board_comment WHERE board_id = '$article_num' ORDER BY board_comment_id DESC";

$comments_state = mysqli_query($conn, $sql);

?>

<!DOCTYPE HTML>
<html>
  <head>
    <title>재밌는 자료</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="./css/show_list.css">
  </head>
  <body>

    <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php">커뮤니티</a>
      </div>
      <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav">
          <li><a href="mypage.php">Home</a></li>
          <li class="active"><a href="list.php">자료모음</a></li>
        </ul>
        <form class="navbar-form navbar-right" role="search">
          <div class="form-group input-group">
            <input type="text" class="form-control" placeholder="Search..">
            <span class="input-group-btn">
              <button class="btn btn-default" type="button">
                <span class="glyphicon glyphicon-search"></span>
              </button>
            </span>
          </div>
        </form>
        <ul class="nav navbar-nav navbar-right">
          <li><button type="button" class="btn btn-primary btn_badge_m navbar-btn" data-toggle="modal" data-target="#message_modal" id="buttons">
            메시지 <!--<span class="badge badge-light">4</span>-->
          </button></li>
          <li><button type="button" class="btn btn-danger btn_badge_r navbar-btn" data-toggle="modal" data-target="#reply_modal" id="buttons">
            답글 <!--<span class="badge badge-light">4</span>-->
          </button></li>
          <!--<li><a id="sign_btn" cßlass="btn btn-sm btn-outline-primary" href="signup.php">회원가입</a></li>
          <li><a class="btn btn-sm btn-outline-secondary" href="login.php">로그인</a></li>-->
          <li><a href="mypage.php"><span class="glyphicon glyphicon-user"></span> 내 계정</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <a class="btn btn-info" rolw="button" href="list.php">돌아가기</a>

  <div id="whole_blog">
    <div class="blog-header">
      <h1 class="blog-title">글 제목 : <?php echo $row['title']; ?></h1>
      <p class="lead blog-description">작성자 : <?php echo $row['nickname'] . ' / 작성날짜 : ' . $row['wdate']?></p>
    </div>
    <div class="row">
      <div class="blog-main">
        <div class="blog-post">
          <table class="table table-striped">
            <tr>
              <td><img src='<?php echo $row["thumb_path"]; ?>' height="55" width="55"></td>
              <td>IP : <?php echo substr($row['ip'], 0, 6); ?></td>
              <td>조회수 : <?php echo $row['view']?></td>
              <td>추천 / 반대 : <?php echo $row['recommend'] . '/' . $row['against']; ?></td>
            </tr>
          </table>
          <p class="col-md-12">
            <?php echo $row['content'];?>
                        나는 읽기 쉬운 마음이야
              당신도 스윽 훑고 가셔요
              달랠 길 없는 외로운 마음 있지
              머물다 가셔요 음
              내게 긴 여운을 남겨줘요
              사랑을 사랑을 해줘요
              할 수 있다면 그럴 수만 있다면
              새하얀 빛으로 그댈 비춰 줄게요
              그러다 밤이 찾아오면
              우리 둘만의 비밀을 새겨요
              추억할 그 밤 위에 갈피를 꽂고 선
              남몰래 펼쳐보아요
              나의 자라나는 마음을
              못 본채 꺾어 버릴 수는 없네
              미련 남길바엔 그리워 아픈 게 나아
              서둘러 안겨본 그 품은 따스할 테니
              그러다 밤이 찾아오면
              우리 둘만의 비밀을 새겨요
              추억할 그 밤 위에 갈피를 꽂고 선
              남몰래 펼쳐보아요
              언젠가 또 그날이 온대도
              우린 서둘러 뒤돌지 말아요
              마주보던 그대로 뒷걸음치면서
              서로의 안녕을 보아요
              피고 지는 마음을 알아요 다시 돌아온 계절도
              난 한 동안 새 활짝 피었다 질래 또 한번 영원히
              그럼에도 내 사랑은 또 같은 꿈을 꾸고
              그럼에도 꾸던 꿈을 미루진 않을래
          </p>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="well">
    <label for="comment" style="float:left;">댓글:</label>
    <textarea class="form-control" rows="3" id="comment"></textarea>
    &nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary navbar_btn" id="comment_sending" style="float:right;" data-user="<?php
    echo $board_owner; ?>" data-boardnum="<?php echo $article_num; ?>">달기</button>
    </div>
  </div>

  <?php while($comments_row = $comments_state->fetch_array()) : ?>
  <div class="row" id="comment_back">
    <div class="col-sm-3">
      <div class="well">
       <p><?php echo $comments_row["comment_id"]; ?></p>
       <img src="<?php echo $comments_row["comment_path"]; ?>" class="img-circle" height="55" width="55" alt="Avatar">
      </div>
    </div>
    <div class="col-sm-9">
      <div class="well">
        <p><?php echo $comments_row["comment"]; ?></p>
      </div>
    </div>
  </div>
<?php endwhile; ?>


  <!--메시지 모달 -->
  <div class="modal fade" id="message_modal" tabindex="-1" role="dialog" aria-labelledby="modal_main" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_main">채팅</h5>
        <input type="text" class="search_bar" placeholder="채팅기록검색">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php while($data = $statement->fetch_array()) : ?>
        <a href="<?php echo '#chat_monitor_' . $data['nickname'];?>" class="list-group-item list-group-item-action" data-toggle="collapse" data-touserid="<?php echo $data['nickname']; ?>" id="start_chat">
        <div class="media start_chat" id="user_modal_details">
          <img id="user_photo" class="img-thumbnail" src="<?php echo $data['thumb_path']; ?>" width="65" height="65" alt="Generic placeholder image">
          <div class="media-body">
            <h5 class="mt-0" id="user_name"><?php echo $data['nickname']; ?></h5>
            <!--<span class="badge badge-danger" id="info_span">1</span><p id="chat_content"></p> -->
            <!-- <span class="user_date">8월 15일</span> -->
          </div>
        </div>
        </a>
      <?php endwhile; ?>

        <!--메시지 전송 모달 -->
        <div id="send_modal"></div>
      </div>

      </div>
    </div>
  </div>
  </div>

  <!-- 알림 모달  -->
  <div class="modal fade" id="reply_modal" tabindex="-3" role="dialog" aria-labelledby="reply_modal_main" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reply_modal_main">답글 알림</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php while($alert_data = $alert_statement->fetch_array()) : ?>
        <a class="list-group-item list-group-item-action"
          data-toggle="collapse" id="alert_modal" data-number="<?php echo $alert_data['board_comment_id'];?>">
          <div class="media">
            <img id="reply_thumnails" class="img-thumbnail" src="<?php echo $alert_data['comment_path']; ?>" alt="reply_modal_thumbnail" width="55" height="55">
            <div class="media-body">
              <h5 class="mt-0" id="article-name"><?php echo $alert_data['title']; ?></h5>
              <!--<span class="badge badge-danger" id="info_span">1</span>-->
              <p id="reply_content"><?php echo $alert_data['comment']; ?></p>
              <span class="reply_date"><?php echo $alert_data['comment_id']; ?></span>
            </div>
          </div>
        </a>
      <?php endwhile; ?>
      </div>
    </div>
  </div>
  </div>

</body>

<script>

$(document).ready(function() {
  var checkOpen = 0;

  $('#start_chat').click(function() {
    var to_user_id = $(this).data('touserid');
    if (0 == checkOpen) {
      $.ajax({
        url:"getChatMessage.php",
        method:"POST",
        data:{toId:to_user_id},
        success:function(data) {
          $('#send_modal').html(data);
        }
      })

      setInterval(function(){
        refresh_chat(to_user_id);
      }, 5000);

      checkOpen = 1;
    } else {
      //$("#chat_monitor_"+to_user_id).hide();
      checkOpen = 0;
    }

  });

  $(document).on('click', '#sendButton',function(){
      var toUserId = $(this).data('touserid');
      var chat_message = $(".write_msg").val();
      $.ajax({
        url:"insert_chat.php",
        method:"POST",
        data:{toUserId:toUserId, chat_message:chat_message},
        success:function(data) {
          $('.write_msg').val('');
          //$('#chat_monitor_'+toUserId).before(data);
          $(data).insertBefore(".type_msg");
        }
      })
  });

  $(document).on('keydown', ".write_msg", function(key) {
    var chat = $(".write_msg").val();
    if(key.keyCode == 8) {
      if(chat.length == 1) {
        $("#sendButton").prop('disabled', true);
      }
    }
  });

  $(document).on("keypress", ".write_msg",function(key) {
    $("#sendButton").prop('disabled', false);

    if(key.keyCode == 13) {
      var toUserId = $("#sendButton").data('touserid');
      var chat_message = $(".write_msg").val();
      $.ajax({
        url:"insert_chat.php",
        method:"POST",
        data:{toUserId:toUserId, chat_message:chat_message},
        success:function(data) {
          $('.write_msg').val('');
          //$('#chat_monitor_'+toUserId).before(data);
          $(data).insertBefore(".type_msg");
        }
      })
    }
  });

  function refresh_chat(to_user_id) {
    //var to_user_id = $(this).data('touserid');
    var time = $(".time_date:last").text();
    $.ajax({
      url:"checkMessage.php",
      method:"POST",
      data:{toId:to_user_id,time:time},
      success:function(data) {
          $(data).insertBefore(".type_msg");
      }
    })
  }

  $("#comment_sending").click(function() {
    var text = $("#comment").val();
    var touserid = $(this).data('user');
    var boardnum = $(this).data('boardnum');
    $.ajax({
      url:"board_comment_send.php",
      method:"POST",
      data:{text:text, userid:touserid, num:boardnum},
      success:function(data) {
        $('#comment').val('');
        //$('#chat_monitor_'+toUserId).before(data);
        $(data).insertBefore("#comment_back");
      }
    })
  });

  $("#alert_modal").click(function() {
    var num = $(this).data('number');
    $.ajax({
      url:"board_comment_send.php",
      method:"POST",
      data:{alert_num:num},
      success:function(data) {
        window.location.replace("show_list.php?article_num="+data);
      }
    })
  });
})
</script>
