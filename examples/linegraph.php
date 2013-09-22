<?php
require('SplClassLoader.php');
$classLoader = new SplClassLoader('Nikush', '../src');
$classLoader->register();

$data_set = array(
    'Bender' => 50,
    'Fry' => 20,
    'Zoidberg' => 27,
    'Hermes' => 25,
    'Hebert' => 20,
    'Lela' => 23,
    'Amy' => 32,
    'Wormstrom' => 50,
    'Scruffy' => 15,
    'Someone' => 20,
);

$line = new Nikush\Graphs\LineGraph(500, 400, $data_set);

echo $line->render();
