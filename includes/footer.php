<?php
/*
  $Id: footer.php,v 1.1 2006/03/27 08:50:00 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

require DIR_WS_INCLUDES . 'counter.php';
?>
<table border="0" width="100%" cellspacing="0" cellpadding="1">
    <tr class="blockTitle">
        <td class="blockTitle">&nbsp;&nbsp;<?php echo strftime(DATE_FORMAT_LONG); ?>&nbsp;&nbsp;</td>
        <td align="right" class="blockTitle">&nbsp;&nbsp;<?php echo $counter_now . ' ' . FOOTER_TEXT_REQUESTS_SINCE . ' ' . $counter_startdate_formatted; ?>&nbsp;&nbsp;</td>
    </tr>

</table>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td align="center" valign="middle" class="smallText">
            <b><?php echo " &nbsp; Your IP Address is: " . $REMOTE_ADDR; ?></b><br><br>
        </td>
    </tr>
    <tr>
        <td align="center" class="smallText">
            <?php
            /*
              The following copyright announcement can only be
              appropriately modified or removed if the layout of
              the site theme has been modified to distinguish
              itself from the default osCommerce-copyrighted
              theme.

              For more information please read the following
              Frequently Asked Questions entry on the osCommerce
              support site:

              http://www.oscommerce.com/community.php/faq,26/q,50

              Please leave this comment intact together with the
              following copyright announcement.
            */

            echo FOOTER_TEXT_BODY
            ?>
        </td>
    </tr>
</table>
<?php
if ($banner = tep_banner_exists('dynamic', '468x50')) {
                ?>
    <br>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center"><?php echo tep_display_banner('static', $banner); ?></td>
        </tr>
    </table>
    <?php
            }
?>
