<?php
require('./database/call_database.php');
date_default_timezone_set("Asia/Seoul");
session_start();
$id = $_SESSION['id'];


/*
CREATE TABLE list (id INT PRIMARY KEY AUTO_INCREMENT, title VARCHAR(40) NOT NULL, nickname VARCHAR(20),
content text, wdate datetime, ip VARCHAR(15), view int(11) unsigned, recommend int(11) unsigned);
*/
if(!isset($id)) {
    header("Location:/03project/site/login.php");
}

$query = "SELECT * FROM member WHERE email != '$id'";

$statement = mysqli_query($conn, $query);


 //수정해야할 부분 데이터베이스 설
$sql = "SELECT * FROM list order by id desc";
$result = mysqli_query($conn, $sql);

$exist = mysqli_num_rows($result);
$total_record = $exist;

if(isset($_GET['no'])) {
  $no = $_GET['no'];
}

$page_size = 5;
$page_list_size = 5;

if(!isset($no) || $no < 0) {
    $no = 0;
}
$total_row = $total_record;

$total_page = floor(($total_row - 1) / $page_size);
$current_page = floor($no / $page_size);


$sql = "SELECT * FROM list ORDER BY id DESC LIMIT $no, $page_size";
$result = mysqli_query($conn, $sql);

$start_page = (ceil($current_page / $page_list_size) * $page_list_size);
$end_page = $start_page + $page_list_size - 1;
if($total_page < $end_page) {
    $end_page = $total_page;
}

function getImage($email) {
  global $conn;
  $query = "SELECT thumb_path FROM member where email = '$email'";
  $statement = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($statement);
  echo $row['thumb_path'];
  return $row['thumb_path'];
}
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
            <input type="text" id="myInput" onKeyup="finding()" placeholder="제목을 입력하세요...">

            <!-- 추가 하는 버튼 -->
            <button  class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#article_modal">글 작성하기</button>

<table class="table table-hover" id="myTable">
    <thead class="thead-dark" id="thead_list">
    <tr>
      <th scope="col">썸네일</th>
      <th scope="col">제목</th>
      <th scope="col">작성자</th>
      <th scope="col">등록일</th>
      <th scope="col">추천/반대</th>
      <th scope="col">조회수</th>
    </tr>
  </thead>
  <tbody>
  <?php while($data = $result->fetch_array()) : //여기서 시작?>
    <tr id="article_click" data-tonum="<?php echo $data['id']; ?>">
      <th scope="row"><img src='<?php echo $data["thumb_path"]; ?>' height="55" width="55"></th>
      <td><?php echo $data['title']; ?></td>
      <td><?php echo $data['nickname']; ?></td>
      <td><?php echo $data['wdate']; ?></td>
      <td><?php echo "{$data['recommend']} / {$data['against']}";?></td>
      <td><?php echo $data['view']; ?></td>
    </tr>
  <?php endwhile; $conn->close(); ?>
  </tbody>
</table>
            <div class="box_btn">
                <table class="table">
                    <?php
                    if($start_page >= $page_list_size) :
                        $prev_list = ($start_page - 1) * page_size; ?>
                    <th><button class="btn"><a href="list.php?no=<?php echo $prev_list; ?>">&#8592;</a></button></th>
                    <?php endif?>
                    <?php for($i=$start_page; $i <= $end_page; $i++) :
                        $page = $page_size*$i;
                        $page_num = $i+1; ?>
                    <th><button class="btn"><a href="list.php?no=<?php echo $page ?>">
                        <?php echo $page_num;?>
                        </a></button></th>
                    <?php endfor?>
                    <?php if($total_page > $end_page) :
                    $next_list = ($end_page + 1) * $page_size;?>
                    <th><button class="btn">
                        <a href="list?no=<?php echo $next_list; ?>">&#8594;</a></button></th>
                    <?php endif ?>
                </table>
            </div>

            <!-- 글 작성 모달 -->
            <div class="modal fade" id="article_modal" tabindex="-2" role="dialog" aria-labelledby="modal_article" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 style="text-align:center;">글 작성하기</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form>
                      <table class="table table-hover">
                        <tr>
                          <td><span class="input-group-addon">글 제목 : </span></td>
                          <td><input type="text" class="form-control" placeholder="제목을 입력하세요." id="title"></td>
                        </tr>
                        <tr>
                          <td><span class="input-group-addon">본문 : </span></td>
                          <td><textarea class="form-control" id="text_main" rows="5" placeholder="내용을 입력하세요."></textarea></td>
                        </tr>
                        <tr>
                          <td></td>
                          <td><button class="btn btn-primary" id="send_article">작성하기</button></td>
                        </tr>
                      </table>
                    </form>
                  </div>
                </div>
              </div>
            </div>
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

      $("#send_article").click(function() {
        var title = $("#title").val();
        var text = $("#text_main").val();
        $.ajax({
          url:"upload_article.php",
          method:"POST",
          data:{title:title, text:text},
          success:function(data) {
            if(data) {
              $("#thead_list").html(data);
            }
          }
        })
      })

      $(document).on("click", "#article_click", function() {
        var num = $(this).data('tonum');
        window.location.replace("show_list.php?article_num="+num);
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


    });
    function finding() {
        var input, filter, table, tr, td, i, tbody;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        //tbody = table.getElementsByTagName("tbody");
        tr = table.getElementsByTagName("tr");

        for(i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                if(td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
  </script>
  </html>
