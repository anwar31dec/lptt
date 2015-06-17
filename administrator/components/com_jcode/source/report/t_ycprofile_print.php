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
$lan = $_GET['lan'];
//***************************************************************Basic Information**********************************************/
   
	$ItemGroupId = $_GET['ItemGroupId'];
	$CountryId=$_GET['CountryId']; 
  	$CountryName=$_GET['CountryName']; 
	$Year=$_GET['Year'];
    $RequirementYear = $_GET['RequirementYear']; 
    
	//if(!empty($CountryId) && !empty($Year))
		 		$sql="SELECT SQL_CALC_FOUND_ROWS a.YCProfileId, a.YCValue, Year, a.CountryId, a.ParamId, ParamName, ParamNameFrench
				FROM t_ycprofile a
                INNER JOIN t_country b ON a.CountryId = b.CountryId
                INNER JOIN t_cprofileparams c ON a.ParamId = c.ParamId
                WHERE a.CountryId = '" . $CountryId . "'
                AND a.Year = '" . $Year . "' AND c.ItemGroupId = '" . $ItemGroupId . "'
				order by c.ShortBy;";
     mysql_query("SET character_set_results=utf8");            
	$r= mysql_query($sql) ;
	$total = mysql_num_rows($r);
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
							<h2 style="text-align:center;">'.$SITETITLE.'</h2>
                			<h3 style="text-align:center;">'.$gTEXT['Country Profile'].' of '.$CountryName.' '.$Year.'<h3>
                			<h3>'.$gTEXT['Basic Information'].'<h3>
            			</div>	
            			<table class="table table-striped display" id="gridDataCountry">
            				<thead>
            				</thead>
            				<tbody>
            					<tr>
            					    <th style="text-align: center;">SL#</th>
            					    <th style="text-align: left;">'.$gTEXT['Parameter Name'].'</th>
            					    <th style="text-align: left;">'.$gTEXT['Value'].'</th>
            	                </tr>';
                                
                                while($rec=mysql_fetch_array($r)){
                                	echo '<tr>
                                             <td style="text-align: center;">'.$i.'</td>
                                             <td style="text-align:left;">'.$rec['ParamName'].'</td>
                                	         <td style="text-align:left;">';

                                             if($rec['YCValue']==''){
                                                $rec['YCValue']=='';
                                             }else{
                                                if(is_numeric($rec['YCValue'])){
                                                     $rec['YCValue'] = number_format($rec['YCValue']);
                                                }else{
                                                     $rec['YCValue'] = $rec['YCValue'];
                                                }
                                             }  
                                             echo $rec['YCValue'];                                                                                     
                                    echo '</td></tr>';
                                    $i++; 
                                }
	 echo '</tbody></table></div></div></div><br/>';
     
	 
//********************************************************************Regimens/Patients ************************************************
	function getcheckBox($v) {
		if ($v == "true") {
			$x = "<input type='checkbox' checked  disabled class='datacell' value='" . $v . "' /><span class='custom-checkbox'></span>";
		} else {
			$x = "<input type='checkbox' disabled class='datacell' value='" . $v . "' /><span class='custom-checkbox'></span>";
		}
		return $x;
	}
		echo '<div class="row"> 
				<div class="panel panel-default table-responsive" id="grid_country">
					<div class="padding-md clearfix">
					<div class="panel-heading">
						<h3>'.$gTEXT['Funding Source'].'<h3>
					</div>	
					<table class="table table-striped display" id="gridDataCountry">
						<thead>
						</thead>
						<tbody>
						<tr>
							<th style="text-align: center;">SL</th>
							<th style="text-align: left;">'.$gTEXT['Funding Source Name'].'</th>
							<th style="text-align: left;"></th>
						</tr>';

		$sql = "SELECT t_fundingsource.ItemGroupId, t_fundingsource.FundingSourceName
		,t_yearly_country_fundingsource.YearlyFundingSrcId,t_fundingsource.FundingSourceId
		,IF(t_yearly_country_fundingsource.YearlyFundingSrcId is Null,'false','true') chkValue
		FROM t_fundingsource
		LEFT JOIN t_yearly_country_fundingsource ON (t_yearly_country_fundingsource.FundingSourceId = t_fundingsource.FundingSourceId
				AND t_yearly_country_fundingsource.CountryId = $CountryId
				AND t_yearly_country_fundingsource.Year = $Year
				AND t_fundingsource.ItemGroupId = $ItemGroupId)
		WHERE t_fundingsource.ItemGroupId = $ItemGroupId;";
		$pacrs = mysql_query($sql, $conn);
		$sql = "SELECT FOUND_ROWS()";
		$rs = mysql_query($sql, $conn);
		$r = mysql_fetch_array($rs);
		$total = $r[0];
		$f = '';
		$serial = 1;
		
		while ($row = @mysql_fetch_object($pacrs)) {
			$FundingSourceId = $row->FundingSourceId;
			$FundingSourceName = $row->FundingSourceName;
			$chkValue = $row->chkValue;
			$YearlyFundingSrcId = $row->YearlyFundingSrcId;
			//if ($f++)
			echo '<tr>
					  <td style="text-align: center;">'.$serial.'</td>
					  <td style="text-align: left;">'.$FundingSourceName.'</td>
					   <td style="text-align: left;">'.getcheckBox($chkValue).'</td>
				  </tr>';
			$serial++;

		}	
		echo'</thead>
				</table>
			  </div>
		   </div>  
		</div><br/>';

