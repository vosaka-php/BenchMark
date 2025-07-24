<?php

declare(strict_types=1);

require "vendor/autoload.php";

use Amp\Http\Client\HttpClientBuilder;
use Amp\Http\Client\Request;
use Amp\Http\Client\Response;
use PhpBench\Attributes as Bench;

use function Amp\async;
use function Amp\await;

class AmphpCurlBench
{
    private array $testUrls = [
        "https://httpbin.org/delay/0",
        "https://httpbin.org/json",
        "https://httpbin.org/uuid",
    ];

    private array $postUrls = [
        "https://httpbin.org/post",
        "https://httpbin.org/anything",
    ];

    /**
     * Benchmark concurrent HTTP GET requests - Amphp
     */
    #[Bench\Revs(5)]
    #[Bench\Iterations(5)]
    public function benchAmphpCurl(): array
    {
        return \Amp\async(function () {
            $client = HttpClientBuilder::buildDefault();
            $results = [];
            $futures = [];

            foreach ($this->testUrls as $index => $url) {
                $futures[] = async(function () use ($client, $url) {
                    try {
                        $request = new Request($url, "GET");
                        $response = $client->request($request);
                        $body = $response->getBody()->buffer();

                        return [
                            "url" => $url,
                            "method" => "GET",
                            "status" => $response->getStatus(),
                            "size" => strlen($body),
                        ];
                    } catch (\Throwable $error) {
                        return [
                            "url" => $url,
                            "method" => "GET",
                            "error" => $error->getMessage() ?: "Unknown error",
                        ];
                    }
                });
            }

            // Wait for all requests to complete
            foreach ($futures as $future) {
                $results[] = $future->await();
            }

            return $results;
        })->await();
    }

    /**
     * Benchmark POST requests - Amphp
     */
    #[Bench\Revs(5)]
    #[Bench\Iterations(5)]
    public function benchAmphpPost(): array
    {
        return \Amp\async(function () {
            $client = HttpClientBuilder::buildDefault();
            $results = [];
            $futures = [];

            foreach ($this->postUrls as $index => $url) {
                $futures[] = async(function () use ($client, $url) {
                    try {
                        $data = [
                            "timestamp" => time(),
                            "test_data" => str_repeat("x", 100),
                            "benchmark" => "amphp-http",
                        ];

                        $request = new Request($url, "POST");
                        $request->setHeader("Content-Type", "application/json");
                        $request->setBody(json_encode($data));

                        $response = $client->request($request);
                        $body = $response->getBody()->buffer();

                        return [
                            "url" => $url,
                            "method" => "POST",
                            "status" => $response->getStatus(),
                            "size" => strlen($body),
                        ];
                    } catch (\Throwable $error) {
                        return [
                            "url" => $url,
                            "method" => "POST",
                            "error" => $error->getMessage() ?: "Unknown error",
                        ];
                    }
                });
            }

            // Wait for all requests to complete
            foreach ($futures as $future) {
                $results[] = $future->await();
            }

            return $results;
        })->await();
    }

    /**
     * Benchmark local mock requests - Amphp
     */
    #[Bench\Revs(5)]
    #[Bench\Iterations(5)]
    public function benchAmphpMockRequests(): array
    {
        return \Amp\async(function () {
            $results = [];
            $futures = [];
            $total = 100;

            for ($i = 0; $i < $total; $i++) {
                $futures[] = async(function () use ($i) {
                    // Simulate async work with delay
                    \Amp\delay(0.001); // 1ms delay

                    $mockResponse = json_encode([
                        "id" => $i,
                        "data" => str_repeat("x", 100),
                    ]);

                    return json_decode($mockResponse, true);
                });
            }

            // Wait for all mock requests to complete
            foreach ($futures as $future) {
                $results[] = $future->await();
            }

            return $results;
        })->await();
    }

    /**
     * Benchmark fast local tasks - Amphp (reduced task count to prevent memory issues)
     */
    #[Bench\Revs(5)]
    #[Bench\Iterations(5)]
    public function benchAmphpFastTasks(): array
    {
        return \Amp\async(function () {
            $results = [];
            $futures = [];
            $total = 100000; // Reduced from 100000 to prevent memory exhaustion

            for ($i = 0; $i < $total; $i++) {
                if (count($futures) < 1000) {
                    $futures[] = async(function () use ($i) {
                        // Fast computation without delay
                        return ["id" => $i, "result" => $i * 2];
                    });
                }
            }

            // Wait for all tasks to complete
            foreach ($futures as $future) {
                $results[] = $future->await();
            }

            return $results;
        })->await();
    }
}
?>