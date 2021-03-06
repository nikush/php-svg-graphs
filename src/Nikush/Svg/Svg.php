<?php

namespace Nikush\Svg;

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
        $this->root_node = new \SimpleXMLElement('<svg></svg>');

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
     * @param   array   $attrs
     * @return  Polyline
     */
    public function addPolyline($attrs=array())
    {
        $polyline = $this->root_node->addChild('polyline');
        $this->setAttributes($polyline, $attrs);
        return new Polyline($polyline);
    }

    /**
     * Add a path to the image.
     *
     * @param   array   $attrs
     * @return  Path
     */
    public function addPath($attrs=array())
    {
        $path = $this->root_node->addChild('path');
        $this->setAttributes($path, $attrs);
        return new Path($path);
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
     * @return  Group
     */
    public function addGroup($x, $y, $attrs=array())
    {
        $g = $this->root_node->addChild('g');
        // translate because g elements don't have x and y attributes.
        if ($x != 0 || $y != 0)
            $attrs['transform'] = "translate($x,$y)";
        $this->setAttributes($g, $attrs);

        return new Group($g);
    }

    /**
     * Set attributes on the SimpleXMLElement.
     *
     * @param   SimpleXMLElement    $element
     * @param   array   $attributes
     * @return  void
     */
    protected function setAttributes(\SimpleXMLElement $element, $attributes)
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

    /**
     * Alias for the render() method.
     *
     * @return  string
     */
    public function __toString()
    {
        return $this->render();
    }
}
