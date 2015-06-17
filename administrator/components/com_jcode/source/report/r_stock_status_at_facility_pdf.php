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
	case 'generateFacilityStockStatusReport' :
		generateFacilityStockStatusReport($conn);		
		break;	
   	default :
		echo "{failure:true}";
		break;
}

function generateFacilityStockStatusReport($conn){
        
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
    
//=====================================================Facility Stock Status Table=======================================================

    $monthId = $_REQUEST['MonthId'];
	$year = $_REQUEST['YearId'];
	$countryId = $_REQUEST['CountryId'];
	$itemGroupId = $_REQUEST['ItemGroupId'];
	$itemNo = $_REQUEST['ItemNo'];
	$regionId = $_REQUEST['RegionId'];
	$fLevelId = $_REQUEST['FLevelId'];
    
    $CountryName = $_REQUEST['CountryName'];  
    $MonthName = $_REQUEST['MonthName'];
    $ItemGroupName = $_REQUEST['ItemGroupName'];
    $ItemName = $_REQUEST['ItemName'];
    $RegionName = $_REQUEST['RegionName'];
    $FLevelName = $_REQUEST['FLevelName'];
    
    //$aColumns = array('SL', 'FacilityName', 'ClStock', 'AMC', 'MOS', 'FacilityId', 'Latitude', 'Longitude');
    
    $sWhere = "";
    if ($_REQUEST['sSearch'] != "") {
    	$sWhere = " AND (b.FacilityName LIKE '%" . mysql_real_escape_string($_REQUEST['sSearch']) . "%'
    				OR " . " b.ClStock LIKE '%" . mysql_real_escape_string($_REQUEST['sSearch']) . "%' 
    				OR " . " b.MOS LIKE '%" . mysql_real_escape_string($_REQUEST['sSearch']) . "%' 
    				OR " . " b.AMC LIKE '%" . mysql_real_escape_string($_REQUEST['sSearch']) . "%' ) ";							
    }
        
    $serial = "@rank:=@rank+1 AS SL";
	$sql = "SELECT SQL_CALC_FOUND_ROWS " . $serial . ",
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
 //   echo $sql;

	mysql_query("SET character_set_results=utf8");
	$result = mysql_query($sql,$conn);
	$total = mysql_num_rows($result);
    
    if($total>0){
        $data=array();
        $f=0; 
        $tblHTML='';
    	while ($rec = mysql_fetch_array($result)) {
            $data['SL'][$f]=$f;
    		$data['FacilityName'][$f] = $rec['FacilityName'];
            $data['ClStock'][$f]=number_format($rec['ClStock']);
    		$data['AMC'][$f]=number_format($rec['AMC']);
    		$data['MOS'][$f]=number_format($rec['MOS'],1);
            
            $tblHTML.='<tr style="page-break-inside:avoid;">
                            <td align="center" width="50" valign="middle">'.($data['SL'][$f]+1).'</td>  
                            <td align="left" width="200" valign="middle">'.$data['FacilityName'][$f].'</td>
                             <td align="right" width="120" valign="middle">'.$data['ClStock'][$f].'</td>
                            <td align="right" width="120" valign="middle">'.$data['AMC'][$f].'</td>
                            <td align="right" width="120" valign="middle">'.$data['MOS'][$f].'</td> 
                    </tr>';
    		$f++;	  		
    	}
        
        //$html_head = "<span><b>".$gTEXT['Facility Stock Status by Product Report of ']." ".$CountryName." on ".$MonthName.",".$year."</b></br>
//                        <b>".$gTEXT['Region'].' : '.$RegionName.", ".$gTEXT['Facility Level'].' : '.$FLevelName."</b></br>
//                        <b>".$gTEXT['Product Group'].' : '.$ItemGroupName.", ".$gTEXT['Product Name'].' : '.$ItemName."</b>
//                    </span>";
        $html_head = '
            <!-- EXAMPLE OF CSS STYLE -->
            <style>
            p {
              line-height: 0.5px;
            }
            </style>
                <body>
                    <p style="text-align:center;"><h4 ><b>'.$gTEXT['Facility Stock Status by Product Report of '].' '.$CountryName.' '.$gTEXT['on'].' '.$MonthName.','.$year.'</b></h4></p>
                    <p style="text-align:center;"><h5><b>'.$gTEXT['Region'].': '.$RegionName.', '.$gTEXT['Facility Level'].': '.$FLevelName.'</b><h5></p></br>
                    <p style="text-align:center;"><h5><b>'.$gTEXT['Product Group'].': '.$ItemGroupName.', '.$gTEXT['Product Name'].': '.$ItemName.' </b><h5><p>
                </body>';
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->writeHTMLCell(0, 0, 10, 10, $html_head, '', 0, 0, false, 'C', true);
        
       
           $tblHTML1.='<table width="100%" border="0.5" style="margin:0 auto;">
	<thead>
		<tr role="row">
			<th role="columnheader" colspan="1" rowspan="3" class="ui-state-default" style=" width:5%; text-align:center;">SL.</th>
			<th role="columnheader" colspan="1" rowspan="3" class="productname ui-state-default" style=" width:23%; text-align:center;">Products</th>
			<th role="columnheader" colspan="1" rowspan="3" class="ui-state-default" style=" width:7%; text-align:center;">AMC</th>
			<th role="columnheader" colspan="1" rowspan="3" class="ui-state-default" style=" width:14%; text-align:center;">Available Stock</th>
			<th role="columnheader" colspan="1" rowspan="3" class="ui-state-default" style=" width:15%; text-align:center;">MOS(Available)</th>
			<th role="columnheader" colspan="4" rowspan="1" class="" style=" width:26%; text-align:center;">Shipment Qty</th>
			<th role="columnheader" colspan="1" rowspan="3" class="ui-state-default" style=" width:11%; text-align:center;">Total MOS</th>
		</tr>
		<tr role="row">
			<th role="columnheader" colspan="2" rowspan="1" class="" style=" width:13%; text-align:center;">GFATM</th>
			<th role="columnheader" colspan="2" rowspan="1" class="" style=" width:13%; text-align:center;">Government</th>
		</tr>
		<tr role="row">
			<th role="columnheader" colspan="1" rowspan="1" class="ui-state-default" style=" width:6%; text-align:center;">Qty</th>
			<th role="columnheader" colspan="1" rowspan="1" class="ui-state-default" style=" width:7%; text-align:center;">MOS</th>
			<th role="columnheader" colspan="1" rowspan="1" class="ui-state-default" style=" width:6%; text-align:center;">Qty</th>
			<th role="columnheader" colspan="1" rowspan="1" class="ui-state-default" style=" width:7%; text-align:center;">MOS</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td width="5%" class="">1</td>
			<td width="23%" class="">Arthémether + Luméfantrine /Plq de 12,</td>
			<td width="7%" class="">3,475</td>
			<td width="14%" class="">40,750</td>
			<td width="15%" class="">11.7</td>
			<td width="6%" class="">100,000</td>
			<td width="7%" class="">28.8</td>
			<td width="6%" class=""></td>
			<td width="7%" class=""></td>
			<td width="11%" class="">40.5</td>
		</tr>
		<tr>
			<td width="5%" class="">2</td><td width="23%" class="">Arthémether + Luméfantrine /Plq de 18,</td><td width="7%" class="">4,500</td><td width="14%" class="">44,100</td><td width="15%" class="">9.8</td><td width="6%" class=""></td><td width="7%" class=""></td><td width="6%" class=""></td><td width="7%" class=""></td><td width="11%" class="">9.8</td>
		</tr>
		<tr>
			<td width="5%" class="">3</td><td width="23%" class="">Arthémether + Luméfantrine /Plq de 24,</td><td width="7%" class="">5,100</td><td width="14%" class="">72,500</td><td width="15%" class="">14.2</td><td width="6%" class=""></td><td width="7%" class=""></td><td width="6%" class=""></td><td width="7%" class=""></td><td width="11%" class="">14.2</td>
		</tr>
		<tr>
			<td width="5%" class="">4</td><td width="23%" class="">Arthémether + Luméfantrine /Plq de 6,</td><td width="7%" class="">3,100</td><td width="14%" class="">65,900</td><td width="15%" class="">21.3</td><td width="6%" class=""></td><td width="7%" class=""></td><td width="6%" class=""></td><td width="7%" class=""></td><td width="11%" class="">21.3</td>
		</tr>
		<tr>
			<td width="5%" class="">5</td><td width="23%" class="">AS/AQ 100mg/270mg</td><td width="7%" class="">6,150</td><td width="14%" class="">134,200</td><td width="15%" class="">21.8</td><td width="6%" class=""></td><td width="7%" class=""></td><td width="6%" class=""></td><td width="7%" class=""></td><td width="11%" class="">21.8</td>
		</tr>
		<tr>
			<td width="5%" class="">6</td><td width="23%" class="">AS/AQ 25mg/67.5mg</td><td width="7%" class="">4,350</td><td width="14%" class="">53,900</td><td width="15%" class="">12.4</td><td width="6%" class=""></td><td width="7%" class=""></td><td width="6%" class=""></td><td width="7%" class=""></td><td width="11%" class="">12.4</td>
		</tr>
		<tr>
			<td width="5%" class="">7</td><td width="23%" class="">AS/AQ 50mg/135mg</td><td width="7%" class="">5,050</td><td width="14%" class="">46,100</td><td width="15%" class="">9.1</td><td width="6%" class=""></td><td width="7%" class=""></td><td width="6%" class=""></td><td width="7%" class=""></td><td width="11%" class="">9.1</td>
		</tr>
		<tr>
			<td width="5%" class="">8</td><td width="23%" class="">Atesunate </td><td width="7%" class="">5,550</td><td width="14%" class="">74,200</td><td width="15%" class="">13.4</td><td width="6%" class=""></td><td width="7%" class=""></td><td width="6%" class=""></td><td width="7%" class=""></td><td width="11%" class="">13.4</td>
		</tr>
		<tr>
			<td width="5%" class="">9</td><td width="23%" class="">Nets</td><td width="7%" class="">6,700</td><td width="14%" class="">61,600</td><td width="15%" class="">9.2</td><td width="6%" class=""></td><td width="7%" class=""></td><td width="6%" class=""></td><td width="7%" class=""></td><td width="11%" class="">9.2</td>
		</tr>
		<tr>
			<td width="5%" class="">10</td><td width="23%" class="">Quinine 200mg 2ml</td><td width="7%" class="">6,400</td><td width="14%" class="">83,600</td><td width="15%" class="">13.1</td><td width="6%" class=""></td><td width="7%" class=""></td><td width="6%" class="">35,000</td><td width="7%" class="">5.5</td><td width="11%" class="">18.5</td>
		</tr>
		<tr>
			<td width="5%" class="">11</td><td width="23%" class="">Quinine 300mg</td><td width="7%" class="">6,150</td><td width="14%" class="">75,400</td><td width="15%" class="">12.3</td><td width="6%" class=""></td><td width="7%" class=""></td><td width="6%" class=""></td><td width="7%" class=""></td><td width="11%" class="">12.3</td>
		</tr>
		<tr>
			<td width="5%" class="">12</td><td width="23%" class="">Quinine 400mg 4ml</td><td width="7%" class="">4,725</td><td width="14%" class="">83,950</td><td width="15%" class="">17.8</td><td width="6%" class=""></td><td width="7%" class=""></td><td width="6%" class=""></td><td width="7%" class=""></td><td width="11%" class="">17.8</td>
		</tr>
		<tr>
			<td width="5%" class="">13</td><td width="23%" class="">RDTs</td><td width="7%" class="">4,275</td><td width="14%" class="">71,950</td><td width="15%" class="">16.8</td><td width="6%" class=""></td><td width="7%" class=""></td><td width="6%" class=""></td><td width="7%" class=""></td><td width="11%" class="">16.8</td>
		</tr>
		<tr>
			<td width="5%" class="">14</td><td width="23%" class="">SP</td><td width="7%" class="">5,850</td><td width="14%" class="">53,600</td><td width="15%" class="">9.2</td><td width="6%" class=""></td><td width="7%" class=""></td><td width="6%" class=""></td><td width="7%" class=""></td><td width="11%" class="">9.2</td>
		</tr>
		<tr>
			<td width="5%" class="">15</td><td width="23%" class="">SP+Amodiaquine 500md/25md/150mg</td><td width="7%" class="">6,800</td><td width="14%" class="">38,800</td><td width="15%" class="">5.7</td><td width="6%" class=""></td><td width="7%" class=""></td><td width="6%" class=""></td><td width="7%" class=""></td><td width="11%" class="">5.7</td>
		</tr>
	</tbody>
</table>';       	          
        $pdf->SetFont('dejavusans', '', 7);
        $pdf->writeHTMLCell(0, 0, '', 40, $tblHTML1, '', 1, 1, false, 'L', true);
        
    	$filePath=SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/StockStatusReport.pdf';
    	if (file_exists($filePath)) {
    		unlink($filePath);		
    	}
        
        $pdf->Output('pdfslice/StockStatusReport.pdf', 'F');
        
       	echo 'StockStatusReport.pdf';	
       		
	}else{
		echo 'Processing Error';
	}
}

?>