<?php
/**
 * ____________________________________________________________________________________________
 * 
 *                                  *** 通用头文件 ***
 */

// 引入预处理库
include_once __DIR__ . '/../../include/_PRE.php';

include_once __DIR__ . '/../../config/config.php';
include_once __DIR__ . '/../../libs/function/function.php';
include_once __DIR__ . '/../../include/debug.php';

// 定义常量
// 使用 dirname 获取项目根目录，确保在任何文件中引用时都能正确指向主目录
define('MAIN_PATH', dirname(__DIR__, 2) . '/');
