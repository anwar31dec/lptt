<?php
include_once ('database_conn.php');
include_once ("function_lib.php");

$task = '';
if (isset($_REQUEST['operation'])) {
	$task = $_REQUEST['operation'];
}

switch($task) {
	case "getCountryProfileParams" :
		getCountryProfileParams();
		break;
	case "getMosTypeProductCount" :
		getMosTypeProductCount();
		break;
	case "getMosTypeProductBullet" :
		getMosTypeProductBullet();
		break;
	case "getCurrentPatients" :
		getCurrentPatients();
		break;
	case "getSeverePatients" :
		getSeverePatients();
		break;			
	case "getCurrentPatientsTable" :
		getCurrentPatientsTable();
		break;
		
	case "getSeverePatientsTable" :
		getSeverePatientsTable();
		break;
	case "getPatientTrendTimeSeries" :
		getPatientTrendTimeSeries();
		break;
	case "getSimpleVsSevere" :
		getSimpleVsSevere();
		break;
	case "getTotalPatients" :
		getTotalPatients();
		break;
	case "getMaleFemale" :
		getMaleFemale();
		break;
	case "getSimpleVsSevere1" :
		getSimpleVsSevere1();
		break;
	case "getNationalSummaryChart" :
		getNationalSummaryChart();
		break;
  case "getCaseSummaryChart" :
		getCaseSummaryChart();
		break;
  case "getFacilityStockoutTimeSeriesChart" :
		getFacilityStockoutTimeSeriesChart();
		break;
  case "getstockoutpercenttable" :
		getstockoutpercenttable();
		break;
	default :
		echo "{failure:true}";
		break;
}

function getMosTypeProductCount() {
    $lan = $_REQUEST['lan'];
    $Reportby = $_POST['Reportby'];
    $ItemGroupId = $_POST['ItemGroupId']; //echo $ItemGroupId;
    if($lan == 'en-GB')
    	$mosTypeName = 'MosTypeName';
    else if($lan == 'fr-FR')
    	$mosTypeName = 'MosTypeNameFrench';
     if($ItemGroupId > 0){
    $sQuery = "SELECT v.MosTypeId, $mosTypeName MosTypeName, ColorCode, IFNULL(RiskCount,0) RiskCount FROM
    			(SELECT p.MosTypeId, COUNT(*) RiskCount FROM (SELECT
    			    a.ItemNo
    			    , IFNULL(a.MOS,0) AS MOS
    			,(SELECT MosTypeId FROM t_mostype x WHERE  IFNULL(a.MOS,0) >= x.MinMos AND IFNULL(a.MOS,0) < x.MaxMos) MosTypeId
    			FROM t_cnm_stockstatus a
    			INNER JOIN t_cnm_masterstockstatus 
        			ON (t_cnm_masterstockstatus.CNMStockId = a.CNMStockId)
    			WHERE  t_cnm_masterstockstatus.MonthId = " . $_POST['MonthId'] . " 
                AND t_cnm_masterstockstatus.Year = " . $_POST['YearId'] . " 
                AND a.ItemGroupId=$ItemGroupId 
                AND (t_cnm_masterstockstatus.CountryId = " . $_POST['CountryId'] . " OR " . $_POST['CountryId'] . " = 0 )
    			AND t_cnm_masterstockstatus.StatusId = 5 
                AND t_cnm_masterstockstatus.OwnerTypeId = $Reportby) p 
    			GROUP BY p.MosTypeId) u
    			RIGHT JOIN t_mostype v ON u.MosTypeId = v.MosTypeId
    			GROUP BY v.MosTypeId"; //echo $sQuery;
    }
    else{
        $sQuery = "SELECT v.MosTypeId, $mosTypeName MosTypeName, ColorCode, IFNULL(RiskCount,0) RiskCount FROM
    			(SELECT p.MosTypeId, COUNT(*) RiskCount FROM (SELECT
    			    a.ItemNo
    			    , IFNULL(a.MOS,0) AS MOS
    			,(SELECT MosTypeId FROM t_mostype x WHERE  IFNULL(a.MOS,0) >= x.MinMos AND IFNULL(a.MOS,0) < x.MaxMos) MosTypeId
    			FROM t_cnm_stockstatus a
    			INNER JOIN t_cnm_masterstockstatus 
        			ON (t_cnm_masterstockstatus.CNMStockId = a.CNMStockId)
                INNER JOIN t_itemlist 
        			ON (t_itemlist.ItemGroupId = a.ItemGroupId)
    			WHERE  t_cnm_masterstockstatus.MonthId = " . $_POST['MonthId'] . " 
                AND t_cnm_masterstockstatus.Year = " . $_POST['YearId'] . " 
                AND (t_cnm_masterstockstatus.CountryId = " . $_POST['CountryId'] . " OR " . $_POST['CountryId'] . " = 0 )
    			AND t_cnm_masterstockstatus.StatusId = 5 
                AND t_cnm_masterstockstatus.OwnerTypeId = $Reportby
                AND t_itemlist.bCommonBasket = 1) p 
    			GROUP BY p.MosTypeId) u
    			RIGHT JOIN t_mostype v ON u.MosTypeId = v.MosTypeId
    			GROUP BY v.MosTypeId"; //echo $sQuery;
    }//	echo $sQuery;
    $rResult = safe_query($sQuery);
    
    $output = array();
    
    while ($obj = mysql_fetch_object($rResult)) {
    	$output[] = $obj;
    }
	
echo json_encode($output);

}

function getMosTypeProductBullet() {
	$lan = $_REQUEST['lan'];
	$baseUrl = $_GET['BaseUrl'];

	$countryId = $_GET['CountryId'];
	$monthId = $_GET['MonthId'];
	$year = $_GET['Year'];
	$itemGroupId = $_GET['ItemGroupId'];//echo $itemGroupId;
	$Reportby = $_GET['Reportby'];
    
	if($lan == 'en-GB'){
		$mosTypeName = 'MosTypeName';
		$mos = 'MOS: ';
	}
	else if($lan == 'fr-FR'){
		$mosTypeName = 'MosTypeNameFrench';
		$mos = 'MSD: ';
	}
        
     if($itemGroupId > 0){
    $sQuery = "SELECT v.MosTypeId, $mosTypeName MosTypeName, ColorCode, IFNULL(RiskCount,0) RiskCount, v.MosLabel FROM
				(SELECT p.MosTypeId, COUNT(*) RiskCount FROM (SELECT
				    a.ItemNo
				    , IFNULL(a.MOS,0) AS MOS
				,(SELECT MosTypeId FROM t_mostype x WHERE  IFNULL(a.MOS,0) >= x.MinMos AND IFNULL(a.MOS,0) < x.MaxMos) MosTypeId
				FROM t_cnm_stockstatus a
				INNER JOIN t_cnm_masterstockstatus 
        			ON (t_cnm_masterstockstatus.CNMStockId = a.CNMStockId)
				WHERE t_cnm_masterstockstatus.MonthId = $monthId 
                AND t_cnm_masterstockstatus.Year = $year 
                AND a.ItemGroupId=$itemGroupId 
                AND (t_cnm_masterstockstatus.CountryId = $countryId OR $countryId = 0 )
                AND t_cnm_masterstockstatus.OwnerTypeId = $Reportby
				AND t_cnm_masterstockstatus.StatusId = 5 ) p 
				GROUP BY p.MosTypeId) u
				RIGHT JOIN t_mostype v ON u.MosTypeId = v.MosTypeId
				GROUP BY v.MosTypeId"; //echo $sQuery;
    }
    else{
        $sQuery = "SELECT v.MosTypeId, $mosTypeName MosTypeName, ColorCode, IFNULL(RiskCount,0) RiskCount, v.MosLabel FROM
				(SELECT p.MosTypeId, COUNT(*) RiskCount FROM (SELECT
				    a.ItemNo
				    , IFNULL(a.MOS,0) AS MOS
				,(SELECT MosTypeId FROM t_mostype x WHERE  IFNULL(a.MOS,0) >= x.MinMos AND IFNULL(a.MOS,0) < x.MaxMos) MosTypeId
				FROM t_cnm_stockstatus a
				INNER JOIN t_cnm_masterstockstatus 
        			ON (t_cnm_masterstockstatus.CNMStockId = a.CNMStockId)
                INNER JOIN t_itemlist 
        			ON (t_itemlist.ItemGroupId = a.ItemGroupId)
				WHERE t_cnm_masterstockstatus.MonthId = $monthId 
                AND t_cnm_masterstockstatus.Year = $year 
                AND (t_cnm_masterstockstatus.CountryId = $countryId OR $countryId = 0 )
                AND t_cnm_masterstockstatus.OwnerTypeId = $Reportby
				AND t_cnm_masterstockstatus.StatusId = 5 
                AND t_itemlist.bCommonBasket = 1) p 
				GROUP BY p.MosTypeId) u
				RIGHT JOIN t_mostype v ON u.MosTypeId = v.MosTypeId
				GROUP BY v.MosTypeId";
        
        
          	
    }//	echo $sQuery;

	
	$rResult = safe_query($sQuery);
	
	$countRows = mysql_num_rows($rResult);


	$aData = array();
	$tRow = array();
	
	$strHtml = '';

	$totalRiskCount = 0;

	
	while ($row = mysql_fetch_array($rResult)) {
		//echo  $row['RiskCount'].'';					
		$totalRiskCount += $row['RiskCount'];
		$output[] = $row;
	}
	
	//echo 'anwar:'.$totalRiskCount;
	
	if($countRows > 0 ){
		foreach ($output as $row) {
			$persRiskCount = 0;
			if( $row['RiskCount'] > 0 )
				 $persRiskCount = number_format($row['RiskCount'] * 100 / $totalRiskCount, 1);
			
			$strHtml .= '<li class="list-group-item no-border-hr padding-xs-hr no-bg no-border-radius">
							 <span style="background-color:' . $row['ColorCode'] .'" class="circle-num-rounded badge-warning"></span> 
							 <span class="db-tbl-mos-type"> ' . $row['MosTypeName'] .' <strong class="pull-right">' . $persRiskCount .' %</strong></span> 
							<div style="padding-left:38px;"> '. $mos . $row['MosLabel'] .'	</div>							 
						  </li>';
		
		}

		$tRow[] = '<ul class="list-group no-margin ">' . $strHtml . '</ul>';
		$aData[] = $tRow;
	}
	
	

	echo '{"sEcho": ' . intval($_GET['sEcho']) . ', "iTotalRecords":"10","iTotalDisplayRecords": "10", "aaData":' . json_encode($aData) . '}';

}

