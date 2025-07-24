# VOsaka
```shell
PHPBench (1.4.1) running benchmarks... #standwithukraine
with PHP version 8.4.8, xdebug ❌, opcache ❌

\VOsakaCurlBench

    benchVosakaHttp.........................I4 - Mo2.562s (±20.16%)
    benchVosakaHttpPost.....................I4 - Mo2.246s (±30.52%)
    benchVosakaMockRequests.................I4 - Mo1.136ms (±10.95%)
    benchVosakaFastTasks....................I4 - Mo25.721ms (±0.73%)

Subjects: 4, Assertions: 0, Failures: 0, Errors: 0
+------+-----------------+-------------------------+-----+------+------------+-----------------+--------------+----------------+
| iter | benchmark       | subject                 | set | revs | mem_peak   | time_avg        | comp_z_value | comp_deviation |
+------+-----------------+-------------------------+-----+------+------------+-----------------+--------------+----------------+
| 0    | VOsakaCurlBench | benchVosakaHttp         |     | 5    | 1,354,224b | 1,524,045.000μs | -1.77σ       | -35.67%        |
| 1    | VOsakaCurlBench | benchVosakaHttp         |     | 5    | 1,353,616b | 2,788,501.000μs | +0.88σ       | +17.69%        |
| 2    | VOsakaCurlBench | benchVosakaHttp         |     | 5    | 1,354,224b | 2,867,063.400μs | +1.04σ       | +21.01%        |
| 3    | VOsakaCurlBench | benchVosakaHttp         |     | 5    | 1,353,616b | 2,347,530.600μs | -0.05σ       | -0.92%         |
| 4    | VOsakaCurlBench | benchVosakaHttp         |     | 5    | 1,354,224b | 2,319,219.000μs | -0.10σ       | -2.11%         |
| 0    | VOsakaCurlBench | benchVosakaHttpPost     |     | 5    | 1,347,936b | 2,488,184.400μs | +1.12σ       | +34.29%        |
| 1    | VOsakaCurlBench | benchVosakaHttpPost     |     | 5    | 1,347,936b | 2,358,940.400μs | +0.89σ       | +27.32%        |
| 2    | VOsakaCurlBench | benchVosakaHttpPost     |     | 5    | 1,347,936b | 1,194,875.000μs | -1.16σ       | -35.51%        |
| 3    | VOsakaCurlBench | benchVosakaHttpPost     |     | 5    | 1,347,936b | 2,052,297.000μs | +0.35σ       | +10.77%        |
| 4    | VOsakaCurlBench | benchVosakaHttpPost     |     | 5    | 1,347,936b | 1,169,773.600μs | -1.21σ       | -36.87%        |
| 0    | VOsakaCurlBench | benchVosakaMockRequests |     | 5    | 927,016b   | 1,444.000μs     | +1.89σ       | +20.72%        |
| 1    | VOsakaCurlBench | benchVosakaMockRequests |     | 5    | 927,016b   | 1,149.000μs     | -0.36σ       | -3.95%         |
| 2    | VOsakaCurlBench | benchVosakaMockRequests |     | 5    | 927,016b   | 1,118.200μs     | -0.60σ       | -6.52%         |
| 3    | VOsakaCurlBench | benchVosakaMockRequests |     | 5    | 927,016b   | 1,069.800μs     | -0.97σ       | -10.57%        |
| 4    | VOsakaCurlBench | benchVosakaMockRequests |     | 5    | 927,016b   | 1,200.000μs     | +0.03σ       | +0.32%         |
| 0    | VOsakaCurlBench | benchVosakaFastTasks    |     | 5    | 1,264,136b | 25,705.800μs    | -0.31σ       | -0.23%         |
| 1    | VOsakaCurlBench | benchVosakaFastTasks    |     | 5    | 1,264,136b | 25,488.800μs    | -1.46σ       | -1.07%         |
| 2    | VOsakaCurlBench | benchVosakaFastTasks    |     | 5    | 1,264,136b | 25,680.800μs    | -0.44σ       | -0.32%         |
| 3    | VOsakaCurlBench | benchVosakaFastTasks    |     | 5    | 1,264,136b | 25,924.600μs    | +0.85σ       | +0.62%         |
| 4    | VOsakaCurlBench | benchVosakaFastTasks    |     | 5    | 1,264,136b | 26,022.000μs    | +1.36σ       | +1.00%         |
+------+-----------------+-------------------------+-----+------+------------+-----------------+--------------+----------------+
```

