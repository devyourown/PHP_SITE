<!-- refrence: https://www.w3schools.com/bootstrap/tryit.asp?filename=trybs_temp_social&stacked=h -->
<?php
/* database 만들기
CREATE TABLE mypage (nickname VARCHAR(20) NOT NULL, message, alert, comment, feeling, interests,)
CREATE TABLE mycomment(writer VARCHAR(20) NOT NULL, content VARCHAR(255))
*/

require("./database/call_database.php");
date_default_timezone_set("Asia/Seoul");
session_start();
$id = $_SESSION['id'];
$nickname = $_SESSION['login_details_id'];
if(isset($_GET['nickname'])) {
$home_owner = $_GET['nickname'];
}

if(!isset($id)) {
  header("Location:/03project/site/login.php");
}

if(isset($home_owner)) {
  $sql = "SELECT * FROM member WHERE nickname = '$home_owner'";
  $get_statement = mysqli_query($conn, $sql);
  $owner_row = mysqli_fetch_assoc($get_statement);
}

$query = "SELECT * FROM member WHERE email = '$id'";

$statement = mysqli_query($conn, $query);

$row = mysqli_fetch_assoc($statement);
$nick = $row['nickname'];

$query = "SELECT * FROM chat_message Where from_user_id = '$nick'";
$message_db = mysqli_query($conn, $query);

$query = "SELECT * FROM member WHERE email != '$id'";

$statement = mysqli_query($conn, $query);


function getImage($email) {
  global $conn;
  $query = "SELECT thumb_path FROM member where email = '$email'";
  $statement = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($statement);
  echo $row['thumb_path'];
  return $row['thumb_path'];
}
if(isset($home_owner)) {
  $query = "SELECT * FROM friends_comment where home_id = '$home_owner'";
} else {
  $query = "SELECT * FROM friends_comment where home_id = '$nickname'";
}

$comments_state = mysqli_query($conn, $query);

$alert_sql = "SELECT * FROM board_comment WHERE board_own_id = '$nickname' and checked = '0'";
$alert_statement = mysqli_query($conn, $alert_sql);

