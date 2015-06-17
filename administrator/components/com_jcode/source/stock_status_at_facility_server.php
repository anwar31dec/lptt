<?php
include_once ('database_conn.php');
include_once ("function_lib.php");

$task = '';
if (isset($_REQUEST['operation'])) {
	$task = $_REQUEST['operation'];
}
switch($task) {
	case "getCountryInfoById" :
		getCountryInfoById();
		break;
	case "getStockStatusAtFacility" :
		getStockStatusAtFacility();
		break;
	case "getStockStatusAtFacilityForMap" :
		getStockStatusAtFacilityForMap();
		break;
	case "getCountryProductList" :
		getCountryProductList();
		break;
	case "getFacilityLevel" :
		getFacilityLevel();
		break;
	case "getRegionList" :
		getRegionList();
		break;
    case "getDistrictList" :
		getDistrictList();
		break;
	case "getLegendMos" :
		getLegendMos();
		break;
	case "getLegendMosDetails" :
		getLegendMosDetails();
		break;				
	default :
		echo "{failure:true}";
		break;
}

function getStockStatusAtFacility() {

	$monthId = $_REQUEST['MonthId'];
	$year = $_REQUEST['YearId'];
	$countryId = $_REQUEST['CountryId'];
	$itemGroupId = $_REQUEST['ItemGroupId'];
	$itemNo = $_REQUEST['ItemNo'];
	$regionId = $_REQUEST['RegionId'];
	$fLevelId = $_REQUEST['FLevelId'];
    $districtId = $_REQUEST['DistrictId'];
	$ownerTypeId = $_REQUEST['OwnerTypeId'];
    $mostypeId = $_REQUEST['MosTypeId']; //echo $mostypeId.'ert';
	$aColumns = array('SL', 'FacilityName', 'ClStock', 'AMC', 'MOS', 'FacilityId', 'Latitude', 'Longitude');

	$sLimit = "";
    $sWhere="";
	if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
		$sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
	}

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

	/* Individual column filtering */
	for ($i = 0; $i < count($aColumns); $i++) {

		if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch'] != '') {

			if ($sWhere == "") {
				$sWhere = " AND (";
			} else {
				$sWhere .= " OR ";
			}
			$sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' ";
		}
	}

	if ($sWhere != "")
		$sWhere .= ")";
