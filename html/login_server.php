<?php
include 'db.php';

if($_SERVER["REQUEST_METHOD"]== "POST") {
    $username = $db->real_escape_string($_POST["username"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM user WHERE user_id = '$username'";

    $result = $db->query($sql);


    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if(password_verify($password, $row['user_pw'])) {
            session_start();
            $_SESSION["userid"] = $username;
            $nation = $row['nation'];
            if($nation == "korea") {
                header("Location: chat_list.php");
                exit;
            } else {
                header("Location: chat_list_jp.php");
                exit;
            }
        } else {
            echo "로그인 실패: 비밀번호가 일치하지 않습니다.";
        }
    } else {
        echo "로그인 실패: 사용자를 찾을 수 없습니다.";
    }
} else {
    echo "올바른 요청이 아닙니다";
}
?>





