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

    $MonthId=$_REQUEST['MonthId']; 
	$YearId=$_REQUEST['YearId'];
    $mosTypeId = $_REQUEST['MosTypeId'];
	$countryId = $_REQUEST['CountryId'];
	$fLevelId = $_REQUEST['FLevelId'];
    $FacilityId=$_GET['FacilityId'];
    $ItemGroupId = $_GET['ItemGroupId'];
    $year = $_REQUEST['Year'];
    $CountryName = $_REQUEST['CountryName'];
    $monthName = $_REQUEST['MonthName'];
    $ItemGroupName = $_REQUEST['ItemGroupName'];
    $FacilityName = $_REQUEST['FacilityName'];
	
    $regionId = $_REQUEST['RegionId'];
    $RegionName = $_REQUEST['RegionName'];
    $districtId = $_REQUEST['DistrictId'];
    $DistrictName = $_REQUEST['DistrictName'];
    $ownerTypeId = $_REQUEST['OwnerTypeId'];
    $OwnerTypeName = $_REQUEST['OwnerTypeName'];
    $lan = $_REQUEST['lan'];
	$column_name = array();
	
	if($lan == 'en-GB'){
            $mosTypeName = 'MosTypeName';
        }else{
            $mosTypeName = 'MosTypeNameFrench';
        }     
	$sQuery =  "SELECT
			    MosTypeId
			    , $mosTypeName MosTypeName
			    , ColorCode
				FROM
				    t_mostype_facility
				WHERE CountryId = $countryId AND FLevelId = $fLevelId  AND (MosTypeId = $mosTypeId OR $mosTypeId = 0)
				ORDER BY MosTypeId;";
mysql_query("SET character_set_results=utf8");
   $rResult = mysql_query($sQuery);
   $output = array();
   
   $dQuery  = "SELECT p.MosTypeId, ItemName, MOS ,ClStock,AMC FROM (SELECT
				    a.ItemNo, b.ItemName, a.MOS ,a.ClStock,a.AMC
					,(SELECT MosTypeId FROM t_mostype_facility x WHERE CountryId = $countryId AND FLevelId = $fLevelId  AND (MosTypeId = $mosTypeId OR $mosTypeId = 0) AND a.MOS >= x.MinMos AND a.MOS < x.MaxMos) MosTypeId
					FROM t_cfm_stockstatus a, t_itemlist b,  t_cfm_masterstockstatus c
					WHERE a.itemno = b.itemno AND a.MOS IS NOT NULL AND a.MonthId = " . $_REQUEST['MonthId'] . " AND a.Year = '" . $_REQUEST['YearId'] . "' AND a.CountryId = " . $_REQUEST['CountryId'] . " AND a.FacilityId = " . $_REQUEST['FacilityId'] . " AND a.ItemGroupId = " . $_REQUEST['ItemGroupId'] . " AND a.CFMStockId = c.CFMStockId" . " AND c.StatusId = 5 " . ") p
					WHERE (p.MosTypeId = $mosTypeId OR $mosTypeId = 0) 
					ORDER BY ItemName";
mysql_query("SET character_set_results=utf8");
	$dResult = mysql_query($dQuery);
	$aData = array();
   
   
    $total = mysql_num_rows($dResult);
   	$i=1;		
	if($total>0){	
		
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
			<div class="panel panel-default table-responsive" id="grid_country">
            <div class="padding-md clearfix">
           	<div class="panel-heading">
           	<h3 style="text-align:center;">'.$gTEXT['Facility Inventory Control'].'  '.$CountryName.' '.$gTEXT['on'].' '.$monthName.', '.$year.'<h3>
		    </div>
		     <div class="clearfix">
	            		<h4 style="text-align:center;">'.$gTEXT['Region'].':  '.$RegionName.'   ,   '.$gTEXT['District'].': '. $DistrictName.'<h4>
						<h4 style="text-align:center;">'.$gTEXT['Owner Type'].': '.$OwnerTypeName.'   ,   '.$gTEXT['Facility'].': '.$FacilityName.'<h4>
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

		
	while ($row = mysql_fetch_array($dResult)) {
		
	     {
		 	
			echo '<tr>
			           <td>'.$row['ItemName'].'</td>
			           <td>'.$row['ClStock'].'</td>
			           <td>'.$row['AMC'].'</td>
			         <td> '.number_format($row['MOS'],1).'</td>  
			     ';
		 		
			foreach ($output1 as $rowMosType) {
			if ($rowMosType['MosTypeId'] == $row['MosTypeId']) {
				$tmpRow[] = '<i class="fa fa-check-circle fa-lg" style="color:' . $rowMosType['ColorCode'] . ';font-size:2.5em;"></i>';
				echo '<td><span class="fa fa-check-circle fa-lg" style="color:' . $rowMosType['ColorCode'] . ';font-size:2.5em;text-align:center;"></span></td>';
				
			 } else
				echo '<td> </td>';
		     }
		     
			 
			 echo ' </tr>';
		}
	
		 
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