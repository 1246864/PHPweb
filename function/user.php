<?php
// 引入预处理库
include_once __DIR__ . '/../include/_PRE.php';

 include_once __DIR__ . '/../include/conn.php';


function checkLogin()
{
    if (!isset($_SESSION['user'])) {
        http_response_code(403);
        include 'error/403.html';
        exit();
    }
}
