<?php
require('SplClassLoader.php');
$classLoader = new SplClassLoader('Nikush', '../src');
$classLoader->register();

$data_set = array();

$handle = fopen('data.csv', 'r');
while ($data = fgetcsv($handle, 1000)) {
    $k = $data[0];
    $v = $data[1];
    $data_set[$k] = $v;
}

$line = new Nikush\Graphs\LineGraph(500, 400, $data_set);

echo $line->render();
