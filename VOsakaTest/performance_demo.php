<?php

declare(strict_types=1);

require "vendor/autoload.php";

use venndev\vosaka\VOsaka;
use vosaka\http\Browzr;

echo "=== VOsaka HTTP Performance Optimization Demo ===\n\n";

// Test URLs
$testUrls = [
    "https://httpbin.org/json",
    "https://httpbin.org/uuid",
    "https://httpbin.org/user-agent",
];

// Function to measure execution time
function measureTime(callable $generatorFn): array {
    $start = microtime(true);
    // Assume VOsaka::run can execute a generator and return its result.
    $result = VOsaka::run($generatorFn());
    $end = microtime(true);
    return [
        'result' => $result,
        'time' => ($end - $start) * 1000 // ms
    ];
}

// --- Test 1: Standard GET requests ---
echo "1. Testing Standard GET Requests (New Client Per Request)...\n";
$standardTest = measureTime(function() use ($testUrls) {
    $requests = [];
    foreach ($testUrls as $url) {
        // Use a new, non-pooled client for a fair comparison
        $requests[] = Browzr::client()->get($url);
    }
    $responses = yield from VOsaka::join(...$requests)->unwrap();
    return $responses;
});
echo "Standard GET - Time: " . sprintf('%.2f', $standardTest['time']) . "ms\n";

// --- Test 2: Optimized GET requests (using default pooled client) ---
echo "\n2. Testing Optimized GET Requests with Connection Pooling...\n";
$optimizedTest = measureTime(function() use ($testUrls) {
    $requests = [];
    foreach ($testUrls as $url) {
        // Use the default client which is now optimized with pooling
        $requests[] = Browzr::get($url, [], ['keep_alive' => true]);
    }
    $responses = yield from VOsaka::join(...$requests)->unwrap();
    return $responses;
});
echo "Optimized GET - Time: " . sprintf('%.2f', $optimizedTest['time']) . "ms\n";

// --- Test 3: Batch GET requests ---
echo "\n3. Testing Batch GET Requests...\n";
$batchTest = measureTime(function() use ($testUrls) {
    return yield from Browzr::batchGet($testUrls, [], [
        'timeout' => 15,
        'keep_alive' => true
    ])->unwrap();
});
echo "Batch GET - Time: " . sprintf('%.2f', $batchTest['time']) . "ms\n";

// --- Test 4: POST requests comparison ---
echo "\n4. Testing POST Requests...\n";
$postUrl = "https://httpbin.org/post";
$testData = ["benchmark" => "performance-demo", "time" => time()];

// Standard POST
$standardPost = measureTime(function() use ($postUrl, $testData) {
    return yield from Browzr::client()->post($postUrl, $testData, ["Content-Type" => "application/json"])->unwrap();
});
echo "Standard POST - Time: " . sprintf('%.2f', $standardPost['time']) . "ms\n";

// Optimized POST
$optimizedPost = measureTime(function() use ($postUrl, $testData) {
    return yield from Browzr::post($postUrl, $testData, ["Content-Type" => "application/json"], ['keep_alive' => true])->unwrap();
});
echo "Optimized POST - Time: " . sprintf('%.2f', $optimizedPost['time']) . "ms\n";

// --- Performance Summary ---
echo "\n=== Performance Summary ===\n";
if ($standardTest['time'] > 0.1) {
    $getImprovement = (($standardTest['time'] - $optimizedTest['time']) / $standardTest['time']) * 100;
    $batchImprovement = (($standardTest['time'] - $batchTest['time']) / $standardTest['time']) * 100;
    echo "GET Optimization (Pooled vs New Client): " . sprintf('%+.1f', $getImprovement) . "%\n";
    echo "Batch GET vs Standard: " . sprintf('%+.1f', $batchImprovement) . "%\n";
}
if ($standardPost['time'] > 0.1) {
    $postImprovement = (($standardPost['time'] - $optimizedPost['time']) / $standardPost['time']) * 100;
    echo "POST Optimization (Pooled vs New Client): " . sprintf('%+.1f', $postImprovement) . "%\n";
}

// --- Connection pool stats ---
echo "\n=== Connection Pool Statistics (Default Client) ===\n";
$client = Browzr::getDefaultClient();
$stats = $client->getConnectionStats();
echo "Pool Size: {$stats['pool_size']}\n";
echo "Total Connections Created: {$stats['total_created']}\n";
echo "Total Connections Reused: {$stats['total_reused']}\n";

if (($stats['total_created'] + $stats['total_reused']) > 0) {
    $reuseRatio = ($stats['total_reused'] / ($stats['total_created'] + $stats['total_reused'])) * 100;
    echo "Connection Reuse Ratio: " . sprintf('%.1f', $reuseRatio) . "%\n";
}

echo "\nScript finished. The code should now run correctly and demonstrate the performance gains.\n";

?>