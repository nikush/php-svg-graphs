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
        $max_width = 20;
        $values = count($this->data_values);

        $section_width = $this->bounds->width / $values;
        $bar_width = min($max_width, $section_width);
        $bar_padding = ($section_width - $bar_width) / 2;

        $bar_style = array('fill' => $this->next_colour());
        $g = $this->canvas->addGroup(
            $this->bounds->left,
            $this->bounds->top,
            $bar_style
        );

        for ($i = 0; $i < $values; $i++) {
            $bar_height = $this->data_values[$i] * $this->axis_ratio;

            $g->addRect(
                1 + ($section_width * $i) + $bar_padding,
                $this->bounds->height - round($bar_height) + .5,
                $bar_width-1,
                $bar_height
            );
        }
    }
}
