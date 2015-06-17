<?php

require ("define.inc");
$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());


include('function_lib.php');
include('language/lang_en.php');
include('language/lang_fr.php');
include('language/lang_switcher_report.php');
$gTEXT = $TEXT;

mysql_query("SET character_set_client=utf8");
mysql_query("SET character_set_connection=utf8");

$task = '';
if (isset($_POST['action'])) {
    $task = $_POST['action'];
} else if (isset($_GET['action'])) {
    $task = $_GET['action'];
}

switch ($task) {
    case 'getCountryList':
        getCountryList($conn);
        break;
    case 'getProductList':
        getProductList($conn);
        break;
    case 'insertAllorOneMapping':
        insertAllorOneMapping($conn);
        break;
    default :
        echo "{failure:true}";
        break;
}

/* * ***************************************************lab user authentication***************************************** */

function getCountryList($conn) {
	 
    global $gTEXT; 	
	
	$lan = $_POST['lan'];
	 if($lan == 'en-GB'){
            $CountryName = 'CountryName';
        }else{
            $CountryName = 'CountryNameFrench';
        }
		
	$userName = $_POST['userName'];   
	
    mysql_query('SET CHARACTER SET utf8');
    
    $sWhere = "";
	if ($_POST['sSearch'] != "") { $sWhere = " and ($CountryName like '%".mysql_real_escape_string($_POST['sSearch'])."%')";                                                                                         
	}
    
    $sLimit = "";
	if (isset($_POST['iDisplayStart'])) { 
	   $sLimit = "limit " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
	}
    
    $sOrder = "";
	if (isset($_POST['iSortCol_0'])) { $sOrder = " ORDER BY  ";
		for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
			$sOrder .= fnColumnToGetCountry(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
		}
		$sOrder = substr_replace($sOrder, "", -2);
	}
 
	$sql = "SELECT a.CountryId, CountryCode,$CountryName CountryName 	
            FROM t_country a	
            INNER JOIN t_user_country_map b ON a.CountryId = b.CountryId
            WHERE b.UserId = '".$userName."' 
            ".$sWhere." ".$sOrder." ".$sLimit." "; 
   // echo $sql;
	$pacrs = mysql_query($sql, $conn);
	$sql = "SELECT FOUND_ROWS()";
	$rs = mysql_query($sql, $conn);
	$r = mysql_fetch_array($rs);
	$total = $r[0];
	echo '{ "sEcho": ' . intval($_POST['sEcho']) . ', "iTotalRecords":' . $total . ', "iTotalDisplayRecords": ' . $total . ', "aaData":[';
	$f = 0;
	$serial = $_POST['iDisplayStart'] + 1;		
	  
	while ($row = @mysql_fetch_object($pacrs)) {
	    $CountryId = $row -> CountryId;
		$CountryName = $row -> CountryName;	
      
		if ($f++)
			echo ",";           
                 
        echo '["'.$CountryId.'", "'.$serial.'", "'.$CountryName.'"]'; 
        $serial++;
	}
    echo ']}';
}

function fnColumnToGetCountry($i) {
	if ($i == 2)
		return "CountryName ";
}


function getcheckBox($v){ 
    if ($v == "true") {
        $x="<input type='checkbox' checked class='datacell' value='".$v."' /><span class='custom-checkbox'></span>";
    } else {
        $x="<input type='checkbox' class='datacell' value='".$v."' /><span class='custom-checkbox'></span>";
    } 
    return $x;
}

