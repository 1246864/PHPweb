<?php
// 引入预处理库
include_once __DIR__ . '/../include/_PRE.php';
include_once __DIR__ . '/../include/header.php';

class UserController {
    
    public function index() {
        global $config;
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
    
    public function profile() {
        global $config;
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
    
    public function login() {
        global $config;
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
    
    public function logout() {
        global $config;
        // 这里可以添加退出登录的逻辑
        header('Location: ' . $config['site']['url']);
        exit();
    }
}