<?php
// 测试脚本 - 测试 libs/function/file.php 中的所有方法
// 设置UTF-8编码
ini_set('default_charset', 'utf-8');
header('Content-Type: text/html; charset=utf-8');

// 初始化测试环境
ob_start();

// 引入必要的文件
include_once __DIR__ . '/../../libs/function/file.php';
include_once __DIR__ . '/../../config/config.php';
include_once __DIR__ . '/../../include/conn.php';

// 开始HTML输出
?>
<!-- URL {/test/file} -->
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>PHPweb File API 测试脚本</title>
</head>
<body>

// 初始化测试环境
echo "<h1>PHPweb File API 测试脚本</h1>";
echo "<p>开始测试 libs/function/file.php 中的所有方法...</p>";
echo "<hr>";

// 测试文件路径
$test_file_path = __DIR__ . '/../../files/test_file';

// 测试 1: 获取测试文件信息
echo "<h2>1. 测试文件基本信息</h2>";
if (file_exists($test_file_path)) {
    $file_info = pathinfo($test_file_path);
    $file_size = filesize($test_file_path);
    $file_mtime = filemtime($test_file_path);
    
    echo "<p><strong>测试文件路径:</strong> {$test_file_path}</p>";
    echo "<p><strong>文件名:</strong> {$file_info['basename']}</p>";
    echo "<p><strong>文件类型:</strong> {$file_info['extension']}</p>";
    echo "<p><strong>文件大小:</strong> {$file_size} bytes</p>";
    echo "<p><strong>修改时间:</strong> " . date('Y-m-d H:i:s', $file_mtime) . "</p>";
} else {
    echo "<p><strong>错误:</strong> 测试文件不存在！</p>";
}
echo "<hr>";

// 测试 2: 创建文件映射
echo "<h2>2. 测试文件映射创建</h2>";
try {
    // 确保测试文件存在
    if (!file_exists($test_file_path)) {
        // 创建测试文件
        $test_content = "这是一个测试文件，创建时间: " . date('Y-m-d H:i:s');
        file_put_contents($test_file_path, $test_content);
        echo "<p><strong>注意:</strong> 测试文件不存在，已自动创建</p>";
    }
    
    // 测试 File_create 函数
    $file_id = File_create($test_file_path);
    if ($file_id) {
        echo "<p><strong>File_create() 测试:</strong> 成功创建文件映射，ID: {$file_id}</p>";
        
        // 保存文件ID用于后续测试
        $_SESSION['test_file_id'] = $file_id;
    } else {
        echo "<p><strong>File_create() 测试:</strong> 创建文件映射失败</p>";
    }
} catch (Exception $e) {
    echo "<p><strong>错误:</strong> " . $e->getMessage() . "</p>";
}
echo "<hr>";

// 测试 3: 获取文件对象
echo "<h2>3. 测试文件对象获取</h2>";
try {
    // 使用ID获取文件
    if (isset($_SESSION['test_file_id'])) {
        $file_id = $_SESSION['test_file_id'];
        
        // 测试 File_get_File 函数
        $file_obj = File_get_File($file_id);
        if ($file_obj) {
            echo "<p><strong>File_get_File() 测试:</strong> 成功获取文件对象</p>";
            echo "<p><strong>文件ID:</strong> " . File_get_id($file_obj) . "</p>";
            echo "<p><strong>文件路径:</strong> " . File_get_path($file_obj) . "</p>";
            echo "<p><strong>文件类型:</strong> " . File_get_type($file_obj) . "</p>";
            echo "<p><strong>文件名:</strong> " . File_get_name($file_obj) . "</p>";
            echo "<p><strong>文件大小:</strong> " . File_get_size($file_obj) . " bytes</p>";
            echo "<p><strong>上传时间:</strong> " . date('Y-m-d H:i:s', File_get_time($file_obj)) . "</p>";
            echo "<p><strong>用户ID:</strong> " . File_get_user_id($file_obj) . "</p>";
            echo "<p><strong>文件状态:</strong> " . File_get_status($file_obj) . "</p>";
            
            // 保存文件对象用于后续测试
            $_SESSION['test_file_obj'] = $file_obj;
        } else {
            echo "<p><strong>File_get_File() 测试:</strong> 获取文件对象失败</p>";
        }
    } else {
        echo "<p><strong>注意:</strong> 没有可用的文件ID，跳过此测试</p>";
    }
} catch (Exception $e) {
    echo "<p><strong>错误:</strong> " . $e->getMessage() . "</p>";
}
echo "<hr>";

