<?php   
function user_all_test(){
    
    $user = JFactory::getUser();
    $userName = $user->username; 
    $lang = JFactory::getLanguage();
    $lan = $lang->getTag();
    
    $sQuery = " SELECT COUNT(CountryId) CNumber
                FROM t_country  ";
    				
    $rResult = safe_query($sQuery);
    $r = mysql_fetch_object($rResult);
    $CNumber = $r->CNumber;  
      
    $sQuery_user = " SELECT COUNT(CountryId) CUserNumber
                     FROM t_user_country_map
                     WHERE UserId = '".$userName."' "; 
    
    $rResult_user = safe_query($sQuery_user);
    $r_user = mysql_fetch_object($rResult_user);
    $CUserNumber = $r_user->CUserNumber;  
    
    if($CNumber == $CUserNumber){
        if($lan == 'en-GB'){
            return '<option value="0" selected="true">All</option>';
        }else{
            return '<option value="0" selected="true">Tous</option>';
        }        
    }else{
        return " ";
    }                       
}

?>      
               
<script type="text/javascript">   

<?php   
        $user = JFactory::getUser();
        $baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
        $jBaseUrl = JURI::base();
        $userName = $user->username; 
        $lang = JFactory::getLanguage();
        $lan = $lang->getTag();
        
        if($lan == 'en-GB'){
            $CountryLang = 'CountryName';
            $MonthLang = 'MonthName';
            $GroupLang = 'GroupName';
            $ServiceLang = 'ServiceTypeName';
			
			$OwnerTypeName = 'OwnerTypeName';
			$ServiceAreaName = 'ServiceAreaName';
            $reportbyLang = 'OwnerTypeName';
			$FLevelLang = 'FLevelName';
        }else{
            $CountryLang = 'CountryNameFrench';
            $MonthLang = 'MonthNameFrench';
            $GroupLang = 'GroupNameFrench';
            $ServiceLang = 'ServiceTypeNameFrench';
			
			$OwnerTypeName = 'OwnerTypeNameFrench';
			$ServiceAreaName = 'ServiceAreaNameFrench';
            $reportbyLang = 'OwnerTypeNameFrench';
			$FLevelLang = 'FLevelNameFrench';
        } 
		
		
		/*************************************** Select Default User Country and Item Group ************************************************************* */       
		
		echo ' var gUserCountryId = 1;';
		echo ' var gUserItemGroupId = 1;';
        echo ' var gDetaultOwnerTypeId = 2;';
        /***************************************Month List************************************************************* */       
		
		$sTable = "t_month";
			
		$sQuery = "SELECT MonthId, $MonthLang MonthName 
					FROM  $sTable
                    ORDER BY MonthId";
							
		$rResult = safe_query($sQuery);
		
		$output = array();
		
		while($obj = mysql_fetch_object($rResult)) {
				$output[] = $obj;
		}
		echo ' var gMonthList = JSON.parse(\'' . json_encode($output) . '\');';
		
		    
        /***************************************Month List for quard************************************************************* */       
		
		$sTable = "t_month";
			
		$sQuery = "SELECT MonthId, $MonthLang MonthName  FROM t_quarter Order By MonthId";
							
		$rResult = safe_query($sQuery);
		
		$output = array();
		
		while($obj = mysql_fetch_object($rResult)) {
				$output[] = $obj;
		}
		echo ' var gMonthListbydashboard = JSON.parse(\'' . json_encode($output) . '\');';
	
	
		/******************************************** Year List **********************************************************/
		
		$aColumns = array('YearId', 'YearName');	
		
		$sTable = "t_year";
			
		$sQuery = "SELECT  `" . str_replace(" , ", " ", implode("`, `", $aColumns)) . "`
					FROM  $sTable";
							
		$rResult = safe_query($sQuery);
		
		$output = array();
		
		while($obj = mysql_fetch_object($rResult)) {
				$output[] = $obj;
		}
		
		echo ' var gYearList = JSON.parse(\'' . json_encode($output) . '\');';
		
		/************************************* Country List ********************************************************************/
	
		$sTable = "t_country ";
   			
		$sQuery = " SELECT a.CountryId, $CountryLang CountryName
					FROM $sTable a
                    INNER JOIN t_user_country_map b ON a.CountryId = b.CountryId
                    WHERE b.UserId = '".$userName."' 
                    ORDER BY CountryName"; 
							
		$rResult = safe_query($sQuery);
		
		$output = array();
		
		while($obj = mysql_fetch_object($rResult)) {
				$output[] = $obj;
		}
				
		echo ' var gCountryList = JSON.parse(\'' . json_encode($output) . '\');';
		
		/************************************* Country object list with facility level ********************************************************************/
		
		$aColumns = array('CountryId', 'CountryName');	
		
		$sTable = "t_country";
			
	    $sQuery = " SELECT a.CountryId, CountryName
					FROM $sTable a
                    INNER JOIN t_user_country_map b ON a.CountryId = b.CountryId
                    WHERE b.UserId = '$userName'
					ORDER BY CountryName";
							
		$rResult = safe_query($sQuery);
		
		$output = array();
		
		while($obj = mysql_fetch_object($rResult)) {
				$output[] = $obj;
		}
				
		echo ' var gCountryListFLevel = JSON.parse(\'' . json_encode($output) . '\');';
		
		/******************************************* Country List  Array ****************************************************/
		
		$aColumns = array('CountryId', 'CountryName', 'StartMonth', 'StartYear');	
		
		$sTable = "t_country";
			
		$sQuery = " SELECT a.CountryId, CountryName, StartMonth, StartYear
					FROM $sTable a
                    INNER JOIN t_user_country_map b ON a.CountryId = b.CountryId
                    WHERE b.UserId = '$userName' 
                    ORDER BY CountryName ";
                    
		$rResult = safe_query($sQuery);
		
		$output = array();
		
		while ($aRow = mysql_fetch_array($rResult)) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
					$row[] = $aRow[$aColumns[$i]];				
			}
			$output[] = $row;			
		}
		echo ' var gCountryListArry = JSON.parse(\'' . json_encode($output). '\');';
		
		/********************************** Country List  Array of Facility Level********************************************************/
		
		$aColumns = array('CountryId', 'CountryName',  'StartMonth', 'StartYear');	
		
		$sTable = "t_country";
			
		$sQuery = " SELECT a.CountryId, CountryName, StartMonth, StartYear
					FROM $sTable a
                    INNER JOIN t_user_country_map b ON a.CountryId = b.CountryId
                    WHERE b.UserId = '$userName' 
                    ORDER BY CountryName";
		
		$rResult = safe_query($sQuery);
		
		$output = array();
		
		while ($aRow = mysql_fetch_array($rResult)) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
					$row[] = $aRow[$aColumns[$i]];				
			}
			$output[] = $row;			
		}
		echo ' var gCountryListArryFLevel = JSON.parse(\'' . json_encode($output). '\');';		
		
		/******************************************* Country List  Array With ISO3 ****************************************************/
		
		$aColumns = array('CountryId', 'CountryName', 'ISO3');	
		
		$sTable = "t_country";
			
		$sQuery = " SELECT CountryId, $CountryLang CountryName, ISO3
    				FROM $sTable
    			    ORDER BY CountryName";
							
		$rResult = safe_query($sQuery);
		
		$row = array();
		
		while ($aRow = mysql_fetch_object($rResult)) {			
					$row[$aRow->CountryId] = $aRow;						
		}
		echo ' var gCountryListISO3Chain = JSON.parse(\'' . json_encode($row). '\');';
		
		/************************************************** Item Group *****************************************************************************/
		
		$sTable = "t_itemgroup";
			
		$sQuery = "SELECT t_itemgroup.ItemGroupId, $GroupLang GroupName, t_itemgroup.bPatientInfo
					FROM  t_itemgroup
					INNER JOIN t_user_itemgroup_map ON t_itemgroup.ItemGroupId = t_user_itemgroup_map.ItemGroupId
					where t_user_itemgroup_map.UserId = '".$userName."' 
					ORDER BY GroupName";
							
		$rResult = safe_query($sQuery);
		
		$output = array();
		
		while($obj = mysql_fetch_object($rResult)) {
				$output[] = $obj;
		}
				
		echo ' var gItemGroupList = JSON.parse(\'' . json_encode($output) . '\');';	
		
		/************************************************** Product Group *****************************************************************************/
		
		$sTable = "t_itemgroup";
			
		$sQuery = "SELECT ItemGroupId, GroupName, bPatientInfo 
		           FROM ((SELECT t_itemgroup.ItemGroupId, $GroupLang GroupName, t_itemgroup.bPatientInfo
					FROM  t_itemgroup
					INNER JOIN t_user_itemgroup_map ON t_itemgroup.ItemGroupId = t_user_itemgroup_map.ItemGroupId
					where t_user_itemgroup_map.UserId = '".$userName."' ORDER BY GroupName) 
				    UNION 
				    (SELECT 0,'Basket Medicine',0)) a"; //echo $sQuery;
							
		$rResult = safe_query($sQuery);
		
		$output = array();
		
		while($obj = mysql_fetch_object($rResult)) {
				$output[] = $obj;
		}
       // echo $output;
				
		echo ' var gProductGroupList = JSON.parse(\'' . json_encode($output) . '\');';		
			
		
		/************************************* Item Group  Array ****************************************************************/
		
		$aColumns = array('ItemGroupId', 'GroupName');	
		
		$sTable = "t_itemgroup";
			
		$sQuery = "SELECT  `" . str_replace(" , ", " ", implode("`, `", $aColumns)) . "`
					FROM   $sTable
					ORDER BY GroupName";
							
		$rResult = safe_query($sQuery);
		
		$output = array();
		
		while ($aRow = mysql_fetch_array($rResult)) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
					$row[] = $aRow[$aColumns[$i]];				
			}
			$output[] = $row;			
		}
		echo ' var gItemGroupListArry = JSON.parse(\'' . json_encode($output). '\');';	
		
		/************************************* User Map Country ****************************************************************/
		
		$aColumns = array('MapId', 'UserId', 'CountryId');	
		
		$sTable = "t_user_country_map";
			
		$sQuery = "SELECT  `" . str_replace(" , ", " ", implode("`, `", $aColumns)) . "`
					FROM   $sTable
					WHERE UserId = '$jUserId'";
							
		$rResult = safe_query($sQuery);
		
		$output = array();
		
		while($obj = mysql_fetch_object($rResult)) {
				$output[] = $obj;
		}
		
		echo ' var gUserCountry = JSON.parse(\'' . json_encode($output). '\');';
        	
		/************************************* Service Type ********************************************************************/
		
		$sTable = "t_servicetype";
			
		$sQuery = "SELECT ServiceTypeId, $ServiceLang ServiceTypeName 
					FROM  $sTable
					ORDER BY ServiceTypeName";
							
		$rResult = safe_query($sQuery);
		
		$output = array();
		
		while($obj = mysql_fetch_object($rResult)) {
				$output[] = $obj;
		}
				
		echo ' var gServiceTypeList = JSON.parse(\'' . json_encode($output) . '\');';
        
		/************************************* Funding Source********************************************************************/
		
		$aColumns = array('FundingSourceId', 'FundingSourceName');	
		
		$sTable = "t_fundingsource";
			
		$sQuery = "SELECT  `" . str_replace(" , ", " ", implode("`, `", $aColumns)) . "`
					FROM   $sTable
					ORDER BY FundingSourceName";
							
		$rResult = safe_query($sQuery);
		
		$output = array();
		
		while($obj = mysql_fetch_object($rResult)) {
				$output[] = $obj;
		}
				
		echo ' var gFundingSourceList = JSON.parse(\'' . json_encode($output) . '\');';
        
 	    /************************************* Shipment Status ********************************************************************/
		
		$aColumns = array('ShipmentStatusId', 'ShipmentStatusDesc');	
		
		$sTable = "t_shipmentstatus";
			
		$sQuery = "SELECT  `" . str_replace(" , ", " ", implode("`, `", $aColumns)) . "`
					FROM   $sTable
					ORDER BY ShipmentStatusDesc";
							
		$rResult = safe_query($sQuery);
		
		$output = array();
		
		while($obj = mysql_fetch_object($rResult)) {
				$output[] = $obj;
		}
				
		echo ' var gShipmentStatusList = JSON.parse(\'' . json_encode($output) . '\');';	
        
      	  








	/************************************* ServiceArea List ********************************************************************/
	
		$sTable = "t_service_area ";
   			
		$sQuery = " SELECT a.ServiceAreaId, $ServiceAreaName ServiceAreaName
					FROM $sTable a
                    ORDER BY ServiceAreaName"; 
							
		$rResult = safe_query($sQuery);
		
		$output = array();
		
		while($obj = mysql_fetch_object($rResult)) {
				$output[] = $obj;
		}
				
		echo ' var gServiceAreaList = JSON.parse(\'' . json_encode($output) . '\');';

	/************************************* OwnerType  List ********************************************************************/
	
		$sTable = "t_owner_type ";
   			
		$sQuery = " SELECT a.OwnerTypeId, $OwnerTypeName OwnerTypeName
					FROM $sTable a
                    ORDER BY OwnerTypeName"; 
							
		$rResult = safe_query($sQuery);
		
		$output = array();
		
		while($obj = mysql_fetch_object($rResult)) {
				$output[] = $obj;
		}
				
		echo ' var gOwnerTypeList = JSON.parse(\'' . json_encode($output) . '\');';
		
		$aColumns = array('OwnerTypeId', 'OwnerTypeName');	
				
		$sQuery = "SELECT
					    t_owner_type.OwnerTypeId
					    , t_owner_type.OwnerTypeName
					FROM
					    t_user_owner_type_map
					    INNER JOIN t_owner_type 
					        ON (t_user_owner_type_map.OwnerTypeId = t_owner_type.OwnerTypeId)
					WHERE (t_user_owner_type_map.UserId = 'admin');"; 
		
		$rResult = safe_query($sQuery);
		
		$output = array();
		
		while ($aRow = mysql_fetch_array($rResult)) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
					$row[] = $aRow[$aColumns[$i]];				
			}
			$output[] = $row;			
		}
		echo ' var gOwnerTypeListArray = JSON.parse(\'' . json_encode($output). '\');';	

	/************************************* District List ********************************************************************/
	
		$sTable = "t_districts ";
   			
		$sQuery = " SELECT a.DistrictId, DistrictName
					FROM $sTable a
                    ORDER BY DistrictName"; 
							
		$rResult = safe_query($sQuery);
		
		$output = array();
		
		while($obj = mysql_fetch_object($rResult)) {
				$output[] = $obj;
		}
				
		echo ' var gDistrictList = JSON.parse(\'' . json_encode($output) . '\');';		
		
		
