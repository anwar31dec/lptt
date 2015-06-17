<?php
include("../define.inc");
$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());

include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');

mysql_query('SET CHARACTER SET utf8');

$gTEXT = $TEXT;
$jBaseUrl = $_GET['jBaseUrl']; 
   
    $ItemGroupId = $_GET['ItemGroupId']; 
    $ItemGroupName = $_GET['ItemGroupName'];  
    $CountryId = $_GET['CountryId'];
    $CountryName = $_GET['CountryName'];  
	$RegimenId = $_GET['RegimenId'];  
	
	if($RegimenId != ""){
		$RegimenId = "WHERE a.RegimenId = ".$RegimenId." ";
	}

    $sql = "SELECT a.RegimenId, b.FormulationName, RegimenName, ItemName, PatientPercentage, OptionId
			FROM t_regimen a
            INNER JOIN t_formulation b ON a.FormulationId = b.FormulationId AND b.ItemGroupId = '".$ItemGroupId."'
            INNER JOIN t_regimenitems c ON a.RegimenId = c.RegimenId AND c.CountryId = '".$CountryId."'
            INNER JOIN t_itemlist e ON c.ItemNo = e.ItemNo 
            INNER JOIN t_country f ON c.CountryId = f.CountryId 
            ".$RegimenId. " order by b.FormulationName, RegimenName, a.RegimenId, OptionId,ItemName"; 
    mysql_query("SET character_set_results=utf8");                   
	$r= mysql_query($sql) ;
	$total = mysql_num_rows($r);
 
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
                    <div class="col-md-7"> 
                        <div class="panel panel-default table-responsive" id="grid_country">                       
                        <div class="padding-md clearfix">
                            <div class="panel-heading">
                                <h3 style="text-align:center;">'.$gTEXT['Regimen Item List'].' of '.$CountryName.'<h3>                           
                            </div>
                            <table class="table table-striped display" id="gridDataCountry">
                                <thead>
                                </thead>
                                <tbody>
                                    <tr>                                                                      
                                        <td>'.$gTEXT['Regimen Name'].' </td>
                                        <td>'.$gTEXT['Combination'].' </td>
                                        <td>'.$gTEXT['Regimen Item Name'].' </td>
                                        <td>'.$gTEXT['Percentage'].' </td>
                                    </tr>'; 

// 		               

        $tempGroupId='';
        $i=1;	
        
        while($rec=mysql_fetch_object($r)){
           $arr[] = $rec;	  
        }  
        
        $dataLength = count($arr);
        $data = $arr;
        
        $regimen_all = array();      
        for ($i = 0; $i < $dataLength; $i++) {		   
            array_push($regimen_all, $data[$i]->RegimenId);       
        }
                   
        $uregimen_id = array_values(array_unique($regimen_all));        
        $count = array();
        $p = '';      
        foreach($data as $value){   
            if($p != $value->RegimenId){$j = 1;}        
            $levelID = $value->RegimenId;       
            $p = $levelID;
                              
            for($i = 0; $i<count($uregimen_id); $i++){                   
                if($uregimen_id[$i] == $levelID){
                    $count[$i] = $j;
                    $j++;
                }                              
            }     
        }
        
        $x = 0;
        $temp = " ";
        
        for ($y = 0; $y < $dataLength; $y++) {
        	if($tempGroupId!=$data[$y]->FormulationName) 
				   {
				   	 	echo'<tr>
		                     <td style="background-color:#DAEF62;border-radius:2px;align:center;font-size:14px;"colspan="5">'.$data[$y]->FormulationName.'</td>
		                   </tr>'; 


					   $tempGroupId=$data[$y]->FormulationName; 
				    }
			 	            
            echo "<tr>";            
            if ($temp != $data[$y]->RegimenId) {
                echo "<td rowspan=".$count[$x]." valign='middle' align='center' style='padding:5px;'><b>".$data[$y]->RegimenName."<b></td>"; 
                $x++; 
            }
            $temp = $data[$y]->RegimenId;
        
            echo "<td align=left>Combination - ".$data[$y]->OptionId."</td>
            	 <td align=right>".$data[$y]->ItemName."</td>
	    	     <td align=right>".$data[$y]->PatientPercentage."%</td>
                 </tr>";        			
        }
      
        echo  "</table></body></html>";
        
       // print_r($count); 
         
      }  
        
        
        
        
        
        
        
        
        
    
        
	   
