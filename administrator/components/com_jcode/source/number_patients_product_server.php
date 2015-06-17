<?php
include_once ('database_conn.php');
include_once ("function_lib.php");

//print_r($_POST);

$task = '';
if (isset($_REQUEST['action'])) {
	$task = $_REQUEST['action'];
}

switch($task) {
	case 'getNumberPatientsProduct' : 
		getNumberPatientsProduct();
		break;	
	default :
		echo "{failure:true}";
		break;
}
function numberToMonth($i) {
	$i=trim($i);
	if ($i == 1)
		return "Jan ";
	else if ($i == 2)
		return "Feb";
  	else if ($i == 3)
		return "Mar ";
   	else if ($i == 4)
		return "Apr ";
	else if ($i == 5)
		return "May ";
   	else if ($i == 6)
		return "Jun ";
	else if ($i == 7)
		return "Jul ";
	else if ($i == 8)
		return "Aug ";
		else if ($i == 9)
		return "Sep ";
		else if ($i == 10)
		return "Oct ";
		else if ($i == 11)
		return "Nov ";
		else if ($i == 12)
		return "Dec ";
			
		
}
	
function getNumberPatientsProduct(){
	
	$monthId = $_POST['MonthId'];
	$year = $_POST['YearId'];
	$countryId = $_POST['CountryId'];
	$itemGroupId = $_POST['ItemGroupId'];
	
	$sWhere = "";
	if ($_POST['sSearch'] != "") {
		$sWhere = " WHERE (a.ItemName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
					OR " . " a.ClStock LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' 
					OR " . " a.MOS LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' 
					OR " . " b.Qty LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
					OR " . " c.TotalPatient LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' ) ";							
	}
	
	//$sOrder = " ORDER BY ItemName,ClStock,StockOnOrder,MOS,StockOnOrderMOS,TotalMOS ";
	 
	   $sLimit = "";
	if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
		$sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
	}
	 if (isset($_GET['iSortCol_0'])) {
		 $sOrder = "ORDER BY  ";
		 for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
			 if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
				 $sOrder .= "`" . $aColumns[intval($_GET['iSortCol_' . $i])] . "` " . ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
			 }
		 }
		 $sOrder = substr_replace($sOrder, "", -2);
		 if ($sOrder == "ORDER BY") {
			 $sOrder = "";
		 }
	 }
	
	$currentYearMonth = $_POST['YearId'] . "-" . $_POST['MonthId'] . "-" . "01";
	
	$monthList = array('1'=>'January','2'=>'February','3'=>'March','4'=>'April','5'=>'May','6'=>'June','7'=>'July','8'=>'August','9'=>'September','10'=>'October','11'=>'November','12'=>'December');
	
	$sQuery =" SELECT ItemName, AMC, ClStock, FORMAT(MOS,1) MOS, Qty StockOnOrder, FORMAT(Qty/AMC,1) StockOnOrderMOS, (ifnull(FORMAT(MOS,1),0)+ifnull(FORMAT(Qty/AMC,1),0)) TotalMOS
