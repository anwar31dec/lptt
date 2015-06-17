<?php
include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());

mysql_query('SET CHARACTER SET utf8');

error_reporting(0);

$gTEXT = $TEXT;
$jBaseUrl = $_GET['jBaseUrl']; 


    $months = $_GET['MonthNumber'];    
    $CountryId = $_GET['Country']; 
      
	  
	$r= mysql_query($sql) ;
	$total = mysql_num_rows($r);
 
	if($total>0){
    
    if($CountryId){
		$CountryId = " AND c.CountryId = ".$CountryId." ";
	} 
    	    
    $monthIndex = date("n");
    $yearIndex = date("Y");
    settype($yearIndex, "integer");
    
    if ($monthIndex == 1){
		$monthIndex = 12;				
		$yearIndex = $yearIndex - 1;				
	}else{
	    $monthIndex = $monthIndex - 1;
	}
    	
    $month_name = array();
    $Tdetails = array();         
   	for ($i = 1; $i <= $months; $i++){
   	    
         $sql = "SELECT a.ServiceTypeId, IFNULL(SUM(c.TotalPatient),0) TotalPatient
                FROM t_servicetype a
                INNER JOIN t_formulation b ON a.ServiceTypeId = b.ServiceTypeId
                LEFT JOIN t_cnm_patientoverview c ON (c.FormulationId = b.FormulationId AND c.MonthId = ".$monthIndex." AND c.Year = '".$yearIndex."' 
                AND (c.CountryId = ".$CountryId." OR ".$CountryId." = 0))  		                       
                GROUP BY a.ServiceTypeId
		        ORDER BY a.ServiceTypeId "; 
                    
        $result = mysql_query($sql);
        $total = mysql_num_rows($result); 
        $Pdetails = array();  
        
        if($total>0){      
    		while ($aRow = mysql_fetch_array($result)) {
                $Pdetails['ServiceTypeId'] = $aRow['ServiceTypeId'];
                $Pdetails['MonthIndex'] = $monthIndex;
                $Pdetails['TotalPatient'] = number_format($aRow['TotalPatient']);
                array_push($Tdetails, $Pdetails);  
       	    }
            $mn = date("M", mktime(0,0,0,$monthIndex,1,0));
            $mn = $mn." ".$yearIndex;
            array_push($month_name, $mn);            
        }                           
   	    $monthIndex--;
		if ($monthIndex == 0){
			$monthIndex = 12;   				
			$yearIndex = $yearIndex - 1;			
		}
    }
     
    $art = array();
    $rtk = array();
    $pmtct = array();
    $rmonth_name = array();
    $RTdetails = array();
    
    $rmonth_name = array_reverse($month_name);
    $RTdetails = array_reverse($Tdetails);
    
    foreach($RTdetails as $key => $value){
         $ServiceTypeId = $value['ServiceTypeId'];
         $MonthIndex = $value['MonthIndex'];
         $TotalPatient = $value['TotalPatient'];  
         
         if($ServiceTypeId == 1){
            array_push($art, $TotalPatient);  
         }else if($ServiceTypeId == 2){
            array_push($rtk, $TotalPatient); 
         }else if($ServiceTypeId == 3){
            array_push($pmtct, $TotalPatient); 
         }             		            
    } 
    
    array_unshift($art, "1", "ART");
    array_unshift($rtk, "2", "RTK");
    array_unshift($pmtct, "3", "PMTCT");
    
    $str = ',"COLUMNS":[{"sTitle": "SL", "sWidth":"5%"}, {"sTitle": "Patient Type", "sClass" : "PatientType"}, ';	
    $f=0;
    foreach($rmonth_name as $mon){
        if($f++) $str.=', ';
        $str.= '{"sTitle": "'.$mon.'", "sClass" : "MonthName"}';                           
    }
	$str.= ']}';
           
    echo '{"sEcho": ' . intval($_GET['sEcho']) . ', "iTotalRecords":"10","iTotalDisplayRecords": "10", "aaData":[';    
    echo ''.json_encode($art).', '.json_encode($rtk).', '.json_encode($pmtct).']';   
	echo $str;    	  
       		
	}else{
		echo 'Processing Error';
	}




?>