<?php
include 'db.php';

if($_SERVER["REQUEST_METHOD"]== "POST") {
    $user_name = $db->real_escape_string($_POST['username']);
    $password = $db->real_escape_string($_POST["password"]);
    $password2 = $db->real_escape_string($_POST["password2"]);
    $nickname = $db->real_escape_string($_POST["nickname"]);
    $nation = $db->real_escape_string($_POST["nationality"]);

    if($password != $password2) {
        echo "<script>alert('비밀번호가 같지 않습니다.');
            window.location.href = 'register.html';</script>";
            exit;
    }
        else {
        // 아이디 중복 검사
        $sql = "SELECT * FROM user WHERE user_id = '$user_name'";
        $result = $db->query($sql);
        if ($result->num_rows > 0) {
            echo "<script>alert('해당 아이디가 이미 사용 중입니다.');
            window.location.href = 'register.html';</script>";
            exit;
        }

        // 닉네임 중복 검사
        $sql = "SELECT * FROM user WHERE user_nick = '$nickname'";
        $result = $db->query($sql);
        if ($result->num_rows > 0) {
            echo "<script>alert('해당 닉네임이 이미 사용 중입니다.');
            window.location.href = 'register.html';</script>";
            exit;
        }

        // 회원가입 처리
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (user_id, user_pw, user_nick, nation) VALUES ('$user_name', '$password_hash', '$nickname', '$nation')";
        if ($db->query($sql) == TRUE) {
            header("Location: index.html");
            exit;
        } else {
            echo "오류 발생" . $db->error;
        }
    }
} else {
    echo "올바른 요청이 아닙니다";
}

$db->close();

?>