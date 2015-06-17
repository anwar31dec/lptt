<?php
//Run Query Function
function safe_query($query="")
	{
		if(empty($query))
		{return false;}

		$result=mysql_query($query) or die("Query Fails:"
		."<li> Errno=".mysql_errno()
		."<li> ErrDetails=".mysql_error()
		."<li>Query=".$query);
		return $result;
	}
?>