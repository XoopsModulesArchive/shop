<?php

/*
  $Id: box.php,v 1.1 2006/03/27 09:01:42 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License

  Example usage:

  $heading = array();
  $heading[] = array('params' => 'class="menuBoxHeading"',
                     'text'  => BOX_HEADING_TOOLS,
                     'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=tools'));

  $contents = array();
  $contents[] = array('text'  => SOME_TEXT);

  $box = new box;
  echo $box->infoBox($heading, $contents);
*/

class box extends tableBlock
{
    public function __construct()
    {
        $this->heading = [];

        $this->contents = [];
    }

    public function infoBox($heading, $contents)
    {
        $this->table_row_parameters = 'class="infoBoxHeading"';

        $this->table_data_parameters = 'class="infoBoxHeading"';

        $this->heading = parent::__construct($heading);

        $this->table_row_parameters = '';

        $this->table_data_parameters = 'class="infoBoxContent"';

        $this->contents = parent::__construct($contents);

        return $this->heading . $this->contents;
    }

    public function menuBox($heading, $contents)
    {
        $this->table_data_parameters = 'class="menuBoxHeading"';

        if (isset($heading[0]['link'])) {
            $this->table_data_parameters .= ' onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . $heading[0]['link'] . '\'"';

            $heading[0]['text'] = '&nbsp;<a href="' . $heading[0]['link'] . '" class="menuBoxHeadingLink">' . $heading[0]['text'] . '</a>&nbsp;';
        } else {
            $heading[0]['text'] = '&nbsp;' . $heading[0]['text'] . '&nbsp;';
        }

        $this->heading = parent::__construct($heading);

        $this->table_data_parameters = 'class="menuBoxContent"';

        $this->contents = parent::__construct($contents);

        return $this->heading . $this->contents;
    }
}
