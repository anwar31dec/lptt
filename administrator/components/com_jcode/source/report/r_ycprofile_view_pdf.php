<?php
include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());

error_reporting(0);

$gTEXT = $TEXT;
$jBaseUrl = $_REQUEST['jBaseUrl']; 

$task = '';
if (isset($_REQUEST['operation'])) {
	$task = $_REQUEST['operation'];
}

switch($task) {
	case 'generateCountryProfileReport' :
		generateCountryProfileReport($conn);		
		break;	
   	default :
		echo "{failure:true}";
		break;
}
function generateCountryProfileReport($conn){
       
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
//=====================================================Country profile parameters table=======================================================
    $CountryId=$_REQUEST['CountryId'];
    $CountryName=$_REQUEST['CountryName']; 	 
    $Year=$_REQUEST['Year']; 
    
    
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
    mysql_query("SET character_set_results=utf8");                      
   	$result = mysql_query($sql,$conn);
	//$total = mysql_num_rows($result);
        $data=array();
        $f=0; 
        $tblHTML='';
    	while ($rec = mysql_fetch_array($result)) {
            $data['SL'][$f]=$f;
    		$data['ParamName'][$f] = $rec['ParamName'];
            if($rec['YCValue']==''){
                 $rec['YCValue']=='';
            }else {
                    if(is_numeric($rec['YCValue'])){
                         $rec['YCValue'] = number_format($rec['YCValue']);
                    } else{
                         $rec['YCValue'] = $rec['YCValue'];
                    }
            } 
    		$data['YCValue'][$f] = $rec['YCValue'] ;
            
            $tblHTML.='<tr style="page-break-inside:avoid;">
                            <td align="center" width="50" valign="middle">'.($data['SL'][$f]+1).'</td>  
                            <td align="left" width="300" valign="middle">'.$data['ParamName'][$f].'</td>
                            <td align="right" width="280" valign="middle">'.$data['YCValue'][$f].'</td></tr>';
    		$f++;        
        }
        
      /*  $html = '
        <!-- EXAMPLE OF CSS STYLE -->
        <style>
        </style>
        <body>
            <h4 style="text-align:center;"><b>'.$gTEXT['Country Profile of'].'  '.$CountryName.' '.$Year.'</b></h4>
            <h4 style="text-align:left;"><b>'.$gTEXT['Parameter List'].'</b></h4>
        </body>';
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->writeHTMLCell(0, 0, 10, 5, $html, '', 0, 0, false, 'C', true);*/
        
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
            <div>
            <p><h4 style="text-align:center;font-size:12px;"><b>'.$gTEXT['Country Profile of'].'  '.$CountryName.' '.$Year.'</b></h4><p></br>
            <h4 style="text-align:center;font-size:12px;"><b>'.$gTEXT['Parameter List'].'</b></h4>
            <table width=auto border="0.5" style="margin:0 auto;">
                <tr>
            		<th width="50" align="center"><b>SL#</b></th>
                    <th width="300" align="left"><b>'.$gTEXT['Parameter Name'].'</b></th>
                    <th width="280" align="right"><b>'.$gTEXT['Value'].'</b></th>
         	    </tr>'.$tblHTML.'</table></body></div>';
               	          
        $pdf->SetFont('dejavusans', '', 7);
        $pdf->writeHTMLCell(0, 0, 12, 5, $html, '', 1, 1, false, 'L', true);
        $pdf->endPage() ;
//==================================================Regiment Patients Table======================================================//
    $CountryId=$_REQUEST['CountryId']; 
	$CountryName=$_REQUEST['CountryName']; 	 
	$Year=$_REQUEST['Year'];
    
    $aColumns = array('SL', 'RegimenName', 'PatientCount', 'FormulationName');
    $aColumns2 = array('SL', 'RegimenName', 'PatientCount', 'FormulationName');
    
   /* if($_REQUEST['lan'] == 'fr-FR'){
            $aColumns = array('SL', 'RegimenName', 'PatientCount', 'FormulationNameFrench');
            $aColumns2 = array('SL', 'RegimenName', 'PatientCount', 'FormulationNameFrench');
     }else{
            $aColumns = array('SL', 'RegimenName', 'PatientCount', 'FormulationName');
            $aColumns2 = array('SL', 'RegimenName', 'PatientCount', 'FormulationName');
     }*/
    if($_REQUEST['lan'] == 'fr-FR'){
            $FLang = 'FormulationNameFrench';
            
     }else{
            $FLang = 'FormulationName';
            
     }
     
    $sIndexColumn = "YearlyRegPatientId";

	/* DB table to use */
	$sTable = "t_yearly_country_regimen_patient ";

	// Joins
	$sJoin = 'INNER JOIN t_regimen ON t_yearly_country_regimen_patient.RegimenId = t_regimen.RegimenId ';
	$sJoin .= 'INNER JOIN t_formulation ON t_regimen.FormulationId = t_formulation.FormulationId ';
	/*
	 * Paging
	 */
	$sLimit = "";
	if (isset($_REQUEST['iDisplayStart']) && $_REQUEST['iDisplayLength'] != '-1') {
		$sLimit = "LIMIT " . intval($_REQUEST['iDisplayStart']) . ", " . intval($_REQUEST['iDisplayLength']);
	}

	/*
	 * Ordering
	 */
	$sOrder = "";
	if (isset($_REQUEST['iSortCol_0'])) {
		$sOrder = "ORDER BY  ";
		for ($i = 0; $i < intval($_REQUEST['iSortingCols']); $i++) {
			if ($_REQUEST['bSortable_' . intval($_REQUEST['iSortCol_' . $i])] == "true") {
				$sOrder .= "" . $aColumns[intval($_REQUEST['iSortCol_' . $i])] . " " . ($_REQUEST['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
			}
		}

		$sOrder = substr_replace($sOrder, "", -2);
		if ($sOrder == "ORDER BY") {
			$sOrder = "";
		}
	}
	$sWhere = "";
	/* Individual column filtering */
	for ($i = 0; $i < count($aColumns); $i++) {
		if (isset($_REQUEST['bSearchable_' . $i]) && $_REQUEST['bSearchable_' . $i] == "true" && $_REQUEST['sSearch'] != '') {
			if ($sWhere == "") {
				$sWhere = "WHERE ";
			} else {
				$sWhere .= " OR ";
			}
			$sWhere .= "" . $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_REQUEST['sSearch']) . "%' ";
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
		$sWhere .= "t_yearly_country_regimen_patient.CountryId = " . $_REQUEST['CountryId'] . " AND t_yearly_country_regimen_patient.Year = " . $_REQUEST['Year'];
	}

	$bUseSL = true;
	$serial = '';


   /* $sQuery = "SELECT  " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
			FROM   $sTable
			$sJoin
			$sWhere
			$sOrder
			$sLimit
			";*/
  $sQuery = "SELECT SQL_CALC_FOUND_ROWS @rank:=@rank+1 AS SL, RegimenName, PatientCount, $FLang FormulationName
                FROM t_yearly_country_regimen_patient
                INNER JOIN t_regimen ON t_yearly_country_regimen_patient.RegimenId = t_regimen.RegimenId 
                INNER JOIN t_formulation ON t_regimen.FormulationId = t_formulation.FormulationId
                WHERE t_yearly_country_regimen_patient.CountryId = " . $_REQUEST['CountryId'] . " 
                AND t_yearly_country_regimen_patient.Year = " . $_REQUEST['Year']."
                ORDER BY t_formulation.FormulationId asc";
                
            // echo $sQuery;mysql_query("SET character_set_results=utf8");FormulationName asc,RegimenName";
            mysql_query("SET character_set_results=utf8");		
	$rResult =mysql_query($sQuery);
    $j=1;
    $tempGroupId='';
    $col = '';
    
    while ($aRow = mysql_fetch_array($rResult)) {
       // $aRow['RegimenName']=$regname;
        $row = array();
        for ($i = 0; $i < count($aColumns2); $i++) {			
            if ($i == 0)
                $row[] = $serial++;
            else
                $row[] = $aRow[$aColumns2[$i]];
            }
        if($tempGroupId!=$aRow['FormulationName']) {
            $col .= '<tr style="page-break-inside:avoid;">
                        <td style="background-color:#DAEF62;border-radius:2px;  align:center;" colspan="3">'.$aRow['FormulationName'].'</td>
                    </tr>'; 
            $tempGroupId=$aRow['FormulationName'];
        }
        $col .= '<tr style="page-break-inside:avoid;">
                    <td width="100" style="text-align: center;">'.$j.'</td>
                    <td width="250" style="text-align: left;">'.$aRow['RegimenName'].'</td>
                    <td width="280" style="text-align: right;">'.($aRow['PatientCount']==''? '':number_format($aRow['PatientCount'])).'</td>
                </tr>';
    	$j++; 
    }

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
        <div>
        <body>
        <h4 style="text-align:center; font-size:12px;"><p><b>'.$gTEXT['ART Protocols with Patient Count'].'</b></p></h4></br></br>
        <table width=auto border="0.5" style="margin:0 auto;">
        <tr>
            <th width="100" style="text-align: center;"><b>SL#</b></th>
            <th width="250" style="text-align: left;"><b>'.$gTEXT['RegimenCount'].'</b></th>
            <th width="280" style="text-align: right;"><b>'.$gTEXT['Patients'].'</b></th>
        </tr>'.$col.'</table></body></div>';
        
    $pdf->startPage();        	          
    $pdf->SetFont('dejavusans', '', 7);
    $pdf->writeHTMLCell(0, 0, 12, 12, $html, '', 1, 1, false, 'L', true);
    $pdf->endPage() ;
//================================================Funding Requirements======================================================================//
    $CountryId=$_REQUEST['CountryId'];
    $CountryName=$_REQUEST['CountryName'];  	 
    $Year=$_REQUEST['Year']; 
    
    $aColumns3 = array('ServiceTypeName', 'FundingReqSourceName', 'Y1', 'Y2', 'Y3', 'TotalRequirements');
	$aColumns4 = array( 'ServiceTypeName', 'FundingReqSourceName', 'Y1', 'Y2', 'Y3', 'TotalRequirements');
    
    if($_REQUEST['lan'] == 'fr-FR'){
            $SeLang = 'ServiceTypeNameFrench';
            $forLang = 'FundingReqSourceNameFrench';
     }else{
            $SeLang = 'ServiceTypeName';
            $forLang = 'FundingReqSourceName';
     } 
     
    $sIndexColumn = "FundingReqId";
	$sTable1 = "t_yearly_funding_requirements ";
	$sJoin1 = 'INNER JOIN  t_fundingreqsources ON t_fundingreqsources.FundingReqSourceId = t_yearly_funding_requirements.FormulationId  ';
	$sJoin1 .= 'INNER JOIN t_servicetype ON t_servicetype.ServiceTypeId = t_fundingreqsources.ServiceTypeId ';
	$sJoin1 .= 'INNER JOIN t_itemgroup ON t_itemgroup.ItemGroupId = t_fundingreqsources.ItemGroupId';
    
	$bUserFilter1 = true;
	if ($bUserFilter1) {
		if ($sWhere1 == "") {
			$sWhere1 = "WHERE ";
		} else {
			$sWhere1 .= " AND ";
		}
		$sWhere1 .= "t_yearly_funding_requirements.CountryId = " . $_REQUEST['CountryId'] . " 
                    AND t_yearly_funding_requirements.Year = " . $_REQUEST['Year'];
	}
    $sOrder = "Order By ServiceTypeName, t_fundingreqsources.FundingReqSourceId ";
    $bUseSL1 = true;
    $serial1 = '';
    /*$sQuery1 = "
			SELECT  " . $serial1 . str_replace(" , ", " ", implode(", ", $aColumns3)) . "
			FROM   $sTable1
			$sJoin1
			$sWhere1
			$sOrder
			$sLimit
			";*/
    $sQuery1 = "SELECT $SeLang ServiceTypeName, $forLang FundingReqSourceName, Y1, Y2, Y3, TotalRequirements
                    FROM t_yearly_funding_requirements
                    $sJoin1
                    $sWhere1 
                    $sOrder ";
	mysql_query("SET character_set_results=utf8");
	$rResult1 =mysql_query($sQuery1);
	$j=1;
    $tempGroupId1='';
    $col1 = '';
	while ($aRow1 = mysql_fetch_array($rResult1)) {
		$row = array();
		for ($i = 0; $i < count($aColumns4); $i++) {
			$row[] = $aRow1[$aColumns4[$i]];
		}
		if($tempGroupId1!=$aRow1['ServiceTypeName']) {
		   	 $col1 .='<tr >
                     <td style="background-color:#DAEF62;border-radius:2px;  align:center; font-size:10px;" colspan="6">'.$aRow1['ServiceTypeName'].'</td>
                   </tr>'; 
			 $tempGroupId1 = $aRow1['ServiceTypeName'];
		   }
		$col1 .= '<tr>
                    <td width="100" style="text-align: center;">'.$j.'</td>
                    <td width="150" style="text-align: left;">'.$aRow1['FundingReqSourceName'].'</td>
                    <td width="100" style="text-align: right;">'.number_format($aRow1['Y1']).'</td>
                    <td width="100" style="text-align: right;">'.number_format($aRow1['Y2']).'</td>
                    <td width="100" style="text-align: right;">'.number_format($aRow1['Y3']).'</td>
                    <td width="90" style="text-align: right;">'.number_format($aRow1['TotalRequirements']).'</td>
		      </tr>';
		$j++; 
	}
      
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
        <div>
        <body>
        <h4 style="text-align:center; font-size:12px;"><p><b>'.$gTEXT['Funding Requirements'].$gTEXT['MonetaryTitle'].'</b></p></h4></br></br>
        <table width="600px" border="0.5" style="margin:0 auto;">
        <tr>
            <th width="100" style="text-align: center;"><b>SL#</b></th>
            <th width="150" style="text-align: left;"><b>'.$gTEXT['Formulation'].'</b></th>
            <th width="100" style="text-align: right;"><b>'.$gTEXT['2014'].'</b></th>
            <th width="100" style="text-align: right;"><b>'.$gTEXT['2015'].'</b></th>
            <th width="100" style="text-align: right;"><b>'.$gTEXT['2016'].'</b></th>
            <th width="90" style="text-align: right;"><b>'.$gTEXT['Total'].'</b></th>
        </tr>'.$col1.'</table></body></div>';
        
    $pdf->startPage();
    $pdf->SetFont('dejavusans', '', 7);
    $pdf->writeHTMLCell(0, 0, 12, 12, $html, '', 1, 1, false, 'L', true);
    $pdf->endPage() ;
//=======================================================Pledged Funding=================================================================//
    $CountryId = $_POST['CountryId'];
	$Year = $_POST['Year'];
    $CountryName=$_POST['CountryName']; 
	$RequirementYear = $_POST['RequirementYear'];
    
     if($_POST['lan'] == 'fr-FR'){
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
            
     $col = '';
     $col .= '<tr style="page-break-inside:avoid;">
            <th width="80" style="text-align: left;"><b>SL#</b></th>
            <th width="98" style="text-align: left;"><b>'.$gTEXT['Category'].'</b></th>
            <th width="98" style="text-align: left;"><b>'.$gTEXT['Total Requirements'].'</b></th>';
            	/*===Funding Source List=*/
            $sql_1 = "select f.FundingSourceId,s.FundingSourceName 
                        from t_yearly_country_fundingsource f
                		Inner Join t_fundingsource s on s.FundingSourceId=f.FundingSourceId
                		where  CountryId='" . $CountryId . "' 
                        and Year='" . $Year . "' 
                		Order By FundingSourceName asc ";
			$resultPre = mysql_query($sql_1);
			$total = mysql_num_rows($resultPre);
			$k=0;$odd=1;
            
			while ($row = mysql_fetch_object($resultPre)) {
				if($k%2==0){
					$col .= ' <th width="77" style="text-align: left;"><b>'.$row -> FundingSourceName.'</b></th>';	
					
					$odd=0;
				}else{
					$col .= ' <th width="77" style="text-align: left;"><b>'.$row -> FundingSourceName.'</b></th>';	
					
					$odd=1;
				}
				$k++;
			}
			/*===Funding Source List=*/
			$col .=  '<th width="80" style="text-align: left;"><b>'.$gTEXT['Total'].'</b></th>
				   <th width="80" style="text-align: left;"><b>'.$gTEXT['Gap/Surplus'].'</b></th></tr>';
                   
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
        <h4 style="text-align:center; font-size:12px;"><p><b>'.$gTEXT['Pledged Funding'].$gTEXT['MonetaryTitle'].'</b></p></h4></br></br>
        <table width="600px" border="0.5" style="margin:0 auto;">'.$col.''.$tbody.'</table></body>';
      
    } 
    $pdf->startPage();  	          
    $pdf->SetFont('dejavusans', '', 7);
    $pdf->writeHTMLCell(0, 0, 12, 12, $html, '', 1, 1, false, 'L', true);
    $pdf->endPage() ;    
    	$filePath=SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/CountryProfileReport.pdf';
    	if (file_exists($filePath)) {
    		unlink($filePath);		
    	}
        
        $pdf->Output('pdfslice/CountryProfileReport.pdf', 'F');
    
       	echo 'CountryProfileReport.pdf';	
       		
/*	}else{
		echo 'Processing Error';
	}*/
}

?>