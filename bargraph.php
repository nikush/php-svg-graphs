<?php

/**
 * Draw an SVG bar graph.
 *
 * @auhtor  Nikush Patel
 */
class BarGraph extends Graph
{
    /**
     * Generate a bar graph.
     *
     * @param   int $width
     * @param   int $hight
     * @param   array   $data_set
     */
    public function __construct($width, $height, $data_set)
    {
        parent::__construct($width, $height, $data_set);

        // background
        $bg_style = array('fill' => '#eee', 'stroke' => '#ddd', 'stroke-width' => 2);
        $this->canvas->addRect(0, 0, $width, $height, $bg_style);

        $this->draw_axis();

        $this->draw_bars();
    }

    /**
     * Draw the bars for the graph
     *
     * @return  void
     */
    private function draw_bars()
    {
        $values = count($this->data_values);
        $section_width = $this->bounds->width / $values;

        $bar_style = array('fill' => $this->next_colour());

        for ($i = 0; $i < $values; $i++) {
            $bar_height = $this->data_values[$i] * $this->axis_ratio;

            $this->canvas->addRect(
                $this->bounds->left + ($section_width * $i) + 5,
                $this->bounds->bottom - $bar_height,
                $section_width-10,
                $bar_height,
                $bar_style
            );
        }
    }
}
