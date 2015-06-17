<?php
include_once ('database_conn.php');
include_once ("function_lib.php");

//print_r($_POST);

$task = '';
if (isset($_REQUEST['operation'])) {
	$task = $_REQUEST['operation'];
}

switch($task) {

	case 'getNationalStockPiplineInfoChart' :
		getNationalStockPiplineInfoChart();
		break;
    case 'getNationalStockPiplineInfoTable' :
		getNationalStockPiplineInfoTable();
		break;	
	default :
		echo "{failure:true}";
		break;
}

function getNationalStockPiplineInfoChart(){
	$lan = $_REQUEST['lan'];
	$monthId = $_POST['MonthId'];
	$year = $_POST['YearId'];
	$countryId = $_POST['CountryId'];
	$itemGroupId = $_POST['ItemGroupId'];
	$Reportby = $_POST['Reportby'];
	
	$currentYearMonth = $_POST['YearId'] . "-" . $_POST['MonthId'] . "-" . "01";
	
	if($lan == 'en-GB'){
            $mOSAvailable = 'MOS (Available)';
			$mOSPipeline = 'MOS (Pipeline)';
			$monthList = array('1'=>'January','2'=>'February','3'=>'March','4'=>'April','5'=>'May','6'=>'June','7'=>'July','8'=>'August','9'=>'September','10'=>'October','11'=>'November','12'=>'December');
        }else{
            $mOSAvailable = 'MSD (disponible)';
			$mOSPipeline = 'MSD (pipeline)';
			$monthList = array('1'=>'Janvier','2'=>'F�vrier','3'=>'Mars','4'=>'Avril','5'=>'Mai','6'=>'Juin','7'=>'Juillet','8'=>'Ao�t','9'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'D�cembre');
        } 
		
	$sQuery = "SELECT ItemName, IFNULL(AMC,0) AMC, ClStock, FORMAT(MOS,1) MOS, IFNULL(Qty,0) StockOnOrder FROM 
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
				       AND t_cnm_masterstockstatus.MonthId = $monthId
				       AND t_cnm_masterstockstatus.CountryId = $countryId
				       AND t_cnm_stockstatus.ItemGroupId = $itemGroupId
					   AND (t_cnm_masterstockstatus.OwnerTypeId = $Reportby OR $Reportby = 0)
				       AND t_cnm_masterstockstatus.StatusId = 5)
					   
				GROUP BY t_cnm_masterstockstatus.CountryId, t_itemlist.ItemNo, t_itemlist.ItemName) a 
				LEFT JOIN (SELECT
				    CountryId
				    , ItemNo
				    , SUM(Qty) Qty
				FROM
				    t_agencyshipment
				WHERE (ShipmentDate > CAST('$currentYearMonth' AS DATETIME) /*  AND ShipmentStatusId = 2*/)
				GROUP BY CountryId, ItemNo) b
				ON a.CountryId = b.CountryId AND a.ItemNo = b.ItemNo
				/*HAVING AMC>0 OR MOS>0 OR ClStock>0 OR StockOnOrder>0*/
				 order by ItemName;";	
		//echo $sQuery;		
	$rResult = safe_query($sQuery);

	//$output = array('Categories'=>array(), 'Series'=>array(), 'Colors'=>array('#0000FF', '#4DAC26')); '#b35806', '#542788'//#ed8917', '#464d57
	$output = array('Categories'=>array(), 'Series'=>array(), 'Colors'=>array('#dd56e6', '#ecad3f'));
	
	
	$output2 =  array('name'=>$mOSAvailable, 'data'=>array());
	$output3 = array('name'=>$mOSPipeline, 'data'=>array());

	while ($row = mysql_fetch_assoc($rResult)) {		
		$output['Categories'][] = $row['ItemName'];	
		
		if(!is_null($row['MOS']))	
			settype($row['MOS'], "float");
		
		if(!is_null($row['StockOnOrder']))	
			settype($row['StockOnOrder'], "float");
		
		if(!is_null($row['AMC']))	
			settype($row['AMC'], "float");
		
		$amc = ($row['AMC'] == 0? 1 : $row['AMC']);
		$stockOnOrderMOS = str_replace(',','',(number_format($row['StockOnOrder'] / $amc,1)));
		settype($stockOnOrderMOS,"float");			
			
		$output2['data'][] =  $row['MOS'];
		$output3['data'][] = ($stockOnOrderMOS<0.00001?NULL:$stockOnOrderMOS);
	}	
		
	$output['Series'][] = $output3;
	$output['Series'][] = $output2;		
	$output['Height'] = count($output['Categories']);
	$output['MonthYear'] = $monthList[$monthId].' '.$year;
	
	echo json_encode($output);
}

