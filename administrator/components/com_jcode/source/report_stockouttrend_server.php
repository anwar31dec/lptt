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

switch ($task) {
    case 'getStockoutTrendChart' :
        getStockoutTrendChart();
        break;
    case 'getStockoutTrendTable' :
        getStockoutTrendTable();
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

function getStockoutTrendChart() {
    $lan = $_REQUEST['lan'];
    $months = $_POST['MonthNumber'];
    $CountryId = $_POST['Country'];
    $ItemGroupId = $_POST['ItemGroup'];
    $OwnerTypeId = $_POST['OwnerTypeId'];

    $StartMonthId = isset($_POST['StartMonthId']) ? $_POST['StartMonthId'] : '';
    $StartYearId = isset($_POST['StartYearId']) ? $_POST['StartYearId'] : '';
    $EndMonthId = isset($_POST['EndMonthId']) ? $_POST['EndMonthId'] : '';
    $EndYearId = isset($_POST['EndYearId']) ? $_POST['EndYearId'] : '';

    if ($lan == 'en-GB') {
        $mosTypeName = 'MosTypeName';
    } else {
        $mosTypeName = 'MosTypeNameFrench';
    }
	
    if ($_POST['MonthNumber'] != 0) {
        $months = $_POST['MonthNumber'];
        $monthIndex = date("n");
        $yearIndex = date("Y");
        if ($monthIndex == 1) {
            $monthIndex = 12;
            $yearIndex = $yearIndex - 1;
        } else {
            $monthIndex = $monthIndex - 1;
        }
    } else {
        $startDate = $StartYearId . "-" . $StartMonthId . "-" . "01";
        $endDate = $EndYearId . "-" . $EndMonthId . "-" . "10";
        $months = getMonthsBtnTwoDate($startDate, $endDate) + 1;
        $monthIndex = $EndMonthId;
        $yearIndex = $EndYearId;
    }
    settype($yearIndex, "integer");

    $month_name = array();
    $Tdetails = array();
    $sumRiskCount = array();
    $sumTR = 0;

    for ($i = 1; $i <= $months; $i++) {
        // $ItemGroupId = $_POST['ItemGroup'];
        if ($ItemGroupId > 0) {
            $sql = " SELECT v.MosTypeId, $mosTypeName MosTypeName, ColorCode, IFNULL(RiskCount,0) RiskCount FROM
        		    (SELECT p.MosTypeId, COUNT(*) RiskCount FROM (
                     SELECT a.ItemNo, IFNULL(a.MOS,0) MOS,(SELECT MosTypeId FROM t_mostype x WHERE  IFNULL(a.MOS,0) >= x.MinMos AND IFNULL(a.MOS,0) < x.MaxMos) MosTypeId
				     FROM t_cnm_stockstatus a
                                      INNER JOIN t_cnm_masterstockstatus m ON a.CNMStockId=m.CNMStockId
                     INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo  AND b.ItemGroupId = " . $ItemGroupId . "
				     WHERE   m.MonthId = " . $monthIndex . "  AND m.OwnerTypeId = " . $OwnerTypeId . "  AND m.Year = " . $yearIndex . " 
                     AND m.OwnerTypeId = " . $OwnerTypeId . " 
                     AND (m.CountryId = " . $CountryId . " OR " . $CountryId . " = 0)) p 
				     GROUP BY p.MosTypeId) u
				     RIGHT JOIN t_mostype v ON u.MosTypeId = v.MosTypeId
				     GROUP BY v.MosTypeId";
        } else {
            $sql = " SELECT v.MosTypeId, $mosTypeName MosTypeName, ColorCode, IFNULL(RiskCount,0) RiskCount FROM
        		    (SELECT p.MosTypeId, COUNT(*) RiskCount FROM (
                     SELECT a.ItemNo, IFNULL(a.MOS,0) MOS,(SELECT MosTypeId FROM t_mostype x WHERE  IFNULL(a.MOS,0) >= x.MinMos AND IFNULL(a.MOS,0) < x.MaxMos) MosTypeId
				     FROM t_cnm_stockstatus a
                                      INNER JOIN t_cnm_masterstockstatus m ON a.CNMStockId=m.CNMStockId
                     INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo  AND b.bCommonBasket = 1
				     WHERE  m.MonthId = " . $monthIndex . "  AND m.OwnerTypeId = " . $OwnerTypeId . "  AND m.Year = " . $yearIndex . " 
                     AND m.OwnerTypeId = " . $OwnerTypeId . " 
                     AND (m.CountryId = " . $CountryId . " OR " . $CountryId . " = 0)) p 
				     GROUP BY p.MosTypeId) u
				     RIGHT JOIN t_mostype v ON u.MosTypeId = v.MosTypeId
				     GROUP BY v.MosTypeId";
        }
       
        $result = mysql_query($sql);
        $total = mysql_num_rows($result);
        $Pdetails = array();

        if ($total > 0) {
            while ($aRow = mysql_fetch_array($result)) {
                $Pdetails['MosTypeId'] = $aRow['MosTypeId'];
                $Pdetails['MonthIndex'] = $monthIndex;
                $Pdetails['MosTypeName'] = $aRow['MosTypeName'];
                $Pdetails['ColorCode'] = $aRow['ColorCode'];
                $Pdetails['RiskCount'] = $aRow['RiskCount'];
                array_push($Tdetails, $Pdetails);
            }
            $mn = date("M", mktime(0, 0, 0, $monthIndex, 1, 0));
            $mn = $mn . " " . $yearIndex;
            array_push($month_name, $mn);
        }
        $monthIndex--;
        if ($monthIndex == 0) {
            $monthIndex = 12;
            $yearIndex = $yearIndex - 1;
        }
    }
    $veryHighRisk = array();
    $highRisk = array();
    $mediumRisk = array();
    $lowRisk = array();
    $noRisk = array();
    $color = array();
    $areaName = array();

    $RTdetails = array_reverse($Tdetails);

    foreach ($RTdetails as $key => $value) {
        $MosTypeId = $value['MosTypeId'];
        $MonthIndex = $value['MonthIndex'];
        $ColorCode = $value['ColorCode'];
        $MosTypeName = $value['MosTypeName'];
        $RiskCount = $value['RiskCount'];
        settype($RiskCount, "integer");

        if ($MosTypeId == 1) {
            array_push($veryHighRisk, $RiskCount);
            array_push($color, $ColorCode);
            array_push($areaName, $MosTypeName);
        } else if ($MosTypeId == 2) {
            array_push($highRisk, $RiskCount);
            array_push($color, $ColorCode);
            array_push($areaName, $MosTypeName);
        } else if ($MosTypeId == 3) {
            array_push($mediumRisk, $RiskCount);
            array_push($color, $ColorCode);
            array_push($areaName, $MosTypeName);
        } else if ($MosTypeId == 4) {
            array_push($lowRisk, $RiskCount);
            array_push($color, $ColorCode);
            array_push($areaName, $MosTypeName);
        } else if ($MosTypeId == 5) {
            array_push($noRisk, $RiskCount);
            array_push($color, $ColorCode);
            array_push($areaName, $MosTypeName);
        }
    }

    $data = array();
    $data['categories'] = array_reverse($month_name);
    $data['VeryHighRisk'] = $veryHighRisk;
    $data['HighRisk'] = $highRisk;
    $data['MediumRisk'] = $mediumRisk;
    $data['LowRisk'] = $lowRisk;
    $data['NoRisk'] = $noRisk;
    $data['barcolor'] = array_reverse(array_unique($color));
    $data['areaName'] = array_reverse(array_unique($areaName));
    $data['name'] = 'from ' . end($month_name) . ' to ' . reset($month_name);

    echo json_encode($data);
}

function getStockoutTrendTable() {
    $lan = $_REQUEST['lan'];
    $months = $_POST['MonthNumber'];
    $CountryId = $_POST['Country'];
    $ItemGroupId = $_POST['ItemGroup'];  //echo $ItemGroupId;
    $OwnerTypeId = $_POST['OwnerTypeId'];



    $StartMonthId = isset($_POST['StartMonthId']) ? $_POST['StartMonthId'] : '';
    $StartYearId = isset($_POST['StartYearId']) ? $_POST['StartYearId'] : '';
    $EndMonthId = isset($_POST['EndMonthId']) ? $_POST['EndMonthId'] : '';
    $EndYearId = isset($_POST['EndYearId']) ? $_POST['EndYearId'] : '';

    if ($lan == 'en-GB') {
        $mosTypeName = 'MosTypeName';
        $lblMOSTypeName = 'MOS Type Name';
    } else {
        $mosTypeName = 'MosTypeNameFrench';
        $lblMOSTypeName = 'Type MSD Nom';
    }
	
    if ($_POST['MonthNumber'] != 0) {
        $months = $_POST['MonthNumber'];
        $monthIndex = date("n");
        $yearIndex = date("Y");
        if ($monthIndex == 1) {
            $monthIndex = 12;
            $yearIndex = $yearIndex - 1;
        } else {
            $monthIndex = $monthIndex - 1;
        }
    } else {
        $startDate = $StartYearId . "-" . $StartMonthId . "-" . "01";
        $endDate = $EndYearId . "-" . $EndMonthId . "-" . "10";
        $months = getMonthsBtnTwoDate($startDate, $endDate) + 1;
        $monthIndex = $EndMonthId;
        $yearIndex = $EndYearId;
    }
    settype($yearIndex, "integer");

    $month_name = array();
    $Tdetails = array();
    $sumRiskCount = array();
    $sumTR = 0;

    for ($i = 1; $i <= $months; $i++) {

        if ($ItemGroupId > 0) {
            $sql = " SELECT v.MosTypeId, $mosTypeName MosTypeName, ColorCode, IFNULL(RiskCount,0) RiskCount FROM
        		    (SELECT p.MosTypeId, COUNT(*) RiskCount FROM (
                     SELECT a.ItemNo, IFNULL(a.MOS,0) MOS,(SELECT MosTypeId FROM t_mostype x WHERE  IFNULL(a.MOS,0) >= x.MinMos AND IFNULL(a.MOS,0) < x.MaxMos) MosTypeId
				     FROM t_cnm_stockstatus a
                                       INNER JOIN t_cnm_masterstockstatus m ON a.CNMStockId=m.CNMStockId
                     INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo  
					 AND b.ItemGroupId = " . $ItemGroupId . "
				     WHERE m.MonthId = " . $monthIndex . " 
					 AND m.Year = " . $yearIndex . "
					 AND m.OwnerTypeId = " . $OwnerTypeId . " 
                     AND (m.CountryId = " . $CountryId . " OR " . $CountryId . " = 0)) p 
				     GROUP BY p.MosTypeId) u
				     RIGHT JOIN t_mostype v ON u.MosTypeId = v.MosTypeId
				     GROUP BY v.MosTypeId";
        } else {
            $sql = " SELECT v.MosTypeId, $mosTypeName MosTypeName, ColorCode, IFNULL(RiskCount,0) RiskCount FROM
        		    (SELECT p.MosTypeId, COUNT(*) RiskCount FROM (
                     SELECT a.ItemNo, IFNULL(a.MOS,0) MOS,(SELECT MosTypeId FROM t_mostype x WHERE  IFNULL(a.MOS,0) >= x.MinMos AND IFNULL(a.MOS,0) < x.MaxMos) MosTypeId
				     FROM t_cnm_stockstatus a
                                       INNER JOIN t_cnm_masterstockstatus m ON a.CNMStockId=m.CNMStockId
                     INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo  
					 AND b.bCommonBasket = 1
				     WHERE  m.MonthId = " . $monthIndex . " 
					 AND m.Year = " . $yearIndex . "
					 AND m.OwnerTypeId = " . $OwnerTypeId . " 					
                     AND (m.CountryId = " . $CountryId . " OR " . $CountryId . " = 0)) p 
				     GROUP BY p.MosTypeId) u
				     RIGHT JOIN t_mostype v ON u.MosTypeId = v.MosTypeId
				     GROUP BY v.MosTypeId;";
        }   
		
        $result = mysql_query($sql);
        $total = mysql_num_rows($result);
        $Pdetails = array();
        if ($total > 0) {
            while ($aRow = mysql_fetch_array($result)) {
                $Pdetails['MosTypeId'] = $aRow['MosTypeId'];
                $Pdetails['MonthIndex'] = $monthIndex;
                $Pdetails['MosTypeName'] = $aRow['MosTypeName'];
                $Pdetails['RiskCount'] = $aRow['RiskCount'];
                array_push($Tdetails, $Pdetails);
            }
            $mn = date("M", mktime(0, 0, 0, $monthIndex, 1, 0));
            $mn = $mn . " " . $yearIndex;
            array_push($month_name, $mn);
        }

        $monthIndex--;
        if ($monthIndex == 0) {
            $monthIndex = 12;
            $yearIndex = $yearIndex - 1;
        }
    }
    $veryHighRisk = array();
    $highRisk = array();
    $mediumRisk = array();
    $lowRisk = array();
    $noRisk = array();
    $areaName = array();

    $rmonth_name = array_reverse($month_name);
    $RTdetails = array_reverse($Tdetails);

    foreach ($RTdetails as $key => $value) {
        $MosTypeId = $value['MosTypeId'];
        $MonthIndex = $value['MonthIndex'];
        $MosTypeName = $value['MosTypeName'];
        $RiskCount = $value['RiskCount'];

        if ($MosTypeId == 1) {
            array_push($veryHighRisk, $RiskCount);
            array_push($areaName, $MosTypeName);
        } else if ($MosTypeId == 2) {
            array_push($highRisk, $RiskCount);
            array_push($areaName, $MosTypeName);
        } else if ($MosTypeId == 3) {
            array_push($mediumRisk, $RiskCount);
            array_push($areaName, $MosTypeName);
        } else if ($MosTypeId == 4) {
            array_push($lowRisk, $RiskCount);
            array_push($areaName, $MosTypeName);
        } else if ($MosTypeId == 5) {
            array_push($noRisk, $RiskCount);
            array_push($areaName, $MosTypeName);
        }
    }

    $vhr = array();
    $hr = array();
    $mr = array();
    $lr = array();
    $nr = array();

    for ($i = 0; $i < count($veryHighRisk); $i++) {
        $sumOfRiskCount = $veryHighRisk[$i] + $highRisk[$i] + $mediumRisk[$i] + $lowRisk[$i] + $noRisk[$i];
        if ($sumOfRiskCount == 0)
            $sumOfRiskCount = 1;
        $newPercentVHR = number_format($veryHighRisk[$i] * 100 / $sumOfRiskCount, 1);
        $newPercentHR = number_format($highRisk[$i] * 100 / $sumOfRiskCount, 1);
        $newPercentMR = number_format($mediumRisk[$i] * 100 / $sumOfRiskCount, 1);
        $newPercentLR = number_format($lowRisk[$i] * 100 / $sumOfRiskCount, 1);
        $newPercentNR = number_format($noRisk[$i] * 100 / $sumOfRiskCount, 1);

        array_push($vhr, $newPercentVHR . "%");
        array_push($hr, $newPercentHR . "%");
        array_push($mr, $newPercentMR . "%");
        array_push($lr, $newPercentLR . "%");
        array_push($nr, $newPercentNR . "%");
    }


    $unique = array_reverse(array_unique($areaName));
    array_unshift($vhr, "1", $unique[0]);
    array_unshift($hr, "2", $unique[1]);
    array_unshift($mr, "3", $unique[2]);
    array_unshift($lr, "4", $unique[3]);
    array_unshift($nr, "5", $unique[4]);



    $str = ',"COLUMNS":[{"sTitle": "SL", "sWidth":"5%"}, {"sTitle": "' . $lblMOSTypeName . '", "sClass" : "PatientType"}, ';
    $f = 0;
   $sEcho = isset($_POST['sEcho'])? $_POST['sEcho'] : '';
    foreach ($rmonth_name as $mon) {
        if ($f++)
            $str.=', ';
        $str.= '{"sTitle": "' . $mon . '", "sClass" : "MonthName"}';
    }
    $str.= ']}';

    echo '{"sEcho": ' . intval($sEcho) . ', "iTotalRecords":"10","iTotalDisplayRecords": "10", "aaData":[';
    echo '' . json_encode($vhr) . ', ' . json_encode($hr) . ', ' . json_encode($mr) . ', ' . json_encode($lr) . ', ' . json_encode($nr) . ']';
    echo $str;
}
