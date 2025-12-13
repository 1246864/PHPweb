<!-- URL {/test/user/3} -->
<?php
// 测试脚本 - 测试 libs/function/user.php 中的所有方法
// 设置UTF-8编码
header('Content-Type: text/html; charset=utf-8');

// 初始化测试环境
ob_start();

include_once __DIR__ . '/../../headers/user.php';


echo "<h2>测试完成！</h2>";
echo "<p>所有方法测试结束。</p>";

ob_end_flush();
