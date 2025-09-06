<?php
include "db.php";
session_start();

if (!isset($_GET['id'])) {
    die('No chatroom ID provided');
}
if (!isset($_SESSION['ask_count'])) {
    $_SESSION['ask_count'] = 0;
}
$chatroom_id = $_GET['id'];

// First check the current number of users
$query_check = "SELECT current_users, creator, keyword, name, ask_count FROM chatrooms WHERE id = $chatroom_id";
$result_check = mysqli_query($db, $query_check);
if (!$result_check) {
    die("Query failed: " . mysqli_error($db));
}
$row_check = mysqli_fetch_assoc($result_check);
if ($row_check['current_users'] >= 2 && $_SESSION['in']!=1) {
    echo "<script>alert('이미 최대 인원이 참여하고 있습니다.');
    window.location.href = 'chat_list.php';
    </script>";
}
$creator_id = $row_check['creator'];
$room_name = $row_check['name'];
$ask_count = $row_check['ask_count'];

// Then update the current_users
if($_SESSION['in']==0) {
    $_SESSION['in'] = 1;
    $query = "UPDATE chatrooms SET current_users = current_users + 1 WHERE id = $chatroom_id";
    $result = mysqli_query($db, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($db));
    }
}

$user_id = $_SESSION["userid"];
$sql = "SELECT * FROM user WHERE user_id = '$user_id'";
$result = $db->query($sql);

if($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$nick = $row["user_nick"];
		
	}
}
if (!$db) {
    die('DB connection failed');
}

//아래는 답 비교
if(!isset($_SESSION['tries'])) {
    $_SESSION['tries'] = 0;
}
$answer = $row_check['keyword'];
$mess = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_guess = $_POST["keyword"];
    $_SESSION['tries']++;
    $query = "UPDATE chatrooms SET current_users = current_users + 1 WHERE id = $chatroom_id";
    $result = mysqli_query($db, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($db));
    }
    if ($user_guess == $answer) {
        $_SESSION['tries'] = 0;
        $query = "UPDATE chatrooms SET answer_correct = 1 WHERE id = $chatroom_id";
        $result = mysqli_query($db, $query);
        
        if (!$result) {
            die("Query failed: " . mysqli_error($db));
        }

        echo "<script>alert('유저가 정답을 맞혔습니다!');
        window.location.href = 'chat_list.php';
        exit;</script>";

    } else if ($_SESSION['tries'] >= 3) {
        $_SESSION['tries'] = 0;
        $query = "UPDATE chatrooms SET answer_correct = 2 WHERE id = $chatroom_id";
        $result = mysqli_query($db, $query);
        echo "<script>alert('3번의 기회를 다 소진했습니다.');
        window.location.href = 'chat_list.php';
        </script>";
        exit;

    } else {
        $mess = "{$_SESSION['tries']}번 시도했습니다. 다시 시도해 보세요.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chatroom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&family=Space+Mono&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/abf15be44c.js" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="icon" href="sm.png">
    <!-- previous version -->
    <style>
        body {
            background-color: #f8f5f1;
        }
        #main-chat {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1);
            padding: 10px;
            width: 80%;
            margin: 0 auto;
        }

        #chat-box {
            height: 500px;
            overflow-y: scroll;
            border: 1px solid #ccc;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1);
            padding: 10px;
            width: 100%;
            max-width: 700px;
            margin: 10px auto;
        }
        .message {
            padding: 10px;
            border-radius: 10px;
            margin: 10px;
            max-width: 80%;
        }

        .message.user {
            background-color: #007BFF;
            color: white;
            align-self: flex-end;
        }

        /* Styling for other users' messages */
        .message.other {
            background-color: #ddd;
            color: #333;
            align-self: flex-start;
        }

        #chat-form {
            background-color: #f8f5f1;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0px -3px 6px rgba(0, 0, 0, 0.1);
        }

        .chat-input {
            width: 80%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }

        .chat-submit {
            padding: 10px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
        }

        .keyword-input {
            width: 80%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }

        .keyword-submit {
            padding: 10px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
        }

        #message {
            width: 75%;
            border: none;
            border-radius: 15px;
            padding: 10px;
            background-color: #f5f5dc;
            outline: none;
            resize: none;

        }

        .delete-chatroom {
            margin-top: 20px;
        }

        body {
            background-color: #f8f5f1;
        }

        #chat-form {
            background-color: #f8f5f1;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        #message {
            width: 75%;
            border: none;
            border-radius: 15px;
            padding: 10px;
            background-color: #f5f5dc;
        }

        .send-btn {
            color: rgb(200, 150, 200);
            background-color: #f8f5f1;
            border: none;

        }

        /* .button-container > .navbar-toggler {
        margin-left: 20px;
        } */

        .purple-theme {
            background-color: rgb(200, 150, 200);

        }

        .purple-theme-shadow{
            background-color: rgb(160, 110, 160);
        }

        .delete-btn {
            background-color: rgb(160, 110, 160);
            color: white;
            border: none;
        }

        #now_join {
        font-weight: bold;
        font-size: 16px;
        color: rgb(160, 110, 160);
        }   


    </style>
