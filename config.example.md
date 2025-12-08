# 配置文件说明

这个文件展示了如何通过修改 `config/config.php` 来适应不同的部署环境。

## 生产环境配置示例

```php
// 生产环境配置
$config['site']['url'] = 'https://yourdomain.com/PHPweb';
$config['site']['debug'] = false;
$config['db']['host'] = 'your-production-db-host';
$config['db']['user'] = 'your-production-db-user';
$config['db']['password'] = 'your-production-db-password';
$config['db']['database'] = 'your-production-db-name';
```

## 本地开发环境配置示例

```php
// 本地开发环境配置
$config['site']['url'] = 'http://localhost/PHPweb';
$config['site']['debug'] = true;
$config['db']['host'] = 'localhost';
$config['db']['user'] = 'root';
$config['db']['password'] = 'root';
$config['db']['database'] = 'test';
```

## 不同机器部署步骤

1. **修改网站URL配置**：
   ```php
   $config['site']['url'] = 'http://your-server-path/PHPweb';
   ```

2. **修改数据库配置**：
   ```php
   $config['db']['host'] = 'your-db-host';
   $config['db']['user'] = 'your-db-user';
   $config['db']['password'] = 'your-db-password';
   $config['db']['database'] = 'your-db-name';
   ```

3. **调整调试模式**：
   - 生产环境：`$config['site']['debug'] = false;`
   - 开发环境：`$config['site']['debug'] = true;`

4. **可选：修改路由配置**（如需要）：
   ```php
   $config['routes'] = [
       // 添加或修改路由规则
   ];
   ```

## 配置项说明

- `$config['site']`: 网站基本信息
- `$config['routes']`: 路由规则配置
- `$config['controllers']`: 控制器相关配置
- `$config['db']`: 数据库连接配置
- `$config['error_pages']`: 错误页面路径
- `$config['session']`: 会话配置
- `$config['security']`: 安全相关配置

通过集中配置管理，只需修改一个文件即可适应不同的部署环境。