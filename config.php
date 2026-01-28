<?php
// config.php
// 데이터베이스 접속 정보를 설정합니다.
// 이 파일은 PHP 파일이므로 브라우저로 접근해도 내용이 보이지 않아 안전합니다.

// 보안 점검: 허가된 파일(db_connect.php)에서 부른 게 아니면 차단
if (!defined('ALLOWED_ACCESS')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Access Denied");
}

return [
    'DB_HOST' => 'DB_HOST',
    'DB_NAME' => 'DB_NAME',
    'DB_USER' => 'DB_USER',
    'DB_PASS' => 'DB_PASS',
];