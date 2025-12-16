<?php 
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