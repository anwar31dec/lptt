<?php

include_once ('language/lang_en_msg.php');
include_once ('language/lang_fr_msg.php');

mysql_query('SET CHARACTER SET utf8');
/* safe_query function to run query */

function safe_query($query = "") {
    if (empty($query)) {
        return false;
    }

    $result = mysql_query($query) or die("Query Fails:"
                    . "<li> Errno = " . mysql_errno()
                    . "<li> ErrDetails = " . mysql_error()
                    . "<li>Query = " . $query);
    return $result;
}

function crnl2br($string) {
    $patterns = array('/\r/', '/\t/', '/\n/');
    $replace = array('', ' ', ' ');
    return preg_replace($patterns, $replace, $string);
}

/**
 *
 * Execute N number of transactional queries (INSERT, UPDATE, DELETE)
 *
 * Author: Anwar Hossain
 * Date: 2014-11-23
 * Email: anwarcs36@yahoo.com
 * Version 1.1
 *
 * @param   array  $aQuerys   Contain N number queries
 * @param   string  $jUserId  Joomla user id / any user id
 * @param   string  $sLang  Use language for showing message
 * @param   bool  $bSqlLog  Is save the log old values and new values to the t_sqllog table?
 * @param   bool  $bTableLog  Is save the log old values and new values to the audit_t_tablename table?
 * @param   bool  $bRetData  Is return the data which is Insert/update/delete successfully?
 * @param   bool  $bEcho  Is return echo for the developer (not implemented now)
 *
 * @return  array  Php array (success/error message, Last edited row )
 *
 */

