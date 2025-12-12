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





} else {
    Error_404();
}
