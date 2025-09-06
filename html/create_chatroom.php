<?php
include "db.php";
session_start();
$user_id = $_SESSION["userid"];
$sql = "SELECT * FROM user WHERE user_id = '$user_id'";
$result = $db->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $nick = $row["user_nick"];
        $nation = $row["nation"];
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $chatroom_name = $db->real_escape_string($_POST['chatroom_name']);
    $keyword = $_POST['keyword'];
    $query = "INSERT INTO chatrooms (name, creator, keyword, nation, user_name) VALUES ('$chatroom_name', '$user_id', '$keyword', '$nation', '')";
    $result = mysqli_query($db, $query);
    if ($result) {
        $chatroom_id = mysqli_insert_id($db);
        var_dump($chatroom_id);
        header("Location: chatroom.php?id=$chatroom_id");
    } else {
        die('Chatroom creation failed');
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&family=Space+Mono&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/abf15be44c.js" crossorigin="anonymous"></script>
    <link rel="icon" href="sm.png">

    <title>Create Chatroom</title>
    <style>
        .myContents{
            padding-bottom: 15%;
        }
        h2 {
            font-size: 48px; /* Increase font size */
            color: rgb(200, 150, 200);
            font-weight: bold;
            display: flex;
            align-items: center; /* Center vertically */
            justify-content: center; /* Center horizontally */
            margin: auto;
        }
        body {
            font-family: 'Gowun Batang', sans-serif;
            background-color: #f2f2f2;
            display:flex;
            text-align: center;
            justify-content:center;
            flex-direction: column;
            height: 100vh;
        }

        form {
            margin: auto;
            max-width: 480px; /* Increase the maximum width */
            padding: 30px; /* Increase padding */
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px; /* Increase border radius */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); /* Increase box shadow */
        }

        input {
            width: 90%;
            padding: 15px; /* Increase padding */
            margin-bottom:20px;
            border: 2px solid #ddd; /* Increase border width */
            border-radius: 10px; /* Increase border radius */
            font-size: 18px; /* Increase font size */
        }
        input[type="text"]:focus{
            border-color: rgb(200, 150, 200);
            box-shadow: 0 0 5px rgb(200, 150, 200);
        }

        input[type="submit"] {
            background-color: rgb(200, 150, 200);
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 20px; /* Increase font size */
            padding: 15px 20px; /* Increase padding */
        }

        input[type="submit"]:hover {
            background-color: rgba(200, 150, 200, 0.7);
        }

        .myTitle {
            margin-bottom:20px;
            padding: 10px;
        }

        .icon {
            font-size: 36px; /* Increase the icon size */
            margin-right: 10px; /* Add some spacing */
        }
        a:hover {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class = "myContents">
        <div class="myTitle">
            <a href="chat_list.php"><h2>ShitsuMun <i class="fa-regular fa-comments"></i></h2></a>
        </div>
        <form action="create_chatroom.php" method="post">
            <input type="text" name="chatroom_name" placeholder="Chatroom Name" required>
            <input type="text" name="keyword" placeholder="스무고개로 사용할 단어를 입력해주세요" required>
            <br>
            <br>
            <input type="submit" value="Create Chatroom">
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('input[name="chatroom_name"]').on('input', function() {
                var input = $(this).val();
                if (input.length > 20) {
                    $(this).val(input.substring(0, 20));
                }
            });

            $('input[name="keyword"]').on('input', function() {
                var input = $(this).val();
                input = input.replace(/\s+/g, ''); // 띄어쓰기 제거
                if (input.length > 5) {
                    input = input.substring(0, 5);
                }
                $(this).val(input);
            });

            $('form').submit(function(e) {
                var chatroom_name = $('input[name="chatroom_name"]').val();
                var keyword = $('input[name="keyword"]').val();

                if (chatroom_name.length > 20) {
                    alert('Chatroom name must be less than or equal to 20 characters');
                    e.preventDefault();
                }

                if (keyword.length > 5) {
                    alert('Keyword must be less than or equal to 5 characters');
                    e.preventDefault();
                }

                if (/\s/.test(keyword)) {
                    alert('Keyword cannot contain spaces');
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
