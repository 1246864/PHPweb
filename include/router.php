<?php
// 引入预处理库
include_once __DIR__ . '/_PRE.php';

/**
 * 路由器类 - 负责URL解析和请求分发
 * 
 * 路由系统是Web应用的核心组件，它的主要作用是：
 * 1. 解析用户请求的URL
 * 2. 根据URL找到对应的控制器和方法
 * 3. 调用相应的处理逻辑
 * 
 * 这样可以实现URL和代码的分离，使URL更加友好和可维护
 */
class Router {
    /** @var array 存储所有路由规则的数组 */
    private $routes = [];
    
    /** @var array 存储全局配置信息 */
    private $config = [];
    
    /**
     * 构造函数 - 初始化路由器
     * 
     * 从全局配置中加载路由规则，这样路由规则可以通过配置文件管理
     * 而不需要硬编码在代码中，提高了灵活性
     */
    public function __construct() {
        // 从配置文件加载路由配置
        global $config;
        $this->config = $config;
        $this->routes = $config['routes'];
    }
    
    /**
     * 解析当前请求的URL路径
     * 
     * 这个方法的作用是从完整的URL中提取出路径部分
     * 例如：从 "http://localhost/PHPweb/user/profile" 提取出 "user/profile"
     * 
     * @return string 清理后的路径字符串
     */
    public function getPath() {
        // 获取URL的路径部分，去掉查询参数（如 ?id=123）
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // 去掉路径前后的斜杠，统一格式
        $path = trim($path, '/');
        
        // 从配置中获取基础路径并移除
        // 这样可以支持不同的部署环境，比如 /PHPweb/ 或 /project/
        $baseUrl = trim(parse_url($this->config['site']['url'], PHP_URL_PATH), '/');
        if ($baseUrl && strpos($path, $baseUrl) === 0) {
            // 如果路径以基础URL开头，则移除基础URL部分
            $path = substr($path, strlen($baseUrl));
            $path = trim($path, '/');
        }
        
        return $path;
    }
    
    /**
     * 添加路由 - 动态添加新的路由规则
     * 
     * 这个方法允许在运行时添加新的路由规则
     * 虽然目前主要使用配置文件，但这个方法提供了扩展性
     * 
     * @param string $path URL路径，如 'user/profile'
     * @param string $handler 处理器，格式为 'Controller@method'
     */
    public function addRoute($path, $handler) {
        $this->routes[$path] = $handler;
    }
    
    /**
     * 分发路由到对应的控制器 - 路由系统的核心方法
     * 
     * 这个方法是路由系统的工作流程：
     * 1. 获取当前请求的URL路径
     * 2. 在路由表中查找匹配的静态路由
     * 3. 如果没有静态路由，尝试匹配动态路由
     * 4. 找到匹配的路由后，调用对应的控制器方法
     * 5. 如果没有找到任何匹配的路由，显示404页面
     */
    public function dispatch() {
        // 获取当前请求的路径
        $path = $this->getPath();
        
        // 第一步：查找精确匹配的静态路由
        // 例如：'user/profile' 精确匹配 'user/profile'
        if (isset($this->routes[$path])) {
            $handler = $this->routes[$path];
            return $this->callHandler($handler);
        }
        
        // 第二步：尝试查找动态路由
        // 动态路由可以包含参数，如 'user/{id}' 可以匹配 'user/123'
        foreach ($this->routes as $route => $handler) {
            if ($this->matchDynamicRoute($route, $path, $params)) {
                // 如果匹配成功，$params 会包含解析出的参数
                return $this->callHandler($handler, $params);
            }
        }
        
        // 第三步：没有找到匹配的路由，显示404错误页面
        $this->handle404();
    }
    
    /**
     * 匹配动态路由 - 支持参数化URL
     * 
     * 动态路由允许URL中包含参数，使得URL更加灵活
     * 例如：
     * - 路由规则：'user/{id}'
     * - 实际URL：'user/123'
     * - 解析结果：$params = ['id' => '123']
     * 
     * @param string $route 路由规则，可能包含 {参数名}
     * @param string $path 实际的URL路径
     * @param array &$params 引用参数，用于存储解析出的参数
     * @return bool 是否匹配成功
     */
    private function matchDynamicRoute($route, $path, &$params) {
        // 将路由规则和实际路径都按斜杠分割成数组
        $routeParts = explode('/', $route);
        $pathParts = explode('/', $path);
        
        // 如果段落数量不同，直接返回不匹配
        if (count($routeParts) !== count($pathParts)) {
            return false;
        }
        
        // 重置参数数组
        $params = [];
        
        // 逐段比较路由规则和实际路径
        foreach ($routeParts as $index => $part) {
            // 检查当前段落是否是动态参数（格式：{参数名}）
            if (preg_match('/^\{(.+)\}$/', $part, $matches)) {
                // 如果是动态参数，将实际路径对应段的值作为参数值
                // 例如：{id} 对应 123，则 $params['id'] = '123'
                $params[$matches[1]] = $pathParts[$index];
            } elseif ($part !== $pathParts[$index]) {
                // 如果不是动态参数且不匹配，则整个路由不匹配
                return false;
            }
        }
        
        // 只有当解析出参数时才认为匹配成功
        // 这样可以避免静态路由被误判为动态路由
        return !empty($params);
    }
    
    /**
     * 调用处理器 - 实例化控制器并调用方法
     * 
     * 这个方法负责将路由规则转换为实际的代码执行：
     * 1. 解析处理器字符串（如 'UserController@profile'）
     * 2. 定位控制器文件
     * 3. 实例化控制器类
     * 4. 调用指定的方法
     * 5. 传递解析出的参数
     * 
     * @param string $handler 处理器字符串，格式为 'Controller@method'
     * @param array $params 要传递给方法的参数数组
     */
    private function callHandler($handler, $params = []) {
        // 将处理器字符串按 @ 分割，得到控制器名和方法名
        // 例如：'UserController@profile' -> ['UserController', 'profile']
        list($controllerName, $methodName) = explode('@', $handler);
        
        // 使用配置中的控制器目录和后缀构建文件路径
        // 这样控制器目录可以通过配置文件灵活设置
        $controllerDir = $this->config['controllers']['directory'];
        $controllerSuffix = $this->config['controllers']['suffix'];
        $controllerFile = $controllerDir . $controllerName . '.php';
        
        // 检查控制器文件是否存在
        if (file_exists($controllerFile)) {
            // 引入控制器文件
            include_once $controllerFile;
            
            // 检查控制器类是否存在
            if (class_exists($controllerName)) {
                // 实例化控制器
                $controller = new $controllerName();
                
                // 检查方法是否存在
                if (method_exists($controller, $methodName)) {
                    // 调用控制器方法，并传递参数
                    // call_user_func_array 可以动态调用方法并传递参数数组
                    return call_user_func_array([$controller, $methodName], $params);
                }
            }
        }
        
        // 如果任何一步失败，显示404错误页面
        $this->handle404();
    }
    
    /**
     * 处理404错误 - 页面未找到
     * 
     * 当没有找到匹配的路由时调用此方法
     * 使用预处理库中定义的 Error_404() 函数显示错误页面
     */
    private function handle404() {
        Error_404();
    }
}