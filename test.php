<?php
include_once __DIR__ . '/include/header.php';
include_once __DIR__ . '/api/user.php';

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
        echo "___1_".user_register('j2yz', 'zyj123', '1278633').'<br/>';
        echo "___2_".login('j2yz', 'zyj123').'<br/>';
    ?>    
</body>

</html>