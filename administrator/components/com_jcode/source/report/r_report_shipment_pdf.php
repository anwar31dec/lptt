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

$task = '';
if (isset($_REQUEST['operation'])) {
	$task = $_REQUEST['operation'];
}

switch($task) {
	case 'generateShipmentReport' :
		generateShipmentReport($conn);		
		break;	
   	default :
		echo "{failure:true}";
		break;
}

/*function numberToMonth($i) {
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
}*/

function generateShipmentReport($conn){
       
    global $gTEXT;        
    require_once('tcpdf/tcpdf.php');
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);  
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    	require_once(dirname(__FILE__).'/lang/eng.php');
    	$pdf->setLanguageArray($l);
    }
    $pdf->AddPage();
    $pdf->SetFillColor(255,255,255);
//=====================================================National Inventory Table=======================================================
/*	$months = $_POST['MonthNumber']; 
    $monthIndex = date("n");
    $yearIndex = date("Y");
    settype($yearIndex, "integer");    
    if ($monthIndex == 1){
		$monthIndex = 12;				
		$yearIndex = $yearIndex - 1;				
	}else{
	    $monthIndex = $monthIndex - 1;
	}
    $months = $months - 1;  
      	   
    $currentYearMonth = $yearIndex."-0".$monthIndex."-"."01";    
    $currentYearMonth = date('Y-m-d', strtotime($currentYearMonth));
	$lastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "-".$months." month"));  
    */
    $StartMonthId = $_POST['StartMonthId']; 
    $StartYearId = $_POST['StartYearId']; 
    $EndMonthId = $_POST['EndMonthId']; 
    $EndYearId = $_POST['EndYearId'];    
	
	if($_POST['MonthNumber'] != 0){
        $months = $_POST['MonthNumber'];
        $monthIndex = date("m");
        $yearIndex = date("Y");
		 settype($yearIndex, "integer");  
		
		$startDate = $yearIndex."-".$monthIndex."-"."01";	
		$startDate = date('Y-m-d', strtotime($startDate));	
		$months--;
		$endDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($startDate)) . "+".$months." month"));  		
    }else{
        $startDate = $StartYearId."-".$StartMonthId."-"."01";	
		$startDate = date('Y-m-d', strtotime($startDate));	
		
		$d=cal_days_in_month(CAL_GREGORIAN,$EndMonthId,$EndYearId);
    	$endDate = $EndYearId."-".$EndMonthId."-".$d;	
		$endDate = date('Y-m-d', strtotime($endDate));	    	
    }  
	
  
    $CountryId = $_POST['ACountryId'];
    $AFundingSourceId = $_POST['AFundingSourceId'];
    $ASStatusId = $_POST['ASStatusId'];
	$ItemGroup = $_POST['ItemGroup']; 
    $OwnerTypeId = $_POST['OwnerType']; 
    
    if($AFundingSourceId){
		$AFundingSourceId = " AND a.FundingSourceId = '".$AFundingSourceId."' ";
	}   
    if($ASStatusId){
		$ASStatusId = " AND a.ShipmentStatusId = '".$ASStatusId."' ";
	}
	 if($ItemGroup){
		$ItemGroup = " AND e.ItemGroupId = '".$ItemGroup."' ";
	}
     if($OwnerTypeId){
		$OwnerTypeId = " AND f.OwnerTypeId = '".$OwnerTypeId."' ";
	}
       
	$sLimit = "";
	if (isset($_POST['iDisplayStart'])) { $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
	}

	$sOrder = "";
	if (isset($_POST['iSortCol_0'])) { 
	    $sOrder = " ORDER BY  ";
		for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_agencyShipment(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
		}
		$sOrder = substr_replace($sOrder, "", -2);
	}

	$sWhere = "";
	if ($_POST['sSearch'] != "") {
		$sWhere = "  AND (a.ItemNo LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'  OR " .
				     " e.ItemName LIKE '%".mysql_real_escape_string( $_POST['sSearch'] )."%' OR ".
				     " c.ShipmentStatusDesc LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%')  ";
	}

    $sql = "SELECT SQL_CALC_FOUND_ROWS AgencyShipmentId, a.FundingSourceId, d.FundingSourceName, a.ShipmentStatusId, c.ShipmentStatusDesc, a.CountryId, 
            b.CountryName, a.ItemNo, e.ItemName, a.ShipmentDate, a.Qty, a.OwnerTypeId, f.OwnerTypeName 
			FROM t_agencyshipment as a
            INNER JOIN t_country b ON a.CountryId = b.CountryId
            INNER JOIN t_shipmentstatus c ON a.ShipmentStatusId = c.ShipmentStatusId
            INNER JOIN t_fundingsource d ON a.FundingSourceId= d.FundingSourceId
            INNER JOIN t_itemlist e ON a.ItemNo = e.ItemNo
            INNER JOIN t_owner_type f ON a.OwnerTypeId = f.OwnerTypeId 
            WHERE CAST(a.ShipmentDate AS DATETIME) BETWEEN CAST('$startDate' AS DATETIME) AND CAST('$endDate' AS DATETIME) 
            AND (a.CountryId = ".$CountryId." OR ".$CountryId." = 0) 
            ".$AFundingSourceId." ".$ASStatusId. " " .$ItemGroup. " " .$OwnerTypeId."
			$sWhere $sOrder $sLimit ";
            
   	$result = mysql_query($sql,$conn);
	$total = mysql_num_rows($result);
    $i=0;	
    $f = 0;
	$GrandtotalQty=0;
	$SubtotalQty=0;
	$OldCountry=' ';
	$NewCountry=' ';
    $serial= 1;
    $tblHTML = '';
    
    if($total>0){
       /* $data=array();
        $f=0; 
        $tblHTML='';
        $tempGroupId='';*/
    	while($rec=mysql_fetch_array($result)){
    		$ItemName = trim(preg_replace('/\s+/', ' ', addslashes($rec['ItemName'])));
            $date = strtotime($rec['ShipmentDate']);
            $newdate = date( 'd/m/Y', $date );
            
    		if($OldCountry==' ')
    			$OldCountry=addslashes($rec['CountryName']);
    		
    		$NewCountry = addslashes($rec['CountryName']);
            		
    		if($OldCountry != $NewCountry){
    			$tblHTML.='<tr >
                       <td style="background-color:#FE9929;border-radius:2px;align:center; font-size:12px;"colspan="5">Sub Total</td>
                       <td style="background-color:#FE9929;border-radius:2px;text-align:right; font-size:12px;">'.number_format($SubtotalQty).'</td>
    				</tr>'; 
    			$tblHTML.='<tr >
                     <td style="background-color:#DAEF62;border-radius:2px;align:center; font-size:12px;"colspan="6">'.$NewCountry.'</td>
                     
                     </tr>'; 
    		   $tempGroupId=$rec['CountryName'];
    		
    			$OldCountry=$NewCountry;			
    			$SubtotalQty=$rec['Qty'];
    		} else
            
                $SubtotalQty+=$rec['Qty'];
                
    		if($tempGroupId!=$rec['CountryName']) {
			   	 $tblHTML.='<tr >
	                     <td style="background-color:#DAEF62;border-radius:2px;  align:center; font-size:12px;" colspan="6">'.$rec['CountryName'].'</td>
	                   </tr>'; 
			     $tempGroupId=$rec['CountryName'];
			 }
            $tblHTML.= '<tr>
                             <td style="text-align: center;">'.$serial++.'</td>
                             <td style="text-align:left;">'.$ItemName.'</td>
                	         <td style="text-align:left;">'.addslashes($rec['FundingSourceName']).'</td>
                             <td style="text-align:left;">'.addslashes($rec['ShipmentStatusDesc']).'</td>
                	         <td style="text-align:right;">'.$newdate.'</td>
                	         <td style="text-align:right;">'.number_format(addslashes($rec['Qty'])).'</td>
        	           </tr>';
	       $GrandtotalQty+=$rec['Qty'];
           $tblHTML.= '';
    		if($total==$i+1) {
                $tblHTML.='<tr >
                       <td style="background-color:#FE9929;border-radius:2px;  align:center; font-size:12px;" colspan="5">Sub Total</td>
                       <td style="background-color:#FE9929;border-radius:2px;  text-align:right; font-size:12px;" ">'.number_format($SubtotalQty).'</td>
                	</tr>';
                    $tblHTML.='<tr >
                       <td style="background-color:#FE9929;border-radius:2px;  align:center; font-size:12px;" colspan="5">Sub Total</td>
                       <td style="background-color:#FE9929;border-radius:2px;  text-align:right; font-size:12px;" ">'.number_format($SubtotalQty).'</td>
                	</tr>';                    
                $tblHTML.='<tr >
                       <td style="background-color:#50ABED;border-radius:2px;  align:center; font-size:12px;" colspan="5">Grand Total</td>
                       <td style="background-color:#50ABED;border-radius:2px;  text-align:right; font-size:12px;" ">'.number_format($GrandtotalQty).'</td>
                	</tr>';
                   $tblHTML.='<tr >
                       <td style="background-color:#50ABED;border-radius:2px;  align:center; font-size:12px;" colspan="5">Grand Total</td>
                       <td style="background-color:#50ABED;border-radius:2px;  text-align:right; font-size:12px;" ">'.number_format($GrandtotalQty).'</td>
                	</tr>';                 
    		}
              $i++;
     }
    
        //$months = $_POST['MonthNumber'];
        $months = $_POST['MonthNumber'];
        $CountryName = $_POST['CountryName'];
        $FundingSourceName=$_POST['FundingSourceName']; 
    	$ItemGroupName=$_POST['ItemGroupName']; 
    	$ASStatusName=$_POST['ASStatusName']; 
        $OwnerTypeName=$_POST['OwnerTypeName']; 
        
        $html = '
        <!-- EXAMPLE OF CSS STYLE -->
        <style>
        p {
          line-height: 0.5px;
        }
        
        </style>
            <body>
                <p style="text-align:center;"><h4 ><b>'.$gTEXT['Shipment Reports'].'  of '.$CountryName.' '.$gTEXT['from'].' '.date('M,Y', strtotime($startDate)).' '.$gTEXT['to'].' '.date('M,Y', strtotime($endDate)).'</b></h4></p>
                <p style="text-align:center;"><h5><b>'.$FundingSourceName.' - ' .$ASStatusName.' - ' .$ItemGroupName.' - ' .$OwnerTypeName.'</b><h5></p>
            </body>';
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->writeHTMLCell(0, 0, 8, 10, $html, '', 0, 0, false, 'C', true);//date('M,Y', strtotime($StartYearMonth)), date('M,Y', strtotime($EndYearMonth))
        
        $html = '
            <!-- EXAMPLE OF CSS STYLE -->
            <style>
             td{
                 height: 6px;
                 line-height:3px;
             }
             th{
                height:20;
                font-size:10px;
            }
            </style>
            <body>
            <table width="600px" border="0.5" style="margin:0 auto;">
                <tr>
            		<th width="30" align="center"><b>SL#</b></th>
                    <th width="250" align="left"><b>'.$gTEXT['Product Name'].'</b></th>
                    <th width="100" align="left"><b>'.$gTEXT['Funding Source'].'</b></th>
            		<th width="90" align="left"><b>'.$gTEXT['Shipment Status'].'</b></th>
                    <th width="100" align="right"><b>'.$gTEXT['Shipment Date'].'</b></th>
                    <th width="90" align="right"><b>'.$gTEXT['Quantity'].'</b></th>                    
         	    </tr>'.$tblHTML.'</table></body>'; //echo $tblHTML;
                  	          
        $pdf->SetFont('dejavusans', '', 7);
        $pdf->writeHTMLCell(0, 0, 10, 40, $html, '', 1, 1, false, 'L', true);
        
    	$filePath=SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/ShipmentReport.pdf';
    	if (file_exists($filePath)) {
    		unlink($filePath);		
    	}
        
        $pdf->Output('pdfslice/ShipmentReport.pdf', 'F');
    
       	echo 'ShipmentReport.pdf';	
       		
	}else{
		echo 'Processing Error';
	}
}

function fnColumnToField_agencyShipment($i) {
	if ($i == 1)
		return "ItemName ";
  	else if ($i == 2)
		return "FundingSourceName ";
  	else if ($i == 3)
		return "ShipmentStatusDesc ";
    else if ($i == 4)
    	return "ShipmentDate ";
    else if ($i == 5)
    	return "Qty ";
    else if ($i == 6)
        return "CountryName ";
}

?>