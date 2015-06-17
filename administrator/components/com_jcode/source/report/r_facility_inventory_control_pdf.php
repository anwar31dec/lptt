<?php
include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());

error_reporting(0);

$gTEXT = $TEXT;
$jBaseUrl = $_GET['jBaseUrl']; 

$task = '';
if (isset($_REQUEST['operation'])) {
	$task = $_REQUEST['operation'];
}

switch($task) {
	case 'generateFacilityInventoryReport' :
		generateFacilityInventoryReport($conn);		
		break;	
   	default :
		echo "{failure:true}";
		break;
}

function generateFacilityInventoryReport($conn){
       
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
	$pdf->setFontSubsetting(false);
    $pdf->AddPage();
    $pdf->SetFillColor(255,255,255);

//=====================================================Facility Inventory Table=======================================================
	$MonthId=$_REQUEST['MonthId']; 
	$YearId=$_REQUEST['YearId'];
    $mosTypeId = $_REQUEST['MosTypeId'];
	$countryId = $_REQUEST['CountryId'];
	$fLevelId = $_REQUEST['FLevelId'];
    $FacilityId=$_REQUEST['FacilityId'];
    $ItemGroupId = $_REQUEST['ItemGroupId'];
	
	$regionId = $_REQUEST['RegionId'];
    $districtId = $_REQUEST['DistrictId'];
    $ownerTypeId = $_REQUEST['OwnerTypeId'];
    $region = $_REQUEST['Region'];
    $district = $_REQUEST['District'];
    $ownerType = $_REQUEST['OwnerType'];
	
	$year = $_REQUEST['Year'];
    $CountryName = $_REQUEST['CountryName'];
    $monthName = $_REQUEST['MonthName'];
    $ItemGroupName = $_REQUEST['ItemGroupName'];
    $FacilityName = $_REQUEST['FacilityName'];
	
	 $lan=$_REQUEST['lan']; 
	if($lan == 'en-GB'){
		$SITETITLE = SITETITLEENG;
	}else{
	   $SITETITLE = SITETITLEFRN;
	} 
	
	$column_name = array();

	$sQuery1 = "SELECT
			    MosTypeId
			    , MosTypeName
			    , ColorCode
			FROM
			    t_mostype_facility
			WHERE CountryId = $countryId AND FLevelId = $fLevelId  AND (MosTypeId = $mosTypeId OR $mosTypeId = 0)
			ORDER BY MosTypeId;";

	$rResult1 = mysql_query($sQuery1);
	$output1 = array();
    $col = '';
	while ($row1 = mysql_fetch_array($rResult1)) {
		$output1[] = $row1;
        array_push($column_name, $row1['MosTypeName']);
	}  
 
    $col.= '<tr><th width="180" align="left"><b>'.$gTEXT['Product Name'].'</b></th>';
	$col.= '<th width="60" align="left"><b>'.$gTEXT['Closing Balance'].'</b></th>';
	$col.= '<th width="45" align="left"><b>'.$gTEXT['AMC'].'</b></th>';
    $col.= '<th width="40" align="left"><b>'.$gTEXT['MOS'].'</b></th>';	
    $f=0;
    for($f = 0; $f<count($output1); $f++){       
        $col.= '<th width="70" align="right"><b>'.$column_name[$f].'</b></th>';             
    }
    $col.='</tr>';  		
 /*
	$sQuery = "SELECT p.MosTypeId, ItemName, MOS FROM (SELECT
				    a.ItemNo
				    , b.ItemName
				    , a.MOS
				,(SELECT MosTypeId FROM t_mostype_facility x WHERE CountryId = $countryId AND FLevelId = $fLevelId  AND (MosTypeId = $mosTypeId OR $mosTypeId = 0) AND a.MOS >= x.MinMos AND a.MOS < x.MaxMos) MosTypeId
				FROM t_cfm_stockstatus a, t_itemlist b,  t_cfm_masterstockstatus c
				WHERE a.itemno = b.itemno AND a.MOS IS NOT NULL AND a.MonthId = " . $_REQUEST['MonthId'] . " AND a.Year = '" . $_REQUEST['YearId'] . "' AND a.CountryId = " . $_REQUEST['CountryId'] . " AND a.FacilityId = " . $_REQUEST['FacilityId'] . " AND a.ItemGroupId = " . $_REQUEST['ItemGroupId'] . " AND a.CFMStockId = c.CFMStockId" . " AND c.StatusId = 5 " . ") p
				WHERE (p.MosTypeId = $mosTypeId OR $mosTypeId = 0) 
				ORDER BY ItemName";
*/
 if($ownerTypeId == 1 || $ownerTypeId == 2){
        $sQuery = "SELECT p.MosTypeId, ItemName, MOS ,ClStock,AMC FROM (SELECT
				    a.ItemNo, b.ItemName, a.MOS ,a.ClStock,a.AMC
				,(SELECT MosTypeId FROM t_mostype_facility x WHERE CountryId = $countryId 
                AND FLevelId = $fLevelId  AND (MosTypeId = $mosTypeId OR $mosTypeId = 0) 
                AND a.MOS >= x.MinMos AND a.MOS < x.MaxMos ) MosTypeId
				FROM t_cfm_stockstatus a, t_itemlist b,  t_cfm_masterstockstatus c, t_facility g
				WHERE a.itemno = b.itemno AND a.MOS IS NOT NULL AND a.MonthId = " . $MonthId . " 
                AND a.Year = '" . $YearId . "' AND a.CountryId = " . $countryId . " 
                AND a.FacilityId = " . $FacilityId . " AND a.ItemGroupId = " . $ItemGroupId . "
                AND a.CFMStockId = c.CFMStockId" . " AND c.StatusId = 5 " . "
                AND a.FacilityId=g.FacilityId 
                AND g.OwnerTypeId = $ownerTypeId 
                AND  (g.RegionId = $regionId OR $regionId = 0)
                AND (g.DistrictId = $districtId OR $districtId = 0)
                 ) p
                
				WHERE (p.MosTypeId = $mosTypeId OR $mosTypeId = 0) 
				ORDER BY ItemName";
     }else{
        $sQuery = "SELECT p.MosTypeId, ItemName, MOS ,ClStock,AMC FROM (SELECT
				    a.ItemNo, b.ItemName, a.MOS ,a.ClStock,a.AMC
				,(SELECT MosTypeId FROM t_mostype_facility x WHERE CountryId = $countryId 
                AND FLevelId = $fLevelId  AND (MosTypeId = $mosTypeId OR $mosTypeId = 0) 
                AND a.MOS >= x.MinMos AND a.MOS < x.MaxMos ) MosTypeId
				FROM t_cfm_stockstatus a, t_itemlist b,  t_cfm_masterstockstatus c, t_facility g
				WHERE a.itemno = b.itemno AND a.MOS IS NOT NULL AND a.MonthId = " . $MonthId . " 
                AND a.Year = '" . $YearId . "' AND a.CountryId = " . $countryId . " 
                AND a.FacilityId = " . $FacilityId . " AND a.ItemGroupId = " . $ItemGroupId . "
                AND a.CFMStockId = c.CFMStockId" . " AND c.StatusId = 5 " . "
                AND a.FacilityId=g.FacilityId
                AND g.AgentType = $ownerTypeId 
                AND  (g.RegionId = $regionId OR $regionId = 0)
                AND (g.DistrictId = $districtId OR $districtId = 0) ) p
                
				WHERE (p.MosTypeId = $mosTypeId OR $mosTypeId = 0) 
				ORDER BY ItemName";
     }
	//echo $sQuery;
    mysql_query("SET character_set_results=utf8");
	$rResult = mysql_query($sQuery);
	$aData = array();
    
    $total = mysql_num_rows($rResult);
    if($total>0){
	while ($row = mysql_fetch_array($rResult)) {
		$tmpRow = array();
	/*	foreach ($output1 as $rowMosType) {
			if ($rowMosType['MosTypeId'] == $row['MosTypeId']) {
				//$tmpRow[] = '<span class="glyphicon glyphicon-ok-circle" style="color:' . $rowMosType['ColorCode'] . ';font-size:2em;"></span>';
				$tmpRow[] = '<i class="fa fa-check-circle fa-lg" style="color:' . $rowMosType['ColorCode'] . ';font-size:2.5em;"></i>';

			} else
				$tmpRow[] = '';
		}
		array_unshift($tmpRow, $row['ItemName'], number_format($row['MOS'], 1));
		$aData[] = $tmpRow;*/
        
			$col.= '<tr style="page-break-inside:avoid;">
			           <td>'.$row['ItemName'].'</td>
					   <td>'.$row['ClStock'].'</td>
					   <td>'.$row['AMC'].'</td>
			         <td> '.number_format($row['MOS'],1).'</td>  ';
		 		
			foreach ($output1 as $rowMosType) {
			if ($rowMosType['MosTypeId'] == $row['MosTypeId']) {
				/*$tmpRow[] = '<i class="fa fa-check-circle fa-lg" style="color:' . $rowMosType['ColorCode'] . ';font-size:2.5em;"></i>';
				$col.= '<td><span class="fa fa-check-circle fa-lg" style="color:' . $rowMosType['ColorCode'] . ';font-size:2.5em;text-align:center;"></span></td>';
				*/
                $tmpRow[] = $rowMosType['ColorCode'];
				$col.= '<td style="background-color:'.$rowMosType['ColorCode'].'"></td>';
				
			 } else
				$col.= '<td> </td>';
		     }
		     array_unshift($tmpRow, $row['ItemName'], number_format($row['MOS'], 1));
             $aData[] = $tmpRow;
			 
			 $col.= ' </tr>';
	}
 
    $ItemGroupName = $_REQUEST['ItemGroupName'];
    $FacilityName = $_REQUEST['FacilityName'];
    $html_head = '
            <!-- EXAMPLE OF CSS STYLE -->
            <style>
            p {
              line-height: 0.1px;
            }
            </style>
                <body>
					<p><h3 style="text-align:center;"><b>'.$SITETITLE.'</b></h3></p></br>
                    <p><h4 style="text-align:center;"><b>'.$ItemGroupName.' '.$gTEXT['Facility Inventory Control Report of '].'  '.$CountryName.' '.$gTEXT['on'].' '.$monthName.','.$year.'</b></h4></p></br>
					<p><h4 style="text-align:center;"><b>'.$gTEXT['Region'].': '.$region.', '.$gTEXT['District'].': '.$district.', '.$gTEXT['Report By'].': '.$ownerType.'</b></h4></p></br>
                    <p style="text-align:center;"><h4><b>'.$gTEXT['Facility Name'].': '.$FacilityName.'</b><h4></p></br>
                </body>';
 
	
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->writeHTMLCell(0, 0, 6, 10, $html_head, '', 0, 0, false, 'C', true);
        
        $html = '<head>
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
			</head>
            <!-- EXAMPLE OF CSS STYLE -->
            <style>
             td{
                 height: 6px;
                 line-height:3px;
             }
             th{
             height: 20;
            }
            </style>
            <body>
            <table width="600px" border="0.5" style="margin:0 auto;">'.$col.'</table></body>';
                  	          
        $pdf->SetFont('dejavusans', '', 7);
        $pdf->writeHTMLCell(0, 0, 5, 45, $html, '', 1, 1, false, 'L', true);
    	$filePath=SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/FacilityInventoryReport.pdf';
    	if (file_exists($filePath)) {
    		unlink($filePath);		
    	}
        
        $pdf->Output('pdfslice/FacilityInventoryReport.pdf', 'F');
    
       	echo 'FacilityInventoryReport.pdf';	
      		
	}else{
		echo 'Processing Error';
	}
}

?>