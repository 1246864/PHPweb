# PHPweb 项目

一个简单的PHP Web应用程序，演示了基础的路由系统实现和集中化配置管理。

## 项目概述

PHPweb是一个轻量级的PHP项目，最初作为学习示例，现已实现了基本的MVC架构、路由系统和集中化配置管理，使URL更加友好、代码更加组织化，并且便于在不同环境中部署。

## 功能特性

- ✅ 简单的路由系统（静态和动态路由支持）
- ✅ MVC架构分离
- ✅ 响应式设计
- ✅ 错误处理机制（404/500页面）
- ✅ 基础的用户管理功能
- ✅ 集中化配置管理
- ✅ 灵活的环境适配
- ✅ 可扩展的控制器系统

## 项目结构

```
PHPweb/
├───.htaccess              # Apache重写规则
├───index.php              # 前端控制器（统一入口）
├───README.md              # 项目文档
├───config/
│   ├───.htaccess          # 配置目录保护
│   └───config.php         # 数据库和站点配置
├───css/
│   └───css.css            # 样式文件
├───include/
│   ├───.htaccess          # 包含文件保护
│   ├───_PRE.php           # 预处理库
│   ├───conn.php           # 数据库连接
│   ├───function.php       # 通用函数
│   ├───header.php         # 头部包含文件
│   ├───router.php         # 路由系统核心
│   ├───test.php           # 测试文件
│   └───user.php           # 用户相关函数
├───controllers/
│   ├───HomeController.php # 首页控制器
│   └───UserController.php # 用户控制器
└───page_error/
    ├───.htaccess          # 错误页面保护
    ├───404.php            # 404错误页面
    └───500.php            # 500错误页面
```

## 配置系统详解

### 集中化配置管理

所有配置项都集中在 `config/config.php` 文件中，包括：

1. **网站配置**: 网站名称、URL、调试模式等
2. **路由配置**: URL到控制器方法的映射规则
3. **控制器配置**: 控制器目录、命名空间等
4. **数据库配置**: 连接参数、字符集等
5. **安全配置**: CSRF、XSS、SQL注入保护等
6. **会话配置**: 会话生命周期、安全设置等

### 环境适配

通过修改配置文件即可适应不同的部署环境：

```php
// 生产环境
$config['site']['url'] = 'https://yourdomain.com/PHPweb';
$config['site']['debug'] = false;

// 开发环境
$config['site']['url'] = 'http://localhost/PHPweb';
$config['site']['debug'] = true;
```

详细配置说明请参考 `config.example.md` 文件。

## 路由系统详解

### 路由器实现

路由系统位于 `include/router.php`，提供了以下功能：

1. **静态路由**: 直接映射URL到控制器方法
2. **动态路由**: 支持URL参数传递（如 `user/{id}`）
3. **自动分发**: 根据URL自动调用对应的控制器和方法
4. **配置驱动**: 路由规则从配置文件加载

### 路由配置

路由配置位于 `config/config.php` 中：

```php
$config['routes'] = [
    '' => 'HomeController@index',
    'home' => 'HomeController@index',
    'user' => 'UserController@index',
    'user/profile' => 'UserController@profile',
    'user/login' => 'UserController@login',
    'user/logout' => 'UserController@logout',
    'about' => 'HomeController@about',
    'contact' => 'HomeController@contact'
];
```

### URL映射示例

| URL | 控制器 | 方法 |
|-----|--------|------|
| `/PHPweb/` | HomeController | index |
| `/PHPweb/about` | HomeController | about |
| `/PHPweb/contact` | HomeController | contact |
| `/PHPweb/user` | UserController | index |
| `/PHPweb/user/profile` | UserController | profile |
| `/PHPweb/user/login` | UserController | login |

## 控制器说明

### HomeController

处理首页和静态页面：
- `index()` - 首页
- `about()` - 关于页面
- `contact()` - 联系页面

### UserController

处理用户相关功能：
- `index()` - 用户中心首页
- `profile()` - 个人资料页面
- `login()` - 登录页面
- `logout()` - 退出登录

## 安装和配置

1. **环境要求**
   - PHP 7.0+
   - Apache服务器（支持.htaccess重写）
   - MySQL数据库（可选）

2. **快速部署**
   - 克隆项目到Web目录
   - 修改 `config/config.php` 中的基础配置
   - 确保Apache启用 `mod_rewrite` 模块
   - 设置正确的文件权限

3. **环境适配配置**
   
   **生产环境配置示例**：
   ```php
   $config['site']['url'] = 'https://yourdomain.com/PHPweb';
   $config['site']['debug'] = false;
   $config['db']['host'] = 'your-production-db-host';
   $config['db']['user'] = 'your-production-db-user';
   $config['db']['password'] = 'your-production-db-password';
   ```
   
   **开发环境配置示例**：
   ```php
   $config['site']['url'] = 'http://localhost/PHPweb';
   $config['site']['debug'] = true;
   $config['db']['host'] = 'localhost';
   $config['db']['user'] = 'root';
   $config['db']['password'] = 'root';
   ```

4. **访问方式**
   - 通过浏览器访问项目URL
   - 默认首页：根据 `$config['site']['url']` 配置访问

## 开发指南

### 添加新路由

1. 在 `config/config.php` 的 `$config['routes']` 数组中添加新规则
2. 创建对应的控制器和方法
3. 可选：创建视图文件

```php
// 在配置文件中添加路由
$config['routes']['new/page'] = 'NewController@methodName';
```

### 添加新控制器

1. 在 `controllers/` 目录下创建新的控制器文件
2. 使用全局配置变量获取配置信息
3. 实现所需的方法

```php
class NewController {
    public function methodName() {
        global $config;
        $siteName = $config['site']['name'];
        // 控制器逻辑
    }
}
```

### 配置管理

- 所有配置项都在 `config/config.php` 中管理
- 使用 `global $config;` 在控制器中访问配置
- 参考 `config.example.md` 了解不同环境的配置方法

### 修改样式

编辑 `css/css.css` 文件来自定义页面样式。

## 安全注意事项

⚠️ **当前版本仅用于学习和演示，不建议直接用于生产环境**

- 数据库密码明文存储在配置文件中
- 缺少输入验证和SQL注入防护
- 没有实现完整的身份验证系统
- 错误信息可能暴露敏感信息

## 贡献指南

1. Fork 本项目
2. 创建特性分支 (`git checkout -b feature/AmazingFeature`)
3. 提交更改 (`git commit -m 'Add some AmazingFeature'`)
4. 推送到分支 (`git push origin feature/AmazingFeature`)
5. 打开 Pull Request

## 版本历史

- **v1.0** - 基础项目结构
- **v1.1** - 添加路由系统和MVC架构
- **v1.2** - 实现集中化配置管理和环境适配

## 许可证

本项目仅用于学习目的，请勿用于商业用途。

## 联系方式

如有问题或建议，请通过以下方式联系：
- 提交Issue
- 发送邮件至：contact@example.com