function getCurrentPatients() {
	$lan = $_REQUEST['lan'];
    $Reportby = $_POST['Reportby'];
    
	$countryId = $_POST['CountryId'];
	$monthId = $_POST['MonthId'];
	$year = $_POST['YearId'];
	$itemGroupId = $_POST['ItemGroupId'];	
    /*
    $sQuery = "SELECT
              t_regimen.RegMasterId,
              t_regimen_master.RegimenName,
              IFNULL(SUM(t_cnm_regimenpatient.TotalPatient),0)    TotalPatient
			  FROM t_cnm_regimenpatient
              INNER JOIN t_cnm_masterstockstatus
                ON (t_cnm_regimenpatient.CNMStockId = t_cnm_masterstockstatus.CNMStockId
                    AND (t_cnm_masterstockstatus.CountryId = $countryId OR $countryId = 0)
                    AND t_cnm_masterstockstatus.Year = $year
                    AND t_cnm_masterstockstatus.MonthId = $monthId
                    AND t_cnm_masterstockstatus.ItemGroupId = $itemGroupId
					AND t_cnm_masterstockstatus.OwnerTypeId = $Reportby
                    AND StatusId = 5)                   
              INNER JOIN t_regimen
                ON (t_cnm_regimenpatient.RegimenId = t_regimen.RegimenId)                   
              INNER JOIN t_formulation
                ON (t_regimen.FormulationId = t_formulation.FormulationId AND t_formulation.FormulationId=1)			
			  RIGHT JOIN t_regimen_master
                ON (t_regimen.RegMasterId  = t_regimen_master.RegMasterId)
			WHERE  t_regimen_master.ItemGroupId = $itemGroupId
			
            GROUP BY  t_regimen.RegMasterId, t_regimen_master.RegimenName
            ORDER BY t_regimen.RegMasterId ASC;";
			*/
			
			    $sQuery = "SELECT
              t_regimen.RegMasterId,
              t_regimen_master.RegimenName,
              IFNULL(SUM(t_cnm_regimenpatient.TotalPatient),0)    TotalPatient
			  FROM t_cnm_regimenpatient
              INNER JOIN t_cnm_masterstockstatus
                ON (t_cnm_regimenpatient.CNMStockId = t_cnm_masterstockstatus.CNMStockId
                    AND (t_cnm_masterstockstatus.CountryId = $countryId OR $countryId = 0)
                    AND t_cnm_masterstockstatus.Year = $year
                    AND t_cnm_masterstockstatus.MonthId = $monthId
                    /*AND t_cnm_masterstockstatus.ItemGroupId = $itemGroupId*/
					AND t_cnm_masterstockstatus.OwnerTypeId = $Reportby
                    AND StatusId = 5)                   
              INNER JOIN t_regimen
                ON (t_cnm_regimenpatient.RegimenId = t_regimen.RegimenId)                   
              INNER JOIN t_formulation
                ON (t_regimen.FormulationId = t_formulation.FormulationId AND t_formulation.FormulationId=1)			
			  RIGHT JOIN t_regimen_master
                ON (t_regimen.RegMasterId  = t_regimen_master.RegMasterId)
			WHERE  t_regimen_master.ItemGroupId = $itemGroupId
			
            GROUP BY  t_regimen.RegMasterId, t_regimen_master.RegimenName
            ORDER BY t_regimen.RegMasterId ASC;";
  
	$rResult = safe_query($sQuery);

	$output = array();

	while ($obj = mysql_fetch_assoc($rResult)) {
		$output[] = $obj;
	}

    echo json_encode($output);
/*	if($lan == 'en-GB')
		echo '[{"ServiceTypeId":"1","ServiceTypeName":"< 5 Years","ColorCode":"#D7191C","TotalPatient":"13"},
		{"ServiceTypeId":"2","ServiceTypeName":"> 5 Years","ColorCode":"#FE9929","TotalPatient":"77"},
		{"ServiceTypeId":"3","ServiceTypeName":"Pregnant Women","ColorCode":"#F0F403","TotalPatient":"10"}
		]';
	else if($lan == 'fr-FR')
		echo '[{"ServiceTypeId":"1","ServiceTypeName":"<5 ans","ColorCode":"#D7191C","TotalPatient":"13"},
		{"ServiceTypeId":"2","ServiceTypeName":"> 5 ans","ColorCode":"#FE9929","TotalPatient":"77"},
		{"ServiceTypeId":"3","ServiceTypeName":"Femmes enceintes","ColorCode":"#F0F403","TotalPatient":"10"}
		]';*/
}

function getSeverePatients() {
	$lan = $_REQUEST['lan'];

	 $countryId = $_POST['CountryId'];
	 $monthId = $_POST['MonthId']; 
	 $year = $_POST['YearId'];
	 $itemGroupId = $_POST['ItemGroupId'];
     $Reportby = $_POST['Reportby'];
    
	 $sQuery = "SELECT
              t_regimen.RegMasterId,
              t_regimen_master.RegimenName,
              IFNULL(SUM(t_cnm_regimenpatient.TotalPatient),0)    TotalPatient
            FROM t_cnm_regimenpatient
              INNER JOIN t_cnm_masterstockstatus
                ON (t_cnm_regimenpatient.CNMStockId = t_cnm_masterstockstatus.CNMStockId
                    AND (t_cnm_masterstockstatus.CountryId = $countryId
                          OR $countryId = 0)
                    AND t_cnm_masterstockstatus.Year = $year
                    AND t_cnm_masterstockstatus.MonthId = $monthId
                    /*AND t_cnm_masterstockstatus.ItemGroupId = $itemGroupId*/
                AND t_cnm_masterstockstatus.OwnerTypeId = $Reportby
                    AND StatusId = 5)
                   
              INNER JOIN t_regimen
                ON (t_cnm_regimenpatient.RegimenId = t_regimen.RegimenId)
                   
              INNER JOIN t_formulation
                ON (t_regimen.FormulationId = t_formulation.FormulationId AND t_formulation.FormulationId=2)
			
			  	  RIGHT JOIN t_regimen_master
                ON (t_regimen.RegMasterId  = t_regimen_master.RegMasterId)
			WHERE  t_regimen_master.ItemGroupId = $itemGroupId

            GROUP BY  t_regimen.RegMasterId, t_regimen_master.RegimenName
            ORDER BY t_regimen.RegMasterId ASC;";
    
	 $rResult = safe_query($sQuery);
 
	 $output = array();
 
	 while ($obj = mysql_fetch_assoc($rResult)) {
		 $output[] = $obj;
	 }

	echo json_encode($output);

}

