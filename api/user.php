<?php
// 用户相关函数库

// 引入预处理库
include_once __DIR__ . '/../include/_PRE.php';

include_once __DIR__ . '/../include/conn.php';
include_once __DIR__ . '/function.php';

session_start();

// 规定用户的SESSION结构
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = array(
        'is_login' => false,
        'name' => '游客',
        'flag' => 'user',
    );
}

class User
{
    public $id;
    public $username;
    public $email;
    public $role;
    public $image;
    public function __construct($id, $username, $email, $role, $image)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->role = $role;
        $this->image = $image;
    }
}

/**
 * 用户登录，在代码里调用
 * @param string $username 用户输入的用户名
 * @param string $password 用户输入的明文密码
 * @return bool 登录成功返回true，否则false
 */
function login($username, $password)    // 简便api
{
    return User_login($username, $password);
}
/**
 * 用户注册，在代码里调用
 * @param string $username 用户输入的用户名
 * @param string $password 用户输入的密码
 * @param string $email 用户输入的邮箱
 * 
 * @return bool 注册成功返回true，否则false
 */
function register($username, $password, $email) // 简便api
{
    return User_register($username, $password, $email);
}


/**
 * 用户登录，在代码里调用
 * @param string $username 用户输入的用户名
 * @param string $password 用户输入的明文密码
 * @return bool|User 登录成功返回用户对象，否则false
 */
