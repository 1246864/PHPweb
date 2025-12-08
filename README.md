# PHPweb 项目

一个简单而强大的 PHP Web 应用程序，基于 Bramus Router 库构建了无感路由系统，实现了现代 PHP 开发的最佳实践。

## 🚀 项目概述

PHPweb 是一个轻量级的 PHP 项目，最初作为学习示例，现已发展成为功能完整的 Web 应用框架。项目展示了如何构建可维护、可扩展的 PHP 应用程序，适合作为学习参考或小型项目的基础。

## ✨ 核心特性

- 🛣️ **无感路由系统**：基于 Bramus Router 库的简化适配，使用更直观
- 🏗️ **清晰架构**：简洁的文件组织和代码结构
- 📱 **响应式设计**：适配各种设备屏幕
- 🛡️ **错误处理**：完善的 404/500/501 错误页面
- ⚙️ **集中化配置**：灵活的环境配置管理
- 🔧 **环境适配**：轻松切换开发/生产环境
- 📦 **模块化设计**：可扩展的功能组件系统

## 📁 项目结构

```
PHPweb/
├── 📄 .htaccess                    # Apache 重写规则
├── 📄 README.md                    # 项目文档
├── 📄 BRAMUS_ROUTER_DOCUMENTATION.md  # Bramus Router 文档
├── 📄 BRAMUS_ROUTER_USAGE.md          # Bramus Router 用法指南
├── 📄 bramus-example.php           # Bramus Router 示例
├── 📄 index.php                    # 首页文件
├── 📄 router.php                   # 路由实现
├── 📄 test.php                     # 测试页面
├── 📁 config/                      # 配置文件目录
│   ├── 📄 .htaccess                # 配置目录保护
│   └── 📄 config.php               # 数据库和站点配置
├── 📁 css/                         # 样式文件目录
│   └── 📄 css.css                  # 主样式文件
├── 📁 function/                    # 函数文件目录
│   ├── 📄 function.php             # 通用函数
│   └── 📄 user.php                 # 用户相关函数
├── 📁 include/                     # 包含文件目录
│   ├── 📄 .htaccess                # 包含目录保护
│   ├── 📄 _PRE.php                 # 预处理函数库
│   ├── 📄 conn.php                 # 数据库连接
│   ├── 📄 debug.php                # 调试函数
│   ├── 📄 header.php               # 头部包含文件
│   └── 📄 test.php                 # 测试函数
├── 📁 libs/                        # 第三方库目录
│   └── 📁 Bramus/                  # Bramus Router 库
│       └── 📁 Router/
│           └── 📄 Router.php       # 路由器核心文件
└── 📁 page_error/                  # 错误页面目录
    ├── 📄 .htaccess                # 错误页面保护
    ├── 📄 404.php                  # 404 错误页面
    ├── 📄 500.php                  # 500 错误页面
    └── 📄 501.php                  # 501 错误页面
```

### 核心文件说明

| 文件/目录 | 描述 |
|----------|------|
| `config/config.php` | 项目配置文件，包含路由、数据库、站点等配置 |
| `router.php` | 路由实现文件，封装了 Bramus Router |
| `include/_PRE.php` | 预处理函数库，提供路由添加和错误处理函数 |
| `libs/Bramus/Router/Router.php` | Bramus Router 核心库文件 |
| `page_error/` | 错误页面目录，包含 404/500/501 错误处理页面 |

## 🛣️ 无感路由系统

PHPweb 项目创新性地采用了基于 Bramus Router 的无感路由系统，通过项目适配，让路由配置和使用更加直观和无感。

### 🏗️ 架构设计

PHPweb 项目的路由系统由以下核心文件组成：

- **`router.php`**：路由核心文件，负责路由的注册和分发
- **`config/config.php`**：路由配置文件，定义 URL 与页面的映射关系
- **`include/_PRE.php`**：路由辅助函数，提供便捷的路由注册接口
- **`libs/Bramus/Router/Router.php`**：Bramus Router 核心库

### 🛠️ 工作流程

1. **URL 重写**：通过 `.htaccess` 文件将所有请求重定向到 `router.php`
2. **路由注册**：`router.php` 加载配置文件并注册所有路由
3. **路由匹配**：Bramus Router 解析请求的 URL 并匹配对应的路由规则
4. **参数解析**：将路由参数转换为 `$_GET['URL']` 数组
5. **页面加载**：根据路由配置加载对应的页面文件

### ✅ 功能特性

- **简单易用**：使用 `add_Page()` 函数即可快速添加路由
- **灵活配置**：支持基本路由、参数路由和条件路由
- **方法支持**：支持所有 HTTP 方法（GET、POST、PUT、DELETE 等）
- **错误处理**：内置 404、500 和 501 错误页面处理
- **调试友好**：提供详细的调试信息和日志

### 📝 路由注册示例

在 `config/config.php` 中，通过简单调用 `add_Page()` 函数即可添加路由：

