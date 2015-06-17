<?php
include_once ('database_conn.php');
include_once ("function_lib.php");
include('language/lang_en.php');
include('language/lang_fr.php');
include('language/lang_switcher_report.php');
$gTEXT = $TEXT;
 
$task = '';
if (isset($_POST['action'])) {
	$task = $_POST['action'];
} else if (isset($_GET['action'])) {
	$task = $_GET['action'];
}  
switch($task) {	
    case 'getFacilityData':
		getFacilityData($conn);
		break;
    case 'getCountryLocation':
		getCountryLocation($conn);
		break;
    case 'insertUpdateFacilityData':
		insertUpdateFacilityData($conn);
		break;
    case 'deleteFacilityData':
		deleteFacilityData($conn);
		break;
    case 'getFacilityCode':
		getFacilityCode($conn);
		break;
    case 'getAssignedGroup':
		getAssignedGroup($conn);
		break;
    case "getFacilityWarehouse" :
        getFacilityWarehouse($conn);
        break;
	case "getFacilityGroupMap" :
        getFacilityGroupMap();
        break;
    case "insertUpdateFacilityGroupMap" :
        insertUpdateFacilityGroupMap();
        break;
    case "deleteFacilityGroupMap" :
        deleteFacilityGroupMap();
        break;
	default :
		echo "{failure:true}";
		break;
}

