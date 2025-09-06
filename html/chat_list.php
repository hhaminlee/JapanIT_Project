<?php

include "db.php";

if (!$db) {
    die('DB 연결 망했네 ㅋㅋ ' . mysqli_connect_error());
}
session_start();
$user_id = $_SESSION["userid"];
if(!$user_id) {
    echo "잘못된 접근입니다.";
    exit;
}
$sql = "SELECT * FROM user WHERE user_id = '$user_id'";
$result = $db->query($sql);

if($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$nick = $row["user_nick"];
		
	}
}
//유저가 채팅방에서 채팅방 목록으로 나왔을때 변수들 0으로 초기화
$_SESSION['in'] = 0;
$_SESSION['tries'] = 0;
$_SESSION['ask_count'] = 0;

//24시간 지난 빈 방들 삭제 기능
$yesterday = date('Y-m-d', strtotime('-1 day'));

$query = "DELETE FROM chatrooms WHERE created_at < '$yesterday'";
$result = mysqli_query($db, $query);
//db 닫기
mysqli_close($db);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <title>챗리스트</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/abf15be44c.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&family=Space+Mono&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="icon" href="sm.png">
    <style>
        /* style before */
        .container1 {
            display: flex;
            justify-content: space-between;
            align-items: flex-start; /* or adjust alignment as needed */
            max-width: 100%; /* Adjust this as needed */
            margin: 0 auto; /* Center the container horizontally */
            padding: 0px;
        }
        .left-container {
            /* display: flex;
            justify-content: space-between;
            align-items: flex-start; or adjust alignment as needed */
            width: 100%; /* Adjust this as needed */
            margin-top: 0px;
            margin-bottom: 5px;
            margin-left: 0px;
            margin-right: 0px; /* Center the container horizontally */
            padding: 0px;
        }
        #chatroom_list_title {
            width: 95%;
            text-align: center;
            background-color: rgb(200, 150, 200);
            color: white;
            padding: 15px;
            border-radius: 7px;
            margin-left:0;
            margin-top: 10px;
            margin-bottom: 0px;
            font-size: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }        
        #main-chat-title {
            text-align: center;
            justify-content:center;
            background-color: rgb(200, 150, 200);
            color: white;
            padding: 15px;
            border-radius: 7px;
            /* margin: 10px 0; */
            margin-bottom: 0px;
            font-size: 20px;
            text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.5);
        }
        #main-start-title {
            text-align: center;
            justify-content:center;
            background-color: rgb(200, 150, 200);
            color: white;
            padding: 5px;
            border-radius: 7px;
            font-size: 25px;
            text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.5);
            margin-bottom: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        #main-chat {
            float: right;
            margin-top: 10px;
            margin-left:0;
            background-color:transparent;
            width: 45%;
        }
        body{
            font-family: 'Gowun Batang', sans-serif;
            background-color: #f2f2f2;
            margin:0;
        }
        #chat-box {
            height: 439px;
            overflow-y: auto;
            margin-top: 5px;
            padding-top: 0px;
            padding-bottom: 0px;
            padding-left: 2px;
            padding-right: 2px;
            margin-bottom: 0px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        #chat-box p {
            background-color: #007BFF; /* Background color for chat messages */
            color: white;
            padding: 5px 10px;
            border-radius: 10px;
            margin: 5px 0;
            max-width: 70%;
        }

        #wait-chat-form {
            margin-top: 2px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        #wait-chat-form input[type="submit"] {
            padding: 5px 15px;
            background-color: rgb(200, 150, 200);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #message {
            width: 80%;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        #chatroom_list {
            width: 95%;
            height: 550px;
            background-color: #f9f9f9; /* Change background color */
            padding: 10px;
            border-radius: 7px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            margin-top: 5px;
            overflow-x: hidden;
            overflow-y: scroll;
            position: relative;
            margin-right:0px;
            margin-left:0px;
        }

        #chatroom_list::before {
            left: 0;
        }

        #chatroom_list::after {
            right: 0;
        }

        #chatroom_list ul {
            list-style: none;
            padding: 0;
        }

        #chatroom_list li {
            margin: 10px 0;
            padding: 10px;
            background-color: #ffffff; /* Change the background color of each chatroom */
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Add box shadow */
            position: relative;
        }
        #chatroom_list li::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            background-color: #6a82fb;
        }
        #myWelcome{
            padding: 0px;
            display: flex;
            justify-content: center;
            flex-direction: row;
        }
        h1 {
            text-align: center;
            padding: 20px;
            width: 100%;
            margin: 0;
            /* background: linear-gradient(135deg, #8E24AA, #311B92); */
            background-color: rgb(200, 150, 200);
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            animation: moveBackground 5s linear infinite; /* Add the animation */
            border-radius: 5px;
        }

        @keyframes moveBackground {
            0% {
                background-position: 0% 33%;
                text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            }
            50% {
                background-position: 33% 66%;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            }
            100% {
                background-position: 66% 100%;
                text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            }
        }
        .button {
            background-color: transparent;
            color: #fff;
            text-decoration: none;
            border: 1px solid transparent;
            border-radius: 5px;
            margin-bottom: 10px;
            text-align: center;
        }


        a.button {
            display: block;
            background-color: transparent;
            padding: 5px;
            color: #fff;
            text-decoration: none;
            border: 1px solid transparent;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
            margin-left: 0px;
            margin-right: 3px;
        }
        a.button2 {
            display: block;
            background-color: rgb(200, 150, 200);
            padding: 5px;
            color: #fff;
            text-decoration: none;
            border: 1px solid rgb(160, 110, 160);
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
            margin-left: 0px;
            margin-right: 3px;
        }

        a.button2:hover {
            background-color : rgb(160, 110, 160);
        }


        .list{
            display: inline-block;
            text-decoration: none;
            margin: 3px;
            width: 100%;
        }
        
        h2 {
            color: rgb(200, 150, 200);
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.7);
            display:flex;
            justify-content:center;
        }
        .right{
            float:right;
        }
        .purple-theme {
            background-color: rgb(200, 150, 200);
        }

        .purple-theme-shadow{
            background-color: rgb(160, 110, 160);
        }
        .radio{
            margin-top:60px;
            float:right;
        }

        .trans_nation {
            position: fixed;
            bottom: 0;
            left: 0;
        }

        .trans_nation img {
            width : 150px;
            transition: 0.3s;
        }

        .trans_nation img:hover {
            content: url('nihongode2.png');
            width : 280px;
        }

        @media only screen and (max-width: 600px) {
            .trans_nation img {
                width : 50px;
                transition: 0.3s;
            }

            .trans_nation img:hover {
            content: url('nihongode2.png');
            width : 120px;
            }
            }

        #main-start-title:hover {
            background-color: rgb(160, 110, 160);
        }

        #name_span {
            color : white;
            margin-right : 20px;
        }
    </style>
