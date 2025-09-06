<?php
session_start();
include "db.php";

$username = $_POST['username'];
$message = $_POST['message'];
$chatroom_id = $_POST['chatroom_id'];

$query = "INSERT INTO messages (username, message, chatroom_id) VALUES ('$username', '$message', '$chatroom_id')";

$result = mysqli_query($db, $query);

if (!$result) {
    echo "Failed to send message";
} else {
    echo "Message sent";
}
?>