/*****************************************************lab user authentication******************************************/
function getFacilityData($conn) {
	 	
    global $gTEXT; 	
	
	
	
	$lan = $_POST['lan'];
	 if($lan == 'en-GB'){
            $FLevelName = 'FLevelName';
		    $ServiceAreaName = 'ServiceAreaName';
			$OwnerTypeName = 'OwnerTypeName';
			$GroupName = 'GroupName';
        }else{
            $FLevelName = 'FLevelNameFrench';
			$ServiceAreaName = 'ServiceAreaNameFrench';
			$OwnerTypeName = 'OwnerTypeNameFrench';
			$GroupName = 'GroupNameFrench';
        }	
		
    mysql_query('SET CHARACTER SET utf8');
    
    $CountryId = $_POST['CountryId'];
    $FacilityType = $_POST['FacilityType'];
    $FacilityLevel = $_POST['FacilityLevel'];
    $ARegionId = $_POST['ARegionId'];
    $OwnerTypeId = $_POST['OwnerType']; 
    $DistrictId = $_POST['District-list'];
    $ServiceAreaId = $_POST['ServiceAreaId'];
    
    if($ARegionId){
		$ARegionId = " AND a.RegionId = '".$ARegionId."' ";
	}    
    if($FacilityType){
		$FacilityType = " AND a.FTypeId = '".$FacilityType."' ";
	}
    if($FacilityLevel){
		$FacilityLevel = " AND a.FLevelId = '".$FacilityLevel."' ";
	}  
    
    if($OwnerTypeId){
		$OwnerTypeId = " AND a.OwnerTypeId = '".$OwnerTypeId."' ";
	} 
    
    if($DistrictId){
		$DistrictId = " AND a.DistrictId = '".$DistrictId."' ";
	}
    
    if($ServiceAreaId){
		$ServiceAreaId = " AND a.ServiceAreaId = '".$ServiceAreaId."' ";
	}
    
    $sWhere = "";
	if ($_POST['sSearch'] != "") { 
        $sWhere = " AND (FacilityCode like '%".mysql_real_escape_string($_POST['sSearch'])."%'
                    OR FacilityName like '%".mysql_real_escape_string($_POST['sSearch'])."%'
                    OR FTypeName like '%".mysql_real_escape_string($_POST['sSearch'])."%'
                    OR RegionName like '%".mysql_real_escape_string($_POST['sSearch'])."%'
                    OR $FLevelName like '%".mysql_real_escape_string($_POST['sSearch'])."%'
                    OR FacilityAddress like '%".mysql_real_escape_string($_POST['sSearch'])."%'
                    OR FacilityPhone like '%".mysql_real_escape_string($_POST['sSearch'])."%'
                    OR FacilityFax like '%".mysql_real_escape_string($_POST['sSearch'])."%'
                    OR FacilityEmail like '%".mysql_real_escape_string($_POST['sSearch'])."%'
                    OR FacilityManager like '%".mysql_real_escape_string($_POST['sSearch'])."%'
                    OR DistrictName like '%".mysql_real_escape_string($_POST['sSearch'])."%'
                    OR $OwnerTypeName like '%".mysql_real_escape_string($_POST['sSearch'])."%'
                    OR $ServiceAreaName like '%".mysql_real_escape_string($_POST['sSearch'])."%' ) ";                                                                                         
	}
    
    $sLimit = "";
	if (isset($_POST['iDisplayStart'])) { 
	   $sLimit = "limit " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
	}
    
    $sOrder = "";
	if (isset($_POST['iSortCol_0'])) { $sOrder = " ORDER BY FLevelName, ";
		for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
			$sOrder .= fnColumnToGetFacility(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
		}
		$sOrder = substr_replace($sOrder, "", -2);
	}
 
	$sql = " SELECT SQL_CALC_FOUND_ROWS FacilityId, a.CountryId, a.RegionId, ParentFacilityId, 
             a.FTypeId, a.FLevelId, FacilityCode, FacilityName, FacilityAddress, FacilityPhone, FacilityFax, FacilityEmail, 
             FacilityManager, Latitude, Longitude, FacilityCount,$FLevelName FLevelName, FTypeName, RegionName,
             a.DistrictId, a.OwnerTypeId, a.ServiceAreaId, e.DistrictName, f.$OwnerTypeName OwnerTypeName, g.$ServiceAreaName ServiceAreaName, a.AgentType
             FROM t_facility a
             INNER JOIN t_facility_level b ON a.FLevelId = b.FLevelId
             INNER JOIN t_facility_type c ON a.FTypeId = c.FTypeId
             INNER JOIN t_region d ON a.RegionId = d.RegionId
             INNER JOIN t_districts e ON a.DistrictId = e.DistrictId
             INNER JOIN t_owner_type f ON a.OwnerTypeId = f.OwnerTypeId
             INNER JOIN t_service_area g ON a.ServiceAreaId = g.ServiceAreaId 	
             AND (a.CountryId = ".$CountryId." OR ".$CountryId." = 0) 
             ".$ARegionId." ".$DistrictId." ".$OwnerTypeId." ".$ServiceAreaId." ".$FacilityType." ".$FacilityLevel." 
             ".$sWhere." ".$sOrder." ".$sLimit." "; 
			 
	$pacrs = mysql_query($sql, $conn);
	$sql = "SELECT FOUND_ROWS()";
	$rs = mysql_query($sql, $conn);
	$r = mysql_fetch_array($rs);
	$total = $r[0];
	echo '{ "sEcho": ' . intval($_POST['sEcho']) . ', "iTotalRecords":' . $total . ', "iTotalDisplayRecords": ' . $total . ', "aaData":[';

    $f = 0;
	$serial = $_POST['iDisplayStart'] + 1;		
	  
	while ($row = @mysql_fetch_object($pacrs)) {
	    $FacilityId = $row -> FacilityId;
        $CountryId = $row -> CountryId;
        $FacilityCode = $row -> FacilityCode;
		$FacilityName = $row -> FacilityName;	        
		$FacilityAddress = $row -> FacilityAddress;	
        $FacilityPhone = $row -> FacilityPhone;
		$FacilityFax = $row -> FacilityFax;	
        $FacilityEmail = $row -> FacilityEmail;
		$FacilityManager = $row -> FacilityManager;	
		$FLevelName = $row -> FLevelName;
        $FTypeName = $row -> FTypeName;	
        $FTypeId = $row -> FTypeId;
        $FLevelId = $row -> FLevelId;	
        $Lat = $row -> Latitude;
		$Long = $row -> Longitude;
 	    $FacilityCount = $row -> FacilityCount;
        $RegionId = $row -> RegionId;
        $ParentFacilityId = $row -> ParentFacilityId;
        //$group_name = $row -> ParentFacilityId;
        $RegionName = $row -> RegionName;
        $DistrictName = $row -> DistrictName;
        $OwnerTypeName = $row -> OwnerTypeName;
        $ServiceAreaName = $row -> ServiceAreaName;	
        $DistrictId = $row -> DistrictId;
        $OwnerTypeId = $row -> OwnerTypeId;
        $agentType = $row -> AgentType;
        $ServiceAreaId = $row -> ServiceAreaId;	            
        $x = "<a class='itmMore' href='javascript:void(0);'><span class='label label-success'>".$gTEXT['More']."</span></a> ";
        $y = "<a class='itmEdit' href='javascript:void(0);'><span class='label label-info'>".$gTEXT['Edit']."</span></a> ";
        $z = "<a class='itmDrop' href='javascript:void(0);'><span class='label label-danger'>".$gTEXT['Delete']."</span></a> ";
    //$l = "<input type='checkbox' " . ($agentType == 3? 'checked':'') . " value = " . $agentType . " disabled/><span class='custom-checkbox'></span>";
        $l = "<input type='checkbox' " . ($agentType == 3? 'checked':'disabled') . " value = " . $agentType . " disabled/><span class='custom-checkbox'></span>";
        
          
        if($ParentFacilityId == NULL)$ParentFacilityId=0;
        
    	$sql_parent = " SELECT FacilityName PFacilityName
                 FROM t_facility
                 WHERE FacilityId = ".$ParentFacilityId." "; 
        $pacrs_parent = mysql_query($sql_parent, $conn);
        $r = mysql_fetch_object($pacrs_parent);
    	$PFacilityName = $r -> PFacilityName; 
            
        if($PFacilityName == "")$PFacilityName='None';
        
    	$sql_group = " SELECT FacilityId,$GroupName GroupName
                 FROM t_facility_group_map a
                 INNER JOIN t_itemgroup b ON a.ItemGroupId = b.ItemGroupId
                 WHERE FacilityId = ".$FacilityId." "; 
        $pacrs_group = mysql_query($sql_group, $conn);
        $group_name = "";
        $i = 0;
	    while ($row_group = @mysql_fetch_object($pacrs_group)) {	  
	       if ($i++) $group_name.= ", ";
	       $group_name.= $row_group -> GroupName;           
        }
      
		if ($f++)
			echo ",";           
                 
        echo '["'.$FacilityId.'", "'.$serial.'", "'.$FacilityCode.'", 
        "'.$FacilityName.'", "'.$FTypeName.'", "'.$RegionName.'", "'.$DistrictName.'",
        "'.$OwnerTypeName.'","'.$l.'","'.$ServiceAreaName.'", "'.$PFacilityName.'", 
        "'.$FacilityAddress.'", "'.$group_name.'", "'.$x.$y.$z.'", "'.$FacilityPhone.'", 
        "'.$FacilityFax.'", "'.$FacilityEmail.'", "'.$FacilityManager.'", "'.$Lat.'", "'.$Long.'", 
        "'.$CountryId.'", "'.$FTypeId.'", "'.$FLevelId.'", "'.$RegionId.'", "'.$ParentFacilityId.'", 
        "'.$FLevelName.'", "'.$FacilityCount.'", "'.$DistrictId.'", "'.$OwnerTypeId.'", "'.$ServiceAreaId.'","'.$agentType.'"]'; 
        $serial++;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             // ".$DistrictId." ".$OwnerTypeId." ".$ServiceAreaId."
	}
    echo ']}';
}