//********************************************************************Cases ************************************************
		$ItemGroupId = $_GET['ItemGroupId'];
		if($ItemGroupId==1){
		echo '<div class="row"> 
				<div class="panel panel-default table-responsive" id="grid_country">
					<div class="padding-md clearfix">
					<div class="panel-heading">
						<h3>'.$gTEXT['Cases'].'<h3>
					</div>	
					<table class="table table-striped display" id="gridDataCountry">
						<thead>
						</thead>
						<tbody>
						<tr>
							<th style="text-align: center;">SL</th>
							<th style="text-align: left;">'.$gTEXT['Formulation'].'</th>
							<th style="text-align: right;">(0-4 Years)</th>
							<th style="text-align: right;">(5-14 Years)</th>
							<th style="text-align: right;">(15+ Years)</th>
							<th style="text-align: right;">Pregnant women</th>		
						</tr>';

		if($lan == 'en-GB'){
				$FormulationName = 'FormulationName';
			}else{
				$FormulationName = 'FormulationNameFrench';
			}
			
		$columnsName = "";
		$sql = "SELECT RegMasterId,RegimenName FROM `t_regimen_master`
					where ItemGroupId=" . $ItemGroupId . " Order By RegMasterId ASC;";

					
		$result = mysql_query($sql, $conn);
		$total = mysql_num_rows($result);
		if ($total > 0) {
			while ($row = mysql_fetch_object($result)) {
				$columnsName.=',{ "sTitle": "' . $row->RegimenName . '","sWidth":"12%"}';
			}
		}

		
		$sql = "SELECT SQL_CALC_FOUND_ROWS YearlyRegPatientId, t_regimen_master.RegimenName, PatientCount,
					t_yearly_country_regimen_patient.FormulationId,$FormulationName	FormulationName
					FROM t_yearly_country_regimen_patient 
					INNER JOIN t_regimen_master ON t_yearly_country_regimen_patient.RegMasterId = t_regimen_master.RegMasterId 
					INNER JOIN t_formulation ON t_yearly_country_regimen_patient.FormulationId = t_formulation.FormulationId 
					WHERE t_yearly_country_regimen_patient.CountryId = '" . $CountryId . "' AND t_yearly_country_regimen_patient.Year = '" . $Year . "'	 
					AND t_regimen_master.ItemGroupId = " . $ItemGroupId . "	 				
					Order BY t_formulation.FormulationId ASC, t_yearly_country_regimen_patient.RegMasterId ASC;";
		//	echo $sql;		
		$result = mysql_query($sql, $conn);
		$total = mysql_num_rows($result);
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = mysql_query($sQuery);
		$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
		$iFilteredTotal = $aResultFilterTotal[0];	
		
		$f = 0;
		$tmpFormulationId = -1;
		$serial=0;
		while ($aRow = mysql_fetch_array($result)) {

			if ($tmpFormulationId != $aRow['FormulationId']) {
			if ($serial > 0)
			echo '</tr>';
			
				echo '<tr>
						  <td style="text-align: center;">'.++$serial.'</td>
						  <td style="text-align: left;">'.$aRow['FormulationName'].'</td>
						  <td style="text-align: right;">'.number_format($aRow['PatientCount']).'</td>';
					  $tmpFormulationId = $aRow['FormulationId'];
			}
			else {
				echo '<td style="text-align: right;">'.number_format($aRow['PatientCount']).'</td>';
				$tmpFormulationId = $aRow['FormulationId'];
			}
		}	
		if ($serial > 0)
		echo '</tr>';

		echo'</thead>
				</table>
			  </div>
		   </div>  
		</div><br/>';
}else{
	echo '';
}		
		
