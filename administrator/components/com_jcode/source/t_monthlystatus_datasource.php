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

$Year = $_POST['pYear'];
$Month = $_POST['pMonth'];

switch($task) {
	case "getFacilityData" :
		getFacilityData($conn);
		break;
    case "getFAssignedGroup" :
		getFAssignedGroup($conn);
		break;
    case "insertStock" :
		insertStock($conn);
		break;
    case "getItemsData" :
        getItemsData($conn);
        break;
    case "getRegimenList" :
        getRegimenList($conn);
        break;
    default :
		echo "{failure:true}";
		break;
}

function getFacilityData($conn) {
    
    mysql_query('SET CHARACTER SET utf8');
	global $Year, $Month;
    $CountryId = $_POST['CountryId'];

	$sLimit = "";
	if (isset($_POST['iDisplayStart'])) { $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
	}

	$sOrder = "";
	if (isset($_POST['iSortCol_0'])) { $sOrder = " ORDER BY  ";
		for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
			$sOrder .= fnColumnToField_facility(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
		}
		$sOrder = substr_replace($sOrder, "", -2);
	}

	$sWhere = "";
	if ($_POST['sSearch'] != "") {
		$sWhere = " AND (FacilityName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
	}
           
    $sql = "    SELECT a.FacilityId, a.FacilityCode, a.FacilityName, IFNULL(b.StockId,0) AS StockId, c.CountryId, c.CountryName 
				FROM t_facility a 
                LEFT JOIN t_cmmasterstockstatus b ON (a.FacilityId = b.FacilityId)              
				AND b.Year = '".$Year."' 
                AND b.MonthId = '".$Month."'
                , t_country c
				WHERE a.CountryId = c.CountryId 
                AND c.CountryId = '".$CountryId."'   
   	            $sWhere $sOrder $sLimit "; 
                
	$result = mysql_query($sql, $conn);
	$total = mysql_num_rows($result);
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

		$FacilityName = trim(preg_replace('/\s+/', ' ', addslashes($aRow['FacilityName'])));
        if($aRow['StockId'] == 0){
            $x = "<a class='task-del itmAdd' href='javascript:void(0);'><span class='label label-success'>Add</span></a>";
        }else{
            $x = "<a class='task-del itmEdit' href='javascript:void(0);'><span class='label label-warning'>Edit</span></a>"; 
        }
        
		if ($f++) $sOutput .= ',';       
		$sOutput .= "[";
		$sOutput .= '"' . addslashes($aRow['FacilityId']) . '",';
		$sOutput .= '"' . $serial++ . '",';
		$sOutput .= '"' . addslashes($aRow['FacilityCode']) . '",';
        $sOutput .= '"' . $FacilityName . '",';
		$sOutput .= '"' . $x .'"';		
		$sOutput .= "]";
	}
	$sOutput .= '] }';
	echo $sOutput;
}

function fnColumnToField_facility($i) {
	if ($i == 2)
		return "FacilityCode ";
    else if ($i == 3)
		return "FacilityName ";
}


function getFAssignedGroup($conn){
    
    mysql_query('SET CHARACTER SET utf8');
    
    $FacilityId = $_POST['pFacilityId'];
    
    $sql = " SELECT ItemGroupId FROM t_facility_service WHERE FacilityId = '".$FacilityId."' ";
    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    while ($r = mysql_fetch_array($result)) {
        $arrItemGroup[] = $r['ItemGroupId'];
    }
    if ($total == 0)
    echo '[]';
    else
    echo json_encode($arrItemGroup);  
}

