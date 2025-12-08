<?php
// 引入预处理库
include_once __DIR__ . '/include/_PRE.php';

// 引入路由器
include_once __DIR__ . '/include/router.php';

// 创建路由实例并分发请求
$router = new Router();
$router->dispatch();
?>