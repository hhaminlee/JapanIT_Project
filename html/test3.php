<?php
session_start();
if(!isset($_SESSION['tries'])) {
    $_SESSION['tries'] = 0;
}
$answer = "개";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_guess = $_POST["user_guess"];
    $_SESSION['tries']++;

    if ($user_guess == $answer) {
        $message = "정답! {$_SESSION['tries']}번 만에 맞췄습니다!";
        session_destroy();
    } elseif ($_SESSION['tries'] >= 20) {
        $message = "20번의 기회를 다 소진했습니다.";
        session_destroy();
    } else {
        $message = "{$_SESSION['tries']}번 시도했습니다. 다시 시도해 보세요.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>스무고개 게임</title>
</head>
<body>
    <form action="test3.php" method="post">
        <input type="text" name="user_guess" placeholder="답을 입력하세요">
        <input type="submit" value="제출">
    </form>
    <p><?php echo $message; ?></p>
</body>
</html>