function getCurrentPatientsTable() {
	$lan = $_REQUEST['lan'];

	$baseUrl = $_GET['BaseUrl'];

	$countryId = $_GET['CountryId'];
	$monthId = $_GET['MonthId'];
	$year = $_GET['Year'];
	$itemGroupId = $_GET['ItemGroupId'];
    $Reportby = $_GET['Reportby'];
    
	
    $sQuery = "SELECT
              t_regimen.RegMasterId,
              t_regimen_master.RegimenName,t_regimen_master.STL_Color,
              IFNULL(SUM(t_cnm_regimenpatient.TotalPatient),0)    TotalPatient
            FROM t_cnm_regimenpatient
              INNER JOIN t_cnm_masterstockstatus
                ON (t_cnm_regimenpatient.CNMStockId = t_cnm_masterstockstatus.CNMStockId
                    AND (t_cnm_masterstockstatus.CountryId = $countryId
                          OR $countryId = 0)
                    AND t_cnm_masterstockstatus.Year = $year
                    AND t_cnm_masterstockstatus.MonthId = $monthId
                    /*AND t_cnm_masterstockstatus.ItemGroupId = $itemGroupId*/
                AND t_cnm_masterstockstatus.OwnerTypeId = $Reportby
                    AND StatusId = 5)
                   
              INNER JOIN t_regimen
                ON (t_cnm_regimenpatient.RegimenId = t_regimen.RegimenId)               
                                 
              INNER JOIN t_formulation
                ON (t_regimen.FormulationId = t_formulation.FormulationId AND t_formulation.FormulationId=1)
			
			  RIGHT JOIN t_regimen_master
                ON (t_regimen.RegMasterId  = t_regimen_master.RegMasterId)
				
			WHERE  t_regimen_master.ItemGroupId = $itemGroupId

            GROUP BY  t_regimen.RegMasterId, t_regimen_master.RegimenName, t_regimen_master.STL_Color
            ORDER BY t_regimen.RegMasterId ASC;
            ";

	//echo $sQuery;

	$rResult = safe_query($sQuery);

	$output = array();

	$countRows = mysql_num_rows($rResult);

	$allTotalPatients = 0;

	$aData = array();

	$rowIndex = 0;

	if ($countRows > 0) {
	   
       
	       $totalRiskCount = 0;
    	while ($row = mysql_fetch_array($rResult)) {
    							
    		$totalRiskCount += $row['TotalPatient'];
    		$output[] = $row;
    	}
        
        
        
        foreach ($output as $row) {
			$persRiskCount = 0;
			if( $row['TotalPatient'] > 0 )
				 $persRiskCount = number_format($row['TotalPatient'] * 100 / $totalRiskCount, 1);
			
		$tRow = array();

			$div1 = '<div>';
			$div1 .= '<span class="circle-num-rounded badge-warning" style="float:left;background-color:' . $row['STL_Color'] . '">' . (++$rowIndex) . '</span>' . $row['RegimenName'];

			if ($row['TotalPatient'] > 0) {
				$div1 .= '<span class="" style="float:right;">' . $persRiskCount . ' %';
			} else {
				$div1 .= '<span class="" style="float:right;">' . 0 . ' %';
			}

			$div1 .= '</div>';

			$tRow[] = $div1;

			$aData[] = $tRow;
		}
        
       
	}
	
	echo '{"sEcho": ' . intval($_GET['sEcho']) . ', "iTotalRecords":"10","iTotalDisplayRecords": "10", "aaData":' . json_encode($aData) . '}';

}

function getSeverePatientsTable() {
	$lan = $_REQUEST['lan'];

	 $baseUrl = $_GET['BaseUrl'];
 
	 $countryId = $_GET['CountryId'];
	 $monthId = $_GET['MonthId'];//echo($monthId);
	 $year = $_GET['Year'];
	 $itemGroupId = $_GET['ItemGroupId'];
	 $Reportby = $_GET['Reportby'];
    
	
    $sQuery = "SELECT
              t_regimen.RegMasterId,
              t_regimen_master.RegimenName,t_regimen_master.STL_Color,
              IFNULL(SUM(t_cnm_regimenpatient.TotalPatient),0)    TotalPatient
            FROM t_cnm_regimenpatient
              INNER JOIN t_cnm_masterstockstatus
                ON (t_cnm_regimenpatient.CNMStockId = t_cnm_masterstockstatus.CNMStockId
                    AND (t_cnm_masterstockstatus.CountryId = $countryId
                          OR $countryId = 0)
                    AND t_cnm_masterstockstatus.Year = $year
                    AND t_cnm_masterstockstatus.MonthId = $monthId
                   /* AND t_cnm_masterstockstatus.ItemGroupId = $itemGroupId*/
                AND t_cnm_masterstockstatus.OwnerTypeId = $Reportby
                    AND StatusId = 5)
                   
              INNER JOIN t_regimen
                ON (t_cnm_regimenpatient.RegimenId = t_regimen.RegimenId)               
                                
              INNER JOIN t_formulation
                ON (t_regimen.FormulationId = t_formulation.FormulationId AND t_formulation.FormulationId=2)
			
			 RIGHT JOIN t_regimen_master
                ON (t_regimen.RegMasterId  = t_regimen_master.RegMasterId)
			
			WHERE  t_regimen_master.ItemGroupId = $itemGroupId

            GROUP BY  t_regimen.RegMasterId, t_regimen_master.RegimenName, t_regimen_master.STL_Color
            ORDER BY t_regimen.RegMasterId ASC;
            ";
	// echo $sQuery;
 
	 $rResult = safe_query($sQuery);
 
	 $output = array();
 
	 $countRows = mysql_num_rows($rResult);
 
	 $allTotalPatients = 0;
 
	 $aData = array();
 
	 $rowIndex = 0;
 
	 if ($countRows > 0) {
		 $totalRiskCount = 0;
    	while ($row = mysql_fetch_array($rResult)) {
    						
    		$totalRiskCount += $row['TotalPatient'];
    		$output[] = $row;
    	}
        
        foreach ($output as $row) {
			$persRiskCount = 0;
			if( $row['TotalPatient'] > 0 )
				 $persRiskCount = number_format($row['TotalPatient'] * 100 / $totalRiskCount, 1);
			
		$tRow = array();

			$div1 = '<div>';
			$div1 .= '<span class="circle-num-rounded badge-warning" style="float:left;background-color:' . $row['STL_Color'] . '">' . (++$rowIndex) . '</span>' . $row['RegimenName'];

			if ($row['TotalPatient'] > 0) {
				$div1 .= '<span class="" style="float:right;">' . $persRiskCount . ' %';
			} else {
				$div1 .= '<span class="" style="float:right;">' . 0 . ' %';
			}

			$div1 .= '</div>';

			$tRow[] = $div1;

			$aData[] = $tRow;
		}
	 }

	echo '{"sEcho": ' . intval($_GET['sEcho']) . ', "iTotalRecords":"10","iTotalDisplayRecords": "10", "aaData":' . json_encode($aData) . '}';
	
	
	/*if($lan == 'en-GB')
		echo '{"sEcho": 3, "iTotalRecords":"10","iTotalDisplayRecords": "10", "aaData":[["<div><span class=\"circle-num-rounded2 badge-warning\" style=\"float:left;background-color:#D7191C; margin-right:20px;\">1<\/span>< 5 Years<span class=\"\" style=\"float:right;\">19 %<\/div>"],["<div><span class=\"circle-num-rounded2 badge-warning\" style=\"float:left;background-color:#FFC545;margin-right:20px;\">2<\/span>> 5 Years<span class=\"\" style=\"float:right;\">61 %<\/div>"],["<div><span class=\"circle-num-rounded2 badge-warning\" style=\"float:left;background-color:#F0F403;margin-right:20px;\">3<\/span>Pregnent Woman<span class=\"\" style=\"float:right;\">20 %<\/div>"]]}';
	else if($lan == 'fr-FR')
		echo '{"sEcho": 3, "iTotalRecords":"10","iTotalDisplayRecords": "10", "aaData":[["<div><span class=\"circle-num-rounded2 badge-warning\" style=\"float:left;background-color:#D7191C; margin-right:20px;\">1<\/span>< 5 ans<span class=\"\" style=\"float:right;\">19 %<\/div>"],["<div><span class=\"circle-num-rounded2 badge-warning\" style=\"float:left;background-color:#FFC545;margin-right:20px;\">2<\/span>> 5 ans<span class=\"\" style=\"float:right;\">61 %<\/div>"],["<div><span class=\"circle-num-rounded2 badge-warning\" style=\"float:left;background-color:#F0F403;margin-right:20px;\">3<\/span>Femmes enceintes<span class=\"\" style=\"float:right;\">20 %<\/div>"]]}';
	*/

}