function fnColumnToGetFacility($i) {
	if ($i == 2)
		return "FacilityCode ";
    else if ($i == 3)
		return "FacilityName ";
    else if ($i == 4)
		return "FTypeName ";
    else if ($i == 5)
		return "RegionName ";
    else if ($i == 6)
		return "DistrictName ";
    else if ($i == 7)
		return "OwnerTypeName ";
    else if ($i == 9)
		return "ServiceAreaName ";
    else if ($i == 9)
		return "PFacilityName ";
    else if ($i == 11)
		return "FacilityAddress ";
    else if ($i == 12)
		return "GroupName ";
}

function getCountryLocation($conn){
    
    mysql_query('SET CHARACTER SET utf8');
    
    $CountryId = $_POST['countryId'];
    
    $sql = "SELECT CountryId, CountryName, ISO3, CenterLat, CenterLong, ZoomLevel
            FROM t_country a
            WHERE CountryId = '".$CountryId."' ";   
                                     
    $result = mysql_query($sql,$conn);
    $r_count = mysql_fetch_object($result);
    
    $CountryId = $r_count->CountryId; 
    $CenterLat = $r_count->CenterLat; 
    $CenterLong = $r_count->CenterLong; 
    $ZoomLevel = $r_count->ZoomLevel; 
    
    if($result) {
        echo '{"success":true, "CountryId": "'.$CountryId.'", "CenterLat": "'.$CenterLat.'", "CenterLong": "'.$CenterLong.'", "ZoomLevel": "'.$ZoomLevel.'"}';
    } else {
        echo '{"success":false, "Error": "Invalid query: ' . mysql_error() . '"}';
    }
}

