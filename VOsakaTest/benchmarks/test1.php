<?php

declare(strict_types=1);

require "vendor/autoload.php";

use PhpBench\Attributes as Bench;
use venndev\vosaka\sync\LoopGate;
use venndev\vosaka\VOsaka;
use vosaka\http\Browzr;

class VOsakaCurlBench
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
     * Benchmark concurrent HTTP GET requests - VOsaka HTTP
     */
    #[Bench\Revs(5)]
    #[Bench\Iterations(5)]
    public function benchVosakaHttp(): void
    {
        VOsaka::spawn($this->vosakaHttpGenerator());
        VOsaka::run();
    }

    private function vosakaHttpGenerator(): Generator
    {
        $results = [];
        $requests = [];

        foreach ($this->testUrls as $url) {
            $requests[] = Browzr::get($url);
        }

        $responses = yield from VOsaka::join(...$requests)->unwrap();

        foreach ($responses as $response) {
            if ($response instanceof \Throwable) {
                $results[] = $response;
            } else {
                $results[] = $response;
            }
        }

        return $results;
    }

    /**
     * Benchmark POST requests - VOsaka HTTP
     */
    #[Bench\Revs(5)]
    #[Bench\Iterations(5)]
    public function benchVosakaHttpPost()
    {
        VOsaka::spawn($this->vosakaHttpPostGenerator());
        VOsaka::run();
    }

    private function vosakaHttpPostGenerator(): Generator
    {
        $results = [];
        $requests = [];

        foreach ($this->postUrls as $url) {
            $data = [
                "timestamp" => time(),
                "test_data" => str_repeat("x", 100),
                "benchmark" => "vosaka-http",
            ];

            $requests[] = Browzr::post($url, $data, [
                "Content-Type" => "application/json",
            ]);
        }

        try {
            $responses = yield from VOsaka::join(...$requests)->unwrap();

            foreach ($responses as $index => $response) {
                if ($response instanceof \Throwable) {
                    $results[] = $response;
                } else {
                    $results[] = $response;
                }
            }
        } catch (\Exception $e) {
            throw new \RuntimeException("Error during POST requests: " . $e->getMessage());
            foreach ($this->postUrls as $url) {
                try {
                    $data = [
                        "timestamp" => time(),
                        "test_data" => str_repeat("x", 100),
                        "benchmark" => "vosaka-http",
                    ];

                    $response = yield from Browzr::post($url, $data, [
                        "Content-Type" => "application/json",
                    ])->unwrap();

                    $results[] = $response;
                } catch (\Exception $error) {
                    var_dump($error->getMessage());
                    $results[] = $response;
                }
            }
        }

        return $results;
    }

    /**
     * Benchmark local mock requests - VOsaka
     */
    #[Bench\Revs(5)]
    #[Bench\Iterations(5)]
    public function benchVosakaMockRequests()
    {
        VOsaka::spawn($this->vosakaMockGenerator());
        VOsaka::run();
    }

    private function vosakaMockGenerator(): Generator
    {
        $gate = new LoopGate(3);
        $results = [];
        for ($i = 0; $i < 100; $i++) {
            $mockResponse = json_encode([
                "id" => $i,
                "data" => str_repeat("x", 100),
            ]);
            $results[] = json_decode($mockResponse, true);
            if ($gate->tick()) {
                yield;
            }
        }
        return $results;
    }

    /**
     * Benchmark fast local requests - VOsaka
     */
    #[Bench\Revs(5)]
    #[Bench\Iterations(5)]
    public function benchVosakaFastTasks()
    {
        VOsaka::spawn($this->vosakaFastGenerator());
        VOsaka::run();
    }

    private function vosakaFastGenerator(): Generator
    {
        $gate = new LoopGate(10);
        $results = [];
        for ($i = 0; $i < 100000; $i++) {
            if (count($results) < 1000) {
                $results[] = ["id" => $i, "result" => $i * 2];
            }
            if ($gate->tick()) {
                yield;
            }
        }
        return $results;
    }
}
