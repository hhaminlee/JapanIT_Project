<?php
include "db.php";
session_start();
$user_id = $_SESSION["userid"];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && isset($_POST['creator_nick'])) {
        $chatroom_id = $_POST['id'];
        $creator_nick = $_POST['creator_nick'];
        $query = "SELECT creator_exist FROM chatrooms WHERE id = $chatroom_id"; // messages는 채팅방 메시지를 저장하는 테이블이라고 가정
        $result = mysqli_query($db, $query);

        if($user_id == $creator_nick) {
            $query = "UPDATE chatrooms SET creator_exist = 1 WHERE id = $chatroom_id";
            $result = mysqli_query($db, $query);
        }
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['creator_exist'] == 1) {
                    echo "1";  // creator가 현재 채팅방에 있음
                    return;
                }
            }
        }
        echo "0"; // creator가 현재 채팅방에 없음 or Query 실패
    }
}
?>
