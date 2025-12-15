<?php

// 引入预处理库
include_once __DIR__ . '/../../include/_PRE.php';

// 引入配置文件
include_once __DIR__ . '/../../config/config.php';

// 引入数据库连接文件
include_once __DIR__ . '/../../include/conn.php';

// 引入全局函数库
include_once __DIR__ . '/../function.php';

// 定义文件类
class File
{
    public $id;         // 文件ID
    public $path;       // 文件路径
    public $type;       // 文件类型
    public $name;       // 真实文件名
    public $size;       // 文件大小
    public $time;       // 文件上传时间
    public $user_id;    // 用户ID
    public $status;     // 文件状态(1:正常, 0:删除)
    private $__old;  // 给自己管理的副本，判断哪项有改变

    // 构造函数
    public function __construct($id = null, $path = null, $type = null, $name = null, $size = null, $time = null, $user_id = null, $status = null, $is_old = false)
    {
        // 初始化属性
        $this->id = $id;
        $this->path = $path;
        $this->type = $type;
        $this->name = $name;
        $this->size = $size;
        $this->time = $time;
        $this->user_id = $user_id;
        $this->status = $status;

        // 初始化旧值副本，避免无限递归
        if (!$is_old) {
            $this->__old = new File($id, $path, $type, $name, $size, $time, $user_id, $status, true);
        }
    }

    function equals($file){
        if ($this->id == $file->id && $this->path == $file->path && $this->type == $file->type &&
            $this->name == $file->name && $this->size == $file->size && $this->time == $file->time &&
            $this->user_id == $file->user_id && $this->status == $file->status) {
            return true;
        }
        return false;
    }
    // ----------------------获取信息----------------------------
    /**
     * 获取文件ID
     * @return int|null 文件ID
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * 获取文件路径
     * @return string|null 文件路径
     */
    public function get_path()
    {
        return $this->path;
    }

    /**
     * 获取文件类型
     * @return string|null 文件类型
     */
    public function get_type()
    {

        return $this->type;
    }

    /**
     * 获取文件名
     * @return string|null 文件名
     */
    public function get_name()
    {
        return $this->name;
    }

    /**
     * 获取文件大小
     * @return int|null 文件大小
     */
    public function get_size()
    {
        return $this->size;
    }

    /**
     * 获取文件上传时间
     * @return string|null 文件上传时间
     */
    public function get_time()
    {
        return $this->time;
    }

    /**
     * 获取文件所属用户ID
     * @return int|null 用户ID
     */
    public function get_user_id()
    {
        return $this->user_id;
    }

    /**
     * 获取文件状态
     * @return int|null 文件状态(0:正常, 1:删除)
     */
    public function get_status()
    {
        return $this->status;
    }
    /**
     * 获取未经修改的文件对象
     * @return File 旧文件对象
     */
    public function get_old()
    {
        return $this->__old;
    }

    // ----------------------设置信息----------------------------

    /**
     * 设置文件路径
     * @param string $path 文件路径
     * @return bool 是否设置成功
     */
    public function set_path($path)
    {
        $this->path = $path;
        return true;
    }

    /**
     * 设置文件类型
     * @param string $type 文件类型
     * @return bool 是否设置成功
     */
    public function set_type($type)
    {
        $this->type = $type;
        return true;
    }

    /**
     * 设置文件名
     * @param string $name 文件名
     * @return bool 是否设置成功
     */
    public function set_name($name)
    {
        $this->name = $name;
        return true;
    }

    /**
     * 设置文件上传时间
     * @param string $time 文件上传时间
     * @return bool 是否设置成功
     */
    public function set_time($time)
    {
        $this->time = $time;
        return true;
    }

    /**
     * 设置文件所属用户ID
     * @param int|User $user 用户ID或User对象
     * @return bool 是否设置成功
     */
    public function set_user_id($user)
    {
        if (is_object($user) && get_class($user) == 'User') {
            $this->user_id = $user->get_id();
        } else {
            $this->user_id = $user;
        }
        return true;
    }

