<?php 
// 引入预处理库
include_once __DIR__ . '/../include/_PRE.php';

include_once __DIR__ . '/../config/config.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 服务器错误</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            text-align: center;
        }
        h1 {
            color: #f00;
        }
        p {
            color: #000;
        }
        a {
            color: #00f;
        }
    </style>
</head>
<body>
    <h1>404 服务器错误</h1>
    <p>您请求的页面不存在。</p>
    <p>请检查您输入的 URL 是否正确。</p>
    <a href="<?php echo $config['site']['url']; ?>">返回首页</a>
</body>
</html>