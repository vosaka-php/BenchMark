<?php

require '../vendor/autoload.php';

use Terremoth\Async\Process;

$process = new Process();
$age = 30;
$name = 'John Doe';
$fruits = ['orange', 'apple', 'grape'];

$process->send(function () use (&$age, &$name, &$fruits) {
    $age += 5; // Increment age by 5
    $name = strtoupper($name);
    $fruits = array_map('strtoupper', $fruits);

    // create file 
    $file = fopen('test.txt', 'w');
    if ($file) {
        fwrite($file, "Name: $name\n");
        fwrite($file, "Age: $age\n");
        fwrite($file, "Fruits: " . implode(', ', $fruits) . "\n");
        fclose($file);
    }
});

var_dump($age); // 30
