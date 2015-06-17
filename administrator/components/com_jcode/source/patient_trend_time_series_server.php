<?php

include ("define.inc");
$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());

include('language/lang_en.php');
include('language/lang_fr.php');
include('language/lang_switcher_report.php');
include_once ("function_lib.php");
$gTEXT = $TEXT;

//$jBaseUrl = $_GET['jBaseUrl']; 

$task = '';
if (isset($_POST['action'])) {
    $task = $_POST['action'];
} else if (isset($_GET['action'])) {
    $task = $_GET['action'];
}

switch ($task) {
    case "getPatientTrendTimeSeriesChart" :
        getPatientTrendTimeSeriesChart();
        break;
    case "getPatientTrendTimeSeriesTable" :
        getPatientTrendTimeSeriesTable();
        break;
    case "getPatientTrendTimeSeriesLineChart" :
        getPatientTrendTimeSeriesLineChart();
        break;
    default :
        echo "{failure:true}";
        break;
}

function getPatientTrendTimeSeriesLineChart() {
    $lan = $_REQUEST['lan'];
    $StartMonthId = isset($_POST['StartMonthId']) ? $_POST['StartMonthId'] : '';
    $StartYearId = isset($_POST['StartYearId']) ? $_POST['StartYearId'] : '';
    $EndMonthId = isset($_POST['EndMonthId']) ? $_POST['EndMonthId'] : '';
    $EndYearId = isset($_POST['EndYearId']) ? $_POST['EndYearId'] : '';
    $countryId = $_POST['Country'];
    $itemGroupId = $_POST['ItemGroupId'];
    $frequencyId = 1; // $_POST['FrequencyId'];

    if ($lan == 'en-GB') {
        $serviceTypeName = 'ServiceTypeName';
    } else {
        $serviceTypeName = 'ServiceTypeNameFrench';
    }

    if ($_POST['MonthNumber'] != 0) {
        $months = $_POST['MonthNumber'];
        $monthIndex = date("m");
        $yearIndex = date("Y");
        settype($yearIndex, "integer");
        if ($monthIndex == 1) {
            $monthIndex = 12;
            $yearIndex = $yearIndex - 1;
        } else {
            $monthIndex = $monthIndex - 1;
        }
        $months = $months - 1;

        $d = cal_days_in_month(CAL_GREGORIAN, $monthIndex, $yearIndex);
        $EndYearMonth = $yearIndex . "-" . str_pad($monthIndex, 2, "0", STR_PAD_LEFT) . "-" . $d;
        $EndYearMonth = date('Y-m-d', strtotime($EndYearMonth));

        $StartYearMonth = $yearIndex . "-" . str_pad($monthIndex, 2, "0", STR_PAD_LEFT) . "-" . "01";
        $StartYearMonth = date('Y-m-d', strtotime($StartYearMonth));
        $StartYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($StartYearMonth)) . "-" . $months . " month"));
    } else {
        $startDate = $StartYearId . "-" . $StartMonthId . "-" . "01";
        $StartYearMonth = date('Y-m-d', strtotime($startDate));

        $d = cal_days_in_month(CAL_GREGORIAN, $EndMonthId, $EndYearId);
        $endDate = $EndYearId . "-" . $EndMonthId . "-" . $d;
        $EndYearMonth = date('Y-m-d', strtotime($endDate));
    }


    $monthListShort = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
    $quarterList = array(3 => 'Jan-Mar', 6 => 'Apr-Jun', 9 => 'Jul-Sep', 12 => 'Oct-Dec');
    $output = array('Categories' => array(), 'Series' => array(), 'Colors' => array());
    $output2 = array('name' => '', 'data' => array());

    if ($frequencyId == 1)
        $monthQuarterList = $monthListShort;
    else
        $monthQuarterList = $quarterList;

    $month_list = array();
    $startDate = strtotime($StartYearMonth);
    $endDate = strtotime($EndYearMonth);
    $index = 0;
    while ($endDate >= $startDate) {
        if ($frequencyId == 1) {
            $monthid = date('m', $startDate);
            settype($monthid, "integer");
            $ym = $monthListShort[$monthid] . ' ' . date('Y', $startDate);
            $month_list[$index] = $ym;
            $output['Categories'][] = $ym;
            $index++;
        } else {
            $monthid = date('m', $startDate);
            settype($monthid, "integer");
            if ($monthid == 3 || $monthid == 6 || $monthid == 9 || $monthid == 12) {
                $ym = $quarterList[$monthid] . ' ' . date('Y', $startDate);
                $month_list[$index] = $ym;
                $output['Categories'][] = $ym;
                $index++;
            }
        }

        $startDate = strtotime(date('Y/m/d', $startDate) . ' 1 month');
    }
    // //////////////////

    $sQuery = "SELECT a.ServiceTypeId, IFNULL(SUM(c.TotalPatient),0) TotalPatient
			, $serviceTypeName ServiceTypeName, a.STL_Color,c.Year,c.MonthId
                FROM t_servicetype a
                INNER JOIN t_formulation b ON a.ServiceTypeId = b.ServiceTypeId
                Inner JOIN t_cnm_patientoverview c 	
					ON (c.FormulationId = b.FormulationId 
						and STR_TO_DATE(concat(year,'/',monthid,'/02'), '%Y/%m/%d') 
						between '" . $StartYearMonth . "' and '" . $EndYearMonth . "'
                		AND (c.CountryId = " . $countryId . " OR " . $countryId . " = 0)
						AND (c.ItemGroupId = " . $itemGroupId . " OR " . $itemGroupId . " = 0))  		                       
                GROUP BY a.ServiceTypeId, $serviceTypeName, a.STL_Color
				, c.Year, c.MonthId
				HAVING TotalPatient > 0
		        ORDER BY a.ServiceTypeId asc,c.Year asc, c.MonthId asc;";
    //echo $sQuery;

    $rResult = safe_query($sQuery);

    $tmpServiceTypeId = -1;
    $count = 1;

    while ($row = mysql_fetch_assoc($rResult)) {

        if (!is_null($row['TotalPatient']))
            settype($row['TotalPatient'], "integer");

        if ($tmpServiceTypeId != $row['ServiceTypeId']) {

            if ($count > 1) {
                $output['Series'][] = $output2;
                unset($array[$output2]);
                unset($output2['data']);
            }
            $count++;
            /////
            $output2['name'] = $row['ServiceTypeName'];
            $count = 0;
            while ($count < count($month_list)) {
                $output2['data'][] = null;
                $count++;
            }

            $dataMonthYear = $monthQuarterList[$row['MonthId']] . ' ' . $row['Year'];  //getYearMonth($row['MonthId'],$row['Year']);
            $count = 0;
            while ($count < count($month_list)) {
                if ($month_list[$count] == $dataMonthYear) {
                    $output2['data'][$count] = $row['TotalPatient'];
                }
                $count++;
            }

            $tmpServiceTypeId = $row['ServiceTypeId'];
            $output['Colors'][] = $row['STL_Color'];
            ///////	
        } else {
            $dataMonthYear = $monthQuarterList[$row['MonthId']] . ' ' . $row['Year'];  //getyearmonth($row['MonthId'],$row['Year']);
            $count = 0;
            while ($count < count($month_list)) {
                if ($month_list[$count] == $dataMonthYear) {
                    $output2['data'][$count] = $row['TotalPatient'];
                }
                $count++;
            }
            $tmpServiceTypeId = $row['ServiceTypeId'];
        }
    }

    $output['Series'][] = $output2;
    $output['range'][] = 'From ' . date('M Y', strtotime($StartYearMonth)) . ' to ' . date('M Y', strtotime($EndYearMonth)); // 'rubel';//'from '.end($month_name).' to '.reset($month_name);

    echo json_encode($output);

