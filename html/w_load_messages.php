<?php
include "db.php";

$query = "SELECT * FROM w_messages ORDER BY no DESC LIMIT 18";
$result = mysqli_query($db, $query);

if (!$result) {
    die('Failed to load messages: ' . mysqli_error($db));
}

$messages = [];
while ($row = mysqli_fetch_assoc($result)) {
    $messages[] = $row;
}

$messages = array_reverse($messages); // 배열을 뒤집어 오래된 메세지부터 출력

foreach ($messages as $message) {
    echo $message['username'] . ': ' . $message['message'] . '<br>';
}
?>