function exec_query($aQuerys, $jUserId = 'Admin', $sLang = 'en-GB', $bSqlLog = TRUE, $bTableLog = FALSE, $bRetData = FALSE, $bEcho = FALSE) {
	
	global $TEXT_EN_MSG, $TEXT_FR_MSG;	
	
	switch ($sLang) {
		case 'en-GB' :
			$TEXTMSG = $TEXT_EN_MSG;
			break;
		case 'fr-FR' :
			$TEXTMSG = $TEXT_FR_MSG;
			break;
	}

	try {
		$bResult = TRUE;
		$lastInsertedId = 0;
		$msg = array();
		$errors = array();
		$errorNos = array();
		$command = '';
		$sucMsg = '';
		$aData = array();
		$strAuditInsSql = '';
		mysql_query('SET autocommit = 0;');
		mysql_query('START TRANSACTION;');

		foreach ($aQuerys as $akey => $aQuery) {
			$command = strtoupper($aQuery['command']);

			$sTable = strtolower($aQuery['sTable']);
			
			$pkValue = array_key_exists(0, $aQuery['pk_values'])? $aQuery['pk_values'][0] : '';

			$whClause = '';
			$sqlOld = '';
			$sqlNew = '';
			$fieldNames = array();
			$oldValues = array();
			$newValues = array();

			if ($command == 'INSERT')
				$sucMsg = $TEXTMSG['New Data Added Successfully'];
			else if ($command == 'UPDATE')
				$sucMsg = $TEXTMSG['Data Updated Successfully'];
			else if ($command == 'DELETE')
				$sucMsg = $TEXTMSG['Data Removed Successfully'];

			$arrLog = array();
			if ($pkValue != NULL)
				$whClause = getWhClause($aQuery['pks'], $aQuery['pk_values']);

			if ($command == 'UPDATE' || $command == 'DELETE') {
				$sqlOld = "SELECT * FROM $sTable WHERE " . $whClause . ";";

				//echo $sqlOld;

				$resultOld = mysql_query($sqlOld);

				while ($aRow = mysql_fetch_assoc($resultOld)) {
					$oldValues = array_values($aRow);
					//print_r($oldValues);
					if ($command == 'DELETE') {
						$fieldNames = array_keys($aRow);
						foreach ($fieldNames as $key => $fieldName) {
							$oldValue = $oldValues[$key];
							$arrLog[] = array($fieldName, $oldValue, '');
						}

						if ($bTableLog)
							$strAuditInsSql = getAuditTblInsSql($sTable, $fieldNames, $oldValues, $newValues, $jUserId, $command);
					}
				}
			}

			$query = str_replace("[LastInsertedId]", $lastInsertedId, $aQuery['query']);
			$result = mysql_query($query);

			$bResult &= $result;

			if ($result) {
				if ($pkValue == NULL) {
					$whClause = $aQuery['pks'][0] . " = " . mysql_insert_id();
					if ($aQuery['bUseInsetId']) {
						$lastInsertedId = mysql_insert_id();
					}
				} else {
					$lastInsertedId = $pkValue;
				}

				if ($command != 'DELETE') {
					$sqlNew = "SELECT * FROM $sTable WHERE " . $whClause . ";";
					
					$result = mysql_query($sqlNew);

					while ($aRow = mysql_fetch_assoc($result)) {
						$fieldNames = array_keys($aRow);
						//print_r($fieldNames);
						$newValues = array_values($aRow);
						foreach ($fieldNames as $key => $fieldName) {
							$oldValue = array_key_exists(0, $oldValues)? $oldValues[$key] : '';
							$newValue = array_key_exists(0, $newValues)? $newValues[$key] : '';
							if ($oldValue != $newValue)
								$arrLog[] = array($fieldName, $oldValue, $newValue);
						}
					}
					if ($bTableLog)
						$strAuditInsSql = getAuditTblInsSql($sTable, $fieldNames, $oldValues, $newValues, $jUserId, $command);
					
					if ($bRetData) {
						$rowData = array();
						if ($fieldNames != NULL) {
							foreach ($fieldNames as $key => $fieldName) {
								$rowData[] = $newValues[$key];
							}
							$aData[$sTable][] = $rowData;
						}
					}

				}
				if ($bSqlLog) {
					$sqlLog = "INSERT INTO  t_sqllog(`RemoteIP`,`UserName`,`QueryType`,`TableName`,`JsonText`,`LogDate`, `SqlText`) 
							VALUES('" . get_client_ip() . "','$jUserId','" . $command . "','$sTable','" . mysql_real_escape_string(json_encode($arrLog)) . "',NOW(), '" . mysql_real_escape_string($query) . "');";

					$result2 = mysql_query($sqlLog);
					if (!$result2)
						$query = $sqlLog;
					$bResult &= $result2;
				}

				if ($bTableLog) {
					$result3 = mysql_query($strAuditInsSql);
					if (!$result3)
						$query = $strAuditInsSql;
					$bResult &= $result3;
				}

				$msg[$aQuery['pks'][0]] = $lastInsertedId;
			}
			if (mysql_errno() > 0) {
				$errorNos[] = mysql_errno();
				$errors[] = mysql_error();
				$errCommands[] = $command;
				$errQueries[] = mysql_real_escape_string($query);
			}
		}

		if (!$bResult) {
			throw new Exception();
		}

		mysql_query('COMMIT;');

		mysql_query('SET autocommit = 1;');

		$msg['msgType'] = 'success';
		$msg['msg'] = $sucMsg;
		$msg['aaData'] = $aData;

		return $msg;
	} catch (Exception $e) {
		mysql_query('ROLLBACK;');
		$strErrors = getErrors($errorNos, $errors, $TEXTMSG);
		$msg['msgType'] = 'error';
		$msg['msg'] = $strErrors;
		$errlogSql = "INSERT INTO t_errorlog(UserName, RemoteIP, Query, QueryType, ErrorNo, ErrorMsg, LogDate )
		VALUES ( '" . $jUserId . "','" . get_client_ip() . "', '" . implode(" ", $errQueries) . "','" . implode(", ", $errCommands) . "','" . implode(",", $errorNos) . "','" . mysql_real_escape_string(implode(" | ", $errors)) . "',NOW())";

		mysql_query('SET autocommit = 1;');
		if (!mysql_query($errlogSql)) {
			$msg['msg'] = $strErrors . '<br />' . $TEXTMSG['. But Error Log Saved Fail'];
		}
		return $msg;
	}
}


