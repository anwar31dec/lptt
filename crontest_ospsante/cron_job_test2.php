<?php
include 'dbconn.php';

$sql = "SELECT *  FROM Student";
$result = mysql_query($sql);

$outTable = '<table>';
while ($row = mysql_fetch_array($result)) {
	$outTable .= '<tr><td>' . $row['Name'] . '</td><td>';
}

$outTable .= 'http://areacalculator.comuv.com/crontest_areacal/cron_job_test.php<br/>';
$outTable .= '</table>';

//$to = 'spsbdhelp@msh.org, anwarcs36@yahoo.com, barman@yahoo.com';
$to = 'anwarcs36@yahoo.com';

// subject
$subject = 'Cron Job 2 testing from cpanel (itemlist_master from database)';

// message
$message = $outTable;
//"Hi this is testing email from cron job";

// To send HTML mail, the Content-type header must be set
//$headers = 'From: admin@dgfplmis.org' . "\r\n" .
//'Reply-To: admin@dgfplmis.org' . "\r\n" .
//'X-Mailer: PHP/' . phpversion();
$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'From: admin@areacalculator.comuv.com' . "\r\n";

// Mail it
mail($to, $subject, $message, $headers);
?>