<?php

// $Id: search.php,v 1.1 2006/03/27 08:38:55 mikhail Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <https://www.xoops.org>                             //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

function b_shop_search()
{
    $block = [];

    $block['title'] = BOX_HEADING_SEARCH;

    $block['datum'] = '2004-08-25';

    $block['content'] = '';

    $hidden_session_id = (SID) ? tep_draw_hidden_field([tep_session_name()]) : '';

    $block['content'] .= '<br><center><form name="quick_find" method="get" action="'
                         . tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL')
                         . '">'
                         . $hidden_session_id
                         . '<div align="center"><input type="text" name="keywords" size="13" maxlength="30" value="'
                         . htmlspecialchars(stripslashes(@$keywords), ENT_QUOTES | ENT_HTML5)
                         . '">&nbsp;'
                         . tep_image_submit('button_quick_find.gif', BOX_HEADING_SEARCH)
                         . '</div>'
                         . BOX_SEARCH_TEXT
                         . '<br><div align="center"><a href="'
                         . tep_href_link(FILENAME_ADVANCED_SEARCH, '', 'NONSSL')
                         . '">&nbsp;<b>'
                         . BOX_SEARCH_ADVANCED_SEARCH
                         . '</b>&nbsp;</a></div></form></center><br>';

    return $block;
}


 
