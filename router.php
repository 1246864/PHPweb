<?php
include_once __DIR__ . '/include/_PRE.php';
include_once __DIR__ . '/include/header.php';
include_once __DIR__ . '/libs/Bramus/Router/Router.php';
include_once __DIR__ . '/config/config.php';

global $router;
if (!isset($router)) {
    $router = new \Bramus\Router\Router();

    foreach ($config['router_Page'] as $key => $value) {
        // 查看文件是否存在
        if (!file_exists(__DIR__ . '/' . $value['file'])) {
            Error_501('目标文件不存在：' . $value['file']);
        }

        // 根据请求方法注册路由
        switch ($value['method']) {
            case 'GET':
                $router->get($key, function () use ($config, $value) {
                    $params = func_get_args();
                    $_GET['URL'] = $params;
                    include __DIR__ . '/' . $value['file'];
                });
                break;
            case 'POST':
                $router->post($key, function () use ($config, $value) {
                    $params = func_get_args();
                    $_GET['URL'] = $params;
                    include __DIR__ . '/' . $value['file'];
                });
                break;
            default:
                $router->all($key, function () use ($config, $value) {
                    $params = func_get_args();
                    $_GET['URL'] = $params;

                    include __DIR__ . '/' . $value['file'];
                });
                break;
        }
    }

    $router->set404(function () {
        Error_404();
    });

    $router->run();
}
else {
    Error_404();
}