function insertUpdateFacilityData($conn) {
 
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];
 
    $RecordId = $_POST['RecordId'];
    $CountryId = $_POST['counId'];
    $FTypeId = $_POST['FTypeId'];
    $PFacilityId = $_POST['PFacilityId'];
    if($PFacilityId == 0) $PFacilityId = 'NULL';
    $RegionId = $_POST['RegionId'];
    $FLevelId = $_POST['FLevelId'];
    $FacilityCode = $_POST['FacilityCode'];
    $FacilityName = $_POST['FacilityName'];
    $DistrictId = $_POST['ADistrict-list'];
    $OwnerTypeId = $_POST['AOwnerType'];
    $ServiceAreaId = $_POST['AServiceAreaId'];
    $FacilityAddress = $_POST['FacilityAddress'];
    $FacilityPhone = $_POST['FacilityPhone'];
    $FacilityFax = $_POST['FacilityFax'];
    $FacilityEmail = $_POST['FacilityEmail'];
    $FacilityManager = $_POST['FacilityManager'];
    $FacilityFax = $_POST['FacilityFax'];
    $FacilityCount = $_POST['FacilityCount'];
    $latlang = $_POST['location'];
	$latlngarray = explode(",", $latlang);
	$lat = $latlngarray[0];
	$lng = $latlngarray[1];
    $agentType = $_POST['AgentType'];
   // if($agentType == 1){
  //      $agentType = 3;
  //  }
  //  else{
  //      $agentType = 'NULL';
  //  }
    
    if($agentType == 'true' || $agentType == 'on'){$agentType = 3;}else{$agentType = 0;}
     
    $multiselectitems =  array();       
    $multiselectitems = $_POST['multiselectitems'];
    $AssignedItemList = json_decode($_POST['AssignedItemList'], TRUE); 
    $msg = '';
    $fetchSql = '';
	if($RecordId==''){
  
        $sql = "INSERT INTO t_facility(CountryId, RegionId, FTypeId, FLevelId, ParentFacilityId, FacilityCode, FacilityName, FacilityAddress, FacilityPhone, FacilityFax, FacilityEmail, FacilityManager, Latitude, Longitude, FacilityCount, ServiceAreaId, OwnerTypeId, AgentType, DistrictId)
                 VALUES (".$CountryId.", ".$RegionId.", ".$FTypeId.", " .$FLevelId.", ".$PFacilityId.", '".$FacilityCode."', '".$FacilityName."', '".$FacilityAddress."', '".$FacilityPhone."', '".$FacilityFax."', '".$FacilityEmail."', '".$FacilityManager."', '".$lat."', '".$lng."', '".$FacilityCount."',".$ServiceAreaId.", ".$OwnerTypeId.", '".$agentType."', ".$DistrictId.")";

        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_facility', 'pks' => array('FacilityId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);

		echo json_encode(exec_query($aQuerys, $jUserId, $language));
   
        

	}else{	 
        $sql = "UPDATE t_facility SET
                 CountryId = " . $CountryId . ",
                 RegionId = " . $RegionId . ",
                 FTypeId = " . $FTypeId . ",
                 FLevelId = " . $FLevelId . ",
                 ParentFacilityId = " . $PFacilityId . ",
                 FacilityCode = '" . $FacilityCode . "',
                 FacilityName = '" . $FacilityName . "',
                 FacilityAddress = '" . $FacilityAddress . "',
                 FacilityPhone = '" . $FacilityPhone . "',
                 FacilityFax = '" . $FacilityFax . "',
                 FacilityEmail = '" . $FacilityEmail . "',
                 FacilityManager = '" . $ItemGroupId . "',
                 Latitude = '" . $lat . "', 
                 Longitude = '" . $lng . "',
                 FacilityCount = '" . $FacilityCount . "',
                 ServiceAreaId = '".$ServiceAreaId."',
                 OwnerTypeId = '".$OwnerTypeId."',
                 AgentType = '".$agentType."',
                 DistrictId = '".$DistrictId."'
				 
                 WHERE FacilityId = " . $RecordId;				 
				 
				 

        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_facility', 'pks' => array('FacilityId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
        echo json_encode(exec_query($aQuerys, $jUserId, $language));                           
	}
	
	///20 07 2014 for Total Child update in every Parent
		$sqlParent = "SELECT distinct ParentFacilityId FROM t_facility where ParentFacilityId is not null;";
		$result=mysql_query($sqlParent);
		set_time_limit(0);
		while($rows=mysql_fetch_object($result)) {
			$TotCount = 0;			
			$sqlSet="SELECT count(*) FROM t_facility
				WHERE  ParentFacilityId =".$rows->ParentFacilityId."";
				
			$resultSet=mysql_query($sqlSet);
			$TotCount=mysql_result($resultSet,0,0);
			if($TotCount>0) {
				$query="Update t_facility Set FacilityCount = ".$TotCount." Where  FacilityId=".$rows->ParentFacilityId;
			   mysql_query($query);
				//echo $query;
			}	
			
		}	
}

