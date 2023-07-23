<?php
<!-- �ڴ˴��������ͳ�ƴ��� -->

// ��ȡ�û��Զ������ɵ�Ŀ¼����
$directory_count = isset($_GET['count']) ? intval($_GET['count']) : 1;

// �����ⲿ HTML �ļ�·��
$external_html_file = "./external.html";

// ��ȡ�ⲿ HTML �ļ�����
$external_html_content = file_get_contents($external_html_file);

// ����·�ɹ���ʹ����߼�
$routes = array();
for ($i = 1; $i <= $directory_count; $i++) {
    $directory_name = generateRandomString(8); // ����8���ַ������Ŀ¼��
    $directory_path = "./" . $directory_name;

    // ����Ŀ¼�����ɶ�Ӧ�� index.html �ļ�
    if (!is_dir($directory_path)) {
        mkdir($directory_path, 0777, true);

        // ���ⲿ HTML ����д�� index.html �ļ�
        file_put_contents($directory_path . "/index.html", $external_html_content);
    }

    // ����·�ɹ���
    $routes[$directory_name] = function () use ($directory_path) {
        include $directory_path . "/index.html"; // ���õ�ǰĿ¼�µ� index.html �ļ�
    };
}

// Ĭ�ϴ����߼�
function default_handler() {
    echo "��ӭ���ʷ�Ŀ¼����";
}

// ��ȡURL·��
$request_uri = $_SERVER['REQUEST_URI'];
// ������ܵĲ�ѯ�ַ���
$request_uri = explode('?', $request_uri, 2)[0];
// �Ƴ��ű����ƣ������վ����������Ŀ¼��
$request_uri = str_replace(dirname($_SERVER['SCRIPT_NAME']), '', $request_uri);
// �Ƴ�ǰ����β��б��
$request_uri = trim($request_uri, '/');
// ��ȡ�������
$params = explode('/', $request_uri);

// ����URL·��������Ӧ�Ĵ����߼�
$handler = isset($routes[$params[0]]) ? $routes[$params[0]] : 'default_handler';
call_user_func($handler);

// ����ָ�����ȵ�����ַ���
function generateRandomString($length = 8) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
?>
