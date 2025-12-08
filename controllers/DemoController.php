<?php
// 引入预处理库
include_once __DIR__ . '/../include/_PRE.php';
include_once __DIR__ . '/../include/header.php';

/**
 * 演示控制器 - 展示如何在路由系统中处理GET参数
 * 
 * 这个控制器演示了几种不同的GET参数处理方式：
 * 1. 传统查询字符串参数
 * 2. 路由参数（动态路由）
 * 3. 混合使用
 */
class DemoController {
    
    /**
     * 演示传统GET参数 - 查询字符串方式
     * 
     * 访问方式：/demo/get?id=123&name=test
     * 
     * 这种方式是传统的URL参数传递，参数出现在URL的?后面
     * 路由系统会保持这些参数不变，可以直接通过$_GET获取
     */
    public function getParams() {
        global $config;
        $siteName = $config['site']['name'];
        $pageTitle = 'GET参数演示';
        
        // 获取传统GET参数
        $id = isset($_GET['id']) ? $_GET['id'] : '未提供';
        $name = isset($_GET['name']) ? $_GET['name'] : '未提供';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        
        ?>
        <!DOCTYPE html>
        <html lang="zh-CN">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $pageTitle . $config['site']['title_separator'] . $siteName; ?></title>
            <link rel="stylesheet" href="/PHPweb/css/css.css">
        </head>
        <body>
            <header>
                <h1><?php echo $siteName; ?></h1>
                <nav>
                    <a href="/PHPweb/">首页</a>
                    <a href="/PHPweb/demo/get">GET参数演示</a>
                    <a href="/PHPweb/demo/route">路由参数演示</a>
                    <a href="/PHPweb/demo/mixed">混合演示</a>
                </nav>
            </header>
            
            <main>
                <h2>传统GET参数演示</h2>
                <p>这个页面演示如何在路由系统中处理传统的GET参数。</p>
                
                <h3>当前获取到的参数：</h3>
                <ul>
                    <li><strong>ID:</strong> <?php echo htmlspecialchars($id); ?></li>
                    <li><strong>名称:</strong> <?php echo htmlspecialchars($name); ?></li>
                    <li><strong>页码:</strong> <?php echo htmlspecialchars($page); ?></li>
                </ul>
                
                <h3>测试链接：</h3>
                <ul>
                    <li><a href="/PHPweb/demo/get?id=123&name=test">/demo/get?id=123&name=test</a></li>
                    <li><a href="/PHPweb/demo/get?id=456&name=example&page=2">/demo/get?id=456&name=example&page=2</a></li>
                    <li><a href="/PHPweb/demo/get">/demo/get (无参数)</a></li>
                </ul>
                
                <h3>代码示例：</h3>
                <pre><code>// 获取GET参数
$id = isset($_GET['id']) ? $_GET['id'] : '默认值';
$name = $_GET['name'] ?? '默认值'; // PHP 7+ 语法

// 安全处理 - 防止XSS攻击
$safeId = htmlspecialchars($id, ENT_QUOTES, 'UTF-8');</code></pre>
                
                <h3>注意事项：</h3>
                <ul>
                    <li>始终检查参数是否存在（使用isset或??运算符）</li>
                    <li>对输出进行HTML转义防止XSS攻击</li>
                    <li>对数值参数进行类型验证</li>
                    <li>路由系统不会影响传统GET参数的获取</li>
                </ul>
            </main>
            
            <footer>
                <p>&copy; <?php echo date('Y'); ?> <?php echo $siteName; ?></p>
            </footer>
        </body>
        </html>
        <?php
    }
    
