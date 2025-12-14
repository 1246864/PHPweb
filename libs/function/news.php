<?php
// 新闻相关方法

// 对应数据表名：news
// 字段：id, title, title2, image_id, style, content, user_id, time

// 引入预处理库
include_once __DIR__ . '/../../include/_PRE.php';

include_once __DIR__ . '/../../include/conn.php';
include_once __DIR__ . '/../../config/config.php';
include_once __DIR__ . '/function.php';

// 定义新闻对象
class News
{
    public $id;         // 新闻ID
    public $title;      // 新闻标题
    public $title2;     // 新闻副标题
    public $image_id;      // 封面图片ID
    public $style;     // 新闻样式模板
    public $content;    // 新闻内容
    public $user_id;    // 用户ID
    public $time;       // 发布时间

    // 构造函数
    public function __construct($id=null, $title=null, $title2=null, $image_id=null, $style=null, $content=null, $user_id=null, $time=null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->title2 = $title2;
        $this->image_id = $image_id;
        $this->style = $style;
        $this->content = $content;
        $this->user_id = $user_id;
        $this->time = $time;
    }
}
function News_get_news()
{
    global $conn, $config;
    try {
        $sql = "SELECT * FROM news";
        $result = mysqli_query($conn, $sql);
        $news = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_close($conn);
        return $news;
    } catch (\Throwable $th) {
        if($config['debug']['use_debug']){
            echo '错误：(News_get_news)' . $th->getMessage();
        }
        return null;
    }
}
