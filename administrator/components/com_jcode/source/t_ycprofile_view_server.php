<?php

include_once ('database_conn.php');
include_once ("function_lib.php");

include('language/lang_en.php');
include('language/lang_fr.php');
include('language/lang_switcher_report.php');
$gTEXT = $TEXT;

$task = '';
if (isset($_REQUEST['operation'])) {
    $task = $_REQUEST['operation'];
}

switch ($task) {
    case "getCountryProfileParams" :
        getCountryProfileParams();
        break;
    case "getYcRegimenPatient" :
        getYcRegimenPatient();
        break;
    case "getYcFundingSource" :
        getYcFundingSource();
        break;
    case "getYcPledgedFunding" :
        getYcPledgedFunding();
        break;
    default :
        echo "{failure:true}";
        break;
}

function getTextBox1($v, $class, $id) {
    $r = $v;
    $r = ($r == '') ? '' : $r;
    $x = "<input  type='text' class='datacell " . $class . "' value='" . $r . "' id='" . $id . "'/>";
    //$x = $r;
    return $x;
}

function getCountryProfileParams() {
    /*     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Easy set variables
     */

    /* Array of database columns which should be read and sent back to DataTables. Use a space where
     * you want to insert a non-database field (for example a counter or static image)
     */
    if ($_REQUEST['lan'] == 'fr-FR') {
        $aColumns = array('SL', 'ParamNameFrench', 'YCValue', 't_cprofileparams.ParamId');
        $aColumns2 = array('SL', 'ParamNameFrench', 'YCValue', 'ParamId');
    } else {
        $aColumns = array('SL', 'ParamName', 'YCValue', 't_cprofileparams.ParamId');
        $aColumns2 = array('SL', 'ParamName', 'YCValue', 'ParamId');
    }

    /* Indexed column (used for fast and accurate table cardinality) */
    $sIndexColumn = "YCProfileId";

    /* DB table to use */
    $sTable = "t_ycprofile ";

    // Joins
    $sJoin = 'INNER JOIN t_cprofileparams ON t_ycprofile.ParamId = t_cprofileparams.ParamId ';
    //$sJoin  .= 'INNER JOIN t_country ON t_ycprofile.CountryId = t_country.CountryId ';

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
                $sOrder .= "" . $aColumns[intval($_GET['iSortCol_' . $i])] . " " . ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
            }
        }

        $sOrder = substr_replace($sOrder, "", -2);
        if ($sOrder == "ORDER BY") {
            $sOrder = "";
        }
    }
    $sOrder = " Order By t_ycprofile.ParamId ";
    /*
     * Filtering
     * NOTE this does not match the built-in DataTables filtering which does it
     * word by word on any field. It's possible to do here, but concerned about efficiency
     * on very large tables, and MySQL's regex functionality is very limited
     */
    $sWhere = "";

    // if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
    // $sWhere = "WHERE (";
    // for ($i = 0; $i < count($aColumns); $i++) {
    // $sWhere .= "" . $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
    // }
    // $sWhere = substr_replace($sWhere, "", -3);
    // $sWhere .= ')';
    // }

    /* Individual column filtering */
    for ($i = 0; $i < count($aColumns); $i++) {
        if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch'] != '') {
            if ($sWhere == "") {
                $sWhere = "WHERE ";
            } else {
                $sWhere .= " OR ";
            }
            $sWhere .= "" . $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' ";
        }
    }

    /* User Data Filtering */
    $bUserFilter = true;

    if ($bUserFilter) {
        if ($sWhere == "") {
            $sWhere = "WHERE ";
        } else {
            $sWhere .= " AND ";
        }
        //$sWhere .= "t_ycprofile.CountryId = 2 AND ReportDate = '" . $_GET['YearId']."-".$_GET['MonthId']."-01'";
        $sWhere .= "t_ycprofile.CountryId = " . $_GET['CountryId'] . " AND t_ycprofile.Year = " . $_GET['Year'] . "
		AND t_ycprofile.ItemGroupId=" . $_GET['ItemGroupId'] . "";
    }

    $bUseSL = true;
    $serial = '';

    if ($bUseSL) {
        safe_query("SET @rank=0;");
        $serial = "@rank:=@rank+1 AS ";
    }

    /*
     * SQL queries
     * Get data to display
     */

    $sQuery = "
			SELECT SQL_CALC_FOUND_ROWS " . $serial . str_replace(" , ", " ", implode(", ", $aColumns)) . "
			FROM   $sTable
			$sJoin
			$sWhere
			$sOrder
			$sLimit
			";
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

    /*
     * Output
     */
    $output = array("sEcho" => intval($_GET['sEcho']), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iFilteredTotal, "aaData" => array());

    //echo count($aColumns);

    while ($aRow = mysql_fetch_array($rResult)) {
        $row = array();
        //if()
        //print_r($aRow);
        //if ($aRow['ParamId'] == 12) {
         //   if (!empty($aRow['YCValue']))
        //        $aRow['ParamName'] = $aRow['ParamName'] . '<br />[' . $aRow['YCValue'] . ']';
        //    $aRow['YCValue'] = '';
       // }
        //break;
        for ($i = 0; $i < count($aColumns2); $i++) {
            /* General output */
            //if()
            $row[] = $aRow[$aColumns2[$i]];
        }
        $output['aaData'][] = $row;
    }

    echo json_encode($output);
}

