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

    $monthId = $_REQUEST['MonthId'];   
    $year = $_REQUEST['Year'];        
    $countryId = $_REQUEST['CountryId'];
    $itemGroupId = $_REQUEST['ItemGroupId'];
    $itemNo = $_REQUEST['ItemNo'];
    $regionId = $_REQUEST['RegionId'];
    $fLevelId = $_REQUEST['FLevelId'];
    	
    $CountryName = $_REQUEST['CountryName'];  
    $MonthName = $_REQUEST['MonthName'];
    $ItemGroupName = $_REQUEST['ItemGroupName'];
    $ItemName = $_REQUEST['ItemName'];
    $RegionName = $_REQUEST['RegionName'];
    $FLevelName = $_REQUEST['FLevelName'];

	 $serial = "@rank:=@rank+1 AS SL";
	$sQuery = "SELECT SQL_CALC_FOUND_ROWS " . $serial . ",
				  b.FacilityId,
				  b.FacilityName,				  
				  b.ClStock,
				  b.AMC,
				  b.MOS,
				  `Latitude`, `Longitude`
				  FROM (
				SELECT
				  t_cfm_masterstockstatus.FacilityId,
				  t_facility.FacilityName,
				  `Latitude`, `Longitude`,
				  IFNULL(t_cfm_stockstatus.ClStock,0)    ClStock,
				  IFNULL(t_cfm_stockstatus.AMC,0)       AMC,
				  IFNULL(t_cfm_stockstatus.MOS,0)       MOS
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
				  NULL MOS
				FROM t_cfm_masterstockstatus
				  INNER JOIN t_facility
				    ON t_cfm_masterstockstatus.FacilityId = t_facility.FacilityId
				  INNER JOIN t_region
				    ON t_facility.RegionId = t_region.RegionId
				      AND t_region.CountryId = $countryId
				      AND (t_facility.FLevelId = $fLevelId OR $fLevelId=0)
				      AND (t_region.RegionId = $regionId OR $regionId=0)
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
				                  AND (r.RegionId = $regionId OR $regionId=0)) a
				    ON (t_cfm_masterstockstatus.FacilityId = a.FacilityId
				        AND t_cfm_masterstockstatus.MonthId = $monthId
				        AND t_cfm_masterstockstatus.Year = '$year'
				        AND t_cfm_masterstockstatus.CountryId = $countryId
				        AND t_cfm_masterstockstatus.ItemGroupId = $itemGroupId
				        AND t_cfm_masterstockstatus.StatusId = 5)
				WHERE t_cfm_masterstockstatus.FacilityId IS NULL) b
									WHERE 1=1
									$sWhere
									$sOrder
									$sLimit;";
    mysql_query("SET character_set_results=utf8");   
	$rResult = mysql_query($sQuery);
    $total = mysql_num_rows($rResult);
	$i=1;	
    
	if ($rResult){
		echo '<!DOCTYPE html>
			<html>
			<head>
			 <meta name="viewport" content="width=device-width, initial-scale=1.0" />	
			 <base href="http://localhost/warp/index.php/fr/adminfr/country-fr" />
			 <meta http-equiv="content-type" content="text/html; charset=utf-8" />
			 <meta name="generator" content="Joomla! - Open Source Content Management" />
			 <link rel="stylesheet" href="'.$jBaseUrl.'templates/protostar/css/template.css" type="text/css" /> 			  
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
            
			echo '  <div class="row"> 
                    <div class="panel panel-default table-responsive" id="grid_country">
                    <div class="padding-md clearfix">                  
                    <div class="panel-heading">
                    <h3 style="text-align:center;">'.$gTEXT['Stock Status at Facility Level'].' on '.$MonthName.' '.$Year.'<h3>
                        <h4 style="text-align:center;">'.$gTEXT['Country'].' : '.$CountryName.' , '.$gTEXT['Product Group'].' : '.$ItemGroupName.' , '.$gTEXT['Facility Level'].' : '.$FLevelName.'<h4>
                        <h5 style="text-align:center;">'.$gTEXT['Product Name'].' : '.$ItemName.' , '.$gTEXT['Region'].' : '.$RegionName.'<h5>
                    </div>	
    		
			
                    <table class="table table-striped display" id="gridDataCountry">
                        <thead>
                        </thead>
                        <tbody>
                        <tr>
                            <th style="text-align: center;">SL#</th>
                            <th style="text-align: left;">'.$gTEXT['Health Facility'].'</th> 
                            <th style="text-align: Right;">'.$gTEXT['Balance'].'</th> 
                            <th style="text-align: Right;">'.$gTEXT['AMC'].'</th>                           
                            <th style="text-align: Right;">'.$gTEXT['MOS'].'</th> 						        
		                </tr>';
                        
		while($rec=mysql_fetch_array($rResult)){  
            echo '<tr>
                        <td style="text-align: center;">'.$i.'</td>
                        <td style="text-align: left;">'.$rec['FacilityName'].'</td>
                        <td style="text-align: Right;">'.($rec['ClStock']==''? '':number_format($rec['ClStock'])).'</td>
                        <td style="text-align:Right;">'.($rec['AMC']==''? '':number_format($rec['AMC'])).'</td>
                        <td style="text-align: Right;">'.($rec['MOS']==''? '':number_format($rec['MOS'],1)).'</td>
                  </tr>';
            
            $i++; 
	}
        echo'   </thead>
                </table>
                </div>
                </div>  
                </div>';
        
        echo '  </body>
                </html>';	
    }else{
   	    echo 'No record found';
    }

	
?>