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

$CountryName = $_GET['SelCountryName'];
$CountryId=$_GET['SelCountryId'];
$ShowSelected = $_GET['ShowSelected'];
$ItemGroupId = $_GET['ItemGroupId'];
$tempGroupId = '';

   
$sWhere = "";
	if ($_GET['sSearch'] != "") {
		
		 $sSearch=str_replace("|","+", $_GET['sSearch']);
	 
		$sWhere = " WHERE (RegimenName LIKE '%" . mysql_real_escape_string($sSearch) . "%') ";
    }
	    
   
 if ($ShowSelected == 'false') {
        $sql = " SELECT SQL_CALC_FOUND_ROWS a.CountryRegimenId, a.CountryId, b.RegimenId, IF(a.CountryRegimenId is Null,'false','true') chkValue, RegimenName, FormulationName  	 	
                 FROM  t_country_regimen a 
                 RIGHT JOIN t_regimen b ON (a.RegimenId = b.RegimenId AND a.CountryId = '".$CountryId."')
                 INNER JOIN t_formulation c ON b.FormulationId = c.FormulationId AND c.ItemGroupId = '".$ItemGroupId."' 
                 ".$sWhere." ORDER BY FormulationName, RegimenName";
    } else {
        $sql = " SELECT SQL_CALC_FOUND_ROWS a.CountryRegimenId, a.CountryId, b.RegimenId, IF(a.CountryRegimenId is Null,'false','true') chkValue, RegimenName, FormulationName
                 FROM  t_country_regimen a 
                 INNER JOIN t_regimen b ON (a.RegimenId = b.RegimenId AND a.CountryId = '".$CountryId."')
                 INNER JOIN t_formulation c ON b.FormulationId = c.FormulationId AND c.ItemGroupId = '".$ItemGroupId."'
                 ".$sWhere."ORDER BY FormulationName, RegimenName ";
    }  
	
	 mysql_query("SET character_set_results=utf8"); 			 
    $r= mysql_query($sql) ;
	$i=1;	
	$total = mysql_num_rows($r);
    
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
			echo '<div class="row"> 
	       <div class="panel panel-default table-responsive" id="grid_country">
           	<div class="padding-md clearfix">
           	<div class="panel-heading">
			  <h2 style="text-align:center;">'.$SITETITLE.'</h2>
              <h3 style="text-align:center;">'.$gTEXT['Country Patient of'].' '.$CountryName.'<h3>
            </div>	
    			<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    				</thead>
    				<tbody>
    					<tr>
    					    <th width="30" align="center">SL#</th>
						    <th width="30" align="center">'.$gTEXT['Regimen Name'].'</th>
						    
		                </tr>';
		while($rec=mysql_fetch_array($r))
		{
			
			if($tempGroupId!=$rec['FormulationName']) 
		   {
		   	 	echo'<tr >
                     <td style="background-color:#DAEF62;border-radius:2px;  align:center; font-size:14px;" colspan="3">'.$rec['FormulationName'].'</td>
                   </tr>'; 
			   $tempGroupId=$rec['FormulationName'];
		  
		    }
			echo '<tr>
			         <td width="30" align="center">'.$i.'</td>
					 <td width="30" align="center">'.$rec['RegimenName'].'</td>
	               
		    	</tr>
			     ';
				 
				 $i++; 
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
		
/*function getcheckBox($v){ 
    if ($v == "true") {
        $x="<input type='checkbox' checked class='datacell' value='".$v."' disabled/><span class='custom-checkbox'></span>";
    } else {
        $x="<input type='checkbox' class='datacell' value='".$v."' disabled/><span class='custom-checkbox'></span>";
    } 
    return $x;
}*/	
	
?>