# ReactPHP
```shell
PHPBench (1.4.1) running benchmarks... #standwithukraine
with PHP version 8.4.8, xdebug ❌, opcache ❌

\ReactCurlBench

    benchReactPhpCurl.......................I4 - Mo3.414s (±12.25%)
    benchReactPhpPost.......................I4 - Mo2.544s (±11.98%)
    benchReactPhpMockRequests...............I4 - Mo669.538μs (±9.65%)
    benchReactPhpFastTasks..................I4 - Mo63.330ms (±1.60%)

Subjects: 4, Assertions: 0, Failures: 0, Errors: 0
+------+----------------+---------------------------+-----+------+-------------+-----------------+--------------+----------------+
| iter | benchmark      | subject                   | set | revs | mem_peak    | time_avg        | comp_z_value | comp_deviation |
+------+----------------+---------------------------+-----+------+-------------+-----------------+--------------+----------------+
| 0    | ReactCurlBench | benchReactPhpCurl         |     | 5    | 2,717,680b  | 3,526,283.200μs | +0.85σ       | +10.39%        |
| 1    | ReactCurlBench | benchReactPhpCurl         |     | 5    | 2,716,792b  | 3,020,357.800μs | -0.44σ       | -5.45%         |
| 2    | ReactCurlBench | benchReactPhpCurl         |     | 5    | 2,716,792b  | 3,508,775.800μs | +0.80σ       | +9.84%         |
| 3    | ReactCurlBench | benchReactPhpCurl         |     | 5    | 2,771,440b  | 3,413,704.200μs | +0.56σ       | +6.87%         |
| 4    | ReactCurlBench | benchReactPhpCurl         |     | 5    | 2,716,792b  | 2,502,859.400μs | -1.77σ       | -21.65%        |
| 0    | ReactCurlBench | benchReactPhpPost         |     | 5    | 2,590,544b  | 3,352,938.600μs | +2.00σ       | +23.95%        |
| 1    | ReactCurlBench | benchReactPhpPost         |     | 5    | 2,590,544b  | 2,546,509.400μs | -0.49σ       | -5.86%         |
| 2    | ReactCurlBench | benchReactPhpPost         |     | 5    | 2,590,544b  | 2,546,453.200μs | -0.49σ       | -5.86%         |
| 3    | ReactCurlBench | benchReactPhpPost         |     | 5    | 2,590,544b  | 2,554,310.000μs | -0.47σ       | -5.57%         |
| 4    | ReactCurlBench | benchReactPhpPost         |     | 5    | 2,590,544b  | 2,524,926.200μs | -0.56σ       | -6.66%         |
| 0    | ReactCurlBench | benchReactPhpMockRequests |     | 5    | 921,024b    | 832.400μs       | +1.94σ       | +18.70%        |
| 1    | ReactCurlBench | benchReactPhpMockRequests |     | 5    | 921,024b    | 668.800μs       | -0.48σ       | -4.63%         |
| 2    | ReactCurlBench | benchReactPhpMockRequests |     | 5    | 921,024b    | 640.200μs       | -0.90σ       | -8.71%         |
| 3    | ReactCurlBench | benchReactPhpMockRequests |     | 5    | 921,024b    | 692.600μs       | -0.13σ       | -1.24%         |
| 4    | ReactCurlBench | benchReactPhpMockRequests |     | 5    | 921,024b    | 672.400μs       | -0.43σ       | -4.12%         |
| 0    | ReactCurlBench | benchReactPhpFastTasks    |     | 5    | 81,081,840b | 61,676.400μs    | -1.56σ       | -2.49%         |
| 1    | ReactCurlBench | benchReactPhpFastTasks    |     | 5    | 81,081,840b | 64,621.400μs    | +1.35σ       | +2.16%         |
| 2    | ReactCurlBench | benchReactPhpFastTasks    |     | 5    | 81,081,840b | 64,029.800μs    | +0.77σ       | +1.23%         |
| 3    | ReactCurlBench | benchReactPhpFastTasks    |     | 5    | 81,081,840b | 62,897.200μs    | -0.35σ       | -0.56%         |
| 4    | ReactCurlBench | benchReactPhpFastTasks    |     | 5    | 81,081,840b | 63,046.400μs    | -0.21σ       | -0.33%         |
+------+----------------+---------------------------+-----+------+-------------+-----------------+--------------+----------------+
```

