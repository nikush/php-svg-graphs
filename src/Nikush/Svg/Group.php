<?php

namespace Nikush\Svg;

/**
 * SVG group element.
 *
 * @author  Nikush Patel
 */
class Group extends Svg
{
    /**
     * Wraps the Svg class around a blank group element to decorate it with all
     * of the Svg class' methods.
     *
     * @param   SimpleXMLElement    $g
     */
    public function __construct(\SimpleXMLElement $g)
    {
        $this->root_node = $g;
    }
}
