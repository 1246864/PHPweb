# PHPweb 项目

一个基于 Bramus Router 库构建的轻量级 PHP Web 应用框架，实现了无感路由系统和现代 PHP 开发最佳实践。

## 🚀 项目概述

PHPweb 是一个简洁、可扩展的 PHP 项目，适合作为学习参考或小型项目的基础框架。项目展示了如何构建结构清晰、易于维护的 PHP 应用程序。

## ✨ 核心特性

- 🛣️ **无感路由系统**：支持四种路由注册方式，实现真正的无感开发体验
- 🏗️ **清晰架构**：模块化设计，文件组织结构合理
- 🛡️ **完善的错误处理**：内置 404/500/501 错误页面
- ⚙️ **集中化配置**：所有配置集中在 `config/config.php` 文件
- 🔧 **环境适配**：支持开发/生产环境快速切换

## 📁 项目结构

```
PHPweb/
├── 📄 .htaccess               # Apache 重写规则
├── 📄 README.md               # 项目文档
├── 📄 index.php               # 首页文件
├── 📄 router.php              # 路由系统核心文件
├── 📄 test.php                # 测试页面
├── 📁 admin/                  # 管理工具目录
│   └── 📄 auto_router.php     # 自动路由生成器
├── 📁 config/                 # 配置文件目录
│   ├── 📄 config.php          # 数据库和站点配置
│   ├── 📄 router.php          # 自定义路由配置
│   └── 📄 .auto_router_config.php  # 自动生成的路由配置
├── 📁 css/                    # 样式文件目录
├── 📁 api/                    # API接口目录
│   ├── 📄 function.php        # 通用函数
│   └── 📄 user.php            # 用户相关函数
├── 📁 include/                # 包含文件目录
│   ├── 📄 _PRE.php            # 预处理函数库
│   ├── 📄 conn.php            # 数据库连接
│   ├── 📄 debug.php           # 调试函数
│   └── 📄 header.php          # 公共头部文件
├── 📁 libs/                   # 第三方库目录
│   └── 📁 Bramus/             # Bramus Router 库
├── 📁 pages/                  # 页面文件目录
└── 📁 page_error/             # 错误页面目录
```

## 🚀 快速开始

### 环境要求

- PHP 7.0+
- Apache 服务器（启用 `mod_rewrite` 模块）
- MySQL 数据库（可选）

### 安装步骤

1.- **克隆项目**
   ```bash
   git clone https://github.com/1246864/PHPweb.git
   cd PHPweb
   ```

2. **配置环境**
   - 编辑 `config/config.php` 设置网站 URL 和调试模式
   - 修改数据库连接参数（如果需要）

3. **配置 Web 服务器**
   - 确保启用 `mod_rewrite` 模块
   - 配置虚拟主机指向项目根目录
   - 确保 `.htaccess` 文件能够正常工作

4. **访问应用**
   在浏览器中访问你的域名或 `http://localhost/PHPweb`

## ⚙️ 配置系统

### 基本配置

所有配置集中在 `config/config.php` 文件中：

- **网站配置**：名称、URL、调试模式
- **数据库配置**：主机、用户名、密码、数据库名、字符集
- **路由配置**：支持四种路由注册方式

## 🛣️ 路由系统

PHPweb 提供了四种路由注册方式，优先级从高到低为：
1. **Bramus Router 原版规则注册**（最高优先级）
2. **配置文件注册**
3. **页面内联自动扫描注册**
4. **后台面板数据库注册**（最低优先级）

### 1. 配置文件注册

在 `config/config.php` 文件中使用 `add_Page()` 函数添加路由：

```php
// 基本路由
add_Page('/', 'index.php');
add_Page('/test', 'test.php');

// 带参数的路由
add_Page('/user/{id}', 'user/profile.php');

// 指定请求方法的路由
add_Page('/api/data', 'api/data.php', 'POST');
```

### 2. 后台面板数据库注册

通过数据库存储路由配置，适合需要动态管理路由的场景：

```sql
-- 数据库表结构示例
CREATE TABLE `sys_routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `method` varchar(10) NOT NULL DEFAULT 'ALL',
  `file_path` varchar(255) NOT NULL,
  `is_enable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
);

-- 插入路由示例
INSERT INTO `sys_routes` (`url`, `method`, `file_path`, `is_enable`) VALUES
('/db-route', 'GET', 'pages/db-route.php', 1);
```