function getCountryProfileParams() {
	$lan = $_REQUEST['lan'];

	if ($_REQUEST['lan'] == 'fr-FR') {
		$aColumns = 'ParamNameFrench ParamName';
	} else {
		$aColumns = 'ParamName';
	}

	$sql = "SELECT YCProfileId, p.CountryId, p.ParamId, p.Year, BShow, $aColumns, SUM(YCValue) total 
            FROM `t_ycprofile` p
    		INNER JOIN t_cprofileparams c ON c.ParamId = p.ParamId
    		WHERE c.BShow = 1
    		AND YEAR = '" . $_POST['YearId'] . "' 
    		AND (CountryId = " . $_POST['CountryId'] . " OR " . $_POST['CountryId'] . " = 0 ) 
    		GROUP BY p.ParamId; ";

	$rs = safe_query($sql);
	$sql = "SELECT FOUND_ROWS();";
	$qr = safe_query($sql);
	$r = mysql_fetch_array($qr);
	$trecords = $r[0];
	echo '{ "sEcho": ' . intval($_POST['sEcho']) . ', "iTotalRecords": "' . $trecords . '" , "iTotalDisplayRecords": "' . $trecords . '", "aaData":[';
	$f = 0;
	$serial = $_POST['iDisplayStart'] + 1;
	while ($r = mysql_fetch_object($rs)) {
		if ($f++)
			echo ',';
		if ($_POST['CountryId'] == 0) {
			$YCValue = $r -> total;
			$ParamName = 'Total ' . $r -> ParamName;
		} else {
			$YCValue = $r -> YCValue;
			$ParamName = $r -> ParamName;
		}
		echo '["' . $serial++ . '","' . $ParamName . '", "' . $YCValue . '"]';
	}
	echo ']}';
}


function getSimpleVsSevere() {
	$lan = $_REQUEST['lan'];

	$baseUrl = $_POST['BaseUrl'];
	$countryId = $_POST['CountryId'];
	$monthId = $_POST['MonthId'];
	$year = $_POST['Year'];
	$itemGroupId = $_POST['ItemGroupId'];
    $Reportby = $_POST['Reportby'];
	$sQuery = "SELECT
				    t_formulation.FormulationName
				    , SUM(t_cnm_patientoverview.TotalPatient)
				FROM
				    t_cnm_patientoverview
				    INNER JOIN t_cnm_masterstockstatus 
				        ON (t_cnm_patientoverview.CNMStockId = t_cnm_masterstockstatus.CNMStockId)
				    INNER JOIN t_formulation 
				        ON (t_cnm_patientoverview.FormulationId = t_formulation.FormulationId)
				WHERE (t_cnm_masterstockstatus.CountryId = $countryId
				    AND t_cnm_patientoverview.ItemGroupId = $itemGroupId
				    AND t_cnm_masterstockstatus.Year = $year
				    AND t_cnm_masterstockstatus.MonthId = $monthId
				    AND t_cnm_masterstockstatus.StatusId = 5
                    AND t_cnm_masterstockstatus.OwnerTypeId = $Reportby
				    AND t_formulation.bMajore = 1)
				GROUP BY t_formulation.FormulationName
				ORDER BY t_formulation.FormulationName ASC;";

	$rResult = safe_query($sQuery);

	$output = array();

	$countRows = mysql_num_rows($rResult);

	$allTotalPatients = 0;

	$aData = array();

	$rowIndex = 0;

	if ($countRows > 0) {
		while ($row = mysql_fetch_array($rResult)) {
			$tRow = array();

			$div1 = '<div>';
			$div2 = '<div>';
			$div1 .= '<span class="circle-num-rounded badge-warning" style="float:left;background-color:' . $row['STL_Color'] . '">' . (++$rowIndex) . '</span>' . $row['ServiceTypeName'];

			if ($row['TypeTotalPatient'] > 0) {
				$patientPercent = number_format($row['GTPatient'] / $row['TypeTotalPatient'] * 100, 1);
				$div2 .= getPopulation(number_format($patientPercent / 10, 1), $baseUrl);
				$div1 .= '<span class="badge badge-warning" style="float:right;">' . $patientPercent . ' %';
			} else {
				$div2 .= getPopulation(0, $baseUrl);
				$div1 .= '<span class="badge badge-warning" style="float:right;background-color:' . $row['STL_Color'] . '">' . 0 . ' %';
			}

			$div1 .= '</div>';
			$div2 .= '</div>';

			$tRow[] = $div1 . $div2;

			$aData[] = $tRow;
		}
	}

	echo '{"sEcho": ' . intval($_GET['sEcho']) . ', "iTotalRecords":"10","iTotalDisplayRecords": "10", "aaData":' . json_encode($aData) . '}';

}

function getPopulation($noOfPop, $baseUrl) {
	$lan = $_REQUEST['lan'];
	$strRet = '';
	//$noOfPop = 5.1;
	$intNoOfPop = intval($noOfPop);
	$fractionPart = $noOfPop - $intNoOfPop;

	$fractionPartArr = explode(".", $fractionPart);

	$fractionPart = intval($fractionPartArr[1]);

	$aMen = array_fill(0, 10, '<img src="' . $baseUrl . 'dashboard/images/human_0.png" alt="Human red" width="24" />');
	$tempI = 0;

	for ($i = 0; $i < $intNoOfPop; $i++) {
		$aMen[$i] = '<img src="' . $baseUrl . 'dashboard/images/human_10.png" alt="Human red" width="24" />';
		$tempI = $i + 1;
	}

	if ($fractionPart == 1)
		$aMen[$tempI] = '<img src="' . $baseUrl . 'dashboard/images/human_1.png" alt="Human red" width="24" />';
	else if ($fractionPart == 2)
		$aMen[$tempI] = '<img src="' . $baseUrl . 'dashboard/images/human_2.png" alt="Human red" width="24" />';
	else if ($fractionPart == 3)
		$aMen[$tempI] = '<img src="' . $baseUrl . 'dashboard/images/human_3.png" alt="Human red" width="24" />';
	else if ($fractionPart == 4)
		$aMen[$tempI] = '<img src="' . $baseUrl . 'dashboard/images/human_4.png" alt="Human red" width="24" />';
	else if ($fractionPart == 5)
		$aMen[$tempI] = '<img src="' . $baseUrl . 'dashboard/images/human_5.png" alt="Human red" width="24" />';
	else if ($fractionPart == 6)
		$aMen[$tempI] = '<img src="' . $baseUrl . 'dashboard/images/human_6.png" alt="Human red" width="24" />';
	else if ($fractionPart == 7)
		$aMen[$tempI] = '<img src="' . $baseUrl . 'dashboard/images/human_7.png" alt="Human red" width="24" />';
	else if ($fractionPart == 8)
		$aMen[$tempI] = '<img src="' . $baseUrl . 'dashboard/images/human_8.png" alt="Human red" width="24" />';
	else if ($fractionPart == 9)
		$aMen[$tempI] = '<img src="' . $baseUrl . 'dashboard/images/human_9.png" alt="Human red" width="24" />';

	for ($i = 0; $i < 10; $i++) {
		$strRet .= $aMen[$i];
	}
	return $strRet;
}

