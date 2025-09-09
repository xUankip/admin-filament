<?php

header('Content-Type: text/plain');

echo "Laravel 11 System Requirements Check\n";
echo "------------------------------------\n";

// 1️⃣ Kiểm tra phiên bản PHP
$requirements = [
    'PHP >= 8.2' => version_compare(PHP_VERSION, '8.2.0', '>='),
];

// 2️⃣ Kiểm tra các extension bắt buộc
$extensions = [
    'BCMath PHP Extension' => 'bcmath',
    'Ctype PHP Extension' => 'ctype',
    'Fileinfo PHP Extension' => 'fileinfo',
    'JSON PHP Extension' => 'json',
    'Mbstring PHP Extension' => 'mbstring',
    'OpenSSL PHP Extension' => 'openssl',
    'PDO PHP Extension' => 'pdo',
    'Tokenizer PHP Extension' => 'tokenizer',
    'XML PHP Extension' => 'xml',
    'GD Extension (for image processing)' => 'gd',
    'cURL PHP Extension' => 'curl',
    'Zip PHP Extension' => 'zip',
    'Redis PHP Extension (optional)' => 'redis',
];

foreach ($extensions as $name => $extension) {
    $requirements[$name] = extension_loaded($extension);
}

// 3️⃣ Kiểm tra quyền thư mục với đường dẫn tuyệt đối
$directories = [
    'storage' => __DIR__ . '/../storage',
    'bootstrap/cache' => __DIR__ . '/../bootstrap/cache',
];

echo "\nChecking System Requirements:\n";
echo "-----------------------------\n";
foreach ($requirements as $requirement => $passed) {
    echo "[ " . ($passed ? "✔" : "✘") . " ] " . $requirement . "\n";
}

echo "\nChecking Directory Permissions:\n";
echo "------------------------------\n";
foreach ($directories as $name => $path) {
    $writable = is_writable($path);
    echo "[ " . ($writable ? "✔" : "✘") . " ] $name (writable) $path\n";
}

// 4️⃣ Kiểm tra Composer & PHP Memory Limit
$composer = shell_exec('composer --version');
$memory_limit = ini_get('memory_limit');

echo "\nOther Checks:\n";
echo "-------------\n";
echo "[ " . ($composer ? "✔" : "✘") . " ] Composer Installed\n";
echo "[ ✔ ] PHP Memory Limit: $memory_limit\n";

echo "<pre>";
print_r($_SERVER);
echo "</pre>";
