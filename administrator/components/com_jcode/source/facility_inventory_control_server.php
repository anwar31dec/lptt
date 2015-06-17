<?php
include_once ('database_conn.php');
include_once ("function_lib.php");

$task = '';
if (isset($_REQUEST['operation'])) {
	$task = $_REQUEST['operation'];
}
switch($task) {
	case "getMosType" :
		getMosType();
		break;
	case "getMosTypeProduct" :
		getMosTypeProduct();
		break;
	case "getFacilityByCountryId" :
		getFacilityByCountryId();
		break;
	case "getLegendMos" :
		getLegendMos();
		break;
	default :
		echo "{failure:true}";
		break;
}

function getMosType() {
	$lan = $_REQUEST['lan'];
	$mosTypeId = $_REQUEST['MosTypeId'];
	$countryId = $_REQUEST['CountryId'];
	$fLevelId = $_REQUEST['FLevelId'];

	if($lan == 'en-GB'){
            $mosTypeName = 'MosTypeName';
			$closingBalance = 'Closing Balance';
			$mos = 'MOS';
			$productName = 'Product Name';
        }else{
            $mosTypeName = 'MosTypeNameFrench';
			$closingBalance = 'Solde de clôture';
			$mos = 'MSD';
			$productName = 'Nom du produit';
        }     
		
		
	$sQuery = "SELECT MosTypeId, $mosTypeName MosTypeName, MinMos, MaxMos, ColorCode 
			FROM
			    t_mostype_facility
			WHERE CountryId = $countryId AND FLevelId = $fLevelId  AND (MosTypeId = $mosTypeId OR $mosTypeId = 0)
			ORDER BY MosTypeId;";

	$rResult = safe_query($sQuery);

	$output = array();

	while ($row = mysql_fetch_array($rResult)) {
		$tmpRow['sTitle'] = $row['MosTypeName'];
		$tmpRow['sClass'] = 'center-aln';
		$tmpRow['MosTypeId'] = $row['MosTypeId'];
		$tmpRow['MosTypeName'] = $row['MosTypeName'];
		$tmpRow['MinMos'] = $row['MinMos'];
		$tmpRow['MaxMos'] = $row['MaxMos'];
		$tmpRow['ColorCode'] = $row['ColorCode'];
		$output[] = $tmpRow;
	}
	array_unshift($output, array('sTitle' => $productName, 'sWidth' => '30%'),array('sTitle' => $closingBalance, 'sClass' => 'right-aln', 'sWidth' => '7%'),
	array('sTitle' => 'AMC', 'sClass' => 'right-aln', 'sWidth' => '7%'), array('sTitle' => $mos, 'sClass' => 'right-aln', 'sWidth' => '7%'));
	echo json_encode($output);
}

