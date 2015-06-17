<?php
include 'dbconn.php';

$time_zone = 'Server Default Time Zone: ' . date_default_timezone_get() . '<br/>';

$default_time = 'Server Default Time: ' . date('Y-m-d H:i:s') . '<br/>';

$default_12hr_time = 'Server Default 12hr Time: ' . date('Y-m-d_h:i:s_A', strtotime(date("Y-m-d H:i:s"))) . '<br/>';

date_default_timezone_set('Asia/Dhaka');

$date_time = 'Asia/Dhaka 12hr time: ' . date('Y-m-d_h:i:s_A', strtotime(date("Y-m-d H:i:s"))) . '<br/>';

$outTable = $time_zone . $default_time . $default_12hr_time. $date_time;

$sql = "SELECT 100 Col1, 200 Col2, 300 Col3 
		UNION
		SELECT 101 Col1, 201 Col2, 301 Col3
		UNION
		SELECT 102 Col1, 202 Col2, 302 Col3
		UNION
		SELECT 103 Col1, 203 Col2, 303 Col3";
$result = mysql_query($sql);
if ($result)
	$outTable .= 'Database connected.' . '<br/>';

$outTable .= '<table>';
while ($row = mysql_fetch_array($result)) {
	$outTable .= '<tr><td>' . $row['Col1'] . '</td><td>' . $row['Col2'] . '</td><td>' . $row['Col3'] . '</td><td>';
}

//echo $outTable;

//echo date('Y-m-d H:i:s');
//echo date_default_timezone_get();
$outTable .= '</table>';
$outTable .= 'Real Path: ' . __FILE__ . '<br/>';
$outTable .= 'URL: ' . 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"] . '<br/>';

$to = 'anwarcs36@yahoo.com';

// subject
$subject = 'Cron Job testing from cpanel';

// message
$message = $outTable;
//echo $message;
//exit;
//"Hi this is testing email from cron job";

// To send HTML mail, the Content-type header must be set
//$headers = 'From: admin@dgfplmis.org' . "\r\n" .
//'Reply-To: admin@dgfplmis.org' . "\r\n" .
//'X-Mailer: PHP/' . phpversion();
$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'From: admin@ospsante.org' . "\r\n";

// Mail it
mail($to, $subject, $message, $headers);
?>