# PHPweb 项目

一个基于 Bramus Router 库构建的轻量级 PHP Web 应用框架，实现了无感路由系统和现代 PHP 开发最佳实践。当前版本：1.1.0

## 🚀 项目概述

PHPweb 是一个简洁、可扩展的 PHP 项目，适合作为学习参考或小型项目的基础框架。项目展示了如何构建结构清晰、易于维护的 PHP 应用程序，支持用户管理、新闻管理和文件管理等核心功能。

## ✨ 核心特性

- 🛣️ **无感路由系统**：基于 Bramus Router 库，支持四种路由注册方式，实现智能路由匹配
- 🏗️ **清晰架构**：模块化设计，文件组织结构合理，易于扩展和维护
- 👤 **用户管理系统**：完善的用户认证、权限管理和用户信息操作功能
- 📰 **新闻管理系统**：支持新闻的创建、查询、更新和删除操作
- 📁 **文件管理系统**：增强的文件映射和同步功能
- 📦 **类封装设计**：独立的 User、File、News 类，便于维护和扩展
- 🔄 **统一路径管理**：使用 MAIN_PATH 常量统一管理路径，避免路径引用错误
- 🛡️ **完善的错误处理**：内置 404/500/501 错误页面，增强的路由错误处理
- ⚙️ **集中化配置**：所有配置集中在 `config/config.php` 文件，便于管理
- 🔧 **环境适配**：支持开发/生产环境快速切换，优化的路由匹配算法

## 📁 项目结构

```
PHPweb/
├── 📄 .htaccess               # Apache 重写规则
├── 📄 .main_router.php        # 主路由文件
├── 📄 index.php               # 首页文件
├── 📄 LICENSE                 # MIT 许可证文件
├── 📄 README.md               # 项目文档
├── 📁 .git/                   # Git 版本控制
├── 📁 admin/                  # 后台管理目录
│   └── 📄 auto_router.php     # 自动路由生成器
├── 📁 config/                 # 配置文件目录
│   ├── 📄 .auto_router_config.php  # 自动路由配置
│   ├── 📄 config.php          # 数据库和站点配置
│   └── 📄 router.php          # 路由配置
├── 📁 database/               # 数据库文件目录
├── 📁 files/                  # 文件存储目录
├── 📁 include/                # 包含文件目录
│   ├── 📄 _PRE.php            # 预处理函数库（含 MAIN_PATH 定义）
│   ├── 📄 conn.php            # 数据库连接
│   └── 📄 debug.php           # 调试函数
├── 📁 libs/                   # 库目录
│   ├── 📁 Bramus/             # Bramus Router 库
│   │   └── 📁 Router/         # 路由库核心代码
│   ├── 📁 function/           # 功能函数
│   │   ├── 📁 class/          # 类文件
│   │   │   ├── 📄 File.php    # 文件管理类
│   │   │   ├── 📄 News.php    # 新闻管理类
│   │   │   └── 📄 User.php    # 用户管理类
│   │   ├── 📄 file.php        # 文件管理函数
│   │   ├── 📄 function.php    # 通用函数
│   │   ├── 📄 news.php        # 新闻管理函数
│   │   └── 📄 user.php        # 用户管理函数
│   └── 📁 page_error/         # 错误页面
│       ├── 📄 404.php         # 404 错误页面
│       ├── 📄 500.php         # 500 错误页面
│       └── 📄 501.php         # 501 错误页面
└── 📁 pages/                  # 页面文件目录
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

支持四种路由注册方式，优先级从高到低：

**方法1：Bramus Router 原版注册**
```php
// 在 config/router.php 中
$router->get('/new-page', function () {
    include __DIR__ . '/../new-page.php';
});
```

**方法2：配置文件注册**
```php
// 在 config/config.php 中
add_Page('/new-page', 'new-page.php');

// 带参数的路由
add_Page('/product/{id}', 'product/details.php');
```

**方法3：页面内联注册**
```php
// 在页面文件首行添加
<!-- URL {/new-page, GET} -->
<!DOCTYPE html>
<html>
<!-- 页面内容 -->
</html>
```

**方法4：自动路由扫描**
```php
// 运行自动路由生成器
php admin/auto_router.php
```

### 创建新页面

1. 在项目根目录或子目录中创建页面文件
2. 选择上述四种路由注册方式之一添加路由
3. 通过 `$_GET['URL']` 访问路由参数

### 数据库操作

```php
// 引入数据库连接
require_once __DIR__ . '/include/conn.php';

// 执行查询
$result = mysqli_query($conn, "SELECT * FROM users");
```

### 使用类库

```php
// 引入 User 类
require_once __DIR__ . '/libs/function/class/User.php';

// 创建用户对象
$user = new User();

// 使用用户方法
$userInfo = $user->getUserById(1);
```

## 📄 许可证

本项目采用 MIT 许可证 - 查看 [LICENSE](LICENSE) 文件了解详情。

## 📝 版本更新历史

详细的版本更新历史请查看 [CHANGELOG.md](CHANGELOG.md) 文件。

## 🔗 相关链接

- [GitHub 仓库](https://github.com/1246864/PHPweb)
- [更新日志](CHANGELOG.md)
- [许可证](LICENSE)
- [发布说明](RELEASE_NOTE_1.1.0.md)

## 🙏 致谢

- [Bramus Router](https://github.com/bramus/router) - 强大的 PHP 路由库
- 所有贡献者和支持者

---

⭐ 如果这个项目对你有帮助，请给它一个星标！
