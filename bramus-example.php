<?php
/**
 * Bramus Router 集成示例
 * 
 * 展示如何使用 bramus/router 路由库
 */

// 引入 bramus/router
require_once __DIR__ . '/libs/Bramus/Router/Router.php';

// 创建路由实例
$router = new \Bramus\Router\Router();

// 设置基础路径
$router->setBasePath('/PHPweb');

// 自定义404处理
$router->set404(function() {
    header('HTTP/1.1 404 Not Found');
    echo '<h1>404 - 页面未找到</h1>';
    echo '<p>抱歉，您请求的页面不存在。</p>';
});

// 首页路由
$router->get('/', function() {
    echo '<h1>欢迎使用 Bramus Router</h1>';
    echo '<p>这是一个集成示例</p>';
    echo '<ul>';
    echo '<li><a href="/PHPweb/hello">Hello 页面</a></li>';
    echo '<li><a href="/PHPweb/user/123">用户页面</a></li>';
    echo '<li><a href="/PHPweb/api/test">API测试</a></li>';
    echo '</ul>';
});

// 简单路由
$router->get('/hello', function() {
    echo '<h1>Hello World!</h1>';
    echo '<a href="/PHPweb">返回首页</a>';
});

// 带参数路由
$router->get('/user/(\d+)', function($id) {
    echo "<h1>用户ID: $id</h1>";
    echo '<a href="/PHPweb">返回首页</a>';
});

// API路由组
$router->mount('/api', function() use ($router) {
    $router->get('/test', function() {
        header('Content-Type: application/json');
        echo json_encode(['message' => 'API测试成功', 'status' => 'ok']);
    });
});

// 运行路由器
$router->run();

?>