//t_cfm_stockstatus
	safe_query("SET @rank=0;");

	$serial = "@rank:=@rank+1 AS SL";

	$sQuery = "SELECT SQL_CALC_FOUND_ROWS " . $serial . ",
                  b.FacilityId,
                  b.FacilityName,                 
                  b.ClStock,
                  b.AMC,
                  b.MOS,
                  `Latitude`, `Longitude`, b.MosTypeId
                  FROM (
               
               
                SELECT
                  t_cfm_masterstockstatus.FacilityId,
                  t_facility.FacilityName,
                  `Latitude`, `Longitude`,
                  IFNULL(t_cfm_stockstatus.ClStock,0)    ClStock,
                  IFNULL(t_cfm_stockstatus.AMC,0)       AMC,
                  IFNULL(t_cfm_stockstatus.MOS,0)       MOS
                  ,(SELECT MosTypeId FROM t_mostype_facility x WHERE CountryId = $countryId
                        AND FLevelId = $fLevelId  AND (MosTypeId = $mostypeId OR $mostypeId = 0)
                        AND t_cfm_stockstatus.MOS >= x.MinMos AND t_cfm_stockstatus.MOS < x.MaxMos ) MosTypeId
                FROM t_cfm_stockstatus
                  INNER JOIN t_cfm_masterstockstatus
                    ON (t_cfm_stockstatus.CFMStockId = t_cfm_masterstockstatus.CFMStockId)
                  INNER JOIN t_country_product
                    ON (t_country_product.CountryId = t_cfm_stockstatus.CountryId)
                      AND (t_country_product.ItemNo = t_cfm_stockstatus.ItemNo)
                  INNER JOIN t_facility
                    ON (t_facility.FacilityId = t_cfm_masterstockstatus.FacilityId)
                  INNER JOIN t_region
                    ON t_facility.RegionId = t_region.RegionId
                
                      AND t_region.CountryId = $countryId
                      AND (t_facility.FLevelId = $fLevelId OR $fLevelId=0)
                      AND (t_region.RegionId = $regionId OR $regionId=0)
                      AND (t_facility.DistrictId = $districtId OR $districtId=0)
                      AND (t_facility.OwnerTypeId = $ownerTypeId OR $ownerTypeId=0)
                     
                WHERE (t_cfm_masterstockstatus.StatusId = 5
                       AND t_cfm_masterstockstatus.MonthId = $monthId
                       AND t_cfm_masterstockstatus.Year = '$year'
                       AND t_cfm_masterstockstatus.CountryId = $countryId
                       AND t_country_product.ItemGroupId = $itemGroupId
                       AND t_country_product.ItemNo = $itemNo
                       AND t_cfm_stockstatus.ClStockSourceId IS NOT NULL
                       AND (t_cfm_stockstatus.ClStock <> 0
                             OR t_cfm_stockstatus.AMC <> 0))
                             
                             
                 UNION
               
                SELECT
                  a.FacilityId,
                  a.FacilityName,
                  a.`Latitude`, a.`Longitude`,
                  NULL ClStock,
                  NULL AMC,
                  NULL MOS, NULL MosTypeId
                       
                FROM t_cfm_masterstockstatus
                  INNER JOIN t_facility
                    ON t_cfm_masterstockstatus.FacilityId = t_facility.FacilityId
                  INNER JOIN t_region
                    ON t_facility.RegionId = t_region.RegionId
                
                      AND t_region.CountryId = $countryId
                      AND (t_facility.FLevelId = $fLevelId OR $fLevelId=0)
                      AND (t_region.RegionId = $regionId OR $regionId=0)
                      AND (t_facility.DistrictId = $districtId OR $districtId=0)
                      AND (t_facility.OwnerTypeId = $ownerTypeId OR $ownerTypeId=0)
                  RIGHT JOIN (SELECT
                                p.FacilityId,
                                p.FacilityCode,
                                p.FacilityName,
                                `Latitude`, `Longitude`
                              FROM t_facility p
                                INNER JOIN t_facility_group_map q
                                  ON p.FacilityId = q.FacilityId
                                INNER JOIN t_region r
                                  ON p.RegionId = r.RegionId                       
                              WHERE p.CountryId = $countryId
                                  AND q.ItemGroupId = $itemGroupId
                                  AND (p.FLevelId = $fLevelId OR $fLevelId=0)
                                  AND (r.RegionId = $regionId OR $regionId=0)
                          AND (p.DistrictId = $districtId OR $districtId=0)
                          AND (p.OwnerTypeId = $ownerTypeId OR $ownerTypeId=0)) a
                         
                    ON (t_cfm_masterstockstatus.FacilityId = a.FacilityId
                        AND t_cfm_masterstockstatus.MonthId = $monthId
                        AND t_cfm_masterstockstatus.Year = '$year'
                        AND t_cfm_masterstockstatus.CountryId = $countryId
                        AND t_cfm_masterstockstatus.StatusId = 5)
                WHERE t_cfm_masterstockstatus.FacilityId IS NULL
                ) b
                                    WHERE 1=1 AND b.MosTypeId = $mostypeId OR $mostypeId = 0
									$sWhere
									$sOrder
									$sLimit;"; 
									
//echo $sQuery;

	$rResult = safe_query($sQuery);

	/* Data set length after filtering */
	$sQuery = "SELECT FOUND_ROWS()";
	$rResultFilterTotal = safe_query($sQuery);
	$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];

	$iTotal = mysql_num_rows($rResult);

	$output = array("sEcho" => intval($_GET['sEcho']), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iFilteredTotal, "aaData" => array());
	
	while ($aRow = mysql_fetch_array($rResult)) {
		
		$row = array();
		for ($i = 0; $i < count($aColumns); $i++) {			
			if (is_null($aRow[$aColumns[$i]]))
				$row[] = '';
			else {
				if ($aColumns[$i] == 'ClStock' || $aColumns[$i] == 'AMC')
					$row[] = number_format($aRow[$aColumns[$i]]);
				else if ($aColumns[$i] == 'MOS')
					$row[] = number_format($aRow[$aColumns[$i]], 1);
				else
					$row[] = $aRow[$aColumns[$i]];
			}
		}
		$output['aaData'][] = $row;
	}
	
	echo json_encode($output);
}

