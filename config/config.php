<?php
include_once __DIR__ . '/../include/_PRE.php';

global $config;
/**
 * 配置文件
 * 请根据实际情况修改以下配置
 */

// 标记是否引用了该配置文件
if (!isset($config["loaded"])) {
    $config['loaded'] = true;

    // 数据库配置
    $config['db_enable'] = true;     // 是否启用数据库连接
    $config['db'] = array(
        'host' => 'localhost',      // 数据库主机
        'user' => 'root',           // 数据库用户名
        'password' => 'root',       // 数据库密码
        'database' => 'phpweb',     // 数据库名
        'charset' => 'utf8'         // 数据库字符集
    );

    // 时区配置
    $config['timezone'] = 'PRC';  // 时区设置，如PRC表示中国时区

    // 网站基本配置
    $config['site'] = array(
        'name' => '网站名称',       // 网站名称
        'url' => 'http://localhost/PHPweb',  // 网站URL
    );
    
    // 调试配置
    $config['debug'] = array(
        'use_debug' => true,        // 调试试模式，生产环境请设置为false
        'more_debug' => true,        // 是否启用进阶调试
        'clear_debug' => false        // 是否提炼最新调试信息(避免调试输出受HTML标签影响)
    );
    
    // 数据库路由配置
    $config['DB_router'] = array(
        'enable' => true,           // 是否启用数据库路由
        'table' => 'router',        // 数据库路由表名
    );
    
    // 默认路由配置(优先级最低)
    add_Page('/', 'index.php');
    add_Page('/test', 'test/test.php');
    add_Page('/error', 'router.php');
    add_Page('/test/api','test/test_api.php');

    // 其他配置项可以根据需要添加
}