// echo '{"Categories":["Aug 2013","Sep 2013","Oct 2013","Nov 2013","Dec 2013","Jan 2014","Feb 2014"
// ,"Mar 2014","Apr 2014","May 2014","Jun 2014","Jul 2014"],
// "Series":[{"name":"ART","data":[null,null,null,null,null,26395,28161,11520,null,null,null,null],"range":""},
// {"name":"RTK","range":"","data":[null,null,null,null,null,null,null,609,null,null,null,null]},
// {"name":"PMTCT","range":"","data":[null,null,null,null,null,null,null,200,null,null,null,null]}],
// "Colors":["#FFC545","#9AD268","#50ABED"],"range":[""]}';
}

function getYearMonth($monthId, $yearId) {
    $my = date("M", mktime(0, 0, 0, $monthId, 1, 0));
    $my = $my . " " . $yearId;
    return $my;
}

function getPatientTrendTimeSeriesTable() {
    $lan = $_REQUEST['lan'];
    $StartMonthId = isset($_POST['StartMonthId']) ? $_POST['StartMonthId'] : '';
    $StartYearId = isset($_POST['StartYearId']) ? $_POST['StartYearId'] : '';
    $EndMonthId = isset($_POST['EndMonthId']) ? $_POST['EndMonthId'] : '';
    $EndYearId = isset($_POST['EndYearId']) ? $_POST['EndYearId'] : '';


    $countryId = $_POST['Country'];
    $itemGroupId = $_POST['ItemGroupId'];
    $frequencyId = 1; // $_POST['FrequencyId'];

    if ($lan == 'en-GB') {
        $serviceTypeName = 'ServiceTypeName';
    } else {
        $serviceTypeName = 'ServiceTypeNameFrench';
    }

    if ($_POST['MonthNumber'] != 0) {
        $months = $_POST['MonthNumber'];
        $monthIndex = date("m");
        $yearIndex = date("Y");
        settype($yearIndex, "integer");
        if ($monthIndex == 1) {
            $monthIndex = 12;
            $yearIndex = $yearIndex - 1;
        } else {
            $monthIndex = $monthIndex - 1;
        }
        $months = $months - 1;

        $d = cal_days_in_month(CAL_GREGORIAN, $monthIndex, $yearIndex);
        $EndYearMonth = $yearIndex . "-" . str_pad($monthIndex, 2, "0", STR_PAD_LEFT) . "-" . $d;
        $EndYearMonth = date('Y-m-d', strtotime($EndYearMonth));

        $StartYearMonth = $yearIndex . "-" . str_pad($monthIndex, 2, "0", STR_PAD_LEFT) . "-" . "01";
        $StartYearMonth = date('Y-m-d', strtotime($StartYearMonth));
        $StartYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($StartYearMonth)) . "-" . $months . " month"));
    } else {
        $startDate = $StartYearId . "-" . $StartMonthId . "-" . "01";
        $StartYearMonth = date('Y-m-d', strtotime($startDate));

        $d = cal_days_in_month(CAL_GREGORIAN, $EndMonthId, $EndYearId);
        $endDate = $EndYearId . "-" . $EndMonthId . "-" . $d;
        $EndYearMonth = date('Y-m-d', strtotime($endDate));
    }



    $monthListShort = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
    $quarterList = array(3 => 'Jan-Mar', 6 => 'Apr-Jun', 9 => 'Jul-Sep', 12 => 'Oct-Dec');



    $output = array('aaData' => array());
    $aData = array();
    $output2 = array();

    if ($frequencyId == 1)
        $monthQuarterList = $monthListShort;
    else
        $monthQuarterList = $quarterList;

    $month_list = array();
    $startDate = strtotime($StartYearMonth);
    $endDate = strtotime($EndYearMonth);
    $index = 0;
    // while ($endDate >= $startDate) {	
    // $month_list[$index] = date('M Y',$startDate);
    // $index++;
    // $startDate = strtotime( date('Y/m/d',$startDate).' 1 month');
    // }
    while ($endDate >= $startDate) {
        if ($frequencyId == 1) {
            $monthid = date('m', $startDate);
            settype($monthid, "integer");
            $ym = $monthListShort[$monthid] . ' ' . date('Y', $startDate);
            $month_list[$index] = $ym;
            $output['Categories'][] = $ym;
            $index++;
        } else {
            $monthid = date('m', $startDate);
            settype($monthid, "integer");
            if ($monthid == 3 || $monthid == 6 || $monthid == 9 || $monthid == 12) {
                $ym = $quarterList[$monthid] . ' ' . date('Y', $startDate);
                $month_list[$index] = $ym;
                $output['Categories'][] = $ym;
                $index++;
            }
        }

        $startDate = strtotime(date('Y/m/d', $startDate) . ' 1 month');
    }
    // //////////////////

    $sQuery = "SELECT a.ServiceTypeId, IFNULL(SUM(c.TotalPatient),0) TotalPatient
			, $serviceTypeName ServiceTypeName, a.STL_Color,c.Year,c.MonthId
                FROM t_servicetype a
                INNER JOIN t_formulation b ON a.ServiceTypeId = b.ServiceTypeId
                Inner JOIN t_cnm_patientoverview c 	
					ON (c.FormulationId = b.FormulationId 
						and STR_TO_DATE(concat(year,'/',monthid,'/02'), '%Y/%m/%d') 
						between '" . $StartYearMonth . "' and '" . $EndYearMonth . "'
                		AND (c.CountryId = " . $countryId . " OR " . $countryId . " = 0)
						AND (c.ItemGroupId = " . $itemGroupId . " OR " . $itemGroupId . " = 0))  		                       
                GROUP BY a.ServiceTypeId, $serviceTypeName, a.STL_Color
				, c.Year, c.MonthId
				HAVING TotalPatient > 0
		        ORDER BY a.ServiceTypeId asc,c.Year asc, c.MonthId asc;";
    //echo $sQuery;
    $rResult = safe_query($sQuery);
    $total = mysql_num_rows($rResult);
    $tmpServiceTypeId = -1;
    $countServiceType = 1;
    $count = 1;
    $preServiceTypeName = '';

    if ($total == 0)
        return;
    //echo 'Rubel';
    while ($row = mysql_fetch_assoc($rResult)) {

        if (!is_null($row['TotalPatient']))
            settype($row['TotalPatient'], "integer");

        if ($tmpServiceTypeId != $row['ServiceTypeId']) {

            if ($count > 1) {
                array_unshift($output2, $countServiceType, $preServiceTypeName);

                $aData[] = $output2;
                unset($output2);
                $countServiceType++;
            }
            $count++;

            $preServiceTypeName = $row['ServiceTypeName'];
            $count = 0;
            while ($count < count($month_list)) {
                $output2[] = null;
                $count++;
            }

            $dataMonthYear = $monthQuarterList[$row['MonthId']] . ' ' . $row['Year'];
            $count = 0;
            while ($count < count($month_list)) {
                if ($month_list[$count] == $dataMonthYear) {
                    $output2[$count] = $row['TotalPatient'];
                }
                $count++;
            }
            $tmpServiceTypeId = $row['ServiceTypeId'];
        } else {
            $dataMonthYear = $monthQuarterList[$row['MonthId']] . ' ' . $row['Year'];
            $count = 0;
            while ($count < count($month_list)) {
                if ($month_list[$count] == $dataMonthYear) {
                    $output2[$count] = $row['TotalPatient'];
                }
                $count++;
            }
            $tmpServiceTypeId = $row['ServiceTypeId'];
        }
    }

    array_unshift($output2, $countServiceType, $preServiceTypeName);
    $aData[] = $output2;


    if ($lan == 'en-GB') {
        $TypeLang = 'Patient Type';
    } else {
        $TypeLang = 'Type de Patient';
    }
    
    $sEcho = isset($_REQUEST['sEcho'])? $_REQUEST['sEcho'] : '';

    $str = ',"COLUMNS":[{"sTitle": "SL", "sWidth":"5%"}, {"sTitle": "' . $TypeLang . '", "sClass" : "' . 'PatientType' . '"}, ';
    $f = 0;
    foreach ($month_list as $mon) {
        if ($f++)
            $str.=', ';
        $str.= '{"sTitle": "' . $mon . '", "sClass" : "MonthName"}';
    }
    $str.= ']}';


    echo '{"sEcho": ' . intval($sEcho) . ', "iTotalRecords":"10","iTotalDisplayRecords": "10", "aaData":' . json_encode($aData) . '';
    echo $str;
}
?>











