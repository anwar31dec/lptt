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
  $year=$_GET['Year']; 
  $countryId=$_GET['CountryId'];
  $itemGroupId=$_GET['ItemGroupId'];
  $CountryName=$_GET['CountryName'];   
  $MonthName = $_GET['MonthName'];
  $ItemGroupName = $_GET['ItemGroupName'];
  function numberToMonth($i) {
	$i=trim($i);
	if ($i == 1)
		return "Jan ";
	else if ($i == 2)
		return "Feb";
  	else if ($i == 3)
		return "Mar ";
   	else if ($i == 4)
		return "Apr ";
	else if ($i == 5)
		return "May ";
   	else if ($i == 6)
		return "Jun ";
	else if ($i == 7)
		return "Jul ";
	else if ($i == 8)
		return "Aug ";
		else if ($i == 9)
		return "Sep ";
		else if ($i == 10)
		return "Oct ";
		else if ($i == 11)
		return "Nov ";
		else if ($i == 12)
		return "Dec ";
			
		
}
	
   $sWhere = "";
	if ($_GET['sSearch'] != "") {
		$sWhere = " WHERE (a.ItemName LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%'
        OR " . " a.AMC LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%'
        OR " . " a.ClStock LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' 
        OR " . " a.MOS LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%'
        OR " . " b.Qty LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' 
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
    
	$currentYearMonth = $_GET['Year'] . "-" . $_GET['MonthId'] . "-" . "01";
	
	$monthList = array('1'=>'January','2'=>'February','3'=>'March','4'=>'April','5'=>'May','6'=>'June','7'=>'July','8'=>'August','9'=>'September','10'=>'October','11'=>'November','12'=>'December');
	
 	$sQuery =" SELECT ItemName, AMC, ClStock, FORMAT(MOS,1) MOS, Qty StockOnOrder, FORMAT(Qty/AMC,1) StockOnOrderMOS, (ifnull(FORMAT(MOS,1),0)+ifnull(FORMAT(Qty/AMC,1),0)) TotalMOS
				,a.ItemNo,TotalPatient
				 FROM 
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
				       AND t_cnm_masterstockstatus.MonthId =$monthId
				       AND t_cnm_masterstockstatus.CountryId = $countryId
				       AND t_cnm_masterstockstatus.ItemGroupId =$itemGroupId
				       AND t_cnm_masterstockstatus.StatusId = 5)
				GROUP BY t_cnm_masterstockstatus.CountryId, t_itemlist.ItemNo, t_itemlist.ItemName) a 
				LEFT JOIN (SELECT
				    CountryId
				    , ItemNo
				    , SUM(Qty) Qty
				FROM
				    t_agencyshipment
				WHERE (ShipmentDate > CAST('$currentYearMonth' AS DATETIME)  AND ShipmentStatusId = 3)
				GROUP BY CountryId, ItemNo) b
				ON a.CountryId = b.CountryId AND a.ItemNo = b.ItemNo
				LEFT JOIN (SELECT t_cnm_regimenpatient.CountryId,ItemNo,sum(TotalPatient) as TotalPatient
				from t_cnm_regimenpatient
				Inner Join t_regimenitems ON t_cnm_regimenpatient.RegimenId=t_regimenitems.RegimenId
				Group By t_cnm_regimenpatient.CountryId,ItemNo) c ON a.CountryId = c.CountryId AND a.ItemNo = c.ItemNo
				 ".$sWhere."
				HAVING MOS>0 OR StockOnOrderMOS>0 
				 $sOrder
                $sLimit order by ItemNo,ItemName"; 
	
                 
	$rResult = mysql_query($sQuery);	
	$i=1;	
	if ($rResult)
		
	{
		echo '<!DOCTYPE html>
			<html>
			<head>
			<meta http-equiv="content-type" content="text/html; charset=utf-8" />
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
              <h3 style="text-align:center;">'.$gTEXT['Number of Patients by Product'].'<h3>
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
								<th style="text-align:right;">'.$gTEXT['Total Patients'].'</th>
								<th style="text-align:right;">'.$gTEXT['Available Stock'].'</th>
								<th style="text-align:right;">'.$gTEXT['MOS(Available)'].'</th>
								<th style="text-align:right;">'.$gTEXT['Stock on Order'].'</th>
								<th style="text-align:right;">'.$gTEXT['MOS(Ordered)'].'</th>
								<th style="text-align:right;">'.$gTEXT['Total MOS'].'</th>
								<th style="text-align:right;">'.$gTEXT['Projected Date'].'</th>
							</tr>';
				
			
				
		   while($rec=mysql_fetch_array($rResult)){
		   	                                          
						 $addmonth=number_format($rec['TotalMOS']);  							  
					     $currentYearMonth = $year . "-" . $monthId . "-" . "01";			
						 $lastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "$addmonth month"));
						 
						 $temp=explode('-',$lastYearMonth);
						 $strMonth=numberToMonth($temp[1]);
						 $lastYearMonth=$strMonth.'  , '.$temp[0];
	 		    	  		                       
                                	echo '<tr>
                                             <td style="text-align: center;">'.$i.'</td>
                                             <td style="text-align:left;">'.$rec['ItemName'].'</td>
                                	         <td style="text-align:right;">'.($rec['TotalPatient']==''? '':number_format($rec['TotalPatient'])).'</td>
                                             <td style="text-align:right;">'.($rec['ClStock']==''? '':number_format($rec['ClStock'])).'</td>
                                	         <td style="text-align:right;">'.($rec['MOS']==''? '':number_format($rec['MOS'],1)).'</td>
                                	         <td style="text-align:right;">'.($rec['StockOnOrder']==''? '0':number_format($rec['StockOnOrder'])).'</td>
                                	         <td style="text-align:right;">'.($rec['StockOnOrderMOS']==''? '0.0':number_format($rec['StockOnOrderMOS'],1)).'</td>
                                	         <td style="text-align:right;">'.($rec['TotalMOS']==''? '':number_format($rec['TotalMOS'],1)).'</td>
                                	         <td style="text-align:right;">'.$lastYearMonth.'</td>
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
    }		
	else
	{
		
	$error = 0;	
		echo $error;
	}
	
?>