function getMosTypeProduct() {
	$lan = $_REQUEST['lan'];
	$mosTypeId = $_REQUEST['MosTypeId'];
	$countryId = $_REQUEST['CountryId'];
	$fLevelId = $_REQUEST['FLevelId'];

	if($lan == 'en-GB'){
            $mosTypeName = 'MosTypeName';
        }else{
            $mosTypeName = 'MosTypeNameFrench';
        }     
		
	$sQuery1 = "SELECT
			    MosTypeId
			    , $mosTypeName MosTypeName
			    , ColorCode
			FROM
			    t_mostype_facility
			WHERE CountryId = $countryId AND FLevelId = $fLevelId  AND (MosTypeId = $mosTypeId OR $mosTypeId = 0)
			ORDER BY MosTypeId;";

	$rResult1 = safe_query($sQuery1);

	$output1 = array();

	while ($row1 = mysql_fetch_array($rResult1)) {
		$output1[] = $row1;
	}
    $regionId = $_REQUEST['RegionId'];
    $districtId = $_REQUEST['DistrictId'];
    $ownerTypeId = $_REQUEST['OwnerTypeId'];
     
	 $condition="";
	 if($regionId)
	 { 
		$condition.= " and  g.RegionId = $regionId ";
	 }
	 
	 if($districtId)
	 {
		$condition.= " and g.DistrictId = $districtId ";
	 }
	/* if($ownerTypeId)
	 {
		$condition.= " and  g.OwnerTypeId = $ownerTypeId ";
	 }*/
     if($ownerTypeId == 1 || $ownerTypeId == 2){
        $sQuery = "SELECT p.MosTypeId, ItemName, MOS ,ClStock,AMC FROM (SELECT
				    a.ItemNo, b.ItemName, a.MOS ,a.ClStock,a.AMC
				,(SELECT MosTypeId FROM t_mostype_facility x WHERE CountryId = $countryId 
                AND FLevelId = $fLevelId  AND (MosTypeId = $mosTypeId OR $mosTypeId = 0) 
                AND IFNULL(a.MOS,0) >= x.MinMos AND IFNULL(a.MOS,0) < x.MaxMos ) MosTypeId
				FROM t_cfm_stockstatus a, t_itemlist b,  t_cfm_masterstockstatus c, t_facility g
				WHERE a.itemno = b.itemno AND a.MonthId = " . $_REQUEST['MonthId'] . " 
                AND a.Year = '" . $_REQUEST['YearId'] . "' AND a.CountryId = " . $_REQUEST['CountryId'] . " 
                AND a.FacilityId = " . $_REQUEST['FacilityId'] . " AND a.ItemGroupId = " . $_REQUEST['ItemGroupId'] . "
                AND a.CFMStockId = c.CFMStockId" . " AND c.StatusId = 5 " . "
                AND a.FacilityId=g.FacilityId 
                AND g.OwnerTypeId = $ownerTypeId 
                AND  (g.RegionId = $regionId OR $regionId = 0)
                AND (g.DistrictId = $districtId OR $districtId = 0)
                 ) p
                
				WHERE (p.MosTypeId = $mosTypeId OR $mosTypeId = 0) 
				ORDER BY ItemName"; //AND g.OwnerTypeId = $ownerTypeId
     }else{
        $sQuery = "SELECT p.MosTypeId, ItemName, MOS ,ClStock,AMC FROM (SELECT
				    a.ItemNo, b.ItemName, a.MOS ,a.ClStock,a.AMC
				,(SELECT MosTypeId FROM t_mostype_facility x WHERE CountryId = $countryId 
                AND FLevelId = $fLevelId  AND (MosTypeId = $mosTypeId OR $mosTypeId = 0) 
                AND IFNULL(a.MOS,0) >= x.MinMos AND IFNULL(a.MOS,0) < x.MaxMos ) MosTypeId
				FROM t_cfm_stockstatus a, t_itemlist b,  t_cfm_masterstockstatus c, t_facility g
				WHERE a.itemno = b.itemno AND a.MonthId = " . $_REQUEST['MonthId'] . " 
                AND a.Year = '" . $_REQUEST['YearId'] . "' AND a.CountryId = " . $_REQUEST['CountryId'] . " 
                AND a.FacilityId = " . $_REQUEST['FacilityId'] . " AND a.ItemGroupId = " . $_REQUEST['ItemGroupId'] . "
                AND a.CFMStockId = c.CFMStockId" . " AND c.StatusId = 5 " . "
                AND a.FacilityId=g.FacilityId
                AND g.AgentType = $ownerTypeId 
                AND  (g.RegionId = $regionId OR $regionId = 0)
                AND (g.DistrictId = $districtId OR $districtId = 0) ) p
                
				WHERE (p.MosTypeId = $mosTypeId OR $mosTypeId = 0) 
				ORDER BY ItemName";
     } 
	 //echo $sQuery; 
	 

	$rResult = safe_query($sQuery);

	$aData = array();

	while ($row = mysql_fetch_array($rResult)) {
		$mos = '';
		$ClStock = '';
		$AMC = '';
		$tmpRow = array();
		foreach ($output1 as $rowMosType) {
			if ($rowMosType['MosTypeId'] == $row['MosTypeId'] && $row['MOS']!='') {
				//$tmpRow[] = '<span class="glyphicon glyphicon-ok-circle" style="color:' . $rowMosType['ColorCode'] . ';font-size:2em;"></span>';
				$tmpRow[] = '<i class="fa fa-check-circle fa-lg" style="color:' . $rowMosType['ColorCode'] . ';font-size:2.5em;"></i>';
				$mos = number_format($row['MOS'],1);				
			} else
				$tmpRow[] = '';
		}
		
		if($row['ClStock'] != '')
			$ClStock = number_format($row['ClStock']);
		if($row['AMC'] != '')
			$AMC = number_format($row['AMC']);
		
		array_unshift($tmpRow, $row['ItemName'],$ClStock,$AMC, $mos); 
		$aData[] = $tmpRow;
	}

	echo '{"sEcho": ' . intval($_REQUEST['sEcho']) . ', "iTotalRecords":"10","iTotalDisplayRecords": "10", "aaData":' . json_encode($aData) . '}';

}

