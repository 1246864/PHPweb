<?php
// 引入预处理库
include_once __DIR__ . '/_PRE.php';

class Router {
    private $routes = [];
    private $config = [];
    
    public function __construct() {
        // 从配置文件加载路由配置
        global $config;
        $this->config = $config;
        $this->routes = $config['routes'];
    }
    
    /**
     * 解析当前请求的URL路径
     */
    public function getPath() {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = trim($path, '/');
        
        // 从配置中获取基础路径并移除
        $baseUrl = trim(parse_url($this->config['site']['url'], PHP_URL_PATH), '/');
        if ($baseUrl && strpos($path, $baseUrl) === 0) {
            $path = substr($path, strlen($baseUrl));
            $path = trim($path, '/');
        }
        
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
        
        // 使用配置中的控制器目录和后缀
        $controllerDir = $this->config['controllers']['directory'];
        $controllerSuffix = $this->config['controllers']['suffix'];
        $controllerFile = $controllerDir . $controllerName . '.php';
        
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