<?php

// $Id: menu.php,v 1.1 2006/03/27 09:05:33 mikhail Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <https://www.xoops.org>                             //
//  ------------------------------------------------------------------------ //
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

$adminmenu[1]['title'] = 'Konfiguration';
$adminmenu[1]['link'] = 'admin/configuration.php?selected_box=configuration&gID=1';
$adminmenu[2]['title'] = 'Catalog';
$adminmenu[2]['link'] = 'admin/categories.php?selected_box=catalog';
$adminmenu[3]['title'] = 'Customers';
$adminmenu[3]['link'] = 'admin/customers.php?selected_box=customers';
$adminmenu[4]['title'] = 'Reports';
$adminmenu[4]['link'] = 'admin/stats_products_purchased.php?selected_box=reports';
$adminmenu[5]['title'] = 'Modules';
$adminmenu[5]['link'] = 'admin/modules.php?selected_box=modules&set=payment';
$adminmenu[6]['title'] = 'Country / Tax';
$adminmenu[6]['link'] = 'admin/countries.php?selected_box=taxes';
$adminmenu[7]['title'] = 'Languages / Currencies';
$adminmenu[7]['link'] = 'admin/currencies.php?selected_box=localization';
$adminmenu[8]['title'] = 'Affiliate';
$adminmenu[8]['link'] = 'admin/affiliate.php?selected_box=affiliate';
$adminmenu[9]['title'] = 'Tools';
$adminmenu[9]['link'] = 'admin/backup.php?selected_box=tools';