function deleteFacilityData($conn) {
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];
    $RecordId = $_POST['RecordId'];

    if ($RecordId != '') {

        $sql_group = "DELETE FROM t_facility_group_map WHERE FacilityId = " . $RecordId;

        $aQuery1 = array('command' => 'DELETE', 'query' => $sql_group, 'sTable' => 't_facility_group_map', 'pks' => array('FacilityId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);

        $sql = "DELETE FROM t_facility WHERE FacilityId = " . $RecordId;

        $aQuery2 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_facility', 'pks' => array('FacilityId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1, $aQuery2);

        echo json_encode(exec_query($aQuerys, $jUserId, $language));

    }
}

function getFacilityCode($conn){   
    
    mysql_query('SET CHARACTER SET utf8');
    
    $CountryId = $_POST['countryId'];
	
   // $Query1 = "SELECT MAX(FacilityId) AS I FROM `t_facility` WHERE CountryId = '".$CountryId."' ";  
   //     $data = mysql_query($Query1);
   // 	$r1 = mysql_fetch_object($data);
   // 	$ItemNo = $r1 -> I; 
    
    $Query3 = "SELECT ISO3 AS ISO FROM `t_country` WHERE CountryId = '".$CountryId."' ";  
        $data = mysql_query($Query3);
    	$r1 = mysql_fetch_object($data);
    	$ISO = $r1 ->ISO;
    
   // $Query2 = "SELECT MAX(substr(FacilityCode,4,8))+1 AS M FROM t_facility WHERE FacilityId = '".$ItemNo."' ";  
   $Query2 = "SELECT MAX(substr(FacilityCode,4,8))+1 AS M FROM t_facility WHERE CountryId = '".$CountryId."' ";  
    	$qr = mysql_query($Query2);
    	$r = mysql_fetch_object($qr);
    	$ItemCode = $r -> M; 
    	  
    $padding = str_pad($ItemCode,4,'0',STR_PAD_LEFT);	
    $newItemCode = $ISO.$padding;
    
    echo '{"success":true, "newFCode": "'.$newItemCode.'"}';			
}   

