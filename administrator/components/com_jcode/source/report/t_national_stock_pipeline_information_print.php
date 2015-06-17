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
   
  $monthId=$_GET['MonthId']; 
  $year=$_GET['YearId']; 
  $countryId=$_GET['CountryId'];
  $itemGroupId=$_GET['ItemGroupId'];
  $CountryName=$_GET['CountryName'];   
  $MonthName = $_GET['MonthName'];
  $ItemGroupName = $_GET['ItemGroupName'];
  
   $sWhere = "";
	if ($_GET['sSearch'] != "") {
		 $sSearch=str_replace("|","+", $_GET['sSearch']);
		$sWhere = " WHERE (a.ItemName LIKE '%" . mysql_real_escape_string($sSearch) . "%'
        OR " . " a.AMC LIKE '%" . mysql_real_escape_string($sSearch) . "%'
        OR " . " a.ClStock LIKE '%" . mysql_real_escape_string($sSearch) . "%' 
        OR " . " a.MOS LIKE '%" . mysql_real_escape_string($sSearch) . "%'
        OR " . " b.Qty LIKE '%" . mysql_real_escape_string($sSearch) . "%' 
        )";							
	}
        
    $sLimit = "";
	if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
		$sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
	}
    $sOrder = "";
	if (isset($_GET['iSortCol_0'])) { $sOrder = " ORDER BY  ";
		for ($i = 0; $i < mysql_real_escape_string($_GET['iSortingCols']); $i++) {
			$sOrder .= fnColumnToField_Item(mysql_real_escape_string($_GET['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
		}
		$sOrder = substr_replace($sOrder, "", -2);
	}
    
	$currentYearMonth = $_GET['YearId'] . "-" . $_GET['MonthId'] . "-" . "01";
	
	$monthList = array('1'=>'January','2'=>'February','3'=>'March','4'=>'April','5'=>'May','6'=>'June','7'=>'July','8'=>'August','9'=>'September','10'=>'October','11'=>'November','12'=>'December');
	
    
    
	
	$sQuery = "SELECT ItemName, IFNULL(AMC,0) AMC, IFNULL(ClStock,0) ClStock, IFNULL(MOS,0) MOS, IFNULL(Qty,0) StockOnOrder FROM 
				(SELECT
				 t_cnm_masterstockstatus.CountryId,
				  t_itemlist.ItemNo,
				  t_itemlist.ItemName,
				  SUM(t_cnm_stockstatus.AMC)    AMC,
				  SUM(t_cnm_stockstatus.ClStock)    ClStock,
				  SUM(t_cnm_stockstatus.MOS)    MOS
				FROM t_cnm_stockstatus
				  INNER JOIN t_cnm_masterstockstatus
				    ON (t_cnm_stockstatus.CNMStockId = t_cnm_masterstockstatus.CNMStockId)
				  INNER JOIN t_itemlist
				    ON (t_cnm_stockstatus.ItemNo = t_itemlist.ItemNo)
				WHERE (t_cnm_masterstockstatus.Year = '$year'
				       AND t_cnm_masterstockstatus.MonthId = $monthId
				       AND t_cnm_masterstockstatus.CountryId = $countryId
				       AND t_cnm_masterstockstatus.ItemGroupId = $itemGroupId
				       AND t_cnm_masterstockstatus.StatusId = 5)
				GROUP BY t_cnm_masterstockstatus.CountryId, t_itemlist.ItemNo, t_itemlist.ItemName) a 
				LEFT JOIN (SELECT
				    CountryId
				    , ItemNo
				    , SUM(Qty) Qty
				FROM
				    t_agencyshipment
				WHERE (ShipmentDate > CAST('$currentYearMonth' AS DATETIME)  AND ShipmentStatusId = 2)
				GROUP BY CountryId, ItemNo) b
				ON a.CountryId = b.CountryId AND a.ItemNo = b.ItemNo
                ".$sWhere."
				HAVING AMC>0 OR MOS>0 OR ClStock>0 OR StockOnOrder>0
                 order by ItemName
                $sLimit";
         // echo  $sQuery;    
     mysql_query("SET character_set_results=utf8");             
	$rResult = mysql_query($sQuery);
	$total = mysql_num_rows($rResult);	
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
              <h3 style="text-align:center;">'.$gTEXT['National Stock Pipeline Information List'].'<h3>
            </div>
               <div class="clearfix">
	            		<h4 style="text-align:center;">'.$gTEXT['Country Name'].': '. $CountryName.'   ,   '.$gTEXT['Product Group'].': '. $ItemGroupName.' <h4>
						<h4 style="text-align:center;">'.$gTEXT['Month'].': '. $MonthName.'   ,   '.$gTEXT['Year'].': '. $year.'<h4>
				</div> 	
    			<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    				</thead>
    				<tbody>
    		               <tr>	
    						    <th>SL#</th>	 
								<th >'.$gTEXT['Products'].'</th>
								<th style="text-align:right;">'.$gTEXT['AMC'].'</th>
								<th style="text-align:right;">'.$gTEXT['Available Stock'].'</th>
								<th style="text-align:right;">'.$gTEXT['MOS(Available)'].'</th>
								<th style="text-align:right;">'.$gTEXT['Stock on Order'].'</th>
								<th style="text-align:right;">'.$gTEXT['MOS(pipeline)'].'</th>
								<th style="text-align:right;">'.$gTEXT['Total MOS'].'</th>
							</tr>';
				
			
            	
            
				
		   while($rec=mysql_fetch_array($rResult)){
		   	 $amc = ($rec['AMC'] == 0? 1 : $rec['AMC']);	
			 $stockOnOrderMOS =  $rec['StockOnOrder'] / $amc;	
             $stockOnOrderMOS = $stockOnOrderMOS== 0? '' : number_format($stockOnOrderMOS,1);
             $totalMOS = number_format((number_format($rec['MOS'],1) + $stockOnOrderMOS),1) ;
             $totalMOS = $totalMOS== 0? '' : $totalMOS;
                                	echo '<tr>
                                             <td style="text-align: center;">'.$i.'</td>
                                             <td style="text-align:left;">'.$rec['ItemName'].'</td>
                                	         <td style="text-align:right;">'.($rec['AMC']==''? '':number_format($rec['AMC'])).'</td>
                                             <td style="text-align:right;">'.($rec['ClStock']==''? '':number_format($rec['ClStock'])).'</td>
                                	         <td style="text-align:right;">'.($rec['MOS']==''? '':number_format($rec['MOS'],1)).'</td>
                                	         <td style="text-align:right;">'. ($rec['StockOnOrder']== 0? '' : $rec['StockOnOrder']).'</td>
                                	         <td style="text-align:right;">'.$stockOnOrderMOS.'</td>
                                	         <td style="text-align:right;">'.$totalMOS.'</td>
                                         </tr>';
                                    $i++; 
                                }
	 echo '</tbody></table></div></div></div><br/>';
     
	 echo'</tbody>';
	 echo $tbody;
	 echo'</tbody>    				
    			</table>
            </div>
		</div>  
     </div>';
		
	
     echo '</body>
      </html>';	
     }	else{
			echo "No record found.";	
			
	}
	
?>