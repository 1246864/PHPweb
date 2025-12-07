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
        'name' => '网站名称',       // 网站名称
        'url' => 'http://localhost/PHPweb',  // 网站URL
        'debug' => true             // 调试模式，生产环境请设置为false
    );

    // 其他配置项可以根据需要添加
}