function getYcRegimenPatient() {

    $lan = $_POST['lan'];
    if ($lan == 'en-GB') {
        $FormulationName = 'FormulationName';
    } else {
        $FormulationName = 'FormulationNameFrench';
    }
//echo 'hi';
    $CountryId = $_POST['country'];
    $Year = $_POST['year'];
    $ItemGroupId = $_POST['ItemGroupId'];

    if (!empty($CountryId) && !empty($Year)) {

        $sql_count = "SELECT COUNT(YearlyRegPatientId) as M						
						FROM t_yearly_country_regimen_patient 
						INNER JOIN t_regimen_master 
							ON t_regimen_master.RegMasterId = t_yearly_country_regimen_patient.RegMasterId
						WHERE t_yearly_country_regimen_patient.CountryId = " . $CountryId . "
						AND t_yearly_country_regimen_patient.Year = '" . $Year . "'	
						AND t_regimen_master.ItemGroupId = '" . $ItemGroupId . "'							
                        ";
        //echo $sql_count;				
        $qr_count = mysql_query($sql_count);
        $r_count = mysql_fetch_object($qr_count);
        $re_num = $r_count->M;

        if ($re_num == 0) {
            $sql_paramlist = "SELECT FormulationId,FormulationName,FormulationNameFrench FROM `t_formulation`
							WHERE ItemGroupId = $ItemGroupId
							order by FormulationId";
//echo $sql_paramlist;
            $result_paramlist = mysql_query($sql_paramlist);
            $total = mysql_num_rows($result_paramlist);

            while ($row_paramlist = mysql_fetch_object($result_paramlist)) {
                $FormulationId = $row_paramlist->FormulationId;

                $sql = "INSERT INTO `t_yearly_country_regimen_patient` 
				(`YearlyRegPatientId` ,`Year` ,`CountryId`,`ItemGroupId` ,`RegMasterId` ,`PatientCount` ,`FormulationId`)
				SELECT NULL, '" . $Year . "', '" . $CountryId . "',ItemGroupId, RegMasterId, '' ,'" . $FormulationId . "'
				FROM `t_regimen_master` WHERE  ItemGroupId = '" . $ItemGroupId . "';";

                //echo $sql;
                mysql_query($sql);
            }
        }
    }

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    //if (isset($_POST['iSortCol_0'])) { $sOrder = " ORDER BY  ";
    //	for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
    //		$sOrder .= fnColumnToField_regimen2(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
    //							" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
    //	}
    //	$sOrder = substr_replace($sOrder, "", -2);
    //}
    $columnsName = "";
    $sql = "SELECT SQL_CALC_FOUND_ROWS RegMasterId,RegimenName FROM `t_regimen_master`
				where ItemGroupId=" . $ItemGroupId . " Order By RegMasterId ASC;";

    //echo $sql;
    $result = mysql_query($sql);
    $total = mysql_num_rows($result);
    if ($total > 0) {
        while ($row = mysql_fetch_object($result)) {
            $columnsName.=',{ "sTitle": "' . $row->RegimenName . '","sWidth":"12%"}';
        }
    }

    $sql = "SELECT SQL_CALC_FOUND_ROWS YearlyRegPatientId, t_regimen_master.RegimenName, PatientCount,
				t_yearly_country_regimen_patient.FormulationId,$FormulationName	FormulationName
				FROM t_yearly_country_regimen_patient 
				INNER JOIN t_regimen_master ON t_yearly_country_regimen_patient.RegMasterId = t_regimen_master.RegMasterId 
				INNER JOIN t_formulation ON t_yearly_country_regimen_patient.FormulationId = t_formulation.FormulationId 
				WHERE t_yearly_country_regimen_patient.CountryId = '" . $CountryId . "' AND t_yearly_country_regimen_patient.Year = '" . $Year . "'	 
				AND t_regimen_master.ItemGroupId = " . $ItemGroupId . "	 				
				Order BY t_formulation.FormulationId ASC, t_yearly_country_regimen_patient.RegMasterId ASC;";
    //	echo $sql;		
    $result = mysql_query($sql);
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
    $serial = $_POST['iDisplayStart'];

    $f = 0;
    $tmpFormulationId = -1;
    while ($aRow = mysql_fetch_array($result)) {

        if ($tmpFormulationId != $aRow['FormulationId']) {

            if ($serial > 0) //For Close Bracket
                $sOutput .= "]";

            if ($f++)
                $sOutput .= ',';

            $sOutput .= "[";
            $sOutput .= '"' . ++$serial . '"';
            $sOutput .= ',"' . $aRow['FormulationName'] . '"';
            $sOutput .= ',"' . $aRow['PatientCount'] . '"'; //',"' . getTextBox1($aRow['PatientCount'],'yc_1',$aRow['YearlyRegPatientId']) . '"';

            $tmpFormulationId = $aRow['FormulationId'];
        }
        else {
            $sOutput .= ',"' . $aRow['PatientCount'] . '"'; //',"' . getTextBox1($aRow['PatientCount'],'yc_1',$aRow['YearlyRegPatientId']) . '"';
            $tmpFormulationId = $aRow['FormulationId'];
        }
    }
    if ($serial > 0) //For Close Bracket
        $sOutput .= "]";

    //$sOutput .= '],';
    $sOutput .= '] }';
    echo $sOutput;
    // echo '{'.$sOutput.' "COLUMNS":[{ "sTitle": "SL","sWidth":"10%"},{ "sTitle": "Formulation","sWidth":"13%"}'.$columnsName;
    //	echo ']}';	
}

