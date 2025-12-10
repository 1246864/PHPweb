# PHPweb 项目

一个基于 Bramus Router 库构建的轻量级 PHP Web 应用框架，实现了无感路由系统和现代 PHP 开发最佳实践。

## 🚀 项目概述

PHPweb 是一个简洁、可扩展的 PHP 项目，适合作为学习参考或小型项目的基础框架。项目展示了如何构建结构清晰、易于维护的 PHP 应用程序。

## ✨ 核心特性

- 🛣️ **无感路由系统**：基于 Bramus Router 库，使用直观的 `add_Page()` 函数快速配置路由
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
├── 📄 router.php              # 路由实现
├── 📄 test.php                # 测试页面
├── 📁 config/                 # 配置文件目录
│   └── 📄 config.php          # 数据库和站点配置
├── 📁 css/                    # 样式文件目录
├── 📁 function/               # 函数文件目录
│   ├── 📄 function.php        # 通用函数
│   └── 📄 user.php            # 用户相关函数
├── 📁 include/                # 包含文件目录
│   ├── 📄 _PRE.php            # 预处理函数库
│   ├── 📄 conn.php            # 数据库连接
│   └── 📄 debug.php           # 调试函数
├── 📁 libs/                   # 第三方库目录
│   └── 📁 Bramus/             # Bramus Router 库
└── 📁 page_error/             # 错误页面目录
```

## 🚀 快速开始

### 环境要求

- PHP 7.0+
- Apache 服务器（启用 `mod_rewrite` 模块）
- MySQL 数据库（可选）

### 安装步骤

1. **克隆项目**
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
- **路由配置**：通过 `add_Page()` 函数添加路由

### 路由配置示例

```php
// 基本路由
add_Page('/', 'index.php');
add_Page('/test', 'test.php');

// 带参数的路由
add_Page('/user/{id}', 'user/profile.php');

// 指定请求方法的路由
add_Page('/api/data', 'api/data.php', 'POST');
```

## 📖 使用指南

### 添加新路由

在 `config/config.php` 中使用 `add_Page()` 函数添加路由：

```php
// 基本路由
add_Page('/new-page', 'new-page.php');

// 带参数的路由
add_Page('/product/{id}', 'product/details.php');
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

## 📄 许可证

本项目采用 MIT 许可证 - 查看 [LICENSE](LICENSE) 文件了解详情。

## 🙏 致谢

- [Bramus Router](https://github.com/bramus/router) - 强大的 PHP 路由库
- 所有贡献者和支持者

---

⭐ 如果这个项目对你有帮助，请给它一个星标！