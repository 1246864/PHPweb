<?php

/**
 * ____________________________________________________________________________________________
 * 
 *                                  *** 用户管理头文件 ***
 * 
 *                          该文件包含用户管理相关的函数和常量定义
 *                          包括用户注册、登录、注销、查询等操作
 *                          如需使用用户管理相关函数,请先引入该文件
 *                          
 *                          除了简便api,其他相关函数都以User_开头
 * 
 * -----------------------------------------------------------------------------------
 * 
 *                                     *** 注意事项 ***
 * 
 *                  该库里所有的获取信息类、信息修改类、用户创建类的函数，
 *                  都会返回User类的实例对象。相关信息已经过封装，如需获取具体信息，
 *                  请调用该库的相关方法获取，不建议直接访问User类的属性。
 * 
 * +---------------------------------------函数定义------------------------------------
 * |                    +=============================================+
 * |                    |         这里是对用户管理相关函数的简单定义        |
 * |                    |      如需详细了解每个函数的参数和返回值,          |
 * |                    |            请参考文档，或相关IDE的自动提示       |
 * |                    +=============================================+
 * |
 * |
 * |--------- 登录 ----->
 * |    User_login($username, $password, $cookie = false)   -------------- 用户登录
 * |    login($username, $password, $cookie = false)        -------------- 用户登录(简便api)
 * |    User_auto_login()                                   -------------- 尝试自动登录
 * |
 * |    注: 参数$cookie为true时,可以配合User_auto_login()
 * |        使用,实现自动登录的操作
 * |
 * |--------- 注销 ----->
 * |
 * |    User_logout()     ------------------------------------------------ 用户注销
 * |    logout()          ------------------------------------------------ 用户注销(简便api)
 * |
 * |    注: 当调用了User_logout()函数时,会清除当前会话中的用户信息,
 * |        并删除自动登录的cookie,下次的自动登录操作将失效
 * |
 * |--------- 注册 ----->
 * |
 * |    User_register($username, $password, $email)   -------------------- 用户注册
 * |    register($username, $password, $email)        -------------------- 用户注册(简便api)
 * |
 * |------ 获取用户对象 -->
 * |
 * |    User_get_user($key=null)       ---------------------------------------- 获取指定用户信息
 * |    get_user($key=null)            ---------------------------------------- 获取指定用户信息(简便api)
 * |
 * |    注: 当$key为null时,默认获取当前会话中的用户信息
 * |
 * |    User_get_all_user()          ---------------------------------------- 获取所有用户信息
 * |
 * |------ 访问、修改用户信息 -->
 * |
 * |    User_get_name($user)       ---------------------------------------- 获取用户名
 * |    User_get_email($user)      ---------------------------------------- 获取邮箱
 * |    User_get_role($user)       ---------------------------------------- 获取用户角色
 * |
 * |    User_change_name($user, $new_username)   ------------------------- 修改用户名
 * |    User_change_password($user, $new_password) ---------------------- 修改密码
 * |    User_change_email($user, $new_email)    ------------------------- 修改邮箱
 * |
 * |    User_to_admin($user)      ---------------------------------------- 将指定的用户权限修改为管理员
 * |    User_to_writer($user)     ---------------------------------------- 将指定的用户权限修改为作者
 * |    User_to_user($user)       ---------------------------------------- 将指定的用户权限修改为用户
 * |    
 * |    注: 这些函数可以直接修改其他用户的信息，
 * |        请谨慎使用，应在使用前对用户进行权限验证，
 * |        避免对系统安全造成影响
 * |    
 * |------ 查询键是否存在 -->
 * |
 * |    User_check_name($username)   ------------------------------------ 查询用户名是否存在
 * |    User_check_email($email)     ------------------------------------ 查询邮箱是否存在
 * |
 * |------- 删除用户 ----->
 * |
 * |    User_delete($user)        ---------------------------------------- 删除指定用户
 * |
 * L___________________________________________________________________________________________________    
 */


// 引入预处理库
include_once __DIR__ . '/../../include/_PRE.php';

include_once __DIR__ . '/header.php';
include_once __DIR__ . '/../../include/conn.php';
include_once __DIR__ . '/../../libs/function/user.php';


/**
 * 用户登录，在代码里调用
 * @param string $username 用户输入的用户名
 * @param string $password 用户输入的明文密码
 * @return bool 登录成功返回true，否则false
 */
function login($username, $password, $cookie = false)    // 简便api
{
    return User_login($username, $password, $cookie);
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
 * 用户注销，在代码里调用
 * @return bool 注销成功返回true，否则false
 */
function logout() // 简便api
{
    return User_logout();
}

/**
 * 获取用户信息
 * @param string|null $key 用户名或邮箱或id(当为null时,默认获取当前会话中的用户信息)
 * @return null|User 用户信息对象
 */
function get_user($key = null) // 简便api
{
    return User_get_user($key);
}
