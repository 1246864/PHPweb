<?php
// 文件获取相关方法
// 对应数据表名：file_mapping
// 字段：id, path, size, type, name, time, user_id, status

// 引入预处理库
include_once __DIR__ . '/../../include/_PRE.php';

include_once __DIR__ . '/../../config/config.php';
include_once __DIR__ . '/../../include/conn.php';
include_once __DIR__ . '/function.php';

// 引入文件类
include_once __DIR__ . '/class/File.php';

// 引入用户类
include_once __DIR__ . '/class/User.php';

// 项目主目录
$MAIN_PATH = __DIR__ . '/../../';

/**
 * 在数据库查找第一个匹配的文件, 推荐用id查找
 * @param int|string|User $key 文件标识符
 * @return File|null 文件对象
 */
function File_get_File($key)
{
    global $conn, $config;
    try {
        if ($key instanceof User) {
            $key = $key->id;
        }
        $sql = "SELECT * FROM file_mapping WHERE id = ? or path = ? or name = ? or type = ? or status = ? and user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssss", $key, $key, $key, $key, $key, $key);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $file = $result->fetch_assoc();
            $file_obj = new File($file['id'], $file['path'], $file['type'], $file['name'], $file['size'], $file['time'], $file['user_id'], $file['status']);

            return $file_obj;
        } else {
            return null;
        }
    } catch (\Throwable $th) {    // 所有错误
        if ($config['debug']['use_debug']) {
            echo '错误：(File_get_File)' . $th->getMessage();
        }
        return null;
    }
}

/**
 * 在数据库查找所有匹配的文件
 * @param int|string|User $key 文件标识符
 * @return array<User>|null 文件对象数组
 */
function File_get_Files($key){
    global $conn, $config;
    try {
if ($key instanceof User) {
            $key = $key->id;
        }
        $sql = "SELECT * FROM file_mapping WHERE id = ? or path = ? or name = ? or type = ? or status = ? and user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssss", $key, $key, $key, $key, $key, $key);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $file_obj = new File($row['id'], $row['path'], $row['type'], $row['name'], $row['size'], $row['time'], $row['user_id'], $row['status']);
                $files[] = $file_obj;
            }
            return $files;
        } else {
            return null;
        }
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(File_get_Files)' . $th->getMessage();
        }
        return null;
    }
}

// 创建文件映射
function File_create($path){
    global $conn, $config, $MAIN_PATH;
    try {
        // 判断文件是否存在
        if (!file_exists($path)) {
            $path = $MAIN_PATH . $path;
            // 判断文件是否存在
            if (!file_exists($path)) {
                throw new Exception("文件不存在：{$path}");
            }
        }
        // 获取文件信息
        $file_info = pathinfo($path);
        $size = filesize($path);
        $type = isset($file_info['extension']) ? $file_info['extension'] : '';
        $name = $file_info['basename'];
        $time = filemtime($path);
        $status = 1;

        // 查找用户ID
        if (isset($_SESSION['user']['is_login']) && $_SESSION['user']['is_login']){
            $user_id = $_SESSION['user']['id'];
        } else {
            $user_id = 0;
        }

        $sql = "INSERT INTO file_mapping (path, size, type, name, time, user_id, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $path, $size, $type, $name, $time, $user_id, $status);
        $stmt->execute();
        return $conn->insert_id;
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(File_create)' . $th->getMessage();
        }
        return null;
    }
}

/**
 * 同步文件信息：把数据库中的文件信息同步到文件系统中
 * @param File $file 文件对象
 * @return bool 是否同步成功
 */
function File_update($file)
{
    global $config, $MAIN_PATH;
    try {
        $old_file = $file->get_old();
        $path = $old_file->get_path();
        // 根据$path找到对应文件,由项目主目录开始
        $full_path = $MAIN_PATH . $path;
        if (!file_exists($full_path)) {
            throw new Exception("文件不存在：{$full_path}");
        }
        if ($old_file && $file->equals($old_file)) {
            // 新旧文件对象完全一致，无需更新
            return true;
        }
        if ($file->time != $old_file->time) {
            // 更新文件上传时间
            touch($full_path, $file->time);
        }
        if ($file->status == 0) {
            // 删除文件
            unlink($full_path);
        }
        if ($file->path != $old_file->path) {
            // 更新文件路径
            rename($full_path, $MAIN_PATH . $file->path);
            $full_path = $MAIN_PATH . $file->path;
        }

        return true;
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(File_update)' . $th->getMessage();
        }
        return false;
    }
}
/**
 * 删除文件
 * @param File $file 文件对象
 * @return bool 是否删除成功
 */
