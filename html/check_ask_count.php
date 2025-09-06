<?php
include "db.php";

$chatroom_id = $_POST['id'];
session_start();
$query_check = "SELECT current_users, creator, keyword, name, ask_count FROM chatrooms WHERE id = $chatroom_id";
$result_check = mysqli_query($db, $query_check);
if (!$result_check) {
    die("Query failed: " . mysqli_error($db));
}
$row_check = mysqli_fetch_assoc($result_check);
$creator_id = $row_check['creator'];
$user_id = $_SESSION["userid"];

if($user_id != $creator_id) {
    $query = "UPDATE chatrooms SET ask_count = ask_count + 1 WHERE id = $chatroom_id";
    $result = mysqli_query($db, $query);




    $query = "SELECT ask_count FROM chatrooms WHERE id = $chatroom_id";
    $result = mysqli_query($db, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $count = $row['ask_count'];
    }
    echo $count;
}
?>
