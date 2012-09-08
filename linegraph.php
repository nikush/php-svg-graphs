<?php

/**
 * Draw an SVG line graph.
 *
 * @auhtor  Nikush Patel
 */
class LineGraph extends Graph
{
    /**
     * Generate a line graph.
     *
     * @param   int $width
     * @param   int $hight
     * @param   array   $data
     */
    public function __construct($width, $height, $data)
    {
        parent::__construct($width, $height, $data);

        // background
        $bg_style = array('fill' => '#eee', 'stroke' => '#ddd', 'stroke-width' => 2);
        $this->canvas->addRect(0, 0, $width, $height, $bg_style);

        $this->draw_axis();

        $this->draw_line();
    }

    /**
     * Draw the bars for the graph
     *
     * @return  void
     */
    private function draw_line()
    {
        $values = count($this->data_values);
        $section_width = $this->bounds->width / ($values - 1);

        $line_style = array(
            'fill' => 'none',
            'stroke' => $this->next_colour(),
            'stroke-width' => 2
        );

        $points = array();

        for ($i = 0; $i < $values; $i++) {
            $x = $this->bounds->left + ($i * $section_width);
            $y = $this->bounds->bottom - ($this->axis_ratio * $this->data_values[$i]);
            array_push($points, $x, $y);
        }

        $this->canvas->addPolyline($points, $line_style);
    }
}
