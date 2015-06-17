<?php
$sql='';
$sqlResult='';
$totalRec=0;
$useSl = 1;
$sl=1;

$task = '';
if (isset($_POST['action'])) {
	$task = $_POST['action'];
} else if (isset($_GET['action'])) {
	$task = $_GET['action'];
}

switch($task) {
	case "getOwnerTypeData" :
		getOwnerTypeData();
		break;
	case "getReportingByData" :
		getReportingByData();
		break;
	case "getDistrictData" :
		getDistrictData();
		break;
	case "getMOStypeData" :
		getMOStypeData();
		break;
	case "getProductSubGroupData" :
		getProductSubGroupData();
		break;
	case "getformulationData" :
		getformulationData();
		break;	
	case "getItemListData" :
		getItemListData();
		break;
	case "getCountryData" :
		getCountryData();
		break;
	case "getCaseTypeMasterData" :
		getCaseTypeMasterData();
		break;
	case "getCountryRegionsData" :
		getCountryRegionsData();
		break;
	case "getYearData" :
		getYearData();
		break;
	case "getProfileParamData" :
		getProfileParamData();
		break;
	case "getFundingSourceData" :
		getFundingSourceData();
		break;
	case "getAgreementData" :
		getAgreementData();
		break;
	case "getProcuringAgentsData" :
		getProcuringAgentsData();
		break;
	case "getSStatusData" :
		getSStatusData();
		break;
	case "getFacilityTypeData" :
		getFacilityTypeData();
		break;
	case "getFacilityLevelData" :
		getFacilityLevelData();
		break;
	case "getAdjustReasonData" :
		getAdjustReasonData();
		break;
	case "getAmcChangeReasonData" :
		getAmcChangeReasonData();
		break;
	case "getItemData" :
		getItemData();
		break;
	case "getServiceData" :
		getServiceData();
		break;
	case "getRegimenData" :
		getRegimenData();
		break;
	case "getAgencyShipment" :
		getAgencyShipment();
		break;
	case "getStockStatusAtFacility" :
		getStockStatusAtFacility();
		break;
	case "getServiceIndicators" :
		getServiceIndicators();
		break;
	case "getServiceIndicators1" :
		getServiceIndicators1();
		break;
	case "getServiceAreaData" :
		getServiceAreaData();
		break;
	case "getrptFrequencyData" :
		getrptFrequencyData();
		break;

	default :
		echo "{failure:true}";
		break;
}


function getOwnerTypeData() {
	global $sql;	
	$sql = "SELECT OwnerTypeName, OwnerTypeNameFrench FROM t_owner_type order by OwnerTypeName;";	
}
function getReportingByData() {
	global $sql;	
	$sql = "SELECT OwnerTypeName, OwnerTypeNameFrench FROM t_reportby order by OwnerTypeName;"; 
}
function getDistrictData() {
	global $sql;
	global $sqlParameterList;
	$sql = "SELECT DistrictName FROM t_districts 
	where (CountryId = ".$sqlParameterList[0]." OR ".$sqlParameterList[0]." = 0) 
	AND (RegionId = ".$sqlParameterList[1]." OR ".$sqlParameterList[1]." = 0)
	order by DistrictName;"; 
}
function getMOStypeData() {
	global $sql;	
	$sql = "SELECT MosTypeName, MosTypeNameFrench, MinMos, MaxMos,  ColorCode, MosLabel
			FROM t_mostype order by MosTypeId;"; 
}


function getProductSubGroupData() {
	global $sql;	
	$sql = "SELECT ProductSubGroupName, GroupName 
				FROM t_product_subgroup a
                INNER JOIN t_itemgroup b ON a.ItemGroupId = b.ItemGroupId  order by GroupName;"; 
}