function getTotalPatients() {
	$lan = $_REQUEST['lan'];
	
	$countryId = $_POST['CountryId'];
	$monthId = $_POST['MonthId'];
	$year = $_POST['YearId'];
	$itemGroupId = $_POST['ItemGroupId'];
    $Reportby = $_POST['Reportby'];
    $Reportby = $_POST['Reportby'];
    
	$sQuery = "SELECT
				    IFNULL(SUM(t_cnm_patientoverview.TotalPatient),0) AS TotalPatient
				FROM
				    t_cnm_patientoverview
				    INNER JOIN t_cnm_masterstockstatus 
				        ON (t_cnm_patientoverview.CNMStockId = t_cnm_masterstockstatus.CNMStockId)
				WHERE ((t_cnm_masterstockstatus.CountryId = $countryId OR $countryId = 0)
				    AND t_cnm_patientoverview.ItemGroupId = $itemGroupId
				    AND t_cnm_masterstockstatus.Year = '$year'
				    AND t_cnm_masterstockstatus.MonthId = $monthId
				    AND t_cnm_masterstockstatus.StatusId = 5
                    AND t_cnm_masterstockstatus.OwnerTypeId = $Reportby);"; //echo $sQuery;

	$rResult = safe_query($sQuery);

	$output = array();

	while ($obj = mysql_fetch_object($rResult)) {
		$output[] = $obj;
	}
	
	echo json_encode($output);	
}

function getMaleFemale() {
	$lan = $_REQUEST['lan'];
	
	$countryId = $_POST['CountryId'];
	$monthId = $_POST['MonthId'];
	$year = $_POST['YearId'];
	$itemGroupId = $_POST['ItemGroupId'];
    $Reportby = $_POST['Reportby'];
    
	$sQuery = "SELECT
				    t_regimen_master.GenderTypeId
				    , SUM(t_cnm_regimenpatient.TotalPatient) TotalPatient
				FROM
				    t_cnm_regimenpatient
				    INNER JOIN t_cnm_masterstockstatus 
				        ON (t_cnm_regimenpatient.CNMStockId = t_cnm_masterstockstatus.CNMStockId)
				    INNER JOIN t_regimen 
				        ON (t_cnm_regimenpatient.RegimenId = t_regimen.RegimenId)
				    INNER JOIN t_regimen_master 
				        ON (t_regimen.RegMasterId = t_regimen_master.RegMasterId)
				WHERE (t_cnm_masterstockstatus.CountryId = $countryId
				    AND t_cnm_regimenpatient.ItemGroupId = $itemGroupId
				    AND t_cnm_masterstockstatus.Year = '$year'
				    AND t_cnm_masterstockstatus.MonthId = $monthId
                    
				    AND t_cnm_masterstockstatus.StatusId = 5)
				GROUP BY t_regimen_master.GenderTypeId
				ORDER BY t_regimen_master.GenderTypeId ASC;";
				//echo $sQuery;
//AND t_cnm_masterstockstatus.OwnerTypeId = $Reportby
	$rResult = safe_query($sQuery);

	$output = array();
	
	$grandTotal = 0;

	while ($obj = mysql_fetch_assoc($rResult)) {
		$grandTotal += $obj['TotalPatient'];
		$output[] = $obj;
	}
	
	$output2 = array();
	foreach ($output as $row) {
		//$output2 = array();
		$totalPerc = 0;
		if( $row['TotalPatient'] > 0 ){
			 $totalPerc = number_format($row['TotalPatient'] * 100 / $grandTotal);
		
		$row['TotalPerc'] = $totalPerc;
		$output2[] = $row;
		
		}
	}
	
	echo json_encode($output2);	
}

function getSimpleVsSevere1() {
	$lan = $_REQUEST['lan'];
	
	$countryId = $_POST['CountryId'];
	$monthId = $_POST['MonthId'];
	$year = $_POST['YearId'];
	$itemGroupId = $_POST['ItemGroupId'];
	$Reportby = $_POST['Reportby'];
    
	//$formulationName = '';
	
	if($lan == 'en-GB')
		$formulationName = 'FormulationName';
	else if($lan == 'fr-FR')
		$formulationName = 'FormulationNameFrench';

	
    if($itemGroupId > 0){
    $sQuery = "SELECT
				    $formulationName AS FormulationName
				    , IFNULL(SUM(t_cnm_patientoverview.TotalPatient),0) TotalPatient
				FROM
				    t_cnm_patientoverview
				    INNER JOIN t_cnm_masterstockstatus 
				        ON (t_cnm_patientoverview.CNMStockId = t_cnm_masterstockstatus.CNMStockId)
				    INNER JOIN t_formulation 
				        ON (t_cnm_patientoverview.FormulationId = t_formulation.FormulationId)
				WHERE ((t_cnm_masterstockstatus.CountryId = $countryId OR $countryId = 0)
				    AND t_cnm_patientoverview.ItemGroupId = $itemGroupId
				    AND t_cnm_masterstockstatus.Year = '$year'
				    AND t_cnm_masterstockstatus.MonthId = $monthId
				    AND t_cnm_masterstockstatus.StatusId = 5
                    AND t_cnm_masterstockstatus.OwnerTypeId = $Reportby
				    AND t_formulation.bMajore = 1)
				GROUP BY t_formulation.FormulationName
				ORDER BY t_formulation.FormulationName DESC;"; //echo $sQuery;
    }
    else{
        $sQuery = "SELECT
				    $formulationName AS FormulationName
				    , IFNULL(SUM(t_cnm_patientoverview.TotalPatient),0) TotalPatient
				FROM
				    t_cnm_patientoverview
				    INNER JOIN t_cnm_masterstockstatus 
				        ON (t_cnm_patientoverview.CNMStockId = t_cnm_masterstockstatus.CNMStockId)
				    INNER JOIN t_formulation 
				        ON (t_cnm_patientoverview.FormulationId = t_formulation.FormulationId)
                    INNER JOIN t_itemlist 
        			     ON (t_itemlist.ItemGroupId = t_cnm_patientoverview.ItemGroupId)
				WHERE ((t_cnm_masterstockstatus.CountryId = $countryId OR $countryId = 0)
				    AND t_cnm_masterstockstatus.Year = '$year'
				    AND t_cnm_masterstockstatus.MonthId = $monthId
				    AND t_cnm_masterstockstatus.StatusId = 5
                    AND t_cnm_masterstockstatus.OwnerTypeId = $Reportby
				    AND t_formulation.bMajore = 1
                    AND t_itemlist.bCommonBasket = 1)
				GROUP BY t_formulation.FormulationName
				ORDER BY t_formulation.FormulationName DESC";
    }
	//echo $sQuery;
    

	$rResult = safe_query($sQuery);

	$output = array();
	
	$grandTotal = 0;

	while ($obj = mysql_fetch_assoc($rResult)) {
		$grandTotal += $obj['TotalPatient'];
		$output[] = $obj;
	}
	
	$output2 = array();
	foreach ($output as $row) {
		//$output2 = array();
		$totalPerc = 0;
		if( $row['TotalPatient'] > 0 ){
			 $totalPerc = number_format($row['TotalPatient'] * 100 / $grandTotal);
		
		$row['TotalPerc'] = $totalPerc;
		$output2[] = $row;
		
		}
	}
	
	echo json_encode($output2);	
}

