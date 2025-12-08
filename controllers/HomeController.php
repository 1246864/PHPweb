<?php
// 引入预处理库
include_once __DIR__ . '/../include/_PRE.php';
include_once __DIR__ . '/../include/header.php';

class HomeController {
    
    public function index() {
        include_once __DIR__ . '/../config/config.php';
        ?>
        <!DOCTYPE html>
        <html lang="zh-CN">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $config['site']['name']; ?> - 首页</title>
            <link rel="stylesheet" href="/PHPweb/css/css.css">
        </head>
        <body>
            <header>
                <h1><?php echo $config['site']['name']; ?></h1>
                <nav>
                    <a href="/PHPweb/">首页</a>
                    <a href="/PHPweb/about">关于</a>
                    <a href="/PHPweb/contact">联系</a>
                    <a href="/PHPweb/user">用户中心</a>
                </nav>
            </header>
            
            <main>
                <h2>欢迎来到 <?php echo $config['site']['name']; ?>!</h2>
                <p>这是使用路由系统的首页。</p>
                <p>当前时间: <?php echo date('Y-m-d H:i:s'); ?></p>
            </main>
            
            <footer>
                <p>&copy; <?php echo date('Y'); ?> <?php echo $config['site']['name']; ?></p>
            </footer>
        </body>
        </html>
        <?php
    }
    
    public function about() {
        include_once __DIR__ . '/../config/config.php';
        ?>
        <!DOCTYPE html>
        <html lang="zh-CN">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>关于我们 - <?php echo $config['site']['name']; ?></title>
            <link rel="stylesheet" href="/PHPweb/css/css.css">
        </head>
        <body>
            <header>
                <h1><?php echo $config['site']['name']; ?></h1>
                <nav>
                    <a href="/PHPweb/">首页</a>
                    <a href="/PHPweb/about">关于</a>
                    <a href="/PHPweb/contact">联系</a>
                    <a href="/PHPweb/user">用户中心</a>
                </nav>
            </header>
            
            <main>
                <h2>关于我们</h2>
                <p>这是一个使用简单路由系统的PHP项目示例。</p>
                <p>路由系统帮助我们更好地组织代码结构。</p>
            </main>
            
            <footer>
                <p>&copy; <?php echo date('Y'); ?> <?php echo $config['site']['name']; ?></p>
            </footer>
        </body>
        </html>
        <?php
    }
    
    public function contact() {
        include_once __DIR__ . '/../config/config.php';
        ?>
        <!DOCTYPE html>
        <html lang="zh-CN">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>联系我们 - <?php echo $config['site']['name']; ?></title>
            <link rel="stylesheet" href="/PHPweb/css/css.css">
        </head>
        <body>
            <header>
                <h1><?php echo $config['site']['name']; ?></h1>
                <nav>
                    <a href="/PHPweb/">首页</a>
                    <a href="/PHPweb/about">关于</a>
                    <a href="/PHPweb/contact">联系</a>
                    <a href="/PHPweb/user">用户中心</a>
                </nav>
            </header>
            
            <main>
                <h2>联系我们</h2>
                <p>如果您有任何问题，请通过以下方式联系我们：</p>
                <ul>
                    <li>邮箱: contact@example.com</li>
                    <li>电话: 123-456-7890</li>
                    <li>地址: 示例地址123号</li>
                </ul>
            </main>
            
            <footer>
                <p>&copy; <?php echo date('Y'); ?> <?php echo $config['site']['name']; ?></p>
            </footer>
        </body>
        </html>
        <?php
    }
}