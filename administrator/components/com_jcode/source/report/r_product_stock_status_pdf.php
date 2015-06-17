<?php
include("../define.inc");


$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());

error_reporting(0);

$gTEXT = $TEXT;
$jBaseUrl = $_GET['jBaseUrl']; 

$task = '';
if (isset($_POST['action'])) {
	$task = $_POST['action'];
} else if (isset($_GET['action'])) {
	$task = $_GET['action'];
}

switch($task) {
	case 'prepareProductStockReport' :
		prepareProductStockReport($conn);
		break;
	case 'generateProductStockReport' :
		generateProductStockReport($conn);		
		break;	
   	default :
		echo "{failure:true}";
		break;
}

function getSingleValue($query) {
	$result1 = safe_query($query);
	while($row = mysql_fetch_array($result1))
		$value = $row[0];
	return $value;
}





function createThumbnail($filename, $thname, $width=100, $height=100, $cdn=null)
{
	 
	$filePath =  'pdfslice';   
    try {
        $extension = substr($filename, (strrpos($filename, '.')) + 1 - strlen($filename));
        $fallback_save_path = $filePath;
          
        if ($extension == "svg") {
            $im = new Imagick();
            $svgdata = file_get_contents($filename);
            $svgdata = svgScaleHack($svgdata, $width, $height);

            //$im->setBackgroundColor(new ImagickPixel('transparent'));
            $im->readImageBlob($svgdata);

            $im->setImageFormat("jpg");
            $im->resizeImage($width, $height, imagick::FILTER_LANCZOS, 1);

            $raw_data = $im->getImageBlob();

            (is_null($cdn)) ? file_put_contents($fallback_save_path . '/' . $thname, $im->getImageBlob()) : '';
        } else if ($extension == "jpg") {
            $im = new Imagick($filename);
            $im->stripImage();

            // Save as progressive JPEG
            $im->setInterlaceScheme(Imagick::INTERLACE_PLANE);
            $raw_data = $im->resizeImage($width, $height, imagick::FILTER_LANCZOS, 1);

            // Set quality
            // $im->setImageCompressionQuality(85);

            (is_null($cdn)) ? $im->writeImage($fallback_save_path . '/' . $thname) : '';
        }

        if (!is_null($cdn)) {
            $imageObject = $cdn->DataObject();
            $imageObject->SetData( $raw_data );
            $imageObject->name = $thname;
            $imageObject->content_type = 'image/jpg';
            $imageObject->Create();
        }

        $im->clear();
        $im->destroy();
        return true;
    }
    catch(Exception $e) {
        return false;
    }
}

function svgScaleHack($svg, $minWidth, $minHeight)
{
    $reW = '/(.*<svg[^>]* width=")([\d.]+px)(.*)/si';
    $reH = '/(.*<svg[^>]* height=")([\d.]+px)(.*)/si';
    preg_match($reW, $svg, $mw);
    preg_match($reH, $svg, $mh);
    $width = floatval($mw[2]);
    $height = floatval($mh[2]);
    if (!$width || !$height) return false;

    // scale to make width and height big enough
    $scale = 1;
    if ($width < $minWidth)
        $scale = $minWidth/$width;
    if ($height < $minHeight)
        $scale = max($scale, ($minHeight/$height));

    $width *= $scale*2;
    $height *= $scale*2;

    $svg = preg_replace($reW, "\${1}{$width}px\${3}", $svg);
    $svg = preg_replace($reH, "\${1}{$height}px\${3}", $svg);

    return $svg;
}
function prepareProductStockReport(){	
echo 'softworks ltd';	  
    require_once('tcpdf/tcpdf.php');
    ini_set('magic_quotes_gpc', 'off');
    $html=htmlentities($_POST['html'], ENT_QUOTES, "UTF-8");
    $html=html_entity_decode($html, ENT_QUOTES, "UTF-8");
    $alavel=htmlentities($_POST['alavel'], ENT_QUOTES, "UTF-8");
    $alavel=html_entity_decode($alavel, ENT_QUOTES, "UTF-8");

   $filePath = 'D:/xampp/htdocs/ospsante/administrator/components/com_jcode/source/report/pdfslice/product_stock123456.svg'; 
    if (file_exists($filePath)) {
    	unlink($filePath);	
echo 'softworks';		
    }	
    $file = fopen($filePath,"w");
    fwrite($file, $html);
	
    fclose($file);	
}