</head>
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
  <div class="collapse purple-theme-shadow" id="navbarHeader_exit">
    <div class="container">
      <div class="row">
        <div class="col-sm-8 col-md-7 py-4">
        </div>
        <div class="col-sm-4 offset-md-1 py-4" style="display: flex; justify-content: flex-end;">
            <!-- 채팅방 삭제 -->
            <div style="display: block; position: relative;">
                <?php if ($creator_id == $user_id) : ?>
                    <div class="chatroom-action">
                        <form action="delete_chatroom.php" method="post">
                        <input type="hidden" name="chatroom_id" value="<?php echo $chatroom_id; ?>">
                        <button type="submit" class="delete-btn">채팅방 삭제</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- 채팅방 나가기 -->
            <div style="display: block; position: absolute; bottom: 6px;">
                <a href="chat_list.php" class="text-white">채팅방 나가기</a>
            </div>
        </div>
      </div>
    </div>
  </div>
  <div class="navbar navbar-dark purple-theme shadow-sm">
    <div class="container">
      <a href="chat_list.php" class="navbar-brand align-items-center" >
        <i class="fa-regular fa-comments"></i>
        <strong style="margin-left: 5px;"><?php echo "$room_name / 방장 : $creator_id"; ?></strong>
      </a>
      <div class="button-container" style="float:right;">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader_exit" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
          <span class="fas fa-door-open"></span>
        </button>
      </div>
    </div>
</div>
</header>

<?php
        if($creator_id == $user_id) {
            echo 
                "<div style='text-align:center; margin-top: 20px;'>
                    <p><i class='fas fa-users'></i> 현재 참가자 : <span id='now_join'></span></p>
                </div>";
        }
    ?>


    <div id="chat-box"></div>
    <!-- 채팅 메시지 보내기 폼 -->
    <form id="chat-form">
    <div class="input-group">
        <input type="text" id="message" placeholder="message" required class="form-control">
        <button type="submit" class="send-btn"><i class="fas fa-paper-plane"></i>
    </div>
    <input type="hidden" id="chatroom_id" value="<?php echo $chatroom_id; ?>">
</form>



    <!-- 아래는 스무고개 키워드를 맞추는 생성자는 못봄 -->
    <?php
        if($creator_id != $user_id) {
            echo "<div class='text-center'>
            <form action='chatroom.php?id={$chatroom_id}' method='post' id='keyword-form'>
            <input type='text' name='keyword' placeholder='키워드를 입력' required>
            <input type='hidden' name='chatroom_id' value='{$chatroom_id}'>
            <button type='submit' class='send-btn'><i class='fas fa-keyboard'></i></button>
            </form>
            <p>{$mess}</p>
            </div>";
            
        }
        //채팅방 삭제 폼 :: creator 외에는 보이지 않음
    //     else {
    //         echo "<form action='delete_chatroom.php' method='post'>
    //         <input type='hidden' name='chatroom_id' value='{$chatroom_id}'>
    //         <input type='submit' value='채팅방 삭제'>
    //         </form>";
    //     }
    // 
    ?>

    <!-- <a href="chat_list.php" class="btn btn-secondary">채팅방 나가기</a> -->
    <?php
        if($creator_id != $user_id) {
            if($ask_count >= 20) {
                echo "<div style='text-align: center'>
                <p id='ask_count_p'>기회를 모두 소진했습니다.</p>
                </div>";
            }
                
            else {
                echo "<div style='text-align: center'>
                <p id='ask_count_p'>지금까지의 질문 횟수: <span id='ask_count'>{$ask_count}</span></p>
                </div>";

            }
        }
    ?>



    </div>

