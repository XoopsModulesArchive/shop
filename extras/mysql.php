<?php

$mysql_host = 'your_hostname.com'; // host name
$mysql_user = 'your_username'; // username
$mysql_pass = 'your_password'; // password
$mysql_database = 'your_database'; // database
// connect
mysql_connect($mysql_host, $mysql_user, $mysql_pass) || die($GLOBALS['xoopsDB']->error());
mysqli_select_db($GLOBALS['xoopsDB']->conn, $mysql_database) || die($GLOBALS['xoopsDB']->error());