?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <title>커뮤니티</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="./css/index.css">

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
        <li class="active"><a href="mypage.php">Home</a></li>
        <li><a href="list.php">자료모음</a></li>
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
        <!--<li><a id="sign_btn" class="btn btn-sm btn-outline-primary" href="signup.php">회원가입</a></li>
        <li><a class="btn btn-sm btn-outline-secondary" href="login.php">로그인</a></li>-->
        <li><a href="mypage.php"><span class="glyphicon glyphicon-user"></span> 내 계정</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container text-center">
  <div class="row">
    <div class="col-sm-3 well">
      <div class="well">
        <?php if(isset($home_owner)) {
          echo '<p><button type="button"class="btn btn_badge_m" disabled="true">Profile</button></p>';
        } else {
          echo '<p><button type="button" id="myProfile" class="btn btn_badge_m" data-toggle="modal" data-target="#profile_modal">My Profile</button></p>';
        }?>
        <img src="<?php echo (isset($home_owner)) ? $owner_row['thumb_path'] : $row['thumb_path']; ?>" class="img-circle" height="65" width="65" alt="Avatar">
      </div>

      <?php //여기부터 프로필 db 연결
      if(isset($home_owner)) {
        $query = "SELECT * FROM interests WHERE home_id = '$home_owner'";
      } else {
        $query = "SELECT * FROM interests WHERE home_id = '$nickname'";
      }
      $interests_state = mysqli_query($conn, $query);

      $colors = ["default", "primary", "success", "info", "warning", "danger"];

      if(isset($home_owner)) {
      $query = "SELECT * FROM manage_profile WHERE home_id = '$home_owner'";
      } else {
      $query = "SELECT * FROM manage_profile WHERE home_id = '$nickname'";
      }
      $profile_state = mysqli_query($conn, $query);
      $profile_row = mysqli_fetch_assoc($profile_state);
       ?>
      <div class="well">
        <p><a href="#">관심있는 것들</a></p>
        <p>
          <?php while($interests_row = $interests_state->fetch_array()) : ?>
          <span class="label label-<?php echo $colors[mt_rand(0,5)];?>"><?php echo $interests_row["interest"]; ?></span>
          <!--<span class="label label-primary">W3Schools</span>
          <span class="label label-success">Labels</span>
          <span class="label label-info">Football</span>
          <span class="label label-warning">Gaming</span>
          <span class="label label-danger">Friends</span>-->
        <?php endwhile; ?>
        </p>
      </div>
      <div class="alert alert-success fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        <p><strong>안녕!</strong></p>
        사람들이 당신을 알아볼수 있게 만드세요!
      </div>
      <p><a href="<?php echo $profile_row["instagram"]; ?>">Instagram</a></p>
      <p><a href="<?php echo $profile_row["facebook"]; ?>">Facebook</a></p>
      <p><a href="<?php echo $profile_row["twitter"]; ?>">Twitter</a></p>
    </div>
    <div class="col-sm-7">

      <div class="row">
        <div class="col-sm-12">
          <div class="panel panel-default text-left">
            <div class="panel-body">
              <p>기분 상태 : <?php echo $profile_row["feeling"]; ?></p>
              <!--<p contenteditable="true"></p>-->
              <button type="button" class="btn btn-default btn-sm">
                <span class="glyphicon glyphicon-thumbs-up"></span> Like
                <p><?php echo $profile_row["howlike"]; ?></p>
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="well">
        <label for="comment" style="float:left;">댓글:</label>
        <textarea class="form-control" rows="3" id="comment"></textarea>
        &nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary navbar_btn" id="comment_sending" style="float:right;" data-user="<?php
        echo (isset($home_owner))?$home_owner:$nickname; ?>">달기</button>
        </div>
      </div>

      <?php while($comments_row = $comments_state->fetch_array()) : ?>
      <div class="row" id="comment_back">
        <div class="col-sm-3">
          <div class="well">
           <p><?php echo $comments_row["comment_id"]; ?></p>
           <img src="<?php echo $comments_row["friend_path"]; ?>" class="img-circle" height="55" width="55" alt="Avatar">
          </div>
        </div>
        <div class="col-sm-9">
          <div class="well">
            <p><?php echo $comments_row["comment"]; ?></p>
          </div>
        </div>
      </div>
    <?php endwhile; ?>

    </div>
    <div class="col-sm-2 well">
      <div class="thumbnail" id="user_details">

    </div>
  </div>
</div>

<!-- 모달 추가-->

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
        <img id="user_photo" class="img-thumbnail" src="<?php getImage($data['email']); ?>" width="65" height="65" alt="Generic placeholder image">
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
<!-- 답글 알림 모달 -->
<div class="modal fade" id="profile_modal" tabindex="-2" role="dialog" aria-labelledby="profile_modal_main" aria-hidden="true">
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="profile_modal_main" style="text-align:center;">프로필 관리 화면</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <table class="table table-hover">
        <tbody>
          <tr>
            <td><h5>프로필 사진 변경</h5></td>
            <form id="imageSendForm" action="change_profile.php" enctype="multipart/form-data" method="post">
              <td><input type="file" name="image_file" id="image_file"></td>
              <td><button class="btn btn-primary" id="change_image_btn">프로필 사진 변경</button></td>
            </form>
          </tr>
          <tr>
            <td><h5>SNS 연결</h5></td>
            <td><select id="sns_select"><option>Instagram</option><option>Facebook</option><option>Twitter</option></select><input type="text" placeholder="sns_url을 등록하세요." id="sns_write"></td>
            <td><button class="btn btn-primary" id="change_sns">SNS 위치 변경</button></td>
          </tr>
          <tr>
            <td>관심사 적기</td>
            <td><input type="text" id="interests_text"></td>
            <td><button class="btn btn-primary" id="change_interest">관심사 등록</button></td>
          </tr>
        </tbody>
      </table>
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

