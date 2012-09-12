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
     * @param   array   $attrs
     * @return  SvgPolyline
     */
    public function addPolyline($attrs=array())
    {
        $polyline = $this->root_node->addChild('polyline');
        $this->setAttributes($polyline, $attrs);
        return new SvgPolyline($polyline);
    }

    /**
     * Add a path to the image.
     *
     * @param   array   $attrs
     * @return  SvgPath
     */
    public function addPath($attrs=array())
    {
        $path = $this->root_node->addChild('path');
        $this->setAttributes($path, $attrs);
        return new SvgPath($path);
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
        if ($x != 0 || $y != 0)
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

/**
 * SVG polyline element.
 *
 * @author  Nikush Patel
 */
class SvgPolyline
{
    /**
     * The polyline object
     *
     * @var SimpleXMLElement
     */
    private $polyline;

    /**
     * The previous point plotted.  Used when plotting relative points.
     *
     * @var array
     */
    private $last_point;

    /**
     * Wrap the class around a blank polyline element to decorate it with
     * polyline drawing methods.
     *
     * @param   SimpleXMLElement    $polyline
     */
    public function __construct(SimpleXMLElement $polyline)
    {
        $this->polyline = $polyline;
        $this->polyline['points'] = '';
    }

    /**
     * Plot a point.
     *
     * @param   number  $x
     * @param   number  $y
     * @return  void
     */
    public function addPoint($x, $y)
    {
        $this->last_point = array($x, $y);
        $this->polyline['points'] .= "$x,$y ";
    }

    /**
     * Plot a point relative the last point plotted.
     *
     * @param   number  $x
     * @param   number  $y
     * @return  void
     */
    public function addRelPoint($x, $y)
    {
        if (!isset($this->last_point))
            $this->last_point = array(0,0);

        $new_point = $this->last_point;
        $new_point[0] += $x;
        $new_point[1] += $y;
        $this->last_point = $new_point;
        $this->polyline['points'] .= "{$new_point[0]},{$new_point[1]} ";
    }
}

/**
 * SVG path element.
 *
 * @author  Nikush Patel
 */
class SvgPath
{
    /**
     * The path element.
     *
     * @var SimpleXMLElement
     */
    private $path;

    /**
     * Wrap the class around a blank path element to decorate it with path
     * drawing methods.
     *
     * @param   SimpleXMLElement    $path
     */
    public function __construct(SimpleXMLElement $path)
    {
        $this->path = $path;
        $this->path['d'] = '';
    }

    /**
     * Move the pen to the point.
     *
     * @param   number  $x
     * @param   number  $y
     * @param   boolean $relative
     * @return  void
     */
    public function moveTo($x, $y, $relative=true)
    {
        $c = $relative ? 'm' : 'M';
        $this->path['d'] .= "$c$x,$y ";
    }

    /**
     * Draw a line to the point.
     *
     * @param   number  $x
     * @param   number  $y
     * @param   boolean $relative
     * @return  void
     */
    public function lineTo($x, $y, $relative=true)
    {
        $c = $relative ? 'l' : 'L';
        $this->path['d'] .= "$c$x,$y ";
    }

    /**
     * Draw a horizontal line the x coordinate.
     *
     * @param   number  $x
     * @param   number  $y
     * @param   boolean $relative
     * @return  void
     */
    public function horizontalLineTo($x, $relative=true)
    {
        $c = $relative ? 'h' : 'H';
        $this->path['d'] .= "$c$x ";
    }

    /**
     * Draw a vertical line to the y coordinate.
     *
     * @param   number  $x
     * @param   number  $y
     * @param   boolean $relative
     * @return  void
     */
    public function verticalLineTo($y, $relative=true)
    {
        $c = $relative ? 'v' : 'V';
        $this->path['d'] .= "$c$y ";
    }

    /**
     * Draw an eliptical arch.
     *
     * @param   number  $rx x-axis radius
     * @param   number  $ry y-axis radius
     * @param   number  $xr x-axis rotation
     * @param   boolean $large  Take the long way round
     * @param   boolean $sweep  Draw arch inside or outside
     * @param   number  $x  end point
     * @param   number  $y  end point
     * @param   boolean $relative
     * @return  void
     */
    public function archTo($rx, $ry, $xr, $large, $sweep, $x, $y, $relative=true)
    {
        $c = $relative ? 'a' : 'A';
        $l = $large ? 1 : 0;
        $s = $sweep ? 1 : 0;
        $this->path['d'] .= "$c$rx,$ry $xr $l,$s $x,$y ";
    }

    /**
     * Close the path.
     *
     * @return  void
     */
    public function closePath()
    {
        $this->path['d'] .= 'z';
    }
}