```php
// 基本路由
add_Page('/', 'index.php'); // 首页
add_Page('/test', 'test.php'); // 测试页面

// 带参数的路由
add_Page('/user/{id}', 'user/profile.php'); // 用户详情页
add_Page('/product/{id}/detail', 'product/detail.php'); // 产品详情页

// 指定请求方法的路由
add_Page('/api/data', 'api/data.php', 'POST'); // POST 请求的 API 接口
add_Page('/api/user/{id}', 'api/user.php', 'GET|PUT|DELETE'); // 支持多种方法的 API 接口
```

### 📊 参数传递

路由参数会自动传递给目标页面，通过 `$_GET['URL']` 数组访问：

```php
// user/profile.php
<?php
// 获取用户ID
$userId = $_GET['URL'][0];

// 显示用户信息
echo "用户ID: $userId";

// product/detail.php
<?php
// 获取产品ID
$productId = $_GET['URL'][0];

// 显示产品详情
echo "产品ID: $productId";
```

### 🔧 适配实现细节

项目通过 `include/_PRE.php` 中的 `add_Page()` 函数对 Bramus Router 进行了适配：

```php
// include/_PRE.php 中的 add_Page() 函数
function add_Page($URL, $PATH, $type = 'GET') {
    global $Page_Array;
    
    // 确保 $Page_Array 存在
    if (!isset($Page_Array)) {
        $Page_Array = array();
    }
    
    // 标准化路径
    $path = str_replace('\\', '/', __DIR__ . '/../' . $PATH);
    
    // 添加路由到全局数组
    $Page_Array[] = array($URL, $path, $type);
}
```

### 🔑 路由核心实现

`router.php` 文件使用 Bramus Router 实现路由的注册和分发：

```php
// router.php
<?php

// 加载配置文件
require_once __DIR__ . '/config/config.php';

// 引入 Bramus Router 库
require __DIR__ . '/libs/Bramus/Router/Router.php';

// 创建路由器实例
$router = new \Bramus\Router\Router();

// 注册所有路由
foreach ($Page_Array as $v) {
    $URL = $v[0];
    $PATH = $v[1];
    $TYPE = isset($v[2]) ? $v[2] : 'GET';
    
    // 根据请求方法注册路由
    $router->$TYPE($URL, function() use ($PATH) {
        // 获取路由参数
        global $router;
        $params = $router->getParams();
        
        // 将参数转换为 $_GET['URL'] 数组
        if (!empty($params)) {
            $_GET['URL'] = array_values($params);
        }
        
        // 加载页面文件
        include $PATH;
    });
}

// 404 错误处理
$router->set404(function() {
    header('HTTP/1.1 404 Not Found');
    include __DIR__ . '/page_error/404.php';
});

// 运行路由器
$router->run();
```

### 🔧 配置说明

1. **基本路由**：`add_Page('/path', 'page.php')`
2. **带参数的路由**：`add_Page('/path/{param}', 'page.php')`
3. **指定请求方法的路由**：`add_Page('/path', 'page.php', 'GET|POST')`
4. **复杂路由**：`add_Page('/path/{param1}/sub/{param2}', 'page.php')`

### 🔑 优势

1. **无感使用**：开发者无需了解 Bramus Router 的内部实现，只需使用 `add_Page()` 函数即可
2. **灵活扩展**：支持所有 Bramus Router 的高级功能
3. **性能优化**：路由匹配效率高，响应速度快
4. **代码简洁**：减少了重复代码，提高了开发效率
5. **易于维护**：路由配置集中管理，便于维护和更新
6. **向后兼容**：可以轻松切换到其他路由库，而不需要修改大量代码

## ⚙️ 配置系统

### 集中化配置管理

所有配置项都集中在 `config/config.php` 文件中：

1. **网站配置**：名称、URL、调试模式等
2. **路由配置**：通过 `add_Page()` 函数添加路由
3. **数据库配置**：连接参数、字符集等
4. **时区配置**：设置应用程序时区
5. **调试配置**：控制调试信息的显示

### 配置示例

```php
<?php
include_once __DIR__ . '/../include/_PRE.php';

// 标记是否引用了该配置文件
if (!isset($config["loaded"])) {
    $config['loaded'] = true;

    // 数据库配置
    $config['db'] = array(
        'host' => 'localhost',      // 数据库主机
        'user' => 'root',           // 数据库用户名
        'password' => 'root',       // 数据库密码
        'database' => 'test',       // 数据库名
        'charset' => 'utf8'         // 数据库字符集
    );

    // 时区配置
    $config['timezone'] = 'PRC';  // 时区设置，如PRC表示中国时区

    // 网站基本配置
    $config['site'] = array(
        'name' => '网站名称',       // 网站名称
        'url' => 'http://localhost/PHPweb',  // 网站URL
        'debug' => true,             // 调试模式，生产环境请设置为false
    );
    
    // 调试配置
    $config['debug'] = array(
        'more_debug' => true,        // 是否启用进阶调试
        'clear_debug' => true        // 是否提炼调试信息(避免调试输出受HTML标签影响)
    );

    // 添加路由
    add_Page('/', 'index.php');
    add_Page('/test', 'test.php');
    add_Page('/user/{id}', 'user/profile.php');
}
```

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

