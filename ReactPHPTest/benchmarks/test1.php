<?php

declare(strict_types=1);

require "vendor/autoload.php";

use PhpBench\Attributes as Bench;
use React\EventLoop\Factory as ReactFactory;
use React\Http\Browser;

class ReactCurlBench
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
     * Benchmark concurrent HTTP GET requests - ReactPHP
     */
    #[Bench\Revs(5)]
    #[Bench\Iterations(5)]
    public function benchReactPhpCurl(): array
    {
        $loop = ReactFactory::create();
        $browser = new Browser($loop);
        $completed = 0;
        $total = count($this->testUrls);
        $results = [];

        foreach ($this->testUrls as $index => $url) {
            $browser
                ->get($url)
                ->then(function ($response) use (&$completed, $total, $loop, &$results, $index, $url) {
                    $results[] = [
                        "url" => $url,
                        "method" => "GET",
                        "status" => $response->getStatusCode(),
                        "size" => strlen((string) $response->getBody()),
                    ];
                    $completed++;
                    if ($completed >= $total) {
                        $loop->stop();
                    }
                })
                ->otherwise(function ($error) use (&$completed, $total, $loop, &$results, $index, $url) {
                    $results[] = [
                        "url" => $url,
                        "method" => "GET",
                        "error" => $error->getMessage() ?: "Unknown error",
                    ];
                    $completed++;
                    if ($completed >= $total) {
                        $loop->stop();
                    }
                });
        }

        $loop->run();
        return $results;
    }

    /**
     * Benchmark POST requests - ReactPHP
     */
    #[Bench\Revs(5)]
    #[Bench\Iterations(5)]
    public function benchReactPhpPost(): array
    {
        $loop = ReactFactory::create();
        $browser = new Browser($loop);
        $completed = 0;
        $total = count($this->postUrls);
        $results = [];

        foreach ($this->postUrls as $index => $url) {
            $data = [
                "timestamp" => time(),
                "test_data" => str_repeat("x", 100),
                "benchmark" => "reactphp-http",
            ];

            $browser
                ->post($url, [
                    "Content-Type" => "application/json",
                ], json_encode($data))
                ->then(function ($response) use (&$completed, $total, $loop, &$results, $index, $url) {
                    $results[] = [
                        "url" => $url,
                        "method" => "POST",
                        "status" => $response->getStatusCode(),
                        "size" => strlen((string) $response->getBody()),
                    ];
                    $completed++;
                    if ($completed >= $total) {
                        $loop->stop();
                    }
                })
                ->otherwise(function ($error) use (&$completed, $total, $loop, &$results, $index, $url) {
                    $results[] = [
                        "url" => $url,
                        "method" => "POST",
                        "error" => $error->getMessage() ?: "Unknown error",
                    ];
                    $completed++;
                    if ($completed >= $total) {
                        $loop->stop();
                    }
                });
        }

        $loop->run();
        return $results;
    }

    /**
     * Benchmark local mock requests - ReactPHP
     */
    #[Bench\Revs(5)]
    #[Bench\Iterations(5)]
    public function benchReactPhpMockRequests(): array
    {
        $loop = ReactFactory::create();
        $completed = 0;
        $total = 100;
        $results = [];

        for ($i = 0; $i < $total; $i++) {
            $loop->futureTick(function () use (&$completed, $total, $loop, $i, &$results) {
                $mockResponse = json_encode([
                    "id" => $i,
                    "data" => str_repeat("x", 100),
                ]);
                $results[] = json_decode($mockResponse, true);
                $completed++;
                if ($completed >= $total) {
                    $loop->stop();
                }
            });
        }

        $loop->run();
        return $results;
    }

    /**
     * Benchmark fast local requests - ReactPHP
     */
    #[Bench\Revs(5)]
    #[Bench\Iterations(5)]
    public function benchReactPhpFastTasks(): array
    {
        $loop = ReactFactory::create();
        $completed = 0;
        $total = 100000;
        $results = [];

        for ($i = 0; $i < $total; $i++) {
            $loop->futureTick(function () use (&$completed, $total, $loop, $i, &$results) {
                if (count($results) < 1000)
                    $results[] = ["id" => $i, "result" => $i * 2];
                $completed++;
                if ($completed >= $total) {
                    $loop->stop();
                }
            });
        }

        $loop->run();
        return $results;
    }
}
?>