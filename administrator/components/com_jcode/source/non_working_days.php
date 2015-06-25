<?php 
include_once ('database_conn.php');
include_once ("function_lib.php");
include('language/lang_en.php');
include('language/lang_fr.php');
include('language/lang_switcher_report.php');
include_once ("working_days.php");

function getWednesdays($y, $m)
{
    return new DatePeriod(
        new DateTime("first saturday of $y-$m"),
        DateInterval::createFromDateString('next saturday'),
        new DateTime("last day of $y-$m")
    );
}

/* foreach (getWednesdays(2015, 06) as $saturday) {
    echo $saturday->format("l, Y-m-d\n")."<br/>";
} */
$count=1;
for($monthId = 1;$monthId<=12;$monthId++){
	foreach (getWednesdays(2015, $monthId) as $saturday) {
		//echo $saturday->format("l, Y-m-d\n")."<br/>";
		$nwd = $saturday->format("Y-m-d")."<br/>";
		$sql = "INSERT INTO t_non_working_days (NwdDate, TYPE) VALUES ('$nwd', 'WEEKEND');";
		$aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_non_working_days', 'pks' => array('NwdId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
		$aQuerys[] = $aQuery1;
	}
}
echo json_encode(exec_query($aQuerys, $jUserId, $language));
?>