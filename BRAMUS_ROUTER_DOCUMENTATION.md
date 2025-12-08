# Bramus Router 完整文档

## 概述

Bramus Router 是一个轻量级、简单而强大的面向对象 PHP 路由库。它提供了灵活的路由系统，支持多种 HTTP 方法、动态路由、中间件等功能，是构建现代 PHP Web 应用的理想选择。

## 特性

- ✅ 支持 `GET`、`POST`、`PUT`、`DELETE`、`OPTIONS`、`PATCH` 和 `HEAD` 请求方法
- ✅ 路由简写方法：`get()`、`post()`、`put()` 等
- ✅ 静态路由模式
- ✅ 动态路由模式：基于 PCRE 的动态路由或基于占位符的动态路由
- ✅ 可选路由子模式
- ✅ 支持 `X-HTTP-Method-Override` 头部
- ✅ 子路由/路由挂载
- ✅ 允许 `Class@Method` 调用
- ✅ 自定义 404 处理
- ✅ 路由前中间件
- ✅ 路由后中间件
- ✅ 在子文件夹中正常工作

## 系统要求

- PHP 5.3 或更高版本
- URL 重写支持（Apache 的 mod_rewrite 或 Nginx 的重写规则）

## 安装

### 通过 Composer 安装（推荐）

```bash
composer require bramus/router ~1.6
```

### 手动安装

1. 下载源代码
2. 将 `src/Bramus/Router/Router.php` 复制到项目中
3. 在代码中引入文件

```php
require_once __DIR__ . '/path/to/Router.php';
```

## 快速开始

### 基本使用

```php
<?php
// 引入路由器
require_once __DIR__ . '/libs/Bramus/Router/Router.php';

// 创建路由器实例
$router = new \Bramus\Router\Router();

// 定义路由
$router->get('/', function() {
    echo 'Hello World!';
});

$router->get('/about', function() {
    echo 'About Page';
});

// 运行路由器
$router->run();
?>
```

### 设置基础路径

```php
$router = new \Bramus\Router\Router();

// 手动设置基础路径
$router->setBasePath('/myapp');

// 或让路由器自动检测（默认行为）
// $router = new \Bramus\Router\Router();
```

## 路由定义

### HTTP 方法支持

```php
// GET 请求
$router->get('/users', function() {
    // 获取用户列表
});

// POST 请求
$router->post('/users', function() {
    // 创建用户
});

// PUT 请求
$router->put('/users/(\d+)', function($id) {
    // 更新用户
});

// DELETE 请求
$router->delete('/users/(\d+)', function($id) {
    // 删除用户
});

// PATCH 请求
$router->patch('/users/(\d+)', function($id) {
    // 部分更新用户
});

// OPTIONS 请求
$router->options('/users', function() {
    // 返回允许的方法
});

// 多种方法
$router->match('GET|POST', '/contact', function() {
    // 处理联系表单
});

// 所有方法
$router->all('/test', function() {
    echo '测试页面';
});
```

### 静态路由

静态路由不包含动态部分，必须精确匹配：

```php
$router->get('/about', function() {
    echo '关于我们';
});

$router->get('/contact', function() {
    echo '联系我们';
});
```

### 动态路由

#### 基于 PCRE 的动态路由

使用正则表达式定义动态部分：

```php
// 匹配数字
$router->get('/user/(\d+)', function($id) {
    echo "用户ID: $id";
});

// 匹配字母数字字符
$router->get('/profile/(\w+)', function($username) {
    echo "用户名: $username";
});

// 匹配小写字母、数字、下划线和连字符
$router->get('/category/([a-z0-9_-]+)', function($category) {
    echo "分类: $category";
});
```

#### 常用 PCRE 模式

- `\d+` - 一个或多个数字 (0-9)
- `\w+` - 一个或多个字母数字字符 (a-z 0-9 _)
- `[a-z0-9_-]+` - 小写字母、数字、下划线和连字符
- `.*` - 任意字符（包括斜杠），零个或多个
- `[^/]+` - 除斜杠外的任意字符，一个或多个

#### 多参数路由

```php
$router->get('/movie/(\d+)/photo/(\d+)', function($movieId, $photoId) {
    echo "电影 #$movieId, 照片 #$photoId";
});
```

### 可选路由参数

```php
// 博客路由示例
$router->get('/blog(/\d{4})?(/\d{2})?(/\d{2})?(/[a-z0-9_-]+)?', 
    function($year = null, $month = null, $day = null, $slug = null) {
        if (!$year) {
            echo '博客首页';
        } elseif (!$month) {
            echo "$year 年的博客";
        } elseif (!$day) {
            echo "$year 年 $month 月的博客";
        } elseif (!$slug) {
            echo "$year 年 $month 月 $day 日的博客";
        } else {
            echo "文章: $slug ($year-$month-$day)";
        }
    }
);
```

**注意：** 使用可选参数时，建议使用嵌套的可选参数结构：

```php
// 推荐的嵌套结构
$router->get('/blog(/\d{4}(/\d{2}(/\d{2}(/[a-z0-9_-]+)?)?)?)?', 
    function($year = null, $month = null, $day = null, $slug = null) {
        // 处理逻辑
    }
);
```

## 路由组

### 基本路由组

使用 `mount()` 方法创建路由组：

```php
$router->mount('/admin', function() use ($router) {
    // 对应 /admin/
    $router->get('/', function() {
        echo '管理面板首页';
    });
    
    // 对应 /admin/users
    $router->get('/users', function() {
        echo '用户管理';
    });
    
    // 对应 /admin/settings
    $router->get('/settings', function() {
        echo '系统设置';
    });
});
```

