# PHPweb 项目

一个简单的PHP Web应用程序，演示了基础的路由系统实现。

## 项目概述

PHPweb是一个轻量级的PHP项目，最初作为学习示例，现已实现了基本的MVC架构和路由系统，使URL更加友好和代码更加组织化。

## 功能特性

- ✅ 简单的路由系统
- ✅ MVC架构分离
- ✅ 响应式设计
- ✅ 错误处理机制（404/500页面）
- ✅ 基础的用户管理功能

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

## 路由系统详解

### 路由器实现

路由系统位于 `include/router.php`，提供了以下功能：

1. **静态路由**: 直接映射URL到控制器方法
2. **动态路由**: 支持URL参数传递（如 `user/{id}`）
3. **自动分发**: 根据URL自动调用对应的控制器和方法

### 路由配置

默认路由配置如下：

```php
$this->routes = [
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

2. **配置步骤**
   - 克隆项目到Web目录
   - 修改 `config/config.php` 中的数据库配置
   - 确保Apache启用 `mod_rewrite` 模块
   - 设置正确的文件权限

3. **访问方式**
   - 通过浏览器访问项目URL
   - 默认首页：`http://yourdomain.com/PHPweb/`

## 开发指南

### 添加新路由

1. 在 `include/router.php` 的路由配置中添加新规则
2. 创建对应的控制器和方法
3. 可选：创建视图文件

### 添加新控制器

1. 在 `controllers/` 目录下创建新的控制器文件
2. 继承基础控制器结构
3. 实现所需的方法

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

## 许可证

本项目仅用于学习目的，请勿用于商业用途。

## 联系方式

如有问题或建议，请通过以下方式联系：
- 提交Issue
- 发送邮件至：contact@example.com