function getErrors($errorNos, $errors, $TEXTMSG) {
	$eMsg = '';
	foreach ($errorNos as $key => $errorNo) {
		$error = $errors[$key];
		switch ($errorNo) {
			case '1062' :
				$errorStr = substr($error, strpos($error, 'Duplicate entry \'') + 17);

				$constValue = substr($errorStr, 0, strpos($errorStr, '\''));

				$errorStr1 = substr($error, strpos($error, 'for key \'') + 9);

				$constName = substr($errorStr1, 0, -1);

				if ($constName == 'PRIMARY')
					$eMsg .= "* $error<br />";
				else
					$eMsg .= '* ' . $TEXTMSG[$constName] . "'" . $constValue . "'<br />";

				break;
			case '1451' :
				$errorStr = substr($error, strpos($error, 'CONSTRAINT `') + 12);
				$constName = substr($errorStr, 0, strpos($errorStr, '`'));
				$eMsg .= '* ' . $TEXTMSG[$constName] . '<br />';
				break;
			case '1452' :
				$errorStr = substr($error, strpos($error, 'CONSTRAINT `') + 12);
				$constName = substr($errorStr, 0, strpos($errorStr, '`'));
				$eMsg .= '* ' . $TEXTMSG[$constName . '_1452'] . '<br />';
				break;
			case '1065' :
				$errorStr = substr($error, strpos($error, 'CONSTRAINT `') + 12);
				$constName = substr($errorStr, 0, strpos($errorStr, '`'));
				$eMsg .= '* Query was empty<br />';
				break;
			case '1054' :
				$errorStr = substr($error, strpos($error, 'CONSTRAINT `') + 12);
				$constName = substr($errorStr, 0, strpos($errorStr, '`'));
				$eMsg .= '* There is a unknown column in the query.<br />';
				break;
			case '1146' :
				$errorStr = substr($error, strpos($error, 'CONSTRAINT `') + 12);
				$constName = substr($errorStr, 0, strpos($errorStr, '`'));
				$eMsg .= '* There is a table doesn\'t exist.<br />';
				break;
			case '1064' :
				$errorStr = substr($error, strpos($error, 'CONSTRAINT `') + 12);
				$constName = substr($errorStr, 0, strpos($errorStr, '`'));
				$eMsg .= '* You have an error in your SQL syntax.<br />';
				break;
			default :
				$eMsg .= $TEXTMSG['* An error occur during database operation. Please try after some time'] . '<br />';
				break;
		}
	}
	return $eMsg;
}

function getWhClause($pks, $pk_values) {
	$strWhere = '';
	foreach ($pks as $key => $pk) {
		$strWhere .= $pk . ' = ' . $pk_values[$key] . ' AND ';
	}
	$strWhere = substr($strWhere, 0, -4);
	return $strWhere;
}

/**
 *
 * Create Audit log sql for Audit Table
 *
 * Author: Anwar Hossain
 * Date: 2014-12-21
 * Email: anwarcs36@yahoo.com
 * Version 1.1
 *
 * @param   array  $sTable   Name of the original table
 * @param   array  $fieldNames  All the field names of the table
 * @param   array  $oldValues  Old values of the table record
 * @param   array  $newValues  New values of the table record
 *
 * @return  string  Insert Sql query for Audit table
 *
 */
function getAuditTblInsSql($sTable, $fieldNames, $oldValues, $newValues, $jUserId, $command) {

	$oldFieldNames = prefix_array_values($fieldNames, 'Old_');
	$newFieldNames = prefix_array_values($fieldNames, 'New_');

	if (count($oldValues) == 0)
		$oldValues = array_map(function($value) {
			return '%Y9KO73H2VU7%_NULL';
		}, $newValues);

	if (count($newValues) == 0)
		$newValues = array_map(function($value) {
			return '%Y9KO73H2VU7%_NULL';
		}, $oldValues);

	$sQuery = "INSERT INTO audit_$sTable (LogDate, UserName, QueryType, RemoteIP, `" . str_replace(" , ", " ", implode("`, `", $oldFieldNames)) . "`, `" . str_replace(" , ", " ", implode("`, `", $newFieldNames)) . "`) VALUES (NOW(), '$jUserId','$command', '" . get_client_ip() . "', '" . str_replace(" , ", " ", implode("', '", $oldValues)) . "', '" . str_replace(" , ", " ", implode("', '", $newValues)) . "');";

	$sQuery = str_replace("'%Y9KO73H2VU7%_NULL'", "NULL", $sQuery);

	//echo $sQuery;

	return $sQuery;
}

/**
 *
 * Function which will prefix all the array values
 *
 * Author: Anwar Hossain
 * Date: 2014-12-21
 * Email: anwarcs36@yahoo.com
 * Version 1.1
 *
 * @param   array  $array   Array which is ready for prefixion
 * @param   string $prefix  String value witch is used to prefix
 *
 * @return  array  Array with prefixed string
 *
 */

function prefix_array_values($array, $prefix = '') {
	if (!is_array($array))
		return false;

	// prefix the values and respect the keys
	foreach ($array as $key => $value) {
		if (!is_string($value))
			continue;

		$array[$key] = $prefix . $value;
	}

	return $array;
}

