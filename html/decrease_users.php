<?php
//이 php파일은 채팅방에서 사람이 나가면 비동기로 current_user를 -1로 만들어주는 서버입니다.
include "db.php";
session_start();
$user_id = $_SESSION["userid"];
if (isset($_POST['id'])) {
    $chatroom_id = $_POST['id'];
    $query = "SELECT creator FROM chatrooms WHERE id = $chatroom_id";
    $result = mysqli_query($db, $query);
    if($result) {
        while($row = mysqli_fetch_assoc($result)) {
            $creator_id = $row['creator'];
        }
    }

    $query = "UPDATE chatrooms SET current_users = current_users - 1 WHERE id = $chatroom_id AND current_users > 0";
    $result = mysqli_query($db, $query);
    if($creator_id == $user_id) {
        $query = "UPDATE chatrooms SET creator_exist = 0 WHERE id = $chatroom_id";
        $result = mysqli_query($db, $query);
    }
    if (!$result) {
        die("Query failed: " . mysqli_error($db));
    }
}
?>