,a.ItemNo,TotalPatient
 FROM 
				(SELECT
				 t_cnm_masterstockstatus.CountryId,
				  t_itemlist.ItemNo,
				  t_itemlist.ItemName,
				  SUM(t_cnm_stockstatus.AMC)    AMC,
				  SUM(t_cnm_stockstatus.ClStock)    ClStock,
				  SUM(t_cnm_stockstatus.MOS)    MOS
				FROM t_cnm_stockstatus
				  INNER JOIN t_cnm_masterstockstatus
				    ON (t_cnm_stockstatus.CNMStockId = t_cnm_masterstockstatus.CNMStockId)
				  INNER JOIN t_itemlist
				    ON (t_cnm_stockstatus.ItemNo = t_itemlist.ItemNo)
				WHERE (t_cnm_masterstockstatus.Year = '$year'
				       AND t_cnm_masterstockstatus.MonthId =$monthId
				       AND t_cnm_masterstockstatus.CountryId = $countryId
				       AND t_cnm_masterstockstatus.ItemGroupId =$itemGroupId
				       AND t_cnm_masterstockstatus.StatusId = 5)
				GROUP BY t_cnm_masterstockstatus.CountryId, t_itemlist.ItemNo, t_itemlist.ItemName) a 
				LEFT JOIN (SELECT
				    CountryId
				    , ItemNo
				    , SUM(Qty) Qty
				FROM
				    t_agencyshipment
				WHERE (ShipmentDate > CAST('$currentYearMonth' AS DATETIME)  AND ShipmentStatusId = 3)
				GROUP BY CountryId, ItemNo) b
				ON a.CountryId = b.CountryId AND a.ItemNo = b.ItemNo
				LEFT JOIN (SELECT t_cnm_regimenpatient.CountryId,ItemNo,sum(TotalPatient) as TotalPatient
				from t_cnm_regimenpatient
				Inner Join t_regimenitems ON t_cnm_regimenpatient.RegimenId=t_regimenitems.RegimenId
				Group By t_cnm_regimenpatient.CountryId,ItemNo) c ON a.CountryId = c.CountryId AND a.ItemNo = c.ItemNo
				 ".$sWhere."
				HAVING MOS>0 OR StockOnOrderMOS>0 
				 $sOrder
                $sLimit;"; 
	
	
	
				
	$rResult = mysql_query($sQuery);
	/*while ($row = mysql_fetch_assoc($rResult)) {
		
	}*/
	
	
	 $total = mysql_num_rows($rResult);
	$sQuery1 = "SELECT FOUND_ROWS()";
	$rResultFilterTotal = mysql_query($sQuery1);
	$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];
	$sOutput = '{';
	$sOutput .= '"sEcho": ' . intval($_POST['sEcho']) . ', ';
	$sOutput .= '"iTotalRecords": ' . $iFilteredTotal . ', ';
	$sOutput .= '"iTotalDisplayRecords": ' . $iFilteredTotal . ', ';
	$sOutput .= '"aaData": [ ';
	$serial = $_POST['iDisplayStart'] + 1;
	$f = 0;
	while ($aRow = mysql_fetch_array($rResult)) {

		$ItemName = trim(preg_replace('/\s+/', ' ', addslashes($aRow['ItemName'])));
		
	 $addmonth=number_format($aRow['TotalMOS']);

    $currentYearMonth = $year . "-" . $monthId . "-" . "01";			
	$lastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "$addmonth month"));
	 
	 $temp=explode('-',$lastYearMonth);
	 $strMonth=numberToMonth($temp[1]);
	 $lastYearMonth=$strMonth.'  , '.$temp[0];

		if ($f++)
        $sOutput .= ',';
		$sOutput .= "[";		
		$sOutput .= '"' . $serial++ . '",';
		$sOutput .= '"' . $ItemName . '",';
		$sOutput .= '"' . number_format($aRow['TotalPatient']) . '",';
        $sOutput .= '"' . number_format($aRow['ClStock']) . '",';
		 $sOutput .= '"' . number_format($aRow['MOS'],1) . '",';
 	    $sOutput .= '"' . number_format($aRow['StockOnOrder']) . '",';
        $sOutput .= '"' . number_format($aRow['StockOnOrderMOS'],1) . '",';
		 $sOutput .= '"' . number_format($aRow['TotalMOS'],1) . '",';
		$sOutput .= '"' . $lastYearMonth . '"';
      
    	$sOutput .= "]";
	}
	$sOutput .= '] }';
	echo $sOutput;  
	
function fnColumnToField_formulation($i) {
	if ($i == 1)
		return "ItemName ";
	if ($i == 2)
		return "TotalPatient ";
  	else if ($i == 3)
		return "ClStock ";
		else if ($i == 4)
		return "MOS";
   	else if ($i == 5)
		return "StockOnOrder ";
	else if ($i == 6)
		return "StockOnOrderMOS ";
   	else if ($i == 7)
		return "TotalMOS ";
		else if ($i == 8)
		return "ProjectedDate ";
			
		
}
	
	

	
	
	
	

	// $output = array('Categories'=>array(), 'Series'=>array(), 'Colors'=>array('#0000FF', '#00FF00'));
// 	
	// $output2 =  array('name'=>'MOS (Current)', 'data'=>array());
	// $output3 = array('name'=>'MOS (Ordered)', 'data'=>array());
// 
// 			
		// $output['Categories'][] = $row['ItemName'];	
		// if(!is_null($row['MOS']))	
			// settype($row['MOS'], "float");
		// if(!is_null($row['StockOnOrderMOS']))
			// settype($row['StockOnOrderMOS'], "float");	
		// $output2['data'][] =  $row['MOS'];
		// $output3['data'][] =  $row['StockOnOrderMOS'];	
	// }	
// 		
	// $output['Series'][] = $output3;
	// $output['Series'][] = $output2;	
// 	
	// $output['Height'] = count($output['Categories']) * 40;
	// $output['MonthYear'] = $monthList[$monthId].' '.$year;
// 	
	// echo json_encode($output);	

}

?>