function File_delete($file){
    global $config, $MAIN_PATH;
    try {
        $path = $file->get_path();
        // 根据$path找到对应文件,由项目主目录开始
        $full_path = $MAIN_PATH . $path;
        if (!file_exists($full_path)) {
            throw new Exception("文件不存在：{$full_path}");
        }
        // 删除文件
        unlink($full_path);
        return true;
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(File_delete)' . $th->getMessage();
        }
        return false;
    }
}



// ----------------------获取信息----------------------------

/**
 * 获取文件ID
 * @param File $file 文件对象
 * @return int|null 文件ID
 */
function File_get_id($file)
{
    global $config;
    try {
        return $file->get_id();
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(File_get_id)' . $th->getMessage();
        }
        return null;
    }
}

/**
 * 获取文件路径
 * @param File $file 文件对象
 * @return string|null 文件路径
 */
function File_get_path($file)
{
    global $config;
    try {
        return $file->get_path();
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(File_get_path)' . $th->getMessage();
        }
        return null;
    }
}

/**
 * 获取文件类型
 * @param File $file 文件对象
 * @return string|null 文件类型
 */
function File_get_type($file)
{
    global $config;
    try {
        return $file->get_type();
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(File_get_type)' . $th->getMessage();
        }
        return null;
    }
}

/**
 * 获取文件名
 * @param File $file 文件对象
 * @return string|null 文件名
 */
function File_get_name($file)
{
    global $config;
    try {
        return $file->get_name();
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(File_get_name)' . $th->getMessage();
        }
        return null;
    }
}

/**
 * 获取文件大小
 * @param File $file 文件对象
 * @return int|null 文件大小
 */
function File_get_size($file)
{
    global $config;
    try {
        return $file->get_size();
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(File_get_size)' . $th->getMessage();
        }
        return null;
    }
}

/**
 * 获取文件上传时间
 * @param File $file 文件对象
 * @return string|null 文件上传时间
 */
function File_get_time($file)
{
    global $config;
    try {
        return $file->get_time();
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(File_get_time)' . $th->getMessage();
        }
        return null;
    }
}

/**
 * 获取文件所属用户ID
 * @param File $file 文件对象
 * @return int|null 用户ID
 */
function File_get_user_id($file)
{
    global $config;
    try {
        return $file->get_user_id();
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(File_get_user_id)' . $th->getMessage();
        }
        return null;
    }
}

/**
 * 获取文件状态
 * @param File $file 文件对象
 * @return int|null 文件状态(0:正常, 1:删除)
 */
function File_get_status($file)
{
    global $config;
    try {
        return $file->get_status();
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(File_get_status)' . $th->getMessage();
        }
        return null;
    }
}

// ----------------------设置信息----------------------------

/**
 * 设置文件路径
 * @param File $file 文件对象
 * @param string $path 文件路径
 * @return bool 是否设置成功
 */
function File_set_path($file, $path)
{
    global $config;
    try {
        return $file->set_path($path);
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(File_set_path)' . $th->getMessage();
        }
        return false;
    }
}

/**
 * 设置文件类型
 * @param File $file 文件对象
 * @param string $type 文件类型
 * @return bool 是否设置成功
 */
function File_set_type($file, $type)
{
    global $config;
    try {
        return $file->set_type($type);
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(File_set_type)' . $th->getMessage();
        }
        return false;
    }
}

/**
 * 设置文件名
 * @param File $file 文件对象
 * @param string $name 文件名
 * @return bool 是否设置成功
 */
function File_set_name($file, $name)
{
    global $config;
    try {
        return $file->set_name($name);
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(File_set_name)' . $th->getMessage();
        }
        return false;
    }
}


/**
 * 设置文件上传时间
 * @param File $file 文件对象
 * @param string $time 文件上传时间
 * @return bool 是否设置成功
 */
function File_set_time($file, $time)
{
    global $config;
    try {
        return $file->set_time($time);
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(File_set_time)' . $th->getMessage();
        }
        return false;
    }
}

/**
 * 设置文件所属用户ID
 * @param File $file 文件对象
 * @param int $user_id 用户ID
 * @return bool 是否设置成功
 */
function File_set_user_id($file, $user_id)
{
    global $config;
    try {
        return $file->set_user_id($user_id);
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(File_set_user_id)' . $th->getMessage();
        }
        return false;
    }
}

/**
 * 设置文件状态
 * @param File $file 文件对象
 * @param int $status 文件状态(0:正常, 1:删除)
 * @return bool 是否设置成功
 */
function File_set_status($file, $status)
{
    global $config;
    try {
        return $file->set_status($status);
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(File_set_status)' . $th->getMessage();
        }
        return false;
    }
}
