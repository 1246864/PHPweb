<?php
// 用户相关函数库

// 对应数据表名：user
// 字段：id, username, password, role, email, image

// 引入预处理库
include_once __DIR__ . '/../../include/_PRE.php';

include_once __DIR__ . '/../../config/config.php';
include_once __DIR__ . '/../../include/conn.php';
include_once __DIR__ . '/function.php';

// 引入User类
include_once __DIR__ . '/class/User.php';


session_start();

// 规定用户的SESSION结构
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = array(
        'is_login' => false,
        'name' => '游客',
        'flag' => 'user',
        'id' => 0,
    );
}




/**
 * 用户登录，在代码里调用
 * @param string $username 用户输入的用户名
 * @param string $password 用户输入的明文密码
 * @return bool|User 登录成功返回用户对象，否则false
 */
function User_login($username, $password, $cookie = false)
{
    global $conn, $config;

    try {

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

        $flag = verifyPassword($password, $DB_password);

        if ($flag) {
            $_SESSION['user'] = array(
                'is_login' => true,
                'name' => $row['username'],
                'flag' => $row['role'],
                'id' => $row['id'],
            );
            $stmt->close();

            if ($cookie) {
                $day7 = 7 * 24 * 60 * 60;
                setcookie('login_user', $row['username'], time() + $day7, '/');
                setcookie('login_password', $password, time() + $day7, '/');
            }

            return new User($row['id'], $row['username'], $row['email'], $row['role'], $row['image']);
        }
        $stmt->close();
        return false;
    } catch (\Throwable $e) {
        if ($config['debug']['use_debug']) {
            echo '错误：(User_login/login) ' . $e->getMessage();
        }
        return false;
    }
}

/**
 * 自动登录，在代码里调用
 * @return bool|User 登录成功返回用户对象，否则false
 */
function User_auto_login()
{
    global $config;
    try {
        if (isset($_COOKIE['login_user']) && isset($_COOKIE['login_password'])) {
            $username = $_COOKIE['login_user'];
            $password = $_COOKIE['login_password'];
            return User_login($username, $password, true);
        }
        return false;
    } catch (\Throwable $e) {
        if ($config['debug']['use_debug']) {
            echo '错误：(User_auto_login) ' . $e->getMessage();
        }
        return false;
    }
}

/**
 * 用户注销，在代码里调用
 * @return bool 注销成功返回true，否则false
 */
function User_logout()
{
    global $config;
    try {
        $_SESSION['user'] = array(
            'is_login' => false,
            'name' => '游客',
            'flag' => 'user',
            'id' => 0,
        );
        setcookie('login_user', '', time() - 1, '/');
        setcookie('login_password', '', time() - 1, '/');
        return true;
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(User_logout) ' . $th->getMessage();
        }
        return false;
    }
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

    global $conn, $config;
    try {
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
        if ($flag) {
            return new User($conn->insert_id, $username, $email, 'user', ''); // 注册成功返回用户对象
        } else {
            return false;
        }
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(User_register) ' . $th->getMessage();
        }
        return false;
    }
}
/**
 * 删除用户，在代码里调用
 * @param User $user 用户对象
 * @return bool 删除成功返回true，否则false
 */
function User_del_user($user)
{
    global $conn, $config;
    try {
        $user_id = $conn->real_escape_string($user->id);
        $sql = "DELETE FROM `user` WHERE `id` = '$user_id'";
        $flag = $conn->query($sql);
        return $flag;
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(User_del_user) ' . $th->getMessage();
        }
        return false;
    }
}
/**
 * 检查用户名是否存在，在代码里调用或异步调用
 * @param string $username 用户名
 * @return bool 用户名存在返回true，否则false
 */
function User_check_name($username)
{
    global $conn, $config;
    try {
        $username = $conn->real_escape_string($username);
        $sql = "SELECT * FROM `user` WHERE `username` = '$username'";
        $flag = $conn->query($sql);
        return $flag->num_rows > 0;
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(User_check_name) ' . $th->getMessage();
        }
        return false;
    }
}

/**
 * 检查邮箱是否存在
 * @param string $email 邮箱
 * @return bool 邮箱存在返回true，否则false
 */
function User_check_email($email)
{
    global $conn, $config;
    try {
        $email = $conn->real_escape_string($email);
        $sql = "SELECT * FROM `user` WHERE `email` = '$email'";
        $flag = $conn->query($sql);
        return $flag->num_rows > 0;
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(User_check_email) ' . $th->getMessage();
        }
        return false;
    }
}

/**
 * 获取用户信息
 * @param string|null $key 用户名或邮箱或id(当为null时,默认获取当前会话中的用户信息)
 * @return null|User 用户信息对象
 */
