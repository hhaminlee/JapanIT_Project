<?php
include "db.php";
if (!isset($_GET['id'])) {
    die('No chatroom ID provided');
}

$chatroom_id = $_GET['id'];
$query_check = "SELECT current_users FROM chatrooms WHERE id = $chatroom_id";
$result_check = mysqli_query($db, $query_check);
if (!$result_check) {
    die("Query failed: " . mysqli_error($db));
}
$row_check = mysqli_fetch_assoc($result_check);
$creator_id = $row_check['creator'];

session_start();


if(!isset($_SESSION['tries'])) {
    $_SESSION['tries'] = 0;
}
$answer = $row_check['keyword'];
$mess = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_guess = $_POST["user_guess"];
    $_SESSION['tries']++;

    if ($user_guess == $answer) {
        $mess = "정답! {$_SESSION['tries']}번 만에 맞췄습니다!";
        session_destroy();
    } elseif ($_SESSION['tries'] >= 20) {
        $mess = "20번의 기회를 다 소진했습니다.";
        session_destroy();
    } else {
        $mess = "{$_SESSION['tries']}번 시도했습니다. 다시 시도해 보세요.";
    }
}


?>