function getYcFundingSource() {
    /*     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Easy set variables
     */

    /* Array of database columns which should be read and sent back to DataTables. Use a space where
     * you want to insert a non-database field (for example a counter or static image)
     */

    /* off 04 09 2014 this form linked new table just show ordery by sir
      if($_REQUEST['lan'] == 'fr-FR'){
      $aColumns = array('SL', 'ServiceTypeNameFrench', 'FormulationNameFrench', 'Y1', 'Y2', 'Y3', 'TotalRequirements');
      $aColumns2 = array('SL', 'ServiceTypeNameFrench', 'FormulationNameFrench', 'Y1', 'Y2', 'Y3', 'TotalRequirements');
      }else{
      $aColumns = array('SL', 'ServiceTypeName', 'FormulationName', 'Y1', 'Y2', 'Y3', 'TotalRequirements');
      $aColumns2 = array('SL', 'ServiceTypeName', 'FormulationName', 'Y1', 'Y2', 'Y3', 'TotalRequirements');
      }
      off 04 09 2014 this form linked new table just show ordery by sir
     */
    if ($_REQUEST['lan'] == 'fr-FR') {
        $aColumns = array('SL', 'ServiceTypeNameFrench', 'FundingReqSourceNameFrench', 'Y1', 'Y2', 'Y3', 'TotalRequirements');
        $aColumns2 = array('SL', 'ServiceTypeNameFrench', 'FundingReqSourceNameFrench', 'Y1', 'Y2', 'Y3', 'TotalRequirements');
    } else {
        $aColumns = array('SL', 'ServiceTypeName', 'FundingReqSourceName', 'Y1', 'Y2', 'Y3', 'TotalRequirements');
        $aColumns2 = array('SL', 'ServiceTypeName', 'FundingReqSourceName', 'Y1', 'Y2', 'Y3', 'TotalRequirements');
    }






    /* Indexed column (used for fast and accurate table cardinality) */
    $sIndexColumn = "FundingReqId";

    /* DB table to use */
    $sTable = "t_yearly_funding_requirements ";

    /* off 04 09 2014 this form linked new table just show ordery by sir

      // Joins
      $sJoin = 'INNER JOIN  t_formulation ON t_formulation.FormulationId = t_yearly_funding_requirements.FormulationId  ';
      $sJoin .= 'INNER JOIN t_servicetype ON t_servicetype.ServiceTypeId = t_formulation.ServiceTypeId ';
      $sJoin .= 'INNER JOIN t_itemgroup ON t_itemgroup.ItemGroupId = t_formulation.ItemGroupId ';
      ////$sJoin  .= 'INNER JOIN t_country ON t_ycprofile.CountryId = t_country.CountryId ';

      off 04 09 2014 this form linked new table just show ordery by sir
     */

// Joins
    $sJoin = 'INNER JOIN  t_fundingreqsources ON t_fundingreqsources.FundingReqSourceId = t_yearly_funding_requirements.FundingReqSourceId ';
    $sJoin .= 'INNER JOIN t_servicetype ON t_servicetype.ServiceTypeId = t_fundingreqsources.ServiceTypeId ';
    $sJoin .= 'INNER JOIN t_itemgroup ON t_itemgroup.ItemGroupId = t_fundingreqsources.ItemGroupId ';
    ////$sJoin  .= 'INNER JOIN t_country ON t_ycprofile.CountryId = t_country.CountryId ';





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
                $sOrder .= "" . $aColumns[intval($_GET['iSortCol_' . $i])] . " " . ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
            }
        }

        $sOrder = substr_replace($sOrder, "", -2);
        if ($sOrder == "ORDER BY") {
            $sOrder = "";
        }
    }
    $sOrder = "Order By t_fundingreqsources.FundingReqSourceId ";


    /*
     * Filtering
     * NOTE this does not match the built-in DataTables filtering which does it
     * word by word on any field. It's possible to do here, but concerned about efficiency
     * on very large tables, and MySQL's regex functionality is very limited
     */
    $sWhere = "";

    // if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
    // $sWhere = "WHERE (";
    // for ($i = 0; $i < count($aColumns); $i++) {
    // $sWhere .= "" . $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
    // }
    // $sWhere = substr_replace($sWhere, "", -3);
    // $sWhere .= ')';
    // }

    /* Individual column filtering */
    for ($i = 0; $i < count($aColumns); $i++) {
        if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch'] != '') {
            if ($sWhere == "") {
                $sWhere = "WHERE ";
            } else {
                $sWhere .= " OR ";
            }
            $sWhere .= "" . $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' ";
        }
    }

    /* User Data Filtering */
    $bUserFilter = true;

    if ($bUserFilter) {
        if ($sWhere == "") {
            $sWhere = "WHERE ";
        } else {
            $sWhere .= " AND ";
        }
        //$sWhere .= "t_ycprofile.CountryId = 2 AND ReportDate = '" . $_GET['YearId']."-".$_GET['MonthId']."-01'";
        $sWhere .= "t_yearly_funding_requirements.CountryId = " . $_GET['CountryId'] . "
		AND t_yearly_funding_requirements.Year = " . $_GET['Year'] . " AND t_yearly_funding_requirements.ItemGroupId=" . $_GET['ItemGroupId'];
    }

    $bUseSL = true;
    $serial = '';

    if ($bUseSL) {
        safe_query("SET @rank=0;");
        $serial = "@rank:=@rank+1 AS ";
    }

    /*
     * SQL queries
     * Get data to display
     */

    $sQuery = "
			SELECT SQL_CALC_FOUND_ROWS " . $serial . str_replace(" , ", " ", implode(", ", $aColumns)) . "
			FROM   $sTable
			$sJoin
			$sWhere
			$sOrder
			$sLimit
			";
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

    /*
     * Output
     */
    $output = array("sEcho" => intval($_GET['sEcho']), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iFilteredTotal, "aaData" => array());

    //echo count($aColumns);

    while ($aRow = mysql_fetch_array($rResult)) {
        $row = array();
        for ($i = 0; $i < count($aColumns2); $i++) {
            /* General output */
            $row[] = $aRow[$aColumns2[$i]];
        }
        $output['aaData'][] = $row;
    }

    echo json_encode($output);
}

