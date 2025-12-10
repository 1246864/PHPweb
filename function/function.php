<?php
// 引入预处理库
include_once __DIR__ . '/../include/_PRE.php';

include_once __DIR__ . '/../config/config.php';


/**
 * 生成密码哈希（推荐用法）
 * @param string $password 明文密码
 * @return string 加密后的哈希字符串
 */
function encryptPassword(string $password)
{
    // 算法选项：默认 PASSWORD_DEFAULT（BCrypt），PHP 7.2+ 可使用 PASSWORD_ARGON2ID（更安全）
    $algo = PASSWORD_DEFAULT;
    // 成本因子：数值越大，哈希计算越慢，抵御暴力破解能力越强（默认 10，建议 10-12）
    $options = [
        'cost' => 12,
        // 若使用 Argon2，需配置以下参数（PHP 7.2+，且服务器需安装 Argon2 扩展）
        // 'memory_cost' => 1<<17, // 128MB
        // 'time_cost' => 4,
        // 'threads' => 3,
    ];
    
    // 生成哈希（自动生成盐值，无需手动添加）
    $hash = password_hash($password, $algo, $options);
    
    // 验证哈希生成是否成功
    if ($hash === false) {
        throw new RuntimeException('密码哈希生成失败');
    }
    
    return $hash;
}


/**
 * 验证密码是否匹配
 * @param string $plainPassword 用户输入的明文密码
 * @param string $storedHash 数据库中存储的密码哈希
 * @return bool 匹配返回true，否则false
 */
function verifyPassword(string $plainPassword, string $storedHash)
{
    // 空哈希直接返回false
    if (empty($storedHash)) {
        return false;
    }
    
    // password_verify 会自动解析哈希中的盐值和算法，无需手动处理
    return password_verify($plainPassword, $storedHash);
}


