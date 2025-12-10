<?php
include_once __DIR__ . '/include/header.php';
include_once __DIR__ . '/function/user.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>aaaaa</h1>
    <?php
        // 测试为：有BUG
        echo login('1', '2');
    ?>    
</body>

</html>