/*
  function getYcPledgedFunding() {

  if($_REQUEST['lan'] == 'fr-FR'){
  $aColumns = 'f.FundingReqSourceNameFrench FormulationName, ServiceTypeNameFrench GroupName';
  }else{
  $aColumns = 'f.FundingReqSourceName FormulationName, ServiceTypeName GroupName';
  }

  $CountryId = $_POST['CountryId'];
  $Year = $_POST['Year'];
  $ItemGroupId = $_POST['ItemGroupId'];

  $RequirementYear = $_POST['RequirementYear'];
  $rowData = array();
  $dynamicColumns = array();
  $dynamiccolWidths = array();
  if (!empty($CountryId) && !empty($Year)) {
  $sql = "select f.FundingSourceId,s.FundingSourceName from t_yearly_country_fundingsource f
  Inner Join t_fundingsource s on s.FundingSourceId=f.FundingSourceId
  where  CountryId='" . $CountryId . "' and Year='" . $Year . "'  and f.ItemGroupId = '".$ItemGroupId."'
  Order By FundingSourceName asc ";

  //echo $sql;
  $resultPre = safe_query($sql);
  $total = mysql_num_rows($resultPre);

  $l = 0;
  $trecord = 0;
  if ($total > 0) {
  while($row=mysql_fetch_object($resultPre)){
  $FundingSourceId=$row->FundingSourceId;
  $col=array();
  $col['FundingSourceId'] =  $row->FundingSourceId;
  array_push($dynamicColumns,$col);
  }
  }

  $sql = "SELECT f.ItemGroupId,f.FundingReqSourceId, $aColumns
  FROM t_fundingreqsources f
  INNER JOIN t_servicetype ON t_servicetype.ServiceTypeId = f.ServiceTypeId
  INNER JOIN t_itemgroup g on g.ItemGroupId=f.ItemGroupId
  WHERE f.ItemGroupId = '".$ItemGroupId."'
  Order By f.FundingReqSourceId ";

  $result = safe_query($sql);
  $total = mysql_num_rows($result);

  $superGrandTotalRequirements=0;$superGrandFundingTotal=array();$superGrandSubTotal=0;$superGrandGapSurplus=0;
  $groupsubtotal=0;$groupsubTmp=-1;$p=0;$q=0;$r=0;$grandTotalRequirements=0;$grandFundingTotal=array();$grandSubTotal=0;$grandGapSurplus=0;
  while ($row = mysql_fetch_object($result)) {
  $ItemGroupId = $row -> ItemGroupId;
  $FundingReqSourceId = $row -> FundingReqSourceId;

  // group grand  total row
  if($p!=0&&$groupsubTmp!=$row -> GroupName){
  $l = 0;
  $cellData = array();
  $cellData[$l++]=$groupsubTmp;
  $cellData[$l++]='Total';
  $cellData[$l++]=$grandTotalRequirements;
  for ($j = 0; $j < count($dynamicColumns); $j++) {
  $subtotal=0;
  for ($k = 0; $k < count($grandFundingTotal); $k++)
  $subtotal+=$grandFundingTotal[$k][$j];
  $cellData[$l++]=$subtotal;
  $superGrandFundingTotal[$r][$j]=$subtotal;
  }
  //print_r($grandFundingTotal);
  $cellData[$l++]=$grandSubTotal;
  if ($grandGapSurplus >= 0){
  $cellData[ $l++] =number_format($grandGapSurplus);
  }
  else{
  $cellData[ $l++] = '(' . number_format((-1) * $grandGapSurplus ). ')';
  }
  $cellData[ $l++] = $ItemGroupId;
  $cellData[ $l++] = $FundingReqSourceId;
  $rowData[] = $cellData;
  // add super grand Total
  $superGrandTotalRequirements+=$grandTotalRequirements;
  $superGrandSubTotal+=$grandSubTotal;
  $superGrandGapSurplus+=$grandGapSurplus;
  // refresh group data
  $q=0;$grandTotalRequirements=0;$grandFundingTotal=array();$grandSubTotal=0;$grandGapSurplus=0;
  $r++;
  }
  // group grand  total row
  $l = 0;
  $cellData = array();
  $groupsubTmp=$row -> GroupName;
  $cellData[$l++] = $row -> GroupName;
  $cellData[$l++] = $row -> FormulationName;
  // fetch total require data

  $sql = "Select * from t_yearly_funding_requirements where CountryId='" . $CountryId . "'
  and Year='" . $Year . "' and ItemGroupId='" . $ItemGroupId . "' and FundingReqSourceId='" . $FundingReqSourceId . "' ";
  $result2 = safe_query($sql);
  $total2 = mysql_num_rows($result2);
  if ($total2 > 0) {
  $row2 = mysql_fetch_object($result2);
  if ($RequirementYear == 1) {
  $totalRequirement = $row2 -> Y1;
  } else if ($RequirementYear == 2) {
  $totalRequirement = $row2 -> Y2;
  } else if ($RequirementYear == 3) {
  $totalRequirement = $row2 -> Y3;
  }
  } else {
  $totalRequirement = 0;
  }

  // fetch pledged funding data
  $cellData[$l++] = $totalRequirement;
  $grandTotalRequirements+=$totalRequirement;
  $subtotal = 0;
  for ($j = 0; $j < count($dynamicColumns); $j++) {

  $FundingSourceId = $dynamicColumns[$j]['FundingSourceId'];
  $sql = "select * from t_yearly_pledged_funding where CountryId='" . $CountryId . "'
  and Year='" . $Year . "' and ItemGroupId='" . $ItemGroupId . "'
  and FundingReqSourceId='" . $FundingReqSourceId . "' and FundingSourceId='" . $FundingSourceId . "' ";
  $result3 = safe_query($sql);
  $total3 = mysql_num_rows($result3);
  if ($total3 == 0) {
  $subtotal += 0;
  $cellData[$l++] = 0;
  } else {
  $row3 = mysql_fetch_object($result3);
  $subtotal += $row3 -> TotalFund;
  $cellData[$l++ ] = $row3 -> TotalFund;
  }
  $grandFundingTotal[$q][$j]=$row3 -> TotalFund;

  }
  $cellData [$l++] = $subtotal;
  $grandSubTotal+=$subtotal;
  $surplus = $totalRequirement - $subtotal;
  if ($surplus >= 0){
  $cellData[ $l++] =number_format($surplus);
  $grandGapSurplus+=$surplus;
  }
  else{
  $cellData[ $l++] = '(' . number_format((-1) * $surplus ). ')';
  $grandGapSurplus+=$surplus;
  }
  $cellData[ $l++] = $ItemGroupId;
  $cellData[ $l++] = $FundingReqSourceId;

  $rowData[] = $cellData;
  // group grand  total row
  if($p==$total-1){
  $l = 0;
  $cellData = array();
  $cellData[$l++]=$groupsubTmp;
  $cellData[$l++]='Total';
  $cellData[$l++]=$grandTotalRequirements;
  for ($j = 0; $j < count($dynamicColumns); $j++) {
  $subtotal=0;
  for ($k = 0; $k < count($grandFundingTotal); $k++)
  $subtotal+=$grandFundingTotal[$k][$j];
  $cellData[$l++]=$subtotal;
  $superGrandFundingTotal[$r][$j]=$subtotal;
  }
  $cellData[$l++]=$grandSubTotal;
  if ($grandGapSurplus >= 0){
  $cellData[ $l++] =number_format($grandGapSurplus);
  }
  else{
  $cellData[ $l++] = '(' . number_format((-1) * $grandGapSurplus ). ')';
  }
  $cellData[ $l++] = $ItemGroupId;
  $cellData[ $l++] = $FundingReqSourceId;
  $rowData[] = $cellData;
  //add super grand Total
  $superGrandTotalRequirements+=$grandTotalRequirements;
  $superGrandSubTotal+=$grandSubTotal;
  $superGrandGapSurplus+=$grandGapSurplus;
  $r++;
  // Super Grand Total
  $l = 0;
  $cellData = array();
  $cellData[$l++]=$groupsubTmp;
  $cellData[$l++]='Grand Total';
  $cellData[$l++]=$superGrandTotalRequirements;

  for ($j = 0; $j < count($dynamicColumns); $j++) {
  $subtotal=0;
  for ($k = 0; $k < count($superGrandFundingTotal); $k++)
  $subtotal+=$superGrandFundingTotal[$k][$j];
  $cellData[$l++]=$subtotal;
  }
  $cellData[$l++]=$superGrandSubTotal;
  if ($superGrandGapSurplus >= 0){
  $cellData[ $l++] =number_format($superGrandGapSurplus);
  }
  else{
  $cellData[ $l++] = '(' . number_format((-1) * $superGrandGapSurplus ). ')';
  }
  $cellData[ $l++] = $ItemGroupId;
  $cellData[ $l++] = $FundingReqSourceId;
  $rowData[] = $cellData;
  }
  // group grand  total row
  $p++;$q++;
  //print_r($cellData);
  //break;
  }
  //print_r($grandSubTotal);
  echo '{"sEcho": ' . intval($_POST['sEcho']) . ', "iTotalRecords":' . $trecord . ',"iTotalDisplayRecords":' . $trecord . ', "aaData":[';
  $x=0; $f=0; $groupsubtotal=0;$groupsubTmp='-1';
  $endlimit=count($rowData);
  $groupsubTmp=-1;$p=0;
  while(count($rowData)>$x)
  {
  // group grand  total row
  // group grand  total row
  $groupsubTmp=$rowData[$x][1];
  if($f) echo ',';
  //print_r($rowData);
  if($rowData[$x][1]=='Grand Total'){
  $rowData[$x][1]='';
  echo '["Grand Total"';
  }else if($groupsubTmp=='Total')	  {
  $rowData[$x][1]='';
  echo '["'.$rowData[$x][0].' Total"';
  }else{
  $f++;
  if($f==$endlimit) {
  echo '["'.$f.'"';
  }else
  echo '["'.$f.'"';
  }
  $y=0;
  while(count($rowData[$x])>$y){
  if($y>1&&$y<(count($rowData[$x])-3)){
  echo  ',"'.number_format($rowData[$x][$y]).'"';
  }else
  echo  ',"'.$rowData[$x][$y].'"';
  $y++;
  }


  echo ']';

  $x++;
  }
  $str = '],"COLUMNS":[{ "sTitle": "SL","sWidth":"9%"}, { "sTitle": "Class", "sClass" : "hideme"}, { "sTitle": "Category","sClass":"column_bg"}, { "sTitle": "Total<br />Requirements", "sClass" :"tbl_right"}, ';
  $hideme = '';
  $sql = "select f.FundingSourceId,s.FundingSourceName from t_yearly_country_fundingsource f
  Inner Join t_fundingsource s on s.FundingSourceId=f.FundingSourceId
  where  CountryId='" . $CountryId . "' and Year='" . $Year . "' and f.ItemGroupId='".$ItemGroupId."'
  Order By FundingSourceName asc ";
  $resultPre = safe_query($sql);
  $total = mysql_num_rows($resultPre);
  $k=0;$odd=1;
  while ($row = mysql_fetch_object($resultPre)) {
  if($k%2==0){
  $str .= '{ "sTitle": "' . $row -> FundingSourceName . ' ", "sClass" : "format tbl_right column_bg "},';
  $odd=0;
  }else{
  $str .= '{ "sTitle": "' . $row -> FundingSourceName . ' ", "sClass" : "format tbl_right "},';
  $odd=1;
  }
  $k++;
  }
  if($odd==1){
  $str .= '{ "sTitle": "Total", "sClass" : "format tbl_right column_bg"},';
  $str .= '{ "sTitle": "Gap/Surplus", "sClass" : "format tbl_right "}';
  }else{
  $str .= '{ "sTitle": "Total", "sClass" : "format tbl_right "},';
  $str .= '{ "sTitle": "Gap/Surplus", "sClass" : "format tbl_right column_bg"}';
  }
  $str .= ']}';
  echo $str;
  }

  echo '{"sEcho": 1, "iTotalRecords":0,"iTotalDisplayRecords":0,
  "aaData":[["1","Malaria","RDT","4,091,688","0","0","0","0","4,091,688","155","1111"],
  ["2","Malaria","LLIN","271,032","0","0","0","0","271,032","551","2111"],
  ["3","Malaria","ACTs","13,251","0","0","0","0","13,251","561","3111"],
  ["4","Malaria","SP","904","0","0","0","0","904","156","4111"],
  ["5","Malaria","Severe Malaria","888,785","0","0","0","0","888,785","156","511111"],
  ["Malaria Total","Malaria","","5,265,660","0","0","0","0","5,265,660","1565","5111"],
  ["Grand Total","Malaria","","5,265,660","0","0","0","0","5,265,660","5651","511"]],
  "COLUMNS":[{ "sTitle": "SL","sWidth":"9%"},
  { "sTitle": "Class", "sClass" : "hideme"},
  { "sTitle": "Category","sClass":"column_bg"},
  { "sTitle": "Total<br />Requirements", "sClass" :"tbl_right"},
  { "sTitle": "GFATM ", "sClass" : "format tbl_right column_bg "},
  { "sTitle": "Government ", "sClass" : "format tbl_right "},
  { "sTitle": "PMI/USAID ", "sClass" : "format tbl_right column_bg "},
  { "sTitle": "Total", "sClass" : "format tbl_right "},
  { "sTitle": "Gap/Surplus", "sClass" : "format tbl_right column_bg"}]}';

  } */

