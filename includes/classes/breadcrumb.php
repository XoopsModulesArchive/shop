<?php

/*
  $Id: breadcrumb.php,v 1.1 2006/03/27 09:08:11 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

class breadcrumb
{
    public $_trail;

    public function __construct()
    {
        $this->reset();
    }

    public function reset()
    {
        $this->_trail = [];
    }

    public function add($title, $link = '')
    {
        $this->_trail[] = ['title' => $title, 'link' => $link];
    }

    public function trail($separator = ' - ')
    {
        $trail_string = '';

        for ($i = 0, $n = count($this->_trail); $i < $n; $i++) {
            if (isset($this->_trail[$i]['link']) && tep_not_null($this->_trail[$i]['link'])) {
                $trail_string .= '<a href="' . $this->_trail[$i]['link'] . '" class="headerNavigation">' . $this->_trail[$i]['title'] . '</a>';
            } else {
                $trail_string .= $this->_trail[$i]['title'];
            }

            if (($i + 1) < $n) {
                $trail_string .= $separator;
            }
        }

        return $trail_string;
    }
}