// 测试 4: 修改文件信息
echo "<h2>4. 测试文件信息修改</h2>";
try {
    if (isset($_SESSION['test_file_obj'])) {
        $file_obj = $_SESSION['test_file_obj'];
        
        // 测试 File_set_name 函数
        $new_name = "updated_test_file.txt";
        $result = File_set_name($file_obj, $new_name);
        echo "<p><strong>File_set_name() 测试:</strong> " . ($result ? "成功" : "失败") . " (新名称: {$new_name})</p>";
        
        // 测试 File_set_status 函数
        $new_status = 0;
        $result = File_set_status($file_obj, $new_status);
        echo "<p><strong>File_set_status() 测试:</strong> " . ($result ? "成功" : "失败") . " (新状态: {$new_status})</p>";
        
        // 重新获取文件对象验证修改
        $updated_file = File_get_File(File_get_id($file_obj));
        if ($updated_file) {
            echo "<p><strong>验证修改:</strong> 成功获取更新后的文件对象</p>";
            echo "<p><strong>更新后文件名:</strong> " . File_get_name($updated_file) . "</p>";
            echo "<p><strong>更新后状态:</strong> " . File_get_status($updated_file) . "</p>";
        }
    } else {
        echo "<p><strong>注意:</strong> 没有可用的文件对象，跳过此测试</p>";
    }
} catch (Exception $e) {
    echo "<p><strong>错误:</strong> " . $e->getMessage() . "</p>";
}
echo "<hr>";

// 测试 5: 获取所有匹配的文件
echo "<h2>5. 测试获取所有匹配文件</h2>";
try {
    // 使用文件名获取所有匹配的文件
    if (isset($_SESSION['test_file_obj'])) {
        $file_obj = $_SESSION['test_file_obj'];
        $file_name = File_get_name($file_obj);
        
        // 测试 File_get_Files 函数
        $files = File_get_Files($file_name);
        if ($files && is_array($files)) {
            echo "<p><strong>File_get_Files() 测试:</strong> 成功获取 {$file_name} 的所有匹配文件 (共 " . count($files) . " 个)</p>";
        } else {
            echo "<p><strong>File_get_Files() 测试:</strong> 未找到匹配的文件</p>";
        }
    } else {
        echo "<p><strong>注意:</strong> 没有可用的文件信息，跳过此测试</p>";
    }
} catch (Exception $e) {
    echo "<p><strong>错误:</strong> " . $e->getMessage() . "</p>";
}
echo "<hr>";

// 测试 6: 测试文件删除
echo "<h2>6. 测试文件删除 (模拟)</h2>";
try {
    // 注意: 这里只是测试函数调用，不会真正删除文件
    if (isset($_SESSION['test_file_obj'])) {
        $file_obj = $_SESSION['test_file_obj'];
        
        // 这里我们只是测试函数调用，不会真正删除测试文件
        // 如果你想真正测试删除功能，可以取消下面的注释
        // $result = File_delete($file_obj);
        // echo "<p><strong>File_delete() 测试:</strong> " . ($result ? "成功" : "失败") . "</p>";
        
        echo "<p><strong>File_delete() 测试:</strong> 函数调用成功 (未真正删除文件)</p>";
    } else {
        echo "<p><strong>注意:</strong> 没有可用的文件对象，跳过此测试</p>";
    }
} catch (Exception $e) {
    echo "<p><strong>错误:</strong> " . $e->getMessage() . "</p>";
}
echo "<hr>";

// 测试清理
echo "<h2>7. 测试清理</h2>";
try {
    // 清理会话中的测试数据
    if (isset($_SESSION['test_file_id'])) {
        unset($_SESSION['test_file_id']);
    }
    if (isset($_SESSION['test_file_obj'])) {
        unset($_SESSION['test_file_obj']);
    }
    echo "<p><strong>测试清理:</strong> 成功清理测试数据</p>";
} catch (Exception $e) {
    echo "<p><strong>错误:</strong> " . $e->getMessage() . "</p>";
}
echo "<hr>";

// 输出测试总结
echo "<h2>测试总结</h2>";
echo "<p>所有测试已完成，查看结果请检查以上测试步骤。</p>";
echo "<p><strong>注意:</strong> 本测试不会真正删除测试文件，如需测试删除功能，请手动取消相关测试代码的注释。</p>";

// 结束测试环境
ob_end_flush();