</head>
<!-- before the fix -->
<body>

    <header class="purple-theme-shadow">
        <div class="collapse" id="navbarHeader">
          <div class="container">
            <div class="row">
              <div class="col-sm-8 col-md-7 py-4">
                <h4 class="text-white">일본IT학회</h4>
              </div>
              <div class="col-sm-4 offset-md-1 py-4">
                <h4 class="text-white">Contact</h4>
                <ul class="list-unstyled">
                  <li><a href="#" class="text-white">22000104@handong.ac.kr</a></li>
                  <li><a href="#" class="text-white">22000576@handong.ac.kr</a></li>
                  <li><a href="#" class="text-white">22000594@handong.ac.kr</a></li>
                  <li><a href="#" class="text-white">22100275@handong.ac.kr</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="navbar navbar-dark purple-theme shadow-sm">
            <div class="container">
              <a href="chat_list.php" class="navbar-brand" style="display:justify-content:center; align-items: center;">
                ShitsuMun<i class="fa-regular fa-comments"></i>
              </a>
              <div class="button-container">                
              </div>
            </div>
            <span id="name_span"><strong><?php echo $nick; ?></strong>님</span>
            <a class="button2" style="margin-right:10px" href="rule.html">룰 보기</a>
            <a class="button2" href="logout.php">로그아웃</a>
            

            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
            </button>

            </div>
        </header>
   

    <div class = "container1">
        <div class = "left-container">
            <div id= "chatroom_list_title">
                    <h4>개설된 방 목록<h4>
            </div>
            <div id="chatroom_list">
            </div>
        </div>

        <!-- <a href="create_chatroom.php">챗룸만들기</a> -->
        <!-- <a class="button" href="create_chatroom.php">챗룸만들기</a> -->

        <div id="main-chat">
            <div id="main-start-title">
                <a class="button" href="create_chatroom.php">챗룸만들기</a>
            </div>            
            <div id ="main-chat-title">                
                <h4>대기실 채팅</h4>
            </div>
            <div id="chat-box"></div>
                <!-- 채팅 메시지 보내기 폼 -->
                <form id="wait-chat-form">
                    <input type="text" id="message" placeholder="Message">
                    <input type="submit" value="Send">
                </form>
                <!-- <div class ="radio">
                    <input type="radio" class="btn-check" name="options-base" id="option1" autocomplete="off" checked>
                    <label class="btn" for="option1">한국어</label>

                    <input type="radio" class="btn-check" name="options-base" id="option2" autocomplete="off">
                    <label class="btn" for="option2">日本語</label>
                </div> -->
            
        </div>
        
    <div class="trans_nation">
        <a href="chat_list_jp.php"><img src="shitsu-chan.png"></img></a>
    </div>
    <script>
        


        function loadChatrooms(){
            $.ajax({
                url: "load_chatrooms.php",  // 이 PHP 파일은 아래에 작성하겠습니다.
                method: "GET",
                success: function(data){
                    $("#chatroom_list").html(data);
                }
            });
        }

        loadChatrooms();
        setInterval(loadChatrooms, 1000);  // 1초마다 채팅방 목록과 인원을 새로 불러옵니다.

        $("#wait-chat-form").submit(function(e){
            let username = "<?php echo $nick; ?>";
            e.preventDefault();

            let message = $("#message").val();

            $.ajax({
                url: "w_send_message.php",
                method: "POST",
                data: { username: username, message: message},
                success: function(data){
                    $("#message").val("");
                }
            });
        });

        function loadMessages(){

            $.ajax({
                url: "w_load_messages.php",
                method: "GET",
                success: function(data){
                    $("#chat-box").html(data);
                }
            });
        }

        setInterval(loadMessages, 1000);

        $(document).ready(function() {
            $('input[id="message"]').on('input', function() {
                var input = $(this).val();
                if (input.length > 30) {
                    $(this).val(input.substring(0, 30));
                }
            });

        });
    </script>
</body>
</html>