### 嵌套路由组

```php
$router->mount('/api', function() use ($router) {
    $router->mount('/v1', function() use ($router) {
        // API v1 路由
        $router->get('/users', function() {
            echo 'API v1 用户列表';
        });
    });
    
    $router->mount('/v2', function() use ($router) {
        // API v2 路由
        $router->get('/users', function() {
            echo 'API v2 用户列表';
        });
    });
});
```

## 控制器集成

### Class@Method 语法

```php
// 调用控制器方法
$router->get('/user/(\d+)', 'UserController@showProfile');

// 带命名空间
$router->get('/user/(\d+)', '\App\Controllers\User@showProfile');
```

### 设置默认命名空间

```php
// 设置默认命名空间
$router->setNamespace('\App\Controllers');

// 现在可以直接使用类名
$router->get('/users/(\d+)', 'User@showProfile');
$router->get('/cars/(\d+)', 'Car@showProfile');
```

### 控制器示例

```php
namespace App\Controllers;

class UserController {
    // 静态方法（推荐）
    public static function showProfile($id) {
        echo "用户资料: $id";
    }
    
    // 非静态方法（会自动实例化）
    public function edit($id) {
        echo "编辑用户: $id";
    }
}
```

## 中间件

### 路由前中间件

在路由处理之前执行：

```php
// 特定路由的中间件
$router->before('GET|POST', '/admin/.*', function() {
    if (!isset($_SESSION['admin'])) {
        header('HTTP/1.1 403 Forbidden');
        echo '访问被拒绝';
        exit();
    }
});

// 全局中间件
$router->before('GET', '/.*', function() {
    // 记录所有 GET 请求
    error_log('GET 请求: ' . $_SERVER['REQUEST_URI']);
});
```

### 路由后中间件

在路由处理之后执行：

```php
$router->run(function() {
    echo '<!-- 页面footer -->';
});
```

**注意：** 如果路由处理函数中调用了 `exit()`，后置中间件不会执行。

## 错误处理

### 自定义 404 处理

```php
// 全局 404 处理
$router->set404(function() {
    header('HTTP/1.1 404 Not Found');
    echo '<h1>404 - 页面未找到</h1>';
});

// 特定路径的 404 处理
$router->set404('/api(/.*)?', function() {
    header('HTTP/1.1 404 Not Found');
    header('Content-Type: application/json');
    echo json_encode([
        'error' => 'API 路由未找到',
        'code' => 404
    ]);
});

// 使用控制器方法处理 404
$router->set404('\App\Controllers\Error@notFound');
```

### 手动触发 404

```php
$router->get('/user/(\d+)', function($id) use ($router) {
    if (!User::exists($id)) {
        $router->trigger404();
        return;
    }
    // 显示用户信息
});
```

## 特殊功能

### PUT 请求处理

PHP 没有 `$_PUT` 超全局变量，需要手动处理：

```php
$router->put('/user/(\d+)', function($id) {
    // 模拟 $_PUT
    $_PUT = [];
    parse_str(file_get_contents('php://input'), $_PUT);
    
    echo "更新用户 $id";
    print_r($_PUT);
});
```

### HEAD 请求处理

HEAD 请求会自动转换为 GET 请求并抑制输出，符合 RFC2616 规范。

### HTTP 方法覆盖

支持使用 `X-HTTP-Method-Override` 头部覆盖请求方法：

```php
// 当原始请求为 POST 时，可以通过头部覆盖
// X-HTTP-Method-Override: PUT
// 允许的值：PUT、DELETE、PATCH
```

## 集成其他库

使用 `use` 关键字传递依赖到路由处理函数：

```php
// 使用模板引擎
$tpl = new \Acme\Template\Template();

$router->get('/', function() use ($tpl) {
    $tpl->load('home.tpl');
    $tpl->setdata([
        'name' => 'Bramus!'
    ]);
});

$router->run(function() use ($tpl) {
    $tpl->display();
});
```

## 最佳实践

### 1. 路由组织

- 按功能模块组织路由
- 使用路由组减少重复代码
- 将最常用的路由放在前面

### 2. 参数验证

```php
$router->get('/user/(\d+)', function($id) {
    // 验证参数
    if (!is_numeric($id) || $id <= 0) {
        $router->trigger404();
        return;
    }
    // 处理逻辑
});
```

### 3. 中间件使用

- 使用中间件处理认证、授权
- 使用中间件记录日志
- 保持中间件轻量级

### 4. 错误处理

- 始终提供友好的 404 页面
- 为 API 提供适当的错误响应
- 记录错误日志

## 性能考虑

1. **路由顺序**：将最常用的路由放在前面
2. **正则表达式**：避免过于复杂的正则表达式
3. **中间件**：保持中间件轻量级
4. **缓存**：考虑缓存路由解析结果

## 故障排除

### 常见问题

1. **路由不匹配**
   - 检查基础路径设置
   - 验证正则表达式语法
   - 确认 HTTP 方法匹配

2. **参数传递错误**
   - 确保正则表达式有捕获组
   - 检查参数顺序

3. **中间件不执行**
   - 验证中间件注册位置
   - 检查路由匹配条件

### 调试技巧

```php
// 启用调试模式
$router->before('GET', '/.*', function() {
    error_log('当前路由: ' . $_SERVER['REQUEST_URI']);
    error_log('请求方法: ' . $_SERVER['REQUEST_METHOD']);
});
```

## 许可证

Bramus Router 使用 MIT 公共许可证发布。

---

*更多信息和示例请参考 [Bramus Router 官方仓库](https://github.com/bramus/router)*