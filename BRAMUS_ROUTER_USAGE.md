# Bramus Router 用法指南

## 简介

[Bramus Router](https://github.com/bramus/router) 是一个轻量级的、面向对象的 PHP 路由库。它提供了简单而强大的路由功能，支持多种 HTTP 方法和灵活的路由模式。

## 安装

本项目已将 Bramus Router 集成到 `libs/Bramus/Router/` 目录中，无需额外安装。

## 基本用法

### 1. 创建路由器实例

```php
require_once __DIR__ . '/libs/Bramus/Router/Router.php';

$router = new \Bramus\Router\Router();
```

### 2. 设置基础路径

```php
$router->setBasePath('/your-app-path');
```

### 3. 定义路由

#### 静态路由

```php
$router->get('/', function() {
    echo '首页';
});

$router->get('/about', function() {
    echo '关于页面';
});
```

#### 动态路由（带参数）

```php
$router->get('/user/(\d+)', function($id) {
    echo "用户ID: $id";
});

$router->get('/post/(\d+)/comment/(\d+)', function($postId, $commentId) {
    echo "文章ID: $postId, 评论ID: $commentId";
});
```

#### 多种 HTTP 方法

```php
$router->get('/users', function() {
    // 获取用户列表
});

$router->post('/users', function() {
    // 创建用户
});

$router->put('/users/(\d+)', function($id) {
    // 更新用户
});

$router->delete('/users/(\d+)', function($id) {
    // 删除用户
});

// 支持多种方法
$router->match('GET|POST', '/contact', function() {
    // 处理联系表单
});

// 所有方法
$router->all('/test', function() {
    echo '测试';
});
```

## 高级功能

### 1. 路由组

```php
$router->mount('/admin', function() use ($router) {
    $router->get('/', function() {
        echo '管理面板';
    });
    
    $router->get('/users', function() {
        echo '用户管理';
    });
    
    $router->get('/settings', function() {
        echo '系统设置';
    });
});
```

### 2. API 路由组

```php
$router->mount('/api', function() use ($router) {
    $router->mount('/v1', function() use ($router) {
        $router->get('/users', function() {
            header('Content-Type: application/json');
            echo json_encode(['users' => []]);
        });
        
        $router->post('/users', function() {
            header('Content-Type: application/json');
            echo json_encode(['message' => '用户创建成功']);
        });
    });
});
```

### 3. 中间件

#### 路由中间件

```php
$router->before('GET|POST', '/admin/.*', function() {
    if (!isset($_SESSION['admin'])) {
        header('HTTP/1.1 403 Forbidden');
        exit();
    }
});
```

#### 全局中间件

```php
$router->before('GET', '/.*', function() {
    // 记录访问日志
    error_log('访问: ' . $_SERVER['REQUEST_URI']);
});
```

#### 后置中间件

```php
$router->run(function() {
    echo '<!-- 全局页脚 -->';
});
```

### 4. 控制器调用

```php
// 调用控制器方法
$router->get('/user/(\d+)', 'UserController@showProfile');

// 带命名空间
$router->get('/user/(\d+)', '\App\Controllers\User@showProfile');

// 设置默认命名空间
$router->setNamespace('\App\Controllers');
$router->get('/users/(\d+)', 'User@showProfile');
```

### 5. 错误处理

```php
// 自定义404处理
$router->set404(function() {
    header('HTTP/1.1 404 Not Found');
    echo '<h1>404 - 页面未找到</h1>';
});

// 特定路径的404处理
$router->set404('/api(/.*)?', function() {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'API路由未找到']);
});

// 手动触发404
$router->get('/user/(\d+)', function($id) use ($router) {
    if (!User::exists($id)) {
        $router->trigger404();
        return;
    }
    // 显示用户信息
});
```

## 完整示例

```php
<?php
require_once __DIR__ . '/libs/Bramus/Router/Router.php';

$router = new \Bramus\Router\Router();
$router->setBasePath('/PHPweb');

// 404处理
$router->set404(function() {
    header('HTTP/1.1 404 Not Found');
    echo '<h1>404 - 页面未找到</h1>';
});

// 中间件
$router->before('GET', '/.*', function() {
    session_start();
});

// 首页
$router->get('/', function() {
    echo '<h1>首页</h1>';
    echo '<a href="/PHPweb/about">关于</a> | ';
    echo '<a href="/PHPweb/user/123">用户</a> | ';
    echo '<a href="/PHPweb/api/users">API</a>';
});

// 关于页面
$router->get('/about', function() {
    echo '<h1>关于我们</h1>';
    echo '<a href="/PHPweb">返回首页</a>';
});

// 用户路由
$router->mount('/user', function() use ($router) {
    $router->get('/', function() {
        echo '<h1>用户列表</h1>';
        echo '<a href="/PHPweb">返回首页</a>';
    });
    
    $router->get('/(\d+)', function($id) {
        echo "<h1>用户详情: $id</h1>";
        echo '<a href="/PHPweb/user">返回用户列表</a>';
    });
});

// API路由
$router->mount('/api', function() use ($router) {
    $router->get('/users', function() {
        header('Content-Type: application/json');
        echo json_encode([
            ['id' => 1, 'name' => '张三'],
            ['id' => 2, 'name' => '李四']
        ]);
    });
});

// 运行路由器
$router->run();
?>
```

## 路由模式说明

### 正则表达式模式

Bramus Router 使用 PCRE (Perl Compatible Regular Expressions) 进行路由匹配：

- `(\d+)` - 匹配一个或多个数字
- `(\w+)` - 匹配一个或多个字母数字字符
- `([a-z0-9_-]+)` - 匹配小写字母、数字、下划线和连字符
- `(.*)` - 匹配任意字符（包括斜杠）
- `([^/]+)` - 匹配除斜杠外的任意字符

### 可选参数

```php
$router->get('/blog(/\d{4})?(/\d{2})?', function($year = null, $month = null) {
    if (!$year) {
        echo '博客首页';
    } elseif (!$month) {
        echo "$year 年的博客";
    } else {
        echo "$year 年 $month 月的博客";
    }
});
```

## 最佳实践

1. **路由顺序**：将最常用的路由放在前面
2. **中间件**：使用中间件处理认证、日志等横切关注点
3. **路由组织**：使用路由组组织相关的路由
4. **错误处理**：始终提供友好的404页面
5. **安全性**：验证和过滤路由参数

## 注意事项

1. 路由参数会作为字符串传递，需要时进行类型转换
2. 使用 `exit()` 会阻止后置中间件的执行
3. HEAD 请求会自动转换为 GET 请求并抑制输出
4. 确保服务器配置了正确的 URL 重写规则

## 本项目集成说明

- 库文件位置：`libs/Bramus/Router/Router.php`
- 示例文件：`bramus-example.php`
- 基础路径：`/PHPweb`

访问 `bramus-example.php` 可以看到完整的示例演示。

---

*更多详细信息请参考 [Bramus Router 官方文档](https://github.com/bramus/router)*