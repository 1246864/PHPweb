<?php
// 404 拦截逻辑（加在 index.php 最顶部）
$request_path = $_SERVER['REQUEST_URI'];
$file_path = $_SERVER['DOCUMENT_ROOT'] . $request_path;
// 只拦截「文件/目录不存在」的请求，正常请求直接跳过
if (!file_exists($file_path) && !is_dir($file_path)) {
    include 'include/error/404.html'; // 你的 404 页面路径
    exit; // 拦截后直接退出，不执行后面的代码
}

include 'header.php';
?>