### 3. 页面内联自动扫描注册

在页面文件首行添加路由标记，系统会自动扫描并注册：

```php
<!-- URL {/page/index, GET} -->
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>首页</title>
</head>
<body>
    <h1>页面内容</h1>
</body>
</html>
```

**自动扫描路由**：
```bash
# 访问自动路由生成器
http://localhost/PHPweb/admin/auto_router
```

### 4. Bramus Router 原版规则注册

在 `config/router.php` 文件中使用 Bramus Router 原版 API 注册路由：

```php
// 自定义路由(声明方法遵循Bramus Router)
$router->get('/example', function () {
    include __DIR__ . '/example.php';
});

$router->post('/api/submit', function () {
    // 处理 POST 请求
    echo 'POST 请求已处理';
});

$router->all('/hello', function () {
    echo 'Hello World!';
});
```

### 路由优先级说明

系统会按照以下顺序匹配路由：
1. **Bramus Router 原版规则**：优先级最高，直接绕过其他路由配置
2. **配置文件注册**：通过 `add_Page()` 函数添加的路由
3. **自动扫描注册**：系统自动扫描页面文件生成的路由
4. **数据库注册**：从数据库读取的路由配置

### 路由参数获取

```php
// 在页面中获取路由参数
$params = $_GET['URL'] ?? [];

// 例如：路由为 /user/{id}
// 访问 /user/123 时，$params[0] 为 '123'
```

## 📖 使用指南

### 添加新路由

根据需要选择适合的路由注册方式：

**方法1：配置文件注册**
```php
// 在 config/config.php 中
add_Page('/new-page', 'new-page.php');
add_Page('/product/{id}', 'product/details.php');
```

**方法2：页面内联注册**
```php
// 在页面文件首行添加
<!-- URL {/new-page, GET} -->
<!DOCTYPE html>
<html>
<!-- 页面内容 -->
</html>
```

**方法3：Bramus Router 原版注册**
```php
// 在 config/router.php 中
$router->get('/new-page', function () {
    include __DIR__ . '/../new-page.php';
});
```

### 创建新页面

1. 在项目根目录或子目录中创建页面文件
2. 在配置文件中添加对应的路由
3. 通过 `$_GET['URL']` 访问路由参数

### 数据库操作

```php
// 引入数据库连接
require_once __DIR__ . '/include/conn.php';

// 执行查询
$result = mysqli_query($conn, "SELECT * FROM users");
```

### 数据库路由配置

1. **启用数据库路由**：在 `config/config.php` 中设置
```php
$config['DB_router']['enable'] = true;
$config['DB_router']['table'] = 'sys_routes'; // 路由表名
```

2. **数据库表结构**：
```sql
CREATE TABLE `sys_routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `method` varchar(10) NOT NULL DEFAULT 'ALL',
  `file_path` varchar(255) NOT NULL,
  `is_enable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
);
```
```

## 📄 许可证

本项目采用 MIT 许可证 - 查看 [LICENSE](LICENSE) 文件了解详情。

## 📝 版本更新历史

### 0.3.0 (开发中)
- **主要更新**：实现URL内联文件自动扫描并注册路由功能
- **重要改进**：路由系统逻辑正式完善
- **功能增强**：自动路由生成器支持批量扫描和冲突检测

### 0.2.4
- **功能新增**：实现了基础的用户登录、注册功能
- **优化改进**：添加了用户名/邮箱重复检查

### 0.2.3
- **修复改进**：修复了多个已知Bug

### 0.2.2
- **配置更新**：优化了系统配置结构

### 0.2.1
- **文档完善**：更新了README.md，提供更详细的项目介绍

### 0.2.0
- **核心功能**：集成Bramus Router路由系统
- **架构更新**：重构了路由处理逻辑

### 0.1.0
- **初始版本**：项目基础框架搭建完成
- **核心功能**：实现了基础的页面访问和路由功能

## 🙏 致谢

- [Bramus Router](https://github.com/bramus/router) - 强大的 PHP 路由库
- 所有贡献者和支持者

---

⭐ 如果这个项目对你有帮助，请给它一个星标！