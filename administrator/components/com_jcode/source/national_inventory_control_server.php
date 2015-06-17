<?php
include_once ('database_conn.php');
include_once ("function_lib.php");

$task = '';
if (isset($_REQUEST['operation'])) {
	$task = $_REQUEST['operation'];
}

switch($task) {
	case "getMosType" :
		getMosType();
		break;
	case "getMosTypeProduct" :
		getMosTypeProduct();
		break;	
	case "getLegendMos" :
		getLegendMos();
		break;
	default :
		echo "{failure:true}";
		break;
}

function getMosType() {
	$lan = $_REQUEST['lan'];
	$mosTypeId = $_REQUEST['MosTypeId'];
		
	if($lan == 'en-GB'){
            $mosTypeName = 'MosTypeName';
			$mos = 'MOS';
			$productName = 'Product Name';
        }else{
            $mosTypeName = 'MosTypeNameFrench';
			$mos = 'MSD';
			$productName = 'Nom du produit';		
        }     
 
	$sQuery = "SELECT MosTypeId, $mosTypeName MosTypeName, MinMos, MaxMos, ColorCode 
			FROM
			    t_mostype
			WHERE (MosTypeId = $mosTypeId OR $mosTypeId = 0)
			ORDER BY MosTypeId;";
 
	$rResult = safe_query($sQuery);
	$output = array();
	while ($row = mysql_fetch_array($rResult)) {
		$tmpRow['sTitle'] = $row['MosTypeName'];
		$tmpRow['sClass'] = 'center-aln';
		$tmpRow['MosTypeId'] = $row['MosTypeId'];
		$tmpRow['MosTypeName'] = $row['MosTypeName'];
		$tmpRow['MinMos'] = $row['MinMos'];
		$tmpRow['MaxMos'] = $row['MaxMos'];
		$tmpRow['ColorCode'] = $row['ColorCode'];
		$output[] = $tmpRow;
	}
	array_unshift($output, array('sTitle' => $productName, 'sWidth' => '30%'), array('sTitle' => $mos, 'sClass' => 'right-aln', 'sWidth' => '7%'));
	echo json_encode($output);
}

function getMosTypeProduct() {
	$lan = $_REQUEST['lan'];
	$mosTypeId = $_REQUEST['MosTypeId'];
	$countryId = $_REQUEST['CountryId'];
	$ItemGroupId = $_REQUEST['ItemGroupId'];
	$Reportby = $_POST['Reportby'];
	if($lan == 'en-GB'){
            $mosTypeName = 'MosTypeName';
        }else{
            $mosTypeName = 'MosTypeNameFrench';
        }   

	$sQuery1 = "SELECT
			    MosTypeId
			    , $mosTypeName MosTypeName
			    , ColorCode
			FROM
			    t_mostype
			WHERE (MosTypeId = $mosTypeId OR $mosTypeId = 0)
			ORDER BY MosTypeId;";
  
	$rResult1 = safe_query($sQuery1);

	$output1 = array();

	while ($row1 = mysql_fetch_array($rResult1)) {
		$output1[] = $row1;
	}
	
	
if($ItemGroupId > 0){
	$sQuery = "SELECT p.MosTypeId, ItemName, MOS FROM (SELECT
				    a.ItemNo
				    , b.ItemName
				    , IFNULL(a.MOS,0) AS MOS
				,(SELECT MosTypeId FROM t_mostype x WHERE (MosTypeId = $mosTypeId OR $mosTypeId = 0) AND IFNULL(a.MOS,0) >= x.MinMos AND IFNULL(a.MOS,0) < x.MaxMos) MosTypeId
				FROM t_cnm_stockstatus a, t_itemlist b,  t_cnm_masterstockstatus c
				WHERE a.itemno = b.itemno AND a.MonthId = " . $_REQUEST['MonthId'] . " 
				AND a.Year = '" . $_REQUEST['YearId'] . "' 
				AND a.CountryId = " . $_REQUEST['CountryId'] . " 
				AND a.ItemGroupId = " . $_REQUEST['ItemGroupId'] . " 
				AND c.OwnerTypeId = " . $Reportby . " 
				AND a.CNMStockId = c.CNMStockId" . " AND c.StatusId = 5 " . ") p
				WHERE (p.MosTypeId = $mosTypeId OR $mosTypeId = 0) 
				ORDER BY ItemName";
   }else{
	$sQuery = "SELECT p.MosTypeId, ItemName, MOS FROM (SELECT
				    a.ItemNo
				    , b.ItemName
				    , IFNULL(a.MOS,0) AS MOS
				,(SELECT MosTypeId FROM t_mostype x WHERE (MosTypeId = $mosTypeId OR $mosTypeId = 0) AND IFNULL(a.MOS,0) >= x.MinMos AND IFNULL(a.MOS,0) < x.MaxMos) MosTypeId
				 FROM t_cnm_stockstatus a, t_itemlist b,  t_cnm_masterstockstatus c
				WHERE a.itemno = b.itemno  
				AND a.MonthId = " . $_REQUEST['MonthId'] . " 
				AND a.Year = '" . $_REQUEST['YearId'] . "' 
				AND a.CountryId = " . $_REQUEST['CountryId'] . " 
				AND c.OwnerTypeId = " . $Reportby . " 
				AND b.bCommonBasket = 1 
				AND a.CNMStockId = c.CNMStockId" . " AND c.StatusId = 5 " . ") p
				WHERE (p.MosTypeId = $mosTypeId OR $mosTypeId = 0) 
				GROUP by p.MosTypeId, ItemName
				ORDER BY ItemName";
	
  }
	//echo $sQuery;

	$rResult = safe_query($sQuery);

	$aData = array();

	while ($row = mysql_fetch_array($rResult)) {
		$tmpRow = array();
		foreach ($output1 as $rowMosType) {
			if ($rowMosType['MosTypeId'] == $row['MosTypeId']) {
				$tmpRow[] = '<i class="fa fa-check-circle fa-lg" style="color:' . $rowMosType['ColorCode'] . ';font-size:2.5em;"></i>';

			} else
				$tmpRow[] = '';
		}
		array_unshift($tmpRow, $row['ItemName'], number_format($row['MOS'], 1));
		$aData[] = $tmpRow;
	}

	echo '{"sEcho": ' . intval($_REQUEST['sEcho']) . ', "iTotalRecords":"10","iTotalDisplayRecords": "10", "aaData":' . json_encode($aData) . '}';
	
}


function getLegendMos() {
	$lan = $_REQUEST['lan'];
	
	if($lan == 'en-GB'){
            $mosTypeName = 'MosTypeName';
        }else{
            $mosTypeName = 'MosTypeNameFrench';
        } 

	$sQuery = "SELECT MosTypeId,$mosTypeName MosTypeName, MinMos, MaxMos, ColorCode, MosLabel
			FROM
			    t_mostype
			ORDER BY MosTypeId;";

	$rResult = safe_query($sQuery);

	$output = array();

	while ($row = mysql_fetch_array($rResult)) {
		$output[] = $row;
	}
	echo json_encode($output);

 }
?>