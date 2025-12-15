<?php
// 简单测试脚本 - 测试 file.php 核心功能

// 设置UTF-8编码
ini_set('default_charset', 'UTF-8');
header('Content-Type: text/html; charset=UTF-8');

// 引入必要文件
require_once __DIR__ . '/../../libs/function/file.php';

// 测试文件路径
$test_file_path = __DIR__ . '/../../files/test_file';

// 确保测试文件存在
if (!file_exists($test_file_path)) {
    file_put_contents($test_file_path, "测试文件内容 - " . date('Y-m-d H:i:s'));
    echo "<p>已创建测试文件: {$test_file_path}</p>";
}

echo "<h1>File.php 功能测试</h1>";

echo "<h2>1. 测试 File_create() - 创建文件映射</h2>";
$file_obj = File_create($test_file_path);
if ($file_obj) {
    $file_id = $file_obj->get_id();
    echo "<p style='color: green;'>✓ 成功创建文件映射，ID: {$file_id}</p>";
} else {
    echo "<p style='color: red;'>✗ 创建文件映射失败</p>";
}

echo "<h2>2. 测试 File_get_File() - 获取文件对象</h2>";
// 重新获取文件对象以确保测试完整性
$file_obj = File_get_File($file_id);
if ($file_obj) {
    echo "<p style='color: green;'>✓ 成功获取文件对象</p>";
    echo "<p>文件信息：</p>";
    echo "<ul>";
    echo "<li>ID: " . File_get_id($file_obj) . "</li>";
    echo "<li>路径: " . File_get_path($file_obj) . "</li>";
    echo "<li>类型: " . File_get_type($file_obj) . "</li>";
    echo "<li>名称: " . File_get_name($file_obj) . "</li>";
    echo "<li>大小: " . File_get_size($file_obj) . " bytes</li>";
    $file_time = File_get_time($file_obj);
    $time_display = is_numeric($file_time) ? date('Y-m-d H:i:s', $file_time) : "无效时间";
    echo "<li>时间: " . $time_display . "</li>";
    echo "<li>用户ID: " . File_get_user_id($file_obj) . "</li>";
    echo "<li>状态: " . File_get_status($file_obj) . "</li>";
    echo "</ul>";
} else {
    echo "<p style='color: red;'>✗ 获取文件对象失败</p>";
}

echo "<h2>3. 测试 File_set_name() - 修改文件名</h2>";
if ($file_obj) {
    $new_name = "updated_test_file.txt";
    $result = File_set_name($file_obj, $new_name);
    if ($result) {
        echo "<p style='color: green;'>✓ 成功修改文件名</p>";
        echo "<p>新文件名: " . File_get_name($file_obj) . "</p>";
    } else {
        echo "<p style='color: red;'>✗ 修改文件名失败</p>";
    }
}

echo "<h2>4. 测试 File_get_Files() - 获取所有匹配文件</h2>";
if ($file_obj) {
    $files = File_get_Files(File_get_name($file_obj));
    if ($files && is_array($files)) {
        echo "<p style='color: green;'>✓ 成功获取 " . count($files) . " 个匹配文件</p>";
    } else {
        echo "<p style='color: red;'>✗ 获取匹配文件失败</p>";
    }
}

echo "<h2>5. 测试空对象处理 - 传入null参数</h2>";
// 测试get函数
$null_result = File_get_name(null);
echo "<p>File_get_name(null) 返回: " . ($null_result === null ? "null (正确)": "$null_result (错误)") . "</p>";

// 测试set函数
$false_result = File_set_name(null, "test");
echo "<p>File_set_name(null, 'test') 返回: " . ($false_result === false ? "false (正确)": "$false_result (错误)") . "</p>";

echo "<h2>测试完成</h2>";
echo "<p>所有测试已执行完毕。</p>";
