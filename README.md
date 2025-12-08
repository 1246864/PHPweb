# PHPweb 项目

一个简单而强大的 PHP Web 应用程序，演示了现代 PHP 开发的最佳实践，包括路由系统、MVC 架构和集中化配置管理。

## 🚀 项目概述

PHPweb 是一个轻量级的 PHP 项目，最初作为学习示例，现已发展成为功能完整的 Web 应用框架。项目展示了如何构建可维护、可扩展的 PHP 应用程序，适合作为学习参考或小型项目的基础。

## ✨ 核心特性

- 🛣️ **双重路由系统**：自研路由系统 + Bramus Router 集成
- 🏗️ **MVC 架构**：清晰的模型-视图-控制器分离
- 📱 **响应式设计**：适配各种设备屏幕
- 🛡️ **错误处理**：完善的 404/500 错误页面
- 👥 **用户管理**：基础的用户认证和管理功能
- ⚙️ **集中化配置**：灵活的环境配置管理
- 🔧 **环境适配**：轻松切换开发/生产环境
- 📦 **模块化设计**：可扩展的控制器和组件系统

## 📁 项目结构

```
PHPweb/
├── 📄 .htaccess                    # Apache 重写规则
├── 📄 index.php                    # 前端控制器（统一入口）
├── 📄 README.md                    # 项目文档
├── 📄 bramus-example.php           # Bramus Router 示例
├── 📁 config/                      # 配置文件目录
│   ├── 📄 .htaccess                # 配置目录保护
│   └── 📄 config.php               # 数据库和站点配置
├── 📁 controllers/                 # 控制器目录
│   ├── 📄 HomeController.php      # 首页控制器
│   ├── 📄 UserController.php       # 用户控制器
│   └── 📄 DemoController.php       # 演示控制器
├── 📁 css/                         # 样式文件目录
│   └── 📄 css.css                  # 主样式文件
├── 📁 include/                     # 包含文件目录
│   ├── 📄 _PRE.php                 # 预处理库
│   ├── 📄 conn.php                 # 数据库连接
│   ├── 📄 function.php             # 通用函数
│   ├── 📄 header.php               # 头部包含文件
│   ├── 📄 router.php               # 自研路由系统核心
│   ├── 📄 test.php                 # 测试文件
│   └── 📄 user.php                 # 用户相关函数
├── 📁 libs/                        # 第三方库目录
│   └── 📁 Bramus/                  # Bramus Router 库
│       └── 📁 Router/
│           └── 📄 Router.php       # 路由器核心文件
├── 📁 page_error/                  # 错误页面目录
│   ├── 📄 .htaccess                # 错误页面保护
│   ├── 📄 404.php                  # 404 错误页面
│   └── 📄 500.php                  # 500 错误页面
└── 📄 docs/                        # 文档目录
    ├── 📄 BRAMUS_ROUTER_DOCUMENTATION.md  # Bramus Router 文档
    ├── 📄 BRAMUS_ROUTER_USAGE.md          # Bramus Router 用法指南
    └── 📄 config.example.md               # 配置示例文档
```

## 🛣️ 路由系统

### 自研路由系统

项目包含一个自研的轻量级路由系统，位于 `include/router.php`，特点：

- 支持静态和动态路由
- 配置驱动的路由映射
- 自动控制器实例化
- 灵活的参数传递

#### 路由配置示例

```php
$config['routes'] = [
    '' => 'HomeController@index',
    'user/profile' => 'UserController@profile',
    'demo/user/{id}' => 'DemoController@userProfile',
];
```

### Bramus Router 集成

项目集成了 [Bramus Router](https://github.com/bramus/router) 库，提供了更强大的路由功能：

- 支持所有 HTTP 方法
- 路由组和子路由
- 中间件支持
- 自定义 404 处理
- Class@Method 调用

#### 使用示例

```php
require_once __DIR__ . '/libs/Bramus/Router/Router.php';

$router = new \Bramus\Router\Router();
$router->setBasePath('/PHPweb');

$router->get('/', function() {
    echo '首页';
});

$router->get('/user/(\d+)', function($id) {
    echo "用户ID: $id";
});

$router->run();
```

## ⚙️ 配置系统

### 集中化配置管理

所有配置项都集中在 `config/config.php` 文件中：

1. **网站配置**：名称、URL、调试模式等
2. **路由配置**：URL到控制器方法的映射
3. **控制器配置**：目录、命名空间等
4. **数据库配置**：连接参数、字符集等
5. **安全配置**：CSRF、XSS、SQL注入保护
6. **会话配置**：生命周期、安全设置

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
- MySQL 数据库（可选）

### 安装步骤

1. **克隆项目**
   ```bash
   git clone https://github.com/1246864/PHPweb.git
   cd PHPweb
   ```

2. **配置环境**
   ```bash
   cp config.example.md config/config.php
   # 编辑 config/config.php 设置你的配置
   ```

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

5. **访问应用**
   在浏览器中访问你的域名或 `http://localhost/PHPweb`

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

## 📖 使用指南

### 添加新路由

1. **自研路由系统**：
   在 `config/config.php` 中添加路由规则：
   ```php
   $config['routes']['new/page'] = 'NewController@methodName';
   ```

2. **Bramus Router**：
   在代码中定义路由：
   ```php
   $router->get('/new/page', function() {
       // 处理逻辑
   });
   ```

### 添加新控制器

1. 在 `controllers/` 目录下创建新的控制器文件
2. 实现控制器类和方法
3. 在路由配置中引用

```php
class NewController {
    public function methodName() {
        global $config;
        $siteName = $config['site']['name'];
        // 控制器逻辑
    }
}
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

- 遵循 PSR-4 自动加载标准
- 使用有意义的变量和函数名
- 添加适当的注释
- 保持代码简洁和可读性

### 安全最佳实践

1. **输入验证**：始终验证用户输入
2. **SQL 注入防护**：使用预处理语句
3. **XSS 防护**：输出时转义特殊字符
4. **CSRF 保护**：实现 CSRF 令牌验证
5. **文件权限**：设置适当的文件和目录权限

### 性能优化

1. **缓存**：实现适当的缓存策略
2. **数据库优化**：使用索引和优化查询
3. **资源压缩**：压缩 CSS 和 JavaScript
4. **CDN**：使用内容分发网络

## 🧪 测试

### 运行测试

```bash
# 运行 PHP 语法检查
find . -name "*.php" -exec php -l {} \;

# 运行自定义测试
php include/test.php
```

### 测试覆盖

项目包含基本的测试文件 `include/test.php`，可以扩展添加更多测试用例。

## 📚 文档

- [配置指南](config.example.md) - 详细的配置说明
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
- 添加适当的测试
- 更新相关文档
- 确保所有测试通过

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