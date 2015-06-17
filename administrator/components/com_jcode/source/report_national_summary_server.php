<?php
include ("define.inc");
$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());
mysql_query('SET CHARACTER SET utf8');

$task = '';
if (isset($_POST['action'])) {
	$task = $_POST['action'];
} else if (isset($_GET['action'])) {
	$task = $_GET['action'];
}

switch($task) {
	case 'getSummaryChart' :
		getSummaryChart();
		break;
	case 'getSummaryData' :
		getSummaryData();
		break;	
   	default :
		echo "{failure:true}";
		break;
}

function getSummaryChart(){
    
    $Mos = array();
    $MosType = array();
    $item_name = array();
    $mos = array();
    $barcolor = array();

        
    $sql = "SELECT MosTypeName, MinMos, MaxMos, ColorCode FROM t_mostype ORDER BY MosTypeId";
   	$result = mysql_query($sql);
   	while ($aRow = mysql_fetch_array($result)) {
   	    $Mos['Min'] = $aRow['MinMos'];
        $Mos['Max'] = $aRow['MaxMos'];
        $Mos['ColorCode'] = $aRow['ColorCode'];
        array_push($MosType, $Mos);     
    }
      
	$lan = $_POST['lan'];
	 if($lan == 'en-GB'){
           $MonthList = array('1'=>'January','2'=>'February','3'=>'March','4'=>'April','5'=>'May','6'=>'June','7'=>'July','8'=>'August','9'=>'September','10'=>'October','11'=>'November','12'=>'December');
        }else{
            $MonthList = array('1'=>'Janvier','2'=>'Février','3'=>'Mars','4'=>'Avril','5'=>'Mai','6'=>'Juin','7'=>'Juillet','8'=>'Août','9'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'Décembre');
        }  
	  
	    
       // print_r($MonthList);
   
    $Year = $_POST['Year'];
    $ItemGroupId = $_POST['ItemGroup'];
    $Month = $_POST['Month']; //echo $Month;
    $CountryId = $_POST['Country']; //echo $CountryId;
    $Reportby = $_POST['Reportby'];
	
   if($ItemGroupId > 0){
       $sql = "  SELECT a.ItemNo, b.ItemName, SUM(DispenseQty) ReportedConsumption, SUM(ClStock) ReportedClosingBalance, SUM(AMC) AMC, IFNULL(((SUM(ClStock))/(SUM(AMC))),0) MOS                 
            	FROM t_cnm_stockstatus a 
                INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo AND b.bKeyItem = 1 AND b.ItemGroupId = ".$ItemGroupId."
            	INNER JOIN t_cnm_masterstockstatus c ON a.CNMStockId = c.CNMStockId and c.StatusId = 5
            	WHERE a.MonthId = ".$Month." AND a.Year = ".$Year." 
                AND (a.CountryId = ".$CountryId." OR ".$CountryId." = 0)
				AND c.OwnerTypeId = ".$Reportby." 
            	GROUP BY a.ItemNo, ItemName 
            	/*HAVING IFNULL(((SUM(ClStock))/(SUM(AMC))),0)>0*/"; 
   }else{
   	$sql = "  SELECT a.ItemNo, b.ItemName, SUM(DispenseQty) ReportedConsumption, SUM(ClStock) ReportedClosingBalance, SUM(AMC) AMC, IFNULL(((SUM(ClStock))/(SUM(AMC))),0) MOS                 
            	FROM t_cnm_stockstatus a 
                INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo AND b.bKeyItem = 1 AND b.bCommonBasket = 1
            	INNER JOIN t_cnm_masterstockstatus c ON a.CNMStockId = c.CNMStockId and c.StatusId = 5 
            	WHERE a.MonthId = ".$Month." AND a.Year = ".$Year." 
                AND (a.CountryId = ".$CountryId." OR ".$CountryId." = 0)
				AND c.OwnerTypeId = ".$Reportby."
            	GROUP BY a.ItemNo, ItemName 
            	/*HAVING IFNULL(((SUM(ClStock))/(SUM(AMC))),0)>0*/"; 
   	
   } 
                
    $result = mysql_query($sql);
    if($result){
        $i = 0;    
   	while ($aRow = mysql_fetch_array($result)) {  
   	    
   	    $item_name[$i] = $aRow['ItemName'];
        //$mos[$i] = number_format($aRow['MOS'],1);		
		$mos[$i] = number_format($aRow['MOS'],1);
		$mos[$i] = str_replace(',', '', $mos[$i]);		
        
        foreach($MosType as $key => $value){
             $min = $value['Min'];
             $max = $value['Max'];
             $color = $value['ColorCode'];              
             if ($mos[$i] == $min || ($mos[$i] > $min && $mos[$i] < $max)) $barcolor[$i] = $color;		            
        }  
		$mos[$i]=floatval($mos[$i]);
        $i++;              
    }
    }
    
    $data=array();
    $data['item_name'] = $item_name;
    $data['temp'] = $mos;
    $data['barcolor'] = $barcolor;
    $data['name'] = $MonthList[$Month].', '.$Year;
    
    echo json_encode($data);   
  
}


