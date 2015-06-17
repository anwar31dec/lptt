<?php
$connection=mysql_connect('localhost','newospsa_admin','@JnLa?8yMXkU') or die("Could not connect to server");


$db=mysql_select_db('newospsa_db',$connection) or die("Could not select database");

$sql = "select count(*),CFMStockId,FacilityId,MonthId,Year from t_cfm_stockstatus group by  CFMStockId,FacilityId,MonthId,Year
having count(*)<>55;";

		$result = mysql_query($sql);
		
		while ($row = mysql_fetch_object($result)) {
		
	$sql1 = "select ItemNo from t_itemlist where ItemNo not in (select ItemNo from t_cfm_stockstatus where CFMStockId =". $row -> CFMStockId.")";
			$result1 = mysql_query($sql1);
			while ($row1 = mysql_fetch_object($result1)) {
				$query="INSERT INTO `t_cfm_stockstatus` ( `CFMStockId`, `CountryId`, `FacilityId`, `MonthId`, `Year`, `UserId`, `ItemNo`, `ItemGroupId`, `OpStock`, `OpStock_C`, `ReceiveQty`, `DispenseQty`, `AdjustQty`, `AdjustId`, `StockoutDays`, `StockOutReasonId`, `ClStock`, `ClStock_C`, `ClStockSourceId`, `MOS`, `AMC`, `AMC_C`, `AmcChangeReasonId`, `MaxQty`, `OrderQty`, `ActualQty`, `OUReasonId`, `LastEditTime`, `FirstTimeStamp`) VALUES
				(".$row -> CFMStockId.", 1, ".$row -> FacilityId.", ".$row -> MonthId.", '".$row -> Year."', '', ".$row1 -> ItemNo.", 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2015-05-09 16:54:51', '2015-05-09 10:54:51');";
				
				 mysql_query($query);

			}
		}
?>

