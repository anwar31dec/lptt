<?php
include ("define.inc");
$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());
mysql_query('SET CHARACTER SET utf8');

include('language/lang_en.php');
include('language/lang_fr.php');
include('language/lang_switcher_report.php');
$gTEXT = $TEXT;

$task = '';
if (isset($_POST['action'])) {
	$task = $_POST['action'];
} else if (isset($_GET['action'])) {
	$task = $_GET['action'];
}

switch($task) {
	case 'getBarChartFundingStatus' :
		getBarChartFundingStatus();
		break;
	case 'getFundingStatusData' :
		getFundingStatusData();
		break;	
	default :
		echo "{failure:true}";
		break;
}

function getBarChartFundingStatus(){
	$Year = $_POST['Year'];    
    $CountryId = $_POST['Country']; 
    $ItemGroup = $_POST['ItemGroup']; 
    if($ItemGroup){
		$ItemGroup = " AND g.ItemGroupId = '".$ItemGroup."' ";
	}
	
    mysql_query('SET CHARACTER SET utf8');
    if($_REQUEST['lan'] == 'fr-FR'){
        $aColumns = 'f.FundingReqSourceNameFrench FundingReqSourceName';   
    }else{
        $aColumns = 'f.FundingReqSourceName';   
    }
    
	$currDate=time();
	$data=array();
	$countData1=array();
	$countData2=array();
	$countData3=array();

	if(isset($_POST['Country'])&&!empty($_POST['Country'])){
		$countryQuery=" and p.CountryId='".$CountryId."' ";
	}else{
		$countryQuery="";
	}
	
	$sql="SELECT $aColumns,r.FundingReqId,r.ItemGroupId,r.Y1,r.Year,sum(p.Y1) Total 
			from t_yearly_pledged_funding p
			Inner Join t_yearly_funding_requirements r 
				on r.FundingReqSourceId=p.FundingReqSourceId and r.Year=p.Year and r.CountryId=p.CountryId and r.ItemGroupId = p.ItemGroupId
			Inner Join t_fundingreqsources f on f.FundingReqSourceId=r.FundingReqSourceId
            Inner Join t_itemgroup g on g.ItemGroupId =f.ItemGroupId 
			where p.Year='".$Year."' ".$countryQuery." ".$ItemGroup."
			group by p.FundingReqSourceId ";
			//echo $sql;
	$k=0;
	$result=mysql_query($sql);
	while($row=mysql_fetch_object($result)){
		$data[]=$row->FundingReqSourceName;
		$countData1[$k]=(float)$row->Y1;
		$countData2[$k]=(float)$row->Total;
		$k++;
	}		
	
	$allData=array();
	$sData=array();
	$tmpData=array();
	if($_REQUEST['lan'] == 'en-GB')
		$tmpData['name']='Requirements (USD)';
	else
		$tmpData['name']='Exigences (USD)';
	
	$tmpData['data']=$countData1;
	$tmpData['color']='#FFC545';
	$sData[]=$tmpData;
	$tmpData=array();
	
	
	if($_REQUEST['lan'] == 'en-GB')
		$tmpData['name']='Committed (USD)';
	else
		$tmpData['name']='Engagé (USD)';
		
	$tmpData['data']=$countData2;
	$tmpData['color']='#9AD268';
	$sData[]=$tmpData;		
	$allData['categories']=$data;
	$allData['dataSeries']=$sData;
	
	$allData['title']='Funding Status';
	
	echo json_encode($allData);
}

