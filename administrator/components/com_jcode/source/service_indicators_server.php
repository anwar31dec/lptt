<?php
include ("define.inc");
$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());
mysql_query('SET CHARACTER SET utf8');

$task = '';
if (isset($_REQUEST['operation'])) {
    $task = $_REQUEST['operation'];
}

switch ($task) {
    case "getServiceIndicators" :
        getServiceIndicators();
        break;
    case "getTotalPatient" :
        getTotalPatient();
        break;
    default :
        echo "{failure:true}";
        break;
}

function getServiceIndicators() {

    mysql_query('SET CHARACTER SET utf8');
    $Year = $_POST['Year'];
    $Month = $_POST['Month'];
    $CountryId = $_POST['Country'];
    $ServiceType = $_POST['ServiceType'];

    if ($CountryId) {
        $CountryId = " AND a.CountryId = " . $CountryId . " ";
    }

    $regionId = $_REQUEST['RegionId'];
    $districtId = $_REQUEST['DistrictId'];

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " AND (FacilityName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
                         OR TotalPatient LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

   /* $sql = "SELECT SQL_CALC_FOUND_ROWS a.FacilityId, FacilityName, IFNULL(SUM(a.NewPatient),0) NewPatient,
	IFNULL(SUM(a.TotalPatient),0) TotalPatient 
            FROM t_cfm_patientoverview a
            INNER JOIN t_facility b ON a.FacilityId = b.FacilityId AND b.FLevelId = 99 	
            AND (b.RegionId = " . $regionId . " OR " . $regionId . " = 0)  
            AND (b.DistrictId = " . $districtId . " OR " . $districtId . " = 0)
            INNER JOIN t_formulation d ON a.FormulationId = d.FormulationId AND d.ServiceTypeId = " . $ServiceType . "
            INNER JOIN t_cfm_masterstockstatus f ON a.CFMStockId = f.CFMStockId AND StatusId = 5
            WHERE a.MonthId = " . $Month . " AND a.Year = '" . $Year . "' " . $CountryId . "  $sWhere  
            GROUP BY a.FacilityId, FacilityName
           	$sOrder $sLimit ";  
			*/
			$sql = "SELECT  a.FacilityId,FacilityName, c.RegimenName, 
			SUM(a.TotalPatient) AS TotalPatient,
			sum( if( b.RegMasterId = 1, a.TotalPatient, 0 ) ) AS Years04,
			sum( if( b.RegMasterId = 3, a.TotalPatient, 0 ) ) AS Years515, 
			sum( if( b.RegMasterId = 5, a.TotalPatient, 0 ) ) AS Years15,
			sum( if( b.RegMasterId = 7, a.TotalPatient, 0 ) ) AS Pregnant 
			
			FROM t_cfm_regimenpatient a
			INNER JOIN t_regimen b ON a.RegimenId = b.RegimenId
			INNER JOIN t_regimen_master c ON b.RegMasterId = c.RegMasterId
			INNER JOIN t_cfm_masterstockstatus d ON d.CFMStockId = a.CFMStockId AND d.StatusId = 5
			INNER JOIN t_formulation e ON e.FormulationId = b.FormulationId AND e.ServiceTypeId = " . $ServiceType . "
			INNER JOIN t_facility f ON a.FacilityId = f.FacilityId AND f.FLevelId = 99 
			AND (f.RegionId = " . $regionId . " OR " . $regionId . " = 0)  
			AND (f.DistrictId = " . $districtId . " OR " . $districtId . " = 0)
			WHERE a.MonthId = " . $Month . "
			AND a.Year = '" . $Year . "' " . $CountryId . "  $sWhere 
			GROUP BY a.FacilityId";  


    $result = mysql_query($sql);
    
    if ($result) {
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

            if ($f++)
                $sOutput .= ',';
            $sOutput .= "[";
            $sOutput .= '"' . $serial++ . '",';
            $sOutput .= '"' . addslashes($aRow['FacilityName']) . '",';
            $sOutput .= '"' . number_format($aRow['Years04']) . '",';
            $sOutput .= '"' . number_format($aRow['Years515']) . '",';
            $sOutput .= '"' . number_format($aRow['Years15']) . '",';
            $sOutput .= '"' . number_format($aRow['Pregnant']) . '",';
            $sOutput .= '"' . number_format($aRow['TotalPatient']) . '"';
            $sOutput .= "]";
        }
        $sOutput .= '] }';
        echo $sOutput;
    }
}

function fnColumnToField($i) {
    if ($i == 1)
        return "FacilityName ";
    else if ($i == 2)
        return "TotalPatient ";
    else if ($i == 3)
        return "NewPatient ";
}

function getTotalPatient() {

    $Year = $_POST['Year'];
    $Month = $_POST['Month'];
    $CountryId = $_POST['Country'];
    $ServiceType = $_POST['ServiceType'];
    $regionId = $_REQUEST['RegionId'];
    $districtId = $_REQUEST['DistrictId'];

    if ($CountryId) {
        $CountryId = " AND a.CountryId = " . $CountryId . " ";
    }

    $sql = "SELECT SQL_CALC_FOUND_ROWS a.FacilityId, FacilityName, IFNULL(SUM(a.NewPatient),0) NewPatient, IFNULL(SUM(a.TotalPatient),0) TotalPatient 
            FROM t_cfm_patientoverview a
            INNER JOIN t_facility b ON a.FacilityId = b.FacilityId AND b.FLevelId = 99
            AND (b.RegionId = " . $regionId . " OR " . $regionId . " = 0)  
            AND (b.DistrictId = " . $districtId . " OR " . $districtId . " = 0)	
            INNER JOIN t_formulation d ON a.FormulationId = d.FormulationId AND d.ServiceTypeId = " . $ServiceType . "
            INNER JOIN t_cfm_masterstockstatus f ON a.CFMStockId = f.CFMStockId AND StatusId = 5
            WHERE a.MonthId = " . $Month . " AND a.Year = '" . $Year . "' " . $CountryId . "  
            GROUP BY a.FacilityId, FacilityName"; //echo $sql;

    $result = mysql_query($sql);
    $totalPatient = 0;
    while ($aRow = mysql_fetch_object($result)) {
        $totalPatient = $totalPatient + $aRow->TotalPatient;
    }

    echo number_format($totalPatient);
}
?>














