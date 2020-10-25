<?php

/*
  $Id: application_bottom.php,v 1.1 2006/03/27 08:46:30 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

// close session (store variables)
tep_session_close();

if (STORE_PAGE_PARSE_TIME == 'true') {
    if (!is_object($logger)) {
        $logger = new logger();
    }

    echo $logger->timer_stop(DISPLAY_PAGE_PARSE_TIME);
}