function getAssignedGroup($conn){
    
    mysql_query('SET CHARACTER SET utf8');
    
    $FacilityId = $_POST['RecordId'];
    $row = array();

    $sql = " SELECT ItemGroupId FROM t_facility_group_map WHERE FacilityId = ".$FacilityId."  order by ItemGroupId ";
  
    $result = mysql_query($sql);
	$total = mysql_num_rows($result);
	while ($row = mysql_fetch_array($result)) {
		 $arr[] = $r;
         $data[]=array($row['ItemGroupId']);
	}       
	if ($total == 0)
		echo '[]';
    else{
   	    echo json_encode($data);   
	}
}

function getFacilityWarehouse($conn) {
    
    mysql_query('SET CHARACTER SET utf8');
    
    $CountryId = $_GET['CountryId'];
    
	/*$sql = " SELECT FacilityId, FacilityName 
                FROM t_facility a
                INNER JOIN t_facility_level b ON a.FLevelId = b.FLevelId 	
                WHERE a.CountryId = ".$CountryId." 
                AND (a.FacilityCount != 0 || a.ParentFacilityId IS NULL)
                ORDER BY FacilityName";  */
                
    $sql = " SELECT FacilityId, FacilityName 
                FROM t_facility a
                INNER JOIN t_facility_level b ON a.FLevelId = b.FLevelId 	
                WHERE a.CountryId = ".$CountryId." 
                AND b.FLevelId != 99
                ORDER BY FacilityName";  

	$result = mysql_query($sql, $conn);
	$total = mysql_num_rows($result);
	while ($r = mysql_fetch_array($result)) {
		$arr[] = $r;
	}
	if ($total == 0)
		echo '[]';
	else
		array_unshift($arr, array('FacilityId' => 0, 'FacilityName' => '[None]'));
		echo json_encode($arr);
}


