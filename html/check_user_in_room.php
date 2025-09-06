<?php
include "db.php";
session_start();
$user_id = $_SESSION["userid"];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && isset($_POST['creator_nick'])) {
        $chatroom_id = $_POST['id'];
        $creator_nick = $_POST['creator_nick'];
        $query = "SELECT current_users FROM chatrooms WHERE id = $chatroom_id"; 
        $result = mysqli_query($db, $query);
        $row = mysqli_fetch_assoc($result);
        if ($row['current_users'] == 1) {
            echo "아무도 방에 없습니다.";  
            return;
        } else {
            if($user_id != $creator_nick) {
                $query = "UPDATE chatrooms SET user_name = '$user_id' WHERE id = $chatroom_id";
                $result = mysqli_query($db, $query);
                
            }
            $query = "SELECT user_name FROM chatrooms WHERE id = $chatroom_id"; 
                $result = mysqli_query($db, $query);
                while($row = mysqli_fetch_assoc($result)) {
                    echo $row['user_name'];
                }
        }
        
        

        
    }
}
?>
