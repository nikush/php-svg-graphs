<?php
require('SplClassLoader.php');
$classLoader = new SplClassLoader('Nikush', '../src');
$classLoader->register();

$attrs = array(
    'fill' => 'red',
    'stroke' => 'blue'
);

$svg = new Nikush\Graphs\Svg\Svg(200, 200);

$svg->addRect(0, 0, 50, 50, $attrs);
$svg->addCircle(100, 25, 25, $attrs);
$svg->addLine(150, 25, 200, 25, $attrs);

$polyline = $svg->addPolyline($attrs);
$polyline->addPoint(0, 100);
$polyline->addPoint(50, 100);
$polyline->addRelPoint(0, 50);
$polyline->addRelPoint(-50, 0);

$path = $svg->addPath($attrs);
$path->moveTo(75, 100);
$path->lineTo(50, 0);
$path->archTo(50, 50, 0, false, true, -50, 50);

$svg->addText('Nikush', 150, 150);

// add a group and set the fill of all of it's contents to green
$g = $svg->addGroup(0, 0, array('fill' => '#0f0'));
// add a rect to the group
$g->addRect(0, 0, 10, 10);
// add a group to the group
$sub_g = $g->addGroup(10, 10);
// add a rect to the new group
$sub_g->addRect(0,0,10,10);

echo $svg;
