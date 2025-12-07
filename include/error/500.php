<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 服务器错误</title>
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
    </style>
</head>
<body>
    <h1>500 服务器错误</h1>
    <p>服务器遇到了一个错误，无法完成您的请求。</p>
    <p><?php echo $error_message; ?></p>
</body>
</html>