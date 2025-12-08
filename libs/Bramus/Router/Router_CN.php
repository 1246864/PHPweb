<?php

/**
 * @author      Bram(us) Van Damme <bramus@bram.us>
 * @copyright   Copyright (c), 2013 Bram(us) Van Damme
 * @license     MIT public license
 */
namespace Bramus\Router;

/**
 * 路由器类
 */
class Router
{
    /**
     * @var array 路由模式及其处理函数
     */
    private $afterRoutes = array();

    /**
     * @var array 前置中间件路由模式及其处理函数
     */
    private $beforeRoutes = array();

    /**
     * @var array [object|callable] 当没有匹配到路由时执行的函数
     */
    protected $notFoundCallback = [];

    /**
     * @var string 当前基础路由，用于（子）路由挂载
     */
    private $baseRoute = '';

    /**
     * @var string 需要处理的请求方法
     */
    private $requestedMethod = '';

    /**
     * @var string 路由器的服务器基础路径
     */
    private $serverBasePath;

    /**
     * @var string 默认控制器命名空间
     */
    private $namespace = '';

    /**
     * 存储一个前置中间件路由和一个处理函数，当使用指定方法之一访问时执行。
     *
     * @param string          $methods 允许的方法，用 | 分隔
     * @param string          $pattern 路由模式，如 /about/system
     * @param object|callable $fn      要执行的处理函数
     */
    public function before($methods, $pattern, $fn)
    {
        $pattern = $this->baseRoute . '/' . trim($pattern, '/');
        $pattern = $this->baseRoute ? rtrim($pattern, '/') : $pattern;

        if ($methods === '*') {
            $methods = 'GET|POST|PUT|DELETE|OPTIONS|PATCH|HEAD';
        }
        
        foreach (explode('|', $methods) as $method) {
            $this->beforeRoutes[$method][] = array(
                'pattern' => $pattern,
                'fn' => $fn,
            );
        }
    }

    /**
     * 存储一个路由和一个处理函数，当使用指定方法之一访问时执行。
     *
     * @param string          $methods 允许的方法，用 | 分隔
     * @param string          $pattern 路由模式，如 /about/system
     * @param object|callable $fn      要执行的处理函数
     */
    public function match($methods, $pattern, $fn)
    {
        $pattern = $this->baseRoute . '/' . trim($pattern, '/');
        $pattern = $this->baseRoute ? rtrim($pattern, '/') : $pattern;

        foreach (explode('|', $methods) as $method) {
            $this->afterRoutes[$method][] = array(
                'pattern' => $pattern,
                'fn' => $fn,
            );
        }
    }

    /**
     * 使用任何方法访问的路由的简写。
     *
     * @param string          $pattern 路由模式，如 /about/system
     * @param object|callable $fn      要执行的处理函数
     */
    public function all($pattern, $fn)
    {
        $this->match('GET|POST|PUT|DELETE|OPTIONS|PATCH|HEAD', $pattern, $fn);
    }

    /**
     * 使用 GET 方法访问的路由的简写。
     *
     * @param string          $pattern 路由模式，如 /about/system
     * @param object|callable $fn      要执行的处理函数
     */
    public function get($pattern, $fn)
    {
        $this->match('GET', $pattern, $fn);
    }

    /**
     * 使用 POST 方法访问的路由的简写。
     *
     * @param string          $pattern 路由模式，如 /about/system
     * @param object|callable $fn      要执行的处理函数
     */
    public function post($pattern, $fn)
    {
        $this->match('POST', $pattern, $fn);
    }

    /**
     * 使用 PATCH 方法访问的路由的简写。
     *
     * @param string          $pattern 路由模式，如 /about/system
     * @param object|callable $fn      要执行的处理函数
     */
    public function patch($pattern, $fn)
    {
        $this->match('PATCH', $pattern, $fn);
    }

    /**
     * 使用 DELETE 方法访问的路由的简写。
     *
     * @param string          $pattern 路由模式，如 /about/system
     * @param object|callable $fn      要执行的处理函数
     */
    public function delete($pattern, $fn)
    {
        $this->match('DELETE', $pattern, $fn);
    }

