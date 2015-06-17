<?php
include ("define.inc");

define('SITEROOT', '/warp/');
define('JBASEURL', "http://localhost/warp/administrator/components/com_jcode/source/");

define('SITEDOCUMENT', $_SERVER['DOCUMENT_ROOT'] . SITEROOT);

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());
?>