<script>
    $(document).ready(function(){
        window.onbeforeunload = function() {
        let chatroom_id = $("#chatroom_id").val();

        $.ajax({
            url: "decrease_users.php",
            method: "POST",
            data: { id: chatroom_id },
            });
        };
        
        function checkCreatorInRoom(){
            let chatroom_id = $("#chatroom_id").val();
            let creator_nick = "<?php echo $creator_id; ?>"; // PHP 변수에서 creator ID 가져오기

            $.ajax({
                url: "check_creator_in_room.php", 
                method: "POST",
                data: { id: chatroom_id, creator_nick: creator_nick },
                success: function(data){
                    if (data === "0") {
                        // creator가 현재 채팅방에 없음
                        alert("현재 방장이 없습니다.");
                        $.ajax({
                            url: "decrease_users.php",
                            method: "POST",
                            data: { id: chatroom_id },
                            success: function() {
                                // 감소 성공 후 리디렉션
                                window.location.href = "chat_list.php";
                            },
                            error: function() {
                                // 오류 처리 (옵션)
                                alert("오류가 발생했습니다. 다시 시도해주세요.");
                            }
                        });
                    }
                }
            });
        }

        // 페이지 로딩시에 한 번, 그리고 일정 주기로 user가 있는지 체크
        checkCreatorInRoom();
        setInterval(checkCreatorInRoom, 5000);

        function checkUserInRoom(){
            let chatroom_id = $("#chatroom_id").val();
            let creator_nick = "<?php echo $creator_id; ?>"; // PHP 변수에서 creator ID 가져오기
            $.ajax({
                url: "check_user_in_room.php", 
                method: "POST",
                data: { id: chatroom_id, creator_nick: creator_nick },
                success: function(data){
                    $("#now_join").html(data);
                }
            });
        }

        // 페이지 로딩시에 한 번, 그리고 일정 주기로 creator가 있는지 체크
        checkUserInRoom();
        setInterval(checkUserInRoom, 1000);


        let canAdd = 0;
        $("#chat-form").submit(function(e){
            e.preventDefault();
            let user_id = "<?php echo $user_id; ?>";
            let creator_id = "<?php echo $creator_id; ?>"; // PHP 변수에서 creator ID 가져오기
            let chatroom_id = $("#chatroom_id").val();
            
            $.ajax({
                url: "check_ask_count.php",
                method: "POST",
                data: { id: chatroom_id },
                success: function(data){
                    var submitCount = data;
                    $("#ask_count").html(submitCount);
                    if (submitCount >= 20 && user_id != creator_id) {
                        $("#ask_count_p").html("기회를 모두 소진했습니다.");
                        canAdd = 1;
                        e.preventDefault();
                        return;
    
                    } else {
                        canAdd = 0;
                    }
                    }
                });
        });
        
        $("#chat-form").submit(function(e){
            let username = "<?php echo $nick; ?>";
            e.preventDefault();
            let message = $("#message").val();
            let chatroom_id = $("#chatroom_id").val();
            let chatBox = $("#chat-box");
            if(canAdd != 1) {
            $.ajax({
                url: "send_message.php",
                method: "POST",
                data: { username: username, message: message, chatroom_id: chatroom_id },
                success: function(data){
                    $("#message").val("");
                    chatBox.scrollTop(chatBox.prop("scrollHeight"));
                    
                }
            });
            }
            
        });

        let lastMessageId = 0;

        function loadMessages(){
            let chatroom_id = $("#chatroom_id").val();

            $.ajax({
                url: "load_messages.php",
                method: "GET",
                data: { id: chatroom_id },
                success: function(data){
                    let messages = JSON.parse(data);
                    let chatBox = $("#chat-box");
                    let isScrolledToBottom = chatBox.scrollTop() + chatBox.innerHeight() >= chatBox[0].scrollHeight;

                    let newContent = "";
                    messages.forEach(message => {
                        if(message.id > lastMessageId) {
                            lastMessageId = message.id;
                            newContent += "<div>" + message.username + ": " + message.message + "</div>";
                        }
                    });

                    if(newContent != "") {
                        chatBox.append(newContent);


                        chatBox.scrollTop(chatBox.prop("scrollHeight"));

                    }
                    
                }
            });
        }

        setInterval(loadMessages, 1000);

        

        function checkAnswerCorrect() {
        let chatroom_id = $("#chatroom_id").val();

        $.ajax({
            url: "check_answer_correct.php",
            method: "POST",
            data: { id: chatroom_id },
            success: function(data){
                if (data === "1") {
                    alert("유저가 답을 맞혔습니다!");
                    let chatroom_id = $("#chatroom_id").val();
                    $.ajax({
                        url: "delete_chatroom.php",
                        method: "POST",
                        data: { chatroom_id: chatroom_id },
                        success: function(response) {
                            window.location.href = "chat_list.php";
                        }
                    });
                    return;
                }
                if (data === "2") {
                    alert("유저가 답을 못맞혔습니다ㅠㅠ");
                    let chatroom_id = $("#chatroom_id").val();
                    $.ajax({
                        url: "delete_chatroom.php",
                        method: "POST",
                        data: { chatroom_id: chatroom_id },
                        success: function(response) {
                            window.location.href = "chat_list.php";
                        }
                    });
                    return;
                }
            }
        });
        }

        if ("<?php echo $creator_id; ?>" === "<?php echo $user_id; ?>") {
            setInterval(checkAnswerCorrect, 1000);
        }

    });
</script>

</body>
</html>


<!-- 1. 중앙정렬
     2. 나가기 버튼 : 채팅방 나가기, 채팅방 삭제 -->