/************************************* Region List ********************************************************************/
	
		$sTable = "t_region";
   			
		$sQuery = " SELECT a.RegionId, RegionName
					FROM $sTable a
                    ORDER BY RegionName"; 
							
		$rResult = safe_query($sQuery);
		
		$output = array();
		
		while($obj = mysql_fetch_object($rResult)) {
				$output[] = $obj;
		}
				
		echo ' var gRegionList = JSON.parse(\'' . json_encode($output) . '\');';	
		
		$aColumns = array('RegionId', 'RegionName');	
				
		$sQuery = "SELECT
					    t_user_region_map.RegionId
					    , t_region.RegionName
					FROM
					    t_region
					    INNER JOIN t_user_region_map 
					        ON (t_region.RegionId = t_user_region_map.RegionId)
					WHERE (t_user_region_map.UserId = 'admin');"; 
		
		$rResult = safe_query($sQuery);
		
		$output = array();
		
		while ($aRow = mysql_fetch_array($rResult)) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
					$row[] = $aRow[$aColumns[$i]];				
			}
			$output[] = $row;			
		}
		echo ' var gRegionListArray = JSON.parse(\'' . json_encode($output). '\');';	


/************************************* Regimen Master List ********************************************************************/
	
		$sTable = "t_regimen_master";
   			
		$sQuery = " SELECT a.RegMasterId, a.RegimenName
					FROM $sTable a
                    ORDER BY RegimenName"; 
							
		$rResult = safe_query($sQuery);
		
		$output = array();
		
		while($obj = mysql_fetch_object($rResult)) {
				$output[] = $obj;
		}
				
		echo ' var gRegimenMasterList = JSON.parse(\'' . json_encode($output) . '\');';	

		
        
		
		