//hideme
function getYcPledgedFunding() {
    $lan = $_POST['lan'];
    if ($lan == 'en-GB') {
        $ServiceTypeName = 'ServiceTypeName';
        $FundingReqSourceName = 'FundingReqSourceName';
    } else {
        $ServiceTypeName = 'ServiceTypeNameFrench';
        $FundingReqSourceName = 'FundingReqSourceNameFrench';
    }

    $CountryId = isset($_POST['country']) ? $_POST['country'] : '';
    $Year = isset($_POST['year']) ? $_POST['year'] : '';
    $RequirementYear = isset($_POST['RequirementYear']) ? $_POST['RequirementYear'] : '';
    $ItemGroupId = isset($_POST['ItemGroupId']) ? $_POST['ItemGroupId'] : '';

    $columnsName = "";
    $sql = "SELECT SQL_CALC_FOUND_ROWS t_yearly_country_fundingsource.FundingSourceId,FundingSourceName FROM t_yearly_country_fundingsource
		INNER JOIN t_fundingsource ON t_yearly_country_fundingsource.FundingSourceId=t_fundingsource.FundingSourceId
				where Year ='" . $Year . "' AND CountryId ='" . $CountryId . "' AND t_fundingsource.ItemGroupId = '" . $ItemGroupId . "'
				Order By FundingSourceId;";

    $result = mysql_query($sql);
    $total = mysql_num_rows($result);
    $subTotal = array();
    $grandTotal = array();
    if ($total > 0) {
        if ($lan == 'en-GB') {
            $columnsName.= '{ "sTitle": "SL","sWidth":"100px"}';
            $columnsName.= ',{"sTitle": "Class", "sClass" : "hideme"}'; //',{ "sTitle": "Service Type","sWidth":"100px"}';
            $columnsName.= ',{ "sTitle": "Category","sWidth":"100px"}';
            $columnsName.= ',{ "sTitle": "Total Requirements","sWidth":"100px", "sClass" : "format tbl_right "}';
        } else {
            $columnsName.= '{ "sTitle": "SL","sWidth":"100px"}';
            $columnsName.= ',{"sTitle": "Class", "sClass" : "hideme"}'; //',{ "sTitle": "Service Type","sWidth":"100px"}';
            $columnsName.= ',{ "sTitle": "catégorie","sWidth":"100px"}';
            $columnsName.= ',{ "sTitle": "total des besoins","sWidth":"100px", "sClass" : "format tbl_right "}';
        }

        while ($row = mysql_fetch_object($result)) {
            $columnsName.=',{ "sTitle": "' . $row->FundingSourceName . '","sWidth":"100px", "sClass" : "format tbl_right "}';
        }

        if ($lan == 'en-GB') {
            $columnsName.= ',{ "sTitle": "Total","sWidth":"100px", "sClass" : "format tbl_right "}';
            $columnsName.= ',{ "sTitle": "Gap/Surplus","sWidth":"100px", "sClass" : "format tbl_right "}';
        } else {
            $columnsName.= ',{ "sTitle": "total","sWidth":"100px", "sClass" : "format tbl_right "}';
            $columnsName.= ',{ "sTitle": "Gap/Surplus","sWidth":"100px", "sClass" : "format tbl_right "}';
        }
    }
	
	else{
		if ($lan == 'en-GB') 
			echo '{"aaData": [ ], "COLUMNS":[{ "sTitle": "Service Type","sWidth":"100px"},{ "sTitle": "Category","sWidth":"100px"}, { "sTitle": "Total Requirements","sWidth":"100px"}, { "sTitle": "Total","sWidth":"100px"},{ "sTitle": "Gap/Surplus","sWidth":"100px"}]}';
		else
			echo '{"aaData": [ ], "COLUMNS":[{ "sTitle": "Type de service offert","sWidth":"100px"},{ "sTitle": "Category","sWidth":"100px"}, { "sTitle": "total des besoins","sWidth":"100px"}, { "sTitle": "total","sWidth":"100px"},{ "sTitle": "Gap/Surplus","sWidth":"100px"}]}';	
	
		return;
	}
	

    $YValue = 'a.Y' . $RequirementYear;

    $sql = "SELECT d.ServiceTypeId, d.$ServiceTypeName ServiceTypeName, d.ServiceTypeNameFrench,
			b.FundingReqSourceId, b.$FundingReqSourceName FundingReqSourceName, b.FundingReqSourceNameFrench, IFNULL($YValue,0) YReq
			FROM t_yearly_funding_requirements a
			INNER JOIN t_fundingreqsources b ON a.FundingReqSourceId = b.FundingReqSourceId
			INNER JOIN t_servicetype d ON b.ServiceTypeId = d.ServiceTypeId						
			WHERE a.CountryId = " . $CountryId . "
			AND  a.ItemGroupId = " . $ItemGroupId . "
			AND a.Year = '" . $Year . "'
			ORDER BY b.ServiceTypeId, b.FundingReqSourceId;";
    //echo $sql;				
    $result = mysql_query($sql);
    $tmpServiceTypeId = -1;
    $tmpServiceTypeName = ' ';
    $sOutput = '"aaData": [ ';
    $f = 0;
    $sl = 0;
    while ($aRow = mysql_fetch_array($result)) {
        $Total = 0;
        $YReq = $aRow['YReq'];

        $FundingReqSourceId = $aRow['FundingReqSourceId'];

        if ($tmpServiceTypeId != $aRow['ServiceTypeId']) {
            if ($sl > 0) {
                $count = count($subTotal);
                for ($i = 0; $i < $count; $i++) {

                    if ($i > 2 && $i < $count - 1) {
                        $subTotal[$i] = number_format($subTotal[$i]);
                    }

                    settype($subTotal[$i], "string");
                }

                unset($subTotal);
            }
            $subTotal[0] = $aRow['ServiceTypeName'] . ' Total';
            $subTotal[1] = $aRow['ServiceTypeName'];
            $subTotal[2] = '';

            $grandTotal[0] = 'Grand Total';
            $grandTotal[1] = $aRow['ServiceTypeName'];
            $grandTotal[2] = '';
        }
        $subTotal[3] = isset($subTotal[3]) ? $subTotal[3] : '';
        $grandTotal[3] = isset($grandTotal[3]) ? $subTotal[3] : '';
        $subTotal[3] = $subTotal[3] + $YReq;
        $grandTotal[3] = $grandTotal[3] + $YReq;

        if ($sl == 0)
            $tmpServiceTypeId = $aRow['ServiceTypeId'];


        //$tmpServiceTypeName
        if ($f++)
            $sOutput .= ',';
        $sOutput .= "[";
        $sOutput .= '"' . ++$sl . '",';
        $sOutput .= '"' . $aRow['ServiceTypeName'] . '",';
        $sOutput .= '"' . $aRow['FundingReqSourceName'] . '",';
        $sOutput .= '"' . number_format($YReq) . '",';

        $sql1 = "SELECT a.PledgedFundingId, b.FundingSourceId, b.FundingSourceName, IFNULL($YValue,0) YCurr	
					FROM t_yearly_pledged_funding a
					INNER JOIN t_fundingsource b ON a.FundingSourceId = b.FundingSourceId								
					WHERE a.CountryId = " . $CountryId . "
					AND  a.ItemGroupId = " . $ItemGroupId . "
					AND a.Year = '" . $Year . "'
					AND a.FundingReqSourceId = " . $aRow['FundingReqSourceId'] . "
					ORDER BY b.FundingSourceId;";
        //echo $sql;				
        $sResult = mysql_query($sql1);
        $index = 3;
        while ($r = mysql_fetch_array($sResult)) {
            $sOutput .= '"' . number_format($r['YCurr']) . '",'; //'"'. getTextBox1($r['YCurr'],'yccurr_'.$FundingReqSourceId,$r['PledgedFundingId']) . '",';
            $Total+= $r['YCurr'];

            $index++;
            @$subTotal[$index] = $subTotal[$index] + $r['YCurr'];
            @$grandTotal[$index] = $grandTotal[$index] + $r['YCurr'];
        }

        $sOutput .= '"' . number_format($Total) . '",'; //'"'. getTextBox1(number_format($Total,1),'yctotal_'.$FundingReqSourceId,$aRow['PledgedFundingId']) . '",';	


        $subTotal[$index + 1] = isset($subTotal[$index + 1]) ? $subTotal[$index + 1] : '';
        $grandTotal[$index + 1] = isset($grandTotal[$index + 1]) ? $grandTotal[$index + 1] : '';
        $subTotal[$index + 1]+= $Total;
        $grandTotal[$index + 1]+= $Total;

        $sOutput .= '"' . number_format(($YReq - $Total)) . '"';  //'"'.getTextBox1(number_format(($YReq-$Total),1),'ycgaporsurplus_'.$FundingReqSourceId,$aRow['PledgedFundingId']). '"';
        
        $subTotal[$index + 2] = isset($subTotal[$index + 2]) ? $subTotal[$index + 2] : '';
        $grandTotal[$index + 2] = isset($grandTotal[$index + 2]) ? $grandTotal[$index + 2] : '';
        
        $subTotal[$index + 2] = $subTotal[$index + 2] + ($YReq - $Total);
        $grandTotal[$index + 2] = $grandTotal[$index + 2] + ($YReq - $Total);

        $subTotal[$index + 3] = '';
        $grandTotal[$index + 3] = '';
        $sOutput .= "]";

        //if($tmpServiceTypeId != $aRow['ServiceTypeId'])
        //	$sOutput .= ',["Malaria Total","","","5,265,6601","0","0","0","0","5,265,660"]';
    }

    $count = count($subTotal);
    for ($i = 0; $i < $count; $i++) {

        if ($i > 2 && $i < $count - 1) {
            $subTotal[$i] = number_format($subTotal[$i]);
            $grandTotal[$i] = number_format($grandTotal[$i]);
        }
        settype($subTotal[$i], "string");
        settype($grandTotal[$i], "string");
    }
    $sOutput .= ',' . json_encode($subTotal) . '';
    $sOutput .= ',' . json_encode($grandTotal) . '';
    //$sOutput .= ',["Grand Total","Malaria","","5,265,660","0","0","0","0","5,265,660"]';	

    $sOutput .= '],';

    echo '{"sEcho": 1, "iTotalRecords":0,"iTotalDisplayRecords":0,' . $sOutput . ' "COLUMNS":[' . $columnsName . ']}';

    /*

      echo '{"sEcho": 1, "iTotalRecords":0,"iTotalDisplayRecords":0,
      "aaData":[["1","Malaria","RDT","4,091,688","0","0","0","0","4,091,688","155","1111"],
      ["2","Malaria","LLIN","271,032","0","0","0","0","271,032","551","2111"],
      ["3","Malaria","ACTs","13,251","0","0","0","0","13,251","561","3111"],
      ["4","Malaria","SP","904","0","0","0","0","904","156","4111"],
      ["5","Malaria","Severe Malaria","888,785","0","0","0","0","888,785","156","511111"],
      ["Malaria Total","Malaria","","5,265,660","0","0","0","0","5,265,660","1565","5111"],
      ["Grand Total","Malaria","","5,265,660","0","0","0","0","5,265,660","5651","511"]],

      "COLUMNS":[{ "sTitle": "SL","sWidth":"9%"},
      { "sTitle": "Class", "sClass" : "hideme"},
      { "sTitle": "Category","sClass":"column_bg"},
      { "sTitle": "Total<br />Requirements", "sClass" :"tbl_right"},
      { "sTitle": "GFATM ", "sClass" : "format tbl_right column_bg "},
      { "sTitle": "Government ", "sClass" : "format tbl_right "},
      { "sTitle": "PMI/USAID ", "sClass" : "format tbl_right column_bg "},
      { "sTitle": "Total", "sClass" : "format tbl_right "},
      { "sTitle": "Gap/Surplus", "sClass" : "format tbl_right column_bg"}]}';
     */
}

?>