## 🚀 快速开始

### 环境要求

- PHP 7.0+
- Apache 服务器（支持 .htaccess 重写）
- MySQL 数据库（可选，用于数据存储）

### 安装步骤

1. **克隆项目**
   ```bash
   git clone https://github.com/1246864/PHPweb.git
   cd PHPweb
   ```

2. **配置环境**
   - 编辑 `config/config.php` 设置你的配置
   - 修改数据库连接参数（如果需要）
   - 设置网站 URL 和调试模式

3. **设置权限**
   ```bash
   chmod -R 755 .
   ```

4. **配置 Web 服务器**

   **Apache 配置示例：**
   ```apache
   <VirtualHost *:80>
       DocumentRoot /path/to/PHPweb
       ServerName yourdomain.com
       
       <Directory /path/to/PHPweb>
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

   **注意：** 确保启用了 `mod_rewrite` 模块，以便 `.htaccess` 文件能够正常工作。

5. **访问应用**
   在浏览器中访问你的域名或 `http://localhost/PHPweb`

6. **测试路由**
   - 访问首页：`http://localhost/PHPweb/`
   - 访问测试页面：`http://localhost/PHPweb/test`
   - 访问带参数的路由：`http://localhost/PHPweb/user/123`

### 使用 Docker

```dockerfile
FROM php:7.4-apache

# 启用 mod_rewrite
RUN a2enmod rewrite

# 复制项目文件
COPY . /var/www/html/

# 设置权限
RUN chown -R www-data:www-data /var/www/html/

EXPOSE 80
```

```bash
docker build -t phpweb .
docker run -p 8080:80 phpweb
```

## 📖 使用指南

### 添加新路由

在 `config/config.php` 中使用 `add_Page()` 函数添加路由：

```php
// 基本路由
add_Page('/new-page', 'new-page.php');

// 指定请求方法的路由
add_Page('/api/data', 'api/data.php', 'POST');

// 带参数的路由
add_Page('/product/{id}', 'product/details.php');
```

### 创建新页面

1. 在项目根目录或子目录中创建页面文件
2. 在配置文件中添加路由
3. 在页面中可以使用 `$_GET['URL']` 访问路由参数

```php
// product/details.php
<?php
// 获取路由参数
$productId = $_GET['URL'][0];

// 显示产品详情
echo "产品ID: $productId";
```

### 数据库操作

```php
// 引入数据库连接
require_once __DIR__ . '/include/conn.php';

// 执行查询
$result = mysqli_query($conn, "SELECT * FROM users");

// 处理结果
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['name'];
}
```

## 🔧 开发指南

### 代码规范

- 使用有意义的变量和函数名
- 添加适当的注释
- 保持代码简洁和可读性
- 遵循项目现有的代码风格

### 安全最佳实践

1. **输入验证**：始终验证用户输入
2. **SQL 注入防护**：使用预处理语句
3. **XSS 防护**：输出时转义特殊字符
4. **文件权限**：设置适当的文件和目录权限
5. **错误信息**：生产环境中避免显示详细错误信息

## 🧪 测试

### 运行测试

```bash
# 运行 PHP 语法检查
find . -name "*.php" -exec php -l {} \;

# 运行自定义测试
php include/test.php
```

### 测试路由

```bash
# 使用 curl 测试路由
curl http://localhost/PHPweb/
curl http://localhost/PHPweb/test
curl http://localhost/PHPweb/user/123
```

## 📚 文档

- [README.md](README.md) - 项目文档
- [Bramus Router 文档](BRAMUS_ROUTER_DOCUMENTATION.md) - 完整的路由库文档
- [Bramus Router 用法](BRAMUS_ROUTER_USAGE.md) - 实用的用法指南

## 🤝 贡献

欢迎贡献代码！请遵循以下步骤：

1. Fork 本项目
2. 创建特性分支 (`git checkout -b feature/AmazingFeature`)
3. 提交更改 (`git commit -m 'Add some AmazingFeature'`)
4. 推送到分支 (`git push origin feature/AmazingFeature`)
5. 打开 Pull Request

### 贡献指南

- 遵循现有的代码风格
- 添加适当的注释
- 更新相关文档
- 确保代码可以正常运行

## 📋 版本历史

- **v1.0** - 基础项目结构
- **v1.1** - 添加路由系统和 MVC 架构
- **v1.2** - 实现集中化配置管理和环境适配
- **v1.3** - 集成 Bramus Router 路由库
- **v1.4** - 完善文档和示例

## 📄 许可证

本项目采用 MIT 许可证 - 查看 [LICENSE](LICENSE) 文件了解详情。

## 🙏 致谢

- [Bramus Router](https://github.com/bramus/router) - 强大的 PHP 路由库
- 所有贡献者和支持者

## 📞 联系方式

如有问题或建议，请通过以下方式联系：

- 提交 [Issue](https://github.com/1246864/PHPweb/issues)
- 发送邮件至：contact@example.com

---

⭐ 如果这个项目对你有帮助，请给它一个星标！