function getStockStatusAtFacilityForMap() {

	$monthId = $_REQUEST['MonthId'];
	$year = $_REQUEST['YearId'];
	$countryId = $_REQUEST['CountryId'];
	$itemGroupId = $_REQUEST['ItemGroupId'];
	$itemNo = $_REQUEST['ItemNo'];
	$regionId = $_REQUEST['RegionId'];
	$fLevelId = $_REQUEST['FLevelId'];
    $districtId = $_REQUEST['DistrictId'];
	$ownerTypeId = $_REQUEST['OwnerTypeId'];
    $mostypeId = $_REQUEST['MosTypeId'];
		
	$sQuery = "SELECT 
                  b.FacilityId,
                  b.FacilityName,                 
                  b.ClStock,
                  b.AMC,
                  b.MOS,
                  `Latitude`, `Longitude`, b.MosTypeId
                  FROM (
                SELECT
                  t_cfm_masterstockstatus.FacilityId,
                  t_facility.FacilityName,
                  `Latitude`, `Longitude`,
                  IFNULL(t_cfm_stockstatus.ClStock,0)    ClStock,
                  IFNULL(t_cfm_stockstatus.AMC,0)       AMC,
                  IFNULL(t_cfm_stockstatus.MOS,0)       MOS
                  ,(SELECT MosTypeId FROM t_mostype_facility x WHERE CountryId = $countryId
                        AND FLevelId = $fLevelId  AND (MosTypeId = $mostypeId OR $mostypeId = 0)
                        AND t_cfm_stockstatus.MOS >= x.MinMos AND t_cfm_stockstatus.MOS < x.MaxMos ) MosTypeId
                FROM t_cfm_stockstatus
                  INNER JOIN t_cfm_masterstockstatus
                    ON (t_cfm_stockstatus.CFMStockId = t_cfm_masterstockstatus.CFMStockId)
                  INNER JOIN t_country_product
                    ON (t_country_product.CountryId = t_cfm_stockstatus.CountryId)
                      AND (t_country_product.ItemNo = t_cfm_stockstatus.ItemNo)
                  INNER JOIN t_facility
                    ON (t_facility.FacilityId = t_cfm_masterstockstatus.FacilityId)
                  INNER JOIN t_region
                    ON t_facility.RegionId = t_region.RegionId
                
                      AND t_region.CountryId = $countryId
                      AND (t_facility.FLevelId = $fLevelId OR $fLevelId=0)
                      AND (t_region.RegionId = $regionId OR $regionId=0)
                      AND (t_facility.DistrictId = $districtId OR $districtId=0)
                      AND (t_facility.OwnerTypeId = $ownerTypeId OR $ownerTypeId=0)
                     
                WHERE (t_cfm_masterstockstatus.StatusId = 5
                       AND t_cfm_masterstockstatus.MonthId = $monthId
                       AND t_cfm_masterstockstatus.Year = '$year'
                       AND t_cfm_masterstockstatus.CountryId = $countryId
                       AND t_country_product.ItemGroupId = $itemGroupId
                       AND t_country_product.ItemNo = $itemNo
                       AND t_cfm_stockstatus.ClStockSourceId IS NOT NULL
                       AND (t_cfm_stockstatus.ClStock <> 0
                             OR t_cfm_stockstatus.AMC <> 0))
                 UNION
               
                SELECT
                  a.FacilityId,
                  a.FacilityName,
                  a.`Latitude`, a.`Longitude`,
                  NULL ClStock,
                  NULL AMC,
                  NULL MOS, NULL MosTypeId
                       
                FROM t_cfm_masterstockstatus
                  INNER JOIN t_facility
                    ON t_cfm_masterstockstatus.FacilityId = t_facility.FacilityId
                  INNER JOIN t_region
                    ON t_facility.RegionId = t_region.RegionId
                
                      AND t_region.CountryId = $countryId
                      AND (t_facility.FLevelId = $fLevelId OR $fLevelId=0)
                      AND (t_region.RegionId = $regionId OR $regionId=0)
                      AND (t_facility.DistrictId = $districtId OR $districtId=0)
                      AND (t_facility.OwnerTypeId = $ownerTypeId OR $ownerTypeId=0)
                  RIGHT JOIN (SELECT
                                p.FacilityId,
                                p.FacilityCode,
                                p.FacilityName,
                                `Latitude`, `Longitude`
                              FROM t_facility p
                                INNER JOIN t_facility_group_map q
                                  ON p.FacilityId = q.FacilityId
                                INNER JOIN t_region r
                                  ON p.RegionId = r.RegionId                       
                              WHERE p.CountryId = $countryId
                                  AND q.ItemGroupId = $itemGroupId
                                  AND (p.FLevelId = $fLevelId OR $fLevelId=0)
                                  AND (r.RegionId = $regionId OR $regionId=0)
                          AND (p.DistrictId = $districtId OR $districtId=0)
                          AND (p.OwnerTypeId = $ownerTypeId OR $ownerTypeId=0)) a
                         
                    ON (t_cfm_masterstockstatus.FacilityId = a.FacilityId
                        AND t_cfm_masterstockstatus.MonthId = $monthId
                        AND t_cfm_masterstockstatus.Year = '$year'
                        AND t_cfm_masterstockstatus.CountryId = $countryId
                        
                        AND t_cfm_masterstockstatus.StatusId = 5)
                WHERE t_cfm_masterstockstatus.FacilityId IS NULL
                ) b
                                    WHERE 1=1 AND b.MosTypeId = $mostypeId OR $mostypeId = 0;";
				

	$rResult = safe_query($sQuery);

	$output = array();

	while ($obj = mysql_fetch_object($rResult)) {		
		if (!is_null($obj -> ClStock))
			$obj -> ClStock = number_format($obj -> ClStock);
		if (!is_null($obj -> AMC))
			$obj -> AMC = number_format($obj -> AMC);
		if (!is_null($obj -> MOS))
			$obj -> MOS = number_format($obj -> MOS, 1);
		$output[] = $obj;
	}
	
	echo json_encode($output);
}


