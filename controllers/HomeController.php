<?php
// 引入预处理库 - 提供基础功能和错误处理
include_once __DIR__ . '/../include/_PRE.php';
include_once __DIR__ . '/../include/header.php';

/**
 * 首页控制器 - 处理网站首页和静态页面
 * 
 * 控制器是MVC架构中的C（Controller），负责：
 * 1. 接收路由器分发的请求
 * 2. 处理业务逻辑
 * 3. 调用视图或直接输出HTML
 * 
 * 这个控制器处理以下路由：
 * - '' 或 'home' -> index() 方法
 * - 'about' -> about() 方法  
 * - 'contact' -> contact() 方法
 */
class HomeController {
    
    /**
     * 首页方法 - 处理网站首页请求
     * 
     * 这个方法对应路由：
     * - '' (空字符串，根路径)
     * - 'home'
     * 
     * 路由器会根据 config/config.php 中的路由配置调用这个方法
     */
    public function index() {
        // 获取全局配置变量，包含网站的所有设置
        global $config;
        
        // 从配置中提取常用变量，方便在模板中使用
        $siteName = $config['site']['name'];
        $siteUrl = $config['site']['url'];
        $pageTitle = '首页';
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
                <h2>欢迎来到 <?php echo $siteName; ?>!</h2>
                <p>这是使用路由系统的首页。</p>
                <p>当前时间: <?php echo date('Y-m-d H:i:s'); ?></p>
            </main>
            
            <footer>
                <p>&copy; <?php echo date('Y'); ?> <?php echo $siteName; ?></p>
            </footer>
        </body>
        </html>
        <?php
    }
    
    /**
     * 关于页面方法 - 处理关于我们页面请求
     * 
     * 这个方法对应路由：
     * - 'about'
     * 
     * 当用户访问 /PHPweb/about 时，路由器会调用这个方法
     */
    public function about() {
        // 获取全局配置变量
        global $config;
        
        // 从配置中提取常用变量
        $siteName = $config['site']['name'];
        $siteUrl = $config['site']['url'];
        $pageTitle = '关于我们';
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
                <h2>关于我们</h2>
                <p>这是一个使用简单路由系统的PHP项目示例。</p>
                <p>路由系统帮助我们更好地组织代码结构。</p>
            </main>
            
            <footer>
                <p>&copy; <?php echo date('Y'); ?> <?php echo $siteName; ?></p>
            </footer>
        </body>
        </html>
        <?php
    }
    
    /**
     * 联系页面方法 - 处理联系我们页面请求
     * 
     * 这个方法对应路由：
     * - 'contact'
     * 
     * 当用户访问 /PHPweb/contact 时，路由器会调用这个方法
     */
    public function contact() {
        // 获取全局配置变量
        global $config;
        
        // 从配置中提取常用变量
        $siteName = $config['site']['name'];
        $siteUrl = $config['site']['url'];
        $pageTitle = '联系我们';
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
                <h2>联系我们</h2>
                <p>如果您有任何问题，请通过以下方式联系我们：</p>
                <ul>
                    <li>邮箱: contact@example.com</li>
                    <li>电话: 123-456-7890</li>
                    <li>地址: 示例地址123号</li>
                </ul>
            </main>
            
            <footer>
                <p>&copy; <?php echo date('Y'); ?> <?php echo $siteName; ?></p>
            </footer>
        </body>
        </html>
        <?php
    }
}