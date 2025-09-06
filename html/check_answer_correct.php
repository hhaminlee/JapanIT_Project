<?php
include "db.php";

$chatroom_id = $_POST['id'];

$query = "SELECT answer_correct FROM chatrooms WHERE id = $chatroom_id";
$result = mysqli_query($db, $query);
$row = mysqli_fetch_assoc($result);

echo $row['answer_correct'];
?>
