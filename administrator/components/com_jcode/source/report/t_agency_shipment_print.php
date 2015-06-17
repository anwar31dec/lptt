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
    
    $CountryId=$_GET['ACountryId']; 
    $AFundingSourceId=$_GET['AFundingSourceId']; 
    $ASStatusId=$_GET['ASStatusId']; 
    
    $CountryName = $_GET['CountryName'];
    $FundingSourceName = $_GET['FundingSourceName'];
    $ShipmentStatusDesc = $_GET['ShipmentStatusDesc'];
    	
    $ItemGroup = $_GET['ItemGroup']; 
    $OwnerTypeId = $_GET['OwnerType']; 
    $ItemGroupName = $_GET['ItemGroupName'];
    $OwnerTypeName = $_GET['OwnerTypeName'];
	
	
    if($CountryId){
		$CountryId = " WHERE a.CountryId = '".$CountryId."' ";
	}
    if($AFundingSourceId){
		$AFundingSourceId = " AND a.FundingSourceId = '".$AFundingSourceId."' ";
	}   
    if($ASStatusId){
		$ASStatusId = " AND a.ShipmentStatusId = '".$ASStatusId."' ";
	}
    if($ItemGroup){
		$ItemGroup = " AND a.ItemGroupId = '".$ItemGroup."' ";
	} 
    if($OwnerTypeId){
		$OwnerTypeId = " AND a.OwnerTypeId = '".$OwnerTypeId."' ";
	} 
	
	
	 $sWhere = "";
	if ($_GET['sSearch'] != "") {
		
		 $sSearch=str_replace("|","+", $_GET['sSearch']);
	 
		$sWhere = " AND (a.ShipmentDate LIKE '%" . mysql_real_escape_string($sSearch) . "%'  OR " .
				    "a.Qty LIKE '%".mysql_real_escape_string($sSearch)."%' OR ".
                    "GroupName LIKE '%".mysql_real_escape_string($sSearch)."%' OR ".
                    "ItemName LIKE '%".mysql_real_escape_string($sSearch)."%' OR ".
				    "ShipmentStatusDesc LIKE '%" . mysql_real_escape_string($sSearch) . "%' OR ".
				    "g.OwnerTypeName LIKE '%" . mysql_real_escape_string($sSearch) . "%') ";
	}
	    
	
    $sql ="SELECT  AgencyShipmentId, a.FundingSourceId, d.FundingSourceName, a.ShipmentStatusId, c.ShipmentStatusDesc, a.CountryId, 
            b.CountryName, a.ItemNo, e.ItemName, a.ShipmentDate, a.Qty, f.GroupName,a.ItemGroupId,a.OwnerTypeId, g.OwnerTypeName 
			FROM t_agencyshipment as a
            INNER JOIN t_country b ON a.CountryId = b.CountryId
            INNER JOIN t_shipmentstatus c ON a.ShipmentStatusId = c.ShipmentStatusId
            INNER JOIN t_fundingsource d ON a.FundingSourceId= d.FundingSourceId
            INNER JOIN t_itemlist e ON a.ItemNo = e.ItemNo 
			INNER JOIN t_itemgroup f ON a.ItemGroupId = f.ItemGroupId 
            INNER JOIN t_owner_type g ON a.OwnerTypeId = g.OwnerTypeId
            ".$CountryId." ".$AFundingSourceId." ".$ASStatusId." " .$ItemGroup." " .$OwnerTypeId."
			$sWhere ORDER BY ShipmentStatusDesc,ItemName ";	
			//FundingSourceName asc
	mysql_query("SET character_set_results=utf8");     	  
	$r= mysql_query($sql) ;
    $total = mysql_num_rows($r); 
    
	$i=1;
	if ($total>0){
		echo '<!DOCTYPE html>
            <html>
            <head>
             <meta name="viewport" content="width=device-width, initial-scale=1.0" />	
			 <base href="http://localhost/warp/index.php/fr/adminfr/country-fr" />
			 <meta http-equiv="content-type" content="text/html; charset=utf-8" />
			 <meta name="generator" content="Joomla! - Open Source Content Management" />
            <link rel="stylesheet" href="'.$jBaseUrl.'templates/protostar/css/template.css" type="text/css"/> 
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
                                    <h3 style="text-align:center;">'.$gTEXT['Shipment Entry of'].'  '.$CountryName.'</h3>
                                    <h4 style="text-align:center; font-size:14px;">'.$gTEXT['Funding Source'].': '.$FundingSourceName.'  ,   '.$gTEXT['Product Group'].': '.$ItemGroupName.'</h4>
                                    <h5 style="text-align:center; font-size:14px;">'.$gTEXT['Shipment Status'].': '.$ShipmentStatusDesc.' ,   '.$gTEXT['Owner Type'].': '.$OwnerTypeName.'</h5>
                                    
                                </div>	
                                <table class="table table-striped display" id="gridDataCountry">
                                    <thead>
                                    <tbody>
                                    <tr>
                                        <th style="text-align: center;">SL#</th>
                                        <th style="text-align: left;">'.$gTEXT['Product Group'].'</th>
                                        <th style="text-align: left;">'.$gTEXT['Item Name'].'</th>
                                        <th style="text-align: left;">'.$gTEXT['Shipment Status'].'</th>
                                        <th style="text-align: left;">'.$gTEXT['Shipment Date'].'</th>
                                        <th style="text-align: left;">'.$gTEXT['Owner Type'].'</th>
                                        <th style="text-align: right;">'.$gTEXT['Quantity'].'</th>
                                     </tr>';
									
						
									
                                    $CountryId='';
                                    $AFundingSourceId='';
                                    $ASStatusId='';
                                    $tempGroupId='';
                            		while($rec=mysql_fetch_array($r)){
                            			        $date = strtotime($rec['ShipmentDate']);
                                                $newdate = date( 'd/m/Y', $date );
        
										
                            			 if($tempGroupId!=$rec['FundingSourceName']){
                            		   	 	echo'<tr >
                                                    <td style=background-color:#DAEF62;border-radius:2px; font-size:9px;align: center;color:#000000; colspan="7">'.$rec['FundingSourceName'].'</td>
                                                 </tr>'; 
                            			   $tempGroupId=$rec['FundingSourceName'];
                            		   }


                            			 echo'<tr>
                                		 	      <td style="text-align: center;">'.$i.'</td>
                                			      <td style="text-align: left;">'.$rec['GroupName'].'</td>
                                			      <td style="text-align: left;">'.$rec['ItemName'].'</td>
                                			      <td style="text-align: left;">'.$rec['ShipmentStatusDesc'].'</td>
                                			      <td style="text-align: left;">'.$newdate.'</td>
                                			      <td style="text-align: left;">'.$rec['OwnerTypeName'].'</td>
                                                  <td style="text-align:right;">'.($rec['Qty']==''? '':number_format($rec['Qty'])).'</td>
                            			     </tr>';
                            				 
                   				    $i++; 
                                    }
                                        echo'</thead>
                                        </table>
                                        </div>
                                        </div>  
                                        </div>';
                                        echo '</body></html>';	
                                        
    } else{
    		$error = "No record found";	
    		echo $error;
	}


?>