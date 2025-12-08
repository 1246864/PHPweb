<?php
// 引入预处理库 - 提供基础功能和错误处理
include_once __DIR__ . '/../include/_PRE.php';
include_once __DIR__ . '/../include/header.php';

/**
 * 用户控制器 - 处理用户相关功能
 * 
 * 这个控制器负责处理所有与用户相关的请求：
 * 1. 用户中心首页
 * 2. 用户个人资料
 * 3. 用户登录
 * 4. 用户退出
 * 
 * 对应的路由配置在 config/config.php 中：
 * - 'user' -> UserController@index
 * - 'user/profile' -> UserController@profile
 * - 'user/login' -> UserController@login
 * - 'user/logout' -> UserController@logout
 */
class UserController {
    
    /**
     * 用户中心首页 - 显示用户功能菜单
     * 
     * 这个方法对应路由：
     * - 'user'
     * 
     * 当用户访问 /PHPweb/user 时，路由器会调用这个方法
     * 显示用户中心的导航菜单和功能列表
     */
    public function index() {
        // 获取全局配置变量
        global $config;
        
        // 从配置中提取常用变量
        $siteName = $config['site']['name'];
        $siteUrl = $config['site']['url'];
        $pageTitle = '用户中心';
        ?>
        <!DOCTYPE html>
        <html lang="zh-CN">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $pageTitle . $config['site']['title_separator'] . $siteName; ?></title>
            <link rel="stylesheet" href="/PHPweb/css/css.css">
        </head>
        <body>
            <header>
                <h1><?php echo $siteName; ?></h1>
                <nav>
                    <a href="/PHPweb/">首页</a>
                    <a href="/PHPweb/about">关于</a>
                    <a href="/PHPweb/contact">联系</a>
                    <a href="/PHPweb/user">用户中心</a>
                </nav>
            </header>
            
            <main>
                <h2>用户中心</h2>
                <p>欢迎来到用户中心!</p>
                <nav>
                    <a href="/PHPweb/user/profile">个人资料</a> |
                    <a href="/PHPweb/user/login">登录</a> |
                    <a href="/PHPweb/user/logout">退出</a>
                </nav>
            </main>
            
            <footer>
                <p>&copy; <?php echo date('Y'); ?> <?php echo $siteName; ?></p>
            </footer>
        </body>
        </html>
        <?php
    }
    
    /**
     * 个人资料页面 - 显示用户详细信息
     * 
     * 这个方法对应路由：
     * - 'user/profile'
     * 
     * 当用户访问 /PHPweb/user/profile 时，路由器会调用这个方法
     * 显示用户的个人资料信息
     */
    public function profile() {
        // 获取全局配置变量
        global $config;
        
        // 从配置中提取常用变量
        $siteName = $config['site']['name'];
        $siteUrl = $config['site']['url'];
        $pageTitle = '个人资料';
        ?>
        <!DOCTYPE html>
        <html lang="zh-CN">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $pageTitle . $config['site']['title_separator'] . $siteName; ?></title>
            <link rel="stylesheet" href="/PHPweb/css/css.css">
        </head>
        <body>
            <header>
                <h1><?php echo $siteName; ?></h1>
                <nav>
                    <a href="/PHPweb/">首页</a>
                    <a href="/PHPweb/about">关于</a>
                    <a href="/PHPweb/contact">联系</a>
                    <a href="/PHPweb/user">用户中心</a>
                </nav>
            </header>
            
            <main>
                <h2>个人资料</h2>
                <p>这里显示用户的个人资料信息。</p>
                <p>用户名: 示例用户</p>
                <p>注册时间: <?php echo date('Y-m-d H:i:s'); ?></p>
                <p><a href="/PHPweb/user">返回用户中心</a></p>
            </main>
            
            <footer>
                <p>&copy; <?php echo date('Y'); ?> <?php echo $siteName; ?></p>
            </footer>
        </body>
        </html>
        <?php
    }
    
    /**
     * 用户登录页面 - 显示登录表单
     * 
     * 这个方法对应路由：
     * - 'user/login'
     * 
     * 当用户访问 /PHPweb/user/login 时，路由器会调用这个方法
     * 显示用户登录表单，处理登录逻辑
     */
    public function login() {
        // 获取全局配置变量
        global $config;
        
        // 从配置中提取常用变量
        $siteName = $config['site']['name'];
        $siteUrl = $config['site']['url'];
        $pageTitle = '用户登录';
        ?>
        <!DOCTYPE html>
        <html lang="zh-CN">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $pageTitle . $config['site']['title_separator'] . $siteName; ?></title>
            <link rel="stylesheet" href="/PHPweb/css/css.css">
        </head>
        <body>
            <header>
                <h1><?php echo $siteName; ?></h1>
                <nav>
                    <a href="/PHPweb/">首页</a>
                    <a href="/PHPweb/about">关于</a>
                    <a href="/PHPweb/contact">联系</a>
                    <a href="/PHPweb/user">用户中心</a>
                </nav>
            </header>
            
            <main>
                <h2>用户登录</h2>
                <form method="post" action="/PHPweb/user/login">
                    <div>
                        <label for="username">用户名:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div>
                        <label for="password">密码:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div>
                        <button type="submit">登录</button>
                    </div>
                </form>
                <p><a href="/PHPweb/user">返回用户中心</a></p>
            </main>
            
            <footer>
                <p>&copy; <?php echo date('Y'); ?> <?php echo $siteName; ?></p>
            </footer>
        </body>
        </html>
        <?php
    }
    
    /**
     * 用户退出 - 处理用户退出登录
     * 
     * 这个方法对应路由：
     * - 'user/logout'
     * 
     * 当用户访问 /PHPweb/user/logout 时，路由器会调用这个方法
     * 处理用户退出登录的逻辑，然后重定向到首页
     */
    public function logout() {
        // 获取全局配置变量
        global $config;
        
        // 这里可以添加退出登录的逻辑
        // 例如：清除会话数据、销毁会话等
        
        // 重定向到网站首页（从配置中获取URL）
        // 这样可以确保重定向地址在不同环境中都是正确的
        header('Location: ' . $config['site']['url']);
        exit(); // 确保脚本停止执行
    }
}