# AMP
```shell
PHPBench (1.4.1) running benchmarks... #standwithukraine
with PHP version 8.4.8, xdebug ❌, opcache ❌

\AmphpCurlBench

    benchAmphpCurl..........................I4 - Mo2.823s (±10.11%)
    benchAmphpPost..........................I4 - Mo2.662s (±10.18%)
    benchAmphpMockRequests..................I4 - Mo13.823ms (±13.38%)
    benchAmphpFastTasks.....................I4 - Mo11.293ms (±2.73%)

Subjects: 4, Assertions: 0, Failures: 0, Errors: 0
+------+----------------+------------------------+-----+------+-------------+-----------------+--------------+----------------+
| iter | benchmark      | subject                | set | revs | mem_peak    | time_avg        | comp_z_value | comp_deviation |
+------+----------------+------------------------+-----+------+-------------+-----------------+--------------+----------------+
| 0    | AmphpCurlBench | benchAmphpCurl         |     | 5    | 26,928,864b | 3,331,705.000μs | +1.15σ       | +11.59%        |
| 1    | AmphpCurlBench | benchAmphpCurl         |     | 5    | 26,928,864b | 2,797,984.600μs | -0.62σ       | -6.29%         |
| 2    | AmphpCurlBench | benchAmphpCurl         |     | 5    | 26,928,864b | 3,337,685.200μs | +1.17σ       | +11.79%        |
| 3    | AmphpCurlBench | benchAmphpCurl         |     | 5    | 26,928,864b | 2,883,317.400μs | -0.34σ       | -3.43%         |
| 4    | AmphpCurlBench | benchAmphpCurl         |     | 5    | 26,928,864b | 2,577,571.200μs | -1.35σ       | -13.67%        |
| 0    | AmphpCurlBench | benchAmphpPost         |     | 5    | 26,907,400b | 3,099,731.800μs | +1.47σ       | +15.02%        |
| 1    | AmphpCurlBench | benchAmphpPost         |     | 5    | 26,907,400b | 2,695,787.000μs | +0.00σ       | +0.03%         |
| 2    | AmphpCurlBench | benchAmphpPost         |     | 5    | 26,907,400b | 2,317,731.200μs | -1.37σ       | -14.00%        |
| 3    | AmphpCurlBench | benchAmphpPost         |     | 5    | 26,907,400b | 2,493,776.000μs | -0.73σ       | -7.47%         |
| 4    | AmphpCurlBench | benchAmphpPost         |     | 5    | 26,907,400b | 2,868,240.800μs | +0.63σ       | +6.43%         |
| 0    | AmphpCurlBench | benchAmphpMockRequests |     | 5    | 3,393,224b  | 11,612.800μs    | -0.76σ       | -10.13%        |
| 1    | AmphpCurlBench | benchAmphpMockRequests |     | 5    | 3,393,064b  | 14,611.800μs    | +0.98σ       | +13.07%        |
| 2    | AmphpCurlBench | benchAmphpMockRequests |     | 5    | 3,393,608b  | 14,939.000μs    | +1.17σ       | +15.61%        |
| 3    | AmphpCurlBench | benchAmphpMockRequests |     | 5    | 3,392,872b  | 10,414.800μs    | -1.45σ       | -19.40%        |
| 4    | AmphpCurlBench | benchAmphpMockRequests |     | 5    | 3,393,256b  | 13,032.800μs    | +0.06σ       | +0.86%         |
| 0    | AmphpCurlBench | benchAmphpFastTasks    |     | 5    | 2,842,152b  | 11,301.600μs    | -0.42σ       | -1.15%         |
| 1    | AmphpCurlBench | benchAmphpFastTasks    |     | 5    | 2,842,152b  | 11,982.800μs    | +1.76σ       | +4.80%         |
| 2    | AmphpCurlBench | benchAmphpFastTasks    |     | 5    | 2,842,152b  | 11,076.400μs    | -1.14σ       | -3.12%         |
| 3    | AmphpCurlBench | benchAmphpFastTasks    |     | 5    | 2,842,152b  | 11,262.600μs    | -0.55σ       | -1.49%         |
| 4    | AmphpCurlBench | benchAmphpFastTasks    |     | 5    | 2,842,152b  | 11,544.000μs    | +0.35σ       | +0.97%         |
+------+----------------+------------------------+-----+------+-------------+-----------------+--------------+----------------+
```
