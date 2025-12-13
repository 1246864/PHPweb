<?php
// header('Content-Type: application/json; charset=utf-8');

// -------------------------- 配置区（根据你的项目调整） --------------------------
$SCAN_DIR = __DIR__ . '/../pages'; // 页面文件存放目录
$CONFIG_FILE = __DIR__ . '/../config/config.php'; // 优先路由配置文件
$AUTO_CONFIG_FILE = __DIR__ . '/../config/.auto_router_config.php'; // 生成的自动路由配置文件
$SUPPORT_LIB_PATH = __DIR__ . '/../include/_PRE.php'; // 支持库路径
$EXCLUDE_EXT = ['txt', 'md', 'log']; // 排除的文件后缀
// --------------------------------------------------------------------------------

// 初始化返回数据
$result = [
    'total' => 0, // 扫描到的URL总条数
    'valid_count' => 0, // 有效URL条数（无冲突）
    'conflict_count' => 0, // 冲突URL条数
    'list' => [] // 每个URL的详细信息
];
// 1. 加载优先路由配置
if (!file_exists($CONFIG_FILE)) {
    exit(json_encode(['code' => 1, 'msg' => '优先路由配置文件不存在', 'data' => $result]));
}
$config = [];
include $CONFIG_FILE;
$priorityRoutes = isset($config['router_Page']) ? $config['router_Page'] : []; // 优先路由列表
$priorityRouteKeys = []; // 优先路由的唯一标识（URL+method）
foreach ($priorityRoutes as $url => $info) {
    $method = strtoupper(isset($info['method']) ? $info['method'] : 'ALL');
    $priorityRouteKeys["{$method}|{$url}"] = $info['file'];
}

// 2. 递归扫描页面文件，提取URL标记
$scannedRoutes = []; // 扫描到的路由（未去重）
$validRoutes = []; // 最终有效路由（无冲突）
$validRouteKeys = []; // 有效路由的唯一标识（URL+method）

function scanFiles($dir, &$scannedRoutes, $excludeExt) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $fullPath = $dir . '/' . $file;
        if (is_dir($fullPath)) {
            scanFiles($fullPath, $scannedRoutes, $excludeExt);
            continue;
        }
        // 过滤文件后缀
        $ext = pathinfo($fullPath, PATHINFO_EXTENSION);
        if (in_array($ext, $excludeExt)) continue;
        // 读取首行内容
        $firstLine = (file($fullPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)[0]);
        $firstLine = isset($firstLine)?trim($firstLine):'';
        if (strpos($firstLine, '<!-- URL ') !== 0) continue;
        // 解析URL标记
        preg_match_all('/\{([^}]+)\}/', $firstLine, $matches);
        foreach ($matches[1] as $match) {
            $parts = array_map('trim', explode(',', $match));
            $url = $parts[0];
            $method = strtoupper(isset($parts[1]) ? $parts[1] : 'ALL');
            $relativeFile = str_replace(__DIR__ . '/../', '', $fullPath); // 相对项目根目录的文件路径
            $scannedRoutes[] = [
                'url' => $url,
                'method' => $method,
                'file' => $relativeFile,
                'conflict_type' => '',
                'conflict_msg' => ''
            ];
        }
    }
}

scanFiles($SCAN_DIR, $scannedRoutes, $EXCLUDE_EXT);
$result['total'] = count($scannedRoutes);

// 3. 冲突检测与路由筛选
foreach ($scannedRoutes as $route) {
    $routeKey = "{$route['method']}|{$route['url']}";
    $currentItem = $route;
    
    // 检测与优先路由的冲突
    if (isset($priorityRouteKeys[$routeKey])) {
        $currentItem['conflict_type'] = 'priority_conflict';
        $currentItem['conflict_msg'] = "与优先路由冲突（优先路由文件：{$priorityRouteKeys[$routeKey]}），已舍弃";
        $result['conflict_count']++;
    }
    // 检测扫描内重复冲突
    elseif (isset($validRouteKeys[$routeKey])) {
        $currentItem['conflict_type'] = 'duplicate_conflict';
        $currentItem['conflict_msg'] = "扫描内重复（已存在文件：{$validRouteKeys[$routeKey]}），已舍弃";
        $result['conflict_count']++;
    }
    // 无冲突，加入有效路由
    else {
        $validRoutes[] = $currentItem;
        $validRouteKeys[$routeKey] = $currentItem['file'];
        $result['valid_count']++;
    }
    
    $result['list'][] = $currentItem;
}

// 4. 生成自动路由配置文件
$configContent = "<?php\n// 自动路由配置文件,由admin/auto_router.文件自动php生成,不推荐在这进行配置\ninclude_once __DIR__ . '/../include/_PRE.php';\n";
foreach ($validRoutes as $route) {
    $url = var_export($route['url'], true);
    $file = var_export($route['file'], true);
    $method = var_export($route['method'], true);
    $configContent .= "auto_add_Page($url, $file, $method);\n";
}
file_put_contents($AUTO_CONFIG_FILE, $configContent);

global $__auto_router_result;
$__auto_router_result = $result;
// // 5. 返回JSON结果
// exit(json_encode([
//     'code' => 0,
//     'msg' => '扫描完成',
//     'data' => $result
// ], JSON_UNESCAPED_UNICODE));