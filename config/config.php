<?php

/**
 * 配置文件
 * 请根据实际情况修改以下配置
 */

// 标记是否引用了该配置文件
if (!isset($config["loaded"])) {
    $config['loaded'] = true;

    // 数据库配置
    $config['db'] = array(
        'host' => 'localhost',      // 数据库主机
        'user' => 'root',           // 数据库用户名
        'password' => 'root',       // 数据库密码
        'database' => 'test',     // 数据库名
        'charset' => 'utf8'         // 数据库字符集
    );

    // 时区配置
    $config['timezone'] = 'PRC';  // 时区设置，如PRC表示中国时区

    // 网站基本配置
    $config['site'] = array(
        'name' => 'PHPweb项目',     // 网站名称
        'url' => 'http://localhost/PHPweb',  // 网站URL
        'debug' => true,            // 调试模式，生产环境请设置为false
        'title_separator' => ' - ', // 页面标题分隔符
        'meta_description' => '一个简单的PHP Web应用程序，演示了基础的路由系统实现', // 网站描述
        'meta_keywords' => 'PHP,路由系统,MVC,Web应用' // 网站关键词
    );

    // 路由配置
    $config['routes'] = array(
        '' => 'HomeController@index',
        'home' => 'HomeController@index',
        'user' => 'UserController@index',
        'user/profile' => 'UserController@profile',
        'user/login' => 'UserController@login',
        'user/logout' => 'UserController@logout',
        'about' => 'HomeController@about',
        'contact' => 'HomeController@contact'
    );

    // 控制器配置
    $config['controllers'] = array(
        'namespace' => '',           // 控制器命名空间
        'directory' => __DIR__ . '/../controllers/', // 控制器目录
        'suffix' => 'Controller'     // 控制器类名后缀
    );

    // 视图配置
    $config['view'] = array(
        'directory' => __DIR__ . '/../views/', // 视图目录（预留）
        'extension' => '.php',       // 视图文件扩展名
        'layout' => 'default'        // 默认布局
    );

    // 错误页面配置
    $config['error_pages'] = array(
        '404' => __DIR__ . '/../page_error/404.php',
        '500' => __DIR__ . '/../page_error/500.php'
    );

    // 会话配置
    $config['session'] = array(
        'name' => 'PHPweb_session',  // 会话名称
        'lifetime' => 3600,          // 会话生命周期（秒）
        'path' => '/',                // 会话路径
        'domain' => '',               // 会话域名
        'secure' => false,            // 仅HTTPS传输
        'httponly' => true            // 仅HTTP访问
    );

    // 安全配置
    $config['security'] = array(
        'csrf_protection' => false,  // CSRF保护
        'xss_protection' => true,    // XSS保护
        'sql_injection_protection' => false, // SQL注入保护
        'password_hash_algorithm' => PASSWORD_DEFAULT // 密码哈希算法
    );

    // 其他配置项可以根据需要添加
}