function getNationalSummaryChart(){
	$lan = $_REQUEST['lan'];
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
	  
    $Year = $_POST['YearId'];
    $ItemGroupId = $_POST['ItemGroupId'];
    $Month = $_POST['MonthId']; //echo $Month;
    $CountryId = $_POST['CountryId'];
	$Reportby = $_POST['Reportby'];
	
   if($ItemGroupId > 0){
       $sql = "  SELECT a.ItemNo, b.ShortName, SUM(DispenseQty) ReportedConsumption, SUM(ClStock) ReportedClosingBalance, SUM(AMC) AMC, IFNULL(((SUM(ClStock))/(SUM(AMC))),0) MOS                 
            	FROM t_cnm_stockstatus a 
                INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo AND b.bKeyItem = 1 AND b.ItemGroupId = ".$ItemGroupId."
            	INNER JOIN t_cnm_masterstockstatus c ON a.CNMStockId = c.CNMStockId and c.StatusId = 5 
            	WHERE a.MonthId = ".$Month." AND a.Year = ".$Year." 
                AND (a.CountryId = ".$CountryId." OR ".$CountryId." = 0)
				AND c.OwnerTypeId = ".$Reportby." 
				AND a.ItemGroupId = ".$ItemGroupId."
            	GROUP BY ItemNo, ShortName 
            	HAVING IFNULL(((SUM(ClStock))/(SUM(AMC))),0)>0"; 
   }else{
   	$sql = "  SELECT a.ItemNo, b.ShortName, SUM(DispenseQty) ReportedConsumption, SUM(ClStock) ReportedClosingBalance, SUM(AMC) AMC, IFNULL(((SUM(ClStock))/(SUM(AMC))),0) MOS                 
            	FROM t_cnm_stockstatus a 
                INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo AND b.bKeyItem = 1 AND b.bCommonBasket = 1
            	INNER JOIN t_cnm_masterstockstatus c ON a.CNMStockId = c.CNMStockId and c.StatusId = 5 
            	WHERE a.MonthId = ".$Month." AND a.Year = ".$Year." 
                AND (a.CountryId = ".$CountryId." OR ".$CountryId." = 0)
				AND c.OwnerTypeId = ".$Reportby."
            	GROUP BY ItemNo, ShortName 
            	HAVING IFNULL(((SUM(ClStock))/(SUM(AMC))),0)>0
				"; 
   	
   } //echo $sql;
    $result = mysql_query($sql);  
    $i = 0;    
   	while ($aRow = mysql_fetch_array($result)) {  
   	    
   	    $item_name[$i] =$aRow['ShortName'];
        $mos[$i] = number_format($aRow['MOS'],1);
        
        foreach($MosType as $key => $value){
             $min = $value['Min'];
             $max = $value['Max'];
             $color = $value['ColorCode'];              
             if ($mos[$i] == $min || ($mos[$i] > $min && $mos[$i] < $max)) $barcolor[$i] = $color;		            
        }  
		$mos[$i]=floatval($mos[$i]);
        $i++;              
    }
    
    $data=array();
    $data['item_name'] = $item_name;
    $data['temp'] = $mos;
    $data['barcolor'] = $barcolor;
    $data['name'] = $MonthList[$Month].', '.$Year;
    
    echo json_encode($data);
   // echo($data);  
   // echo '{"item_name":["AA","BB","CC","DD"],"temp":[17.1,12.5,7.8,29.9],"barcolor":["#4DAC26","#4DAC26","#FE9929","#50ABED"],"name":"July, 2014"}';
  
  
}

function getCaseSummaryChart(){
	$lan = $_REQUEST['lan'];
    $Mos = array();
    $MosType = array();
    $item_name = array();
    $mos = array();
    $barcolor = array();
          
  $lan = $_POST['lan'];
	 if($lan == 'en-GB'){
	 	   $FormulationName = 'FormulationName';
           $MonthList = array('1'=>'January','2'=>'February','3'=>'March','4'=>'April','5'=>'May','6'=>'June','7'=>'July','8'=>'August','9'=>'September','10'=>'October','11'=>'November','12'=>'December');
        }else{
            $MonthList = array('1'=>'Janvier','2'=>'Février','3'=>'Mars','4'=>'Avril','5'=>'Mai','6'=>'Juin','7'=>'Juillet','8'=>'Août','9'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'Décembre');
            $FormulationName = 'FormulationNameFrench';
	   
	    } 
		
	
	  
    $Year = $_POST['YearId'];
    $ItemGroupId = $_POST['ItemGroupId'];
    $Month = $_POST['MonthId']; //echo $Month;
    $CountryId = $_POST['CountryId'];
	$Reportby = $_POST['Reportby']; 
    
       $sql = "  SELECT c.$FormulationName FormulationName  ,c.ColorCode, SUM(IFNULL(b.TotalPatient,0))   AS TotalPatient           
            	FROM t_cnm_masterstockstatus a 
            	INNER JOIN  t_cnm_patientoverview b ON a.CNMStockId = b.CNMStockId
            	INNER JOIN t_formulation c ON b.FormulationId = c.FormulationId
            	WHERE b.FormulationId IN(3,4,5) AND a.StatusId = 5 AND b.MonthId = ".$Month." AND b.Year = ".$Year." 
                AND (b.CountryId = ".$CountryId." OR ".$CountryId." = 0)
				AND a.OwnerTypeId = ".$Reportby." AND b.ItemGroupId = ".$ItemGroupId."
				GROUP BY c.FormulationName , c.ColorCode";
       
      
     // echo $sql;          
    $result = mysql_query($sql);  
    $i = 0;    
   	while ($aRow = mysql_fetch_array($result)) {   	    
   	   settype($aRow['TotalPatient'],"float");
        $item_name[$i] = $aRow['FormulationName'];
        $mos[$i] = $aRow['TotalPatient'];
        $barcolor[$i] = $aRow['ColorCode']; 
        $i++;           
    }

    $data=array();
    $data['item_name'] = $item_name;
    $data['temp'] = $mos;
    $data['barcolor'] = $barcolor;
    $data['name'] = $MonthList[$Month].', '.$Year;
    
    echo json_encode($data);
  
}


function getPatientTrendTimeSeries() {
	$lan = $_REQUEST['lan'];
	
	$monthId = $_POST['MonthId'];
	$yearId = $_POST['YearId'];
	$countryId = $_POST['CountryId'];
	$itemGroupId = $_POST['ItemGroupId'];
	$frequencyId = $_POST['FrequencyId'];
	
	
	$currentYearMonth = $_POST['YearId'] . "-" . $_POST['MonthId'] . "-" . "01";
	$lastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "-11 month"));

	$countryId = $_POST['CountryId'];
	$itemGroupId = $_POST['ItemGroupId'];
 /*
	$sQuery = "SELECT
		    t_servicetype.ServiceTypeId
		    , t_cnm_masterstockstatus.Year
		    , t_cnm_masterstockstatus.MonthId
		    , t_servicetype.ServiceTypeName
		    , t_servicetype.STL_Color
		    , IFNULL(SUM(t_cnm_patientoverview.TotalPatient),0) TotalPatient
			FROM
		    t_servicetype
		    INNER JOIN t_formulation 
		        ON (t_servicetype.ServiceTypeId = t_formulation.ServiceTypeId)
		    INNER JOIN t_cnm_patientoverview 
		        ON (t_cnm_patientoverview.FormulationId = t_formulation.FormulationId)
		    INNER JOIN t_cnm_masterstockstatus 
        		ON (t_cnm_masterstockstatus.CNMStockId = t_cnm_patientoverview.CNMStockId)
        	 INNER JOIN t_reporting_frequency 
        		ON (t_cnm_masterstockstatus.CountryId = t_reporting_frequency.CountryId AND t_cnm_patientoverview.ItemGroupId = t_reporting_frequency.ItemGroupId)
        	WHERE CAST(CONCAT(t_cnm_masterstockstatus.Year,'-0',t_cnm_masterstockstatus.MonthId,'-01') AS DATETIME) BETWEEN CAST('$lastYearMonth' AS DATETIME) AND CAST('$currentYearMonth' AS DATETIME)  
		        AND (t_cnm_masterstockstatus.CountryId = $countryId OR $countryId = 0 )
		        AND t_cnm_masterstockstatus.StatusId = 5 
		        AND t_formulation.ItemGroupId = $itemGroupId
		        AND t_reporting_frequency.FrequencyId = $frequencyId
		 	GROUP BY t_servicetype.ServiceTypeId,t_servicetype.ServiceTypeName,t_cnm_masterstockstatus.Year, t_cnm_masterstockstatus.MonthId, t_servicetype.STL_Color
		    HAVING SUM(t_cnm_patientoverview.TotalPatient) > 0
		    ORDER BY ServiceTypeId, t_cnm_masterstockstatus.Year, t_cnm_masterstockstatus.MonthId";
*/
$sQuery = "SELECT
		    t_servicetype.ServiceTypeId
		    , t_cnm_masterstockstatus.Year
		    , t_cnm_masterstockstatus.MonthId
		    , t_servicetype.ServiceTypeName
		    , t_servicetype.STL_Color
		    , IFNULL(SUM(t_cnm_patientoverview.TotalPatient),0) TotalPatient
			FROM
		    t_servicetype
		    INNER JOIN t_formulation 
		        ON (t_servicetype.ServiceTypeId = t_formulation.ServiceTypeId)
		    INNER JOIN t_cnm_patientoverview 
		        ON (t_cnm_patientoverview.FormulationId = t_formulation.FormulationId)
		    INNER JOIN t_cnm_masterstockstatus 
        		ON (t_cnm_masterstockstatus.CNMStockId = t_cnm_patientoverview.CNMStockId)        	
        	WHERE CAST(CONCAT(t_cnm_masterstockstatus.Year,'-0',t_cnm_masterstockstatus.MonthId,'-01') AS DATETIME) BETWEEN CAST('$lastYearMonth' AS DATETIME) AND CAST('$currentYearMonth' AS DATETIME)  
		        AND (t_cnm_masterstockstatus.CountryId = $countryId OR $countryId = 0 )
		        AND t_cnm_masterstockstatus.StatusId = 5 
		        AND t_formulation.ItemGroupId = $itemGroupId
		 	GROUP BY t_servicetype.ServiceTypeId,t_servicetype.ServiceTypeName,t_cnm_masterstockstatus.Year, t_cnm_masterstockstatus.MonthId, t_servicetype.STL_Color
		    HAVING SUM(t_cnm_patientoverview.TotalPatient) > 0
		    ORDER BY ServiceTypeId, t_cnm_masterstockstatus.Year, t_cnm_masterstockstatus.MonthId";

			
	//echo $sQuery;

	$rResult = safe_query($sQuery);

	
	$monthListShort = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
	
	$quarterList = array(3 => 'Jan-Mar', 6 => 'Apr-Jun', 9 => 'Jul-Sep', 12 => 'Oct-Dec');

	$output = array('Categories' => array(), 'Series' => array(), 'Colors' => array());

	$output2 = array('name' => '', 'data' => array());
	$output3 = array('name' => '', 'data' => array());

	$tmpServiceTypeId = -1;

	while ($row = mysql_fetch_assoc($rResult)) {
		if(!is_null($row['TotalPatient']))	
			settype($row['TotalPatient'], "integer");
		
		if ($tmpServiceTypeId != $row['ServiceTypeId']) {
			$output2['name'] = $row['ServiceTypeName'];
			$output2['data'][] = $row['TotalPatient'];
			$tmpServiceTypeId = $row['ServiceTypeId'];
		} else {
			$output2['data'][] = $row['TotalPatient'];
			$tmpServiceTypeId = $row['ServiceTypeId'];
		}
		
		if($frequencyId == 1)
			$monthQuarterList = $monthListShort;
		else 
			$monthQuarterList = $quarterList;
		
		$output['Categories'][] = $monthQuarterList[$row['MonthId']].$row['Year']; 
		
		$output['Colors'][] = $row['STL_Color']; 
	}
	
	$output['Series'][] = $output2;
	
	echo json_encode($output);
}



