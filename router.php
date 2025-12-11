<?php
include_once __DIR__ . '/include/_PRE.php';
include_once __DIR__ . '/libs/Bramus/Router/Router.php';
include_once __DIR__ . '/config/config.php';
include_once __DIR__ . '/config/.auto_router_config.php';
include_once __DIR__ . '/include/debug.php';

global $router;
if (!isset($router)) {
    $router = new \Bramus\Router\Router();


    // 自动装载配置文件及数据库中的路由
    foreach ($config['router_Page'] + $config['auto_router_Page'] as $key => $value) {
        // 查看文件是否存在
        if (!file_exists(__DIR__ . '/' . $value['file'])) {
            if ($config['debug']['use_debug']) {
                $router->all($key, function () use ($value) {
                    Error_501('目标文件不存在：' . $value['file']);
                });
                echo '警告 注册路由时目标文件不存在：' . $value['file'] . '';
                continue;
            }
            $router->all($key, function () {
                Error_501('目标文件不存在');
            });
            continue;
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
    // 装载数据库中的路由
    if ($config['DB_router']['enable']) {
        include __DIR__ . '/include/conn.php';
        global $conn;
        $sql = "SELECT * FROM sys_routes WHERE is_enable = 1";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // 查看文件是否存在
                if (!file_exists(__DIR__ . '/' . $row['file_path'])) {
                    if ($config['debug']['use_debug']) {
                        $router->all($row['url'], function () use ($config, $row) {
                            Error_501('目标文件不存在：' . $row['file_path']);
                        });
                        echo '警告 注册路由时目标文件不存在：' . $row['file_path'] . '';
                        continue;
                    }
                    $router->all($row['url'], function () {
                        Error_501('目标文件不存在');
                    });
                    continue;
                }
                $method = strtoupper($row['method']);
                switch ($method) {
                    case 'GET':
                        $router->get($row['url'], function () use ($config, $row) {
                            $params = func_get_args();
                            $_GET['URL'] = $params;
                            include __DIR__ . '/' . $row['file_path'];
                        });
                        break;
                    case 'POST':
                        $router->post($row['url'], function () use ($config, $row) {
                            $params = func_get_args();
                            $_GET['URL'] = $params;
                            include __DIR__ . '/' . $row['file_path'];
                        });
                        break;
                    default:
                        $router->all($row['url'], function () use ($config, $row) {
                            $params = func_get_args();
                            $_GET['URL'] = $params;
                            include __DIR__ . '/' . $row['file_path'];
                        });
                        break;
                }
            }
        }
    }

    // 装载自定义路由配置文件
    $open_self_router = true;
    include __DIR__ . '/config/router.php';

    // 404路由(未匹配到任何路由时触发)
    $router->set404(function () {
        Error_404();
    });

    $router->run();
} else {
    Error_404();
}
