<?php
include_once("define.inc");
$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());

mysql_query('SET CHARACTER SET utf8');


/*
include('language/lang_en.php');
include('language/lang_fr.php');
include('language/lang_switcher_report.php');

$task = '';
if (isset($_REQUEST['operation'])) {
	$task = $_REQUEST['operation'];
}

switch($task) {
	case "getCountryProfileParams1" :
		getCountryProfileParams1();
		break;
	default :
		echo "{failure:true}";
		break;
}
*/
 global $gTEXT;
	
	$lan = $_POST['lan'];
	 if($lan == 'en-GB'){
            $GroupName = 'GroupName';
        }else{
            $GroupName = 'GroupNameFrench';
        }

	$CountryId = $_GET['CountryId'];
	
	$query = "SELECT SQL_CALC_FOUND_ROWS YCProfileId, p.CountryId, p.ParamId, p.Year, BShow, ParamName,d.ItemGroupId,$GroupName GroupName, YCValue total 
			FROM `t_ycprofile` p
			INNER JOIN t_cprofileparams c ON c.ParamId=p.ParamId
			INNER JOIN t_itemgroup d ON d.ItemGroupId=p.ItemGroupId
			WHERE c.BShow=1 AND YEAR='2014' AND p.CountryId = $CountryId
			order by d.ItemGroupId,p.ParamId;";
			
	$result = mysql_query($query);
	$total = mysql_num_rows($result);
	if($total>0){
		$data = array();
		while($row = mysql_fetch_array( $result )) {
			$data[] = $row;
		}
		
		$tmpItemGroupId = -1;
		$output = '';
		$count = 0;
		foreach($data as $row){
		
		if($tmpItemGroupId != $row['ItemGroupId']){
			if($count>0){
				$output.="</table></div>";
				echo $output;
			}
			$count++;
			$output='';
			$output.="<div style='float:left; padding:5px 5px 5px 5px'><table border='1'>";
			$output.="<tr> <th colspan='2' style='text-align: center;'>".$row['GroupName']."</th> </tr>";
			$output.="<tr> <th>Parameter</th> <th>Value</th> </tr>";
			
			$tmpItemGroupId = $row['ItemGroupId'];		
			}
			
			$output.="<tr><td>"; 
			$output.=$row['ParamName'];
			$output.="</td><td>"; 
			$output.=$row['total'];
			$output.="</td></tr>"; 		
			
		}
		
		$output.="</table></div>";
		echo $output;
	}
	else{
	
		$output='';
		$output.="<div><table><tr><th>No data available</th></tr></table></div>";
		echo $output;
		
	}