function getformulationData() {
	global $sqlParameterList;
	global $sql;	
	$sql = "SELECT FormulationName,FormulationNameFrench, GroupName, ColorCode, ServiceTypeName
		FROM t_formulation a
		INNER JOIN t_itemgroup b ON a.ItemGroupId = b.ItemGroupId
		INNER JOIN t_servicetype c ON a.ServiceTypeId = c.ServiceTypeId
		WHERE  (a.ItemGroupId = ".$sqlParameterList[0]." OR ".$sqlParameterList[0]." = 0) 
		ORDER BY  ServiceTypeName asc, FormulationName;";
		
		
	/*$sql = "SELECT FormulationName,FormulationNameFrench, ServiceTypeName, ColorCode,GroupName
				FROM t_formulation a
                INNER JOIN t_itemgroup b ON a.ItemGroupId = b.ItemGroupId
                INNER JOIN t_servicetype c ON a.ServiceTypeId = c.ServiceTypeId
				where (a.ItemGroupId = ".$sqlParameterList[0]." OR ".$sqlParameterList[0]." = 0) 
				order by FormulationId;";
				*/
}
function getItemListData() {
	global $sqlParameterList;
	global $sql;	
	$sql = "SELECT a.ItemCode, a.ItemName, ShortName, a.bKeyItem, 
			d.ProductSubGroupName, a.bCommonBasket,b.GroupName
			FROM t_itemlist AS a
			INNER JOIN t_itemgroup b ON a.ItemGroupId = b.ItemGroupId
			INNER JOIN t_unitofmeas c ON a.UnitId = c.UnitId
			INNER JOIN t_product_subgroup d ON a.ProductSubGroupId = d.ProductSubGroupId
			 WHERE (a.ItemGroupId = ".$sqlParameterList[0]." OR ".$sqlParameterList[0]." = 0)
			 ORDER BY  GroupName ASC, ItemCode ASC;"; 
}

function getCountryData() {
	global $sql;	
	$sql = "SELECT ISO3, CountryName, CountryNameFrench,
	CASE WHEN LevelType=1 THEN 'Facility Level' ELSE 'National Level' END LevelType,CONCAT(CenterLat,', ', CenterLong) CenterLat,ZoomLevel
	FROM t_country ORDER BY  CountryCode;"; 
}

function getCaseTypeMasterData() {
	global $sqlParameterList;
	global $sql;	
	$sql = "SELECT  GroupName, RegimenName,  GenderType, STL_Color
		FROM t_regimen_master a INNER JOIN  t_itemgroup b ON a.ItemGroupId=b.ItemGroupId
		INNER JOIN t_gendertype c ON a.GenderTypeId = c.GenderTypeId
		WHERE  (a.GenderTypeId = '".$sqlParameterList[1]."' OR '".$sqlParameterList[1]."' = '0')
		AND (a.ItemGroupId = ".$sqlParameterList[0]." OR ".$sqlParameterList[0]." = 0)							
		ORDER BY  GroupName;"; 
}	

function getCountryRegionsData() {
	global $sqlParameterList;
	global $sql;	
	$sql = "SELECT RegionName, CountryName
	FROM t_region a
	INNER JOIN t_country b ON a.CountryId = b.CountryId  
	WHERE (a.CountryId = ".$sqlParameterList[0]." OR ".$sqlParameterList[0]." = 0)
	ORDER BY CountryName asc, RegionName;";
}
function getYearData() {
	global $sql;	
	$sql = "SELECT  YearName FROM t_year ORDER BY  YearName asc;";
}
function getProfileParamData() {
	global $sqlParameterList;
	global $sql;	
	$sql = "SELECT GroupName,ParamName,ParamNameFrench
				FROM t_cprofileparams
				INNER JOIN t_itemgroup ON t_cprofileparams.ItemGroupId = t_itemgroup.ItemGroupId    
				where (t_itemgroup.ItemGroupId = ".$sqlParameterList[0]." OR ".$sqlParameterList[0]." = 0)
				ORDER BY  GroupName	asc;";
}
function getFundingSourceData() {
	global $sqlParameterList;
	global $sql;	
	$sql = "SELECT GroupName,FundingSourceName, FundingSourceDesc
				FROM  t_fundingsource
				Inner Join t_itemgroup ON t_fundingsource.ItemGroupId = t_itemgroup.ItemGroupId
				WHERE (t_itemgroup.ItemGroupId = ".$sqlParameterList[0]." OR ".$sqlParameterList[0]." = 0)
				ORDER BY  FundingSourceName asc;";
}
function getAgreementData() {
	global $sqlParameterList;
	global $sql;	
	$sql = "SELECT AgreementName, FundingSourceName, GroupName	
				FROM t_subagreements a
                INNER JOIN t_fundingsource b ON a.FundingSourceId = b.FundingSourceId    
				Inner Join t_itemgroup ON a.ItemGroupId = t_itemgroup.ItemGroupId				
				WHERE (t_itemgroup.ItemGroupId = ".$sqlParameterList[0]." OR ".$sqlParameterList[0]." = 0)
				ORDER BY  GroupName asc;";
}

