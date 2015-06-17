<?php
include_once ('database_conn.php');

$task = '';
if (isset($_REQUEST['action'])) {
	$task = $_REQUEST['action'];
}

switch($task) {
	case "checkUserAggrementStatus" :
		checkUserAggrementStatus();
		break;
	case "setUserAggrementStatus" :
		setUserAggrementStatus();
		break;	
	default :
		echo "{failure:true}";
		break;
}

function checkUserAggrementStatus() {	
	$UserId=$_REQUEST['UserId'];
	$sql="SELECT * from j323_users where id='".$UserId."' ";
	$result=mysql_query($sql);
	$total=mysql_num_rows($result);
	if($total>0){
		$row=mysql_fetch_object($result);
		echo $row->IsAgreeTerms==1?1:0;		
	}else
		echo 0;
}

function setUserAggrementStatus() {	
	$UserId=$_REQUEST['UserId'];
	$Status=$_REQUEST['Status'];
	$Status=$Status==true ? 1 :0;
	$sql="UPDATE j323_users set IsAgreeTerms='".$Status."' where id='".$UserId."' ";
	if(mysql_query($sql))
		echo 1;
	else
		echo 0;
}
?>