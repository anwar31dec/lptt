<?php

include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());
mysql_query('SET CHARACTER SET utf8');

$gTEXT = $TEXT;
$jBaseUrl = $_GET['jBaseUrl'];
$lan = $_GET['lan'];
if ($lan == 'en-GB') {
    $SITETITLE = SITETITLEENG;
} else {
    $SITETITLE = SITETITLEFRN;
}
$monthId = isset($_GET['MonthId'])? $_GET['MonthId'] : '';
///
$year = $_GET['Year'];
$country=isset($_GET['CountryId'])? $_GET['CountryId'] : '';
$itemGroupId=isset($_GET['ItemGroupId'])? $_GET['ItemGroupId'] : '';
$CountryName = $_GET['CountryName']; 
$MonthName=$_GET['MonthName'];
//$ItemGroupName = $_GET['ItemGroupName'];
$regionId = $_GET['RegionId'];
$RegionName = $_GET['RegionName'];
$districtId = $_GET['DistrictId'];
$DistrictName = $_GET['DistrictName'];
$ownerTypeId = $_GET['OwnerTypeId'];
$OwnerTypeName = $_GET['OwnerTypeName'];

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
        'LastSubmittedDt', 'bPublished', 'PublishedDt','FLevelName', 'FLevelId');

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


    mysql_query("SET @rank=0;");

    $serial = "@rank:=@rank+1 AS SL";

    $sQuery = "SELECT SQL_CALC_FOUND_ROWS " . $serial . ", b.FacilityId, b.FacilityCode, b.FacilityName,
				IFNULL( a.FacilityId,0) bEntered,				
				DATE_FORMAT(a.CreatedDt, '%d-%b-%Y %h:%i %p') CreatedDt,	
				IF(c.StatusId = '2', '1', '0') bSubmitted,
				DATE_FORMAT(a.LastSubmittedDt, '%d-%b-%Y %h:%i %p')  LastSubmittedDt,
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
				order by b.FLevelId;";

//echo $sQuery;

mysql_query("SET character_set_results=utf8");

