<?php
include "db.php";

if (!isset($_POST['chatroom_id'])) {
    die('No chatroom ID provided');
}

$chatroom_id = $_POST['chatroom_id'];

$query = "DELETE FROM chatrooms WHERE id = $chatroom_id";
$result = mysqli_query($db, $query);

if (!$result) {
    die('Failed to delete chatroom: ' . mysqli_error($db));
} else {
    header('Location: chat_list.php'); // 혹은 다른 페이지로 리다이렉트
}
?>
