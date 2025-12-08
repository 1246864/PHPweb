<?php
// 引入预处理库
include_once __DIR__ . '/_PRE.php';

class Router {
    private $routes = [];
    
    public function __construct() {
        // 默认路由配置
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
    }
    
    /**
     * 解析当前请求的URL路径
     */
    public function getPath() {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = trim($path, '/');
        $path = str_replace('PHPweb/', '', $path); // 移除项目目录名
        return $path;
    }
    
    /**
     * 添加路由
     */
    public function addRoute($path, $handler) {
        $this->routes[$path] = $handler;
    }
    
    /**
     * 分发路由到对应的控制器
     */
    public function dispatch() {
        $path = $this->getPath();
        
        // 查找匹配的路由
        if (isset($this->routes[$path])) {
            $handler = $this->routes[$path];
            return $this->callHandler($handler);
        }
        
        // 尝试查找动态路由 (如 user/{id})
        foreach ($this->routes as $route => $handler) {
            if ($this->matchDynamicRoute($route, $path, $params)) {
                return $this->callHandler($handler, $params);
            }
        }
        
        // 没有找到匹配的路由，返回404
        $this->handle404();
    }
    
    /**
     * 匹配动态路由
     */
    private function matchDynamicRoute($route, $path, &$params) {
        $routeParts = explode('/', $route);
        $pathParts = explode('/', $path);
        
        if (count($routeParts) !== count($pathParts)) {
            return false;
        }
        
        $params = [];
        foreach ($routeParts as $index => $part) {
            if (preg_match('/^\{(.+)\}$/', $part, $matches)) {
                // 动态参数
                $params[$matches[1]] = $pathParts[$index];
            } elseif ($part !== $pathParts[$index]) {
                return false;
            }
        }
        
        return !empty($params);
    }
    
    /**
     * 调用处理器
     */
    private function callHandler($handler, $params = []) {
        list($controllerName, $methodName) = explode('@', $handler);
        
        $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';
        
        if (file_exists($controllerFile)) {
            include_once $controllerFile;
            
            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                
                if (method_exists($controller, $methodName)) {
                    return call_user_func_array([$controller, $methodName], $params);
                }
            }
        }
        
        $this->handle404();
    }
    
    /**
     * 处理404错误
     */
    private function handle404() {
        Error_404();
    }
}