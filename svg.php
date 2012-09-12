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
    protected $root_node;

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
     * @param   array   $attrs
     * @return  void
     */
    public function addRect($x, $y, $width, $height, $attrs=array())
    {
        $rect = $this->root_node->addChild('rect');

        $attrs['x'] = $x;
        $attrs['y'] = $y;
        $attrs['width'] = $width;
        $attrs['height'] = $height;

        $this->setAttributes($rect, $attrs);
    }

    /**
     * Add a circle to the image.
     *
     * @param   int $cx
     * @param   int $cy
     * @param   int $radius
     * @param   array   $attrs
     * @return  void
     */
    public function addCircle($cx, $cy, $radius, $attrs=array())
    {
        $circle = $this->root_node->addChild('circle');

        $attrs['cx'] = $cx;
        $attrs['cy'] = $cy;
        $attrs['r'] = $radius;

        $this->setAttributes($circle, $attrs);
    }

    /**
     * Add a line to the image.
     *
     * @param   int $startx
     * @param   int $starty
     * @param   int $endx
     * @param   int $endy
     * @param   array   $attrs
     * @return  void
     */
    public function addLine($startx, $starty, $endx, $endy, $attrs=array())
    {
        $line = $this->root_node->addChild('line');

        $attrs['x1'] = $startx;
        $attrs['y1'] = $starty;
        $attrs['x2'] = $endx;
        $attrs['y2'] = $endy;

        $this->setAttributes($line, $attrs);
    }

    /**
     * Add a polyline to the image.
     *
     * Points must be provided in a single dimensional array with every number
     * occupying its own index in the array.
     *
     * @param   array   $points
     * @param   array   $attrs
     * @return  void
     */
    public function addPolyline($points, $attrs=array())
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

        $attrs['points'] = $points_str;
        $this->setAttributes($polyline, $attrs);
    }

    /**
     * Add a path to the image.
     *
     * @param   string  $commands
     * @param   array   $attrs
     * @return  void
     */
    public function addPath($commands, $attrs=array())
    {
        $path = $this->root_node->addChild('path');
        $attrs['d'] = $commands;
        $this->setAttributes($path, $attrs);
    }

    /**
     * Add text to the image.
     *
     * @param   string  $value
     * @param   int $x
     * @param   int $y
     * @param   array   $attrs
     * @return  void
     */
    public function addText($value, $x, $y, $attrs=array())
    {
        $text = $this->root_node->addChild('text', $value);
        $attrs['x'] = $x;
        $attrs['y'] = $y;
        $this->setAttributes($text, $attrs);
    }

    /**
     * Add a group element to the image.
     *
     * All the same methods available to this class are available the SvgGroup
     * element that gets returned.
     *
     * @param   array   $attrs
     * @return  SvgGroup
     */
    public function addGroup($x, $y, $attrs=array())
    {
        $g = $this->root_node->addChild('g');
        // translate because g elements don't have x and y attributes.
        if ($x != 0 && $y != 0)
            $attrs['transform'] = "translate($x,$y)";
        $this->setAttributes($g, $attrs);

        return new SvgGroup($g);
    }

    /**
     * Set attributes on the SimpleXMLElement.
     *
     * @param   SimpleXMLElement    $element
     * @param   array   $attributes
     * @return  void
     */
    protected function setAttributes(SimpleXMLElement $element, $attributes)
    {
        foreach ($attributes as $name => $value) {
            $element->addAttribute($name, $value);
        }
    }

    /**
     * Render the SVG element as a string.
     *
     * If $xmlDoc is set to true, the XML document header will be added.
     * This is used when the image is to be saved into its own file.
     * Set it to false (or leave it blank) when embedding the image in a web
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

/**
 * SVG group element.
 *
 * @author  Nikush Patel
 */
class SvgGroup extends Svg
{
    /**
     * Wraps the Svg class around a blank group element to decorate it with all
     * of the Svg class' methods.
     *
     * @param   SimpleXMLElement    $g
     */
    public function __construct(SimpleXMLElement $g)
    {
        $this->root_node = $g;
    }
}
