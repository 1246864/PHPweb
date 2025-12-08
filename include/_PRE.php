<?php
// 预处理, 提供文件头以及自定义方法库构造前的一些函数的封装

// 防止函数被重复声明

if (!isset($x__RPE_OK)) {
    $x__RPE_OK = true;
    
    // 500 错误页面快捷跳转
    function Error_500($error_message) {
        // 清空页面
        ob_end_clean();
        // 设置 HTTP 状态码为 500
        http_response_code(500);
        // 转发到 500 错误页面, 并传递错误信息
        include_once __DIR__.'/../page_error/500.php';
        exit();
    }

    // 404 错误页面快捷跳转
    function Error_404() {
        // 清空页面
        ob_end_clean();
        // 设置 HTTP 状态码为 404
        http_response_code(404);
        // 转发到 404 错误页面
        include_once __DIR__.'/../page_error/404.php';
        exit();
    }
}
