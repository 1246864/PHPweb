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
        $user = User_get_user('jyz2');
        if ($user) {
            print_r($user);
            $user = User_to_writer($user);
            echo '<br> 更改后的权限：' . $user->role.'<br>';
            print_r($user);
        }
    ?>    
</body>

</html>