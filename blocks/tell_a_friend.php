<?php

// $Id: tell_a_friend.php,v 1.1 2006/03/27 08:38:55 mikhail Exp $
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

function b_shop_tellafriend()
{
    $block = [];

    $block['title'] = BOX_HEADING_TELL_A_FRIEND;

    $block['datum'] = '2004-07-06';

    $block['content'] = '';

    $hidden_session_id = (SID) ? tep_hide_fields([tep_session_name()]) : '';

    $tellafriend = '<form name="tell_a_friend" method="get" action="' . tep_href_link(FILENAME_TELL_A_FRIEND) . '">';

    $tellafriendinput = '<div align="center"><input type="text" name="send_to" size="10">&nbsp;'
                        . tep_image_submit('button_tell_a_friend.gif', BOX_HEADING_TELL_A_FRIEND)
                        . tep_draw_hidden_field('products_id', @$_GET['products_id'])
                        . tep_hide_session_id()
                        . '<br>'
                        . BOX_TELL_A_FRIEND_TEXT
                        . '';

    if (SID) {
        $tellafriend .= tep_hide_fields([tep_session_name()]);
    }

    $block['content'] .= $tellafriend;

    $block['content'] .= $hidden_session_id;

    $block['content'] .= $tellafriendinput;

    return $block;
}