    /**
     * 使用 PUT 方法访问的路由的简写。
     *
     * @param string          $pattern 路由模式，如 /about/system
     * @param object|callable $fn      要执行的处理函数
     */
    public function put($pattern, $fn)
    {
        $this->match('PUT', $pattern, $fn);
    }

    /**
     * 使用 OPTIONS 方法访问的路由的简写。
     *
     * @param string          $pattern 路由模式，如 /about/system
     * @param object|callable $fn      要执行的处理函数
     */
    public function options($pattern, $fn)
    {
        $this->match('OPTIONS', $pattern, $fn);
    }

    /**
     * 将一组回调函数挂载到基础路由上。
     *
     * @param string   $baseRoute 要挂载回调函数的路由子模式
     * @param callable $fn        回调方法
     */
    public function mount($baseRoute, $fn)
    {
        // 跟踪当前基础路由
        $curBaseRoute = $this->baseRoute;

        // 构建新的基础路由字符串
        $this->baseRoute .= $baseRoute;

        // 调用可调用函数
        call_user_func($fn);

        // 恢复原始基础路由
        $this->baseRoute = $curBaseRoute;
    }

    /**
     * 获取所有请求头。
     *
     * @return array 请求头
     */
    public function getRequestHeaders()
    {
        $headers = array();

        // 如果 getallheaders() 可用，则使用它
        if (function_exists('getallheaders')) {
            $headers = getallheaders();

            // 如果出现问题，getallheaders() 可能返回 false
            if ($headers !== false) {
                return $headers;
            }
        }

        // 方法 getallheaders() 不可用或出现问题：手动提取它们
        foreach ($_SERVER as $name => $value) {
            if ((substr($name, 0, 5) == 'HTTP_') || ($name == 'CONTENT_TYPE') || ($name == 'CONTENT_LENGTH')) {
                $headers[str_replace(array(' ', 'Http'), array('-', 'HTTP'), ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }

        return $headers;
    }

    /**
     * 获取使用的请求方法，考虑覆盖。
     *
     * @return string 要处理的请求方法
     */
    public function getRequestMethod()
    {
        // 采用在 $_SERVER 中找到的方法
        $method = $_SERVER['REQUEST_METHOD'];

        // 如果是 HEAD 请求，按照 HTTP 规范将其覆盖为 GET 并防止任何输出
        // @url http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.4
        if ($_SERVER['REQUEST_METHOD'] == 'HEAD') {
            ob_start();
            $method = 'GET';
        }

        // 如果是 POST 请求，检查方法覆盖头
        elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $headers = $this->getRequestHeaders();
            if (isset($headers['X-HTTP-Method-Override']) && in_array($headers['X-HTTP-Method-Override'], array('PUT', 'DELETE', 'PATCH'))) {
                $method = $headers['X-HTTP-Method-Override'];
            }
        }

        return $method;
    }

    /**
     * 为可调用方法设置默认查找命名空间。
     *
     * @param string $namespace 给定的命名空间
     */
    public function setNamespace($namespace)
    {
        if (is_string($namespace)) {
            $this->namespace = $namespace;
        }
    }

    /**
     * 获取之前给定的命名空间。
     *
     * @return string 如果存在，返回给定的命名空间
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * 执行路由器：循环所有定义的前置中间件和路由，如果找到匹配则执行处理函数。
     *
     * @param object|callable $callback 在匹配路由处理完成后要执行的函数（= 路由器中间件之后）
     *
     * @return bool
     */
    public function run($callback = null)
    {
        // 定义我们需要处理的方法
        $this->requestedMethod = $this->getRequestMethod();

        // 处理所有前置中间件
        if (isset($this->beforeRoutes[$this->requestedMethod])) {
            $this->handle($this->beforeRoutes[$this->requestedMethod]);
        }

        // 处理所有路由
        $numHandled = 0;
        if (isset($this->afterRoutes[$this->requestedMethod])) {
            $numHandled = $this->handle($this->afterRoutes[$this->requestedMethod], true);
        }

        // 如果没有处理路由，触发 404（如果有）
        if ($numHandled === 0) {
            if (isset($this->afterRoutes[$this->requestedMethod])) {
                $this->trigger404($this->afterRoutes[$this->requestedMethod]);
            } else {
                $this->trigger404();
            }
        } // 如果处理了路由，执行完成回调（如果有）
        elseif ($callback && is_callable($callback)) {
            $callback();
        }

        // 如果最初是 HEAD 请求，通过清空输出缓冲区来清理
        if ($_SERVER['REQUEST_METHOD'] == 'HEAD') {
            ob_end_clean();
        }

        // 如果处理了路由返回 true，否则返回 false
        return $numHandled !== 0;
    }

    /**
     * 设置 404 处理函数。
     *
     * @param object|callable|string $match_fn 要执行的函数
     * @param object|callable $fn 要执行的函数
     */
    public function set404($match_fn, $fn = null)
    {
      if (!is_null($fn)) {
        $this->notFoundCallback[$match_fn] = $fn;
      } else {
        $this->notFoundCallback['/'] = $match_fn;
      }
    }

    /**
     * 触发 404 响应
     *
     * @param string $pattern 路由模式，如 /about/system
     */
    public function trigger404($match = null){

        // 计数器，跟踪我们已处理的路由数量
        $numHandled = 0;

        // 处理 404 模式
        if (count($this->notFoundCallback) > 0)
        {
            // 循环回退路由
            foreach ($this->notFoundCallback as $route_pattern => $route_callable) {

              // 匹配结果
              $matches = [];

              // 检查是否有匹配并获取匹配项作为 $matches（指针）
              $is_match = $this->patternMatches($route_pattern, $this->getCurrentUri(), $matches, PREG_OFFSET_CAPTURE);

              // 是回退路由匹配吗？
              if ($is_match) {

                // 重新处理匹配项，只包含匹配项，不包含原始字符串
                $matches = array_slice($matches, 1);

                // 提取匹配的 URL 参数（仅参数）
                $params = array_map(function ($match, $index) use ($matches) {

                  // 我们有后续参数：从当前参数位置取子字符串直到下一个参数的位置（感谢 PREG_OFFSET_CAPTURE）
                  if (isset($matches[$index + 1]) && isset($matches[$index + 1][0]) && is_array($matches[$index + 1][0])) {
                    if ($matches[$index + 1][0][1] > -1) {
                      return trim(substr($match[0][0], 0, $matches[$index + 1][0][1] - $match[0][1]), '/');
                    }
                  } // 我们没有后续参数：返回全部

                  return isset($match[0][0]) && $match[0][1] != -1 ? trim($match[0][0], '/') : null;
                }, $matches, array_keys($matches));

                $this->invoke($route_callable);

                ++$numHandled;
              }
            }
        }
        if (($numHandled == 0) && (isset($this->notFoundCallback['/']))) {
            $this->invoke($this->notFoundCallback['/']);
        } elseif ($numHandled == 0) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        }
    }

    /**
    * 将所有大括号匹配 {} 替换为单词模式（如 Laravel）
    * 检查是否有路由匹配
    *
    * @param $pattern
    * @param $uri
    * @param $matches
    * @param $flags
    *
    * @return bool -> 是否匹配 是/否
    */
    private function patternMatches($pattern, $uri, &$matches, $flags)
    {
      // 将所有大括号匹配 {} 替换为单词模式（如 Laravel）
      $pattern = preg_replace('/\/{(.*?)}/', '/(.*?)', $pattern);

      // 我们可能有匹配！
      return boolval(preg_match_all('#^' . $pattern . '$#', $uri, $matches, PREG_OFFSET_CAPTURE));
    }

    /**
     * 处理一组路由：如果找到匹配，则执行相关的处理函数。
     *
     * @param array $routes       路由模式及其处理函数的集合
     * @param bool  $quitAfterRun 处理函数在匹配一个路由后是否需要退出？
     *
     * @return int 处理的路由数量
     */
    private function handle($routes, $quitAfterRun = false)
    {
        // 计数器，跟踪我们已处理的路由数量
        $numHandled = 0;

        // 当前页面 URL
        $uri = $this->getCurrentUri();

        // 循环所有路由
        foreach ($routes as $route) {

            // 获取路由匹配
            $is_match = $this->patternMatches($route['pattern'], $uri, $matches, PREG_OFFSET_CAPTURE);

            // 是否有有效匹配？
            if ($is_match) {

                // 重新处理匹配项，只包含匹配项，不包含原始字符串
                $matches = array_slice($matches, 1);

                // 提取匹配的 URL 参数（仅参数）
                $params = array_map(function ($match, $index) use ($matches) {

                    // 我们有后续参数：从当前参数位置取子字符串直到下一个参数的位置（感谢 PREG_OFFSET_CAPTURE）
                    if (isset($matches[$index + 1]) && isset($matches[$index + 1][0]) && is_array($matches[$index + 1][0])) {
                        if ($matches[$index + 1][0][1] > -1) {
                            return trim(substr($match[0][0], 0, $matches[$index + 1][0][1] - $match[0][1]), '/');
                        }
                    } // 我们没有后续参数：返回全部

                    return isset($match[0][0]) && $match[0][1] != -1 ? trim($match[0][0], '/') : null;
                }, $matches, array_keys($matches));

                // 如果期望输入是可调用的，则使用 URL 参数调用处理函数
                $this->invoke($route['fn'], $params);

                ++$numHandled;

                // 如果我们需要退出，则退出
                if ($quitAfterRun) {
                    break;
                }
            }
        }

        // 返回处理的路由数量
        return $numHandled;
    }

    private function invoke($fn, $params = array())
    {
        if (is_callable($fn)) {
            call_user_func_array($fn, $params);
        }

        // 如果不是，检查特殊参数的存在
        elseif (stripos($fn, '@') !== false) {
            // 分解给定路由的段
            list($controller, $method) = explode('@', $fn);

            // 如果设置了命名空间，则调整控制器类
            if ($this->getNamespace() !== '') {
                $controller = $this->getNamespace() . '\\' . $controller;
            }

            try {
                $reflectedMethod = new \ReflectionMethod($controller, $method);
                // 确保它是可调用的
                if ($reflectedMethod->isPublic() && (!$reflectedMethod->isAbstract())) {
                    if ($reflectedMethod->isStatic()) {
                        forward_static_call_array(array($controller, $method), $params);
                    } else {
                        // 确保我们有实例，因为非静态方法不能静态调用
                        if (\is_string($controller)) {
                            $controller = new $controller();
                        }
                        call_user_func_array(array($controller, $method), $params);
                    }
                }
            } catch (\ReflectionException $reflectionException) {
                // 控制器类不可用或类没有方法 $method
            }
        }
    }

    /**
     * 定义当前相对 URI。
     *
     * @return string
     */
    public function getCurrentUri()
    {
        // 获取当前请求 URI 并从中移除重写基础路径（= 允许在子文件夹中运行路由器）
        $uri = substr(rawurldecode($_SERVER['REQUEST_URI']), strlen($this->getBasePath()));

        // 不要在 URL 中考虑查询参数
        if (strstr($uri, '?')) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }

        // 移除末尾斜杻 + 在开头强制添加斜杻
        return '/' . trim($uri, '/');
    }

    /**
     * 返回服务器基础路径，如果未定义则定义它。
     *
     * @return string
     */
    public function getBasePath()
    {
        // 检查服务器基础路径是否已定义，如果没有则定义它。
        if ($this->serverBasePath === null) {
            $this->serverBasePath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
        }

        return $this->serverBasePath;
    }

    /**
     * 显式设置服务器基础路径。当入口脚本路径与入口 URL 不同时使用。
     * @see https://github.com/bramus/router/issues/82#issuecomment-466956078
     *
     * @param string
     */
    public function setBasePath($serverBasePath)
    {
        $this->serverBasePath = $serverBasePath;
    }
}
