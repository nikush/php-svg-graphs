<?php

namespace Nikush\Svg;

/**
 * SVG path element.
 *
 * @author  Nikush Patel
 */
class Path
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
    public function __construct(\SimpleXMLElement $path)
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
