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

    $CountryId=$_GET['CountryId']; 
	$FacilityId=$_GET['FacilityId']; 
    $MonthId=$_GET['MonthId']; 
	$YearId=$_GET['YearId'];
    $ItemGroupId=$_GET['ItemGroupId'];
    $mosTypeId = $_REQUEST['MosTypeId'];
    $CountryName=$_GET['CountryName'];   
    $MonthName = $_GET['MonthName'];
    $ItemGroupName = $_GET['ItemGroupName'];
	// $ownerTypeId = $_REQUEST['OwnerTypeId'];
    // $OwnerTypeName = $_REQUEST['OwnerTypeName'];  
    $lan = $_REQUEST['lan'];
	//$column_name = array();   if($lan == 'en-GB'){
   	
            $mosTypeName = 'MosTypeName';
        }else{
            $mosTypeName = 'MosTypeNameFrench';
        }   
    

	$sQuery = "SELECT
			    MosTypeId
			    , $mosTypeName MosTypeName
			    , ColorCode
			FROM
			    t_mostype
			WHERE (MosTypeId = $mosTypeId OR $mosTypeId = 0)
			ORDER BY MosTypeId;";


	$rResult = mysql_query($sQuery);
	$output = array();
	
	$dQuery = "SELECT p.MosTypeId, ItemName, MOS FROM (SELECT
				    a.ItemNo
				    , b.ItemName
				    , a.MOS
					,(SELECT MosTypeId FROM t_mostype x WHERE (MosTypeId = $mosTypeId OR $mosTypeId = 0) AND a.MOS >= x.MinMos AND a.MOS < x.MaxMos) MosTypeId
					FROM t_cnm_stockstatus a, t_itemlist b,  t_cnm_masterstockstatus c
					WHERE a.itemno = b.itemno AND a.MOS IS NOT NULL AND a.MonthId = " . $_REQUEST['MonthId'] . " AND a.Year = '" . $_REQUEST['YearId'] . "' AND a.CountryId = " . $_REQUEST['CountryId'] . " AND a.ItemGroupId = " . $_REQUEST['ItemGroupId'] . " AND a.CNMStockId = c.CNMStockId" . " AND c.StatusId = 5 " . ") p
					WHERE (p.MosTypeId = $mosTypeId OR $mosTypeId = 0) 
					ORDER BY ItemName";
					
	mysql_query("SET character_set_results=utf8");
						$dResult = mysql_query($dQuery);
						$aData = array();
	
	
	$total = mysql_num_rows($dResult);
     
    if ($total>0){	
		
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
           	<h3 style="text-align:center;">'.$gTEXT['National Inventory Control'].'<h3>
		    </div>
		     <div class="clearfix">
	            		<h4 style="text-align:center;">'.$gTEXT['Country Name'].':'.$CountryName.'   ,   '.$gTEXT['Product Group'].':'.$ItemGroupName.' <h4>
						<h4 style="text-align:center;">'.$gTEXT['Month'].':'.$MonthName.'   ,   '.$gTEXT['Year'].':'.$YearId.'<h4>
				</div> 	
    			<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    					<tr>
                        <th>'.$gTEXT['Product Name'].'</th> 
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