function getFundingStatusData(){
	$Year = $_POST['Year'];    
    $CountryId = $_POST['Country']; 
    $ItemGroup = $_POST['ItemGroup']; 
    if($ItemGroup){
		$ItemGroup = " AND g.ItemGroupId = '".$ItemGroup."' ";
	}
    
    mysql_query('SET CHARACTER SET utf8');
    if($_REQUEST['lan'] == 'fr-FR'){
        $aColumns = 'g.GroupNameFrench GroupName, f.FundingReqSourceNameFrench FundingReqSourceName';   
    }else{
        $aColumns = 'g.GroupName, f.FundingReqSourceName';   
    }
	
	if(isset($_POST['Country'])&&!empty($_POST['Country'])){
		$countryQuery=" and p.CountryId='".$CountryId."' ";
	}else{
		$countryQuery="";
	}
	
	$sql="	SELECT SQL_CALC_FOUND_ROWS $aColumns,r.FundingReqId,r.ItemGroupId,r.Y1,r.Year,sum(p.Y1) Total 
			from t_yearly_pledged_funding p
			Inner Join t_yearly_funding_requirements r 
				on r.FundingReqSourceId=p.FundingReqSourceId and r.Year=p.Year and r.CountryId=p.CountryId  and r.ItemGroupId = p.ItemGroupId
			Inner Join t_fundingreqsources f on f.FundingReqSourceId=r.FundingReqSourceId
			Inner Join t_itemgroup g on g.ItemGroupId =f.ItemGroupId 
			where p.Year='".$Year."' ".$countryQuery." ".$ItemGroup."
			group by g.GroupName,p.FundingReqSourceId "; 
			//
			//echo $sql;
	
	$result = mysql_query($sql);
	$total = mysql_num_rows($result);
	$sQuery = "SELECT FOUND_ROWS()";
	$rResultFilterTotal = mysql_query($sQuery);
	$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];

	$sOutput = '{';
	$sOutput .= '"sEcho": ' . intval($_POST['sEcho']) . ', ';
	$sOutput .= '"iTotalRecords": ' . $iFilteredTotal . ', ';
	$sOutput .= '"iTotalDisplayRecords": ' . $iFilteredTotal . ', ';
	$sOutput .= '"aaData": [ ';
	$serial = $_POST['iDisplayStart'] + 1;
	$f = 0;
    
	$superGrandSubTotal=0;$superGrandSubTotalActual=0;
	$groupsubTmp=-1;$p=0;$q=0;$grandSubTotal=0;$grandSubTotalActual=0;$grandGapSurplus=0;
	while ($aRow = mysql_fetch_array($result)) {

		//$ItemName = trim(preg_replace('/\s+/', ' ', addslashes($aRow['ItemName'])));
		// group total
                //echo $aRow['ItemName'];
                //echo "ddd";
                
		if($p!=0&&$groupsubTmp!=$aRow['GroupName']){
					
			$sOutput .= ',';
			$sOutput .= "[";
			$sOutput .= '"' .$groupsubTmp . ' Total",';
			$sOutput .= '"' . $groupsubTmp . '",';
			$sOutput .= '"",';
			$sOutput .= '"'.number_format($grandSubTotal).'",';
			$sOutput .= '"'.number_format($grandSubTotalActual).'"';     
			$sOutput .= "]";
			
			$superGrandSubTotal+=$grandSubTotal;
			$superGrandSubTotalActual+=$grandSubTotalActual;
			
			$grandSubTotal=0;
			$grandSubTotalActual=0;			
		}
		$groupsubTmp=$aRow['GroupName'];
		// group total
		if ($f++)
			$sOutput .= ',';
		$sOutput .= "[";
		$sOutput .= '"' . $serial++ . '",';
		$sOutput .= '"' . $aRow['GroupName'] . '",';
        $sOutput .= '"' . $aRow['FundingReqSourceName'] . '",';
		$sOutput .= '"' . number_format($aRow['Y1']) . '",';
 	    $sOutput .= '"' . number_format($aRow['Total']) . '"';        
		$sOutput .= "]";
		$grandSubTotal+=$aRow['Y1'];
		$grandSubTotalActual+=$aRow['Total'];
		
		if($p==$total-1){
			$sOutput .= ',';
			$sOutput .= "[";
			$sOutput .= '"' .$groupsubTmp . ' Total",';
			$sOutput .= '"' . $groupsubTmp . '",';
			$sOutput .= '"",';
			$sOutput .= '"'.number_format($grandSubTotal).'",';
			$sOutput .= '"'.number_format($grandSubTotalActual).'"';           
			$sOutput .= "]";			
			
			$superGrandSubTotal+=$grandSubTotal;
			$superGrandSubTotalActual+=$grandSubTotalActual;
			
			$grandSubTotal=0;
			$grandSubTotalActual=0;
			
			// Grand Total
			$sOutput .= ',';
			$sOutput .= "[";
			$sOutput .= '"Grand Total",';
			$sOutput .= '"' . $groupsubTmp . '",';
			$sOutput .= '"",';
			$sOutput .= '"'.number_format($superGrandSubTotal).'",';
			$sOutput .= '"'.number_format($superGrandSubTotalActual).'"';           
			$sOutput .= "]";			
		}
		$p++;$q++;
	}
	$sOutput .= '] }';
	echo $sOutput;         
}