//*******************************************************************************Funding Requirements**********************************************************
		
	  echo '<div class="row"> 
		    <div class="panel panel-default table-responsive" id="grid_country">
           	<div class="padding-md clearfix">
           	<div class="panel-heading">
            <h3>'.$gTEXT['Funding Requirements'].'<h3>
            </div>	
    			<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    				</thead>
    				<tbody>
    					<tr>
						    <th width="100" style="text-align: center;"><b>SL</b></th>
				            <th width="150" style="text-align: left;"><b>'.$gTEXT['Formulation'].'</b></th>
				            <th width="100" style="text-align: right;"><b>'.$Year.'</b></th>
				            <th width="100" style="text-align: right;"><b>'.($Year+1).'</b></th>
				            <th width="100" style="text-align: right;"><b>'.($Year+2).'</b></th>
				            <th width="90" style="text-align: right;"><b>'.$gTEXT['Total'].'</b></th>
		                </tr>';
	
	
	 if($lan == 'en-GB'){
            $ServiceTypeName = 'ServiceTypeName';
            $FundingReqSourceName = 'FundingReqSourceName';
        }else{
            $ServiceTypeName = 'ServiceTypeNameFrench';
			$FundingReqSourceName = 'FundingReqSourceNameFrench';
        }	
	$sql = "SELECT SQL_CALC_FOUND_ROWS a.FundingReqId, a.TotalRequirements, a.Year, Y1, Y2, Y3,
			d.$ServiceTypeName ServiceTypeName, a.CountryId, a.FormulationId, c.$FundingReqSourceName FundingReqSourceName
			FROM t_yearly_funding_requirements a
			INNER JOIN  t_fundingreqsources c ON c.FundingReqSourceId = a.FundingReqSourceId 
			INNER JOIN t_servicetype d ON d.ServiceTypeId = c.ServiceTypeId
			INNER JOIN t_itemgroup b ON c.ItemGroupId = b.ItemGroupId                
			WHERE a.CountryId = '" . $CountryId . "'
			AND a.Year = '" . $Year . "' AND a.ItemGroupId = '" . $ItemGroupId . "'
			ORDER BY a.FundingReqSourceId ASC;";
	//echo $sql;	
    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    $sQuery = "SELECT FOUND_ROWS()";
    $rResultFilterTotal = mysql_query($sQuery);
    $aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
    $iFilteredTotal = $aResultFilterTotal[0];	
	
    $serial = 0;
    $f = 0;
	$tempGroupId='';
    while ($aRow = mysql_fetch_array($result)) {
		 if($tempGroupId!=$aRow['ServiceTypeName']) 
		   {
		   	 	echo'<tr >
                     <td style="background-color:#DAEF62;border-radius:2px;  align:center;" colspan="6">'.$aRow['ServiceTypeName'].'</td>
                   </tr>'; 
			   $tempGroupId=$aRow['ServiceTypeName'];
		   }
		echo '<tr>
		 	     <td width="100" style="text-align: center;">'.++$serial.'</td>
                    <td width="150" style="text-align: left;">'.$aRow['FundingReqSourceName'].'</td>
                    <td width="100" style="text-align: right;">'.number_format($aRow['Y1']).'</td>
                    <td width="100" style="text-align: right;">'.number_format($aRow['Y2']).'</td>
                    <td width="100" style="text-align: right;">'.number_format($aRow['Y3']).'</td>
                    <td width="90" style="text-align: right;">'.number_format($aRow['TotalRequirements']).'</td>
	
		     </tr>';
		}
		 echo'</thead>
    				
    			</table>
            </div>
		</div>  
     </div>';
	 
  echo '</body>
      </html>';	

		
			
