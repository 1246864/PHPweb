<!-- URL {/test/user/1} -->
<?php
// 测试脚本 - 测试 libs/function/user.php 中的所有方法
// 设置UTF-8编码
header('Content-Type: text/html; charset=utf-8');

// 初始化测试环境
ob_start();

include_once __DIR__ . '/../../headers/user.php';

// 初始化测试环境
echo "<h1>PHPweb User API 测试脚本</h1>";
echo "<p>开始测试 libs/function/user.php 中的所有方法...</p>";
echo "<hr>";

// 测试辅助函数（密码哈希和验证）
echo "<h2>1. 测试辅助函数</h2>";

// 测试 encryptPassword 和 verifyPassword
$test_password = "Test123456";
try {
    $hashed_password = encryptPassword($test_password);
    echo "<p><strong>encryptPassword 测试:</strong> 成功生成哈希: " . substr($hashed_password, 0, 20) . "...</p>";
    
    // 测试密码验证
    $verify_result = verifyPassword($test_password, $hashed_password);
    echo "<p><strong>verifyPassword 测试:</strong> 正确密码验证 " . ($verify_result ? "成功" : "失败") . "</p>";
    
    // 测试错误密码验证
    $wrong_password = "WrongPassword";
    $wrong_verify_result = verifyPassword($wrong_password, $hashed_password);
    echo "<p><strong>verifyPassword 测试:</strong> 错误密码验证 " . (!$wrong_verify_result ? "成功（正确返回false）" : "失败") . "</p>";
} catch (RuntimeException $e) {
    echo "<p><strong>错误:</strong> " . $e->getMessage() . "</p>";
}
echo "<hr>";

// 测试用户注册
echo "<h2>2. 测试用户注册</h2>";

// 生成随机测试用户信息
$test_username = "test_user_" . time();
$test_password = "Test123456";
$test_email = "test_" . time() . "@example.com";

// 测试简便API
$result = register($test_username, $test_password, $test_email);
echo "<p><strong>register() 测试:</strong> " . ($result ? "成功" : "失败") . "</p>";

// 测试详细实现
$result2 = User_register($test_username . "2", $test_password, $test_email . "2");
echo "<p><strong>User_register() 测试:</strong> " . ($result2 ? "成功" : "失败") . "</p>";
 echo "<hr>";

// 测试用户登录
echo "<h2>3. 测试用户登录</h2>";

// 测试简便API
$login_result = login($test_username, $test_password);
echo "<p><strong>login() 测试:</strong> " . ($login_result ? "成功" : "失败") . "</p>";

// 测试详细实现
$login_result2 = User_login($test_username, $test_password);
echo "<p><strong>User_login() 测试:</strong> " . ($login_result2 ? "成功" : "失败") . "</p>";

// 测试错误密码登录
$wrong_login_result = User_login($test_username, "WrongPassword");
echo "<p><strong>User_login() 测试:</strong> 错误密码登录 " . (!$wrong_login_result ? "成功（正确返回false）" : "失败") . "</p>";

echo "<hr>";

// 测试获取用户信息
echo "<h2>4. 测试获取用户信息</h2>";

// 测试 User_get_user
$user_info = User_get_user($test_username);
echo "<p><strong>User_get_user() 测试:</strong> " . ($user_info ? "成功" : "失败") . "</p>";
if ($user_info) {
    echo "<p>用户信息: ID=" . $user_info->id . ", 用户名=" . $user_info->username . ", 邮箱=" . $user_info->email . ", 角色=" . $user_info->role . "</p>";
}
echo "<hr>";

// 测试修改用户信息
echo "<h2>5. 测试修改用户信息</h2>";

if ($user_info) {
    // 测试修改用户名
    $new_username = "test_user_new_" . time();
    $change_name_result = User_change_name($user_info, $new_username);
    echo "<p><strong>User_change_name() 测试:</strong> " . ($change_name_result ? "成功" : "失败") . "</p>";
    if ($change_name_result) {
        echo "<p>用户名已修改为: " . $change_name_result->username . "</p>";
        // 更新用户信息变量
        $user_info = $change_name_result;
        // 更新测试用户名
        $test_username = $new_username;
    }
    
    // 测试修改邮箱
    $new_email = "test_new_" . time() . "@example.com";
    $change_email_result = User_change_email($user_info, $new_email);
    echo "<p><strong>User_change_email() 测试:</strong> " . ($change_email_result ? "成功" : "失败") . "</p>";
    if ($change_email_result) {
        echo "<p>邮箱已修改为: " . $change_email_result->email . "</p>";
        // 更新用户信息变量
        $user_info = $change_email_result;
    }
}
echo "<hr>";

// 测试用户权限变更
echo "<h2>6. 测试用户权限变更</h2>";