function getProductList($conn) { 
    
    mysql_query('SET CHARACTER SET utf8');
    $CountryId = $_POST['SelCountryId'];
    $mode = $_POST['mode']; 
      
    $lan = $_POST['lan'];
	 if($lan == 'en-GB'){
            $GroupName = 'GroupName';
        }else{
            $GroupName = 'GroupNameFrench';
        }
		
    $sWhere = "";
	if ($_POST['sSearch'] != "") { $sWhere = " WHERE ($GroupName like '%".mysql_real_escape_string($_POST['sSearch'])."%' OR 
                                                ItemName like '%".mysql_real_escape_string($_POST['sSearch'])."%' 
                                               OR " . " ItemCode like '%" . mysql_real_escape_string($_POST['sSearch']) . "%' )";
    }
    
    $sLimit = "";
	if (isset($_POST['iDisplayStart'])) { 
	   $sLimit = "limit " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
	}
    
    $sOrder = "";
	if (isset($_POST['iSortCol_0'])) { $sOrder = " ORDER BY  ";
		for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
			$sOrder .= fnColumnToGetProduct(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
		}
		$sOrder = substr_replace($sOrder, "", -2);
	}
    
    if ($mode == 'edit') {
        $sql = " SELECT SQL_CALC_FOUND_ROWS a.CountryProductId, a.CountryId, b.ItemNo, IF(a.CountryProductId is Null,'false','true') chkValue, ItemCode, b.ItemGroupId, 
                 ItemName, $GroupName GroupName  	 	
                 FROM  t_country_product a 
                 RIGHT JOIN t_itemlist b ON (a.ItemNo = b.ItemNo AND a.CountryId = '".$CountryId."')
                 INNER JOIN t_itemgroup c ON b.ItemGroupId = c.ItemGroupId
                 ".$sWhere." ".$sOrder."  ".$sLimit.""; 
    }else{
        $sql = " SELECT SQL_CALC_FOUND_ROWS a.CountryProductId, a.CountryId, b.ItemNo, IF(a.CountryProductId is Null,'false','true') chkValue, ItemCode, b.ItemGroupId, 
                 ItemName, $GroupName GroupName  	 	
                 FROM  t_country_product a 
                 INNER JOIN t_itemlist b ON (a.ItemNo = b.ItemNo AND a.CountryId = '".$CountryId."')
                 INNER JOIN t_itemgroup c ON b.ItemGroupId = c.ItemGroupId
                 ".$sWhere." ".$sOrder." ".$sLimit."";
    }   
    
	$pacrs = mysql_query($sql, $conn);
	$sql = "SELECT FOUND_ROWS()";
	$rs = mysql_query($sql, $conn);
	$r = mysql_fetch_array($rs);
	$total = $r[0];
	echo '{ "sEcho": ' . intval($_POST['sEcho']) . ', "iTotalRecords":' . $total . ', "iTotalDisplayRecords": ' . $total . ', "aaData":[';
	$f = 0;
	$serial = $_POST['iDisplayStart'] + 1;		
	  
	while ($row = @mysql_fetch_object($pacrs)) {
	    $CountryProductId = $row -> CountryProductId;
        $ItemGroupId = $row -> ItemGroupId;
        $ItemNo = $row -> ItemNo;
        $ItemCode = $row -> ItemCode;		
		$ItemName = trim(preg_replace('/\s+/', ' ', addslashes($row -> ItemName)));
        $GroupName = trim(preg_replace('/\s+/', ' ', addslashes($row -> GroupName)));
 	    $chkValue = $row -> chkValue;
        
		if ($f++)
			echo ",";           
                 
        echo '["'.$CountryProductId.'", "'.getcheckBox($chkValue)." ".$ItemCode.'", "'.$ItemName.'", "'.$GroupName.'", "'.$ItemNo.'", "'.$ItemGroupId.'"]';
         
        $serial++;
	}
    echo ']}';
}

function fnColumnToGetProduct($i) {
    if ($i == 2)
        return "ItemName ";
    else if ($i == 3)
        return "GroupName ";
}

function insertAllorOneMapping($conn) {

    //$CountryProductId = $_POST['CountryProductId'];
    $CountryId = $_POST['SelCountryId'];
    $ItemNo = $_POST['ItemNo'];
    $ItemGroupId = $_POST['ItemGroupId'];
    $checkVal = $_POST['checkVal'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($checkVal == "true") {
        $sql = "INSERT INTO  t_country_product (CountryProductId, CountryId, ItemNo, ItemGroupId) 
                VALUES ('', '" . $CountryId . "', '" . $ItemNo . "', '" . $ItemGroupId . "') ";

        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_country_product', 'pks' => array('CountryProductId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {
        $sql = "DELETE FROM t_country_product WHERE CountryId = '" . $CountryId . "' 
                AND ItemNo =  '" . $ItemNo . "'
                AND ItemGroupId = '" . $ItemGroupId . "' ";

        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_country_product', 'pks' => array('CountryProductId', 'ItemNo', 'ItemGroupId'), 'pk_values' => array($CountryId, $ItemNo, $ItemGroupId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }

    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

?>