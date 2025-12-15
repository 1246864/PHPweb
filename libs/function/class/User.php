<?php
// 引入预处理库
include_once __DIR__ . '/../../include/_PRE.php';

// 引入配置文件
include_once __DIR__ . '/../../config/config.php';

// 引入数据库连接文件
include_once __DIR__ . '/../../include/conn.php';

// 引入全局函数库
include_once __DIR__ . '/../function.php';

// 定义用户类
class User
{
    public $id;         // 用户ID
    public $username;   // 用户名
    public $email;      // 邮箱
    public $role;       // 用户角色
    public $image;      // 用户头像
    private $__old;     // 用于记录旧值的副本

    // 构造函数
    public function __construct($id, $username, $email, $role, $image)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->role = $role;
        $this->image = $image;
    }

    // ----------------------获取信息----------------------------
    /**
     * 获取用户ID
     * @return int|null 用户ID
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * 获取用户名
     * @return string|null 用户名
     */
    public function get_username()
    {
        return $this->username;
    }

    /**
     * 获取用户邮箱
     * @return string|null 邮箱
     */
    public function get_email()
    {
        return $this->email;
    }

    /**
     * 获取用户角色
     * @return string|null 用户角色
     */
    public function get_role()
    {
        return $this->role;
    }

    /**
     * 获取用户头像
     * @return string|null 用户头像
     */
    public function get_image()
    {
        return $this->image;
    }

    /**
     * 获取未经修改的用户对象
     * @return User 旧用户对象
     */
    public function get_old()
    {
        return $this->__old;
    }

    // ----------------------设置信息----------------------------

    /**
     * 设置用户名
     * @param string $username 用户名
     * @return bool 是否设置成功
     */
    public function set_username($username)
    {
        global $config, $conn;
        // 更新对象属性
        $this->username = $username;
        // 更新数据库记录
        $sql = "UPDATE `user` SET `username` = ? WHERE `id` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $username, $this->id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    /**
     * 设置用户邮箱
     * @param string $email 邮箱
     * @return bool 是否设置成功
     */
    public function set_email($email)
    {
        global $config, $conn;
        // 更新对象属性
        $this->email = $email;
        // 更新数据库记录
        $sql = "UPDATE `user` SET `email` = ? WHERE `id` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $email, $this->id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    /**
     * 设置用户角色
     * @param string $role 用户角色
     * @return bool 是否设置成功
     */
    public function set_role($role)
    {
        global $config, $conn;
        // 更新对象属性
        $this->role = $role;
        // 更新数据库记录
        $sql = "UPDATE `user` SET `role` = ? WHERE `id` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $role, $this->id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    /**
     * 设置用户头像
     * @param string $image 用户头像
     * @return bool 是否设置成功
     */
    public function set_image($image)
    {
        global $config, $conn;
        // 更新对象属性
        $this->image = $image;
        // 更新数据库记录
        $sql = "UPDATE `user` SET `image` = ? WHERE `id` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $image, $this->id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
}
