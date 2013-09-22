<?php
require_once('svg.php');

/**
 * Abstract graph class.
 */
class Graph
{
    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var array   The complete data set.
     */
    protected $data_set;

    /**
     * @var array   The values from the data set.
     */
    protected $data_values;

    /**
     * @var array   The labels from the data set.
     */
    protected $data_labels;

    /**
     * @var boolean Indicates if data set is labeled.
     */
    protected $data_is_labeled;

    /**
     * @var Svg The SVG object to draw on.
     */
    protected $canvas;

    /**
     * @var array   The boundarise for the graph.
     */
    protected $bounds;

    /**
     * @var number  How many pixels a data value of 1 represents
     */
    protected $axis_ratio;

    /**
     * @var int The selected colour in the pallete.
     */
    protected $current_colour = -1;

    /**
     * @var array   The colour pallete.
     */
    protected $colours = array(
        /*
        '#db8615',  // orange
        '#b2d314',  // green
        '#8e0e8e',  // purple
        '#16598d',  // blue
        '#db1515',  // red
        '#dbb915',  // yellow
        '#3b1e96',  // dark blue
        '#11af11',  // dark green
        '#b11168',  // pink
         */
        '#2BA6CB',  // light blue
        '#eeaa50',  // orange
        '#d0ea4e',  // green
        '#ca43ca',  // purple
        '#4c93c9',  // blue
        '#ee5050',  // red
        '#eed350',  // yellow
        '#7154ce',  // dark blue
        '#49d949',  // dark green
        '#da4998',  // pink
    );

    /**
     * Start a new graph.
     *
     * @param   int $width
     * @param   int $hight
     * @param   array   $data_set
     */
    public function __construct($width, $height, $data_set)
    {
        $this->width = $width;
        $this->height = $height;
        $this->data_set = $data_set;
        $this->data_values = array_values($data_set);
        $this->data_labels = array_keys($data_set);
        $this->data_is_labeled = !is_int($this->data_labels[0]);

        $margin = 10;
        $inner_width = $width - ($margin * 2);
        $inner_height = $height - ($margin * 2);

        $this->bounds = new StdClass;
        $this->bounds->left = $margin + 20;
        $this->bounds->right = $inner_width + $margin;
        $this->bounds->top = $margin + 10;
        $this->bounds->bottom = $inner_height + $margin - 10;
        $this->bounds->height = $this->bounds->bottom - $this->bounds->top;
        $this->bounds->width = $this->bounds->right - $this->bounds->left;

        $this->canvas = new Svg($width, $height);
    }

    /**
     * Render the graph to an SVG image.
     *
     * @return  string
     */
    public function render()
    {
        return $this->canvas->render();
    }

    /**
     * Draw the axis onto the graph.
     *
     * @return  void
     */
    protected function draw_axis()
    {
        $max_val = max($this->data_set);
        $tick = $this->calculate_tick($max_val);
        $ticks = floor($max_val / $tick);
        $this->axis_ratio = $this->bounds->height / $max_val;

        $axis_style = array('stroke' => '#999', 'stroke-width' => 1, 'fill' => 'none');
        $tick_style = array('stroke' => '#bbb', 'stroke-width' => 1);
        $y_axis_text_style = array('text-anchor' => 'end', 'font-size' => 10);

        $axis_g = $this->canvas->addGroup(0, 0);

        // x and y axis
        $axis = $axis_g->addPolyline($axis_style);
        $axis->addPoint($this->bounds->left + .5, $this->bounds->top);
        $axis->addPoint($this->bounds->left + .5, $this->bounds->bottom + .5);
        $axis->addPoint($this->bounds->right, $this->bounds->bottom + .5);

        // draw the tick lines
        for ($i = 1; $i < $ticks; $i++) {
            $tick_line_y = $this->bounds->bottom - ($this->axis_ratio * $tick * $i) + .5;
            $axis_g->addLine(
                $this->bounds->left,
                $tick_line_y,
                $this->bounds->right,
                $tick_line_y,
                $tick_style
            );
            $axis_g->addText($tick * $i, $this->bounds->left-5, $tick_line_y+5, $y_axis_text_style);
        }

        // top line
        $axis_g->addLine(
            $this->bounds->left,
            $this->bounds->top + .5,
            $this->bounds->right,
            $this->bounds->top + .5,
            $tick_style
        );

        // top and bottom numbers
        $axis_g->addText('0', $this->bounds->left-5, $this->bounds->bottom+5, $y_axis_text_style);
        $axis_g->addText($max_val, $this->bounds->left-5, $this->bounds->top+5, $y_axis_text_style);

        // x axis labels
        if ($this->data_is_labeled) {
            $label_style = array('text-anchor' => 'middle', 'font-size' => 10);
            $values = count($this->data_values);
            $section_width = $this->bounds->width / $values;
            $half_section = $section_width / 2;
            $label_g = $this->canvas->addGroup(
                $this->bounds->left + $half_section, 
                $this->bounds->bottom + 15,
                $label_style
            );

            foreach ($this->data_labels as $i => $label) {
                $label_g->addText(
                    $label,
                    $section_width * $i,
                    0
                );
            }
        }
    }

    /**
     * Select the next colour from the pallete.
     *
     * @return  string
     */
    protected function next_colour()
    {
        if (++$this->current_colour == count($this->colours))
            $this->current_colour = 0;

        return $this->colours[$this->current_colour];
    }

    /**
     * Calculate the tick inteval for axis numbering.
     *
     * @param   number  $val
     * @return  number
     */
    protected function calculate_tick($val)
    {
        $n = abs($val);
        if ($n < 10) return 1;
        if ($n < 100) return 10;
        if ($n < 100) return 100;
    }
}
