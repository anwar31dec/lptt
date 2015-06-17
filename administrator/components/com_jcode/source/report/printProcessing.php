<?php

include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());

mysql_query('SET CHARACTER SET utf8');

$jBaseUrl=$_GET['jBaseUrl'];
$gTEXT = $TEXT; 

$task = '';
if (isset($_POST['action'])) {
	$task = $_POST['action'];
} else if (isset($_GET['action'])) {
	$task = $_GET['action'];
}

switch($task) {
	
	
	  
	
	
	  case 'getDosesFormData' :
	 		 getDosesFormData();
	break;
	
	
	
     case 'getFacilityReportingStatus':
		  getFacilityReportingStatus();
    break;
	
	
	
	
	 case 'getFacilityData':
		  getFacilityData();
    break;	
	
	 
	
	
	
	
	 case 'getCountryRegimen':
		  getCountryRegimen();
		 
    break;	
	
	 case 'getCountryProduct':
		  	getCountryProduct();
    break;	
	
	 case 'getPOMasterData':
		  	getPOMasterData();
    break;		
	
	
	 case 'getReportStatusData':
		  	getReportStatusData();
    break;		
	
		
	
	 
	
	 case 'getMonthData':
		  	getMonthData();
    break;		
	
	 case'getAgencyShipment':
		  getAgencyShipment();
    break;		
	
	 	
	
	 case 'getYcProfileData':
		  	getYcProfileData();
    break;		
	
	
			
	case'getCountryProfileParams':
		  	getCountryProfileParams();
    break;		
	
	case'getYcRegimenPatient':
		  	getYcRegimenPatient();
    break;		
	
	case'getYcFundingSource':
		  	getYcFundingSource();
    break;	
	case'getYcPledgedFunding':
		  	getYcPledgedFunding();
    break;	
	
	case'getSummaryData':
		  	getSummaryData();
    break;	
	
	case'getStockStatusAtFacility':
		  	getStockStatusAtFacility();
    break;	
		
	case'getFundingStatusData':
		  	getFundingStatusData();
    break;
	
	case'getMosType':
		  	getMosType();
    break;
		
	case'getPatientTrendTimeSeriesChart':
		  	getPatientTrendTimeSeriesChart();
    break;
	
		
}


    


 

function getDosesFormData()
   
