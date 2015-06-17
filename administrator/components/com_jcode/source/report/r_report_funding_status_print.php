<?php

include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());

$gTEXT = $TEXT;
$jBaseUrl = $_GET['jBaseUrl']; 
$ItemGroupId = $_GET['ItemGroupId']; 
$ItemGroup = $_GET['ItemGroup']; 
$tempGroupId='';
    if($ItemGroupId){
		$ItemGroupId = " AND g.ItemGroupId = '".$ItemGroupId."' ";
	}
    $Year = $_GET['Year'];    
    $CountryId = $_GET['Country']; 
	$CountryName=$_GET['CountryName'];   
	if(isset($_GET['Country'])&&!empty($_GET['Country'])){
		$countryQuery=" and p.CountryId='".$CountryId."' ";
	}else{
		$countryQuery="";
	}
	
	$lan=$_GET['lan']; 
    if($lan == 'fr-FR'){
		$aColumns = 'g.GroupNameFrench GroupName, f.FundingReqSourceNameFrench FundingReqSourceName';   
    }else{
        $aColumns = 'g.GroupName, f.FundingReqSourceName';   
    }
	 
	if($lan == 'en-GB'){
		$SITETITLE = SITETITLEENG;
	}else{
	   $SITETITLE = SITETITLEFRN;
	}     
	/*$sql="	SELECT g.GroupName,f.FormulationName,r.FundingReqId,r.ItemGroupId,r.Y1,r.Year,sum(p.TotalFund) Total from t_yearly_pledged_funding p
			Inner Join t_yearly_funding_requirements r on r.FormulationId=p.FormulationId and r.Year=p.Year and r.CountryId=p.CountryId
			Inner Join t_formulation f on f.FormulationId=r.FormulationId
			Inner Join t_itemgroup g on g.ItemGroupId =f.ItemGroupId 
			where p.Year='".$Year."' ".$countryQuery."
			group by g.GroupName,p.FormulationId ";*/
	$sql="	SELECT SQL_CALC_FOUND_ROWS $aColumns,r.FundingReqId,r.ItemGroupId,r.Y1,r.Year,sum(p.Y1) Total 
			from t_yearly_pledged_funding p
			Inner Join t_yearly_funding_requirements r 
				on r.FundingReqSourceId=p.FundingReqSourceId and r.Year=p.Year and r.CountryId=p.CountryId  and r.ItemGroupId = p.ItemGroupId
			Inner Join t_fundingreqsources f on f.FundingReqSourceId=r.FundingReqSourceId
			Inner Join t_itemgroup g on g.ItemGroupId =f.ItemGroupId 
			where p.Year='".$Year."' ".$countryQuery." ".$ItemGroupId."
			group by g.GroupName,p.FundingReqSourceId "; 
