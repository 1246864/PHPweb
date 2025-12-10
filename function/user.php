<?php
// 引入预处理库
include_once __DIR__ . '/../include/_PRE.php';

include_once __DIR__ . '/../include/conn.php';
include_once __DIR__ . '/function.php';

session_start();

// 规定用户的SESSION结构
$_SESSION['user']=array(
        'is_login' => false,
        'name' => '游客',
        'flag' => 'user',
);

/**
 * 用户登录，在代码里调用 (有未排查的BUG!!!!!!!!!!!)
 * @param string $username 用户输入的用户名
 * @param string $password 用户输入的明文密码
 * @return bool 登录成功返回true，否则false
 */
function login($username, $password){
    global $conn;
    
    $sql = "SELECT * from user where username = $username";
    
    $rs = $conn->query($sql);
    $row = $rs->fetch_assoc();
    if(!$row){
        $sql = "SELECT * from user where email = $username";
        $rs = $conn->query($sql);
        $row = $rs->fetch_assoc();

        if(!$row){
            $row['password'] = '';
        }
    }
    $DB_password = $row['password'];
    $flag = verifyPassword($password, $DB_password);

    if($flag){
        $_SESSION['user'] = array(
            'is_login' => true,
            'name' => $rs['username'],
            'flag' => $rs['flag'],
        );
        return true;
    }
    return false;
}