{ 
     global $gTEXT;
 	global $jBaseUrl; 
	$sql=" SELECT  DosesFormId,DosesFormName	
				FROM t_dosesform order by DosesFormName" ;
	$r= mysql_query($sql) ;
	$i=1;	
	if ($r)
		
	{
		echo '<!DOCTYPE html>
			<html>
			<head>
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
			 <div class="col-md-7"> 
           <div class="panel panel-default table-responsive" id="grid_country">
           	<div class="padding-md clearfix">
           	<div class="panel-heading">
              <h3>'.$gTEXT['Dosage Form List'].'<h3>
            </div>	
    			<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    					<tr>
				   <th style="text-align: center;">SL#</th>
					    <th>'.$gTEXT['Dosage Form Name'].'</th>
					    </tr>';
					
		while($rec=mysql_fetch_array($r))
		{
			echo '<tr>
		 	      <td style="text-align: center;">
			      '.$i.'
			      </td>
			      <td>
			     '.$rec['DosesFormName'].'
			     </td>
	            </tr>
			     ';
				 $i++; 
		}
		echo'</thead>
    			</table>
            </div>
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
	
}



function getFacilityReportingStatus()
   
{
	
	 global $gTEXT;
 	  global $jBaseUrl; 
  $monthId=$_GET['monthId']; 
  $year=$_GET['Year']; 
  $country=$_GET['CountryId'];
  $itemGroupId=$_GET['ItemGroupId'];
  		
  		
  		
		/*$sql=" 	SELECT   b.FacilityId, b.FacilityCode, b.FacilityName,
				IFNULL( a.FacilityId,0) bEntered,				
				DATE_FORMAT(a.CreatedDt, '%d-%b-%Y %h:%i %p') CreatedDt,	
				IF(c.StatusId = '2', '1', '0') bSubmitted,
				DATE_FORMAT(a.LastSubmittedDt, '%d-%b-%Y %h:%i %p')  LastSubmittedDt,
				IF(c.StatusId = '3', '1', '0') bAccepted,
				DATE_FORMAT(a.AcceptedDt, '%d-%b-%Y %h:%i %p')  AcceptedDt,
				IF(c.StatusId = '5', '1', '0') bPublished,
				DATE_FORMAT(a.PublishedDt, '%d-%b-%Y %h:%i %p')  PublishedDt
				FROM  t_cfm_masterstockstatus a RIGHT JOIN t_facility b
				ON a.FacilityId = b.FacilityId AND  MonthId = $monthId AND Year = '$year' AND a.CountryId = $country
				LEFT JOIN t_status c ON a.StatusId = c.StatusId " ;*/
	 
	$sQuery = "SELECT  b.FacilityId, b.FacilityCode, b.FacilityName,
				IFNULL( a.FacilityId,0) bEntered,				
				DATE_FORMAT(a.CreatedDt, '%d-%b-%Y %h:%i %p') CreatedDt,	
				IF(c.StatusId = '2', '1', '0') bSubmitted,
				DATE_FORMAT(a.LastSubmittedDt, '%d-%b-%Y %h:%i %p')  LastSubmittedDt,
				IF(c.StatusId = '3', '1', '0') bAccepted,
				DATE_FORMAT(a.AcceptedDt, '%d-%b-%Y %h:%i %p')  AcceptedDt,
				IF(c.StatusId = '5', '1', '0') bPublished,
				DATE_FORMAT(a.PublishedDt, '%d-%b-%Y %h:%i %p')  PublishedDt
				FROM  t_cfm_masterstockstatus a RIGHT JOIN (SELECT * FROM t_facility WHERE CountryId = $country) b
				ON a.FacilityId = b.FacilityId AND  MonthId = $monthId AND Year = '$year' AND a.CountryId = $country AND a.ItemGroupId = $itemGroupId
				LEFT JOIN t_status c ON a.StatusId = c.StatusId 
				";
				
	$r= mysql_query($sQuery) ;
	$i=1;	
	if ($r)
		
	{
		echo '<!DOCTYPE html>
			<html>
			<head>
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
              <h3>'.$gTEXT['Facility Reporting Status'].'<h3>
            </div>	
    			<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    				</thead>
    				<tbody>
    		               <tr>	
    						    <th>SL</th>	 
								<th >'.$gTEXT['Facility Code'].'</th>
								<th>'.$gTEXT['Facility Name'].'</th>
								<th >'.$gTEXT['Entered'].'</th>
								<th >'.$gTEXT['Entry Date'].'</th>
								<th >'.$gTEXT['Submitted'].'</th>
								<th >'.$gTEXT['Submitted Date'].'</th>
								<th >'.$gTEXT['Accepted'].'</th>
								<th >'.$gTEXT['Accepted Date'].'</th>
								<th >'.$gTEXT['Published'].'</th>
								<th >'.$gTEXT['Published Date'].'</th>
							</tr>';
		
			while($rec=mysql_fetch_array($r))
		{
				 
			echo '<tr>
		 	      <td style="text-align: center;">
			      '.$i.'
			      </td>
			      <td >
			     '.$rec['FacilityCode'].'
			     </td>
			      <td>
			     '.$rec['FacilityName'].'
			     </td>
			      <td style= "text-align: center;">';
				 	if($rec['bEntered']==0) echo'<div style="height:18px;width:38px;background-color:#fe402b;border-radius:2px; font-size:9px;align: center;color:#ffffff ">NO</div>'; 
                else echo'<div style="height:18px;width:38px;background-color:#9AD268;border-radius:2px; font-size:9px;align: center;color:#ffffff ">YES</div>'; 
			    echo'</td> 
		        
			      <td>
			     '.$rec['CreatedDt'].'
			     </td>
			     
			       <td style= "text-align:center;">';
				if($rec['bSubmitted']==0) echo'<div style="height:18px;width:38px;background-color:#fe402b;border-radius:2px; font-size:9px;align: center;color:#ffffff ">NO</div>'; 
                else echo'<div style="height:18px;width:38px;background-color:#9AD268;border-radius:2px; font-size:9px;align: center;color:#ffffff ">YES</div>'; 
				 echo'</td> 
				 
			      <td>
			     '.$rec['LastSubmittedDt'].'
			     </td>
			     
			      <td style= "text-align: center;">';
				 	if($rec['bAccepted']==0) echo'<div style="height:18px;width:38px;background-color:#fe402b;border-radius:2px; font-size:9px;align: center;color:#ffffff ">NO</div>'; 
                else echo'<div style="height:18px;width:38px;background-color:#9AD268;border-radius:2px; font-size:9px;align: center;color:#ffffff ">YES</div>'; 
			   echo'</td>
			    
		               <td>
			     '.$rec['AcceptedDt'].'
			     </td>';
				 
			    echo '<td style= "text-align: center;">';
		      if($rec['bPublished']==0) echo'<div style="height:18px;width:38px;background-color:#fe402b;border-radius:2px; font-size:9px;align: center;color:#ffffff ">NO</div>'; 
                else echo'<div style="height:18px;width:38px;background-color:#9AD268;border-radius:2px; font-size:9px;align: center;color:#ffffff ">YES</div>'; 
			 	echo '</td> 
			 	 
			       <td>
			     '.$rec['PublishedDt'].'
			     </td>
			     
			   </tr>
			     ';
			 
				 $i++; 
		}
		
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
	
}




function getFacilityData()
{
    
	global $gTEXT;
 
	global $jBaseUrl;
	 $ARegionId=$_GET['ARegionId']; 
	 $CountryId	=$_GET['CountryId']; 
     $FacilityLevel=$_GET['FacilityLevel']; 
     $FacilityType=$_GET['FacilityType'];
	 
	 $CountryName = $_GET['CountryName'];
	 $RegionName = $_GET['RegionName'];
	 $FTypeName = $_GET['FTypeName'];
	 $FLevelName = $_GET['FLevelName'];
	 
	  if($CountryId){
		$CountryId = " AND a.CountryId = '".$CountryId."' ";
	}  
    if($ARegionId){
		$ARegionId = " AND a.RegionId = '".$ARegionId."' ";
	}    
    if($FacilityType){
		$FacilityType = " AND a.FTypeId = '".$FacilityType."' ";
	}
    if($FacilityLevel){
		$FacilityLevel = " AND a.FLevelId = '".$FacilityLevel."' ";
	}  
	  
	$sql=" SELECT SQL_CALC_FOUND_ROWS FacilityId, a.CountryId, a.RegionId, ParentFacilityId, a.FTypeId, a.FLevelId, FacilityCode, FacilityName, FacilityAddress, FacilityPhone, FacilityFax, FacilityEmail, 
             FacilityManager, Latitude, Longitude, FacilityCount, FLevelName, FTypeName, RegionName
             FROM t_facility a
             INNER JOIN t_facility_level b ON a.FLevelId = b.FLevelId
             INNER JOIN t_facility_type c ON a.FTypeId = c.FTypeId
             INNER JOIN t_region d ON a.RegionId = d.RegionId 	
             ".$CountryId." ".$ARegionId." ".$FacilityType." ".$FacilityLevel." ";
           
	$r= mysql_query($sql) ;
	$i=1;	
	if ($r)
	{
			echo '<!DOCTYPE html>
			<html>
			<head>
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
              <h3 style="text-align:center;">'.$gTEXT['Facility List'].'<h3>
			  <p style="text-align:center; font-size:14px;">Country : '.$CountryName.', Region Name : '.$RegionName.'</p>
			  <p style="text-align:center; font-size:14px;">Facility Type : '.$FTypeName.'</p>
			  <p style="text-align:center; font-size:14px;">Facility Level : '.$FLevelName.'</p>
            </div>	
    			<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    				</thead>
    				<tbody>
    		               <tr>		
								<th style="text-align: center;">SL #</th>
						    <th>'.$gTEXT['Facility Code'].'</th>
						    <th>'.$gTEXT['Facility Name'].'</th>
						    <th style="text-align: center;">'.$gTEXT['Facility Type'].'</th>
						    <th style="text-align: center;">'.$gTEXT['Region Name'].'</th>
						    <th style="text-align: center;">'.$gTEXT['Received From'].'</th>
						    <th style="text-align: center;">'.$gTEXT['Facility Address'].'</th>  
						     <th style="text-align: center;">'.$gTEXT['Assigned Group'].'</th> 
							</tr>';
							
		$tempGroupId='';					
		while($rec=mysql_fetch_array($r))
		{
			if($tempGroupId!=$rec['FLevelName']) 
		   {
		   	 	echo'<tr style=background-color:#DAEF62;border-radius:2px; font-size:9px;align: center;color:#000000>
                     <td class="group"; colspan="8">'.$rec['FLevelName'].'</td>
                   </tr>'; 
			   $tempGroupId=$rec['FLevelName'];
		   }
			
			
			$sql_group = " SELECT FacilityId, GroupName
                 FROM t_facility_group_map a
                 INNER JOIN t_itemgroup b ON a.ItemGroupId = b.ItemGroupId
                 WHERE FacilityId = ".$rec['FacilityId']." "; 
        $pacrs_group = mysql_query($sql_group);
        $group_name = ""; $j = 0;
        if ($pacrs_group)
	   {
	    while ($row_group = mysql_fetch_object($pacrs_group)) {	  
	       if ($j++) $group_name.= ", ";
	       $group_name.= $row_group -> GroupName;       
        }
		}
		
		
		    	
			echo '<tr>
			     <td style="text-align: center;">
			     '.$i.'
			     </td>
			     
				 <td style="text-align:center;">
			     '.$rec['FacilityCode'].'
			     </td>
			      <td>
			     '.$rec['FacilityName'].'
			     </td>
			     
				   <td style="text-align:center;" >
			     '.$rec['FTypeName'].'
			     </td>	
			     <td style="text-align:center;" >
			     '.$rec['RegionName'].'
			     </td>	
				   <td style="text-align: center;">
			     '.$rec['FLevelName'].'
			     </td>	
			        <td style="text-align: center;" >
			     '.$rec['FacilityAddress'].'
			     </td>	
			      </td>	
			        <td style="text-align: center;" >
			     '.$group_name.'
			     </td>		
			     </tr>
			     ';
				 
				 $i++; 
		}
		
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
	
}


function getCountryRegimen()
{ 
 	
	 global $gTEXT;
 
	global $jBaseUrl;
 
	$sql=" SELECT CountryId, CountryCode, CountryName 	
             FROM t_country	order by CountryName ";
$r= mysql_query($sql) ;
	$i=1;	
	if ($r)
		
	{
		
		echo '<!DOCTYPE html>
			<html>
			<head>
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
              <h3>'.$gTEXT['Country Regimen'].'<h3>
            </div>	
    			<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    				</thead>
    				<tbody>
    					<tr>
    					    
						    <th style="text-align: center;">SL#</th>
						    <th>'.$gTEXT['Country Name'].'</th>
		                </tr>';
		while($rec=mysql_fetch_array($r))
		{
				 
			echo '<tr>
			       
		 	      <td style="text-align: center;">
			      '.$i.'
			      </td>
			      <td>
			     '.$rec['CountryName'].'
			     </td>
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
    }		
	else
	{
		
		$error = 0;	
		echo $error;
	}
	
}
	


function getCountryProduct()
{ 
    global $gTEXT;
 	global $jBaseUrl;
	$sql="  SELECT CountryId, CountryCode, CountryName 	
             FROM t_country";
	
	$r= mysql_query($sql) ;
	$i=1;	
	if ($r)
	{
		echo '<!DOCTYPE html>
			<html>
			<head>
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
              <h3>'.$gTEXT['Country Product'].'<h3>
            </div>	
    			<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    				</thead>
    				<tbody>
    					<tr>
						    <th style="text-align: center;">SL#</th>
						    <th style="text-align: left;">'.$gTEXT['Country Name'].'</th>
		                </tr>';
		while($rec=mysql_fetch_array($r))
		{
			echo '<tr>
		 	      <td style="text-align: center;">
			      '.$i.'
			      </td>
			      <td>
			     '.$rec['CountryName'].'
			     </td>
	
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
    }		
	else
	{
		$error = 0;	
		echo $error;
	}
	
}

function getPOMasterData()
{ 
    global $gTEXT;
	global $jBaseUrl;
	$sql=" SELECT  POMasterId, POMasterName	
				FROM t_pomaster order by POMasterId";
	$r= mysql_query($sql) ;
	$i=1;	
	if ($r)
	{
		echo '<!DOCTYPE html>
			<html>
			<head>
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
			echo ' <div class="col-md-7"> 
           <div class="panel panel-default table-responsive" id="grid_country">
           	<div class="padding-md clearfix">
           	<div class="panel-heading">
              <h3>'.$gTEXT['Patient Overview'].'<h3>
            </div>	
    			<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    					<tr>
						    <th style="text-align: center;">SL#</th>
						    <th>'.$gTEXT['Patient Overview'].'</th>
		                </tr>';
		while($rec=mysql_fetch_array($r))
		{
				 
			echo '<tr>
		 	      <td style="text-align: center;">
			      '.$i.'
			      </td>
			      <td>
			     '.$rec['POMasterName'].'
			     </td>
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
    }		
	else
	{
		$error = 0;	
		echo $error;
	}
	
}

    
function getReportStatusData()
{ 
	global $gTEXT;
 	global $jBaseUrl;
$sql=" SELECT  StatusId,StatusName	
				FROM t_status order by StatusId";
	$r= mysql_query($sql) ;
	$i=1;	
	if ($r)
		
	{
		echo '<!DOCTYPE html>
			<html>
			<head>
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
			echo ' <div class="col-md-7"> 
           <div class="panel panel-default table-responsive" id="grid_country">
           	<div class="padding-md clearfix">
           	<div class="panel-heading">
              <h3>'.$gTEXT['Report Status'].'<h3>
            </div>	
    			<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    					<tr>
						    <th style="text-align: center;">SL#</th>
						    <th>'.$gTEXT['Center latitude'].'</th>
		                </tr>';
		while($rec=mysql_fetch_array($r))
		      {
				echo '<tr>
		 	      <td style="text-align: center;">
			      '.$i.'
			      </td>
			      <td>
			     '.$rec['StatusName'].'
			     </td>
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
    }		
	else
	{
		$error = 0;	
		echo $error;
	}
	
} 
	



function getMonthData()
{ 

    global $gTEXT;
 	global $jBaseUrl;
	$sql="SELECT * FROM `t_month`
                ORDER BY `t_month`.`MonthId` ASC
                LIMIT 0 , 30 
	       ;  ";
$r= mysql_query($sql) ;
	$i=1;	
	if ($r)
	{
		echo '<!DOCTYPE html>
			<html>
			<head>
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
              <h3>'.$gTEXT['Month'].'<h3>
            </div>	
    			<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    				</thead>
    				<tbody>
    					<tr>
						    <th style="text-align: center;">SL#</th>
						    <th style="text-align: center;">'.$gTEXT['Month Name'].'</th>
		                </tr>';
			while($rec=mysql_fetch_array($r))
		    {
			echo '<tr>
		 	      <td style="text-align: center;">
			      '.$i.'
			      </td>
			      <td style="text-align: center;">
			     '.$rec['MonthName'].'
			     </td>
	
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
    }		
	else
	{
		$error = 0;	
		echo $error;
	}
	
}

function getAgencyShipment()
{ 

    global $gTEXT;
 	global $jBaseUrl;
	$CountryId=$_GET['ACountryId']; 
	$AFundingSourceId=$_GET['AFundingSourceId']; 
	$ASStatusId=$_GET['ASStatusId']; 
 if($CountryId){
		$CountryId = " WHERE a.CountryId = '".$CountryId."' ";
	}
    if($AFundingSourceId){
		$AFundingSourceId = " AND a.FundingSourceId = '".$AFundingSourceId."' ";
	}   
    if($ASStatusId){
		$ASStatusId = " AND a.ShipmentStatusId = '".$ASStatusId."' ";
	}

   $sql = "SELECT  AgencyShipmentId, a.FundingSourceId, d.FundingSourceName, a.ShipmentStatusId, c.ShipmentStatusDesc, a.CountryId, 
            b.CountryName, a.ItemNo, e.ItemName, a.ShipmentDate, a.Qty
			FROM t_agencyshipment as a
            INNER JOIN t_country b ON a.CountryId = b.CountryId
            INNER JOIN t_shipmentstatus c ON a.ShipmentStatusId = c.ShipmentStatusId
            INNER JOIN t_fundingsource d ON a.FundingSourceId= d.FundingSourceId
            INNER JOIN t_itemlist e ON a.ItemNo = e.ItemNo ".$CountryId." ".$AFundingSourceId." ".$ASStatusId."
			$sWhere $sOrder $sLimit ";  
	$r= mysql_query($sql) ;
	$i=1;	
	if ($r)
	{
		echo '<!DOCTYPE html>
			<html>
			<head>
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
              <h3>'.$gTEXT['Agency Shipment List'].'<h3>
            </div>	
    			<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    				</thead>
    				<tbody>
    					<tr>
						    <th style="text-align: center;">SL#</th>
						    <th style="text-align: left;">'.$gTEXT['Item Name'].'</th>
						    <th style="text-align: left;">'.$gTEXT['Shipment Status'].'</th>
						    <th style="text-align: center;">'.$gTEXT['Shipment Date'].'</th>
						    <th style="text-align: center;">'.$gTEXT['Quantity'].'</th>
		                </tr>';
			$CountryId='';
			$AFundingSourceId='';
			$ASStatusId='';
			$tempGroupId='';
		while($rec=mysql_fetch_array($r))
		{
			 if($tempGroupId!=$rec['FundingSourceName']) 
		   {
		   	 	echo'<tr style=background-color:#DAEF62;border-radius:2px; font-size:9px;align: center;color:#000000>
                     <td class="txtLeft"; colspan="5">'.$rec['FundingSourceName'].'</td>
                   </tr>'; 
			   $tempGroupId=$rec['FundingSourceName'];
		   }
			echo '<tr>
		 	      <td style="text-align: center;">
			      '.$i.'
			      </td>
			      <td style="text-align: left;">
			     '.$rec['ItemName'].'
			     </td>
			      <td style="text-align: left;">
			     '.$rec['ShipmentStatusDesc'].'
			     </td>
			      <td style="text-align: center;">
			     '.$rec['ShipmentDate'].'
			     </td>
			      <td style="text-align: center;">
			     '.$rec['Qty'].'
			     </td>
	
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
    }		
	else
	{
		$error = 0;	
		echo $error;
	}
	
}


    
function getYcProfileData()
{ 

    global $gTEXT;
 	global $jBaseUrl
	;
//Basic Information	 		
	$CountryId=$_GET['CountryId']; 
  	$CountryName=$_GET['CountryName']; 
	$Year=$_GET['year']; 
	if(!empty($CountryId) && !empty($Year))
		 		$sql="SELECT  a.YCProfileId, a.YCValue, Year, a.CountryId, a.ParamId, ParamName
				FROM t_ycprofile a
                INNER JOIN t_country b ON a.CountryId = b.CountryId
                INNER JOIN t_cprofileparams c ON a.ParamId = c.ParamId
                WHERE a.CountryId = '".$CountryId."'
                AND a.Year = '".$Year."' AND a.ParamId NOT IN (5,7)	";
	$r= mysql_query($sql) ;
	$total = mysql_num_rows($r);
	$i=1;	
    
	if ($total>0){
		
	{
		
		echo '<!DOCTYPE html>
			<html>
			<head>
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
		
	 	$CountryId='';
		$Year='';
		while($rec=mysql_fetch_array($r))
		{
			 
			echo '<tr>
		 	      <td style="text-align: center;">
			      '.$i.'
			      </td>
			      <td style="text-align:left;">
			      '.$rec['ParamName'].'
			      </td>
				  <td style="text-align:left;">
			      '.($rec['YCValue']==''? '':number_format($rec['YCValue'])).'
			      </td>
			      </tr>
			      ';
			$i++; 
		 }
			
	 echo '</tbody></table></div></div></div><br/>';
	 
///Regimens/Patients 

	echo '<div class="row"> 
	       <div class="panel panel-default table-responsive" id="grid_country">
           	<div class="padding-md clearfix">
           	<div class="panel-heading">
              <h3>'.$gTEXT['Regimens/Patients'].'<h3>
            </div>	
    			<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    				</thead>
    				<tbody>
    					<tr>
						    <th style="text-align: center;">SL#</th>
						    <th style="text-align: left;">'.$gTEXT['RegimenCount'].'</th>
						     <th style="text-align: left;">'.$gTEXT['Patients'].'</th>
		                </tr>';
	$aColumns = array('RegimenName', 'PatientCount', 'FormulationName');
	$aColumns2 = array('RegimenName', 'PatientCount', 'FormulationName');
    $sIndexColumn = "YearlyRegPatientId";
	$sTable = "t_yearly_country_regimen_patient ";
		
	$sJoin = 'INNER JOIN t_regimen ON t_yearly_country_regimen_patient.RegimenId = t_regimen.RegimenId ';
	$sJoin .= 'INNER JOIN t_formulation ON t_regimen.FormulationId = t_formulation.FormulationId ';	
	$sLimit = "";
	if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
		$sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
	}
	$sOrder = "";
	if (isset($_GET['iSortCol_0'])) {
		$sOrder = "ORDER BY";
		for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
			if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
				$sOrder .= "" . $aColumns[intval($_GET['iSortCol_' . $i])] . " " . ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
			}
		}
	$sOrder = substr_replace($sOrder, "", -2);
		if ($sOrder == "ORDER BY") {
			$sOrder = "";
		}
	}
	
	$sWhere = ""; 
	 
	for ($i = 0; $i < count($aColumns); $i++) {
		if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch'] != '') {
			if ($sWhere == "") {
				$sWhere = "WHERE ";
			} else {
				$sWhere .= " OR ";
			}
			$sWhere .= "" . $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' ";
		}
	}
	$bUserFilter = true;

	if ($bUserFilter) {
		if ($sWhere == "") {
			$sWhere = "WHERE ";
		} else {
			$sWhere .= " AND ";
		}
		$sWhere .=  "t_yearly_country_regimen_patient.CountryId = " . $_GET['CountryId'] . " AND t_yearly_country_regimen_patient.Year = " . $_GET['Year'];
	}

	$bUseSL = true;
	$serial = '';
    $sQuery = "SELECT  " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
			FROM   $sTable
			$sJoin
			$sWhere
			ORDER BY FormulationName
			$sLimit
			";
	$rResult =mysql_query($sQuery);
    $j=1;$tempGroupId='';
	//while ($aRow = mysql_fetch_array($rResult))
	 {
		$row = array();
		for ($i = 0; $i < count($aColumns2); $i++) {			
			if ($i == 0)
				$row[] = $serial++;
			else
				$row[] = $aRow[$aColumns2[$i]];
		}
	 
		 if($tempGroupId!=$aRow['FormulationName']) 
		   {
		   	 	echo'<tr>
                     <td style="background-color:#DAEF62;border-radius:2px;  align:center;" colspan="3">'.$aRow['FormulationName'].'</td>
                   </tr>'; 
			   $tempGroupId=$aRow['FormulationName'];
		   }
			
		echo '<tr>
		 	      <td style="text-align: center;">
			      '.$j.'
			      </td>
			      <td style="text-align: left;">
			     '.$aRow['RegimenName'].'
			     </td>
			       <td style="text-align: left;">
			     '.($aRow['PatientCount']==''? '':number_format($aRow['PatientCount'])).'
			     </td>
	             </tr>
			     ';
				 $j++; 
               }

		
			echo'</thead>
     			</table>
              </div>
		   </div>  
        </div><br/>';
        
echo '</body>
      </html>';	
    }		
	 }	else{
			$error = "No record found.";	
			echo $error;
	}
	 
	 
	
}

function getCountryProfileParams()
{ 

    global $gTEXT;
	global $jBaseUrl;
$CountryId=$_GET['CountryId']; 	 
$Year=$_GET['Year'];

if($_REQUEST['lan'] == 'en-GB'){
     
        $PLang = 'ParamName';   
    }else{
      
        $PLang = 'ParamNameFrench';
    } 
 if(!empty($CountryId) && !empty($Year))
    		 		$sql="SELECT  a.YCProfileId, a.YCValue, Year, a.CountryId, a.ParamId, $PLang ParamName 
    				FROM t_ycprofile a
                    
                    INNER JOIN t_cprofileparams c ON a.ParamId = c.ParamId
                    WHERE a.CountryId = " . $_REQUEST['CountryId'] . " 
                    AND a.Year = " . $_REQUEST['Year'] . " 
                    AND a.ParamId NOT IN (5,7)
                    Order By a.ParamId "; 
	$r= mysql_query($sql) ;
	$total = mysql_num_rows($r);
	$i=1;	
    
	if($total>0){
		
		echo '<!DOCTYPE html>
			 <html>
			 <head>
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
                			<h3 style="text-align:center;">'.$gTEXT['Country Profile'].' of '.$CountryName.' '.$Year.'<h3>
                			<h3>'.$gTEXT['Parameter List'].'<h3>
            			</div>	
            			<table class="table table-striped display" id="gridDataCountry">
            				<thead>
            				</thead>
            				<tbody>
            					<tr>
            					    <th style="text-align: center;">SL</th>
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
      }	else{
			$error = "No record found.";	
			echo $error;
	}
	
}

function getYcRegimenPatient()
{ 

    global $gTEXT;
    global $jBaseUrl;
	$CountryId=$_REQUEST['CountryId']; 
	$CountryName=$_REQUEST['CountryName']; 	 
	$Year=$_REQUEST['Year'];
	echo '<!DOCTYPE html>
			<html>
			<head>
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
              <h3>'.$gTEXT['ART Protocols with Patient Count'].'<h3>
            </div>	
    			<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    				</thead>
    				<tbody>
    					<tr>
						    <th style="text-align: center;">SL</th>
						    <th style="text-align: left;">'.$gTEXT['RegimenCount'].'</th>
						     <th style="text-align: left;">'.$gTEXT['Patients'].'</th>
		                </tr>';
	$aColumns = array('RegimenName', 'PatientCount', 'FormulationName');
	$aColumns2 = array('RegimenName', 'PatientCount', 'FormulationName');
    $sIndexColumn = "YearlyRegPatientId";
	$sTable = "t_yearly_country_regimen_patient ";
		
	$sJoin = 'INNER JOIN t_regimen ON t_yearly_country_regimen_patient.RegimenId = t_regimen.RegimenId ';
	$sJoin .= 'INNER JOIN t_formulation ON t_regimen.FormulationId = t_formulation.FormulationId ';	
	$sLimit = "";
	if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
		$sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
	}

	
	$sOrder = "";
	if (isset($_GET['iSortCol_0'])) {
		$sOrder = "ORDER BY  ";
		for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
			if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
				$sOrder .= "" . $aColumns[intval($_GET['iSortCol_' . $i])] . " " . ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
			}
		}

		$sOrder = substr_replace($sOrder, "", -2);
		if ($sOrder == "ORDER BY FormulationName") {
			$sOrder = "";
		}
	}
	
	$sWhere = ""; 
	 
	for ($i = 0; $i < count($aColumns); $i++) {
		if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch'] != '') {
			if ($sWhere == "") {
				$sWhere = "WHERE ";
			} else {
				$sWhere .= " OR ";
			}
			$sWhere .= "" . $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' ";
		}
	}
	$bUserFilter = true;

	if ($bUserFilter) {
		if ($sWhere == "") {
			$sWhere = "WHERE ";
		} else {
			$sWhere .= " AND ";
		}
		$sWhere .=  "t_yearly_country_regimen_patient.CountryId = " . $_GET['CountryId'] . " AND t_yearly_country_regimen_patient.Year = " . $_GET['Year'];
	}

	$bUseSL = true;
	$serial = '';
    $sQuery = "SELECT SQL_CALC_FOUND_ROWS @rank:=@rank+1 AS SL, RegimenName, PatientCount, $FLang FormulationName
                FROM t_yearly_country_regimen_patient
                INNER JOIN t_regimen ON t_yearly_country_regimen_patient.RegimenId = t_regimen.RegimenId 
                INNER JOIN t_formulation ON t_regimen.FormulationId = t_formulation.FormulationId
                WHERE t_yearly_country_regimen_patient.CountryId = " . $_REQUEST['CountryId'] . " 
                AND t_yearly_country_regimen_patient.Year = " . $_REQUEST['Year']."
                ORDER BY t_formulation.FormulationId asc";
	mysql_query("SET character_set_results=utf8");		
	$rResult =mysql_query($sQuery);
    $j=1;$tempGroupId='';
	while ($aRow = mysql_fetch_array($rResult)) {
		$row = array();
		for ($i = 0; $i < count($aColumns2); $i++) {			
			if ($i == 0)
				$row[] = $serial++;
			else
				$row[] = $aRow[$aColumns2[$i]];
		}
	 
		 if($tempGroupId!=$aRow['FormulationName']) 
		   {
		   	 	echo'<tr>
                     <td style="background-color:#DAEF62;border-radius:2px;  align:center;" colspan="3">'.$aRow['FormulationName'].'</td>
                   </tr>'; 
			   $tempGroupId=$aRow['FormulationName'];
		   }
			
		echo '<tr>
		 	      <td style="text-align: center;">
			      '.$j.'
			      </td>
			      <td style="text-align: left;">
			     '.$aRow['RegimenName'].'
			     </td>
			       <td style="text-align: left;">
			     '.($aRow['PatientCount']==''? '':number_format($aRow['PatientCount'])).'
			     </td>
	             </tr>
			     ';
				 $j++; 
               }
		 echo'</thead>
    			</table>
            </div>
		</div>  
     </div>';
	 
  echo '</body>
      </html>';	
		

}

function getYcFundingSource()
{ 

    global $gTEXT;
 	global $jBaseUrl;
$CountryId=$_GET['CountryId']; 	 
$Year=$_GET['Year']; 
echo '<!DOCTYPE html>
			<html>
			<head>
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
              <h3>'.$gTEXT['Funding Requirements'].$gTEXT['MonetaryTitle'].'<h3>
            </div>	
    			<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    				</thead>
    				<tbody>
    					<tr>
						    <th width="100" style="text-align: center;"><b>SL</b></th>
				            <th width="150" style="text-align: left;"><b>'.$gTEXT['Formulation'].'</b></th>
				            <th width="100" style="text-align: right;"><b>'.$gTEXT['2014'].'</b></th>
				            <th width="100" style="text-align: right;"><b>'.$gTEXT['2015'].'</b></th>
				            <th width="100" style="text-align: right;"><b>'.$gTEXT['2016'].'</b></th>
				            <th width="90" style="text-align: right;"><b>'.$gTEXT['Total'].'</b></th>
		                </tr>';
	 if($_REQUEST['lan'] == 'fr-FR'){
            $aColumns = array('SL', 'ServiceTypeNameFrench', 'FundingReqSourceNameFrench', 'Y1', 'Y2', 'Y3', 'TotalRequirements');
            $aColumns2 = array('SL', 'ServiceTypeNameFrench', 'FundingReqSourceNameFrench', 'Y1', 'Y2', 'Y3', 'TotalRequirements');
     }else{
            $aColumns = array('SL', 'ServiceTypeName', 'FundingReqSourceName', 'Y1', 'Y2', 'Y3', 'TotalRequirements');
            $aColumns2 = array('SL', 'ServiceTypeName', 'FundingReqSourceName', 'Y1', 'Y2', 'Y3', 'TotalRequirements');
     } 
	 
	 
	 
	 
	 
	 
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "FundingReqId";

	/* DB table to use */
	$sTable = "t_yearly_funding_requirements ";

	

// Joins
	$sJoin = 'INNER JOIN  t_fundingreqsources ON t_fundingreqsources.FundingReqSourceId = t_yearly_funding_requirements.FormulationId  ';
	$sJoin .= 'INNER JOIN t_servicetype ON t_servicetype.ServiceTypeId = t_fundingreqsources.ServiceTypeId ';
	$sJoin .= 'INNER JOIN t_itemgroup ON t_itemgroup.ItemGroupId = t_fundingreqsources.ItemGroupId ';
	////$sJoin  .= 'INNER JOIN t_country ON t_ycprofile.CountryId = t_country.CountryId ';
	
	
	
	
	
	/*
	 * Paging
	 */
	$sLimit = "";
	if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
		$sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
	}

	/*
	 * Ordering
	 */
	$sOrder = "";
	if (isset($_GET['iSortCol_0'])) {
		$sOrder = "ORDER BY  ";
		for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
			if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
				$sOrder .= "" . $aColumns[intval($_GET['iSortCol_' . $i])] . " " . ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
			}
		}

		$sOrder = substr_replace($sOrder, "", -2);
		if ($sOrder == "ORDER BY") {
			$sOrder = "";
		}
	}
	$sOrder = "Order By t_fundingreqsources.FundingReqSourceId ";
	
	
	$sWhere = "";

	
	for ($i = 0; $i < count($aColumns); $i++) {
		if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch'] != '') {
			if ($sWhere == "") {
				$sWhere = "WHERE ";
			} else {
				$sWhere .= " OR ";
			}
			$sWhere .= "" . $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' ";
		}
	}

	/*User Data Filtering*/
	$bUserFilter = true;

	if ($bUserFilter) {
		if ($sWhere == "") {
			$sWhere = "WHERE ";
		} else {
			$sWhere .= " AND ";
		}
		//$sWhere .= "t_ycprofile.CountryId = 2 AND ReportDate = '" . $_GET['YearId']."-".$_GET['MonthId']."-01'";
		$sWhere .= "t_yearly_funding_requirements.CountryId = " . $_GET['CountryId'] . " AND t_yearly_funding_requirements.Year = " . $_GET['Year'];
	}

	$bUseSL = true;
	$serial = '';

	if ($bUseSL) {
		mysql_query("SET @rank=0;");
		$serial = "@rank:=@rank+1 AS ";
	}

	/*
	 * SQL queries
	 * Get data to display
	 */

	$sQuery = "
			SELECT SQL_CALC_FOUND_ROWS " . $serial . str_replace(" , ", " ", implode(", ", $aColumns)) . "
			FROM   $sTable
			$sJoin
			$sWhere
			$sOrder
			$sLimit
			";
	$rResult =mysql_query($sQuery);
	 $j=1;$tempGroupId='';
	while ($aRow = mysql_fetch_array($rResult)) {
		$row = array();
		for ($i = 0; $i < count($aColumns2); $i++) {
			
			$row[] = $aRow[$aColumns2[$i]];
		}
	
		 if($tempGroupId!=$aRow['ServiceTypeName']) 
		   {//style="background-color:#DAEF62;border-radius:2px; font-size:9px; align:center;" class="txtLeft";font-size:9px;
		   	 	echo'<tr >
                     <td style="background-color:#DAEF62;border-radius:2px;  align:center;" colspan="6">'.$aRow['ServiceTypeName'].'</td>
                   </tr>'; 
			   $tempGroupId=$aRow['ServiceTypeName'];
		   }
		echo '<tr>
		 	     <td width="100" style="text-align: center;">'.$j.'</td>
                    <td width="150" style="text-align: left;">'.$aRow['FundingReqSourceName'].'</td>
                    <td width="100" style="text-align: right;">'.number_format($aRow['Y1']).'</td>
                    <td width="100" style="text-align: right;">'.number_format($aRow['Y2']).'</td>
                    <td width="100" style="text-align: right;">'.number_format($aRow['Y3']).'</td>
                    <td width="90" style="text-align: right;">'.number_format($aRow['TotalRequirements']).'</td>
	
		     </tr>
			     ';
				 
				 $j++; 
		  
	         }
	
		 echo'</thead>
    				
    			</table>
            </div>
		</div>  
     </div>';
	 
  echo '</body>
      </html>';	

	
}