<script>

$(document).ready(function(){
 fetch_user();

 var checkOpen = 0;

 setInterval(function(){
   update_last_activity();
   fetch_user();
 }, 5000);


 function fetch_user() {
   $.ajax({
     url:"fetch_user.php",
     method:"POST",
     success:function(data){
       $('#user_details').html(data);
     }
   })
 }

 function update_last_activity() {
   $.ajax({
     url:"update_last_activity.php",
     success:function(){

     }
   })
 }

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

/*$("#change_image_btn").click(function() {
  var file = $("#image_file").prop('files')[0];
  var form_data = new FormData();
  form_data.append('file', file);
  $.ajax({
    url:"change_profile.php",
    dataType: 'text',
    cache: false,
    contentType: false,
    processData : false,
    method: "POST"
    data: form_data,
    success:function(data) {
      alert(data);
    }
  })
});*/
$("#change_image_btn").click(function() {
  $("#imageSendForm").ajaxForm({
    dataType: 'json',
    complete: function(data) {
      alert(data);
    }
  })
})

$("#comment_sending").click(function() {
  var text = $("#comment").val();
  var touserid = $(this).data('user');
  $.ajax({
    url:"change_profile.php",
    method:"POST",
    data:{text:text, userid:touserid},
    success:function(data) {
      $('#comment').val('');
      //$('#chat_monitor_'+toUserId).before(data);
      $(data).insertBefore("#comment_back");
    }
  })
})

$("#change_sns").click(function() {
  var sel = document.getElementById("sns_select");
 var sns_type = sel.options[sel.selectedIndex].text;
 var sns_url = $("#sns_write").val();
 $.ajax({
   url:"change_profile.php",
   method:"POST",
   data:{sns_type:sns_type, sns_url:sns_url},
   success:function(data) {
     alert(data);
   }
 })
});

$("#change_interest").click(function() {
  var interests = $("#interests_text").val();
  $.ajax({
    url:"change_profile.php",
    method:"POST",
    data:{interests:interests},
    success:function(data) {
      alert(data);
    }
  })
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

$(document).on('click', '#moving_friend_home', function() {
  var name = $(this).text();
  window.location.replace("mypage.php?nickname="+name);
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

/*
function make_chat_dialog_box(to_user_name) {
  var modal_content = '<div class="collapse multi-collapse" id="chat_monitor_'+to_user_name+'">';
  modal_content += '<div class="outgoing_msg">';
  modal_content +=  '  <div class="sent_msg">';
  modal_content +=      '  <p>Apollo University, Delhi, India Test</p>';
  modal_content +=       ' <span class="time_date"> 11:01 AM    |    Today</span>';
  modal_content +=   ' </div>';
  modal_content +='</div>';
  modal_content +='<div class="incoming_msg">';
  modal_content +=  '<div class="incoming_msg_img"> <img src="./img/list.jpg" alt="sunil"> </div>';
  modal_content += ' <div class="received_msg">';
  modal_content +=     ' <div class="received_withd_msg">';
  modal_content +=     '     <p>We work directly with our designers and suppliers,';
  modal_content +=     '           and sell direct to you, which means quality, exclusive';
  modal_content +=    '          products, at a price anyone can afford.</p>';
  modal_content +=    '      <span class="time_date"> 11:01 AM    |    Today</span>';
  modal_content +=  '    </div>';
  modal_content += ' </div>';
  modal_content += '</div>';
  modal_content += '<div class="type_msg"><div class="input_msg_write"><input type="text" class="write_msg" placeholder="Type a message" /><button class="msg_send_btn" type="button" data-touserid='+to_user_name+'><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button></div></div>';
  modal_content += '</div>';
  $('#send_modal').html(modal_content);
}
*/
});
</script>
</body>
