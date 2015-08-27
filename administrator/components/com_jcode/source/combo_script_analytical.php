<script type="text/javascript">

<?php
$user = JFactory::getUser();
$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
$jBaseUrl = JURI::base();
$userName = $user->username;
$lang = JFactory::getLanguage();
$lan = $lang->getTag();

//====================================================================================

$sQuery = "SELECT MonthId, MonthName FROM  t_month
                    ORDER BY MonthId";

$rResult = safe_query($sQuery);

$output = array();

while ($obj = mysql_fetch_object($rResult)) {
    $output[] = $obj;
}
echo ' var gMonthList = JSON.parse(\'' . json_encode($output, JSON_HEX_APOS) . '\');';

//==================================================================================

$sQuery = "SELECT YearId, YearName FROM  t_year";

$rResult = safe_query($sQuery);

$output = array();

while ($obj = mysql_fetch_object($rResult)) {
    $output[] = $obj;
}

echo ' var gYearList = JSON.parse(\'' . json_encode($output, JSON_HEX_APOS) . '\');';

//====================================================================================

$sQuery = "SELECT b.ProcessId, a.ProcessName, UsualDuration, ProcessOrder, ParentProcessId
                                        FROM t_process_list a
                    INNER JOIN t_user_process_map b ON a.ProcessId = b.ProcessId
                    WHERE b.UserId = '$userName' AND a.ProcUnitId = 2
                    ORDER BY a.ProcessName";      
//exit();					

$rResult = safe_query($sQuery);

$aUserProcess = array();

while ($aRow = mysql_fetch_assoc($rResult)) {
    $aUserProcess = $aRow;
}

?>

//====================================================================================

</script>