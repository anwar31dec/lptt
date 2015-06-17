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

//$username = $_GET['userName'];
$username = isset($_GET['userName'])? $_GET['userName'] :'';
//$name = $_GET['Name'];
$name = isset($_GET['Name'])? $_GET['Name'] :'';

//$BShow = isset($_POST['Name'])? $_POST['Name'] : 'false';


    $sWhere = "";
	if ($_GET['sSearch'] != "") { 
        $sWhere = " AND (name like '%".mysql_real_escape_string($_GET['sSearch'])."%')";                                                                                         
	}
 $sql = "SELECT SQL_CALC_FOUND_ROWS a.id, name, username, GROUP_CONCAT(title SEPARATOR ', ') title
             FROM ykx9st_users a
             INNER JOIN ykx9st_user_usergroup_map b ON a.id = b.user_id 
             INNER JOIN ykx9st_usergroups c ON b.group_id = c.id           
             WHERE b.group_id IN(3, 10, 11, 12, 13, 14, 15)  ".$sWhere." GROUP BY a.id, name, username ORDER BY name ASC";
    /*$sql = "SELECT  a.id, name, username, c.title
            FROM j323_users a
            INNER JOIN j323_user_usergroup_map b ON a.id = b.user_id 
            INNER JOIN j323_usergroups c ON b.group_id = c.id         
            WHERE b.group_id IN(3, 10, 11, 12, 13, 14, 15) ".$sWhere." order by title, name";*/
			
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
				<link href="'.$jBaseUrl.'administrator/components/com_jcode/source/media/datatable-bootstrap/dataTables.bootstrap.css" rel="stylesheet"/>	
				<link rel="stylesheet" href="'.$jBaseUrl.'templates/protostar/css/template.css" type="text/css"/> 
				<link href="'.$jBaseUrl.'administrator/components/com_jcode/source/media/datatable-bootstrap/dataTables.bootstrap.css" rel="stylesheet"/>
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
                    <h3 style="text-align:center;">'.$gTEXT['Country User Map List'].'<h3>
                </div>	
                <table class="table table-striped table-bordered display table-hover" cellspacing="0" id="gridDataCountry">
                <thead>
                </thead>
                <tbody>
  				<tr>
  				    <th style="text-align: center;">SL#</th>
  				    <th>'.$gTEXT['User Name'].'</th>
  				    <th>'.$gTEXT['User Group'].'</th>
                    <th>'.$gTEXT['Country Name'].'</th>
                    <th>'.$gTEXT['Product Group'].'</th>
                    <th>'.$gTEXT['Owner Type'].'</th>
                    <th>'.$gTEXT['Region List'].'</th>
            	</tr>';
                //Mapping User Countries
  	$tempGroupId='';
  	while($rec=mysql_fetch_array($r)){
  	 
        $data = '';
        $data1 = '';
		$data2 = '';
		$data5 = '';
		  
      	/*if($tempGroupId!=$rec['title']){
      		echo'<tr>
                  	<td style=background-color:#DAEF62;border-radius:2px; font-size:9px;align: center;color:#00000000; colspan="6">'.$rec['title'].'</td>
                   </tr>'; 
      		$tempGroupId=$rec['title'];
      		}*/
            
            $sql_2 = "  SELECT CountryName
                        FROM t_user_country_map a 
                        INNER JOIN t_country b ON (a.CountryId = b.CountryId AND a.UserId = '".$rec['username']."')
                        ORDER BY CountryName ";
            $r_2 = mysql_query($sql_2) ;
            
            $h = 0;
            while($rec_2 = mysql_fetch_array($r_2)){
               if($h++) $data.= ", ";
               $data.= $rec_2['CountryName']; 
                                                        
            } 
			 $sql_3 = " SELECT  a.ItemGroupMapId, a.UserId, b.ItemGroupId, GroupName
			            FROM t_user_itemgroup_map a 
			            INNER JOIN t_itemgroup b ON (a.ItemGroupId = b.ItemGroupId AND a.UserId ='".$rec['username']."')
			            ORDER BY GroupName ";
            $r_3 = mysql_query($sql_3) ;
            
            $h = 0;
            while($rec_3 = mysql_fetch_array($r_3)){
               if($h++) $data1.= ", ";
               $data1.= $rec_3['GroupName']; 
                                                        
            } 
             $sql_4 = " SELECT  a.OwnerTypeMapId, a.UserId, b.OwnerTypeId, OwnerTypeName
						 FROM t_user_owner_type_map a 
						 INNER JOIN t_owner_type b ON (a.OwnerTypeId = b.OwnerTypeId AND a.UserId = '".$rec['username']."')
						 ORDER BY OwnerTypeName";
            $r_4 = mysql_query($sql_4) ;
            
            $h = 0;
            while($rec_4 = mysql_fetch_array($r_4)){
               if($h++) $data2.= ", ";
               $data2.= $rec_4['OwnerTypeName']; 
                                                        
            } 
		
		$sql_5 = " SELECT SQL_CALC_FOUND_ROWS a.RegionMapId, a.UserId, b.RegionId, IF(a.RegionMapId IS NULL,'false','true') chkValue, RegionName
					FROM t_user_region_map a 
					INNER JOIN t_region b ON (a.RegionId = b.RegionId AND a.UserId = '".$rec['username']."')
					ORDER BY RegionName;";
            $r_5 = mysql_query($sql_5) ;
            
            $h = 0;
            while($rec_5 = mysql_fetch_array($r_5)){
               if($h++) $data5.= ", ";
               $data5.= $rec_5['RegionName']; 
                                                        
            }		
  			echo '<tr>
  					<td style="text-align: center;">'.$i.'</td>
  			      	<td>'.$rec['name'].'</td>
  			      	<td>'.$rec['title'].'</td>
                    <td>'.$data.'</td>
                    <td>'.$data1.'</td> 
                    <td>'.$data2.'</td>                  
                    <td>'.$data5.'</td>                  
  			     </tr>';
  				 
  			$i++; 
  	}
  	echo'</thead>
        </table>
        </div>
        </div>  
        </div>';
    echo'</body></html>';	
      }	else{
 				$error = "No record found.";	
  				echo $error;
  	}
   
    

function getcheckBox($v){ 
    if ($v == "true") {
        $x="<input type='checkbox' checked class='datacell' value='".$v."' /><span class='custom-checkbox'></span>";
    } else {
        $x="<input type='checkbox' class='datacell' value='".$v."' /><span class='custom-checkbox'></span>";
    } 
    return $x;
}

?>