function getLegendMos($ItemGroup) {
    
	 

	$sQuery = "SELECT MosTypeId, MosTypeName, MinMos, MaxMos, ColorCode, MosLabel 
                FROM t_mostype
                WHERE ItemGroupId = ".$ItemGroup."
                ORDER BY MosTypeId;"; 

	$rResult = mysql_query($sQuery);

	$output = array();
    $x="";//<table><tr>
	$y.="</tr><tr>";
	while ($row = mysql_fetch_array($rResult)) {
		
		//$x.='<td width="10px" height="24px" style="background-color:"' .$row['ColorCode'].'"">&nbsp</td>';
		
	    $x.=$row['ColorCode'];
		break;
		$y.="<td>MOS: ".$row['MosLabel']. "</td>"; 
	}
	$legendtable.=$x;
	 //$legendtable.=$x.$y."</tr></table>";
	return $legendtable;
	 
}

function generateProductStockReport($conn){
	 
	$jBaseUrl = $_POST['jBaseUrl']; 
	$tbltitle=$_REQUEST['tbltitle'];
    $filePath =  'pdfslice/product_stock.svg';    
     $Year=$_REQUEST['Year'];
	$MonthId=$_REQUEST['Month'];
	$ItemGroupId=$_REQUEST['ItemGroup'];
    $bKeyItem = $_REQUEST['bKeyItem'];
	if($bKeyItem == 1){
        $tp = "Tracer Products";
    }else{
        $tp = "All Products";
    }
	
 	$ItemGroupName = $_REQUEST['ItemGroupName'];
	$itemNo = $_REQUEST['ItemNo'];
	$bKeyItemName = $_REQUEST['bKeyItemName'];
	$MonthName = $_REQUEST['MonthName'];
	 
	$ItemName = $_REQUEST['ItemName'];
	$CountryId = 1;
    
	
	$alavel=htmlentities($_POST['alavel'], ENT_QUOTES, "UTF-8");
    $alavel=html_entity_decode($alavel, ENT_QUOTES, "UTF-8");
	 
    require_once('tcpdf/tcpdf.php');
    require_once('fpdf/fpdi.php');  
    
	
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
 
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);  
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    	require_once(dirname(__FILE__).'/lang/eng.php');
    	$pdf->setLanguageArray($l);
    }
    
    $pdf->SetFont('dejavusans', '', 7);
    $pdf->AddPage();
    
	$html0 = ' 			
								<table >
				            	  <tr >
				            		<td width="60" align="center" height="100"  ><img src="../images/logo.png"/></td>
				                    
				            	  </tr>
				            	  
								  </table>';
	$pdf->writeHTMLCell(0, 0, 35, 7, $html0, '', 0, 0, false, 'C', true); 
	
	$html1 = '
		    					 
								
								<table >
				            	  <tr >
				            		 
				                    <td width="400" align="center" style="font-size: 16px;" >  Swaziland Health Product Tracking System </td> 
				            	  </tr>
				            	  
								  </table>';
	$pdf->writeHTMLCell(0, 0, 50, 10, $html1, '', 0, 0, false, 'C', true); 
	
	$html = '
		    					 
								
							 
		        
                  <div class="padding-md clearfix">
                   <h3 style="text-align:center;">Product Stock Status - '.$ItemGroupName.' ('.$tp.')</h3>
                  </div>
     
                 
     ';
	 //<div style="margin:10px ; padding:20px;border: 1px solid gray "></div>
	  
	 $html .= ' <img src="'.$filePath.'" width=auto height=auto />
	 
	 <table style="text-aling:center"> <tr><td></td><td></td>';
	 
	 $sQuery = "SELECT MosTypeId, MosTypeName, MinMos, MaxMos, ColorCode, MosLabel 
                FROM t_mostype
                WHERE ItemGroupId = ".$ItemGroupId."
                ORDER BY MosTypeId ;"; 

	$rResult = mysql_query($sQuery);

	$output = array();
    $x="";//<table><tr>
	$y.="</tr><tr><td></td><td></td>";
	while ($row = mysql_fetch_array($rResult)) {
		
		$x .= '<td style="background-color:'.$row['ColorCode'].'; width:65px;"> </td>';
		  
		$y.="<td>MOS: ".$row['MosLabel']. "</td>"; 
	}
	$html .=$x.$y.'</tr>';
	
	 
	$html .=  ' <tr><td></td></tr><tr><td></td></tr></table>
	
	         <style>
             td{
                 height: 6px;
                 line-height:3px;
             }
             th{
                height: 20;
            }
            </style>
	    
		
	 		<table width="635px"  border="0.5" style="margin:0 auto; font-size: 8px; font-family: sans-serif">
	 		<tr><th style="text-align:center;"><b> '.$tbltitle.'</b></th></tr>
            	  <tr >
            		<td width="30" align="center"  ><b>SL</b></td>
            		 <td width="30" align="left"> </td>
                    <td width="200" align="left"><b>Products</b></td>
            		<td width="105" align="right"><b>Reported Closing Balance</b></td>
                    <td width="105" align="right"><b>Reported Consumption</b></td> 	
            		<td width="105" align="right"><b>Average Monthly Consumption</b></td>
            		<td width="60"  align="right"><b>MOS</b></td>
            	  </tr> 
            	   
	 ';
	 
	 
	mysql_query("SET @rank=0;");

	$serial = "@rank:=@rank+1 AS SL";
    $sql = "SELECT SQL_CALC_FOUND_ROWS " . $serial . "
				, ItemName			    
			    , DispenseQty
			    , ClStock			    
			    , AMC
			    , MOS
			    , ColorCode
			    FROM 
				/* b-START */(SELECT ItemName			    
			    , SUM(DispenseQty) DispenseQty
			    , SUM(ClStock) ClStock			    
			    , SUM(AMC) AMC
			    , IFNULL(SUM(ClStock)/SUM(AMC), 0) MOS
			    , /* Co-START */(SELECT ColorCode FROM t_mostype
			WHERE (ItemGroupId = $ItemGroupId AND IFNULL(SUM(a.ClStock)/SUM(a.AMC), 0) >= MinMos AND IFNULL(SUM(a.ClStock)/SUM(a.AMC), 0) < MaxMos)) ColorCode /* Co-END */ FROM
			/* a-START */(SELECT
			    t_cfm_masterstockstatus.ItemGroupId
			    , t_cfm_stockstatus.ItemNo
			    , t_itemlist.ItemName
			    , t_itemlist.ShortName
			    , SUM(t_cfm_stockstatus.ClStock) ClStock
			    , 0  DispenseQty
			    , 0  AMC       
			FROM
			    t_cfm_stockstatus
			    INNER JOIN t_cfm_masterstockstatus 
			        ON (t_cfm_stockstatus.CFMStockId = t_cfm_masterstockstatus.CFMStockId)
			    INNER JOIN t_itemlist 
			        ON (t_cfm_stockstatus.ItemNo = t_itemlist.ItemNo)
			    INNER JOIN t_facility 
			        ON (t_cfm_stockstatus.FacilityId = t_facility.FacilityId)
			    INNER JOIN t_facility_group_map 
			        ON (t_facility_group_map.FacilityId = t_facility.FacilityId AND t_facility_group_map.ItemGroupId = $ItemGroupId) 
			WHERE (t_cfm_masterstockstatus.Year = '$Year'
			    AND t_cfm_masterstockstatus.MonthId = $MonthId
			    AND t_cfm_masterstockstatus.CountryId = $CountryId
			    AND t_cfm_masterstockstatus.StatusId = 5
			    AND t_cfm_masterstockstatus.ItemGroupId = $ItemGroupId
			    AND t_itemlist.bKeyItem = $bKeyItem)
			GROUP BY t_cfm_masterstockstatus.ItemGroupId, t_cfm_stockstatus.ItemNo
			UNION ALL
			SELECT
			    t_cfm_masterstockstatus.ItemGroupId
			    , t_cfm_stockstatus.ItemNo
			    , t_itemlist.ItemName
			    , t_itemlist.ShortName   
			    , 0 ClStock
			    , SUM(t_cfm_stockstatus.DispenseQty) DispenseQty
			    , SUM(t_cfm_stockstatus.AMC) AMC    
			FROM
			    t_cfm_stockstatus
			    INNER JOIN t_cfm_masterstockstatus 
			        ON (t_cfm_stockstatus.CFMStockId = t_cfm_masterstockstatus.CFMStockId)
			    INNER JOIN t_itemlist 
			        ON (t_cfm_stockstatus.ItemNo = t_itemlist.ItemNo)
			    INNER JOIN t_facility 
			        ON (t_cfm_stockstatus.FacilityId = t_facility.FacilityId)
			    INNER JOIN t_facility_group_map 
			        ON (t_facility_group_map.FacilityId = t_facility.FacilityId AND t_facility_group_map.ItemGroupId = 2) 
			WHERE (t_cfm_masterstockstatus.Year = '$Year'
			    AND t_cfm_masterstockstatus.MonthId = $MonthId
			    AND t_cfm_masterstockstatus.CountryId = $CountryId
			    AND t_cfm_masterstockstatus.StatusId = 5
			    AND t_cfm_masterstockstatus.ItemGroupId = $ItemGroupId
			    AND t_itemlist.bKeyItem = $bKeyItem
			    AND t_facility.FLevelId = 99)
			GROUP BY t_cfm_masterstockstatus.ItemGroupId, t_cfm_stockstatus.ItemNo) a /* a-END */
			GROUP BY ItemGroupId, ItemNo, ItemName, ShortName) b /* b-END */
			WHERE 1=1
				 ORDER BY  	ItemName ";  
	 	 
    $result = mysql_query($sql,$conn);
	$total = mysql_num_rows($result);             
					
	   // $data=array();
        // $f=0; 
        // $tblHTML='';
    	// while ($rec = mysql_fetch_array($result)) {
            // $data['SL'][$f]=$f;
    		// $data['ItemName'][$f] = $rec['ItemName'];
    		// $data['ReportedConsumption'][$f]=number_format($rec['ReportedConsumption']);
    		// $data['ReportedClosingBalance'][$f]=number_format($rec['ReportedClosingBalance']);
    		// $data['AMC'][$f]=number_format($rec['AMC']);
    		// $data['MOS'][$f] = number_format(($rec['MOS']),1);