    /**
     * 同步文件信息到数据库
     * 收集所有修改的字段并统一更新到数据库
     * 同时更新真实文件的路径、时间和状态
     * @return bool 是否同步成功
     */
    public function sync()
    {
        global $config, $conn;
        try {
            // 检查文件ID是否存在
            if (empty($this->id)) {
                if ($config['debug']['use_debug']) {
                    echo '错误：(File::sync)文件ID不存在';
                }
                return false;
            }

            // 获取旧文件对象
            $old_file = $this->get_old();
            if (!$old_file) {
                if ($config['debug']['use_debug']) {
                    throw new Exception('错误：(File::sync)旧文件对象不存在');
                }
                return false;
            }

            // 真实文件路径处理
            $path = $old_file->get_path();
            
            // 检查路径是否已经是绝对路径
            if (strpos($path, ':/') !== false || strpos($path, ':\\') !== false) {
                // 已经是绝对路径，直接使用
                $full_path = $path;
            } else {
                // 相对路径，拼接MAIN_PATH
                $full_path = MAIN_PATH . $path;
                $full_path = realpath($full_path); // 规范化路径
            }
            
            // 检查文件是否存在
            if (!file_exists($full_path)) {
                if ($this->status != 0) { // 如果不是删除操作，才抛出错误
                    throw new Exception("文件不存在：{$full_path}");
                }
            }

            // 检查新旧文件是否完全一致
            if ($this->equals($old_file)) {
                // 新旧文件对象完全一致，无需更新
                return true;
            }

            // 真实文件操作 - 移除修改真实文件时间的代码，time字段仅作为记录时间
            
            if ($this->status == 0) {
                // 删除文件 (0:删除)
                if (file_exists($full_path)) {
                    unlink($full_path);
                }
            }
            
            $path_changed = false;
            if ($this->path != $old_file->path) {
                // 确定新路径
                if (strpos($this->path, ':/') !== false || strpos($this->path, ':\\') !== false) {
                    // 新路径是绝对路径
                    $new_full_path = realpath($this->path);
                } else {
                    // 新路径是相对路径
                    $new_full_path = MAIN_PATH . $this->path;
                    $new_full_path = realpath($new_full_path); // 规范化路径
                }
                
                // 更新文件路径
                rename($full_path, $new_full_path);
                $full_path = $new_full_path;
                // 将绝对路径转换为相对路径
                $full_path = realpath($new_full_path);   // 确保路径是绝对路径
                $this->path = str_replace(MAIN_PATH, '', $full_path);
                // 去掉开头的斜杠
                $this->path = ltrim($this->path, '/');
                
                $path_changed = true;
            }

            // 构建更新的字段和参数
            $update_fields = [];
            $params = [];
            $param_types = '';

            // 检查哪些字段需要更新
            if ($path_changed || isset($this->path)) {
                $update_fields[] = 'path = ?';
                $params[] = &$this->path;
                $param_types .= 's';
            }
            if (isset($this->type)) {
                $update_fields[] = 'type = ?';
                $params[] = &$this->type;
                $param_types .= 's';
            }
            if (isset($this->name)) {
                $update_fields[] = 'name = ?';
                $params[] = &$this->name;
                $param_types .= 's';
            }
            if (isset($this->size)) {
                $update_fields[] = 'size = ?';
                $params[] = &$this->size;
                $param_types .= 'i';
            }
            if (isset($this->time)) {
                $update_fields[] = 'time = ?';
                $params[] = &$this->time;
                $param_types .= 's';
            }
            if (isset($this->user_id)) {
                $update_fields[] = 'user_id = ?';
                $params[] = &$this->user_id;
                $param_types .= 'i';
            }
            if (isset($this->status)) {
                $update_fields[] = 'status = ?';
                $params[] = &$this->status;
                $param_types .= 'i';
            }

            // 如果没有字段需要更新，直接返回成功
            if (empty($update_fields)) {
                return true;
            }

            // 添加文件ID作为WHERE条件
            $params[] = &$this->id;
            $param_types .= 'i';

            // 构建SQL语句
            $sql = "UPDATE file_mapping SET " . implode(', ', $update_fields) . " WHERE id = ?";

            // 执行数据库更新
            $stmt = $conn->prepare($sql);
            
            // 使用引用传递参数
            switch(count($params)) {
                case 1: $stmt->bind_param($param_types, $params[0]); break;
                case 2: $stmt->bind_param($param_types, $params[0], $params[1]); break;
                case 3: $stmt->bind_param($param_types, $params[0], $params[1], $params[2]); break;
                case 4: $stmt->bind_param($param_types, $params[0], $params[1], $params[2], $params[3]); break;
                case 5: $stmt->bind_param($param_types, $params[0], $params[1], $params[2], $params[3], $params[4]); break;
                case 6: $stmt->bind_param($param_types, $params[0], $params[1], $params[2], $params[3], $params[4], $params[5]); break;
                case 7: $stmt->bind_param($param_types, $params[0], $params[1], $params[2], $params[3], $params[4], $params[5], $params[6]); break;
                case 8: $stmt->bind_param($param_types, $params[0], $params[1], $params[2], $params[3], $params[4], $params[5], $params[6], $params[7]); break;
            }
            
            $stmt->execute();
            return $stmt->affected_rows > 0;
        } catch (\Throwable $th) {
            if ($config['debug']['use_debug']) {
                echo '错误：(File::sync)' . $th->getMessage();
            }
            return false;
        }
    }
}