    /**
     * 演示动态路由参数
     * 
     * 需要在config/config.php中添加动态路由：
     * 'demo/user/{id}' => 'DemoController@userProfile'
     * 'demo/product/{category}/{id}' => 'DemoController@productDetail'
     * 
     * 访问方式：/demo/user/123 或 /demo/product/electronics/456
     * 
     * 这种方式将参数作为URL路径的一部分，更加SEO友好
     */
    public function userProfile($userId = null) {
        global $config;
        $siteName = $config['site']['name'];
        $pageTitle = '路由参数演示';
        
        // $userId 是通过动态路由传递的参数
        // 如果没有提供参数，使用默认值
        $id = $userId ?? '未提供';
        
        ?>
        <!DOCTYPE html>
        <html lang="zh-CN">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $pageTitle . $config['site']['title_separator'] . $siteName; ?></title>
            <link rel="stylesheet" href="/PHPweb/css/css.css">
        </head>
        <body>
            <header>
                <h1><?php echo $siteName; ?></h1>
                <nav>
                    <a href="/PHPweb/">首页</a>
                    <a href="/PHPweb/demo/get">GET参数演示</a>
                    <a href="/PHPweb/demo/route">路由参数演示</a>
                    <a href="/PHPweb/demo/mixed">混合演示</a>
                </nav>
            </header>
            
            <main>
                <h2>动态路由参数演示</h2>
                <p>这个页面演示如何在路由系统中处理URL路径参数。</p>
                
                <h3>当前用户ID：</h3>
                <p><strong><?php echo htmlspecialchars($id); ?></strong></p>
                
                <h3>测试链接（需要先在config.php中添加路由配置）：</h3>
                <ul>
                    <li><a href="/PHPweb/demo/user/123">/demo/user/123</a> (用户ID: 123)</li>
                    <li><a href="/PHPweb/demo/user/456">/demo/user/456</a> (用户ID: 456)</li>
                </ul>
                
                <h3>路由配置示例：</h3>
                <pre><code>// 在 config/config.php 中添加：
$config['routes']['demo/user/{id}'] = 'DemoController@userProfile';

// 控制器方法签名：
public function userProfile($userId = null) {
    // $userId 会自动从URL中解析
}</code></pre>
                
                <h3>动态路由的优势：</h3>
                <ul>
                    <li>URL更加简洁和美观</li>
                    <li>有利于SEO优化</li>
                    <li>参数有语义化含义</li>
                    <li>支持多级参数嵌套</li>
                </ul>
            </main>
            
            <footer>
                <p>&copy; <?php echo date('Y'); ?> <?php echo $siteName; ?></p>
            </footer>
        </body>
        </html>
        <?php
    }
    
    /**
     * 演示混合参数使用
     * 
     * 访问方式：/demo/mixed/category/electronics?page=2&sort=price
     * 
     * 这种方式结合了路由参数和查询参数，既有语义化路径，又有灵活的查询选项
     */
    public function mixedParams($category = 'all') {
        global $config;
        $siteName = $config['site']['name'];
        $pageTitle = '混合参数演示';
        
        // 路由参数
        $currentCategory = $category ?? 'all';
        
        // 查询参数
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';
        $filter = isset($_GET['filter']) ? $_GET['filter'] : 'none';
        
        // 安全验证
        $page = max(1, $page); // 确保页码至少为1
        $allowedSorts = ['price', 'name', 'date', 'default'];
        $sort = in_array($sort, $allowedSorts) ? $sort : 'default';
        
        ?>
        <!DOCTYPE html>
        <html lang="zh-CN">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $pageTitle . $config['site']['title_separator'] . $siteName; ?></title>
            <link rel="stylesheet" href="/PHPweb/css/css.css">
        </head>
        <body>
            <header>
                <h1><?php echo $siteName; ?></h1>
                <nav>
                    <a href="/PHPweb/">首页</a>
                    <a href="/PHPweb/demo/get">GET参数演示</a>
                    <a href="/PHPweb/demo/route">路由参数演示</a>
                    <a href="/PHPweb/demo/mixed">混合演示</a>
                </nav>
            </header>
            
            <main>
                <h2>混合参数演示</h2>
                <p>这个页面演示如何同时使用路由参数和查询参数。</p>
                
                <h3>当前参数：</h3>
                <ul>
                    <li><strong>分类 (路由参数):</strong> <?php echo htmlspecialchars($currentCategory); ?></li>
                    <li><strong>页码 (查询参数):</strong> <?php echo $page; ?></li>
                    <li><strong>排序 (查询参数):</strong> <?php echo htmlspecialchars($sort); ?></li>
                    <li><strong>筛选 (查询参数):</strong> <?php echo htmlspecialchars($filter); ?></li>
                </ul>
                
                <h3>测试链接：</h3>
                <ul>
                    <li><a href="/PHPweb/demo/mixed/electronics?page=2&sort=price">电子产品，第2页，按价格排序</a></li>
                    <li><a href="/PHPweb/demo/mixed/books?page=1&sort=name&filter=new">图书，第1页，按名称排序，新品筛选</a></li>
                    <li><a href="/PHPweb/demo/mixed/clothes?sort=date">服装，按日期排序</a></li>
                </ul>
                
                <h3>实际应用场景：</h3>
                <ul>
                    <li><strong>电商网站:</strong> /products/electronics?page=2&sort=price&brand=apple</li>
                    <li><strong>博客系统:</strong> /blog/tech?tag=php&year=2023</li>
                    <li><strong>搜索结果:</strong> /search/php?page=1&per_page=10&sort=relevance</li>
                </ul>
                
                <h3>最佳实践：</h3>
                <ul>
                    <li>使用路由参数表示资源层级（如分类、ID）</li>
                    <li>使用查询参数表示选项和筛选（如页码、排序）</li>
                    <li>始终验证和清理输入参数</li>
                    <li>为参数提供合理的默认值</li>
                </ul>
            </main>
            
            <footer>
                <p>&copy; <?php echo date('Y'); ?> <?php echo $siteName; ?></p>
            </footer>
        </body>
        </html>
        <?php
    }
    