//*******************************************************************Pledged Funding********************************	

    $CountryId = $_GET['CountryId'];
	$Year = $_GET['Year'];

	$RequirementYear = $_GET['RequirementYear'];
	 if($lan == 'en-GB'){
            $ServiceTypeName = 'ServiceTypeName';
            $FundingReqSourceName = 'FundingReqSourceName';
        }else{
            $ServiceTypeName = 'ServiceTypeNameFrench';
			$FundingReqSourceName = 'FundingReqSourceNameFrench';
        }
  /*if($lan == 'en-GB'){
            $ServiceTypeName = 'ServiceTypeName';
            $FundingReqSourceName = 'FundingReqSourceName';
        }else{
            $ServiceTypeName = 'ServiceTypeNameFrench';
			$FundingReqSourceName = 'FundingReqSourceNameFrench';
        }*/
 
 $columnsName = "";

    $sql = "SELECT SQL_CALC_FOUND_ROWS t_yearly_country_fundingsource.FundingSourceId,FundingSourceName FROM t_yearly_country_fundingsource
		INNER JOIN t_fundingsource ON t_yearly_country_fundingsource.FundingSourceId=t_fundingsource.FundingSourceId
				where Year ='" . $Year . "' AND CountryId ='" . $CountryId . "' AND t_fundingsource.ItemGroupId = '" . $ItemGroupId . "'
				Order By FundingSourceId;";
    //echo $sql;	
 
    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
	
	
    if ($total > 0) {
    	if($lan == 'en-GB'){
    	$columnsName.= '<th width="150" style="text-align: left;"><b>Service Type</b></th>';
        $columnsName.= '<th width="150" style="text-align: left;"><b>Category</b></th>';
        $columnsName.= '<th width="150" style="text-align: right;"><b>Total Requirements</b></th>';
    		
    	}
  else{
	    $columnsName.= '<th width="150" style="text-align: left;"><b>Type de service</b></th>';
        $columnsName.= '<th width="150" style="text-align: left;"><b>catégorie</b></th>';
        $columnsName.= '<th width="150" style="text-align: right;"><b>total des besoins</b></th>';
     }

        while ($row = mysql_fetch_object($result)) {
            $columnsName.='<th width="100" style="text-align: right;"><b>' . $row->FundingSourceName . '</b></th>';
        }
    if($lan == 'en-GB'){
	    $columnsName.= '<th width="90" style="text-align: right;"><b>Total</b></th>';
        $columnsName.= '<th width="90" style="text-align: right;"><b>Gap/Surplus</b></th>';
        }
	else{
		$columnsName.= '<th width="90" style="text-align: right;"><b>Total</b></th>';
        $columnsName.= '<th width="90" style="text-align: right;"><b>Gap/Surplus</b></th>';
	   }
        	
    }
 
echo '<div class="row"> 
		    <div class="panel panel-default table-responsive" id="grid_country">
           	<div class="padding-md clearfix">
           	<div class="panel-heading">
            <h3>'.$gTEXT['Pledged Funding'].'<h3>
            </div>	
    			<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    				</thead>
    				<tbody>
    					<tr>
				            
							'.$columnsName.'
		                </tr>';
	
	
	



    $sql = "SELECT FundingReqSourceId FROM t_fundingreqsources  WHERE ItemGroupId = '" . $ItemGroupId . "';";
