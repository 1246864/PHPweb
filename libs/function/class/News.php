<?php 

// 引入预处理库
include_once __DIR__ . '/../../include/_PRE.php';

// 引入配置文件
include_once __DIR__ . '/../../config/config.php';

// 引入数据库连接文件
include_once __DIR__ . '/../../include/conn.php';

// 引入全局函数库
include_once __DIR__ . '/../function.php';

// 引入文件类
include_once __DIR__ . '/File.php';

// 引入用户类
include_once __DIR__ . '/User.php';

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

    // ----------------------获取信息----------------------------
    /**
     * 获取新闻ID
     * @return int|null 新闻ID
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * 获取新闻标题
     * @return string|null 新闻标题
     */
    public function get_title()
    {
        return $this->title;
    }

    /**
     * 获取新闻副标题
     * @return string|null 新闻副标题
     */
    public function get_title2()
    {
        return $this->title2;
    }

    /**
     * 获取封面图片ID
     * @return int|null 封面图片ID
     */
    public function get_image_id()
    {
        return $this->image_id;
    }

    /**
     * 获取新闻样式模板
     * @return string|null 新闻样式模板
     */
    public function get_style()
    {
        return $this->style;
    }

    /**
     * 获取新闻内容
     * @return string|null 新闻内容
     */
    public function get_content()
    {
        return $this->content;
    }

    /**
     * 获取新闻所属用户ID
     * @return int|null 用户ID
     */
    public function get_user_id()
    {
        return $this->user_id;
    }

    /**
     * 获取新闻发布时间
     * @return string|null 新闻发布时间
     */
    public function get_time()
    {
        return $this->time;
    }

    // ----------------------设置信息----------------------------
    /**
     * 设置新闻标题
     * @param string $title 新闻标题
     * @return bool 是否设置成功
     */
    public function set_title($title)
    {
        $this->title = $title;
        return true;
    }

    /**
     * 设置新闻副标题
     * @param string $title2 新闻副标题
     * @return bool 是否设置成功
     */
    public function set_title2($title2)
    {
        $this->title2 = $title2;
        return true;
    }

    /**
     * 设置封面图片ID
     * @param int|File $image_id 封面图片ID或文件对象
     * @return bool 是否设置成功
     */
    public function set_image_id($image_id)
    {
        if($image_id instanceof File)
        {
            $this->image_id = $image_id->get_id();
        }
        else
        {
            $this->image_id = $image_id;
        }
        return true;
    }

    /**
     * 设置新闻样式模板
     * @param string $style 新闻样式模板
     * @return bool 是否设置成功
     */
    public function set_style($style)
    {
        $this->style = $style;
        return true;
    }

    /**
     * 设置新闻内容
     * @param string $content 新闻内容
     * @return bool 是否设置成功
     */
    public function set_content($content)
    {
        $this->content = $content;
        return true;
    }

    /**
     * 设置新闻所属用户ID
     * @param int|User $user_id 用户ID或用户对象
     * @return bool 是否设置成功
     */
    public function set_user_id($user_id)
    {
        if($user_id instanceof User)
        {
            $this->user_id = $user_id->get_id();
        }
        else
        {
            $this->user_id = $user_id;
        }
        return true;
    }

    /**
     * 设置新闻发布时间
     * @param string $time 新闻发布时间
     * @return bool 是否设置成功
     */
    public function set_time($time)
    {
        $this->time = $time;
        return true;
    }

    /**
     * 同步新闻信息到数据库
     * 直接覆盖现有数据，不进行任何修改检查或验证
     * @return bool 是否同步成功
     */
    public function sync()
    {
        global $config, $conn;
        try {
            // 检查新闻ID是否存在
            if (empty($this->id)) {
                if ($config['debug']['use_debug']) {
                    echo '错误：(News::sync)新闻ID不存在';
                }
                return false;
            }

            // 构建更新的SQL语句，直接覆盖所有字段
            $sql = "UPDATE news SET title = ?, title2 = ?, image_id = ?, style = ?, content = ?, user_id = ?, time = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            
            // 绑定参数
            $stmt->bind_param("ssissssi", $this->title, $this->title2, $this->image_id, $this->style, $this->content, $this->user_id, $this->time, $this->id);
            
            // 执行数据库更新
            $stmt->execute();
            return $stmt->affected_rows >= 0;
        } catch (Throwable $th) {
            if ($config['debug']['use_debug']) {
                echo '错误：(News::sync)' . $th->getMessage();
            }
            return false;
        }
    }
}