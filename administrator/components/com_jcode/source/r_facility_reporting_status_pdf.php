 <?php
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');
include("../database_conn.php");
include("../function_lib.php");

error_reporting(0);

$gTEXT=$TEXT;
$jBaseUrl = $_GET['jBaseUrl'];
echo $jBaseUrl;

$task = '';
if (isset($_REQUEST['operation'])) {
    $task = $_REQUEST['operation'];
}

switch ($task) {
    case 'generateFacilityReport':
        generateFacilityReport($conn);
        break;
    default:
        echo "{failure:true}";
        break;
}

/*function safe_query($query = ""){
    if (empty($query)) {
        return false;
    }   
    $result = mysql_query($query) or die("Query Fails:" . "<li> Errno = " . mysql_errno() . "<li> ErrDetails = " . mysql_error() . "<li>Query = " . $query);
    return $result;
    } */

function generateFacilityReport($conn)
{
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
    
    if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
        require_once(dirname(__FILE__) . '/lang/eng.php');
        $pdf->setLanguageArray($l);
    }
    $pdf->AddPage();
    $pdf->SetFillColor(255, 255, 255);
    
    $monthId=$_POST['MonthId']; 
    $year=$_POST['Year']; 
    $country=$_POST['CountryId'];
    $itemGroupId=$_POST['ItemGroupId'];
    $CountryName=$_POST['CountryName'];   
    $MonthName = $_POST['MonthName'];
    $ItemGroupName = $_POST['ItemGroupName'];
    $regionId = $_POST['RegionId'];
    $RegionName = $_POST['RegionName'];
    $districtId = $_POST['DistrictId'];
    $DistrictName = $_POST['DistrictName'];
    $ownerTypeId = $_POST['OwnerTypeId'];
    $OwnerTypeName = $_POST['OwnerTypeName'];
       
	$condition="";
    if($regionId){
        $condition.= " and  x.RegionId = $regionId ";
    }
    
    if($districtId){
        $condition.= " and x.DistrictId = $districtId ";
    }
    if($ownerTypeId){
        $condition.= " and  x.OwnerTypeId = $ownerTypeId ";    
    }
    
    $aColumns = array('SL', 
                    'FacilityCode', 'FacilityName', 
                    'bEntered', 'CreatedDt', 'bSubmitted', 
                    'LastSubmittedDt', 'bAccepted', 'AcceptedDt', 
                    'bPublished', 'PublishedDt');
        
	$sIndexColumn = "FacilityId";
	$sTable = "t_cfm_masterstockstatus";
    
	$sLimit = "";
	if (isset($_POST['iDisplayStart']) && $_POST['iDisplayLength'] != '-1') {
		$sLimit = "LIMIT " . intval($_POST['iDisplayStart']) . ", " . intval($_POST['iDisplayLength']);
	}
    
	$sOrder = "";
	if (isset($_POST['iSortCol_0'])) {
		$sOrder = "ORDER BY  ";
		for ($i = 0; $i < intval($_POST['iSortingCols']); $i++) {
			if ($_POST['bSortable_' . intval($_POST['iSortCol_' . $i])] == "true") {
				$sOrder .= "`" . $aColumns[intval($_POST['iSortCol_' . $i])] . "` " . ($_POST['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
			}
		}
	
		$sOrder = substr_replace($sOrder, "", -2);
		if ($sOrder == "ORDER BY") {
			$sOrder = "";
		}
	}
	
    /*$sWhere="";
	for ($i = 0; $i < count($aColumns); $i++) {
		
		if (isset($_POST['bSearchable_' . $i]) && $_POST['bSearchable_' . $i] == "true" && $_POST['sSearch'] != '') {
			
			if ($sWhere == "") {
				$sWhere = "WHERE ";
			} else {
				$sWhere .= " OR ";
			}
			$sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' ";
		}
	}*/
    $sWhere = "";
	if ($_POST['sSearch'] != "") {
		$sWhere = " WHERE (b.FacilityCode LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
        OR " . " b.FacilityName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' )";							
	}
    
    safe_query("SET @rank=0;");
	$serial = "@rank:=@rank+1 AS SL";
    $sQuery = "SELECT SQL_CALC_FOUND_ROWS ". $serial .", b.FacilityId, b.FacilityCode, b.FacilityName,
				IFNULL( a.FacilityId,0) bEntered,				
				DATE_FORMAT(a.CreatedDt, '%d-%b-%Y %h:%i %p') CreatedDt,	
				IF(c.StatusId = '2', '1', '0') bSubmitted,
				DATE_FORMAT(a.LastSubmittedDt, '%d-%b-%Y %h:%i %p')  LastSubmittedDt,
				IF(c.StatusId = '3', '1', '0') bAccepted,
				DATE_FORMAT(a.AcceptedDt, '%d-%b-%Y %h:%i %p')  AcceptedDt,
				IF(c.StatusId = '5', '1', '0') bPublished,
				DATE_FORMAT(a.PublishedDt, '%d-%b-%Y %h:%i %p')  PublishedDt
				FROM  t_cfm_masterstockstatus a 
				RIGHT JOIN 
                (SELECT x.FacilityId, x.FacilityCode, x.FacilityName 
                FROM t_facility x 
                INNER JOIN  t_facility_group_map y ON x.FacilityId = y.FacilityId  
                WHERE x.CountryId = $country AND y.ItemGroupId = $itemGroupId $condition) b
				ON a.FacilityId = b.FacilityId AND  MonthId = $monthId AND Year = '$year' AND a.CountryId = $country AND a.ItemGroupId = $itemGroupId  
				LEFT JOIN t_status c ON a.StatusId = c.StatusId 
                $sWhere
				$sOrder
				$sLimit;"; //echo $sQuery;
        
    $r= safe_query($sQuery) ;
	$total = mysql_num_rows($r);
    $h=1;					
	$col='';
    $i=1;	
    if ($total > 0) {
        $h=1;
	while ($rec = mysql_fetch_array($r)) {
            
            $narr = array();
            
            for ($i = 0; $i < count($aColumns); $i++) {
                
                if ($aColumns[$i] == "bEntered") {
                    $narr[] = ($rec[$aColumns[$i]] == "0") ? '<span class="label label-danger"> No </span>' : '<span class="label label-success"> Yes </span>';
                    
                } else if ($aColumns[$i] == "bSubmitted") {
                    $narr[] = ($rec[$aColumns[$i]] == "0") ? '<span class="label label-danger"> No </span>' : '<span class="label label-success"> Yes </span>';
                                        
                } else if ($aColumns[$i] == "bAccepted") {
                    $narr[] = ($rec[$aColumns[$i]] == "0") ? '<span class="label label-danger">  No  </span>' : '<span class="label label-success">  Yes  </span>';
					if($rec[$aColumns[$i]] == "1"){
						$narr[5] = '<span class="label label-success">  Yes  </span>';
					}
                } else if ($aColumns[$i] == "bPublished") {
                    $narr[] = ($rec[$aColumns[$i]] == "0") ? '<span class="label label-danger">  No  </span>' : '<span class="label label-success">  Yes  </span>';
					if($rec[$aColumns[$i]] == "1"){
						$narr[5] = '<span class="label label-success">  Yes  </span>';
						$narr[7] = '<span class="label label-success">  Yes  </span>';
					}
                } else if ($aColumns[$i] != ' ') {
                    $narr[] = $rec[$aColumns[$i]];
                }
            }
            $col .= '<tr style="page-break-inside:avoid;">';
            for ($i = 0; $i < count($narr); $i++) {
                $col .= '<td>' . $narr[$i] . '</td>';
            }
            $col .= '</tr>';
                     
        }
        //echo $col;        
     $lan=$_POST['lan']; 
	if($lan == 'en-GB'){
		$SITETITLE = SITETITLEENG;
	}else{
	   $SITETITLE = SITETITLEFRN;
	} 
    $html = '<style>
    </style>
    <head></head>
    <body>
		 <h3 style="text-align:center;"><b>'.$SITETITLE. '</b></h3>
        <h4 style="text-align:center;"><b>'.$ItemGroupName.' '.$gTEXT['Facility Reporting Status of'].' '.$CountryName.' '.$gTEXT['on'].' '.$MonthName .',' . $year . '</b></h4>
        <h4>'.$gTEXT['Region'].': '. $RegionName.', '.$gTEXT['District'].': '. $DistrictName.', '.$gTEXT['Owner Type'].': '.$OwnerTypeName.'</h4>
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
    }
    </style>
    <body>    
    <table width="600px" border="0.5" style="margin:0 auto;">
    <tr style="page-break-inside:avoid;">
            <th width="20" align="center"><b>SL</b></th>
            <th width="60" align="left"><b>' . $gTEXT['Facility Code'] . '</b></th>
            <th width="70" align="left"><b>' . $gTEXT['Facility Name'] . '</b></th>
            <th width="60" align="center"><b>' . $gTEXT['Entered'] . '</b></th>
            <th width="65" align="left"><b>' . $gTEXT['Entry Date'] . '</b></th>
            <th width="60" align="center"><b>' . $gTEXT['Submitted'] . '</b></th>
            <th width="65" align="right"><b>' . $gTEXT['Submitted Date'] . '</b></th>
            <th width="60" align="center"><b>' . $gTEXT['Accepted'] . '</b></th>
            <th width="65" align="left"><b>' . $gTEXT['Accepted Date'] . '</b></th>
            <th width="60" align="center"><b>' . $gTEXT['Published'] . '</b></th>
            <th width="65" align="right"><b>' . $gTEXT['Published Date'] . '</b></th>
         </tr>' . $col . '</table></body>';
             //echo $html;
        $pdf->SetFont('dejavusans', '', 7);
        $pdf->writeHTMLCell(0, 0, 10, 45, $html, '', 1, 1, false, 'L', true);
        
        $filePath = SITEDOCUMENT . 'administrator/components/com_jcode/source/report/pdfslice/FacilityReportingStatus.pdf';
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        $pdf->Output('pdfslice/FacilityReportingStatus.pdf', 'F');
        
        echo trim('FacilityReportingStatus.pdf');

    } else {
        echo 'Processing Error';
    }
    
    
   
}
?> 