function User_get_user($key=null)
{
    global $conn, $config;
    try {
        if ($key === null) {
            // 直接通过session构造
            if (!isset($_SESSION['user']) || !isset($_SESSION['user']['is_login'])) {
                return null;
            }
            $key = $_SESSION['user']['id'];
        }
        $key = $conn->real_escape_string($key);
        $sql = "SELECT * FROM `user` WHERE `username` = '$key' OR `email` = '$key' OR `id` = '$key'";
        $flag = $conn->query($sql);
        $row = $flag->fetch_assoc();
        if (!$row) {
            return null;
        }
        $user = new User($row['id'], $row['username'], $row['email'], $row['role'], $row['image']);
        return $user;
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(User_get_user) ' . $th->getMessage();
        }
        return null;
    }
}

/**
 * 修改用户名
 * @param User $user 用户对象
 * @param string $new_username 新用户名
 * @return bool|User 修改成功返回新用户对象，否则false
 */
function User_set_name($user, $new_username)
{
    global $conn, $config;
    try {
        if (User_check_name($new_username)) {
            return false;
        } else {
            return $user->set_username($new_username);
        }
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(User_set_name) ' . $th->getMessage();
        }
        return false;
    }
}

/**
 * 修改密码
 * @param User $user 用户对象
 * @param string $new_password 新密码
 * @return bool|User 修改成功返回新用户对象，否则false
 */
function User_set_password($user, $new_password)
{
    global $conn, $config;
    try {
        $id = $conn->real_escape_string($user->id);
        $new_password = $conn->real_escape_string($new_password);
        $hash_password = encryptPassword($new_password);
        $sql = "UPDATE `user` SET `password` = '$hash_password' WHERE `id` = '$id'";
        $flag = $conn->query($sql);
        return $flag;
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(User_set_password) ' . $th->getMessage();
        }
        return false;
    }
}

/**
 * 修改邮箱
 * @param User $user 用户对象
 * @param string $new_email 新邮箱
 * @return bool|User 修改成功返回新用户对象，否则false
 */
function User_set_email($user, $new_email)
{
    global $conn, $config;
    try {
        if (User_check_email($new_email)) {
            return false;
        } else {
            return $user->set_email($new_email);
        }
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(User_set_email) ' . $th->getMessage();
        }
        return false;
    }
}

/**
 * 获取用户名
 * @param User $user 用户对象
 * @return string 用户名
 */
function User_get_name($user)
{
    global $conn, $config;
    try {
        return $user->get_username();
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(User_get_name) ' . $th->getMessage();
        }
        return null;
    }
}
/**
 * 获取邮箱
 * @param User $user 用户对象
 * @return string 邮箱
 */
function User_get_email($user)
{
    global $conn, $config;
    try {
        return $user->get_email();
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(User_get_email) ' . $th->getMessage();
        }
        return null;
    }
}
/**
 * 获取用户角色
 * @param User $user 用户对象
 * @return string 用户角色
 */
function User_get_role($user)
{
    global $conn, $config;
    try {
        return $user->get_role();
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(User_get_role) ' . $th->getMessage();
        }
        return 'user';
    }
}

/**
 * 将用户权限修改为用户
 * @param User $user 用户对象
 * @return bool|User 修改成功返回新用户对象，否则false
 */
function User_to_user($user)
{
    global $conn, $config;
    try {
        $id = $conn->real_escape_string($user->id);
        $sql = "UPDATE `user` SET `role` = 'user' WHERE `id` = '$id'";
        $flag = $conn->query($sql);
        if ($flag) {
            $user->role = 'user';
            return true;
        } else {
            return false;
        }
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(User_to_user) ' . $th->getMessage();
        }
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
    global $conn, $config;
    try {
        $id = $conn->real_escape_string($user->id);
        $sql = "UPDATE `user` SET `role` = 'writer' WHERE `id` = '$id'";
        $flag = $conn->query($sql);
        if ($flag) {
            $user->role = 'writer';
            return true;
        } else {
            return false;
        }
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(User_to_writer) ' . $th->getMessage();
        }
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
    global $conn, $config;
    try {
        $id = $conn->real_escape_string($user->id);
        $sql = "UPDATE `user` SET `role` = 'admin' WHERE `id` = '$id'";
        $flag = $conn->query($sql);
        if ($flag) {
            $user->role = 'admin';
            return true;
        } else {
            return false;
        }
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(User_to_admin) ' . $th->getMessage();
        }
        return false;
    }
}

/**
 * 获取所有用户信息
 * @return bool|array<User> 用户信息对象数组（包含用户名、密码哈希、邮箱等）
 */
function User_get_all_user()
{
    global $conn, $config;
    try {
        $sql = "SELECT * FROM `user`";
        $result = $conn->query($sql);
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        if (!$rows) {
            return false;
        }
        $users = [];
        foreach ($rows as $row) {
            $users[] = new User($row['id'], $row['username'], $row['email'], $row['role'], $row['image']);
        }
        return $users;
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(User_get_all_user) ' . $th->getMessage();
        }
        return false;
    }
}

// 以下为辅助函数，用于密码验证（兼容 PHP 5.3+，无任何扩展依赖）-------------------------------------------
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