function getFacilityStockoutTimeSeriesChart(){
	
    $EndMonthId = $_POST['EndMonthId']; 
    $EndYearId = $_POST['EndYearId']; 
    $Region = $_POST['RegionId']; 
	$DistrictId = $_POST['DistrictId']; 
    $ItemGroupId = $_POST['ItemGroupId'];    
    $CountryId = $_POST['Country'];   
    $OwnerTypeId = $_POST['OwnerTypeId']; 
			
		/*	
    $StartMonthId = $_POST['StartMonthId']; 
    $StartYearId = $_POST['StartYearId']; 
    $EndMonthId = $_POST['EndMonthId']; 
    $EndYearId = $_POST['EndYearId']; 
    $Region = $_POST['RegionId']; 
	$DistrictId = $_POST['DistrictId']; 
    $ItemGroupId = $_POST['ItemGroupId'];    
    $CountryId = $_POST['Country'];   
    $OwnerTypeId = $_POST['OwnerTypeId']; 
	 */
	 
        $months =3;// $_POST['MonthNumber'];
        $monthIndex = $_POST['EndMonthId'];
		$yearIndex = $_POST['EndYearId'];
		if($monthIndex<1){
		   $monthIndex = 12;
		   $yearIndex = $yearIndex-1;
		}
    
    settype($yearIndex, "integer");

    $month_name = array();
    $Tdetails = array();         
   	for ($i = 1; $i <= $months; $i++){
		
	
        $percent_query = "SELECT COUNT(DISTINCT a.FacilityId) as Total
                            FROM t_facility a
                            INNER JOIN t_facility_group_map b ON a.FacilityId = b.FacilityId 
                            INNER JOIN t_cfm_masterstockstatus f ON a.FacilityId = f.FacilityId and f.StatusId = 5 
							and (b.ItemGroupId = ".$ItemGroupId." OR 0=".$ItemGroupId." )
                            AND (a.FacilityCount IS NULL OR a.FacilityCount=0)
                            AND (RegionId = ".$Region." OR ".$Region." = 0)  
                            AND f.MonthId = ".$monthIndex." and f.Year = '".$yearIndex."' 
                            AND f.CountryId = ".$CountryId."
							AND a.OwnerTypeId = ".$OwnerTypeId."
							AND (a.DistrictId = ".$DistrictId." OR ".$DistrictId."=0)";   
		//echo  $percent_query;
    	$result_per = mysql_query($percent_query) or die("Query Fails:" . "<li> Errno=" . mysql_errno() . "<li> ErrDetails=" . mysql_error() . "<li>Query=" . $query);
    	
        while($row_per = mysql_fetch_object($result_per)){
    	   $Total = $row_per->Total;
    	}
        if($Total == NULL){$Total = 0;}
        
		if($ItemGroupId > 0){		
        $sql = "SELECT @s:=@s+1 Serial, TotalFacilityId FROM 
                (SELECT COUNT(DISTINCT c.FacilityId) TotalFacilityId
                FROM t_cfm_stockstatus a 
                INNER JOIN t_itemlist b on a.ItemNo = b.ItemNo and b.bKeyItem = 1 and b.ItemGroupId = ".$ItemGroupId."
                INNER JOIN t_facility c on a.FacilityId = c.FacilityId  AND (c.FacilityCount IS NULL OR c.FacilityCount=0)
                INNER JOIN t_itemgroup d on a.ItemGroupId = d.ItemGroupId and d.ItemGroupId = ".$ItemGroupId."  
                INNER JOIN t_facility_group_map e on a.FacilityId = e.FacilityId and e.ItemGroupId = ".$ItemGroupId."
                INNER JOIN t_cfm_masterstockstatus f on a.CFMStockId = f.CFMStockId and f.StatusId = 5 
                WHERE a.MonthId = ".$monthIndex." and a.Year = '".$yearIndex."' 
                AND (RegionId = ".$Region." OR ".$Region." = 0)
				AND f.CountryId = ".$CountryId."
				AND c.OwnerTypeId = ".$OwnerTypeId." 
				AND (c.DistrictId = ".$DistrictId." OR ".$DistrictId."=0)
                AND a.ClStock = 0) a, (SELECT @s:= 0) AS s  ";
		}
		else{	
		$sql = "SELECT @s:=@s+1 Serial, TotalFacilityId FROM 
					(SELECT COUNT(DISTINCT c.FacilityId) TotalFacilityId
					FROM t_cfm_stockstatus a 
					INNER JOIN t_itemlist b on a.ItemNo = b.ItemNo and b.bKeyItem = 1 AND b.bCommonBasket = 1
					INNER JOIN t_facility c on a.FacilityId = c.FacilityId  AND (c.FacilityCount IS NULL OR c.FacilityCount=0)
					INNER JOIN t_itemgroup d on a.ItemGroupId = d.ItemGroupId 
					INNER JOIN t_facility_group_map e on a.FacilityId = e.FacilityId 
					INNER JOIN t_cfm_masterstockstatus f on a.CFMStockId = f.CFMStockId and f.StatusId = 5 
					WHERE a.MonthId = ".$monthIndex." and a.Year = '".$yearIndex."' 
					AND (RegionId = ".$Region." OR ".$Region." = 0)
					AND f.CountryId = ".$CountryId."
					AND c.OwnerTypeId = ".$OwnerTypeId." 
					AND (c.DistrictId = ".$DistrictId." OR ".$DistrictId."=0)
					AND a.ClStock = 0) a, (SELECT @s:= 0) AS s  ";
					
		}	


        $result = mysql_query($sql);
        $total = mysql_num_rows($result); 
        $Pdetails = array();  
        
        if($total>0){         		
            while ($aRow = mysql_fetch_array($result)) {
				
                $totFac = $aRow['TotalFacilityId'];
                if($totFac > 0){
					
                    $Pdetails['Serial'] = $aRow['Serial'];
                    $Pdetails['MonthIndex'] = $monthIndex;
                    $Pdetails['PatientOverview'] = "% of Facilities Stocked Out";
                    $Pdetails['TotalPatient'] = number_format(($aRow['TotalFacilityId']/$Total)*100, 1); 
                    array_push($Tdetails, $Pdetails);  
                }                
            } 
            if($totFac > 0){        
                $mn = date("M", mktime(0,0,0,$monthIndex,1,0));
                $mn = $mn." ".$yearIndex;
                array_push($month_name, $mn); 
            }            
        }
        $monthIndex--;
        if ($monthIndex == 0){
        	$monthIndex = 12;   				
        	$yearIndex = $yearIndex - 1;			
        }
    }
 
    $rmonth_name = array();
    $RTdetails = array();  
    $rmonth_name = array_reverse($month_name);  
    $RTdetails = array_reverse($Tdetails);
    
    $serial = array();
    $patient_overview = array(); 
    $month_index = array();    
          
    foreach($RTdetails as $key => $value){
         $Serial = $value['Serial'];
         $PatientOverview = $value['PatientOverview'];
         $MonthIndex = $value['MonthIndex'];
         
         array_push($month_index, $MonthIndex);    
         array_push($serial, $Serial);
         array_push($patient_overview, $PatientOverview);           		            
    }     
    $userial = array_values(array_unique($serial));
    $upatient_overview = array_values(array_unique($patient_overview));
    $umonth_index = array_values(array_unique($month_index));  
        
    $service_tpatient = array();
    foreach($RTdetails as $value){ 
        
        $MonthIndex = $value['MonthIndex'];
        $Serial = $value['Serial'];
        $PatientOverview = $value['PatientOverview'];
		 if(!is_null($value['TotalPatient']))	
			 settype($value['TotalPatient'], "float");
        $TotalPatient = $value['TotalPatient'];   
           
        for($i = 0; $i<count($umonth_index); $i++){             
            if($umonth_index[$i] == $MonthIndex){
                for($x = 0; $x<count($userial); $x++){  
                    if($userial[$x] == $Serial){
                        $service_tpatient[$Serial][0] = $upatient_overview[$x];
                        $service_tpatient[$Serial][] = $TotalPatient;
                    }                                     
                }                
            }                              
        }                         
    }  
    $overview_name = array();
    $overview_value = array();
    
    for($i = 1; $i <= count($service_tpatient); $i++){
        array_push($overview_name, $service_tpatient[$i][0]); 
    } 
    
    for($i = 1; $i <= count($service_tpatient); $i++){
        $newarray = array_slice($service_tpatient[$i], 1);
        array_push($overview_value, $newarray);
    } 
        
    $data=array();
    $data['month_name'] = $rmonth_name;
    $data['overview_name'] = $overview_name;
    $data['datalist'] = $overview_value;
    $data['name'] = 'last';
   
    echo json_encode($data);
}


