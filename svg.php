<?php

/**
 * SVG image generator.
 *
 * @author  Nikush Patel
 */
class Svg
{
    /**
     * The root <svg> node.
     *
     * @var SimpleXMLElement
     */
    private $root_node;

    /**
     * Create a blank SVG document.
     *
     * @param   int $width
     * @param   int $height
     */
    public function __construct($width = 0, $height = 0)
    {
        $this->root_node = new SimpleXMLElement('<svg></svg>');

        if ($width > 0) {
            $this->root_node->addAttribute('width', $width);
        }
        if ($height > 0) {
            $this->root_node->addAttribute('height', $height);
        }
    }

    /**
     * Add a rectangle to the image.
     *
     * @param   int $x
     * @param   int $y
     * @param   int $width
     * @param   int $height
     * @param   string  $fill
     * @param   string  $stroke
     * @param   int $strokeWidth
     * @return  void
     */
    publIc function addRect($x, $y, $width, $height, $fill=null, $stroke=null, $strokeWidth=null)
    {
        $rect = $this->root_node->addChild('rect');

        $rect->addAttribute('x', $x);
        $rect->addAttribute('y', $y);
        $rect->addAttribute('width', $width);
        $rect->addAttribute('height', $height);

        if ($fill != null) $rect->addAttribute('fill', $fill);
        if ($stroke != null) $rect->addAttribute('stroke', $stroke);
        if ($strokeWidth != null) $rect->addAttribute('stroke-width', $strokeWidth);
    }

    /**
     * Add a circle to the image.
     *
     * @param   int $cx
     * @param   int $cy
     * @param   int $radius
     * @param   string  $fill
     * @param   string  $stroke
     * @param   int $strokeWidth
     * @return  void
     */
    public function addCircle($cx, $cy, $radius, $fill=null, $stroke=null, $strokeWidth=null)
    {
        $circle = $this->root_node->addChild('circle');

        $circle->addAttribute('cx', $cx);
        $circle->addAttribute('cy', $cy);
        $circle->addAttribute('r', $radius);

        if ($fill != null) $circle->addAttribute('fill', $fill);
        if ($stroke != null) $circle->addAttribute('stroke', $stroke);
        if ($strokeWidth != null) $circle->addAttribute('stroke-width', $strokeWidth);
    }

    /**
     * Add a line to the image.
     *
     * @param   int $startx
     * @param   int $starty
     * @param   int $endx
     * @param   int $endy
     * @param   string  $stroke
     * @param   int $strokeWidth
     * @return  void
     */
    public function addLine($startx, $starty, $endx, $endy, $stroke=null, $strokeWidth=null)
    {
        $line = $this->root_node->addChild('line');

        $line->addAttribute('x1', $startx);
        $line->addAttribute('y1', $starty);
        $line->addAttribute('x2', $endx);
        $line->addAttribute('y2', $endy);

        if ($stroke != null) $line->addAttribute('stroke', $stroke);
        if ($strokeWidth != null) $line->addAttribute('stroke-width', $strokeWidth);
    }

    /**
     * Add a polyline to the image.
     *
     * Points must be provided in a single dimensional array with every number
     * occupying its own index in the array.
     *
     * @param   array   $points
     * @param   string  $fill
     * @param   string  $stroke
     * @param   int $strokeWidth
     * @return  void
     */
    public function addPolyline($points, $fill=null, $stroke=null, $strokeWidth=null)
    {
        $num_points = count($points);
        if ($num_points % 2 != 0)
            throw new Exception('Incorrect number of points provided.');

        $polyline = $this->root_node->addChild('polyline');

        $points_str = '';
        for ($i = 0; $i < $num_points; $i += 2) {
            $pointx = $points[$i];
            $pointy = $points[$i+1];

            $points_str .= "$pointx,$pointy ";
        }

        $polyline->addAttribute('points', $points_str);

        if ($fill != null) $polyline->addAttribute('fill', $fill);
        if ($stroke != null) $polyline->addAttribute('stroke', $stroke);
        if ($strokeWidth != null) $polyline->addAttribute('stroke-width', $strokeWidth);
    }

    /**
     * Add a path to the image.
     *
     * @param   string  $commands
     * @param   string  $fill
     * @param   string  $stroke
     * @param   int $strokeWidth
     * @return  void
     */
    public function addPath($commands, $fill=null, $stroke=null, $strokeWidth=null)
    {
        $path = $this->root_node->addChild('path');

        $path->addAttribute('d', $commands);

        if ($fill != null) $path->addAttribute('fill', $fill);
        if ($stroke != null) $path->addAttribute('stroke', $stroke);
        if ($strokeWidth != null) $path->addAttribute('stroke-width', $strokeWidth);
    }

    /**
     * Render the SVG element as a string.
     *
     * If $xmlDoc is set to true, the XML document header will be added.
     * This is used when the image is to be saved saved into its own file.
     * Set it to false (or leave it blank) when embedding the imade in a web
     * page.
     *
     * @param   boolean $xmlDoc
     * @return  string
     */
    public function render($xmlDoc = false)
    {
        $svg_str = $this->root_node->asXML();

        if ($xmlDoc == false) {
            // cut off the XML header automatically added by SimpleXML
            $svg_str = substr($svg_str, 22);
        }

        return $svg_str;
    }
}
