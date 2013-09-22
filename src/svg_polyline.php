<?php

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