function getYcPledgedFunding()
{ 

    global $gTEXT;
 
	global $jBaseUrl;

echo '<!DOCTYPE html>
			<html>
			<head>
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
		
	$CountryId = $_GET['CountryId'];
	$Year = $_GET['Year'];

	$RequirementYear = $_GET['RequirementYear'];
if($_GET['lan'] == 'fr-FR'){
        $aColumns = 'f.FundingReqSourceNameFrench FormulationName, ServiceTypeNameFrench GroupName';   
    }else{
        $aColumns = 'f.FundingReqSourceName FormulationName, ServiceTypeName GroupName';   
    }
    
	$rowData = array();
	$dynamicColumns = array();
	$dynamiccolWidths = array();
	if (!empty($CountryId) && !empty($Year)) {
		$sql = "select f.FundingSourceId,s.FundingSourceName from t_yearly_country_fundingsource f
		Inner Join t_fundingsource s on s.FundingSourceId=f.FundingSourceId
		where  CountryId='" . $CountryId . "' and Year='" . $Year . "' 
		Order By FundingSourceName asc ";
        
		$resultPre = mysql_query($sql);
		$total = mysql_num_rows($resultPre);

		$l = 0;
		$trecord = 0;
		if ($total > 0) {
			while($row=mysql_fetch_object($resultPre)){
				$FundingSourceId=$row->FundingSourceId;
				$col=array();				
				$col['FundingSourceId'] =  $row->FundingSourceId;
				array_push($dynamicColumns,$col);				
			}		
		}
                
        $sql = "SELECT f.ItemGroupId,f.FundingReqSourceId FormulationId, $aColumns 
                FROM t_fundingreqsources f
        		INNER JOIN t_servicetype ON t_servicetype.ServiceTypeId = f.ServiceTypeId
        		INNER JOIN t_itemgroup g on g.ItemGroupId=f.ItemGroupId
        		Order By f.FundingReqSourceId ";
                
        mysql_query("SET character_set_results=utf8");
		$result = mysql_query($sql);
		$total = mysql_num_rows($result);
	
		$superGrandTotalRequirements=0;$superGrandFundingTotal=array();$superGrandSubTotal=0;$superGrandGapSurplus=0;
		$groupsubtotal=0;$groupsubTmp=-1;$p=0;$q=0;$r=0;$grandTotalRequirements=0;$grandFundingTotal=array();$grandSubTotal=0;$grandGapSurplus=0;
		while ($row = mysql_fetch_object($result)) {			
			$ItemGroupId = $row -> ItemGroupId;
			$FormulationId = $row -> FormulationId;
			
			if($p!=0&&$groupsubTmp!=$row -> GroupName){
				$l = 0;		
				$cellData = array();
				$cellData[$l++]=$groupsubTmp;	
				$cellData[$l++]='Total';
				$cellData[$l++]=$grandTotalRequirements;				
				for ($j = 0; $j < count($dynamicColumns); $j++) {				
					$subtotal=0;
					for ($k = 0; $k < count($grandFundingTotal); $k++) 
						$subtotal+=$grandFundingTotal[$k][$j];
					$cellData[$l++]=$subtotal;
					$superGrandFundingTotal[$r][$j]=$subtotal;
				}	
						
				$cellData[$l++]=$grandSubTotal;
				if ($grandGapSurplus >= 0){
					$cellData[ $l++] =number_format($grandGapSurplus);					
				}
				else{
					$cellData[ $l++] = '(' . number_format((-1) * $grandGapSurplus ). ')';					
				}	
				$cellData[ $l++] = $ItemGroupId;
				$cellData[ $l++] = $FormulationId;
				$rowData[] = $cellData;
			
				$superGrandTotalRequirements+=$grandTotalRequirements;
				$superGrandSubTotal+=$grandSubTotal;
				$superGrandGapSurplus+=$grandGapSurplus;
			
				$q=0;$grandTotalRequirements=0;$grandFundingTotal=array();$grandSubTotal=0;$grandGapSurplus=0;	
				$r++;
			}
		
			$l = 0;		
			$cellData = array();
			$groupsubTmp=$row -> GroupName;
			$cellData[$l++] = $row -> GroupName;
			$cellData[$l++] = $row -> FormulationName;
		
			$sql = "Select * from t_yearly_funding_requirements 
                    where CountryId='" . $CountryId . "' 
                    and Year='" . $Year . "' 
                    and ItemGroupId='" . $ItemGroupId . "' 
                    and FormulationId='" . $FormulationId . "' ";
                    
			$result2 = mysql_query($sql);
			$total2 = mysql_num_rows($result2);
			if ($total2 > 0) {
				$row2 = mysql_fetch_object($result2);
				if ($RequirementYear == 1) {
					$totalRequirement = $row2 -> Y1;
				} else if ($RequirementYear == 2) {
					$totalRequirement = $row2 -> Y2;
				} else if ($RequirementYear == 3) {
					$totalRequirement = $row2 -> Y3;
				}
			} else {
				$totalRequirement = 0;
			}

			$cellData[$l++] = $totalRequirement;
			$grandTotalRequirements+=$totalRequirement;
			$subtotal = 0;				
			for ($j = 0; $j < count($dynamicColumns); $j++) {

				$FundingSourceId = $dynamicColumns[$j]['FundingSourceId'];
				$sql = "select * from t_yearly_pledged_funding 
                where CountryId='" . $CountryId . "' 
                and Year='" . $Year . "' 
                and ItemGroupId='" . $ItemGroupId . "' 
                and FormulationId='" . $FormulationId . "' 
                and FundingSourceId='" . $FundingSourceId . "' ";
				
				$result3 = mysql_query($sql);
				$total3 = mysql_num_rows($result3);
				if ($total3 == 0) {
					$subtotal += 0;
					$cellData[$l++] = 0;					
				} else {
					$row3 = mysql_fetch_object($result3);
					$subtotal += $row3 -> TotalFund;
					$cellData[$l++ ] = $row3 -> TotalFund;
				}
				$grandFundingTotal[$q][$j]=$row3 -> TotalFund;

			}
			$cellData [$l++] = $subtotal;
			$grandSubTotal+=$subtotal;
			$surplus = $totalRequirement - $subtotal;
			if ($surplus >= 0){
				$cellData[ $l++] =number_format($surplus);
				$grandGapSurplus+=$surplus;
			}
			else{
				$cellData[ $l++] = '(' . number_format((-1) * $surplus ). ')';
				$grandGapSurplus+=$surplus;
			}
			$cellData[ $l++] = $ItemGroupId;
			$cellData[ $l++] = $FormulationId;
			
			$rowData[] = $cellData;
			
			if($p==$total-1){
				$l = 0;		
				$cellData = array();
				$cellData[$l++]=$groupsubTmp;	
				$cellData[$l++]='Total';
				$cellData[$l++]=$grandTotalRequirements;				
				for ($j = 0; $j < count($dynamicColumns); $j++) {				
					$subtotal=0;
					for ($k = 0; $k < count($grandFundingTotal); $k++) 
						$subtotal+=$grandFundingTotal[$k][$j];
					$cellData[$l++]=$subtotal;	
					$superGrandFundingTotal[$r][$j]=$subtotal;
				}			
				$cellData[$l++]=$grandSubTotal;
				if ($grandGapSurplus >= 0){
					$cellData[ $l++] =number_format($grandGapSurplus);					
				}
				else{
					$cellData[ $l++] = '(' . number_format((-1) * $grandGapSurplus ). ')';					
				}	
				$cellData[ $l++] = $ItemGroupId;
				$cellData[ $l++] = $FormulationId;
				$rowData[] = $cellData;				
				
				$superGrandTotalRequirements+=$grandTotalRequirements;
				$superGrandSubTotal+=$grandSubTotal;
				$superGrandGapSurplus+=$grandGapSurplus;
				$r++;
			
				$l = 0;		
				$cellData = array();
				$cellData[$l++]=$groupsubTmp;	
				$cellData[$l++]='Grand Total';
				$cellData[$l++]=$superGrandTotalRequirements;	
				
				for ($j = 0; $j < count($dynamicColumns); $j++) {				
					$subtotal=0;
					for ($k = 0; $k < count($superGrandFundingTotal); $k++) 
						$subtotal+=$superGrandFundingTotal[$k][$j];
					$cellData[$l++]=$subtotal;					
				}			
				$cellData[$l++]=$superGrandSubTotal;
				if ($superGrandGapSurplus >= 0){
					$cellData[ $l++] =number_format($superGrandGapSurplus);					
				}
				else{
					$cellData[ $l++] = '(' . number_format((-1) * $superGrandGapSurplus ). ')';					
				}	
				$cellData[ $l++] = $ItemGroupId;
				$cellData[ $l++] = $FormulationId;
				$rowData[] = $cellData;
			}
		
		$p++;$q++;
		
		}
		
		$rResult=array();$data=array();$k=0;
		$x=0; $f=0; $groupsubtotal=0;$groupsubTmp='-1';
		$endlimit=count($rowData);
		$groupsubTmp=-1;$p=0;
		while(count($rowData)>$x)
		{ 		  
		  $groupsubTmp=$rowData[$x][1];	
		  if($f) { 
			}
		  
		  if($rowData[$x][1]=='Grand Total'){			
			$rowData[$x][1]='';
			$data[$k++]='Grand Total';
		  }else if($groupsubTmp=='Total')	  {
				$rowData[$x][1]='';
				$data[$k++]=$rowData[$x][0];
		  }else{			  
			$f++;
			  if($f==$endlimit) {
			     
				$data[$k++]=$f;
			  }else  {
			     
				$data[$k++]=$f;
			  }
		  }
		  $y=0;
		  while(count($rowData[$x])>$y){		  
			if($y>1&&$y<(count($rowData[$x])-3)){
				//echo  ',"'.number_format($rowData[$x][$y]).'"'; 
				$data[$k++]=number_format($rowData[$x][$y]);
			}else{
				//echo  ',"'.$rowData[$x][$y].'"';  
				$data[$k++]=$rowData[$x][$y];
			}
			$y++; 
		  } 
		  
		  
		  //echo ']'; 
		  
		  $x++;
		  $rResult[]=$data;
		  $k=0;
		  //break;
		}
		$tbody='';
		$x=0;
		$tempGroupId='';
		while(count($rResult)>$x){			
			$tbody.= '<tr>';
			$k=0;
			
			if($tempGroupId!=$rResult[$x][1]) {
				$tbody.='<td style="background-color:#DAEF62;border-radius:2px;  align:center;" colspan="'.(count($rResult[$x])-3).'">'.$rResult[$x][1].'</td>'; 
				$tempGroupId=$rResult[$x][1];
				$tbody.= '</tr><tr>';
			}

    $f=0;
			while(count($rResult[$x])-2>$k){				
				if($k==1){
				}else{
					$style='';
					if($rResult[$x][0]=='Grand Total')
					{
					  $d=$rResult[$x][$k]; 
						$style=' style="background-color:#50ABED;color:#ffffff;border-radius:2px;  align:center;" ';
					}
					else if(is_int($rResult[$x][0])==false) 
					{
						$style=' style="background-color:#FE9929;border-radius:2px;  align:center; " ';
						
						$d++;
						
						$f++;
						
						if($f==1) $d=$rResult[$x][$k].' Total';
						else $d=$rResult[$x][$k]; 
					}
					else
						{
							$d=$rResult[$x][$k];
						} 
					    $tbody.= '<td '.$style.'>';
						$tbody.= $d;
					    $tbody.= '</td>';
			
					   	}
				$k++;
			}			
			$tbody.='</tr>';
			$x++;
		    }	
			echo '<div class="row"> 
            <div class="panel panel-default table-responsive" id="grid_country">
           	<div class="padding-md clearfix">
           	<div class="panel-heading">
             <h3>'.$gTEXT['Pledged Funding'].$gTEXT['MonetaryTitle'].'<h3>
            </div>	
		    <table class="table table-striped display" id="gridDataCountry">
    				<thead>    				  				
    					<tr>
						    <th>SL</th>
							<th>'.$gTEXT['Category'].'</th>
						    <th >'.$gTEXT['Total Requirements'].'</th>';
			/*===Funding Source List=*/
			$sql = "select f.FundingSourceId,s.FundingSourceName from t_yearly_country_fundingsource f
			Inner Join t_fundingsource s on s.FundingSourceId=f.FundingSourceId
			where  CountryId='" . $CountryId . "' and Year='" . $Year . "' 
			Order By FundingSourceName asc ";
			$resultPre = mysql_query($sql);
			$total = mysql_num_rows($resultPre);
			$k=0;$odd=1;
			while ($row = mysql_fetch_object($resultPre)) {
				if($k%2==0){
					echo ' <th style="text-align: left;">'.$row -> FundingSourceName.'</th>';	
					
					$odd=0;
				}else{
					echo ' <th style="text-align: left;">'.$row -> FundingSourceName.'</th>';	
					
					$odd=1;
				}
				$k++;
			}
			/*===Funding Source List=*/
			echo  '<th style="text-align: left;">'.$gTEXT['Total'].'</th>
				   <th style="text-align: left;">'.'Gap/Surplus'.'</th>
			
		                ';	
		echo '  </tr></thead>';	
		
	}	
			
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


function getStockStatusAtFacility()
{ 

    global $gTEXT;
	global $jBaseUrl;
	$monthId = $_REQUEST['MonthId'];
	$year = $_REQUEST['YearId'];
	$countryId = $_REQUEST['CountryId'];
	$itemGroupId = $_REQUEST['ItemGroupId'];
	$itemNo = $_REQUEST['ItemNo'];
	$regionId = $_REQUEST['RegionId'];
	$fLevelId = $_REQUEST['FLevelId'];
	
	
    $CountryName = $_REQUEST['CountryName'];
	$MonthName = $_REQUEST['MonthName'];
	$Year = $_REQUEST['Year'];
	$ItemGroupName = $_REQUEST['ItemGroupName'];
	$ItemName = $_REQUEST['ItemName'];
	$RegionName = $_REQUEST['RegionName'];
	$FLevelName = $_REQUEST['FLevelName'];
	$sQuery = "SELECT  " . $serial . ",
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


	$r= mysql_query($sQuery) ;
	$i=1;	
	if ($r)
	{
		echo '<!DOCTYPE html>
			<html>
			<head>
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
            <h3 style="text-align:center;">'.$gTEXT['Stock Status at Facility Level'].' on '.$MonthName.' '.$Year.'<h3>
			<p style="text-align:center; font-size:14px;">
			 Country : '.$CountryName.',&nbsp; Product Group : '.$ItemGroupName.',&nbsp; Facility Level: '.$FLevelName.'</p>
			  <p style="text-align:center; font-size:14px;">Product Name : '.$ItemName.',&nbsp; Region: '.$RegionName.'</p>
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
		while($rec=mysql_fetch_array($r))
		{
			echo '<tr>
		 	      <td style="text-align: center;">
			      '.$i.'
			      </td>
			      <td style="text-align: left;">
			     '.$rec['FacilityName'].'
			     </td>
			      <td style="text-align: Right;">
			      '.($rec['ClStock']==''? '':number_format($rec['ClStock'])).'
			     </td>
			      <td style="text-align:Right;">
			     '.($rec['AMC']==''? '':number_format($rec['AMC'])).'
			     </td>
			      <td style="text-align: Right;">
			     '.($rec['MOS']==''? '':number_format($rec['MOS'],1)).'
			     </td>
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
    }		
	else
	{
		$error = 0;	
		echo $error;
	}
	
}

function getFundingStatusData()
{ 

    global $gTEXT;
 	global $jBaseUrl;
	$Year = $_GET['Year'];    
    $CountryId = $_GET['Country']; 
	
	if(isset($_GET['Country'])&&!empty($_GET['Country'])){
		$countryQuery=" and p.CountryId='".$CountryId."' ";
	}else{
		$countryQuery="";
	}
	
	$sql="	SELECT g.GroupName,f.FormulationName,r.FundingReqId,r.ItemGroupId,r.Y1,r.Year,sum(p.TotalFund) Total from t_yearly_pledged_funding p
			Inner Join t_yearly_funding_requirements r on r.FormulationId=p.FormulationId and r.Year=p.Year and r.CountryId=p.CountryId
			Inner Join t_formulation f on f.FormulationId=r.FormulationId
			Inner Join t_itemgroup g on g.ItemGroupId =f.ItemGroupId 
			where p.Year='".$Year."' ".$countryQuery."
			group by g.GroupName,p.FormulationId ";
			
	$r= mysql_query($sql) ;
	$i=1;	
	if ($r)
		
	{
		
		echo '<!DOCTYPE html>
			<html>
			<head>
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
			echo '<div class="row"> 
	      <div class="panel panel-default table-responsive" id="grid_country">
           <div class="padding-md clearfix">
           	<div class="panel-heading">
              <h3>'.$gTEXT['Funding Status'].'<h3>
            </div>	
    			<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    				</thead>
    				<tbody>
    					<tr>
						    <th style="text-align: left;">SL#</th>
						    <th style="text-align: left;">'.$gTEXT['Category'].'</th>
						    <th style="text-align: right;">'.$gTEXT['Planned'].'</th>
						    <th style="text-align: right;">'.$gTEXT['Actual'].'</th>
		                </tr>';
			$tempGroupId='';
		while($rec=mysql_fetch_array($r))
		{
			 if($tempGroupId!=$rec['GroupName']) 
		   {
		   	 	echo'<tr>
                     <td style="background-color:#DAEF62;border-radius:2px;align:center;" colspan="4">'.$rec['GroupName'].'</td>
                   </tr>'; 
			   $tempGroupId=$rec['GroupName'];
		   } 
	
			echo '<tr>
		 	      <td style="text-align: left;">
			      '.$i.'
			      </td>
			      <td style="text-align: left;">
			     '.$rec['FormulationName'].'
			     </td>
			     <td style="text-align: right;">
			     '.number_format($rec['Y1']).'
			     </td>
				<td style="text-align: right;">
			     '.number_format($rec['Total']).'
			     </td>
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
    }		
	else
	{
		$error = 0;	
		echo $error;
	}
	
}

 function getMosType()
{ 
    global $gTEXT;
    global $jBaseUrl;
	$CountryId=$_GET['CountryId']; 
	$FacilityId=$_GET['FacilityId']; 
    $MonthId=$_GET['MonthId']; 
	$YearId=$_GET['YearId'];
    $ItemGroupId=$_GET['ItemGroupId'];
    $mosTypeId = $_REQUEST['MosTypeId'];
    $CountryName=$_GET['CountryName'];   
    $MonthName = $_GET['MonthName'];
    $ItemGroupName = $_GET['ItemGroupName'];
	  

	$sQuery = "SELECT MosTypeId, MosTypeName, MinMos, MaxMos, ColorCode 
			FROM
			    t_mostype
			WHERE (MosTypeId = $mosTypeId OR $mosTypeId = 0)
			ORDER BY MosTypeId;";


	$rResult = mysql_query($sQuery);
	$output = array();

   $r= mysql_query($sQuery) ;
	$i=1;	
	if ($r)
		
	{
		
		echo '<!DOCTYPE html>
			<html>
			<head>
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
           	<h3 style="text-align:center;">'.$gTEXT['Facility Inventory Control'].'<h3>
		    </div>
		     <div class="clearfix">
	            		<h4 style="text-align:center;">'.$gTEXT['Country Name'].':'.$CountryName.'   ,   '.$gTEXT['Product Group'].':'.$ItemGroupName.' <h4>
						<h4 style="text-align:center;">'.$gTEXT['Month'].':'.$MonthName.'   ,   '.$gTEXT['Year'].':'.$year.'<h4>
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

				$sQuery = "SELECT p.MosTypeId, ItemName, MOS FROM (SELECT
				    a.ItemNo
				    , b.ItemName
				    , a.MOS
					,(SELECT MosTypeId FROM t_mostype x WHERE (MosTypeId = $mosTypeId OR $mosTypeId = 0) AND a.MOS >= x.MinMos AND a.MOS < x.MaxMos) MosTypeId
					FROM t_cnm_stockstatus a, t_itemlist b,  t_cnm_masterstockstatus c
					WHERE a.itemno = b.itemno AND a.MOS IS NOT NULL AND a.MonthId = " . $_REQUEST['MonthId'] . " AND a.Year = '" . $_REQUEST['YearId'] . "' AND a.CountryId = " . $_REQUEST['CountryId'] . " AND a.ItemGroupId = " . $_REQUEST['ItemGroupId'] . " AND a.CNMStockId = c.CNMStockId" . " AND c.StatusId = 5 " . ") p
					WHERE (p.MosTypeId = $mosTypeId OR $mosTypeId = 0) 
					ORDER BY ItemName";

						$rResult = mysql_query($sQuery);
						$aData = array();

	while ($row = mysql_fetch_array($rResult)) {
		
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
    }		
	else
	{
	$error = 0;	
		echo $error;
	}
	
}
function getcheckBox($v){ 
    if ($v == "true") {
        $x="<input type='checkbox' checked class='datacell' value='".$v."' /><span class='custom-checkbox'></span>";
    } else {
        $x="<input type='checkbox' class='datacell' value='".$v."' /><span class='custom-checkbox'></span>";
    } 
    return $x;
}

function getPatientTrendTimeSeriesChart()
{
		
	 global $gTEXT;
 	global $jBaseUrl;
	$Year=$_GET['Year'];
	$Month=$_GET['Month'];
    $CountryId=$_GET['Country'];
    $CountryName = $_GET['CountryName'];
	$MonthName = $_GET['MonthName'];
	$Year = $_GET['Year'];
	
	
	 
	  $sql = "SELECT a.ServiceTypeId, IFNULL(SUM(c.TotalPatient),0) TotalPatient
                FROM t_servicetype a
                INNER JOIN t_formulation b ON a.ServiceTypeId = b.ServiceTypeId
                LEFT JOIN t_cnm_patientoverview c ON (c.FormulationId = b.FormulationId AND c.MonthId = ".$monthIndex." AND c.Year = '".$yearIndex."' 
                AND (c.CountryId = ".$CountryId." OR ".$CountryId." = 0))  		                       
                GROUP BY a.ServiceTypeId
		        ORDER BY a.ServiceTypeId ";
			  
				 
	$r= mysql_query($sql) ;
	$i=1;	
	if ($r)
		
	{
		
		echo '<!DOCTYPE html>
			<html>
			<head>
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
			  <h3 style="text-align:center;">'.$gTEXT['Patient Trend Time Series '].' on '.$MonthName.' '.$Year.'<h3>
			 <p style="text-align:center; font-size:14px;">
			 Country : '.$CountryName.'</p>
			  			  
            </div>	
    			<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    				</thead>
    				<tbody>
    					<tr>
    					    <th>SL#</th>
						    <th>'.$gTEXT['Products'].'</th>
						    <th style="text-align: right;">'.$gTEXT['Reported Consumption'].'</th>
						     <th style="text-align: right;">'.$gTEXT['Reported Closing Balance'].'</th>
						      <th style="text-align: right;">'.$gTEXT['Average Monthly Consumption'].'</th>
						      <th style="text-align: right;">'.$gTEXT['MOS'].'</th>
		                </tr>';
						
						
		while($rec=mysql_fetch_array($r))
		{
				 
			echo '<tr>
			       <td style="text-align: center;">
			     '.$i.'
			     </td>
			       <td>
			     '.$rec['ItemName'].'
			     </td>
		         <td style="text-align: right;">
			     '.($rec['ReportedConsumption']==''? '':number_format($rec['ReportedConsumption'])).' 
			     </td>
	               <td style="text-align: right;">
			     '.($rec['ReportedClosingBalance']==''? '':number_format($rec['ReportedClosingBalance'])).'
			     </td>
			       <td style="text-align: right;">
			     '.($rec['ReportedConsumption']==''? '':number_format($rec['ReportedConsumption'])).' 
			     </td>
	
			     <td style="text-align: right;">
			     '.($rec['MOS']==''? '':number_format($rec['MOS'],1)).'
			     </td>
	
	
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
    }		
	else
	{
		
	 
		$error = 0;	
		echo $error;
	}
	
	
}	
		
?>