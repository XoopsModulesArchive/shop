<?php

/*
  $Id: object_info.php,v 1.1 2006/03/27 09:01:42 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

class objectInfo
{
    // class constructor

    public function __construct($object_array)
    {
        reset($object_array);

        while (list($key, $value) = each($object_array)) {
            $this->$key = tep_db_prepare_input($value);
        }
    }
}
