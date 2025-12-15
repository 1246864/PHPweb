<!-- URL {/test/file/1} -->

<?php
// 测试File类的sync方法
ini_set('default_charset', 'utf-8');

// 引入必要的文件
require_once 'd:/xampp/htdocs/PHPweb/libs/function/file.php';
require_once 'd:/xampp/htdocs/PHPweb/config/config.php';
require_once 'd:/xampp/htdocs/PHPweb/include/conn.php';

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<meta charset='utf-8'>";
echo "<title>File类sync方法测试</title>";
echo "</head>";
echo "<body>";
echo "<h1>File类sync方法测试</h1>";

try {
    // 1. 创建一个新文件映射
    echo "<h2>1. 创建新文件映射</h2>";
    $test_file_path = 'd:/xampp/htdocs/PHPweb/files/test_file';
    $file_obj = File_create($test_file_path);
    
    if ($file_obj) {
        echo "创建文件映射成功！文件ID: " . $file_obj->get_id() . "<br>";
        echo "文件路径: " . $file_obj->get_path() . "<br>";
        echo "文件类型: " . $file_obj->get_type() . "<br>";
        echo "文件名: " . $file_obj->get_name() . "<br>";
        echo "文件大小: " . $file_obj->get_size() . "<br>";
        echo "上传时间: " . $file_obj->get_time() . "<br>";
        echo "用户ID: " . $file_obj->get_user_id() . "<br>";
        echo "文件状态: " . $file_obj->get_status() . "<br>";
        
        // 2. 使用set方法修改文件属性
        echo "<h2>2. 使用set方法修改文件属性</h2>";
        $file_obj->set_name("test_sync_file.txt");
        $file_obj->set_type("text/plain");
        $file_obj->set_size(1024);
        $file_obj->set_user_id(1);
        
        echo "修改后文件名: " . $file_obj->get_name() . "<br>";
        echo "修改后文件类型: " . $file_obj->get_type() . "<br>";
        echo "修改后文件大小: " . $file_obj->get_size() . "<br>";
        echo "修改后用户ID: " . $file_obj->get_user_id() . "<br>";
        
        // 3. 同步到数据库
        echo "<h2>3. 同步到数据库</h2>";
        $sync_result = $file_obj->sync();
        if ($sync_result) {
            echo "同步到数据库成功！<br>";
            
            // 4. 重新获取文件信息验证同步结果
            echo "<h2>4. 验证同步结果</h2>";
            $new_file_obj = File_get_File($file_obj->get_id());
            if ($new_file_obj) {
                echo "重新获取的文件名: " . $new_file_obj->get_name() . "<br>";
                echo "重新获取的文件类型: " . $new_file_obj->get_type() . "<br>";
                echo "重新获取的文件大小: " . $new_file_obj->get_size() . "<br>";
                echo "重新获取的用户ID: " . $new_file_obj->get_user_id() . "<br>";
            } else {
                echo "重新获取文件失败！<br>";
            }
        } else {
            echo "同步到数据库失败！<br>";
        }
        
        // 5. 清理测试数据
        echo "<h2>5. 清理测试数据</h2>";
        $delete_result = File_delete($file_obj);
        if ($delete_result) {
            echo "删除测试文件成功！<br>";
        } else {
            echo "删除测试文件失败！<br>";
        }
    } else {
        echo "创建文件映射失败！<br>";
    }
} catch (Exception $e) {
    echo "测试过程中发生错误: " . $e->getMessage() . "<br>";
}

echo "</body>";
echo "</html>";
