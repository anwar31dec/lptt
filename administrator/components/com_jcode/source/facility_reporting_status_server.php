<?php
include_once ('database_conn.php');
include_once ('function_lib.php');

$task = '';
if (isset($_REQUEST['operation'])) {
    $task = $_REQUEST['operation'];
}

switch ($task) {
    case "getFacilityReportingStatus" :
        getFacilityReportingStatus();
        break;
    default :
        echo "{failure:true}";
        break;
}

function getFacilityReportingStatus() {

    $monthId = $_REQUEST['MonthId'];
    $year = $_REQUEST['YearId'];
    $country = $_REQUEST['CountryId'];
   // $itemGroupId = $_REQUEST['ItemGroupId'];
    //
    $regionId = $_REQUEST['RegionId'];
    $districtId = $_REQUEST['DistrictId'];
    $ownerTypeId = $_REQUEST['OwnerTypeId'];
    $sWhere = "";
    $condition = "";
    if ($regionId) {
        //$sWhere=" WHERE "; 
        $condition.= " and  (x.RegionId = $regionId OR $regionId =0 ) ";
    }

    if ($districtId) {
        $condition.= " and (x.DistrictId = $districtId OR $districtId = 0) ";
    }
    if ($ownerTypeId) {
        $condition.= " and  (x.OwnerTypeId = $ownerTypeId OR $ownerTypeId = 0) ";
    }

    /* Array of database columns which should be read and sent back to DataTables. Use a space where
     * you want to insert a non-database field (for example a counter or static image)
     */
    $aColumns = array('SL', 'FacilityId', 'FacilityCode', 'FacilityName', 'bEntered', 'CreatedDt', 'bSubmitted',
        'LastSubmittedDt', 'bAccepted', 'AcceptedDt', 'bPublished', 'PublishedDt','FLevelName', 'FLevelId');

    /* Indexed column (used for fast and accurate table cardinality) */
    $sIndexColumn = "FacilityId";

    /* DB table to use */
    $sTable = "t_cfm_masterstockstatus";
    /*
     * Paging
     */
    $sLimit = "";
    if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
        $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
    }

    /*
     * Ordering
     */
    $sOrder = "";
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
	

    /*
     * Filtering
     * NOTE this does not match the built-in DataTables filtering which does it
     * word by word on any field. It's possible to do here, but concerned about efficiency
     * on very large tables, and MySQL's regex functionality is very limited
     */

    // $sWhere = "";
    // if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
    // $sWhere = "WHERE (";
    // for ($i = 0; $i < count($aColumns); $i++) {
    // $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
    // }
    // $sWhere = substr_replace($sWhere, "", -3);
    // $sWhere .= ')';
    // }
