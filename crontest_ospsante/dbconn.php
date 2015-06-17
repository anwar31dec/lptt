<?php
include ("define.inc");

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die("Could not connect: " . mysql_error());
if (!mysql_select_db(DBNAME, $conn)) {
	echo 'Could not select database';
	exit ;
}
