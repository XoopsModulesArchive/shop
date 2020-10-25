<?php
/*  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Integrate XOOPS-JP 2.0 into oscommerce 2.2 ms1 by oldpa c 2004 version 1.0 
  http://oldpa.adsldns.org
  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

?>
<!-- loginbox //-->

<?php
function b_shop_osc_login()
{
    if (!tep_isset(\ < ? php
/*  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Integrate XOOPS-JP 2.0 into oscommerce 2.2 ms1 by oldpa c 2004 version 1.0 
  http://oldpa.adsldns.org
  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

?>
<!-- loginbox //-->

<?php
function b_shop_osc_login()
{
    if (!tep_session_is_registered('customer_id')) {
        global $languages_id;
        $block            = [];
        $block['title']   = "osclogin";
        $block['content'] = "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
            <form name=\"login\" method=\"post\" action=\"" . tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL') . "\">
              <tr>
                <td align=\"left\" class=\"main\">
                  " . BOX_LOGINBOX_USERNAME . "
                </td>
              </tr>
              <tr>
                <td align=\"left\" class=\"main\">
                  <input type=\"text\" name=\"customers_lastname\" maxlength=\"30\" size=\"18\" value=\"\">
                </td>
              </tr>
              <tr>
                <td align=\"left\" class=\"main\">
                  " . BOX_LOGINBOX_PASSWORD . "
                </td>
              </tr>
              <tr>
                <td align=\"left\" class=\"main\">
                  <input type=\"password\" name=\"password\" maxlength=\"40\" size=\"18\" value=\"\"
                </td>
              </tr>
              <tr>
                <td class=\"main\" align=\"left\">
                  " . tep_image_submit('button_login.gif', IMAGE_BUTTON_LOGIN) . "
                </td>
              </tr>
            </form>
			<tr>
                <td class=\"main\" align=\"left\">
                  " . "<a href=\"" . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . "\">" . BOX_LOST_PASS . "</a>
                </td>
			  </tr>
			  <tr>
                <td class=\"main\" align=\"left\">
                  " . "<a href=\"" . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . "\">" . BOX_CREATE_ACCOUNT . "</a>
                </td>
			  </tr>
            </table>";
    }
    return $block;
}

?>
SESSION['customer_id'])) {
        global $languages_id;
        $block            = [];
        $block['title']   = "osclogin";
        $block['content'] = "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
            <form name=\"login\" method=\"post\" action=\"" . tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL') . "\">
              <tr>
                <td align=\"left\" class=\"main\">
                  " . BOX_LOGINBOX_USERNAME . "
                </td>
              </tr>
              <tr>
                <td align=\"left\" class=\"main\">
                  <input type=\"text\" name=\"customers_lastname\" maxlength=\"30\" size=\"18\" value=\"\">
                </td>
              </tr>
              <tr>
                <td align=\"left\" class=\"main\">
                  " . BOX_LOGINBOX_PASSWORD . "
                </td>
              </tr>
              <tr>
                <td align=\"left\" class=\"main\">
                  <input type=\"password\" name=\"password\" maxlength=\"40\" size=\"18\" value=\"\"
                </td>
              </tr>
              <tr>
                <td class=\"main\" align=\"left\">
                  " . tep_image_submit('button_login.gif', IMAGE_BUTTON_LOGIN) . "
                </td>
              </tr>
            </form>
			<tr>
                <td class=\"main\" align=\"left\">
                  " . "<a href=\"" . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . "\">" . BOX_LOST_PASS . "</a>
                </td>
			  </tr>
			  <tr>
                <td class=\"main\" align=\"left\">
                  " . "<a href=\"" . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . "\">" . BOX_CREATE_ACCOUNT . "</a>
                </td>
			  </tr>
            </table>";
    }
    return $block;
}

?>