$r = mysql_query($sQuery);
$total = mysql_num_rows($r);
$i = 1;
if ($r)
    if ($total > 0) {
        echo '<!DOCTYPE html>
			<html>
			<head>
			<meta http-equiv="content-type" content="text/html; charset=utf-8" />
			 <link rel="stylesheet" href="' . $jBaseUrl . 'templates/protostar/css/template.css" type="text/css" /> 
			 <link href="' . $jBaseUrl . 'templates/protostar/endless/bootstrap/css/bootstrap.min.css" rel="stylesheet">
			 <link href="' . $jBaseUrl . 'templates/protostar/endless/css/font-awesome.min.css" rel="stylesheet">
			 <link href="' . $jBaseUrl . 'templates/protostar/endless/css/pace.css" rel="stylesheet">	
			 <link href="' . $jBaseUrl . 'templates/protostar/endless/css/colorbox/colorbox.css" rel="stylesheet">
			 <link href="' . $jBaseUrl . 'templates/protostar/endless/css/morris.css" rel="stylesheet"/> 	
             <link href="' . $jBaseUrl . 'templates/protostar/endless/css/endless.min.css" rel="stylesheet"> 
	        <link href="' . $jBaseUrl . 'templates/protostar/endless/css/endless-skin.css" rel="stylesheet">
			<link href="' . $jBaseUrl . 'administrator/components/com_jcode/source/css/custom.css" rel="stylesheet"/>
			    
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
				.group {
					background-color: #e8e8e8 !important;
					font-size: 110%;
				}
			</style>
			</head>
			<body>';
        echo '<div class="row" style="padding: 0 30px; margin: 0 auto;"> 
	        <div class="panel panel-default table-responsive" id="grid_country">
          	<div class="padding-md clearfix">
           	<div class="">
			  <h2 style="text-align:center;">' . $SITETITLE . ' <h2>
              <h3 style="text-align:center;">' . $gTEXT['Facility Reporting Status'] . '<h3>
            </div>
               <div class="clearfix">						
	            		<h4 style="text-align:center;">' . $CountryName . ' - ' .$RegionName.' - '.$DistrictName.' - '.$OwnerTypeName.' - '.$MonthName.', '.$year . ' <h4>					
				</div> 	
    			<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    				</thead>
    				<tbody>
    		               <tr>	
    						    <th>SL</th>	 
								<th >' . $gTEXT['Facility Code'] . '</th>
								<th>' . $gTEXT['Facility Name'] . '</th>
								<th >' . $gTEXT['Entered'] . '</th>
								<th >' . $gTEXT['Entry Date'] . '</th>
								<th >' . $gTEXT['Submitted'] . '</th>
								<th >' . $gTEXT['Submitted Date'] . '</th>
								<th >' . $gTEXT['Published'] . '</th>
								<th >' . $gTEXT['Published Date'] . '</th>
								<th></th>	 
							</tr>';


        $h = 1;
		$tmpLevelId='';
        while ($rec = mysql_fetch_array($r)) {
			
			if($rec[11] != $tmpLevelId){
				echo '<tr style="background-color: #e8e8e8;"><td style="text-align: center;">' . $rec[10] . '</td><td></td>
				<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
			  $tmpLevelId = $rec[11];
			}
      
			
			
			
			
			
			
            $narr = array();
            for ($i = 0; $i < count($aColumns)-2; $i++) {
				

                if ($aColumns[$i] == "bEntered") {

                    $narr[] = ($rec[$aColumns[$i]] == "0") ? '<span class="label label-danger">&nbsp No &nbsp</span>' : '<span class="label label-success">&nbsp Yes &nbsp</span>';
                } else if ($aColumns[$i] == "bSubmitted") {

                    $narr[] = ($rec[$aColumns[$i]] == "0") ? '<span class="label label-danger">&nbsp No &nbsp</span>' : '<span class="label label-success">&nbsp Yes &nbsp</span>'
                    ;
                } else if ($aColumns[$i] == "bAccepted") {


                    $narr[] = ($rec[$aColumns[$i]] == "0") ? '<span class="label label-danger">&nbsp No &nbsp</span>' : '<span class="label label-success">&nbsp Yes &nbsp</span>'
                    ;
                    if ($rec[$aColumns[$i]] == "1") {
                        $narr[6] = '<span class="label label-success">&nbsp Yes &nbsp</span>';
                    }
                } else if ($aColumns[$i] == "bPublished") {

                    $narr[] = ($rec[$aColumns[$i]] == "0") ? '<span class="label label-danger">&nbsp No &nbsp</span>' : '<span class="label label-success">&nbsp Yes &nbsp</span>'
                    ;
                    if ($rec[$aColumns[$i]] == "1") {
                        $narr[6] = '<span class="label label-success">&nbsp Yes &nbsp</span>';
                        $narr[8] = '<span class="label label-success">&nbsp Yes &nbsp</span>';
                    }
                } else if ($aColumns[$i] != ' ') {
                    $narr[] = $rec[$aColumns[$i]];
                }
            }




            echo '<tr>';
            $j = 1;

            for ($k = 0; $k < count($narr); $k++) {
                if ($k == 0)
                    echo '<td style="text-align: center;">' . $h . '</td>';
                else {
                    if ($k == 2)
                        echo '<td style="text-align: left;">' . (isset($narr[$j]) ? $narr[$j] : '') . '</td>';
                    else
                        echo '<td style="text-align: center;">' . (isset($narr[$j]) ? $narr[$j] : '') . '</td>';
                }

                $j++;
            }
            $h++;
            echo '</tr>';

            $i++;
        }
		
		
		
		
		
		
		
		
		

        echo'</tbody>
    			</table>
            </div>
		</div>  
 
</div>';

        echo '</body>
      </html>';
    }else {
        echo 'No record found';
    }
?>