    /**
     * 演示参数验证和安全处理
     * 
     * 这个方法展示了如何安全地处理各种类型的参数
     */
    public function secureParams() {
        global $config;
        $siteName = $config['site']['name'];
        $pageTitle = '安全参数处理';
        
        // 获取并验证各种参数
        $errors = [];
        $data = [];
        
        // 整数参数验证
        if (isset($_GET['age'])) {
            if (ctype_digit($_GET['age'])) {
                $data['age'] = (int)$_GET['age'];
                if ($data['age'] < 0 || $data['age'] > 150) {
                    $errors[] = '年龄必须在0-150之间';
                }
            } else {
                $errors[] = '年龄必须是数字';
            }
        }
        
        // 字符串参数验证
        if (isset($_GET['username'])) {
            $username = trim($_GET['username']);
            if (strlen($username) < 3 || strlen($username) > 20) {
                $errors[] = '用户名长度必须在3-20字符之间';
            } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
                $errors[] = '用户名只能包含字母、数字和下划线';
            } else {
                $data['username'] = $username;
            }
        }
        
        // 邮箱验证
        if (isset($_GET['email'])) {
            $email = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = '邮箱格式不正确';
            } else {
                $data['email'] = $email;
            }
        }
        
        // 数组参数处理
        if (isset($_GET['tags'])) {
            // tags可能以逗号分隔的字符串传递
            $tags = is_array($_GET['tags']) ? $_GET['tags'] : explode(',', $_GET['tags']);
            $data['tags'] = array_map('trim', array_filter($tags));
        }
        
        ?>
        <!DOCTYPE html>
        <html lang="zh-CN">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $pageTitle . $config['site']['title_separator'] . $siteName; ?></title>
            <link rel="stylesheet" href="/PHPweb/css/css.css">
        </head>
        <body>
            <header>
                <h1><?php echo $siteName; ?></h1>
                <nav>
                    <a href="/PHPweb/">首页</a>
                    <a href="/PHPweb/demo/get">GET参数演示</a>
                    <a href="/PHPweb/demo/route">路由参数演示</a>
                    <a href="/PHPweb/demo/mixed">混合演示</a>
                    <a href="/PHPweb/demo/secure">安全处理</a>
                </nav>
            </header>
            
            <main>
                <h2>安全参数处理演示</h2>
                <p>这个页面演示如何安全地验证和处理各种类型的参数。</p>
                
                <?php if (!empty($errors)): ?>
                    <div style="background-color: #ffebee; border: 1px solid #f44336; padding: 10px; margin: 10px 0;">
                        <h4>验证错误：</h4>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($data)): ?>
                    <div style="background-color: #e8f5e8; border: 1px solid #4caf50; padding: 10px; margin: 10px 0;">
                        <h4>验证通过的数据：</h4>
                        <ul>
                            <?php foreach ($data as $key => $value): ?>
                                <li><strong><?php echo htmlspecialchars($key); ?>:</strong> 
                                    <?php 
                                    if (is_array($value)) {
                                        echo implode(', ', array_map('htmlspecialchars', $value));
                                    } else {
                                        echo htmlspecialchars($value);
                                    }
                                    ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <h3>测试表单：</h3>
                <form method="get" action="/PHPweb/demo/secure">
                    <div>
                        <label for="username">用户名 (3-20字符，字母数字下划线):</label>
                        <input type="text" id="username" name="username" value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : ''; ?>">
                    </div>
                    <div>
                        <label for="age">年龄 (0-150):</label>
                        <input type="number" id="age" name="age" value="<?php echo isset($_GET['age']) ? htmlspecialchars($_GET['age']) : ''; ?>">
                    </div>
                    <div>
                        <label for="email">邮箱:</label>
                        <input type="email" id="email" name="email" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
                    </div>
                    <div>
                        <label for="tags">标签 (逗号分隔):</label>
                        <input type="text" id="tags" name="tags" value="<?php echo isset($_GET['tags']) ? htmlspecialchars($_GET['tags']) : ''; ?>">
                    </div>
                    <div>
                        <button type="submit">提交验证</button>
                    </div>
                </form>
                
                <h3>安全处理要点：</h3>
                <ul>
                    <li><strong>类型验证:</strong> 使用ctype_digit()、filter_var()等函数</li>
                    <li><strong>范围检查:</strong> 验证数值范围、字符串长度</li>
                    <li><strong>格式验证:</strong> 使用正则表达式验证格式</li>
                    <li><strong>输出转义:</strong> 使用htmlspecialchars()防止XSS</li>
                    <li><strong>SQL注入防护:</strong> 使用预处理语句（未在此示例中展示）</li>
                </ul>
            </main>
            
            <footer>
                <p>&copy; <?php echo date('Y'); ?> <?php echo $siteName; ?></p>
            </footer>
        </body>
        </html>
        <?php
    }
}