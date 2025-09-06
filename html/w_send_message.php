<?php
session_start();
include "db.php";

$username = $_POST['username'];
$message = $_POST['message'];

$query = "INSERT INTO w_messages (username, message) VALUES ('$username', '$message')";

$result = mysqli_query($db, $query);

if (!$result) {
    echo "Failed to send message";
} else {
    echo "Message sent";
}
?>
