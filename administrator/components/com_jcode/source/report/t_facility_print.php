<?php

include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());
mysql_query('SET CHARACTER SET utf8');

$lan=$_REQUEST['lan']; 
	if($lan == 'en-GB'){
		$SITETITLE = SITETITLEENG;
	}else{
	   $SITETITLE = SITETITLEFRN;
	} 

$gTEXT = $TEXT;
$jBaseUrl = $_GET['jBaseUrl']; 
    
    $ARegionId=$_GET['ARegionId']; 
    $CountryId	=$_GET['CountryId']; 
    $FacilityLevel=$_GET['FacilityLevel']; 
    $FacilityType=$_GET['FacilityType'];
    $CountryName = $_GET['CountryName'];
    $RegionName = $_GET['RegionName'];
    $FTypeName = $_GET['FTypeName'];
    $FLevelName = $_GET['FLevelName'];
    
    $OwnerTypeId = $_GET['OwnerType']; 
    $DistrictId = $_GET['District-list'];
    $ServiceAreaId = $_GET['ServiceAreaId'];
	$OwnerTypeName = $_GET['OwnerTypeName'];
    $DistrictName = $_GET['DistrictName'];
    $ServiceArealName = $_GET['ServiceAreaName'];
	
    if($ARegionId){
		$ARegionId = " AND a.RegionId = '".$ARegionId."' ";
	}    
    if($FacilityType){
		$FacilityType = " AND a.FTypeId = '".$FacilityType."' ";
	}
    if($FacilityLevel){
		$FacilityLevel = " AND a.FLevelId = '".$FacilityLevel."' ";
	}  
    
    if($OwnerTypeId){
		$OwnerTypeId = " AND a.OwnerTypeId = '".$OwnerTypeId."' ";
	} 
    
    if($DistrictId){
		$DistrictId = " AND a.DistrictId = '".$DistrictId."' ";
	}
    
    if($ServiceAreaId){
		$ServiceAreaId = " AND a.ServiceAreaId = '".$ServiceAreaId."' ";
	}
    
    $sWhere = "";
	if ($_GET['sSearch'] != "") { 
        $sWhere = " AND (FacilityCode like '%".mysql_real_escape_string($_GET['sSearch'])."%'
                    OR FacilityName like '%".mysql_real_escape_string($_GET['sSearch'])."%'
                    OR FTypeName like '%".mysql_real_escape_string($_GET['sSearch'])."%'
                    OR RegionName like '%".mysql_real_escape_string($_GET['sSearch'])."%'
                    OR FLevelName like '%".mysql_real_escape_string($_GET['sSearch'])."%'
                    OR FacilityAddress like '%".mysql_real_escape_string($_GET['sSearch'])."%'
                    OR FacilityPhone like '%".mysql_real_escape_string($_GET['sSearch'])."%'
                    OR FacilityFax like '%".mysql_real_escape_string($_GET['sSearch'])."%'
                    OR FacilityEmail like '%".mysql_real_escape_string($_GET['sSearch'])."%'
                    OR FacilityManager like '%".mysql_real_escape_string($_GET['sSearch'])."%'
                    OR DistrictName like '%".mysql_real_escape_string($_GET['sSearch'])."%'
                    OR OwnerTypeName like '%".mysql_real_escape_string($_GET['sSearch'])."%'
                    OR ServiceAreaName like '%".mysql_real_escape_string($_GET['sSearch'])."%' ) ";                                                                                         
	}
    
    $sLimit = "";
	if (isset($_GET['iDisplayStart'])) { 
	   $sLimit = "limit " . mysql_real_escape_string($_GET['iDisplayStart']) . ", " . mysql_real_escape_string($_GET['iDisplayLength']);
	}
    
    $sOrder = "";
	if (isset($_GET['iSortCol_0'])) { $_GET = " ORDER BY FLevelName, ";
		for ($i = 0; $i < mysql_real_escape_string($_GET['iSortingCols']); $i++) {
			$sOrder .= fnColumnToGetFacility(mysql_real_escape_string($_GET['iSortCol_' . $i])) . "" . mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
		}
		$sOrder = substr_replace($sOrder, "", -2);
	}
 
	$sql = " SELECT SQL_CALC_FOUND_ROWS FacilityId, a.CountryId, a.RegionId, ParentFacilityId, 
             a.FTypeId, a.FLevelId, FacilityCode, FacilityName, FacilityAddress, FacilityPhone, FacilityFax, FacilityEmail, 
             FacilityManager, Latitude, Longitude, FacilityCount, FLevelName, FTypeName, RegionName,
             a.DistrictId, a.OwnerTypeId, a.ServiceAreaId, e.DistrictName, f.OwnerTypeName, g.ServiceAreaName, a.AgentType
             FROM t_facility a
             INNER JOIN t_facility_level b ON a.FLevelId = b.FLevelId
             INNER JOIN t_facility_type c ON a.FTypeId = c.FTypeId
             INNER JOIN t_region d ON a.RegionId = d.RegionId
             INNER JOIN t_districts e ON a.DistrictId = e.DistrictId
             INNER JOIN t_owner_type f ON a.OwnerTypeId = f.OwnerTypeId
             INNER JOIN t_service_area g ON a.ServiceAreaId = g.ServiceAreaId 	
             AND (a.CountryId = ".$CountryId." OR ".$CountryId." = 0) 
             ".$ARegionId." ".$DistrictId." ".$OwnerTypeId." ".$ServiceAreaId." ".$FacilityType." ".$FacilityLevel." 
             ".$sWhere." ".$sOrder." ".$sLimit."  ORDER BY FLevelName,FacilityCode"; 
   
	 mysql_query("SET character_set_results=utf8");     			             
	$r = mysql_query($sql) ;
	$total = mysql_num_rows($r);  
  
	if ($total>0){
        echo'<!DOCTYPE html>
            <html>
            <head>
             <meta name="viewport" content="width=device-width, initial-scale=1.0" />	
			<base href="http://localhost/warp/index.php/fr/adminfr/country-fr" />
			<meta http-equiv="content-type" content="text/html; charset=utf-8" />
			<meta name="generator" content="Joomla! - Open Source Content Management" />
            <link rel="stylesheet" href="'.$jBaseUrl.'templates/protostar/css/template.css" type="text/css"/> 
            <link href="'.$jBaseUrl.'templates/protostar/endless/bootstrap/css/bootstrap.min.css" rel="stylesheet">
            <link href="'.$jBaseUrl.'templates/protostar/endless/css/font-awesome.min.css" rel="stylesheet">
            <link href="'.$jBaseUrl.'templates/protostar/endless/css/pace.css" rel="stylesheet">	
            <link href="'.$jBaseUrl.'templates/protostar/endless/css/colorbox/colorbox.css" rel="stylesheet">
            <link href="'.$jBaseUrl.'templates/protostar/endless/css/morris.css" rel="stylesheet"/> 	
            <link href="'.$jBaseUrl.'templates/protostar/endless/css/endless.min.css" rel="stylesheet"> 
            <link href="'.$jBaseUrl.'templates/protostar/endless/css/endless-skin.css" rel="stylesheet">
            <link href="'.$jBaseUrl.'administrator/components/com_jcode/source/css/custom.css" rel="stylesheet"/>
            <style>
            table.display tr.even.row_selected td {
            background-color: #4DD4FD;
            }    
            table.display tr.odd.row_selected td {
            background-color: #4DD4FD;
            }
            .SL{
            text-align: center !important;
            }
            td.Countries{
            cursor: pointer;
            }   
            </style>
            </head>
            <body>';
             
		   echo'<div class="row"> 
                    <div class="panel panel-default table-responsive" id="grid_country">
                        <div class="padding-md clearfix">
                            <div class="panel-heading">
								<h2 style="text-align:center;">'.$SITETITLE.'</h2>
                                <h3 style="text-align:center;">'.$gTEXT['Facility List'].'<h3>
                                <p style="text-align:center; font-size:14px;">'.$CountryName.' - '.$RegionName.' - '.$DistrictName.' - '.$FTypeName.'</p>
                                <p style="text-align:center; font-size:14px;">'.$FLevelName.' - '.$ServiceArealName.' - '.$OwnerTypeName.'</p>
                            </div>	
                            <table class="table table-striped display" id="gridDataCountry">
                                <thead>
                                </thead>
                                <tbody>
                                    <tr>		
                        				<th style="text-align: center;">SL #</th>
                        			    <th>'.$gTEXT['Facility Code'].'</th>
                        			    <th>'.$gTEXT['Facility Name'].'</th>
                        			    <th style="text-align: left;">'.$gTEXT['Facility Type'].'</th>
                        			    <th style="text-align: left;">'.$gTEXT['Region Name'].'</th>
                        			    <th style="text-align: left;">'.$gTEXT['District'].'</th>
                        			    <th style="text-align: left;">'.$gTEXT['Owner Type'].'</th>  
										<th style="text-align: left;">PPM</th> 
                        			    <th style="text-align: left;">'.$gTEXT['Service Area'].'</th> 
                        			    <th style="text-align: left;">'.$gTEXT['Facility Address'].'</th>  
                        			    <th style="text-align: left;">'.$gTEXT['Assigned Group'].'</th> 
            		               </tr>';
                    							
                            	$tempGroupId='';
                            	$i=1;						
                            	while($rec=mysql_fetch_array($r)){
                            	   //$agentType = $row -> AgentType;
								   $agentType = $rec['AgentType'];
                                	if($tempGroupId!=$rec['FLevelName']){
                                   	 	echo'<tr style=background-color:#DAEF62;border-radius:2px; font-size:9px;align: center;color:#000000>
                                             <td class="group"; colspan="11">'.$rec['FLevelName'].'</td>
                                           </tr>'; 
                                		   
                                		$tempGroupId=$rec['FLevelName'];
                                	}
                                    
                                    if($rec['ParentFacilityId'] == NULL) $rec['ParentFacilityId']=0;	
                                    	
                                    //$sql_parent = " SELECT FacilityName PFacilityName
                                                    //FROM t_facility
                                                   // WHERE FacilityId = ".$rec['ParentFacilityId']." "; 
                                    //$pacrs_parent = mysql_query($sql_parent);
                                   // $row = mysql_fetch_object($pacrs_parent);
                                    //$PFacilityName = $row -> PFacilityName; 
                                    
                                    //if($PFacilityName == "")$PFacilityName='None';
                                    
                                    $sql_group = "  SELECT FacilityId, GroupName
                                                    FROM t_facility_group_map a
                                                    INNER JOIN t_itemgroup b ON a.ItemGroupId = b.ItemGroupId
                                                    WHERE FacilityId = ".$rec['FacilityId']." "; 
                                    $pacrs_group = mysql_query($sql_group);
                                    $group_name = "";
                                    
                                    $x = 0;
                                    while ($row_group = @mysql_fetch_object($pacrs_group)) {	  
                                        if ($x++) $group_name.= ", ";
                                        $group_name.= $row_group -> GroupName;           
                                    }
									
// 									
// 									
	// if($ParentFacilityId == NULL)$ParentFacilityId=0;
//         
    	// $sql_parent = " SELECT FacilityName PFacilityName
                 // FROM t_facility
                 // WHERE FacilityId = ".$ParentFacilityId." "; 
        // $pacrs_parent = mysql_query($sql_parent, $conn);
        // $r = mysql_fetch_object($pacrs_parent);
    	// $PFacilityName = $r -> PFacilityName; 
//             
        // if($PFacilityName == "")$PFacilityName='None';
//         
    	// $sql_group = " SELECT FacilityId, GroupName
                 // FROM t_facility_group_map a
                 // INNER JOIN t_itemgroup b ON a.ItemGroupId = b.ItemGroupId
                 // WHERE FacilityId = ".$FacilityId." "; 
        // $pacrs_group = mysql_query($sql_group, $conn);
        // $group_name = "";
        // $i = 0;
	    // while ($row_group = @mysql_fetch_object($pacrs_group)) {	  
	       // if ($i++) $group_name.= ", ";
	       // $group_name.= $row_group -> GroupName;           
        // }
$l = "<input type='checkbox' " . ($agentType == 3? 'checked':'disabled') . " value = " . $agentType . " disabled/><span class='custom-checkbox'></span>";
                            echo '<tr> <td style="text-align: center;">'.$i.'</td>
                                	   <td>'.$rec['FacilityCode'].'</td>
                                       <td>'.$rec['FacilityName'].'</td>
                                	   <td style="text-align: left;" >'.$rec['FTypeName'].'</td>	
                                       <td style="text-align: left;" >'.$rec['RegionName'].'</td>
                                       <td style="text-align: left;">'.$rec['DistrictName'].'</td>
                                       <td style="text-align: left;">'.$rec['OwnerTypeName'].'</td>
									   <td style="text-align: left;">'.$l.'</td>
                                	   <td style="text-align: left;" >'.$rec['ServiceAreaName'].'</td>		
                                       <td style="text-align: left;" >'.$rec['FacilityAddress'].'</td>	
                                       <td style="text-align: left;" >'.$group_name.'</td>
                                       		
                                </tr>';
                                            		 
                            $i++; 
                        }
                	
                            echo'</tbody>
                            	</table>
                                </div>
                            	</div>  
                                </div>';
                            echo '</body></html>';	
        
    }else{
				$error = "No record found";	
				echo $error;
	}

?>