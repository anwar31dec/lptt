<?php
include ("define.inc");
$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());

error_reporting(0);
 
$task = '';
if (isset($_POST['action'])) {
	$task = $_POST['action'];
} else if (isset($_GET['action'])) {
	$task = $_GET['action'];
}


switch($task) {
	case "getFacilityStockoutTimeSeriesChart" :
		getFacilityStockoutTimeSeriesChart();
		break;	
   	case "getFacilityStockoutTimeSeriesTable" :
		getFacilityStockoutTimeSeriesTable();
		break;
	default :
		echo "{failure:true}";
		break;
}

function getMonthsBtnTwoDate($firstDate, $lastDate) {
	$diff = abs(strtotime($lastDate) - strtotime($firstDate));
	$years = floor($diff / (365 * 60 * 60 * 24));
	$months = $years * 12 + floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
	return $months;
}

function getFacilityStockoutTimeSeriesChart(){
    $StartMonthId = $_POST['StartMonthId']; 
    $StartYearId = $_POST['StartYearId']; 
    $EndMonthId = $_POST['EndMonthId']; 
    $EndYearId = $_POST['EndYearId']; 
    $Region = $_POST['RegionId']; 
	$DistrictId = $_POST['DistrictId']; 
    $ItemGroupId = $_POST['ItemGroupId'];    
    $CountryId = $_POST['Country'];   
    $OwnerTypeId = $_POST['OwnerTypeId']; 
	 
	 
    if($_POST['MonthNumber'] != 0){
        $months = $_POST['MonthNumber'];
        $monthIndex = date("n");
        $yearIndex = date("Y");
    }else{
        $startDate = $StartYearId."-".$StartMonthId."-"."01";	
    	$endDate = $EndYearId."-".$EndMonthId."-"."10";	
    	$months = getMonthsBtnTwoDate($startDate, $endDate)+1;	      
        $monthIndex = $EndMonthId;
        $yearIndex = $EndYearId;   
    }   
    settype($yearIndex, "integer");
    	
    $month_name = array();
    $Tdetails = array();
   	for ($i = 1; $i <= $months; $i++){
		if($ItemGroupId > 0){
        $percent_query = "SELECT COUNT(DISTINCT a.FacilityId) as Total
                            FROM t_facility a
                            INNER JOIN t_facility_group_map b ON a.FacilityId = b.FacilityId 
                            INNER JOIN t_cfm_masterstockstatus f ON a.FacilityId = f.FacilityId and f.StatusId = 5 
							and b.ItemGroupId = ".$ItemGroupId." 
                            AND (a.FacilityCount IS NULL OR a.FacilityCount=0)
                            AND (RegionId = ".$Region." OR ".$Region." = 0)  
                            AND f.MonthId = ".$monthIndex." and f.Year = '".$yearIndex."' 
                            AND f.CountryId = ".$CountryId."
							AND a.OwnerTypeId = ".$OwnerTypeId."
							AND a.FLevelId = 99
							AND (a.DistrictId = ".$DistrictId." OR ".$DistrictId."=0)";   
		}
		else{
			$percent_query = "SELECT COUNT(DISTINCT a.FacilityId) as Total
                            FROM t_facility a
                            INNER JOIN t_facility_group_map b ON a.FacilityId = b.FacilityId 
                            INNER JOIN t_cfm_masterstockstatus f ON a.FacilityId = f.FacilityId and f.StatusId = 5 
                            AND (a.FacilityCount IS NULL OR a.FacilityCount=0)
                            AND (RegionId = ".$Region." OR ".$Region." = 0)  
                            AND f.MonthId = ".$monthIndex." and f.Year = '".$yearIndex."' 
                            AND f.CountryId = ".$CountryId."
							AND a.OwnerTypeId = ".$OwnerTypeId."
							AND a.FLevelId = 99
							AND (a.DistrictId = ".$DistrictId." OR ".$DistrictId."=0)";   
			
		}		
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
					AND c.FLevelId = 99
					AND (RegionId = ".$Region." OR ".$Region." = 0)
					AND f.CountryId = ".$CountryId."
					AND c.OwnerTypeId = ".$OwnerTypeId." 
					AND (c.DistrictId = ".$DistrictId." OR ".$DistrictId."=0)
					AND a.ClStock = 0) a, (SELECT @s:= 0) AS s  ";
		  }else
		  {
			  $sql = "SELECT @s:=@s+1 Serial, TotalFacilityId FROM 
					(SELECT COUNT(DISTINCT c.FacilityId) TotalFacilityId
					FROM t_cfm_stockstatus a 
					INNER JOIN t_itemlist b on a.ItemNo = b.ItemNo and b.bKeyItem = 1 and b.bCommonBasket = 1
					INNER JOIN t_facility c on a.FacilityId = c.FacilityId  AND (c.FacilityCount IS NULL OR c.FacilityCount=0)
					INNER JOIN t_itemgroup d on a.ItemGroupId = d.ItemGroupId
					INNER JOIN t_facility_group_map e on a.FacilityId = e.FacilityId
					INNER JOIN t_cfm_masterstockstatus f on a.CFMStockId = f.CFMStockId and f.StatusId = 5 
					WHERE a.MonthId = ".$monthIndex." and a.Year = '".$yearIndex."' 
					AND c.FLevelId = 99
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

function getFacilityStockoutTimeSeriesTable() {	
    
    $StartMonthId = $_POST['StartMonthId']; 
    $StartYearId = $_POST['StartYearId']; 
    $EndMonthId = $_POST['EndMonthId']; 
    $EndYearId = $_POST['EndYearId']; 
    $Region = $_POST['RegionId']; 
	$DistrictId = $_POST['DistrictId']; 
    $ItemGroupId = $_POST['ItemGroupId'];    
    $CountryId = $_POST['Country'];   
    $OwnerTypeId = $_POST['OwnerTypeId']; 
    
     if($_POST['MonthNumber'] != 0){
        $months = $_POST['MonthNumber'];
        $monthIndex = date("n");
        $yearIndex = date("Y");
    }else{
        $startDate = $StartYearId."-".$StartMonthId."-"."01";	
    	$endDate = $EndYearId."-".$EndMonthId."-"."01";	
    	$months = getMonthsBtnTwoDate($startDate, $endDate)+1;	      
        $monthIndex = $EndMonthId;
        $yearIndex = $EndYearId;   
    }   
    settype($yearIndex, "integer");
    /*
    if ($monthIndex == 1){
		$monthIndex = 12;				
		$yearIndex = $yearIndex - 1;				
	}else{
	    $monthIndex = $monthIndex - 1;
	}
    	*/
    $month_name = array();
    $Tdetails = array();     
        
   	for ($i = 1; $i <= $months; $i++){
   	    
        $percent_query = "  SELECT COUNT(DISTINCT a.FacilityId) as Total
                            FROM t_facility a
                            INNER JOIN t_facility_group_map b ON a.FacilityId = b.FacilityId 
                            INNER JOIN t_cfm_masterstockstatus f ON a.FacilityId = f.FacilityId and f.StatusId = 5 
							and b.ItemGroupId = ".$ItemGroupId." 
                            AND (a.FacilityCount IS NULL OR a.FacilityCount=0)
                            AND (RegionId = ".$Region." OR ".$Region." = 0) 
							AND (a.DistrictId = ".$DistrictId." OR ".$DistrictId."=0)
                            AND f.MonthId = ".$monthIndex." and f.Year = '".$yearIndex."' 
                            AND a.FLevelId = 99
                            AND f.CountryId = ".$CountryId."
							AND a.OwnerTypeId = ".$OwnerTypeId." ";      
             
    	$result_per = mysql_query($percent_query) or die("Query Fails:" . "<li> Errno=" . mysql_errno() . "<li> ErrDetails=" . mysql_error() . "<li>Query=" . $query);
    	
        while($row_per = mysql_fetch_object($result_per)){
    	   $Total = $row_per->Total;
    	}
        if($Total == NULL){$Total = 1;}
		  
        $sql = "SELECT @s:=@s+1 Serial, TotalFacilityId FROM 
                (SELECT COUNT(DISTINCT c.FacilityId) TotalFacilityId
                FROM t_cfm_stockstatus a 
                INNER JOIN t_itemlist b on a.ItemNo = b.ItemNo and b.bKeyItem = 1 and b.ItemGroupId = ".$ItemGroupId."
                INNER JOIN t_facility c on a.FacilityId = c.FacilityId  AND (c.FacilityCount IS NULL OR c.FacilityCount=0)
                INNER JOIN t_itemgroup d on a.ItemGroupId = d.ItemGroupId and d.ItemGroupId = ".$ItemGroupId."  
                INNER JOIN t_facility_group_map e on a.FacilityId = e.FacilityId and e.ItemGroupId = ".$ItemGroupId."
                INNER JOIN t_cfm_masterstockstatus f on a.CFMStockId = f.CFMStockId and f.StatusId = 5 
                WHERE a.MonthId = ".$monthIndex." and a.Year = '".$yearIndex."' 
				AND c.FLevelId = 99
                AND (RegionId = ".$Region." OR ".$Region." = 0) AND (c.DistrictId = ".$DistrictId." OR ".$DistrictId."=0) 
				AND f.CountryId = ".$CountryId."
				AND c.OwnerTypeId = ".$OwnerTypeId."
                AND a.ClStock = 0) a, (SELECT @s:= 0) AS s  ";  
           
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
                    $Pdetails['TotalPatient'] = number_format(($aRow['TotalFacilityId']/$Total)*100, 1).'%';					
					$Pdetails['PerMonthSoutFacilityCount'] = $aRow['TotalFacilityId']; 
					$Pdetails['PerMonthTotalFacilityCount'] = $Total; 
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
        $TotalPatient = $value['TotalPatient'];  
        $PerMonthSoutFacilityCount = $value['PerMonthSoutFacilityCount'];  
        $PerMonthTotalFacilityCount = $value['PerMonthTotalFacilityCount'];  
            
        for($i = 0; $i<count($umonth_index); $i++){             
            if($umonth_index[$i] == $MonthIndex){
                for($x = 0; $x<count($userial); $x++){  
                    if($userial[$x] == $Serial){
                        $service_tpatient[$Serial][0] = $upatient_overview[$x];
                        //$service_tpatient[$Serial][] = $TotalPatient;
                        $service_tpatient[$Serial][] = $TotalPatient.' ('.$PerMonthSoutFacilityCount.'/'. $PerMonthTotalFacilityCount .')';
                        //$service_tpatient[$Serial][] = $FacilityCount;
                    }                                     
                }                
            }                              
        }                         
    }  
    if(count($rmonth_name)>0){
        $str = '],"COLUMNS":[{"sTitle": "% of Facilities Stocked Out with One or More Products", "sClass" : "PatientType"}, ';	
    }else{
        $str = '],"COLUMNS":[{"sTitle": "% of Facilities Stocked Out with One or More Products", "sClass" : "PatientType"}';	
    }
    
    $f=0;
    foreach($rmonth_name as $mon){
        if($f++) $str.=', ';
        $str.= '{"sTitle": "'.$mon.'", "sClass" : "MonthName"}';                           
    }
	$str.= ']}';  
        
    echo '{"sEcho": ' . intval($_POST['sEcho']) . ', "iTotalRecords":"10","iTotalDisplayRecords": "10", "aaData":[';  
    $f=0;
    for($i = 1; $i <= count($service_tpatient); $i++){
        if($f++) echo ', '; 
        echo ''.json_encode($service_tpatient[$i]).'';      
    } 
	echo $str;  
}


?>