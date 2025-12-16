<?php
// 新闻相关方法

// 对应数据表名：news
// 字段：id, title, title2, image_id, style, content, user_id, time

// 引入预处理库
include_once __DIR__ . '/../../include/_PRE.php';

include_once __DIR__ . '/../../include/conn.php';
include_once __DIR__ . '/../../config/config.php';
include_once __DIR__ . '/function.php';

include_once __DIR__ . '/class/News.php'; // 引入新闻类

/**
 * 获取所有新闻
 * @return array<News>|null 新闻数组
 */
function News_get_all_news()
{
    global $conn, $config;
    try {
        $sql = "SELECT * FROM news";
        $result = $conn->query($sql);
        $news = [];
        while ($row = $result->fetch_assoc()) {
            $news[] = new News($row['id'], $row['title'], $row['title2'], $row['image_id'], $row['style'], $row['content'], $row['user_id'], $row['time']);
        }
        return $news;
    } catch (\Throwable $th) {
        if($config['debug']['use_debug']){
            echo '错误：(News_get_all_news)' . $th->getMessage();
        }
        return null;
    }
}

