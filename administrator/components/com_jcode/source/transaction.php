<?php
require_once ('fb.php');
include "universal_function_lib.php";

try 
{
	$resultCount = 0;
	$error = '';
	mysql_query('SET autocommit=0;');
	mysql_query('START TRANSACTION;');
	mysql_query('SELECT @A:=SUM(salary) FROM table1 WHERE type=1;');
	$result1 = mysql_query('UPDATE table2 SET summary5 = @A WHERE type=1;');	
	$error = mysql_error()."</br>";
	$resultCount = $result1 == '' ? 0 : 1;
	$result2 = mysql_query('UPDATE table1 SET salary9 = 9000 WHERE type=1;');
	$resultCount += $result2 == '' ? 0 : 1;
	$error .= mysql_error();
	
	if ($resultCount != 2)
		throw new Exception("Query error:</br>".mysql_real_escape_string($error));
	
	mysql_query('COMMIT;');
} 
catch (Exception $e) 
{
	mysql_query('ROLLBACK;');
	echo $e->getMessage();		
}

?> 