function insertStock(){
    
    $User = $_POST['UserId'];
    $Month = $_POST['Month'];
    $Year = $_POST['Year'];
    $Country = $_POST['Country'];
    $FacilityId = $_POST['Facility'];
    $ItemGroupList = $_POST['ItemGroupList'];
      
	try {  
        $sql_count = "  SELECT COUNT(StockId) as M FROM t_cmmasterstockstatus 
                        WHERE FacilityId = '".$FacilityId."'
                        AND MonthId = '".$Month."' 
                        AND Year = '".$Year."'
                        AND CountryId = '".$Country."' ";   
                                
        $qr_count = mysql_query($sql_count, $conn);
        $r_count = mysql_fetch_object($qr_count);
        $re_num = $r_count->M; 
            
        if($re_num == 0){
            
    		$query = "SELECT MAX(StockId) as M FROM t_cmmasterstockstatus";
    		$qr = mysql_query($query);
    		$r = mysql_fetch_object($qr);
    		$StockId = $r->M;
    		$StockId++;
    
    		$insertMaster = mysql_query("INSERT INTO t_cmmasterstockstatus(StockId, FacilityId, MonthId, Year, CountryId, CreatedBy, CreatedDt)
                                VALUES('".$StockId."', '".$FacilityId."', '".$Month."', '".$Year."', '".$Country."', '".$User."', NOW())");
                                
            foreach ($ItemGroupList as $ItemGroup_list => $ItemGroupId) {
            
            	$itemsget = mysql_query("SELECT ItemNo FROM t_itemlist WHERE ItemGroupId = '".$ItemGroupId."' AND bStrength = 1 ORDER BY ItemName ASC");
                
               	while($data1 = mysql_fetch_array($itemsget)) {      	    
                    $ItemNo = $data1['ItemNo']; 
                      
                    $query = "SELECT MAX(StockStatusId) as M FROM t_cmstockstatus";
            		$qr = mysql_query($query);
            		$r = mysql_fetch_object($qr);
            		$StockStatusId = $r->M;
            		$StockStatusId++;
                
        			$insertStatus = mysql_query("INSERT INTO t_cmstockstatus(StockStatusId, StockId, FacilityId, MonthId, Year, UserId, ItemNo, ItemGroupId) 
                                                 VALUES('".$StockStatusId."', '".$StockId."', '".$FacilityId."', '".$Month."', '".$Year."', '".$User."', '".$ItemNo."', '".$ItemGroupId."')");                                                                    
           	    }
            }
        }
        echo "{ success:true }";          
    }
	catch (Exception $e) {
		mysql_query('ROLLBACK;');
		echo "{ failure:true, msg:'" . $e->getMessage() . "'}";
	}  
}

function getItemsData(){
    
    $User = $_POST['UserId'];
    $Month = $_POST['Month'];
    $Year = $_POST['Year'];
    $FacilityId = $_POST['Facility'];
    $ItemGroupId = $_POST['ItemGroupList'];
    $Country = $_POST['Country'];
    
    $sql = "SELECT a.StockStatusId, a.StockId, a.FacilityId, a.MonthId, a.Year, a.UserId, a.ItemNo, ItemName, a.ItemGroupId, OpStock, ReceiveQty, DispenseQty, 
            AdjustQtyPlus, AdjustQtyMinus, StockoutDays, ClStock, MOS, AMC, MaxQty         
			FROM t_cmstockstatus a
            INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo   
            WHERE FacilityId = '".$FacilityId."'
            AND MonthId = '".$Month."' 
            AND Year = '".$Year."' 
            AND a.ItemGroupId = '".$ItemGroupId."' "; //echo $sql;
                    
  	$result = mysql_query($sql);  
    $i = 0;     
   	while ($aRow = mysql_fetch_array($result)) {
   	    $i++;
   	    $data[] = array($i, $aRow['ItemName'], number_format($aRow['OpStock']), number_format($aRow['ReceiveQty']), number_format($aRow['DispenseQty']), number_format($aRow['AdjustQtyPlus']), number_format($aRow['AdjustQtyMinus']), number_format($aRow['ClStock']), number_format($aRow['MOS']));		   

    }               
       
    echo json_encode($data);
}

function getRegimenList(){
    
    $User = $_POST['UserId'];
    $Month = $_POST['Month'];
    $Year = $_POST['Year'];
    $FacilityId = $_POST['Facility'];
    $Country = $_POST['Country'];
    $Fromulation = $_POST['Fromulation'];
    
    $sql = "SELECT a.CMPatientStatusId, a.RegimenId, b.RegimenName, a.FacilityId, a.CountryId, a.PatientCount, a.MonthId, a.Year 
			FROM t_cmpatientstatus a
            INNER JOIN t_regimen b ON a.RegimenId = b.RegimenId 
            INNER JOIN t_formulation c ON b.FormulationId = c.FormulationId   
            WHERE a.FacilityId = '".$FacilityId."'
            AND a.MonthId = '".$Month."' 
            AND a.Year = '".$Year."' 
            AND a.CountryId = '".$Country."'
            AND b.FormulationId = '".$Fromulation."' "; 
                    
  	$result = mysql_query($sql);  
    $i = 0;     
   	while ($aRow = mysql_fetch_array($result)) {
   	    $i++;
   	    $data[] = array($i, $aRow['RegimenName'], number_format($aRow['PatientCount']));		   

    }               
       
    echo json_encode($data);
}






















































?>