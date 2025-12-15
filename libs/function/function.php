<?php
// 全局函数库

// 引入预处理库
include_once __DIR__ . '/../../include/_PRE.php';

include_once __DIR__ . '/../../config/config.php';

// 定义常量
// 使用 dirname 获取项目根目录，确保在任何文件中引用时都能正确指向主目录
// PHP 5兼容写法：多次调用dirname()
define('MAIN_PATH', dirname(dirname(__DIR__)) . '/');