//echo $sql;
    mysql_query("SET character_set_results=utf8");			
	mysql_query("SET character_set_results=utf8");		
	$result = mysql_query($sql);
	$total = mysql_num_rows($result);
	$sQuery = "SELECT FOUND_ROWS()";
	$rResultFilterTotal = mysql_query($sQuery);
	$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];
        $sEcho = isset($_GET['sEcho'])? $_GET['sEcho'] : '';
        $iDisplayStart = isset($_GET['iDisplayStart'])? $_GET['iDisplayStart'] : '';
        
	$sOutput = '{';
	$sOutput .= '"sEcho": ' . intval($sEcho) . ', ';
	$sOutput .= '"iTotalRecords": ' . $iFilteredTotal . ', ';
	$sOutput .= '"iTotalDisplayRecords": ' . $iFilteredTotal . ', ';
	$sOutput .= '"aaData": [ ';
	$serial = $iDisplayStart + 1;
	$f = 0;
    
	$superGrandSubTotal=0;$superGrandSubTotalActual=0;
	$groupsubTmp=-1;$p=0;$q=0;$grandSubTotal=0;$grandSubTotalActual=0;$grandGapSurplus=0;
	$htm='';
	while ($aRow = mysql_fetch_array($result)) {

		//$ItemName = trim(preg_replace('/\s+/', ' ', addslashes($aRow['ItemName'])));
			
		if($p!=0&&$groupsubTmp!=$aRow['GroupName']){ 
		    
			$htm.='<tr>
	                  <td style="background-color:#ff962b;border-radius:2px;align:center;" colspan="2"> '.$groupsubTmp.' Total</td>
	               '; 
					   
			$htm.=' <td style="background-color:#ff962b;border-radius:2px;text-align:right;" >'.number_format($grandSubTotal).'</td>
	                    ';
			$htm.=' <td style="background-color:#ff962b;border-radius:2px;text-align:right;" >'.number_format($grandSubTotalActual).'</td>
	                   </tr>';   
			
			$superGrandSubTotal+=$grandSubTotal;
			$superGrandSubTotalActual+=$grandSubTotalActual;
			
			$grandSubTotal=0;
			$grandSubTotalActual=0;			
		}
		$groupsubTmp=$aRow['GroupName'];
		
		if ($f++)
			$sOutput .= ',';
		$sOutput .= "[";
		$sOutput .= '"' . $serial++ . '",';
		$sOutput .= '"' . $aRow['GroupName'] . '",';
        $sOutput .= '"' . $aRow['FundingReqSourceName'] . '",';
		$sOutput .= '"' . number_format($aRow['Y1']) . '",';
 	    $sOutput .= '"' . number_format($aRow['Total']) . '"';        
		$sOutput .= "]";
		$grandSubTotal+=$aRow['Y1'];
		$grandSubTotalActual+=$aRow['Total'];
		
		if($tempGroupId!=$aRow['GroupName']) 
		   {
			     	   
				   
		   	 $htm.='<tr>
                     <td style="background-color:#DAEF62;border-radius:2px;align:center;" colspan="4">'.$aRow['GroupName'].'</td>
                   </tr>';  
			   
			    $tempGroupId=$aRow['GroupName'];
				$Planned= 0;
		   }	
		$htm.='<tr>
		 	      <td style="text-align: left;">
			      '. $serial.'
			      </td>
			      <td style="text-align: left;">
			     '.$aRow['FundingReqSourceName'].'
			     </td>
			     <td style="text-align: right;">
			     '.number_format($aRow['Y1']).'
			     </td>
				<td style="text-align: right;">
			     '.number_format($aRow['Total']).'
			     </td>
			     </tr>
			     ';
		
		if($p==$total-1){
			$htm.='<tr>
	                 <td style="background-color:#ff962b;border-radius:2px;align:center;" colspan="2"> '.$groupsubTmp.' Total</td>'; 
			$htm.=' <td style="background-color:#ff962b;border-radius:2px;text-align:right;" >'.number_format($grandSubTotal).'</td>';
			$htm.=' <td style="background-color:#ff962b;border-radius:2px;text-align:right;" >'.number_format($grandSubTotalActual).'</td>
	               </tr>'; 
			
			$superGrandSubTotal+=$grandSubTotal;
			$superGrandSubTotalActual+=$grandSubTotalActual;
			
			$grandSubTotal=0;
			$grandSubTotalActual=0; 
			 	
			 $htm.='<tr>
	                  <td style="background-color:#52a8ee; color:#ffffff;border-radius:2px;align:center;" colspan="2">Grand Total</td>'; 
			 $htm.='<td style="background-color:#52a8ee; color:#ffffff;border-radius:2px;text-align:right;" >'.number_format($superGrandSubTotal).'</td>';
			 $htm.='<td style="background-color:#52a8ee; color:#ffffff;border-radius:2px;text-align:right;" >'.number_format($superGrandSubTotalActual).'</td>
	                 </tr>'; 		

		}
		$p++;$q++;
	}
	 
	if ($total>0)
		
	{
		
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
			<link href="'.$jBaseUrl.'/administrator/components/com_jcode/source/css/custom.css" rel="stylesheet"/>
			
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
			// $svgfilePath='pdfslice/Funding Status_11_1_2015_13_53_14.svg';
			echo '<div class="row" style="padding: 0 30px; margin:0 auto;"> 
	      <div class="panel panel-default table-responsive" id="grid_country">
           <div class="padding-md clearfix">
           	<div class="panel-heading">
			  <h2 style="text-align:center;">'.$SITETITLE.'<h2>
              <h3 style="text-align:center;">'.$gTEXT['Funding Status'].'<h3>
              <h4 style="text-align:center;">'.$CountryName.' - '.$ItemGroup.' - '.$Year.' <h4>
            </div>';
            /*
			echo '<div class="panel panel-default">
						<div class="panel-body">
							<div id="barchart-container">
							
								<div id="bar-chart" width="100%">
								<img src="'.$svgfilePath.'" width="100%" height="auto">								
								</div>
							</div>
						</div>           
					</div>';
	            */		
					
    			echo '<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    				</thead>
    				<tbody>
    					<tr>
						    <th style="text-align: left;">SL#</th>
						    <th style="text-align: left;">'.$gTEXT['Category'].'</th>
						    <th style="text-align: right;">'.$gTEXT['Requirements (USD)'].'</th>
						    <th style="text-align: right;">'.$gTEXT['Committed (USD)'].'</th>
		                </tr>';
			 
			echo $htm;
		 
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