function get_client_ip() {
	$ipaddress = '';
	if (getenv('HTTP_CLIENT_IP'))
		$ipaddress = getenv('HTTP_CLIENT_IP');
	else if (getenv('HTTP_X_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	else if (getenv('HTTP_X_FORWARDED'))
		$ipaddress = getenv('HTTP_X_FORWARDED');
	else if (getenv('HTTP_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_FORWARDED_FOR');
	else if (getenv('HTTP_FORWARDED'))
		$ipaddress = getenv('HTTP_FORWARDED');
	else if (getenv('REMOTE_ADDR'))
		$ipaddress = getenv('REMOTE_ADDR');
	else
		$ipaddress = 'UNKNOWN';
	return $ipaddress;
}

function convertToHoursMins($time, $format = '%d:%d') {
    settype($time, 'integer');
    if ($time < 1) {
        return;
    }
	$time = floor($time / 60);//Seconds to minutes
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
}

// function exec_query($aQuerys, $jUserId = 'Admin', $sLang = 'en-GB', $bSqlLog = TRUE, $bTableLog = FALSE, $bRetData = FALSE, $bEcho = FALSE) {
// 
    // include('language/lang_en_msg.php');
    // include('language/lang_fr_msg.php');
// 
    // switch ($sLang) {
        // case 'en-GB' :
            // $TEXTMSG = $TEXT_EN_MSG;
            // break;
        // case 'fr-FR' :
            // $TEXTMSG = $TEXT_FR_MSG;
    // }
// 
    // try {
        // $bResult = TRUE;
        // $lastInsertedId = 0;
        // $msg = array();
        // $errors = array();
        // $errorNos = array();
        // $whClause = '';
        // $command = '';
        // $sucMsg = '';
        // $aData = array();
        // $strAuditInsSql = '';
        // mysql_query('SET autocommit = 0;');
        // mysql_query('START TRANSACTION;');
// 
        // foreach ($aQuerys as $akey => $aQuery) {
            // $command = strtoupper($aQuery['command']);
// 
            // $sTable = strtolower($aQuery['sTable']);
            // $pkValue = $aQuery['pk_values'][0];
// 
            // if ($command == 'INSERT')
                // $sucMsg = $TEXTMSG['New Data Added Successfully'];
// 
            // else if ($command == 'UPDATE')
                // $sucMsg = $TEXTMSG['Data Updated Successfully'];
            // else if ($command == 'DELETE')
                // $sucMsg = $TEXTMSG['Data Removed Successfully'];
// 
            // $arrLog = array();
            // if ($pkValue != NULL)
                // $whClause = getWhClause($aQuery['pks'], $aQuery['pk_values']);
// 
            // if ($command == 'UPDATE' || $command == 'DELETE') {
                // $sqlOld = "SELECT * FROM $sTable WHERE " . $whClause . ";";
// 
                // //echo $sqlOld;
// 
                // $resultOld = mysql_query($sqlOld);
// 
                // while ($aRow = mysql_fetch_assoc($resultOld)) {
                    // $oldValues = array_values($aRow);
                    // if ($command == 'DELETE') {
                        // $fieldNames = array_keys($aRow);
                        // foreach ($fieldNames as $key => $fieldName) {
                            // $oldValue = $oldValues[$key];
                            // $arrLog[] = array($fieldName, $oldValue, '');
                        // }
// 
                        // if ($bTableLog)
                            // $strAuditInsSql = getAuditTblInsSql($sTable, $fieldNames, $oldValues, $newValues, $jUserId, $command);
                    // }
                // }
            // }
// 
            // $query = str_replace("[LastInsertedId]", $lastInsertedId, $aQuery['query']);
            // $result = mysql_query($query);
// 
            // $bResult &= $result;
            // if ($result) {
                // if ($pkValue == NULL) {
                    // if ($aQuery['bUseInsetId'])
                        // $lastInsertedId = mysql_insert_id();
                    // $whClause = $aQuery['pks'][0] . " = " . $lastInsertedId;
                // }
                // else
                    // $lastInsertedId = $pkValue;
// 
                // if ($command != 'DELETE') {
                    // $sqlNew = "SELECT * FROM $sTable WHERE " . $whClause . ";";
// 
                    // $result = mysql_query($sqlNew);
// 
                    // while ($aRow = mysql_fetch_assoc($result)) {
                        // $fieldNames = array_keys($aRow);
                        // $newValues = array_values($aRow);
                        // foreach ($fieldNames as $key => $fieldName) {
                            // $oldValue = $oldValues[$key];
                            // $newValue = $newValues[$key];
                            // if ($oldValue != $newValue)
                                // $arrLog[] = array($fieldName, $oldValue, $newValue);
                        // }
                    // }
                    // if ($bTableLog)
                        // $strAuditInsSql = getAuditTblInsSql($sTable, $fieldNames, $oldValues, $newValues, $jUserId, $command);
// 
                    // if ($bRetData) {
                        // $rowData = array();
                        // if ($fieldNames != NULL) {
                            // foreach ($fieldNames as $key => $fieldName) {
                                // $rowData[] = $newValues[$key];
                            // }
                            // $aData[$sTable][] = $rowData;
                        // }
                    // }
                // }
                // if ($bSqlLog) {
                    // $sqlLog = "INSERT INTO  t_sqllog(`RemoteIP`,`UserName`,`QueryType`,`TableName`,`JsonText`,`LogDate`, `SqlText`) 
							// VALUES('" . get_client_ip() . "','$jUserId','" . $command . "','$sTable','" . json_encode($arrLog) . "',NOW(), '" . mysql_real_escape_string($query) . "');";
// 
                    // $result2 = mysql_query($sqlLog);
                    // if (!$result2)
                        // $query = $sqlLog;
                    // $bResult &= $result2;
                // }
// 
                // if ($bTableLog) {
                    // $result3 = mysql_query($strAuditInsSql);
                    // if (!$result3)
                        // $query = $strAuditInsSql;
                    // $bResult &= $result3;
                // }
// 
                // $msg[$aQuery['pks'][0]] = $lastInsertedId;
            // }
            // if (mysql_errno() > 0) {
                // $errorNos[] = mysql_errno();
                // $errors[] = mysql_error();
                // $errCommands[] = $command;
                // $errQueries[] = mysql_real_escape_string($query);
            // }
        // }
// 
        // if (!$bResult) {
            // throw new Exception();
        // }
// 
        // mysql_query('COMMIT;');
// 
        // mysql_query('SET autocommit = 1;');
// 
        // $msg['msgType'] = 'success';
        // $msg['msg'] = $sucMsg;
        // $msg['aaData'] = $aData;
// 
        // return $msg;
    // } catch (Exception $e) {
        // mysql_query('ROLLBACK;');
        // $strErrors = getErrors($errorNos, $errors, $TEXTMSG);
        // $msg['msgType'] = 'error';
        // $msg['msg'] = $strErrors;
        // $errlogSql = "INSERT INTO t_errorlog(UserName, RemoteIP, Query, QueryType, ErrorNo, ErrorMsg, LogDate )
		// VALUES ( '" . $jUserId . "','" . get_client_ip() . "', '" . implode(" ", $errQueries) . "','" . implode(", ", $errCommands) . "','" . implode(",", $errorNos) . "','" . mysql_real_escape_string(implode(" | ", $errors)) . "',NOW())";
// 
        // mysql_query('SET autocommit = 1;');
        // if (!mysql_query($errlogSql)) {
            // $msg['msg'] = $strErrors . '<br />' . $TEXTMSG['. But Error Log Saved Fail'];
        // }
        // return $msg;
    // }
// }
// 
// function getErrors($errorNos, $errors, $TEXTMSG) {
    // $eMsg = '';
    // foreach ($errorNos as $key => $errorNo) {
        // $error = $errors[$key];
        // switch ($errorNo) {
            // case '1062' :
                // $errorStr = substr($error, strpos($error, 'Duplicate entry \'') + 17);
// 
                // $constValue = substr($errorStr, 0, strpos($errorStr, '\''));
// 
                // $errorStr1 = substr($error, strpos($error, 'for key \'') + 9);
// 
                // $constName = substr($errorStr1, 0, -1);
// 
                // if ($constName == 'PRIMARY')
                    // $eMsg .= "* $error<br />";
                // else
                    // $eMsg .= '* ' . $TEXTMSG[$constName] . "'" . $constValue . "'<br />";
// 
                // break;
            // case '1451' :
                // $errorStr = substr($error, strpos($error, 'CONSTRAINT `') + 12);
                // $constName = substr($errorStr, 0, strpos($errorStr, '`'));
                // $eMsg .= '* ' . $TEXTMSG[$constName] . '<br />';
                // //echo $constName;
                // break;
            // case '1452' :
                // $errorStr = substr($error, strpos($error, 'CONSTRAINT `') + 12);
                // $constName = substr($errorStr, 0, strpos($errorStr, '`'));
                // $eMsg .= '* ' . $TEXTMSG[$constName . '_1452'] . '<br />';
                // break;
            // case '1065' :
                // $errorStr = substr($error, strpos($error, 'CONSTRAINT `') + 12);
                // $constName = substr($errorStr, 0, strpos($errorStr, '`'));
                // $eMsg .= '* Query was empty<br />';
                // break;
            // case '1054' :
                // $errorStr = substr($error, strpos($error, 'CONSTRAINT `') + 12);
                // $constName = substr($errorStr, 0, strpos($errorStr, '`'));
                // $eMsg .= '* There is a unknown column in the query.<br />';
                // break;
            // case '1146' :
                // $errorStr = substr($error, strpos($error, 'CONSTRAINT `') + 12);
                // $constName = substr($errorStr, 0, strpos($errorStr, '`'));
                // $eMsg .= '* There is a table doesn\'t exist.<br />';
                // break;
            // case '1064' :
                // $errorStr = substr($error, strpos($error, 'CONSTRAINT `') + 12);
                // $constName = substr($errorStr, 0, strpos($errorStr, '`'));
                // $eMsg .= '* You have an error in your SQL syntax.<br />';
                // break;
            // default :
                // $eMsg .= $TEXTMSG['* An error occur during database operation. Please try after some time'] . '<br />';
                // break;
        // }
    // }
    // return $eMsg;
// }
// 
// function get_client_ip() {
    // $ipaddress = '';
    // if (getenv('HTTP_CLIENT_IP'))
        // $ipaddress = getenv('HTTP_CLIENT_IP');
    // else if (getenv('HTTP_X_FORWARDED_FOR'))
        // $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    // else if (getenv('HTTP_X_FORWARDED'))
        // $ipaddress = getenv('HTTP_X_FORWARDED');
    // else if (getenv('HTTP_FORWARDED_FOR'))
        // $ipaddress = getenv('HTTP_FORWARDED_FOR');
    // else if (getenv('HTTP_FORWARDED'))
        // $ipaddress = getenv('HTTP_FORWARDED');
    // else if (getenv('REMOTE_ADDR'))
        // $ipaddress = getenv('REMOTE_ADDR');
    // else
        // $ipaddress = 'UNKNOWN';
    // return $ipaddress;
// }
// 
// function getWhClause($pks, $pk_values) {
    // $strWhere = '';
    // foreach ($pks as $key => $pk) {
        // $strWhere .= $pk . ' = ' . $pk_values[$key] . ' AND ';
    // }
    // $strWhere = substr($strWhere, 0, -4);
    // return $strWhere;
// }
// 
// function getAuditTblInsSql($sTable, $fieldNames, $oldValues, $newValues, $jUserId, $command) {
// 
    // $oldFieldNames = prefix_array_values($fieldNames, 'Old_');
    // $newFieldNames = prefix_array_values($fieldNames, 'New_');
// 
    // if (!is_array($oldValues))
        // $oldValues = array_map(function($value) {
                    // return '%Y9KO73H2VU7%_NULL';
                // }, $newValues);
// 
    // if (!is_array($newValues))
        // $newValues = array_map(function($value) {
                    // return '%Y9KO73H2VU7%_NULL';
                // }, $oldValues);
// 
    // $sQuery = "INSERT INTO audit_$sTable (LogDate, UserName, QueryType, RemoteIP, `" . str_replace(" , ", " ", implode("`, `", $oldFieldNames))
            // . "`, `" . str_replace(" , ", " ", implode("`, `", $newFieldNames))
            // . "`) VALUES (NOW(), '$jUserId','$command', '" . get_client_ip() . "', '"
            // . str_replace(" , ", " ", implode("', '", $oldValues)) . "', '"
            // . str_replace(" , ", " ", implode("', '", $newValues)) . "');";
// 
    // $sQuery = str_replace("'%Y9KO73H2VU7%_NULL'", "NULL", $sQuery);
// 
    // //echo $sQuery;
// 
    // return $sQuery;
// }
// 
// function prefix_array_values($array, $prefix = '') {
    // if (!is_array($array))
        // return false;
// 
    // // prefix the values and respect the keys
    // foreach ($array as $key => $value) {
        // if (!is_string($value))
            // continue;
// 
        // $array[$key] = $prefix . $value;
    // }
// 
    // return $array;
// }

?>