<?php
<!-- 在此处插入你的统计代码 -->

// 获取用户自定义生成的目录数量
$directory_count = isset($_GET['count']) ? intval($_GET['count']) : 1;

// 定义外部 HTML 文件路径
$external_html_file = "./external.html";

// 读取外部 HTML 文件内容
$external_html_content = file_get_contents($external_html_file);

// 定义路由规则和处理逻辑
$routes = array();
for ($i = 1; $i <= $directory_count; $i++) {
    $directory_name = generateRandomString(8); // 生成8个字符的随机目录名
    $directory_path = "./" . $directory_name;

    // 创建目录并生成对应的 index.html 文件
    if (!is_dir($directory_path)) {
        mkdir($directory_path, 0777, true);

        // 将外部 HTML 内容写入 index.html 文件
        file_put_contents($directory_path . "/index.html", $external_html_content);
    }

    // 设置路由规则
    $routes[$directory_name] = function () use ($directory_path) {
        include $directory_path . "/index.html"; // 引用当前目录下的 index.html 文件
    };
}

// 默认处理逻辑
function default_handler() {
    echo "欢迎访问泛目录程序！";
}

// 获取URL路径
$request_uri = $_SERVER['REQUEST_URI'];
// 清除可能的查询字符串
$request_uri = explode('?', $request_uri, 2)[0];
// 移除脚本名称（如果网站配置了虚拟目录）
$request_uri = str_replace(dirname($_SERVER['SCRIPT_NAME']), '', $request_uri);
// 移除前导和尾随斜杠
$request_uri = trim($request_uri, '/');
// 获取请求参数
$params = explode('/', $request_uri);

// 根据URL路径调用相应的处理逻辑
$handler = isset($routes[$params[0]]) ? $routes[$params[0]] : 'default_handler';
call_user_func($handler);

// 生成指定长度的随机字符串
function generateRandomString($length = 8) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
?>
