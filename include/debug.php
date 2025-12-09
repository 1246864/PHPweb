<?php
include_once __DIR__ . "/../config/config.php";
if (!$config["site"]["debug"]) {
    // 关闭调试模式，禁止PHP输出调试信息

} else if ($config["site"]["debug"] && $config["debug"]["more_debug"]) {

    // 设置自定义错误处理函数，用于显示更直观的错误堆栈
    set_error_handler(function ($errno, $errstr, $errfile, $errline) {

        global $config;
        if ($config["debug"]["clear_debug"]) {
            // 清空页面
            ob_end_clean();
        }

        $errorType = [
            E_ERROR             => '致命错误',
            E_WARNING           => '警告',
            E_PARSE             => '解析错误',
            E_NOTICE            => '通知',
            E_CORE_ERROR        => '核心致命错误',
            E_CORE_WARNING      => '核心警告',
            E_COMPILE_ERROR     => '编译致命错误',
            E_COMPILE_WARNING   => '编译警告',
            E_USER_ERROR        => '用户致命错误',
            E_USER_WARNING      => '用户警告',
            E_USER_NOTICE       => '用户通知',
            E_STRICT            => '严格标准',
            E_RECOVERABLE_ERROR => '可恢复致命错误',
            E_DEPRECATED        => '已弃用',
            E_USER_DEPRECATED   => '用户已弃用'
        ];

        $type = isset($errorType[$errno]) ? $errorType[$errno] : '未知错误';
        echo "<div style='background:#ffecec;border:1px solid #ff6666;padding:10px;margin:10px;font-family:monospace;'>";
        echo "<strong>[$type]</strong> $errstr<br>";
        echo "<small>文件：$errfile 第 $errline 行</small><br>";
        echo "<pre>";
        debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        echo "</pre>";
        echo "</div>";
        if ($config["debug"]["clear_debug"]) {
            // 停止
            exit;
        }
        return true;
    });

    // 设置异常处理函数，用于捕获未捕获的异常
    set_exception_handler(function ($exception) {

        global $config;
        if ($config["debug"]["clear_debug"]) {
            // 清空页面
            ob_end_clean();
        }

        echo "<div style='background:#ffecec;border:1px solid #ff6666;padding:10px;margin:10px;font-family:monospace;'>";
        echo "<strong>[未捕获异常]</strong> " . $exception->getMessage() . "<br>";
        echo "<small>文件：" . $exception->getFile() . " 第 " . $exception->getLine() . " 行</small><br>";
        echo "<pre>";
        echo $exception->getTraceAsString();
        echo "</pre>";
        echo "</div>";
        if ($config["debug"]["clear_debug"]) {
            // 停止
            exit;
        }
    });
}
