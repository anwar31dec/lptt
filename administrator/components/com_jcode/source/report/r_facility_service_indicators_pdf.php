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
	case 'generateServiceReport' :
		generateServiceReport($conn);		
		break;	
   	default :
		echo "{failure:true}";
		break;
}
function generateServiceReport($conn){
       
   	global $gTEXT;        
    require_once('tcpdf/tcpdf.php');
    //require_once('fpdf/fpdi.php');  
    //$pdf = new FPDI();
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
	$Year = $_POST['Year'];   
    $Month = $_POST['Month'];
    $CountryId = $_POST['CountryId'];  
    $ServiceType = $_POST['ServiceType']; 
    if($CountryId){
    		$CountryId = " AND a.CountryId = ".$CountryId." ";
    	}  
    $sLimit = "";
	if (isset($_POST['iDisplayStart'])) { $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
	}

	$sOrder = "";
	if (isset($_POST['iSortCol_0'])) { $sOrder = " ORDER BY  ";
		for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
			$sOrder .= fnColumnToField(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
		}
		$sOrder = substr_replace($sOrder, "", -2);
	}

	$sWhere = "";
	if ($_POST['sSearch'] != "") {
		$sWhere = " AND (FacilityName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
                         OR NewPatient LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
                         OR TotalPatient LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
	}
    
	$sql = "SELECT SQL_CALC_FOUND_ROWS a.FacilityId, FacilityName, IFNULL(SUM(a.NewPatient),0) NewPatient, IFNULL(SUM(a.TotalPatient),0) TotalPatient 
            FROM t_cfm_patientoverview a
            INNER JOIN t_facility b ON a.FacilityId = b.FacilityId AND b.FLevelId = 99	
            INNER JOIN t_formulation d ON a.FormulationId = d.FormulationId AND d.ServiceTypeId = ".$ServiceType."
            INNER JOIN t_cfm_masterstockstatus f ON a.CFMStockId = f.CFMStockId AND StatusId = 5
            WHERE a.MonthId = ".$Month." AND a.Year = '".$Year."' ".$CountryId." $sWhere  
            GROUP BY a.FacilityId, FacilityName
           	$sOrder $sLimit ";   
                          
   	$result = mysql_query($sql,$conn);
	$total = mysql_num_rows($result);
    
    
    if($total>0){
        $data=array();
        $f=0; 
        $tblHTML='';
    	while ($rec = mysql_fetch_array($result)) {
            $data['SL'][$f]=$f;
    		$data['FacilityName'][$f] = $rec['FacilityName'];
    		$data['TotalPatient'][$f]=$rec['TotalPatient']== 0? '' : $rec['TotalPatient'];
    		//$data['NewPatient'][$f]=$rec['NewPatient']== 0? '' : $rec['NewPatient'];
            
            $tblHTML.='<tr style="page-break-inside:avoid;">
                            <td align="center" width="50" valign="middle">'.($data['SL'][$f]+1).'</td>  
                            <td align="left" width="300" valign="middle">'.$data['FacilityName'][$f].'</td>
                            <td align="right" width="300" valign="middle">'.$data['TotalPatient'][$f].'</td>
                            
                            
                    </tr>';//<td align="right" width="200" valign="middle">'.$data['NewPatient'][$f].'</td>
    		$f++;        
        }
        $Year = $_POST['Year'];   
        $Month = $_POST['Month'];
        $CountryId = $_POST['CountryId'];  
        $ServiceType = $_POST['ServiceType'];   
         
        if($CountryId){
    		$CountryId = " AND a.CountryId = ".$CountryId." ";
    	}
        
        $sql = "SELECT SQL_CALC_FOUND_ROWS a.FacilityId, FacilityName, IFNULL(SUM(a.NewPatient),0) NewPatient, IFNULL(SUM(a.TotalPatient),0) TotalPatient 
                FROM t_cfm_patientoverview a
                INNER JOIN t_facility b ON a.FacilityId = b.FacilityId AND b.FLevelId = 99	
                INNER JOIN t_formulation d ON a.FormulationId = d.FormulationId AND d.ServiceTypeId = ".$ServiceType."
                INNER JOIN t_cfm_masterstockstatus f ON a.CFMStockId = f.CFMStockId AND StatusId = 5
                WHERE a.MonthId = ".$Month." AND a.Year = '".$Year."' ".$CountryId."  
                GROUP BY a.FacilityId, FacilityName";
                    
        $result = mysql_query($sql);
        $totalPatient = 0;
       	while ($aRow = mysql_fetch_object($result)) {
       	    $totalPatient = $totalPatient + $aRow->TotalPatient; 
        }
    
    
        $Year = $_POST['Year'];
        $MonthName = $_POST['MonthName'];
        $CountryName = $_POST['CountryName']; 
        $ServiceTypeName = $_POST['ServiceTypeName']; 
        $html = '
        <!-- EXAMPLE OF CSS STYLE -->
        <style>
        </style>
        <body>
            <h4 style="text-align:center;"><b>'.$gTEXT['Facility Service Indicators Report of '].'  '.$CountryName.' on '.$MonthName.','.$Year.'</b></h4>
            <h4 style="text-align:center;"><b>'.$gTEXT['Service Type'].': '.$ServiceTypeName.'</b></h4>
            <h4 style="text-align:center;"><b>'.$gTEXT['Total Patient'].' is '.(number_format($totalPatient)).' </b></h4>
        </body>';
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->writeHTMLCell(0, 0, 10, 10, $html, '', 0, 0, false, 'C', true);
        
        $html = '
            <!-- EXAMPLE OF CSS STYLE -->
            <style>
             td{
                 height: 6px;
                 line-height:3px;
             }
             th{
             height: 20;
             font-size:10px;
            }
            </style>
            <body>
            <table width="600px" border="0.5" style="margin:0 auto;">
                <tr>
            		<th width="50" align="center"><b>SL</b></th>
                    <th width="300" align="left"><b>'.$gTEXT['Name of Facility'].'</b></th>
                    <th width="300" align="right"><b>'.$gTEXT['Number of Total Patients'].'</b></th>
            		
         	    </tr>'.$tblHTML.'</table></body>';
                //<th width="200" align="right"><b>'.$gTEXT['Number of New Patients'].'</b></th>
                  	          
        $pdf->SetFont('dejavusans', '', 7);
        $pdf->writeHTMLCell(0, 0, 10, 40, $html, '', 1, 1, false, 'L', true);
        
    	$filePath=SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/ServiceReport.pdf';
    	if (file_exists($filePath)) {
    		unlink($filePath);		
    	}
        
        $pdf->Output('pdfslice/ServiceReport.pdf', 'F');
    
       	echo 'ServiceReport.pdf';	
       		
	}else{
		echo 'Processing Error';
	}
}

function fnColumnToField($i) {
	if ($i == 1)
		return "FacilityName ";
  	else if ($i == 2)
		return "TotalPatient ";
	else if ($i == 3)
		return "NewPatient ";
}

?>