function getProcuringAgentsData() {
	global $sql;	
	$sql = "SELECT PAgencyName FROM t_procurement_agents ORDER BY  PAgencyId asc;";
}
function getSStatusData() {
	global $sql;	
	$sql = "SELECT ShipmentStatusDesc FROM t_shipmentstatus ORDER BY  ShipmentStatusId asc;";
}
function getFacilityTypeData() {
	global $sql;	
	$sql = "SELECT FTypeName FROM t_facility_type ORDER BY  FTypeName asc;";
}
function getFacilityLevelData() {
	global $sql;	
	$sql = "SELECT FLevelName,FLevelNameFrench,ColorCode FROM t_facility_level ORDER BY  FLevelName asc ;";
}
function getAdjustReasonData() {
	global $sql;	
	$sql = "SELECT AdjustReason FROM  t_adjust_reason ORDER BY  AdjustId asc  ;";
}
function getAmcChangeReasonData() {
	global $sql;	
	$sql = "SELECT AmcChangeReasonName FROM t_amc_change_reason ORDER BY  AmcChangeReasonName asc ;";
}
function getItemData() {
	global $sql;	
	$sql = "SELECT GroupName,GroupNameFrench,bPatientInfo FROM t_itemgroup ORDER BY  GroupName asc ;";
}
function getServiceData() {
	global $sql;	
	$sql = "SELECT ServiceTypeName,ServiceTypeNameFrench FROM t_servicetype ORDER BY  ServiceTypeName asc;";
}
function getRegimenData() {
	global $sqlParameterList;
	global $sql;	
	$sql = "SELECT  RegimenName, b.FormulationName
			FROM t_regimen a
            INNER JOIN t_formulation b ON a.FormulationId = b.FormulationId 
            INNER JOIN t_gendertype c ON a.GenderTypeId = c.GenderTypeId
			 WHERE (b.ItemGroupId = ".$sqlParameterList[0]." OR ".$sqlParameterList[0]." = 0)
			  ORDER BY  a.FormulationId asc, FormulationName asc, RegimenName asc;";
}
function getAgencyShipment() {
	global $sqlParameterList;
	global $sql;	
	//SELECT f.GroupName,e.ItemName,c.ShipmentStatusDesc, a.ShipmentDate,d.FundingSourceName,
      //      b.CountryName, a.ItemNo,  a.Qty, g.OwnerTypeName 
	$sql = "SELECT f.GroupName,e.ItemName,c.ShipmentStatusDesc, a.ShipmentDate, g.OwnerTypeName ,a.Qty,d.FundingSourceName
	
			FROM t_agencyshipment as a
            INNER JOIN t_country b ON a.CountryId = b.CountryId
            INNER JOIN t_shipmentstatus c ON a.ShipmentStatusId = c.ShipmentStatusId
            INNER JOIN t_fundingsource d ON a.FundingSourceId= d.FundingSourceId
            INNER JOIN t_itemlist e ON a.ItemNo = e.ItemNo 
			INNER JOIN t_itemgroup f ON a.ItemGroupId = f.ItemGroupId 
            INNER JOIN t_owner_type g ON a.OwnerTypeId = g.OwnerTypeId
             WHERE (a.CountryId = ".$sqlParameterList[0]." OR ".$sqlParameterList[0]." = 0)
			AND (a.FundingSourceId = ".$sqlParameterList[1]." OR ".$sqlParameterList[1]." = 0) 
			AND (a.ShipmentStatusId = ".$sqlParameterList[2]." OR ".$sqlParameterList[2]." = 0)
			AND (a.ItemGroupId = ".$sqlParameterList[3]." OR ".$sqlParameterList[3]." = 0)
			AND (a.OwnerTypeId = ".$sqlParameterList[4]." OR ".$sqlParameterList[4]." = 0)            
			ORDER BY  d.FundingSourceName asc, ShipmentDate desc;";
}
function getStockStatusAtFacility() {
	global $sqlParameterList;
	global $sql;
	$sql = "SELECT b.FacilityName, b.ClStock,b.AMC,FORMAT(b.MOS,1) MOS                  
                  FROM (   
                SELECT
                  t_cfm_masterstockstatus.FacilityId,
                  t_facility.FacilityName,
                  `Latitude`, `Longitude`,
                  IFNULL(t_cfm_stockstatus.ClStock,0)    ClStock,
                  IFNULL(t_cfm_stockstatus.AMC,0)       AMC,
                  IFNULL(t_cfm_stockstatus.MOS,0)       MOS
                  ,(SELECT MosTypeId FROM t_mostype_facility x WHERE CountryId = 1
                        AND FLevelId = $sqlParameterList[5]  AND (MosTypeId = $sqlParameterList[9] OR $sqlParameterList[9] = 0)
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
                
                      AND t_region.CountryId = $sqlParameterList[0]
                      AND (t_facility.FLevelId = $sqlParameterList[5] OR $sqlParameterList[5]=0)
                      AND (t_region.RegionId = $sqlParameterList[1] OR $sqlParameterList[1]=0)
                      AND (t_facility.DistrictId = $sqlParameterList[2] OR $sqlParameterList[2]=0)
                      AND (t_facility.OwnerTypeId = $sqlParameterList[3] OR $sqlParameterList[3]=0)
                     
                WHERE (t_cfm_masterstockstatus.StatusId = 5
                       AND t_cfm_masterstockstatus.MonthId = $sqlParameterList[6]
                       AND t_cfm_masterstockstatus.Year = '$sqlParameterList[7]'
                       AND t_cfm_masterstockstatus.CountryId = $sqlParameterList[0]
                       AND t_country_product.ItemGroupId = $sqlParameterList[4]
                       AND t_country_product.ItemNo = $sqlParameterList[8]
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
                
                      AND t_region.CountryId = $sqlParameterList[0]
                      AND (t_facility.FLevelId = $sqlParameterList[5] OR $sqlParameterList[5]=0)
                      AND (t_region.RegionId = $sqlParameterList[1] OR $sqlParameterList[1]=0)
                      AND (t_facility.DistrictId =$sqlParameterList[2] OR $sqlParameterList[2]=0)
                      AND (t_facility.OwnerTypeId = $sqlParameterList[3] OR $sqlParameterList[3]=0)
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
                              WHERE p.CountryId = $sqlParameterList[0]
                                  AND q.ItemGroupId = $sqlParameterList[4]
                                  AND (p.FLevelId = $sqlParameterList[5] OR $sqlParameterList[5]=0)
                                  AND (r.RegionId = $sqlParameterList[1] OR $sqlParameterList[1]=0)
                          AND (p.DistrictId = $sqlParameterList[2] OR $sqlParameterList[2]=0)
                          AND (p.OwnerTypeId = $sqlParameterList[3] OR $sqlParameterList[3]=0)) a
                         
                    ON (t_cfm_masterstockstatus.FacilityId = a.FacilityId
                        AND t_cfm_masterstockstatus.MonthId = $sqlParameterList[6]
                        AND t_cfm_masterstockstatus.Year = '$sqlParameterList[7]'
                        AND t_cfm_masterstockstatus.CountryId = $sqlParameterList[0]
                        AND t_cfm_masterstockstatus.StatusId = 5)
                WHERE t_cfm_masterstockstatus.FacilityId IS NULL
                ) b  WHERE 1=1 AND b.MosTypeId = $sqlParameterList[9] OR $sqlParameterList[9] = 0;";
				
}

function getServiceIndicators() {
	global $sqlParameterList;
	global $sql;	
	$sql = "SELECT FacilityName,  IFNULL(SUM(a.TotalPatient),0) TotalPatient 
            FROM t_cfm_patientoverview a
            INNER JOIN t_facility b ON a.FacilityId = b.FacilityId AND b.FLevelId = 99 	
            AND (b.RegionId = $sqlParameterList[1] OR $sqlParameterList[1] = 0)  
            AND (b.DistrictId = $sqlParameterList[2] OR $sqlParameterList[2] = 0)
            INNER JOIN t_formulation d ON a.FormulationId = d.FormulationId AND d.ServiceTypeId = $sqlParameterList[3]
            INNER JOIN t_cfm_masterstockstatus f ON a.CFMStockId = f.CFMStockId AND StatusId = 5
            WHERE a.MonthId = $sqlParameterList[4] AND a.Year = '$sqlParameterList[5]'  AND a.CountryId = $sqlParameterList[0]  
            GROUP BY a.FacilityId, FacilityName
           	 ORDER BY  FacilityName asc;";
}

function getServiceIndicators1() {
	global $sqlParameterList;
	global $sql;	
	$sql = "SELECT a.FacilityName,d.ItemName,
            CASE WHEN b.AdjustQty>0 THEN b.AdjustQty ELSE 0 END AdjustQtyPlus,
            CASE WHEN b.AdjustQty<0 THEN ABS(b.AdjustQty) ELSE 0 END AdjustQtyMinus, c.AdjustReason
            
            FROM t_facility a
            INNER JOIN t_cfm_stockstatus b ON a.FacilityId = b.FacilityId
            INNER JOIN t_adjust_reason c ON b.AdjustId = c.AdjustId
            INNER JOIN t_itemlist d ON b.ItemNo = d.ItemNo
            INNER JOIN t_cfm_masterstockstatus e ON b.CFMStockId = e.CFMStockId
            
            WHERE (a.RegionId = $sqlParameterList[4] OR $sqlParameterList[4]=0)
            AND (a.DistrictId = $sqlParameterList[5] OR $sqlParameterList[5]=0)
            AND (a.OwnerTypeId = $sqlParameterList[6] OR $sqlParameterList[6]=0)
            AND e.StatusId = 5
            AND b.Year = '$sqlParameterList[2]'
            AND b.MonthId = $sqlParameterList[1]
            AND (b.ItemGroupId = $sqlParameterList[7] OR $sqlParameterList[7]=0) 
            HAVING AdjustQtyPlus != 0 OR AdjustQtyMinus != 0
             ORDER BY  FacilityName asc ;";
}


function getServiceAreaData() {
	global $sql;	
	$sql = "SELECT ServiceAreaName, ServiceAreaNameFrench
				FROM t_service_area ORDER BY  ServiceAreaName asc;";
}


function getrptFrequencyData() {
	global $sqlParameterList;
	global $sql;	
	$sql = "SELECT b.CountryName,c.GroupName,d.FrequencyName,a.StartYearId YearName	
		, case a.FrequencyId when 1 then e.MonthName
		else f.MonthName end MonthName
		FROM t_reporting_frequency a
		Inner Join t_country b ON a.CountryId=b.CountryId
		Inner Join t_itemgroup c ON a.ItemGroupId=c.ItemGroupId
		Inner Join t_frequency d ON a.FrequencyId=d.FrequencyId 
		Left Join t_month e ON a.StartMonthId=e.MonthId 
        Left Join t_quarter f ON a.StartMonthId=f.MonthId  WHERE (a.CountryId = $sqlParameterList[0] OR $sqlParameterList[0]=0)
		  ORDER BY  CountryName;";
}

	
//====================================Dynamic Design======================================

	$sqlResult= mysql_query($sql);
	$totalRec = mysql_num_rows($sqlResult);	
	
	if (!($totalRec>0))
	return;
	
	//if(!empty($_REQUEST['useSl']))
	//	$useSl = $_REQUEST['useSl'];
	//$useSl = settype($useSl,'boolean');
	//var_dump($useSl);	
	//$useSl=1;
	//echo $useSl;
	
	$tableFieldList = array();
	$dataList = array();
	
	$index = 0;
	while($row=mysql_fetch_assoc($sqlResult)){
		$dataList[] = $row;
		
		if($index==0) //Get sql field list
			$tableFieldList[] = array_keys($row);
			
		$index++;
	}
//	$useSl = false;
	//if($useSl){
		array_unshift($tableFieldList[0], "sl");
	//}

$tableFieldCount = count($tableFieldList[0]);
$colorCodeIndex = @$colorCodeIndex[0];


	if ($totalRec>0){
	  if($reportType == 'print' || $reportType== 'pdf'){
	
		//Convert PX to Number for Percent start
		$totalwidth=0;
		$index=0;
		
		//echo $tableFieldCount;
		//print_r($tableHeaderWidth);
		
		for($index=0;$index<$tableFieldCount;$index++){
			@$tableHeaderWidth[@$index] = str_replace('px','',@$tableHeaderWidth[@$index]);
			$totalwidth+=$tableHeaderWidth[$index];
		}
		for($index=0;$index<$tableFieldCount;$index++){
			$tableHeaderWidth[$index] = floor(($tableHeaderWidth[$index]*100)/$totalwidth);
		}
		//Convert PX to Number for Percent end

            echo '<table  class="table table-striped display" width="100%" border="0.5" style="margin:0 auto;">    	
    		<tbody><tr>';
			
			//Table Header			
			for($i=0;$i<$tableFieldCount;$i++){
			
				if($i != @$groupBySqlIndex){ //For Avoid Group Header
					if($i==0)
						echo '<th style="width:'.$tableHeaderWidth[$i].'%; text-align:'.$alignment["0"].';">'.$tableHeaderList[$i].'</th>';
					else
						echo '<th style="width:'.@$tableHeaderWidth[@$i].'%; text-align:'.@$alignment[@$dataType[@$i]].';">'.@$tableHeaderList[@$i].'</th>';
				}
				//else{
				//echo $tableFieldCount.' rubel';
				//}
			}
				echo '</tr>';
				
				
			$tempGroupId='';	
			foreach($dataList as $row){
			
			//if(isset($groupBySqlIndex) && $groupBySqlIndex>=0){				
				if($tempGroupId != @$row[@$tableFieldList[0][@$groupBySqlIndex]]){
					echo'<tr style=background-color:#DAEF62;border-radius:2px; font-size:9px;align: left;color:#000000>
							<td class="txtLeft"; colspan="'.$tableFieldCount.'">'.@$row[@$tableFieldList[0][@$groupBySqlIndex]].'</td>
						 </tr>'; 
				$tempGroupId=@$row[@$tableFieldList[0][@$groupBySqlIndex]];
				}
			//}
				echo '<tr>';				
				for($i=0;$i<$tableFieldCount;$i++){
			
					//if($i==0 && $useSl)			
					if($i != @$groupBySqlIndex){ //For Avoid Group 
					
						if($i == $colorCodeIndex && $colorCodeIndex != ''){ //For Color Field						
							echo '<td style="height:5px;width:'.@$tableHeaderWidth[$i].'%;background-color:'.@$row[@$tableFieldList[0][$i]].'"></td>';
						}
						else{
						
							if($i==0)
								echo '<td style="width:'.@$tableHeaderWidth[$i].'%; text-align: '.@$alignment["0"].';">'.$sl.'</td>';
							else{
								//For check box
								if($checkBoxIndex && in_array($i,$checkBoxIndex)){
								//echo 'rubel';
									if($row[@$tableFieldList[0][$i]] == 0)
										 echo '<td style="width:'.@$tableHeaderWidth[$i].'%; text-align: '.@$alignment[@$dataType[$i]].';"><img src="./image/unchecked.png" /></td>';
									else 
										echo '<td style="width:'.@$tableHeaderWidth[$i].'%; text-align: '.@$alignment[@$dataType[$i]].';"><img src="./image/checked.png" /></td>';
								
								}
								else{
									echo '<td style="width:'.@$tableHeaderWidth[$i].'%; text-align: '.@$alignment[@$dataType[$i]].';">'.getValueFormat(@$row[@$tableFieldList[0][$i]], @$dataType[$i]).'</td>';
								
								}
								
							}
						}
					}	

//echo "=".$i."=";					
				}
				echo '</tr>';
						 
					$sl++; 
			}
		
		echo'</tbody>
			 </table>';
	  }
	  else if($reportType == 'excel'){
	  
		//$cellIdentifire = array("1"=>"A","2"=>"B","3"=>"C","4"=>"D","5"=>"E","6"=>"F","7"=>"G","8"=>"H","9"=>"I","10"=>"J","11"=>"K","12"=>"L","13"=>"M","14"=>"N","15"=>"O","16"=>"P","17"=>"Q","18"=>"R","19"=>"S","20"=>"T","21"=>"U","22"=>"V","23"=>"W","24"=>"X","25"=>"Y","26"=>"Z");
		$cellIdentifire = array("1"=>"A","2"=>"B","3"=>"C","4"=>"D","5"=>"E","6"=>"F","7"=>"G","8"=>"H","9"=>"I","10"=>"J",
"11"=>"K","12"=>"L","13"=>"M","14"=>"N","15"=>"O","16"=>"P","17"=>"Q","18"=>"R","19"=>"S","20"=>"T","21"=>"U",
"22"=>"V","23"=>"W","24"=>"X","25"=>"Y","26"=>"Z","27"=>"AA","28"=>"AB","29"=>"AC","30"=>"AD","31"=>"AE","32"=>"AF",
"33"=>"AG","34"=>"AH","35"=>"AI","36"=>"AJ","37"=>"AK","38"=>"AL","39"=>"AM","40"=>"AN","41"=>"AO","42"=>"AP",
"43"=>"AQ","44"=>"AR","45"=>"AS","46"=>"AT","47"=>"AU","48"=>"AV","49"=>"AW","50"=>"AX","51"=>"AY","52"=>"AZ",
"53"=>"BA","55"=>"BB","56"=>"BC","57"=>"BD","58"=>"BE","59"=>"BF","60"=>"BG","61"=>"BH","62"=>"BI","63"=>"BJ",
"64"=>"BK","65"=>"BL","66"=>"BM","67"=>"BN","68"=>"BO","69"=>"BP","70"=>"BQ","71"=>"BR", "72"=>"BS","73"=>"BT",
"74"=>"BU","75"=>"BV","76"=>"BX","77"=>"BX","78"=>"BY","79"=>"BZ","80"=>"CC");
		//$tableHeaderListCount = count($parameterList['tableHeaderList']);// count($parameterList['tableHeaderList']);
		//$colorCodeIndex = 5;
		//$colorCodeIndex = $colorCodeIndex[0];
		require('../lib/PHPExcel.php');	
        $objPHPExcel = new PHPExcel();
		$reportHeaderListCount = count($reportHeaderList);
		
		//Report Header start
		for($i=1;$i<=$reportHeaderListCount;$i++){
			//$objPHPExcel->getActiveSheet()->SetCellValue('A2','Health Commodity Dashboard');
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i,$reportHeaderList[$i-1]);
			$styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
			//$objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel -> getActiveSheet() -> getStyle('A'.$i) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);			
			//$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFont()->setBold(true);
			//$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '18', 'bold' => true)), 'A2');
			$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => (18-$i), 'bold' => true)), 'A'.$i);
			//$objPHPExcel -> getActiveSheet() -> mergeCells('A2:C2');
			$objPHPExcel -> getActiveSheet() -> mergeCells('A'.$i.':'.$cellIdentifire[$tableFieldCount].$i);//mergeCells('A2:C2')
			
		}
		//Report Header end
	
		//Table Header start
		for($i=1;$i<=$tableFieldCount;$i++){
			if($i != @$groupBySqlIndex+1){ //For Avoid Group Header
				$objPHPExcel->getActiveSheet()->SetCellValue($cellIdentifire[$i].($reportHeaderListCount+2), @$tableHeaderList[$i-1]);
				//$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i].($reportHeaderListCount+2)) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);			
				$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), $cellIdentifire[$i] . ($reportHeaderListCount+2));
				$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellIdentifire[$i]) -> setWidth(18);
				$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i].($reportHeaderListCount+2)  . ':'.$cellIdentifire[$i] . ($reportHeaderListCount+2)) -> applyFromArray($styleThinBlackBorderOutline);
				$objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i].($reportHeaderListCount+2))->getFont()->setBold(true);
				if ($tableFieldList[0][$i - 1] == 'sl')
                    $objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i] . ($reportHeaderListCount + 2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                else if (strtolower($dataType[$i - 1]) == 'numeric')
                   $objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i] . ($reportHeaderListCount + 2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                else if ((strtolower($dataType[$i - 1]) == 'string') || (strtolower($dataType[$i - 1]) == '0') || (strtolower($dataType[$i - 1]) == 'html'))
                    $objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i] . ($reportHeaderListCount + 2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                else
                   $objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i] . ($reportHeaderListCount + 2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			}
		}
		//Table Header end
		
		$sl = 1;
	    $cell = ($reportHeaderListCount+3); //Start table body
		
		$tempGroupId='';
		foreach($dataList as $row){		
		//For Group By start
		if($tempGroupId != @$row[@$tableFieldList[0][@$groupBySqlIndex]]){
			$styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
					'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb'=>'DAEF62'),
					  )
			   );
				$objPHPExcel -> getActiveSheet() -> mergeCells('A' . $cell . ':'.$cellIdentifire[$tableFieldCount-1].$cell);	 //$objPHPExcel -> getActiveSheet() -> mergeCells('A'.$j.':B'.$j);	
				
				$objPHPExcel->getActiveSheet()->SetCellValue('A'.$cell, @$row[@$tableFieldList[0][@$groupBySqlIndex]]); //$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, $rec['GroupName']); 
				$objPHPExcel -> getActiveSheet() -> getStyle('A' . $cell . ':'.$cellIdentifire[$tableFieldCount-1].$cell) -> applyFromArray($styleThinBlackBorderOutline1);//$objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline1);
				
			$cell++;
			$tempGroupId = @$row[@$tableFieldList[0][@$groupBySqlIndex]];
			}
			
		//For Group By start				
				
				
			for($i=1; $i <= count($tableFieldList[0]); $i++){			
				//For Group by
				if((@$groupBySqlIndex+1) != $i){
					//For Color Code
					if($colorCodeIndex == ($i-1) && $colorCodeIndex != ''){
						$cCode=explode('#', $row[$tableFieldList[0][$i-1]]);
						$styleThinBlackBorderOutlinecCode = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
								'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('rgb'=>$cCode[1]),
								  )
						   );
						$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i] . $cell . ':' . $cellIdentifire[$i].$cell) -> applyFromArray($styleThinBlackBorderOutlinecCode);//Table body Line color     $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
					}
					else{
					
						if($i == 1){						
							$objPHPExcel->getActiveSheet()->SetCellValue($cellIdentifire[$i].$cell, $sl);
							$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i] . $cell . ':' . $cellIdentifire[$i].$cell) -> applyFromArray($styleThinBlackBorderOutline);//Table body Line color     $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
						}
						else{
							//when have check box
							if($checkBoxIndex && in_array(($i-1),$checkBoxIndex)){							
							  $objDrawing = new PHPExcel_Worksheet_Drawing();									
							  if($row[$tableFieldList[0][$i-1]] == 0)
								  $objDrawing -> setPath('image/unchecked.png');
							  else 
								 $objDrawing -> setPath('image/checked.png');
							  $objDrawing -> setCoordinates($cellIdentifire[$i] . $cell); //$objDrawing -> setCoordinates('D' . $j);
							  $objDrawing -> setWorksheet($objPHPExcel -> getActiveSheet()); 
							 
							}
							//When have not check box
							else{
							  $objPHPExcel->getActiveSheet()->SetCellValue($cellIdentifire[$i].$cell, getValueFormat($row[$tableFieldList[0][$i-1]], $dataType[$i-1]));
							}
							
							$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i] . $cell . ':' . $cellIdentifire[$i].$cell) -> applyFromArray($styleThinBlackBorderOutline);//Table body Line color     $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
							
						}
						
						 if($tableFieldList[0][$i-1] == 'sl')
							$objPHPExcel -> getActiveSheet() -> getStyle('A'.$cell) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						 else if($dataType[$i-1] == 'numeric')
							$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i].$cell) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
						 else if(($dataType[$i-1] == 'string') || ($dataType[$i-1] == '0') || ($dataType[$i-1] == 'html'))
							 $objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i].$cell) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						 else
							 $objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i].$cell) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					}
				}
			}

			$cell++; $sl++;
		}
			
			
		if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
		} else {
				putenv("TZ=UTC");
		}
			
			
		//$reportSaveNameUTF8 = iconv("UTF-8", "ISO-8859-9//TRANSLIT", $reportSaveName);
		
		//$reportSaveName='rpt';		
		$exportTime = date("Y-m-d_His", time()); 
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 		
		$file = $reportSaveName.'_'.$exportTime. '.xlsx';
		$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	
		header('Location:media/' . $file);

	  }
		 
     }else{
   	    echo 'No record found';
    }
	
	
	
	
	
	
function getValueFormat($value, $dataType) {
    //$retVal=0.0;
    if (strtolower($dataType) == 'numeric') {
        //echo $value.'         ';
        //$str_arr = explode('.',$value);
        //$retVal = number_format($value,strlen($str_arr[1]));  // After the Decimal point
		if(is_numeric($value)){
				if (strpos( $value, '.' ))
					$retVal = number_format($value,1);
				else
					$retVal = number_format($value);
		}
		else
			$retVal = $value;
    } elseif(strtolower($dataType) == 'date') {

        if (validateDate($value)) {
			$retVal = date('d-m-Y', strtotime($value));
		} else {
			$retVal = "";
		}
    } else  {
        $retVal = $value;
    }
    return $retVal;
	
}



function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}
?>