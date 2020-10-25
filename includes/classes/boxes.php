<?php

/*
  $Id: boxes.php,v 1.1 2006/03/27 09:08:11 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

class tableBox
{
    public $table_border = '0';

    public $table_width = '100%';

    public $table_cellspacing = '0';

    public $table_cellpadding = '2';

    public $table_parameters = '';

    public $table_row_parameters = '';

    public $table_data_parameters = '';

    // class constructor

    public function __construct($contents, $direct_output = false)
    {
        $tableBox_string = '<table border="' . tep_output_string($this->table_border) . '" width="' . tep_output_string($this->table_width) . '" cellspacing="' . tep_output_string($this->table_cellspacing) . '" cellpadding="' . tep_output_string($this->table_cellpadding) . '"';

        if (tep_not_null($this->table_parameters)) {
            $tableBox_string .= ' ' . $this->table_parameters;
        }

        $tableBox_string .= '>' . "\n";

        for ($i = 0, $n = count($contents); $i < $n; $i++) {
            if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) {
                $tableBox_string .= $contents[$i]['form'] . "\n";
            }

            $tableBox_string .= '  <tr';

            if (tep_not_null($this->table_row_parameters)) {
                $tableBox_string .= ' ' . $this->table_row_parameters;
            }

            if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) {
                $tableBox_string .= ' ' . $contents[$i]['params'];
            }

            $tableBox_string .= '>' . "\n";

            if (isset($contents[$i][0]) && is_array($contents[$i][0])) {
                for ($x = 0, $n2 = count($contents[$i]); $x < $n2; $x++) {
                    if (isset($contents[$i][$x]['text']) && tep_not_null($contents[$i][$x]['text'])) {
                        $tableBox_string .= '    <td';

                        if (isset($contents[$i][$x]['align']) && tep_not_null($contents[$i][$x]['align'])) {
                            $tableBox_string .= ' align="' . tep_output_string($contents[$i][$x]['align']) . '"';
                        }

                        if (isset($contents[$i][$x]['params']) && tep_not_null($contents[$i][$x]['params'])) {
                            $tableBox_string .= ' ' . $contents[$i][$x]['params'];
                        } elseif (tep_not_null($this->table_data_parameters)) {
                            $tableBox_string .= ' ' . $this->table_data_parameters;
                        }

                        $tableBox_string .= '>';

                        if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) {
                            $tableBox_string .= $contents[$i][$x]['form'];
                        }

                        $tableBox_string .= $contents[$i][$x]['text'];

                        if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) {
                            $tableBox_string .= '</form>';
                        }

                        $tableBox_string .= '</td>' . "\n";
                    }
                }
            } else {
                $tableBox_string .= '    <td';

                if (isset($contents[$i]['align']) && tep_not_null($contents[$i]['align'])) {
                    $tableBox_string .= ' align="' . tep_output_string($contents[$i]['align']) . '"';
                }

                if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) {
                    $tableBox_string .= ' ' . $contents[$i]['params'];
                } elseif (tep_not_null($this->table_data_parameters)) {
                    $tableBox_string .= ' ' . $this->table_data_parameters;
                }

                $tableBox_string .= '>' . $contents[$i]['text'] . '</td>' . "\n";
            }

            $tableBox_string .= '  </tr>' . "\n";

            if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) {
                $tableBox_string .= '</form>' . "\n";
            }
        }

        $tableBox_string .= '</table>' . "\n";

        if (true === $direct_output) {
            echo $tableBox_string;
        }

        return $tableBox_string;
    }
}

class infoBox extends tableBox
{
    public function __construct($contents)
    {
        $info_box_contents = [];

        $info_box_contents[] = ['text' => $this->infoBoxContents($contents)];

        $this->table_cellpadding = '1';

        $this->table_parameters = 'class="infoBox"';

        parent::__construct($info_box_contents, true);
    }

    public function infoBoxContents($contents)
    {
        $this->table_cellpadding = '3';

        $this->table_parameters = 'class="infoBoxContents"';

        $info_box_contents = [];

        $info_box_contents[] = [['text' => tep_draw_separator('pixel_trans.gif', '100%', '1')]];

        for ($i = 0, $n = count($contents); $i < $n; $i++) {
            $info_box_contents[] = [
                [
                    'align' => ($contents[$i]['align'] ?? ''),
'form' => ($contents[$i]['form'] ?? ''),
'params' => 'class="boxText"',
'text' => ($contents[$i]['text'] ?? ''),
                ],
            ];
        }

        $info_box_contents[] = [['text' => tep_draw_separator('pixel_trans.gif', '100%', '1')]];

        return parent::__construct($info_box_contents);
    }
}

class infoBoxHeading extends tableBox
{
    public function __construct($contents, $left_corner = true, $right_corner = true, $right_arrow = false)
    {
        $this->table_cellpadding = '0';

        if (true === $left_corner) {
            $left_corner = tep_image(DIR_WS_IMAGES . 'infobox/corner_left.gif');
        } else {
            $left_corner = tep_image(DIR_WS_IMAGES . 'infobox/corner_right_left.gif');
        }

        if (true === $right_arrow) {
            $right_arrow = '<a href="' . $right_arrow . '">' . tep_image(DIR_WS_IMAGES . 'infobox/arrow_right.gif', ICON_ARROW_RIGHT) . '</a>';
        } else {
            $right_arrow = '';
        }

        if (true === $right_corner) {
            $right_corner = $right_arrow . tep_image(DIR_WS_IMAGES . 'infobox/corner_right.gif');
        } else {
            $right_corner = $right_arrow . tep_draw_separator('pixel_trans.gif', '11', '14');
        }

        $info_box_contents = [];

        $info_box_contents[] = [
            [
                'params' => 'height="14" class="infoBoxHeading"',
'text' => $left_corner,
            ],
            [
                'params' => 'width="100%" height="14" class="infoBoxHeading"',
'text' => $contents[0]['text'],
            ],
            [
                'params' => 'height="14" class="infoBoxHeading" nowrap',
'text' => $right_corner,
            ],
        ];

        parent::__construct($info_box_contents, true);
    }
}

class contentBox extends tableBox
{
    public function __construct($contents)
    {
        $info_box_contents = [];

        $info_box_contents[] = ['text' => $this->contentBoxContents($contents)];

        $this->table_cellpadding = '1';

        $this->table_parameters = 'class="infoBox"';

        parent::__construct($info_box_contents, true);
    }

    public function contentBoxContents($contents)
    {
        $this->table_cellpadding = '4';

        $this->table_parameters = 'class="infoBoxContents"';

        return parent::__construct($contents);
    }
}

class contentBoxHeading extends tableBox
{
    public function __construct($contents)
    {
        $this->table_width = '100%';

        $this->table_cellpadding = '0';

        $info_box_contents = [];

        $info_box_contents[] = [
            [
                'params' => 'height="14" class="infoBoxHeading"',
'text' => tep_image(DIR_WS_IMAGES . 'infobox/corner_left.gif'),
            ],
            [
                'params' => 'height="14" class="infoBoxHeading" width="100%"',
'text' => $contents[0]['text'],
            ],
            [
                'params' => 'height="14" class="infoBoxHeading"',
'text' => tep_image(DIR_WS_IMAGES . 'infobox/corner_right_left.gif'),
            ],
        ];

        parent::__construct($info_box_contents, true);
    }
}

class errorBox extends tableBox
{
    public function __construct($contents)
    {
        $this->table_data_parameters = 'class="errorBox"';

        parent::__construct($contents, true);
    }
}

class productListingBox extends tableBox
{
    public function __construct($contents)
    {
        $this->table_parameters = 'class="productListing"';

        parent::__construct($contents, true);
    }
}