if ($user_info) {
    // 测试升级为作者
    $to_writer_result = User_to_writer($user_info);
    echo "<p><strong>User_to_writer() 测试:</strong> " . ($to_writer_result ? "成功" : "失败") . "</p>";
    if ($to_writer_result) {
        echo "<p>用户角色已变更为: " . $to_writer_result->role . "</p>";
        $user_info = $to_writer_result;
    }
    
    // 测试升级为管理员
    $to_admin_result = User_to_admin($user_info);
    echo "<p><strong>User_to_admin() 测试:</strong> " . ($to_admin_result ? "成功" : "失败") . "</p>";
    if ($to_admin_result) {
        echo "<p>用户角色已变更为: " . $to_admin_result->role . "</p>";
        $user_info = $to_admin_result;
    }
    
    // 测试降级为普通用户
    $to_user_result = User_to_user($user_info);
    echo "<p><strong>User_to_user() 测试:</strong> " . ($to_user_result ? "成功" : "失败") . "</p>";
    if ($to_user_result) {
        echo "<p>用户角色已变更为: " . $to_user_result->role . "</p>";
        $user_info = $to_user_result;
    }
}
echo "<hr>";

// 测试获取所有用户
echo "<h2>7. 测试获取所有用户</h2>";

if ($user_info) {
    $all_users = User_get_users($user_info);
    echo "<p><strong>User_get_users() 测试:</strong> " . ($all_users ? "成功" : "失败") . "</p>";
    if ($all_users) {
        echo "<p>共获取到 " . count($all_users) . " 个用户</p>";
        // 只显示前5个用户的信息
        $count = 0;
        foreach ($all_users as $u) {
            if ($count >= 5) break;
            echo "<p>用户 " . ($count+1) . ": ID=" . $u->id . ", 用户名=" . $u->username . "</p>";
            $count++;
        }
        if (count($all_users) > 5) {
            echo "<p>...</p>";
        }
    }
}
echo "<hr>";

// 测试用户注销
echo "<h2>8. 测试用户注销</h2>";

// 测试简便API
$logout_result = logout();
echo "<p><strong>logout() 测试:</strong> " . ($logout_result ? "成功" : "失败") . "</p>";

// 重新登录以便测试详细实现
User_login($test_username, $test_password);

// 测试详细实现
$logout_result2 = User_logout();
echo "<p><strong>User_logout() 测试:</strong> " . ($logout_result2 ? "成功" : "失败") . "</p>";
echo "<hr>";

// 测试检查用户名和邮箱是否存在
echo "<h2>9. 测试检查用户名和邮箱</h2>";

// 测试 User_check_name
$check_name_result = User_check_name($test_username);
echo "<p><strong>User_check_name() 测试:</strong> " . ($check_name_result ? "用户名存在（正确）" : "用户名不存在（错误）") . "</p>";

// 测试不存在的用户名
$check_name_result2 = User_check_name("non_existent_user_" . time());
echo "<p><strong>User_check_name() 测试:</strong> 不存在的用户名 " . (!$check_name_result2 ? "返回false（正确）" : "返回true（错误）") . "</p>";

// 测试 User_check_email
$check_email_result = User_check_email($test_email);
echo "<p><strong>User_check_email() 测试:</strong> " . ($check_email_result ? "邮箱存在（正确）" : "邮箱不存在（错误）") . "</p>";

// 测试不存在的邮箱
$check_email_result2 = User_check_email("non_existent_" . time() . "@example.com");
echo "<p><strong>User_check_email() 测试:</strong> 不存在的邮箱 " . (!$check_email_result2 ? "返回false（正确）" : "返回true（错误）") . "</p>";
echo "<hr>";

// 测试自动登录
echo "<h2>10. 测试自动登录</h2>";

// 测试自动登录功能需要在没有输出之前设置cookie，所以这里只测试功能逻辑
// 不实际测试浏览器中的自动登录效果

echo "<p><strong>User_auto_login() 测试:</strong> 在命令行环境中跳过（需要浏览器环境）</p>";
echo "<hr>";

// 清理测试数据
echo "<h2>11. 清理测试数据</h2>";

global $conn;
if ($conn && is_object($conn)) {
    // 删除测试用户
    $stmt = $conn->prepare("DELETE FROM user WHERE username LIKE ? OR email LIKE ?");
    $like_username = "test_user_%";
    $like_email = "test_%@example.com";
    $stmt->bind_param("ss", $like_username, $like_email);
    $stmt->execute();
    $affected_rows = $stmt->affected_rows;
    $stmt->close();
    echo "<p>已清理 " . $affected_rows . " 条测试用户数据</p>";
}

// 测试完成
echo "<h2>测试完成！</h2>";
echo "<p>所有方法测试结束。</p>";

ob_end_flush();
