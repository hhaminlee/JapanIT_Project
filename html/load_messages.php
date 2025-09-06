<?php
include "db.php";

if (!isset($_GET['id'])) {
    die('No chatroom ID provided');
}

$chatroom_id = $_GET['id'];

$query = "SELECT * FROM messages WHERE chatroom_id = $chatroom_id ORDER BY no ASC LIMIT 40";
$result = mysqli_query($db, $query);

if (!$result) {
    die('Failed to load messages: ' . mysqli_error($db));
}

$messages = [];
while ($row = mysqli_fetch_assoc($result)) {
    $messages[] = [
        'id' => $row['no'], // 메시지 ID 추가
        'username' => $row['username'],
        'message' => $row['message'],
    ];
}

echo json_encode($messages);
?>