// 		

    /* Individual column filtering */
    for ($i = 0; $i < count($aColumns); $i++) {

        if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch'] != '') {

            if ($sWhere == "") {
                $sWhere = "WHERE ";
            } else {
                $sWhere .= " OR ";
            }
            $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' ";
        }
    }

    /*
     * SQL queries
     * Get data to display
     */


    safe_query("SET @rank=0;");

    $serial = "@rank:=@rank+1 AS SL";

    $sQuery = "SELECT SQL_CALC_FOUND_ROWS " . $serial . ", b.FacilityId, b.FacilityCode, b.FacilityName,
				IFNULL( a.FacilityId,0) bEntered,				
				DATE_FORMAT(a.CreatedDt, '%d-%b-%Y %h:%i %p') CreatedDt,	
				IF(c.StatusId = '2', '1', '0') bSubmitted,
				DATE_FORMAT(a.LastSubmittedDt, '%d-%b-%Y %h:%i %p')  LastSubmittedDt,
				IF(c.StatusId = '3', '1', '0') bAccepted,
				DATE_FORMAT(a.AcceptedDt, '%d-%b-%Y %h:%i %p')  AcceptedDt,
				IF(c.StatusId = '5', '1', '0') bPublished,
				DATE_FORMAT(a.PublishedDt, '%d-%b-%Y %h:%i %p')  PublishedDt,FLevelName,b.FLevelId
				FROM  t_cfm_masterstockstatus a 
				RIGHT JOIN (SELECT x.FacilityId, x.FacilityCode, x.FacilityName ,FLevelName,t_facility_level.FLevelId
				FROM t_facility x 
				INNER JOIN t_facility_level ON x.FLevelId = t_facility_level.FLevelId
				WHERE x.CountryId = $country  $condition) b
				ON a.FacilityId = b.FacilityId AND  MonthId = $monthId 
				AND Year = '$year' AND a.CountryId = $country 
				LEFT JOIN t_status c ON a.StatusId = c.StatusId 
				$sWhere
				$sOrder
				$sLimit;";

    //echo $sQuery;
    $rResult = safe_query($sQuery);

    /* Data set length after filtering */
    $sQuery = "
				SELECT FOUND_ROWS()
			";
    $rResultFilterTotal = safe_query($sQuery);
    $aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
    $iFilteredTotal = $aResultFilterTotal[0];


    /* Total data set length */
    $sQuery = "
				SELECT COUNT(`" . $sIndexColumn . "`)
				FROM   $sTable
			";
    $rResultTotal = safe_query($sQuery);
    $aResultTotal = mysql_fetch_array($rResultTotal);
    $iTotal = $aResultTotal[0];

	
    $output = array("sEcho" => intval($_GET['sEcho']), "iTotalRecords" => $iFilteredTotal, "iTotalDisplayRecords" => $iFilteredTotal, "aaData" => array(), "aaData2" => array());

    while ($aRow = mysql_fetch_array($rResult)) {
        $row = array();
        for ($i = 0; $i < count($aColumns); $i++) {

            if ($aColumns[$i] == "bEntered") {
                //'<span class="glyphicon glyphicon-ok-circle" style="color:#ff0000;font-size:2em;"></span>'
                $row[] = ($aRow[$aColumns[$i]] == "0") ? '<span class="label label-danger">&nbsp No &nbsp</span>' : '<span class="label label-success">&nbsp Yes &nbsp</span>';
            } else if ($aColumns[$i] == "bSubmitted") {
                $row[] = ($aRow[$aColumns[$i]] == "0") ? '<span class="label label-danger">&nbsp No &nbsp</span>' : '<span class="label label-success">&nbsp Yes &nbsp</span>';
            } else if ($aColumns[$i] == "bAccepted") {
                $row[] = ($aRow[$aColumns[$i]] == "0") ? '<span class="label label-danger">&nbsp No &nbsp</span>' : '<span class="label label-success">&nbsp Yes &nbsp</span>';
                if ($aRow[$aColumns[$i]] == "1") {
                    $row[6] = '<span class="label label-success">&nbsp Yes &nbsp</span>';
                }
            } else if ($aColumns[$i] == "bPublished") {
                $row[] = ($aRow[$aColumns[$i]] == "0") ? '<span class="label label-danger">&nbsp No &nbsp</span>' : '<span class="label label-success">&nbsp Yes &nbsp</span>';
                if ($aRow[$aColumns[$i]] == "1") {
                    $row[6] = '<span class="label label-success">&nbsp Yes &nbsp</span>';
                    $row[8] = '<span class="label label-success">&nbsp Yes &nbsp</span>';
                }
            } else if ($aColumns[$i] != ' ') {
                /* General output */
                $row[] = $aRow[$aColumns[$i]];
            }
        }
        $output['aaData'][] = $row;
    }
	
    $sQuery2 = "SELECT b.FacilityId,
				IFNULL( a.FacilityId,0) bEntered,
				IF(c.StatusId = '2', '1', '0') bSubmitted,				
				IF(c.StatusId = '3', '1', '0') bAccepted,				
				IF(c.StatusId = '5', '1', '0') bPublished,FLevelName,b.FLevelId				
				FROM  t_cfm_masterstockstatus a 
				RIGHT JOIN (SELECT x.FacilityId, x.FacilityCode, x.FacilityName ,FLevelName,t_facility_level.FLevelId
				FROM t_facility x  
				INNER JOIN t_facility_level ON x.FLevelId = t_facility_level.FLevelId
				WHERE x.CountryId = $country  $condition
				
				) b
				ON a.FacilityId = b.FacilityId AND  MonthId = $monthId AND Year = '$year' AND a.CountryId = $country 
				LEFT JOIN t_status c ON a.StatusId = c.StatusId
				$sWhere
				";

    $rResult2 = safe_query($sQuery2);

    $num_rows = mysql_num_rows($rResult2);

    $aColumns2 = array('FacilityId', 'bEntered', 'bSubmitted', 'bAccepted', 'bPublished');

    $row2 = array('Entered' => 0, 'Submitted' => 0, 'Accepted' => 0, 'Published' => 0);

    while ($aRow2 = mysql_fetch_array($rResult2)) {
        for ($i = 0; $i < count($aColumns2); $i++) {
            if ($aColumns2[$i] == "bEntered") {
                if ($aRow2[$aColumns2[$i]] != 0) {
                    $row2['Entered']++;
                }
            } else if ($aColumns2[$i] == "bSubmitted") {
                if ($aRow2[$aColumns2[$i]] == "1") {
                    $row2['Submitted']++;
                }
            } else if ($aColumns2[$i] == "bAccepted") {
                if ($aRow2[$aColumns2[$i]] == "1") {
                    $row2['Submitted']++;
                    $row2['Accepted']++;
                }
            } else if ($aColumns2[$i] == "bPublished") {
                if ($aRow2[$aColumns2[$i]] == "1") {
                    $row2['Submitted']++;
                    $row2['Accepted']++;
                    $row2['Published']++;
                }
            }
        }
    }

    if ($num_rows > 0) {
        $row3 = array('Entered' => ($row2['Entered'] / $num_rows * 100), 'Submitted' => ($row2['Submitted'] / $num_rows * 100)
            , 'Accepted' => ($row2['Accepted'] / $num_rows * 100), 'Published' => ($row2['Published'] / $num_rows * 100));
    } else {
        $row3 = array('Entered' => 0, 'Submitted' => 0
            , 'Accepted' => 0, 'Published' => 0);
    }

    $output['aaData2'] = $row3;

    echo json_encode($output);
}

?>