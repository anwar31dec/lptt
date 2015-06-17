<?php
/**
 * @package Cron Script for DB-Backup of https://ospsante.org
 * @version 1.0.1
 */
date_default_timezone_set('Asia/Dhaka');
//===============================Joomla===========================================
$dbName = 'newospsa_db';
$dbHost = 'localhost'; // Database Host
$dbUser = 'newospsa_admin'; // Database Username
$dbPass = '@JnLa?8yMXkU'; // Database Password

$file1=$dbName.'_'.date('Y-m-d_H:i:s').'.sql.zip';
$dbFile = '/home/newospsante/ospsante_db_backup/'.$file1;

exec("mysqldump -u ".$dbUser." '-p".$dbPass."' ".$dbName." | gzip > ".$dbFile);
//================================End=============================================

$to = 'anwarcs36@yahoo.com';

// subject
$subject = '.: OSPSANTE DB Backup taken :..: '.date('l jS \of F Y h:i A').' :.';

// message
$message = 'Cron is successfully generated.<br /><br />';

// To send HTML mail, the Content-type header must be set
$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
// Additional headers
$headers .= 'From: ospsante.org Admin <admin@ospsante.org>' . "\r\n";

// Mail it
mail($to, $subject, $message, $headers);

echo 'Thanks, Successfully DB backup taken.';
?>