//echo $sql;

    $result = mysql_query($sql, $conn);
    while ($row = mysql_fetch_object($result)) {
        $FundingReqSourceId = $row->FundingReqSourceId;


        $sqldel = "SELECT PledgedFundingId FROM t_yearly_pledged_funding
			 WHERE YEAR = '" . $Year . "' AND CountryId = " . $CountryId . " AND ItemGroupId = '" . $ItemGroupId . "'
			 AND FundingReqSourceId = " . $FundingReqSourceId . "			 
			 AND FundingSourceId NOT IN (			 
				SELECT FundingSourceId
				FROM t_yearly_country_fundingsource a
				WHERE a.Year = '" . $Year . "' AND a.CountryId = " . $CountryId . " AND a.ItemGroupId = '" . $ItemGroupId . "');";
        //echo $sqldel;
        $delResult = mysql_query($sqldel, $conn);
        while ($r = mysql_fetch_object($delResult)) {
            $sqldel1 = "DELETE FROM t_yearly_pledged_funding WHERE PledgedFundingId = " . $r->PledgedFundingId . ";";
            mysql_query($sqldel1, $conn);
        }

        $sql = "INSERT INTO t_yearly_pledged_funding 
						(`PledgedFundingId` ,`CountryId` ,`Year` ,`ItemGroupId` ,`FundingReqSourceId` ,`FundingSourceId` ,`TotalFund`)
						 SELECT NULL, a.CountryId, a.Year, a.ItemGroupId, '" . $FundingReqSourceId . "' ,a.FundingSourceId,0
							FROM t_yearly_country_fundingsource a
							WHERE a.Year = '" . $Year . "' AND a.CountryId = " . $CountryId . " AND a.ItemGroupId = '" . $ItemGroupId . "'
							AND FundingSourceId NOT IN (
							SELECT DISTINCT FundingSourceId FROM t_yearly_pledged_funding
							WHERE YEAR = '" . $Year . "' AND CountryId = " . $CountryId . " 
							AND ItemGroupId = '" . $ItemGroupId . "' AND FundingReqSourceId = " . $FundingReqSourceId . ");";
        mysql_query($sql, $conn);
			
    }

    $YValue = 'a.Y' . $RequirementYear;

    $sql = "SELECT d.ServiceTypeId, d.$ServiceTypeName ServiceTypeName, d.ServiceTypeNameFrench,
			b.FundingReqSourceId, b.$FundingReqSourceName FundingReqSourceName, b.FundingReqSourceNameFrench, IFNULL($YValue,0) YReq
			FROM t_yearly_funding_requirements a
			INNER JOIN t_fundingreqsources b ON a.FundingReqSourceId = b.FundingReqSourceId
			INNER JOIN t_servicetype d ON b.ServiceTypeId = d.ServiceTypeId
						
			WHERE a.CountryId = " . $CountryId . "
			AND  a.ItemGroupId = " . $ItemGroupId . "
			AND a.Year = '" . $Year . "'
			ORDER BY b.ServiceTypeId, b.FundingReqSourceId;";
    //echo $sql;

    $result = mysql_query($sql, $conn);
    $f = 0;

    $ColumnClass = 0;
    $tmpServiceTypeId = -1;
    $tmpFundingReqSourceId = -1;
    $sl = 0;
    while ($aRow = mysql_fetch_array($result)) {

        $Total = 0;
        $YReq = $aRow['YReq'];
        $FundingReqSourceId = $aRow['FundingReqSourceId'];
		echo '<tr>
                    <td width="150" style="text-align: left;">'.$aRow['ServiceTypeName'].'</td>
                    <td width="100" style="text-align: left;">'.$aRow['FundingReqSourceName'].'</td>
                    <td width="100" style="text-align: right;">'.$YReq.'</td>';
		

        $sql1 = "SELECT a.PledgedFundingId, b.FundingSourceId, b.FundingSourceName, IFNULL($YValue,0) YCurr	
					FROM t_yearly_pledged_funding a
					INNER JOIN t_fundingsource b ON a.FundingSourceId = b.FundingSourceId								
					WHERE a.CountryId = " . $CountryId . "
					AND  a.ItemGroupId = " . $ItemGroupId . "
					AND a.Year = '" . $Year . "'
					AND a.FundingReqSourceId = " . $aRow['FundingReqSourceId'] . "
					ORDER BY b.FundingSourceId;";
        //echo $sql;
        $sResult = mysql_query($sql1, $conn);
        while ($r = mysql_fetch_array($sResult)) {
		echo '<td width="150" style="text-align: right;">'.number_format($r['YCurr']).'</td>';			
			
            $Total+= $r['YCurr'];
        }
		echo '<td width="150" style="text-align:right;">'.number_format($Total, 1).'</td>
			<td width="150" style="text-align: right;">'.number_format(($YReq - $Total), 1).'</td>';
        echo '</tr>';
    }
		 echo'</thead>
    				
    			</table>
            </div>
		</div>  
     </div>';
	 
  echo '</body>
      </html>';					
    		
	 }	else{
			$error = "No record found.";	
			echo $error;
	}
	 
	  

?>