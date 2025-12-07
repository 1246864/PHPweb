<?php
    include 'conn.php';
    

    function checkLogin() {
        if (!isset($_SESSION['user'])) {
            http_response_code(403);
            include 'error/403.html';
            exit();
        }
    }
?>