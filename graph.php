<?php
require_once('svg.php');

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
     * @var array
     */
    protected $data;

    /**
     * @var Svg The svg object to draw on.
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
     * @param   array   $data
     */
    public function __construct($width, $height, $data)
    {
        $this->width = $width;
        $this->height = $height;
        $this->data = $data;

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
        $max_val = max($this->data);
        $tick = $this->calculate_tick($max_val);
        $ticks = floor($max_val / $tick);
        $this->axis_ratio = $this->bounds->height / $max_val;

        $axis_style = array('stroke' => '#999', 'stroke-width' => 2, 'fill' => 'none');
        $tick_style = array('stroke' => '#bbb', 'stroke-width' => 1);

        // x and y axis
        $this->canvas->addPolyline(
            array(
                $this->bounds->left, $this->bounds->top,
                $this->bounds->left, $this->bounds->bottom,
                $this->bounds->right, $this->bounds->bottom
            ),
            $axis_style
        );

        // draw the tick lines
        for ($i = 1; $i < $ticks; $i++) {
            $tick_line_y = $this->bounds->bottom - ($this->axis_ratio * $tick * $i);
            $this->canvas->addLine(
                $this->bounds->left,
                $tick_line_y,
                $this->bounds->right,
                $tick_line_y,
                $tick_style
            );
            $this->canvas->addText($tick * $i, $this->bounds->left-15, $tick_line_y+5);
        }

        // top line
        $this->canvas->addLine(
            $this->bounds->left,
            $this->bounds->top,
            $this->bounds->right,
            $this->bounds->top,
            $tick_style
        );

        // top and bottom numbers
        $this->canvas->addText('0', $this->bounds->left-15, $this->bounds->bottom+5);
        $this->canvas->addText($max_val, $this->bounds->left-15, $this->bounds->top+5);
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
