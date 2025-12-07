<?php
// 引入配置文件
if (!isset($config['loaded'])) {
    if (file_exists('../config/config.php')) {
        include '../config/config.php';
    } else {
        // 如果配置文件不存在，使用默认值或显示错误
        http_response_code(500);
        $error_message = '配置文件不存在，请创建 config.php 文件。';
        include 'error/500.php';
        exit();
    }
}

if (!isset($conn)) {
    // 从配置文件获取数据库连接信息
    $conn = mysqli_connect(
        $config['db']['host'],
        $config['db']['user'],
        $config['db']['password'],
        $config['db']['database']
    );

    if (!$conn) {
        http_response_code(500);
        $error_message = '数据库连接失败，请检查配置文件。';
        include 'error/500.php';
        exit();
    }

    $conn->query('set names ' . $config['db']['charset']);
    date_default_timezone_set($config['timezone']);
}