function getSummaryData() {    
    $Year = $_POST['Year'];
    $ItemGroupId = $_POST['ItemGroup'];
    $Month = $_POST['Month'];
    $CountryId = $_POST['Country'];
    $Reportby = $_POST['Reportby'];
	
   if($ItemGroupId > 0){
		$sql =" SELECT a.ItemNo, b.ItemName, SUM(DispenseQty) ReportedConsumption, SUM(ClStock) ReportedClosingBalance, SUM(AMC) AMC, IFNULL(((SUM(ClStock))/(SUM(AMC))),0) MOS                 
            	FROM t_cnm_stockstatus a 
                INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo AND b.bKeyItem = 1 AND b.ItemGroupId = ".$ItemGroupId."
            	INNER JOIN t_cnm_masterstockstatus c ON a.CNMStockId = c.CNMStockId AND a.CountryId = c.CountryId 
				AND c.StatusId = 5 
           		WHERE a.MonthId = ".$Month." AND a.Year = ".$Year."
                AND (a.CountryId = ".$CountryId." OR ".$CountryId." = 0)
				AND c.OwnerTypeId = ".$Reportby."				
            	GROUP BY ItemNo, ItemName 
            	/*HAVING IFNULL(((SUM(ClStock))/(SUM(AMC))),0)>0*/";
	}else{
		$sql =" SELECT a.ItemNo, b.ItemName, SUM(DispenseQty) ReportedConsumption, SUM(ClStock) ReportedClosingBalance, SUM(AMC) AMC, IFNULL(((SUM(ClStock))/(SUM(AMC))),0) MOS                 
            	FROM t_cnm_stockstatus a 
                INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo AND b.bKeyItem = 1 AND b.bCommonBasket = 1
            	INNER JOIN t_cnm_masterstockstatus c ON a.CNMStockId = c.CNMStockId AND a.CountryId = c.CountryId AND c.StatusId = 5 
           		WHERE a.MonthId = ".$Month." AND a.Year = ".$Year." 
                AND (a.CountryId = ".$CountryId." OR ".$CountryId." = 0)
				AND c.OwnerTypeId = ".$Reportby."				
            	GROUP BY ItemNo, ItemName 
            	/*HAVING IFNULL(((SUM(ClStock))/(SUM(AMC))),0)>0*/";	 
		
	}
	
	//echo $sql;
                    
	$result = mysql_query($sql);
        if($result){
	//$total = mysql_num_rows($result);
	$sQuery = "SELECT FOUND_ROWS()";
	$rResultFilterTotal = mysql_query($sQuery);
	$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];

	$sOutput = '{';
	$sOutput .= '"sEcho": ' . intval($_POST['sEcho']) . ', ';
	$sOutput .= '"iTotalRecords": ' . $iFilteredTotal . ', ';
	$sOutput .= '"iTotalDisplayRecords": ' . $iFilteredTotal . ', ';
	$sOutput .= '"aaData": [ ';
	$serial = $_POST['iDisplayStart'] + 1;
	$f = 0;
 
	while ($aRow = mysql_fetch_array($result)) {

		$ItemName = trim(preg_replace('/\s+/', ' ', addslashes($aRow['ItemName'])));

		if ($f++)
			$sOutput .= ',';
		$sOutput .= "[";
		$sOutput .= '"' . $serial++ . '",';
		$sOutput .= '"' . $ItemName . '",';
        $sOutput .= '"' . number_format($aRow['ReportedConsumption']) . '",';
		$sOutput .= '"' . number_format($aRow['ReportedClosingBalance']) . '",';
 	    $sOutput .= '"' . number_format($aRow['AMC']) . '",';
        $sOutput .= '"' . number_format($aRow['MOS'],1) . '"';
		$sOutput .= "]";
	}
	$sOutput .= '] }';
	echo $sOutput; 
        }
}


?>