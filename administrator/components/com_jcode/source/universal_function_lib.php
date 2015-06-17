<?php
include ("define.inc");
$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die("Could not connect: " . mysql_error());
if (!mysql_select_db(DBNAME, $conn)) {echo 'Could not select database';
	exit ;
}

mysql_query("SET character_set_results=utf8");

$MONTH_NAME = array(1 => 'JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC');
$MONTH_NAME_SMALL = array(1 => 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

function getYearForLastMonth($currentYear, $currentMonth) {
	$currentYearMonth = $currentYear . "-" . $currentMonth . "-" . "01";
	$lastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "-1 month"));
	$lastYearMonthArray = explode("-", $lastYearMonth);
	$lastYear = $lastYearMonthArray[0];
	return $lastYear;
}

function getLastMonth($currentYear, $currentMonth) {
	$currentYearMonth = $currentYear . "-" . $currentMonth . "-" . "01";
	$lastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "-1 month"));
	$lastYearMonthArray = explode("-", $lastYearMonth);
	$lastMonth = $lastYearMonthArray[1];
	return $lastMonth;
}

function getYearForLast2Month($currentYear, $currentMonth) {
	$currentYearMonth = $currentYear . "-" . $currentMonth . "-" . "01";
	$beforeLastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "-2 month"));
	$beforeLastYearMonthArray = explode("-", $beforeLastYearMonth);
	$beforeLastYear = $beforeLastYearMonthArray[0];
	return $beforeLastYear;
}

function getBeforeLastMonth($currentYear, $currentMonth) {
	$currentYearMonth = $currentYear . "-" . $currentMonth . "-" . "01";
	$beforeLastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "-2 month"));
	$beforeLastYearMonthArray = explode("-", $beforeLastYearMonth);
	$beforeLastMonth = $beforeLastYearMonthArray[1];
	return $beforeLastMonth;
}

function getYearFor12MonthsAgo($currentYear, $currentMonth) {
	$currentYearMonth = $currentYear . "-" . $currentMonth . "-" . "01";
	$beforeLastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "-6 month"));
	$beforeLastYearMonthArray = explode("-", $beforeLastYearMonth);
	$beforeLastYear = $beforeLastYearMonthArray[0];
	return $beforeLastYear;
}

function getMonthFor12MonthsAgo($currentYear, $currentMonth) {
	$currentYearMonth = $currentYear . "-" . $currentMonth . "-" . "01";
	$beforeLastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "-6 month"));
	$beforeLastYearMonthArray = explode("-", $beforeLastYearMonth);
	$beforeLastMonth = $beforeLastYearMonthArray[1];
	return $beforeLastMonth;
}

function getYearForLastMonthByFreq($currentYear, $currentMonth, $frequencyId) {
	if ($frequencyId == 1) {
		$currentYearMonth = $currentYear . "-" . $currentMonth . "-" . "01";
		$lastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "-1 month"));
		$lastYearMonthArray = explode("-", $lastYearMonth);
		$lastYear = $lastYearMonthArray[0];
		return $lastYear;
	} else if ($frequencyId == 2) {
		$currentYearMonth = $currentYear . "-" . $currentMonth . "-" . "01";
		$lastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "-3 month"));
		$lastYearMonthArray = explode("-", $lastYearMonth);
		$lastYear = $lastYearMonthArray[0];
		return $lastYear;
	}
}

function getLastMonthByFreq($currentYear, $currentMonth, $frequencyId) {
	if ($frequencyId == 1) {
		$currentYearMonth = $currentYear . "-" . $currentMonth . "-" . "01";
		$lastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "-1 month"));
		$lastYearMonthArray = explode("-", $lastYearMonth);
		$lastMonth = $lastYearMonthArray[1];
		return $lastMonth;
	} else if ($frequencyId == 2) {
		$currentYearMonth = $currentYear . "-" . $currentMonth . "-" . "01";
		$lastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "-3 month"));
		$lastYearMonthArray = explode("-", $lastYearMonth);
		$lastMonth = $lastYearMonthArray[1];
		return $lastMonth;
	}
}

function getYearForLast2MonthByFreq($currentYear, $currentMonth) {
	if ($frequencyId == 1) {
		$currentYearMonth = $currentYear . "-" . $currentMonth . "-" . "01";
		$beforeLastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "-2 month"));
		$beforeLastYearMonthArray = explode("-", $beforeLastYearMonth);
		$beforeLastYear = $beforeLastYearMonthArray[0];
		return $beforeLastYear;
	} else if ($frequencyId == 2) {
		$currentYearMonth = $currentYear . "-" . $currentMonth . "-" . "01";
		$beforeLastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "-6 month"));
		$beforeLastYearMonthArray = explode("-", $beforeLastYearMonth);
		$beforeLastYear = $beforeLastYearMonthArray[0];
		return $beforeLastYear;
	}
}

function getBeforeLastMonthByFreq($currentYear, $currentMonth) {
	if ($frequencyId == 1) {
		$currentYearMonth = $currentYear . "-" . $currentMonth . "-" . "01";
		$beforeLastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "-2 month"));
		$beforeLastYearMonthArray = explode("-", $beforeLastYearMonth);
		$beforeLastMonth = $beforeLastYearMonthArray[1];
		return $beforeLastMonth;
	} else if ($frequencyId == 2) {
		$currentYearMonth = $currentYear . "-" . $currentMonth . "-" . "01";
		$beforeLastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "-6 month"));
		$beforeLastYearMonthArray = explode("-", $beforeLastYearMonth);
		$beforeLastMonth = $beforeLastYearMonthArray[1];
		return $beforeLastMonth;
	}
}
