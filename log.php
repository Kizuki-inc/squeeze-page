<?php
// 确保 log 文件夹存在
$logDir = __DIR__ . '/log';
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}
// 获取访问者的 IP 地址
$ip = $_SERVER['REMOTE_ADDR'];
// 判断设备类型设备用的
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$deviceType = 'PC'; // 默认是PC端
if (strpos($userAgent, 'Android') !== false) {
    $deviceType = 'Android';
} elseif (strpos($userAgent, 'iPhone') !== false || strpos($userAgent, 'iPad') !== false) {
    $deviceType = 'iOS';
}
$time = date('Y-m-d\TH-i-s\Z');
$logFileName = $logDir . '/' . $time . '.txt';
// 备用一手记录时区（用户本地决定）
$timezone = $_GET['timezone'] ?? 'Unknown';
$ipDetails = getIpDetails($ip);

// 偷鸡摸狗记录用户信息
$logContent = "IP: $ip\nTime: " . date('Y-m-d H:i:s') . "\nBrowser: $userAgent\nDevice: $deviceType\nTimezone: $timezone\nIP Details:\n";
$logContent .= "Country: " . ($ipDetails['country'] ?? 'N/A') . "\n";
$logContent .= "Region: " . ($ipDetails['region'] ?? 'N/A') . "\n";
$logContent .= "City: " . ($ipDetails['city'] ?? 'N/A') . "\n";
$logContent .= "ISP: " . ($ipDetails['org'] ?? 'N/A') . "\n";
$logContent .= "Location: " . ($ipDetails['loc'] ?? 'N/A') . "\n";

// 生成数据的
file_put_contents($logFileName, $logContent);

// 伪装
header('Content-Type: image/gif');
echo base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');

function getIpDetails($ip) {
    $apiToken = 'KEY'; 
    $apiUrl = "https://ipinfo.io/{$ip}?token={$apiToken}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    // 用来看详细数据的
    if ($response) {
        return json_decode($response, true);
    }

    return [];
}
?>