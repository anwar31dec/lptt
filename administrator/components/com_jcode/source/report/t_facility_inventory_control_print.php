<?php
include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());

	$gTEXT = $TEXT; 
	$jBaseUrl = $_GET['jBaseUrl'];
	mysql_query("SET character_set_results=utf8");
   $MonthId=$_REQUEST['MonthId']; 
	$YearId=$_REQUEST['YearId'];
    $mosTypeId = $_REQUEST['MosTypeId'];
	$countryId = $_REQUEST['CountryId'];
	$fLevelId = $_REQUEST['FLevelId'];
    $FacilityId=$_REQUEST['FacilityId'];
    $ItemGroupId = $_REQUEST['ItemGroupId'];
	
	$regionId = $_REQUEST['RegionId'];
    $districtId = $_REQUEST['DistrictId'];
    $ownerTypeId = $_REQUEST['OwnerTypeId'];
    $region = $_REQUEST['Region'];
    $district = $_REQUEST['District'];
    $ownerType = $_REQUEST['OwnerType'];
	
	$year = $_REQUEST['Year'];
    $CountryName = $_REQUEST['CountryName'];
    $monthName = $_REQUEST['MonthName'];
    $ItemGroupName = $_REQUEST['ItemGroupName'];
    $FacilityName = $_REQUEST['FacilityName'];
	
	$lan=$_REQUEST['lan']; 
	if($lan == 'en-GB'){
		$SITETITLE = SITETITLEENG;
	}else{
	   $SITETITLE = SITETITLEFRN;
	} 

	$sQuery = "SELECT
			    MosTypeId
			    , MosTypeName
			    , ColorCode
			FROM
			    t_mostype_facility
			WHERE CountryId = $countryId AND FLevelId = $fLevelId  AND (MosTypeId = $mosTypeId OR $mosTypeId = 0)
			ORDER BY MosTypeId;"; 
			 
	mysql_query("SET character_set_results=utf8");		
	$rResult = mysql_query($sQuery);
	$output = array();

 
    $total = mysql_num_rows($rResult);
	$i = 1;	
    
	if($total>0){	
	
		echo '<!DOCTYPE html>
					<html>
					<head>
					<meta name="viewport" content="width=device-width, initial-scale=1.0" />	
					<base href="http://localhost/warp/index.php/fr/adminfr/country-fr" />
					<meta http-equiv="content-type" content="text/html; charset=utf-8" />
					<meta name="generator" content="Joomla! - Open Source Content Management" />
					<link rel="stylesheet" href="'.$jBaseUrl.'templates/protostar/css/template.css" type="text/css" /> 
					<link href="'.$jBaseUrl.'administrator/components/com_jcode/source/report/tcpdf/css/bootstrap.min.css" rel="stylesheet">
					<link href="'.$jBaseUrl.'administrator/components/com_jcode/source/report/tcpdf/css/font-awesome.min.css" rel="stylesheet">
					<link href="'.$jBaseUrl.'templates/protostar/endless/css/pace.css" rel="stylesheet">	
					<link href="'.$jBaseUrl.'templates/protostar/endless/css/colorbox/colorbox.css" rel="stylesheet">
					<link href="'.$jBaseUrl.'templates/protostar/endless/css/morris.css" rel="stylesheet"/> 	
					<link href="'.$jBaseUrl.'templates/protostar/endless/css/endless.min.css" rel="stylesheet"> 
					<link href="'.$jBaseUrl.'templates/protostar/endless/css/endless-skin.css" rel="stylesheet">
					
					<link href="'.$jBaseUrl.'templates/protostar/endless/bootstrap/css/font-halflings.css" rel="stylesheet">
					
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
			echo'
			<div class="row"> 
			<div style="padding:30px; margin: 0 auto;" class="panel panel-default table-responsive" id="grid_country">
            <div class="padding-md clearfix">
           	<div class="">
			<h2 style="text-align:center;">'.$SITETITLE.' <h2>
           	<h3 style="text-align:center;">'.$gTEXT['Facility Inventory Control'].' '.$gTEXT['on'].' '.$monthName.', '.$YearId.'<h3>
		    </div>
		     <div class="clearfix">	            		
	            		<h4 style="text-align:center;">'.$gTEXT['Country Name'].' : '.$CountryName.'   ,   '.$gTEXT['Region'].' : '.$region.'   ,   '.$gTEXT['District'].' : '.$district.' <h4>
						<h4 style="text-align:center;">'.$gTEXT['Product Group'].' : '.$ItemGroupName.'   ,   '.$gTEXT['Report By'].' : '.$ownerType.'  ,   '.$gTEXT['Facility'].' : '.$FacilityName.'<h4>
				</div>
    			<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    					<tr>
                        <th>'.$gTEXT['Product Name'].'</th> 
						<th>'.$gTEXT['Closing Balance'].'</th>
						<th>'.$gTEXT['AMC'].'</th>
						<th>'.$gTEXT['MOS'].'</th>';
		  while ($row = mysql_fetch_array($rResult)) {
		    echo '<th "sClass":"center-aln">'.$row['MosTypeName'].'</th>';
			$tmpRow['sTitle'] =$row['MosTypeName'] ;
			$tmpRow['sClass'] = 'center-aln';
			$output1[] = $row;
		}

		echo ' </tr>';

					
if($ownerTypeId == 1 || $ownerTypeId == 2){
        $sQuery = "SELECT p.MosTypeId, ItemName, MOS ,ClStock,AMC FROM (SELECT
				    a.ItemNo, b.ItemName, a.MOS ,a.ClStock,a.AMC
				,(SELECT MosTypeId FROM t_mostype_facility x WHERE CountryId = $countryId 
                AND FLevelId = $fLevelId  AND (MosTypeId = $mosTypeId OR $mosTypeId = 0) 
                AND IFNULL(a.MOS,0) >= x.MinMos AND IFNULL(a.MOS,0) < x.MaxMos ) MosTypeId
				FROM t_cfm_stockstatus a, t_itemlist b,  t_cfm_masterstockstatus c, t_facility g
				WHERE a.itemno = b.itemno AND a.MonthId = " . $MonthId . " 
                AND a.Year = '" . $YearId . "' AND a.CountryId = " . $countryId . " 
                AND a.FacilityId = " . $FacilityId . " AND a.ItemGroupId = " . $ItemGroupId . "
                AND a.CFMStockId = c.CFMStockId" . " AND c.StatusId = 5 " . "
                AND a.FacilityId=g.FacilityId 
                AND g.OwnerTypeId = $ownerTypeId 
                AND  (g.RegionId = $regionId OR $regionId = 0)
                AND (g.DistrictId = $districtId OR $districtId = 0)
                 ) p
                
				WHERE (p.MosTypeId = $mosTypeId OR $mosTypeId = 0) 
				ORDER BY ItemName";
     }else{
        $sQuery = "SELECT p.MosTypeId, ItemName, MOS ,ClStock,AMC FROM (SELECT
				    a.ItemNo, b.ItemName, a.MOS ,a.ClStock,a.AMC
				,(SELECT MosTypeId FROM t_mostype_facility x WHERE CountryId = $countryId 
                AND FLevelId = $fLevelId  AND (MosTypeId = $mosTypeId OR $mosTypeId = 0) 
                AND IFNULL(a.MOS,0) >= x.MinMos AND IFNULL(a.MOS,0) < x.MaxMos ) MosTypeId
				FROM t_cfm_stockstatus a, t_itemlist b,  t_cfm_masterstockstatus c, t_facility g
				WHERE a.itemno = b.itemno  AND a.MonthId = " . $MonthId . " 
                AND a.Year = '" . $YearId . "' AND a.CountryId = " . $countryId . " 
                AND a.FacilityId = " . $FacilityId . " AND a.ItemGroupId = " . $ItemGroupId . "
                AND a.CFMStockId = c.CFMStockId" . " AND c.StatusId = 5 " . "
                AND a.FacilityId=g.FacilityId
                AND g.AgentType = $ownerTypeId 
                AND  (g.RegionId = $regionId OR $regionId = 0)
                AND (g.DistrictId = $districtId OR $districtId = 0) ) p
                
				WHERE (p.MosTypeId = $mosTypeId OR $mosTypeId = 0) 
				ORDER BY ItemName";
     }	 
	// echo $sQuery;
	$rResult = mysql_query($sQuery);
						$aData = array();

	while ($row = mysql_fetch_array($rResult)) {
		 	$mos = '';
			$ClStock = '';
			$AMC = '';
			$color='';
			foreach ($output1 as $rowMosType) {
			
			if ($rowMosType['MosTypeId'] == $row['MosTypeId'] && $row['MOS']!='') {
				$color.= '<td><span class="fa fa-check-circle fa-lg" style="color:' . $rowMosType['ColorCode'] . ';font-size:2.5em;text-align:center;"></span></td>';
				$mos = number_format($row['MOS'],1);
			
			 } else
				$color.= '<td> </td>';
		     }
			 
			if($row['ClStock'] != '')
				$ClStock = number_format($row['ClStock']);
			if($row['AMC'] != '')
				$AMC = number_format($row['AMC']);
			echo '<tr>
			           <td>'.$row['ItemName'].'</td>
			         <td> '.$ClStock.'</td>  
			         <td> '.$AMC.'</td>  
			         <td> '. $mos .'</td> '.$color.' 
			     ';
		 		
			
		     
			 
			 echo ' </tr>';
		
	
		 
	}
	

	echo'</thead>
			
		</table>
    </div>
</div>  
 	 
</div>';
		 	
     echo '</body>
      </html>';	
    }else{
   	    echo 'No record found';
    }

	

?>