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

    // 路由配置 - URL路径到控制器方法的映射
    // 
    // 路由格式：'URL路径' => '控制器名@方法名'
    // 
    // 静态路由示例：
    // '' => 'HomeController@index'              // 网站根路径访问首页
    // 'about' => 'HomeController@about'         // /about 访问关于页面
    // 'user' => 'UserController@index'          // /user 访问用户中心
    // 
    // 多级路径示例：
    // 'user/profile' => 'UserController@profile' // /user/profile 访问个人资料
    // 'user/login' => 'UserController@login'     // /user/login 访问登录页面
    // 
    // 动态路由示例（带参数）：
    // 'demo/user/{id}' => 'DemoController@userProfile' // /demo/user/123 会传递 $id=123 参数
    // 
    // 注意：路由器会按照这个配置来分发请求，修改路由后需要确保对应的控制器和方法存在
    $config['routes'] = array(
        '' => 'HomeController@index',              // 网站首页
        'home' => 'HomeController@index',          // 首页别名
        'user' => 'UserController@index',          // 用户中心
        'user/profile' => 'UserController@profile', // 用户个人资料
        'user/login' => 'UserController@login',     // 用户登录
        'user/logout' => 'UserController@logout',   // 用户退出
        'about' => 'HomeController@about',         // 关于页面
        'contact' => 'HomeController@contact',      // 联系页面
        
        // GET参数演示路由
        'demo/get' => 'DemoController@getParams',  // 传统GET参数演示
        'demo/route' => 'DemoController@userProfile', // 路由参数演示
        'demo/mixed/{category}' => 'DemoController@mixedParams', // 混合参数演示
        'demo/secure' => 'DemoController@secureParams', // 安全参数处理演示
    );

    // 控制器配置 - 控制器文件和类的相关设置
    // 
    // 这些配置告诉路由器如何找到和加载控制器：
    // - namespace: 控制器的命名空间（当前为空，表示全局命名空间）
    // - directory: 控制器文件所在的目录路径
    // - suffix: 控制器类名的后缀，便于识别
    // 
    // 路由器会根据这些配置来构建控制器文件的完整路径
    $config['controllers'] = array(
        'namespace' => '',                           // 控制器命名空间（留空表示全局）
        'directory' => __DIR__ . '/../controllers/', // 控制器文件目录
        'suffix' => 'Controller'                     // 控制器类名后缀
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
