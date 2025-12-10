<?php
// 预处理, 提供文件头以及自定义方法库构造前的一些函数的封装

// 防止函数被重复声明

if (!isset($x__RPE_OK)) {
    $x__RPE_OK = true;
    
    // 500 错误页面快捷跳转
    function Error_500($error_message) {
        // 获取config信息
        include_once __DIR__."/../config/config.php";
        global $config;
        if ($config['debug']['use_debug']) {
            echo'<div style="color:red;font-size:30px;">'.$error_message.'</div>';
            return;
        }      // 开发环境下, 直接输出错误信息, 不触发500页面

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

    // 501 错误页面快捷跳转
    function Error_501($error_message) {
        // 清空页面
        ob_end_clean();
        // 设置 HTTP 状态码为 501
        http_response_code(501);
        // 转发到 501 错误页面, 并传递错误信息
        include_once __DIR__.'/../page_error/501.php';
        exit();
    }

    // 快捷动态添加路由跳转配置
    // Router_Page(url, 具体页面, [请求方式])
    $config['router_Page'] = array();
    function add_Page($page_name, $page_file, $method = 'ALL') {
        global $config;
        $config['router_Page'][$page_name] = array(
            'file' => $page_file,  // 具体页面
            'method' => $method,     // 请求方式
        );
    }
}
