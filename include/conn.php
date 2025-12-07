<?php
 // 引入预处理库
include_once __DIR__ . '/_PRE.php';

// 引入配置文件
 include_once __DIR__.'/../config/config.php';

if (!isset($conn)) {
    if(!isset($config['db'])) {
        Error_500('配置文件中未定义数据库连接信息。');
    }
    // 从配置文件获取数据库连接信息
    $conn = mysqli_connect(
        $config['db']['host'],
        $config['db']['user'],
        $config['db']['password'],
        $config['db']['database']
    );

    if (!$conn) {
        http_response_code(500);
        // 通过500页面显示错误信息
        Error_500('数据库连接失败，请检查配置文件。<br/>' . mysqli_connect_error());
    }

    $conn->query('set names ' . $config['db']['charset']);
    date_default_timezone_set($config['timezone']);
}