//             
            // $html.='<tr style="page-break-inside:avoid;font-size: 7px; font-family: sans-serif">
                            // <td align="center" width="30" valign="middle" height="20" padding="15" >'.($data['SL'][$f]+1).'</td>  
                            // <td align="left" width="200" valign="middle"  >'.$data['ItemName'][$f].'</td>
                            // <td align="right" width="105" valign="middle">'.$data['ReportedConsumption'][$f].'</td>
                            // <td align="right" width="105" valign="middle">'.$data['ReportedClosingBalance'][$f].'</td>
                            // <td align="right" width="105" valign="middle">'.$data['AMC'][$f].'</td>
                            // <td align="right" width="60" valign="middle">'.$data['MOS'][$f].'</td> 
                    // </tr>';
            // $f++;	        	
    	// }  			
// 		
// 		
		
		
		$aColumns = array('SL', 'ColorCode', 'ItemName', 'DispenseQty', 'ClStock', 'AMC', 'MOS');
        while($aRow=mysql_fetch_array($result)){
        	
			$aRow['ItemName'] = crnl2br($aRow['ItemName']);		
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {			
				if (is_null($aRow[$aColumns[$i]]))
					$row[] = '';
				else {
					if ($aColumns[$i] == 'ClStock' || $aColumns[$i] == 'AMC')
						$row[] = number_format($aRow[$aColumns[$i]]);
					else if ($aColumns[$i] == 'MOS')
						$row[] = number_format($aRow[$aColumns[$i]], 1);
					else if ($aColumns[$i] == 'ColorCode')
						$row[] = '<span style="background-color:' . $aRow[$aColumns[$i]]  . '; width:30px;">						</span>';
					else
						$row[] = $aRow[$aColumns[$i]];
				}
			}
			
			$output['aaData'][] = $row;
               				 
            // $i++; 

        }
 
       for($i=0;$i<count($output['aaData']);$i++)
	   {
	    
	   	$html .= '<tr>';
		for($j=0;$j<count($output['aaData'][$i]);$j++)
		{
			if($j==0 OR $j==1)
			{
				$w='30px';
				$al='center';
			}
			else if($j==2)
			{
				$w='200px';
				$al='left';
			}
			else if($j==6)
			{
				$w='60px';
				$al='right';
			}
			else 
				{
					$w='105px';
				       $al='right';
				}
			 
			 
			 $html .= '<td style="text-align: '.$al.'; width:'.$w.';">'.$output['aaData'][$i][$j].'</td>';
			 
		}
		 $html .= '</tr>';
	   	  
	   	
	   }
		
					
					
		$html .= ' </table> ';			
	 
   
	$pdf->writeHTMLCell(0, 0, 10, 10, $html, '', 0, 0, false, 'C', true); 
 
	$filePath=SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/Product_Stock_Status_Chart.pdf';
	if (file_exists($filePath)) {
		unlink($filePath);		
	}	
    $filename='Product_Stock_Report_'.$ItemGroupName.'_'.$MonthName.'_'.$Year.'.pdf';
	$pdf->Output('pdfslice/'.$filename, 'F');
	
	echo $filename;	
	
	
   
}

function crnl2br($string){
	$patterns = array ('/\r/','/\t/','/\n/');
	$replace = array ('', ' ', ' ');
	return preg_replace($patterns, $replace, $string);
}

?>