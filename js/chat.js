<script>
function make_chat_dialog_box(to_user_id, to_user_name, img_path) {
/*
  <div class="media">
    <img id="user_photo" class="img-thumbnail" src="./img/list.jpg" alt="Generic placeholder image">
    <div class="media-body">
      <h5 class="mt-0" id="user_name">Olivia Hassen</h5>
      <span class="badge badge-danger" id="info_span">1</span><p id="chat_content">Hi, baby It's me</p>
      <span class="user_date">8월 15일</span>
    </div>
  </div>
  */
   
}
alert("fuck you");
$(document).ready(function(){
 fetch_user();

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
});

</script>
