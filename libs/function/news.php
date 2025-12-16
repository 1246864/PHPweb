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
include_once __DIR__ . '/class/File.php'; // 引入文件类
include_once __DIR__ . '/class/User.php'; // 引入用户类

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

// ----------------------获取信息----------------------------

/**
 * 获取新闻ID
 * @param News $news 新闻对象
 * @return int|null 新闻ID
 */
function News_get_id($news)
{
    global $config;
    try {
        return $news->get_id();
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(News_get_id)' . $th->getMessage();
        }
        return null;
    }
}

/**
 * 获取新闻标题
 * @param News $news 新闻对象
 * @return string|null 新闻标题
 */
function News_get_title($news)
{
    global $config;
    try {
        return $news->get_title();
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(News_get_title)' . $th->getMessage();
        }
        return null;
    }
}

/**
 * 获取新闻副标题
 * @param News $news 新闻对象
 * @return string|null 新闻副标题
 */
function News_get_title2($news)
{
    global $config;
    try {
        return $news->get_title2();
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(News_get_title2)' . $th->getMessage();
        }
        return null;
    }
}

/**
 * 获取封面图片ID
 * @param News $news 新闻对象
 * @return int|null 封面图片ID
 */
function News_get_image_id($news)
{
    global $config;
    try {
        return $news->get_image_id();
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(News_get_image_id)' . $th->getMessage();
        }
        return null;
    }
}

/**
 * 获取新闻样式模板
 * @param News $news 新闻对象
 * @return string|null 新闻样式模板
 */
function News_get_style($news)
{
    global $config;
    try {
        return $news->get_style();
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(News_get_style)' . $th->getMessage();
        }
        return null;
    }
}

/**
 * 获取新闻内容
 * @param News $news 新闻对象
 * @return string|null 新闻内容
 */
function News_get_content($news)
{
    global $config;
    try {
        return $news->get_content();
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(News_get_content)' . $th->getMessage();
        }
        return null;
    }
}

/**
 * 获取新闻所属用户ID
 * @param News $news 新闻对象
 * @return int|null 用户ID
 */
function News_get_user_id($news)
{
    global $config;
    try {
        return $news->get_user_id();
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(News_get_user_id)' . $th->getMessage();
        }
        return null;
    }
}

/**
 * 获取新闻发布时间
 * @param News $news 新闻对象
 * @return string|null 新闻发布时间
 */
function News_get_time($news)
{
    global $config;
    try {
        return $news->get_time();
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(News_get_time)' . $th->getMessage();
        }
        return null;
    }
}

// ----------------------设置信息----------------------------

/**
 * 设置新闻标题
 * @param News $news 新闻对象
 * @param string $title 新闻标题
 * @return bool 是否设置成功
 */
function News_set_title($news, $title)
{
    global $config;
    try {
        return $news->set_title($title);
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(News_set_title)' . $th->getMessage();
        }
        return false;
    }
}

/**
 * 设置新闻副标题
 * @param News $news 新闻对象
 * @param string $title2 新闻副标题
 * @return bool 是否设置成功
 */
function News_set_title2($news, $title2)
{
    global $config;
    try {
        return $news->set_title2($title2);
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(News_set_title2)' . $th->getMessage();
        }
        return false;
    }
}

/**
 * 设置封面图片ID
 * @param News $news 新闻对象
 * @param int|File $image_id 封面图片ID或文件对象
 * @return bool 是否设置成功
 */
function News_set_image_id($news, $image_id)
{
    global $config;
    try {
        return $news->set_image_id($image_id);
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(News_set_image_id)' . $th->getMessage();
        }
        return false;
    }
}

/**
 * 设置新闻样式模板
 * @param News $news 新闻对象
 * @param string $style 新闻样式模板
 * @return bool 是否设置成功
 */
function News_set_style($news, $style)
{
    global $config;
    try {
        return $news->set_style($style);
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(News_set_style)' . $th->getMessage();
        }
        return false;
    }
}

/**
 * 设置新闻内容
 * @param News $news 新闻对象
 * @param string $content 新闻内容
 * @return bool 是否设置成功
 */
function News_set_content($news, $content)
{
    global $config;
    try {
        return $news->set_content($content);
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(News_set_content)' . $th->getMessage();
        }
        return false;
    }
}

/**
 * 设置新闻所属用户ID
 * @param News $news 新闻对象
 * @param int|User $user_id 用户ID或用户对象
 * @return bool 是否设置成功
 */
function News_set_user_id($news, $user_id)
{
    global $config;
    try {
        return $news->set_user_id($user_id);
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(News_set_user_id)' . $th->getMessage();
        }
        return false;
    }
}

/**
 * 设置新闻发布时间
 * @param News $news 新闻对象
 * @param string $time 新闻发布时间
 * @return bool 是否设置成功
 */
function News_set_time($news, $time)
{
    global $config;
    try {
        return $news->set_time($time);
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(News_set_time)' . $th->getMessage();
        }
        return false;
    }
}

/**
 * 同步新闻信息到数据库
 * @param News $news 新闻对象
 * @return bool 是否同步成功
 */
function News_sync($news)
{
    global $config;
    try {
        return $news->sync();
    } catch (\Throwable $th) {
        if ($config['debug']['use_debug']) {
            echo '错误：(News_sync)' . $th->getMessage();
        }
        return false;
    }
}