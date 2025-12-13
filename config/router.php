<?php
include_once __DIR__ . '/../include/_PRE.php';
// 自定义路由配置文件

// 包含用户API路由
include_once __DIR__ . '/../api/user.php';

if (isset($open_self_router) && $open_self_router) {
    $open_self_router = false;
    global $router;
    // 自定义路由(声明方法遵循Bramus Router)
    // 例如：$router->get('/example', function () { include __DIR__ . '/bramus-example.php'; });
    $router->all('/hello', function () {
        echo 'Hello World!';
    });

    // 你可以自行在这添加其他路由(优先级最高)
    // ↓↓↓↓↓↓






    // 装载预定义路由 -----------

    // 自动注册路由
    $router->all('/admin/auto_router', function () {
        include_once __DIR__ . '/../admin/auto_router.php';
    });
    // 自动注册路由,并且给定输出
    $router->all('/admin/auto_router/echo', function () {
        // 设置响应头为JSON
        header('Content-Type: application/json; charset=utf-8');
        // 执行自动路由
        include_once __DIR__ . '/../admin/auto_router.php';
        // 返回JSON结果
        global $__auto_router_result;
        exit(json_encode([
            'code' => 0,
            'msg' => '扫描完成',
            'data' => $__auto_router_result,
        ], JSON_UNESCAPED_UNICODE));
    });

    // user 相关路由
    $router->all('/api/user/check_name/(.*)', function ($username) {
        include_once __DIR__ . '/api/user.php';
        echo User_check_name($username) ? 'true' : 'false';
    });
    $router->all('/api/user/check_email/(.*)', function ($email) {
        include_once __DIR__ . '/api/user.php';
        echo User_check_email($email) ? 'true' : 'false';
    });
} else {
    Error_404();
}