function getNationalStockPiplineInfoTable(){	
	$monthId = $_POST['MonthId'];
	$year = $_POST['YearId'];
	$countryId = $_POST['CountryId'];
	$itemGroupId = $_POST['ItemGroupId'];
        $Reportby = $_POST['Reportby'];
	$lan  = $_POST['lan'];
        $sEcho = isset($_REQUEST['sEcho'])? $_REQUEST['sEcho'] : '';
	
    $currentYearMonth = $_POST['YearId'] . "-" . $_POST['MonthId'] . "-" . "01"; 

    $sOrder = "";
	if (isset($_POST['iSortCol_0'])) { $sOrder = " ORDER BY  ";
		for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
			$sOrder .= fnColumnToField_Item(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
		}
		$sOrder = substr_replace($sOrder, "", -2);
	}
    
	$sQuery = "SELECT SQL_CALC_FOUND_ROWS ItemName,b.FundingSourceName,b.FundingSourceId, IFNULL(AMC,0) AMC,
	IFNULL(ClStock,0) ClStock, IFNULL(MOS,0) MOS, IFNULL(Qty,0) StockOnOrder FROM 
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
				       AND t_cnm_masterstockstatus.MonthId = $monthId
				       AND t_cnm_masterstockstatus.CountryId = $countryId
				       AND t_cnm_stockstatus.ItemGroupId = $itemGroupId
					    AND (t_cnm_masterstockstatus.OwnerTypeId = $Reportby OR $Reportby = 0)
				       AND t_cnm_masterstockstatus.StatusId = 5)
				GROUP BY t_cnm_masterstockstatus.CountryId, t_itemlist.ItemNo, t_itemlist.ItemName) a 
				LEFT JOIN (SELECT CountryId, ItemNo,t_agencyshipment.FundingSourceId,FundingSourceName, SUM(Qty) Qty

					FROM t_agencyshipment
					INNER JOIN t_fundingsource ON t_agencyshipment.FundingSourceId = t_fundingsource.FundingSourceId
					WHERE (ShipmentDate > CAST('$currentYearMonth' AS DATETIME)  /*AND ShipmentStatusId = 2*/)
					GROUP BY CountryId, ItemNo,t_agencyshipment.FundingSourceId,FundingSourceName) b
				ON a.CountryId = b.CountryId AND a.ItemNo = b.ItemNo
				
				/*HAVING AMC>0 OR MOS>0 OR ClStock>0 OR StockOnOrder>0*/
                 order by ItemName,b.FundingSourceName,b.FundingSourceId";
      // echo $sQuery;       
	$dataList = array();
	$rResult = mysql_query($sQuery);
	$total = mysql_num_rows($rResult);
	
			while ($row = mysql_fetch_assoc($rResult)) {
				$dataList[] = $row;
			}
			$output = array('aaData' => array());
			$aData = array();
			$output2 = array();
				
				
			$fundingSourceList = array();
			foreach($dataList as $r){
				
				if($r['FundingSourceName'] != ''){
					$fundingSourceList[$r['FundingSourceId']] = $r['FundingSourceName'];
				}
			}
			$sl = 1;
			$itemCount = 1;
			$preItemName='';
			$preAMC=0;
			$preStock=0;
			$preMOS=0;
			$totalMOS=0;	
			$tmpItemName = '';
			
			foreach($dataList as $row){
				if ($tmpItemName != $row['ItemName']) {
					
					if ($itemCount > 1) {
					
						if(empty($output2)){
							$output2[]=$sl;
							array_push($output2, $preItemName, number_format($preAMC),number_format($preStock),number_format($preMOS,1));
						}else{
							array_unshift($output2,$sl,$preItemName,number_format($preAMC),number_format($preStock),number_format($preMOS,1));
						}
						
						$output2[] = number_format($totalMOS,1); //add array last
						$aData[] = $output2;
						unset($output2);//}
						$sl++;
					 }
					$itemCount++;		
					$preItemName = $row['ItemName'];
					$preAMC = $row['AMC'];
					$preStock = $row['ClStock'];
					$preMOS = $row['MOS'];
					$totalMOS = $row['MOS'];
					
					foreach($dataList as $r){		
						if($r['FundingSourceName'] != ''){
							$output2[$r['FundingSourceId']] = null; //qty
							$output2['1'.$r['FundingSourceId']] = null; //mos 1= flag for maintain array index
						}
					}
						
					if($row['FundingSourceId'] != ''){
						$preAMC = ($preAMC == 0? 1 : $preAMC);						
						$stockOnOrderMOS =  $row['StockOnOrder'] / $preAMC;
						$output2[$row['FundingSourceId']] = ($row['StockOnOrder']== 0? '' : number_format($row['StockOnOrder'])); //qty
						$output2['1'.$row['FundingSourceId']] = ($stockOnOrderMOS == 0? '' : number_format($stockOnOrderMOS,1)); //mos
						$totalMOS+= $stockOnOrderMOS;
						}
					$tmpItemName = $row['ItemName'];
				} 
				else {
					if($row['FundingSourceId'] != ''){
						$preAMC = ($preAMC == 0? 1 : $preAMC);						
						$stockOnOrderMOS =  $row['StockOnOrder'] / $preAMC;
						$output2[$row['FundingSourceId']] = ($row['StockOnOrder']== 0? '' : number_format($row['StockOnOrder'])); //qty
						$output2['1'.$row['FundingSourceId']] = ($stockOnOrderMOS == 0? '' : number_format($stockOnOrderMOS,1)); //mos
						$totalMOS+= $stockOnOrderMOS;
						}
					
					$tmpItemName = $row['ItemName'];
				}   
			}

			if(empty($output2)){
				$output2[]=$sl;
				array_push($output2, $preItemName, number_format($preAMC),number_format($preStock),number_format($preMOS,1));
			}else{
				array_unshift($output2,$sl,$preItemName,number_format($preAMC),number_format($preStock),number_format($preMOS,1));
			}
			$output2[] = number_format($totalMOS,1); //add array last
			$aData[] = $output2;

			if($lan=='en-GB'){
				$Products='Products';
				$AMC = 'AMC';
				$AvailableStock = 'Available Stock';
				$TotalMOS ='Total MOS';
				$MOSAvailable = 'MOS(Available)';
				$MOS = 'MOS';
				$Qty = 'Qty';
			}
			else{
				$Products='produits';
				$AMC = 'AMC';
				$AvailableStock = 'disponible en stock';
				$TotalMOS ='Total MSD';
				$MOSAvailable = 'MSD(disponible)';
				$MOS = 'MSD';
				$Qty = 'Quantité';
			}
				
				
			$columnList = array();
			foreach($fundingSourceList as $fs){
				$columnList[]= $fs;    		
			}
			
			if($total == 0){
				echo '{"sEcho": ' . intval($sEcho) . ', "iTotalRecords":"10","iTotalDisplayRecords": "10", "aaData":[]';
				echo ',"COLUMNS":'.json_encode($columnList).'}';
			}
			else{
				echo '{"sEcho": ' . intval($sEcho) . ', "iTotalRecords":"10","iTotalDisplayRecords": "10", "aaData":' . json_encode($aData) . '';
				echo ',"COLUMNS":'.json_encode($columnList).'}';
			}
			

	
}

function fnColumnToField_Item($i) {
	if ($i == 1)
		return "ItemName";
    if ($i == 2)
		return "AMC";
    if ($i == 3)
		return "ClStock";
    if ($i == 4)
		return "MOS";
    if ($i == 5)
		return "StockOnOrder";
    
}
?>