function getCountryProductList() {

	$sQuery = "SELECT
	    t_itemlist.ItemNo
	    , t_itemlist.ItemName
	FROM
	    t_country_product
	    INNER JOIN t_itemlist 
	        ON (t_country_product.ItemNo = t_itemlist.ItemNo) AND (t_itemlist.ItemGroupId = t_country_product.ItemGroupId)
	WHERE (t_country_product.CountryId = " . $_POST['CountryId'] . " AND t_country_product.ItemGroupId = " . $_POST['ItemGroupId'] . ");";

	$rResult = safe_query($sQuery);

	$output = array();

	while ($obj = mysql_fetch_object($rResult)) {
		$output[] = $obj;
	}

	echo json_encode($output);
}


function getCountryInfoById() {
	
	$countryId = $_POST['CountryId'];

	$sQuery = "SELECT
			    CountryId
			    , CountryCode
			    , CountryName
			    , ISO3
			    , CenterLat
			    , CenterLong
			    , ZoomLevel
			FROM
			    t_country
			WHERE (CountryId = $countryId);";

	$rResult = safe_query($sQuery);

	$output = array();

	while ($obj = mysql_fetch_object($rResult)) {
		$output[] = $obj;
	}

	echo json_encode($output);
}


function getRegionList() {
	
	$countryId = $_POST['CountryId'];
	$userName = $_POST['userName'];

/*	$sQuery = "SELECT
				    RegionId
				    , RegionName
				FROM
				    t_region				
				WHERE (CountryId = $countryId);";*/
				
	$sTable = "t_region";   			
	$sQuery = " SELECT a.RegionId, RegionName
				FROM $sTable a
				INNER JOIN t_user_region_map b ON a.RegionId = b.RegionId
				WHERE b.UserId = '$userName'
				ORDER BY RegionName"; 
	$rResult = safe_query($sQuery);

	$output = array();

	while ($obj = mysql_fetch_object($rResult)) {
		$output[] = $obj;
	}
	
	
	array_unshift($output, array("RegionId" => 0, "RegionName"=>"All"));

	echo json_encode($output);
}


function getFacilityLevel() {
    $lan = $_REQUEST['lan'];
    if($lan == 'en-GB'){
            $FlevelName = 'FLevelName';
        }else{
            $FlevelName = 'FLevelNameFrench';
        }   
	
	$sQuery = "SELECT
			    FLevelId
			    , $FlevelName FLevelName
			FROM
			     t_facility_level";

	$rResult = safe_query($sQuery);

	$output = array();

	while ($obj = mysql_fetch_object($rResult)) {
		$output[] = $obj;
	}
	
	echo json_encode($output);
}

function getDistrictList() {
	
	$regionId = $_POST['RegionId'];

	$sQuery = "SELECT
				    DistrictId
				    , DistrictName
				FROM
				    t_districts				
				WHERE (RegionId = $regionId);";

	$rResult = safe_query($sQuery);

	$output = array();

	while ($obj = mysql_fetch_object($rResult)) {
		$output[] = $obj;
	}
	
	
	array_unshift($output, array("RegionId" => 0, "RegionName"=>"All"));

	echo json_encode($output);
}

function getLegendMos() {
	$lan = $_REQUEST['lan'];
	$countryId = $_REQUEST['CountryId'];
	$fLevelId = $_REQUEST['FLevelId'];
	
	if($lan == 'en-GB'){
            $mosTypeName = 'MosTypeName';
        }else{
            $mosTypeName = 'MosTypeNameFrench';
        }     

	$sQuery = "SELECT MosTypeId, $mosTypeName MosTypeName, MinMos, MaxMos, ColorCode, IconMos, IconMos_Width, IconMos_Height, MosLabel
			FROM
			    t_mostype_facility
			WHERE CountryId = $countryId AND FLevelId = $fLevelId
			ORDER BY MosTypeId;";

	$rResult = safe_query($sQuery);
	
	
	$sQuery2 = "SELECT MosTypeId, MosTypeName, MinMos, MaxMos, ColorCode, IconMos, IconMos_Width, IconMos_Height 
			FROM
			    t_mostype_facility_details
			WHERE CountryId = $countryId AND FLevelId = $fLevelId
			ORDER BY MosTypeId;";

	$rResult2 = safe_query($sQuery2);
	
	$output = array("output1" => array(), "output2" => array());

	while ($row = mysql_fetch_array($rResult)) {
		$output['output1'][] = $row;
	}
	
	while ($row2 = mysql_fetch_array($rResult2)) {
		$output['output2'][] = $row2;
	}
	
	echo json_encode($output);
}

?>