/*	    $dataLength = count($arr);	 
		$data = $arr;
	    $temp = " ";
	    $count1 = 0; 
		$count2=0;    
		$tmpRegimenName='';$tmpOptionId='';
	    $count = array();
		$countcombination = array();$countcombination1=array();
	    for ($i = 0; $i < $dataLength; $i++) {
			      if ($data[$i] -> RegimenName!=$tmpRegimenName) 
			       {
			       	 if($i!=0)
					 {
					 	 $count[]=$count1;
						 $count1=0; 
					 }
					 $tmpRegimenName=$data[$i] -> RegimenName;
					 $count1++; 
				   }
				   else 
				   {  
					   $count1++; 
				   }
				if($i==$dataLength-1) $count[]=$count1;
				
				
				
				 

				
	        
	    } 
		
		 for ($i = 0; $i < $dataLength; $i++) {
			      
				 if($tmpOptionId!=$data[$i] -> RegimenName.$data[$i] -> OptionId)
				  
			        {
			       	  if($i!=0)
					  {
					 	  $countcombination[$data[$i] -> RegimenName.$data[$i] -> OptionId]=$count2;
						  $countcombination1[]=$tmpOptionId;
						  $count2=0; 
					 }
					  $tmpOptionId=$data[$i] -> RegimenName.$data[$i] -> OptionId;
					  $count2++; 
				    }
				    else 
				    {  
					    $count2++; 
				    }
				 if($i==$dataLength-1)
				 {
				 	 $countcombination[$data[$i] -> RegimenName.$data[$i] -> OptionId]=$count2;
					 $countcombination1[]=$tmpOptionId;
					 
				 }

			
	      
	    } 
		print_r($count);
		 
		print_r($countcombination);
	    $j=0; $tempRegimenNameOptionId='';
	    for ($i = 0; $i <$dataLength; $i++) {
	    	if($tempGroupId!=$data[$i]->FormulationName) 
				   {
				   	 	echo'<tr>
		                     <td style="background-color:#DAEF62;border-radius:2px;align:center;font-size:14px;"colspan="5">'.$data[$i]->FormulationName.'</td>
		                   </tr>'; 


					   $tempGroupId=$data[$i]->FormulationName; 
				    }
           echo "<tr>";

			    if ($temp != $data[$i]->RegimenName) {
	            echo "<td rowspan=".$count[$j]." align=left style='padding:5px;'><b>".$data[$i]->RegimenName."<b></td>";

				 
				for($x=0; $x <count($countcombination); $x++) {
					
					 
					 
					if(trim($countcombination1[$x])==trim($data[$i]->RegimenName.$data[$i]->OptionId," "))
					{
						$tempRegimenNameOptionId=$data[$i]->RegimenName.$data[$i]->OptionId;
					 echo "<td rowspan=".$countcombination[trim($data[$i]->RegimenName.$data[$i]->OptionId," ")]." align=left style='padding:5px;'><b>Combination-".$data[$i]->OptionId."<b></td>";  
					break;
					}
					
				}	
			    
				 $j++;
				 
			 }
				 
					
					if($tempRegimenNameOptionId!=$data[$i]->RegimenName.$data[$i]->OptionId)
					for($x=0; $x <count($countcombination); $x++) { 
					 
						if(trim($countcombination1[$x])==trim($data[$i]->RegimenName.$data[$i]->OptionId," "))
						{
							$tempRegimenNameOptionId=$data[$i]->RegimenName.$data[$i]->OptionId;
						 echo "<td rowspan=".$countcombination[trim($data[$i]->RegimenName.$data[$i]->OptionId," ")]." align=left style='padding:5px;'><b>Combination-".$data[$i]->OptionId."<b></td>";  
						break;
						}
					
				    }
				 
				
				 
			   $temp = $data[$i]->RegimenName;
		echo " 
	    	 <td align=right>".$data[$i]->ItemName."</td>
	    	 <td align=right>".$data[$i]->PatientPercentage."%</td>
	         </tr>";


		   }
	      }
	    echo  "</table></body></html>"; */

	  

?>