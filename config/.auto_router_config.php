<?php
// 自动路由配置文件,由admin/auto_router.文件自动php生成,不推荐在这进行配置
include_once __DIR__ . '/../include/_PRE.php';
auto_add_Page('/cc', 'pages/a.php', 'ALL');
auto_add_Page('/page/index', 'pages/index.php', 'ALL');
auto_add_Page('/page/首页', 'pages/index.php', 'ALL');
auto_add_Page('/page/news', 'pages/news.php', 'ALL');
auto_add_Page('/test/user/1', 'pages/test/user/test.php', 'ALL');
auto_add_Page('/test/user/2', 'pages/test/user/test_api.php', 'ALL');