function getFacilityGroupMap() {

    $facilityId = $_REQUEST['FacilityId'];

    $aColumns = array('SL'
        , 'FacilityServiceId'
        , 'FacilityId'
        , 'ItemGroupId'
        , 'GroupName'
        , 'StartMonthId'
        , 'MonthName'
        , 'StartYearId'
        , 'SupplyFrom'
        , 'Supplier'
        , 'Action');

    $sLimit = "";
    if (isset($_POST['iDisplayStart']) && $_POST['iDisplayLength'] != '-1') {
        $sLimit = "LIMIT " . intval($_POST['iDisplayStart']) . ", " . intval($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = "ORDER BY  ";
        for ($i = 0; $i < intval($_POST['iSortingCols']); $i++) {
            if ($_POST['bSortable_' . intval($_POST['iSortCol_' . $i])] == "true") {
                $sOrder .= "`" . $aColumns[intval($_POST['iSortCol_' . $i])] . "` " . ($_POST['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
            }
        }
        $sOrder = substr_replace($sOrder, "", -2);
        if ($sOrder == "ORDER BY") {
            $sOrder = "";
        }
    }

    for ($i = 0; $i < count($aColumns); $i++) {
        if (isset($_POST['bSearchable_' . $i]) && $_POST['bSearchable_' . $i] == "true" && $_POST['sSearch'] != '') {
            if ($sWhere == "") {
                $sWhere = " AND (";
            } else {
                $sWhere .= " OR ";
            }
            $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' ";
        }
    }

    if ($sWhere != "")
        $sWhere .= ")";
		
    $sQuery = "SELECT SQL_CALC_FOUND_ROWS
			    t_facility_group_map.FacilityServiceId
			    , t_facility_group_map.FacilityId
			    , t_facility_group_map.ItemGroupId
			    , t_itemgroup.GroupName
			    , t_facility_group_map.StartMonthId
			    , t_month.MonthName
			    , t_facility_group_map.StartYearId
			    , t_facility_group_map.SupplyFrom
			    , t_supplier.FacilityName Supplier
			FROM
			    t_facility_group_map
			    INNER JOIN t_itemgroup 
			        ON (t_facility_group_map.ItemGroupId = t_itemgroup.ItemGroupId)
			    INNER JOIN t_month 
			        ON (t_facility_group_map.StartMonthId = t_month.MonthId)
			    INNER JOIN t_facility 
			        ON (t_facility_group_map.FacilityId = t_facility.FacilityId)
			    LEFT JOIN t_facility t_supplier
			        ON (t_facility_group_map.SupplyFrom = t_supplier.FacilityId)
			WHERE (t_facility_group_map.FacilityId = $facilityId) $sWhere $sOrder $sLimit ";

    //echo  $sQuery;

    $rResult = mysql_query($sQuery);
    $sQuery = "SELECT FOUND_ROWS()";
    $rResultFilterTotal = mysql_query($sQuery);
    $aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
    $iFilteredTotal = $aResultFilterTotal[0];

    $iTotal = mysql_num_rows($rResult);

    $output = array("sEcho" => intval($_POST['sEcho']), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iFilteredTotal, "aaData" => array());

    $k = 0;

    while ($aRow = mysql_fetch_array($rResult)) {
        $row = array();
        for ($i = 0; $i < count($aColumns); $i++) {
            if ($aColumns[$i] == 'SL')
                $row[] = intval($_POST['iDisplayStart']) + (++$k);
            else if ($aColumns[$i] == 'Action') {
                $row[] = "<a class='itmEdit' href='javascript:void(0);'><span class='label label-info'>" . 'Edit' . "</span></a>"
                        . "<a class='itmDrop' href='javascript:void(0);'><span class='label label-danger'>" . 'Delete' . "</span></a>";
            }
            else
                $row[] = $aRow[$aColumns[$i]];
        }
        $output['aaData'][] = $row;
    }
    echo json_encode($output);
}

function insertUpdateFacilityGroupMap() {
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

   $FacilityServiceId = $_POST['FacilityServiceId'];
    $FacilityId = $_POST['FacilityId'];
    $ItemGroupId = $_POST['ItemGroupId'];
    $StartMonthId = $_POST['StartMonthId'];
    $StartYearId = $_POST['StartYearId'];
    $SupplyFrom = $_POST['SupplyFrom'];
    if($SupplyFrom==''){
        $SupplyFrom="NULL";
    }

    if ($FacilityServiceId === '') {

        $sql = "INSERT INTO t_facility_group_map
				            (FacilityId,
				             ItemGroupId,
				             StartMonthId,
				             StartYearId,
				             SupplyFrom) 
				VALUES ($FacilityId,
				        $ItemGroupId,
				        $StartMonthId,
				        '$StartYearId',
				        $SupplyFrom);";
        //echo $sql;
        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_facility_group_map', 'pks' => array('FacilityServiceId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);

        echo json_encode(exec_query($aQuerys, $jUserId, $language));
    } else {

        $sql = "UPDATE t_facility_group_map
				SET ItemGroupId = $ItemGroupId,
				  StartMonthId = $StartMonthId,
				  StartYearId = '$StartYearId',
				  SupplyFrom = $SupplyFrom
				WHERE FacilityServiceId = $FacilityServiceId;";

        //echo $sql;
        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_facility_group_map', 'pks' => array('FacilityServiceId'), 'pk_values' => array($FacilityServiceId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
        echo json_encode(exec_query($aQuerys, $jUserId, $language));
    }
	//echo $sql;
}


function deleteFacilityGroupMap() {
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];
    $FacilityServiceId = $_POST['FacilityServiceId'];

    $msg = '';
    $fetchSql = '';

    if ($FacilityServiceId != '') {

        $query1 = "DELETE
				   FROM t_facility_group_map
				   WHERE FacilityServiceId = $FacilityServiceId;";

        $aQuery1 = array('command' => 'DELETE', 'query' => $query1, 'sTable' => 't_facility_group_map', 'pks' => array('FacilityServiceId'), 'pk_values' => array($FacilityServiceId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);

        echo json_encode(exec_query($aQuerys, $jUserId, $language));
    }
}


?>