/************************************* GenderType List ********************************************************************/
	
		$sTable = "t_gendertype";
   			
		$sQuery = " SELECT GenderTypeId, GenderType
					FROM $sTable 
                    ORDER BY GenderType"; 
							
		$rResult = safe_query($sQuery);
		
		$output = array();
		
		while($obj = mysql_fetch_object($rResult)) {
				$output[] = $obj;
		}
				
		echo ' var gGenderTypeList = JSON.parse(\'' . json_encode($output) . '\');';	

/***************************************Report By List*******************************/	
        $sTable = "t_reportby";
			
		$sQuery = "SELECT OwnerTypeId, $reportbyLang OwnerTypeName 
					FROM  t_reportby
					ORDER BY OwnerTypeName desc";
					//INNER JOIN t_user_itemgroup_map ON t_itemgroup.ItemGroupId = t_user_itemgroup_map.ItemGroupId
					//where t_user_itemgroup_map.UserId = '".$userName."' 		
		$rResult = safe_query($sQuery);
		
		$output = array();
		
		while($obj = mysql_fetch_object($rResult)) {
				$output[] = $obj;
		}
				
		echo ' var gReportByList = JSON.parse(\'' . json_encode($output) . '\');';
		
/***************************************FLevel*******************************/	
        $sTable = "t_facility_level";
			
		$sQuery = "SELECT FLevelId, $FLevelLang FLevelName 
					FROM  $sTable
					ORDER BY FLevelName ";
			//desc
		$rResult = safe_query($sQuery);
		
		$output = array();
		
		while($obj = mysql_fetch_object($rResult)) {
				$output[] = $obj;
		}
				
		echo ' var gFLevelList = JSON.parse(\'' . json_encode($output) . '\');';				
        
	?>	
	

</script>