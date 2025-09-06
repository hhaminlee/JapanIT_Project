<?php
$servername = "localhost"; // MySQL 서버 주소
$username = "shitsumun"; // MySQL 사용자 이름
$password = "japanit2023**"; // MySQL 비밀번호
$dbname = "shitsumun"; // 사용할 데이터베이스 이름

// 데이터베이스 연결 생성
$db = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($db->connect_error) {
    die("데이터베이스 연결 실패: " . $db->connect_error);
}
?>