function User_login($username, $password)
{
    global $conn;
    // 检查数据库连接是否有效
    if (!$conn || !is_object($conn)) {
        Error_500('数据库连接失败，无法执行登录操作。');
        return false;
    }

    // 使用预处理语句防止SQL注入
    $stmt = $conn->prepare("SELECT * from user where username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $rs = $stmt->get_result();
    $row = $rs->fetch_assoc();

    if (!$row) {
        $stmt = $conn->prepare("SELECT * from user where email = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $rs = $stmt->get_result();
        $row = $rs->fetch_assoc();

        if (!$row) {
            $row['password'] = '';
        }
    }
    $DB_password = $row['password'];
    echo '$DB_password:' . $DB_password . '<br/>';

    $flag = verifyPassword($password, $DB_password);

    if ($flag) {
        $_SESSION['user'] = array(
            'is_login' => true,
            'name' => $row['username'],
            'flag' => $row['role'],
        );
        $stmt->close();
        $USER = new User($row['id'], $row['username'], $row['email'], $row['role'], $row['image']);
    }
    $stmt->close();
    return false;
}

/**
 * 用户注册，在代码里调用
 * @param string $username 用户输入的用户名
 * @param string $password 用户输入的密码
 * @param string $email 用户输入的邮箱
 * 
 * @return bool|User 注册成功返回用户对象，否则false
 */
function User_register($username, $password, $email)
{
    global $conn;
    if (User_check_name($username)) {
        return false;
    }
    if (User_check_email($email)) {
        return false;
    }

    $conn->set_charset("utf8");
    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);
    $email = $conn->real_escape_string($email);

    $hash_password = encryptPassword($password);

    echo "$username, $hash_password, $email";

    // 3. 拼接SQL并执行（绕过预处理绑定）
    $sql = "INSERT INTO `user`(`username`, `password`, `email`) 
            VALUES ('$username', '$hash_password', '$email')";
    $flag = $conn->query($sql);

    return $flag ? new User($conn->insert_id, $username, $email, 'user', '') : false; // 注册成功返回用户对象
}

/**
 * 检查用户名是否存在
 * @param string $username 用户名
 * @return bool 用户名存在返回true，否则false
 */
function User_check_name($username)
{
    global $conn;
    $username = $conn->real_escape_string($username);
    $sql = "SELECT * FROM `user` WHERE `username` = '$username'";
    $flag = $conn->query($sql);
    return $flag->num_rows > 0;
}

/**
 * 检查邮箱是否存在
 * @param string $email 邮箱
 * @return bool 邮箱存在返回true，否则false
 */
function User_check_email($email)
{
    global $conn;
    $email = $conn->real_escape_string($email);
    $sql = "SELECT * FROM `user` WHERE `email` = '$email'";
    $flag = $conn->query($sql);
    return $flag->num_rows > 0;
}

/**
 * 获取用户信息
 * @param string $key 用户名或邮箱或idid
 * @return bool|User 用户信息对象（包含用户名、密码哈希、邮箱等）
 */
function User_get_user($key)
{
    global $conn;
    $key = $conn->real_escape_string($key);
    $sql = "SELECT * FROM `user` WHERE `username` = '$key' OR `email` = '$key' OR `id` = '$key'";
    $flag = $conn->query($sql);
    $row = $flag->fetch_assoc();
    if (!$row) {
        return false;
    }
    $user = new User($row['id'], $row['username'], $row['email'], $row['role'], $row['image']);
    return $user;
}

/**
 * 修改用户名
 * @param User $user 用户对象
 * @param string $new_username 新用户名
 * @return bool|User 修改成功返回新用户对象，否则false
 */
function User_change_name($user, $new_username)
{
    global $conn;
    $id = $conn->real_escape_string($user->id);
    $new_username = $conn->real_escape_string($new_username);
    if (User_check_name($new_username)) {
        return false;
    } else {
        $sql = "UPDATE `user` SET `username` = '$new_username' WHERE `id` = '$id'";
        $flag = $conn->query($sql);
        if ($flag) {
            $user = new User($user->id, $new_username, $user->email, $user->role, $user->image);
            return $user;
        } else {
            return false;
        }
    }
}

/**
 * 修改邮箱
 * @param User $user 用户对象
 * @param string $new_email 新邮箱
 * @return bool|User 修改成功返回新用户对象，否则false
 */
function User_change_email($user, $new_email)
{
    global $conn;
    $id = $conn->real_escape_string($user->id);
    $new_email = $conn->real_escape_string($new_email);
    if (User_check_email($new_email)) {
        return false;
    } else {
        $sql = "UPDATE `user` SET `email` = '$new_email' WHERE `id` = '$id'";
        $flag = $conn->query($sql);
        if ($flag) {
            $user = new User($user->id, $user->username, $new_email, $user->role, $user->image);
            return $user;
        } else {
            return false;
        }
    }
}

/**
 * 将用户权限修改为用户
 * @param User $user 用户对象
 * @return bool|User 修改成功返回新用户对象，否则false
 */
function User_to_user($user)
{
    global $conn;
    $id = $conn->real_escape_string($user->id);
    $sql = "UPDATE `user` SET `role` = 'user' WHERE `id` = '$id'";
    $flag = $conn->query($sql);
    if ($flag) {
        $user = new User($user->id, $user->username, $user->email, 'user', $user->image);
        return $user;
    } else {
        return false;
    }
}

/**
 * 将用户权限修改为作者
 * @param User $user 用户对象
 * @return bool|User 修改成功返回新用户对象，否则false
 */
function User_to_writer($user)
{
    global $conn;
    $id = $conn->real_escape_string($user->id);
    $sql = "UPDATE `user` SET `role` = 'writer' WHERE `id` = '$id'";
    $flag = $conn->query($sql);
    if ($flag) {
        $user = new User($user->id, $user->username, $user->email, 'writer', $user->image);
        return $user;
    } else {
        return false;
    }
}

/**
 * 将用户权限修改为管理员
 * @param User $user 用户对象
 * @return bool|User 修改成功返回新用户对象，否则false
 */
function User_to_admin($user)
{
    global $conn;
    $id = $conn->real_escape_string($user->id);
    $sql = "UPDATE `user` SET `role` = 'admin' WHERE `id` = '$id'";
    $flag = $conn->query($sql);
    if ($flag) {
        $user = new User($user->id, $user->username, $user->email, 'admin', $user->image);
        return $user;
    } else {
        return false;
    }
}

/**
 * 生成密码哈希（兼容 PHP 5.3+，无任何扩展依赖）
 * @param string $password 明文密码
 * @return string 加密后的哈希字符串
 * @throws RuntimeException 哈希生成失败时抛出异常
 */
function encryptPassword($password)
{
    // 过滤空密码（避免空值哈希导致的异常）
    if (empty($password) || !is_string($password)) {
        throw new RuntimeException('密码不能为空且必须为字符串类型');
    }

    // 1. 版本判断：优先使用官方 password_hash（PHP 5.5+）
    if (function_exists('password_hash')) {
        $algo = defined('PASSWORD_DEFAULT') ? PASSWORD_DEFAULT : PASSWORD_BCRYPT;
        $options = ['cost' => 12];
        $hash = password_hash($password, $algo, $options);
    } else {
        // 2. 兼容 PHP < 5.5：纯原生实现 bcrypt 哈希（无任何扩展依赖）
        $cost = 12;
        $saltPrefix = '$2a$' . str_pad($cost, 2, '0', STR_PAD_LEFT) . '$';

        // 纯原生生成安全随机盐值（仅用 PHP 内置函数，无扩展依赖）
        $randomStr = '';
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789./'; // bcrypt允许的字符集
        $charsLen = strlen($chars);
        // 生成 22 位随机盐值（bcrypt 要求）
        for ($i = 0; $i < 22; $i++) {
            // 混合多种随机源：mt_rand + 时间戳 + 进程ID + 内存地址
            $seed = mt_rand() + microtime(true) * 1000000 + getmypid() + crc32(uniqid('', true));
            mt_srand($seed);
            $randomStr .= $chars[mt_rand(0, $charsLen - 1)];
        }
        $salt = $saltPrefix . $randomStr;

        // 生成 crypt 哈希（bcrypt 算法）
        $hash = crypt($password, $salt);

        // 验证哈希生成是否有效（bcrypt 哈希长度固定为 60 位）
        if (strlen($hash) !== 60) {
            $hash = false;
        }
    }

    // 通用验证：哈希生成是否成功
    if ($hash === false || empty($hash)) {
        throw new RuntimeException('密码哈希生成失败');
    }

    return $hash;
}

/**
 * 验证密码是否匹配（兼容 PHP 5.3+，无任何扩展依赖）
 * @param string $plainPassword 用户输入的明文密码
 * @param string $storedHash 数据库中存储的密码哈希
 * @return bool 匹配返回true，否则false
 */
function verifyPassword($plainPassword, $storedHash)
{
    // 前置校验：空值/无效哈希直接返回false
    if (empty($storedHash) || !is_string($storedHash) || strlen($storedHash) !== 60) {
        return false;
    }
    if (empty($plainPassword) || !is_string($plainPassword)) {
        return false;
    }

    // 1. 版本判断：优先使用官方 password_verify（PHP 5.5+）
    if (function_exists('password_verify')) {
        return password_verify($plainPassword, $storedHash);
    } else {
        // 2. 兼容 PHP < 5.5：纯原生验证
        $hash = crypt($plainPassword, $storedHash);
        // 安全比较（防时序攻击，纯原生实现）
        if (strlen($hash) !== strlen($storedHash)) {
            return false;
        }
        $result = 0;
        for ($i = 0; $i < strlen($storedHash); $i++) {
            $result |= ord($storedHash[$i]) ^ ord($hash[$i]);
        }
        return $result === 0;
    }
}