function getFacilityByCountryId() {

	$countryId = $_REQUEST['CountryId'];
	$regionId = $_REQUEST['RegionId'];
	$districtId = $_REQUEST['DistrictId'];

	$sQuery = "SELECT
			    FacilityId
			    , FacilityName
			    , FLevelId
			FROM
			    t_facility
			WHERE (CountryId = $countryId) and (RegionId=$regionId OR $regionId=0) and (DistrictId=$districtId OR $districtId=0)
			order by FacilityName;			
			";
//echo $sQuery;

	$rResult = safe_query($sQuery);

	$output = array();

	while ($obj = mysql_fetch_object($rResult)) {
		$output[$obj -> FacilityId] = $obj;
	}

	echo json_encode($output);
}

// function getLegendMos() {
//
// $countryId = $_REQUEST['CountryId'];
// $fLevelId = $_REQUEST['FLevelId'];
//
// $sql = "SELECT MosTypeId, MosTypeName, MinMos, MaxMos, ColorCode
// FROM t_mostype_facility
// WHERE CountryId = $countryId AND FLevelId = $fLevelId
// ORDER BY MosTypeId";
//
// $result = mysql_query($sql);
// $total = mysql_num_rows($result);
//
// $x = "<table><tr>";
// $z = "</tr><tr>";
// $y = "</tr><tr>";
// if ($total > 0) {
// while ($row = mysql_fetch_object($result)) {
// $x .= "<td><div style='background-color:" . $row -> ColorCode . ";'>&nbsp;</div></td>";
// $z .= "<td>" . $row -> MosTypeName . "</td>";
// $y .= "<td>MOS: " . $row -> MinMos . " - " . $row -> MaxMos . "</td>";
// }
// }
// $x = $x . $z . $y . "</tr></table>";
//
// $x = str_replace("\n", '', $x);
// $x = str_replace("\r", '', $x);
// echo $x;
// }

// function getBtnGroupMosType() {
// $countryId = $_REQUEST['CountryId'];
// $fLevelId = $_REQUEST['FLevelId'];
//
// $sql = "SELECT MosTypeId, MosTypeName, MinMos, MaxMos, ColorCode
// FROM t_mostype_facility
// WHERE CountryId = $countryId AND FLevelId = $fLevelId
// ORDER BY MosTypeId";
//
// $result = mysql_query($sql);
// $total = mysql_num_rows($result);
//
// $x = '<div class="btn-group pull-left">'
// .'<button id="0" class="btn btn-default active" type="button">All</button>';
// if ($total > 0) {
// while ($row = mysql_fetch_object($result)) {
// $x .= '<button id = "' . $row -> MosTypeId . '" class="btn btn-default" type="button">' . $row -> MosTypeName . '</button>';
// }
// }
// $x = $x . "</div>";
// echo $x;
// }

function getLegendMos() {
	$lan = $_REQUEST['lan'];
        $mosTypeId=  isset($_REQUEST['MosTypeId'])?$_REQUEST['MosTypeId']:'';
	$countryId = $_REQUEST['CountryId'];
	$fLevelId = $_REQUEST['FLevelId'];
	
	if($lan == 'en-GB'){
            $mosTypeName = 'MosTypeName';
        }else{
            $mosTypeName = 'MosTypeNameFrench';
        }     
		
	$sQuery = "SELECT MosTypeId, $mosTypeName MosTypeName, MinMos, MaxMos, ColorCode, MosLabel
			FROM
			    t_mostype_facility
			WHERE CountryId = $countryId AND FLevelId = $fLevelId
			ORDER BY MosTypeId;";
	$rResult = safe_query($sQuery);

	$output = array();

	while ($row = mysql_fetch_array($rResult)) {
		$output[] = $row;
	}
	echo json_encode($output);
}
?>