<?php
include "db.php";

if (!$db) {
    die('DB 연결 망했네 ㅋㅋ ' . mysqli_connect_error());
}

$query = "SELECT * FROM chatrooms";
$result = mysqli_query($db, $query);

if (!$result) {
    die('쿼리 쓰레기같네 ㅋㅋ ' . mysqli_error($db));
}

while ($row = mysqli_fetch_assoc($result)) {
    if($row['nation']=="japan") {
        $nation = "&#127471;&#127477;";
    } else {
        $nation = "🇰🇷";
    }
    if($row['current_users'] >= 2) {
        $full = "가득참";
    } else {
        $full = $row['current_users'];
    }
    echo "<a class='list' href='chatroom.php?id=" . $row['id'] . "'>" . $row['name'] . " (현재 인원 : " . $full . ") <span class='right'> 방장: " . $row['creator'] . "[" . $nation. "]</span></a><br><hr>";
}

mysqli_close($db);
?>
