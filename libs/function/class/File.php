<?php

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
    public $status;     // 文件状态(0:正常, 1:删除)
    private $__old;  // 给自己管理的副本，判断哪项有改变

    // 构造函数
    public function __construct($id = null, $path = null, $type = null, $name = null, $size = null, $time = null, $user_id = null, $status = null)
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

        // 初始化旧值副本(要以空值调用构造函数，避免无限递归)
        if (
            $this->id == null && $this->path == null && $this->type == null &&
            $this->name == null && $this->size == null && $this->time == null &&
            $this->user_id == null && $this->status == null
        ) {
            $this->__old = new File();
            $this->__old->id = $this->id;
            $this->__old->path = $this->path;
            $this->__old->type = $this->type;
            $this->__old->name = $this->name;
            $this->__old->size = $this->size;
            $this->__old->time = $this->time;
            $this->__old->user_id = $this->user_id;
            $this->__old->status = $this->status;
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
        global $config, $conn;
        try {
            // 更新对象属性
            $this->path = $path;
            // 更新数据库记录
            $sql = "UPDATE file_mapping SET path = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $path, $this->id);
            $stmt->execute();
            return $stmt->affected_rows > 0;
        } catch (\Throwable $th) {
            if ($config['debug']['use_debug']) {
                echo '错误：(File::set_path)' . $th->getMessage();
            }
            return false;
        }
    }

    /**
     * 设置文件类型
     * @param string $type 文件类型
     * @return bool 是否设置成功
     */
    public function set_type($type)
    {
        global $config, $conn;
        try {
            // 更新对象属性
            $this->type = $type;
            // 更新数据库记录
            $sql = "UPDATE file_mapping SET type = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $type, $this->id);
            $stmt->execute();
            return $stmt->affected_rows > 0;
        } catch (\Throwable $th) {
            if ($config['debug']['use_debug']) {
                echo '错误：(File::set_type)' . $th->getMessage();
            }
            return false;
        }
    }

    /**
     * 设置文件名
     * @param string $name 文件名
     * @return bool 是否设置成功
     */
    public function set_name($name)
    {
        global $config, $conn;
        try {
            // 更新对象属性
            $this->name = $name;
            // 更新数据库记录
            $sql = "UPDATE file_mapping SET name = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $name, $this->id);
            $stmt->execute();
            return $stmt->affected_rows > 0;
        } catch (\Throwable $th) {
            if ($config['debug']['use_debug']) {
                echo '错误：(File::set_name)' . $th->getMessage();
            }
            return false;
        }
    }

    /**
     * 设置文件上传时间
     * @param string $time 文件上传时间
     * @return bool 是否设置成功
     */
    public function set_time($time)
    {
        global $config, $conn;
        try {
            // 更新对象属性
            $this->time = $time;
            // 更新数据库记录
            $sql = "UPDATE file_mapping SET time = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $time, $this->id);
            $stmt->execute();
            return $stmt->affected_rows > 0;
        } catch (\Throwable $th) {
            if ($config['debug']['use_debug']) {
                echo '错误：(File::set_time)' . $th->getMessage();
            }
            return false;
        }
    }

    /**
     * 设置文件所属用户ID
     * @param int $user_id 用户ID
     * @return bool 是否设置成功
     */
    public function set_user_id($user_id)
    {
        global $config, $conn;
        try {
            // 更新对象属性
            $this->user_id = $user_id;
            // 更新数据库记录
            $sql = "UPDATE file_mapping SET user_id = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $this->id);
            $stmt->execute();
            return $stmt->affected_rows > 0;
        } catch (\Throwable $th) {
            if ($config['debug']['use_debug']) {
                echo '错误：(File::set_user_id)' . $th->getMessage();
            }
            return false;
        }
    }

    /**
     * 设置文件状态
     * @param int $status 文件状态(0:正常, 1:删除)
     * @return bool 是否设置成功
     */
    public function set_status($status)
    {
        global $config, $conn;
        try {
            // 更新对象属性
            $this->status = $status;
            // 更新数据库记录
            $sql = "UPDATE file_mapping SET status = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $status, $this->id);
            $stmt->execute();
            return $stmt->affected_rows > 0;
        } catch (\Throwable $th) {
            if ($config['debug']['use_debug']) {
                echo '错误：(File::set_status)' . $th->getMessage();
            }
            return false;
        }
    }
}