function getstockoutpercenttable(){	
    $Year = $_POST['YearId'];
    $ItemGroupId = $_POST['ItemGroupId'];
    $Month = $_POST['MonthId']; //echo $Month;
    $CountryId = $_POST['CountryId'];
	$Reportby = $_POST['Reportby'];
			
       
	if($ItemGroupId > 0){
        $percent_query = "SELECT IFNULL(COUNT(DISTINCT b.FacilityId),0) AS reportedFacilityCount
		FROM t_cfm_masterstockstatus a
		INNER JOIN t_cfm_stockstatus b ON a.CFMStockId = b.CFMStockId
		INNER JOIN t_facility c ON c.FacilityId = b.FacilityId AND c.FLevelId = 99
		WHERE  a.StatusId = 5
		AND b.ItemGroupId=$ItemGroupId 
		AND (b.CountryId=$CountryId OR $CountryId=0)
		AND c.OwnerTypeId = $Reportby
		AND b.Year = '$Year'
		AND b.MonthId=$Month;";   
	}
	else{
		$percent_query = "SELECT IFNULL(COUNT(DISTINCT b.FacilityId),0) AS reportedFacilityCount
		FROM t_cfm_masterstockstatus a
		INNER JOIN t_cfm_stockstatus b ON a.CFMStockId = b.CFMStockId
		INNER JOIN t_facility c ON c.FacilityId = b.FacilityId AND c.FLevelId = 99
		WHERE  a.StatusId = 5
		AND (b.CountryId=$CountryId OR $CountryId=0)
		AND c.OwnerTypeId = $Reportby
		AND b.Year = '$Year'
		AND b.MonthId=$Month;";   
	}

    	$result_per = mysql_query($percent_query);
    	
        while($row_per = mysql_fetch_object($result_per)){
    	   $TotalFacilityCount = $row_per->reportedFacilityCount;
    	}
		
		if($ItemGroupId > 0){		
			$sql = " SELECT a.ItemNo, a.ShortName, COUNT(b.FacilityId) AS StockOutFacilityCount
			FROM t_itemlist a
			INNER JOIN t_cfm_stockstatus b ON a.ItemNo = b.ItemNo
			INNER JOIN t_cfm_masterstockstatus c ON b.CFMStockId = c.CFMStockId AND c.StatusId = 5
			INNER JOIN t_facility d ON b.FacilityId = d.FacilityId AND d.FLevelId = 99
			WHERE a.bKeyItem = 1
			AND a.ItemGroupId=$ItemGroupId
			AND (b.CountryId=$CountryId OR $CountryId=0)
			AND d.OwnerTypeId = $Reportby
			AND b.Year = '$Year'
			AND b.MonthId=$Month
			AND IFNULL(b.ClStock,0) = 0
			GROUP BY a.ItemNo, a.ItemName
			HAVING COUNT(b.FacilityId) > 0;";		
		}
		else{
			$sql = "SELECT a.ItemNo, a.ShortName, COUNT(b.FacilityId) AS StockOutFacilityCount
			FROM t_itemlist a
			INNER JOIN t_cfm_stockstatus b ON a.ItemNo = b.ItemNo
			INNER JOIN t_cfm_masterstockstatus c ON b.CFMStockId = c.CFMStockId AND c.StatusId = 5
			INNER JOIN t_facility d ON b.FacilityId = d.FacilityId AND d.FLevelId = 99
			WHERE a.bKeyItem = 1 
			AND a.bCommonBasket = 1
			AND (b.CountryId=$CountryId OR $CountryId=0)
			AND d.OwnerTypeId = $Reportby
			AND b.Year = '$Year'
			AND b.MonthId=$Month
			AND IFNULL(b.ClStock,0) = 0
			GROUP BY a.ItemNo, a.ItemName
			HAVING COUNT(b.FacilityId) > 0;";
					
		}	
//echo $sql;

        $result = mysql_query($sql);
		$htmltable="<table class='table table-striped table-bordered display table-hover' cellspacing='0'><thead>
                        <tr>                            
                            <th style='text-align: left;'>Commodity</th>
                            <th style='text-align: right;'>Stocked Out</th>
                        </tr>
                    </thead><tbody>";
					
		if($TotalFacilityCount > 0){		
            while ($aRow = mysql_fetch_array($result)) {
				$facilitycount = $aRow['StockOutFacilityCount'];
				$htmltable=$htmltable."<tr><td style='text-align: left;'>". $aRow['ShortName'] ."</td>						
				<td style='text-align: right;'>". number_format((($facilitycount*100)/$TotalFacilityCount),2)."% (".$facilitycount."/".$TotalFacilityCount.")" ."</td></tr>";
            }
		}
			
		$htmltable=$htmltable."</tbody></table>";			
		echo $htmltable;
}

function getMonthsBtnTwoDate($firstDate, $lastDate) {
	$diff = abs(strtotime($lastDate) - strtotime($firstDate));
	$years = floor($diff / (365 * 60 * 60 * 24));
	$months = $years * 12 + floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
	return $months;
}
?>