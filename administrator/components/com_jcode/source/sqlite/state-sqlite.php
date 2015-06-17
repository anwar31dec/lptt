<?php
error_reporting(E_ERROR);
// require_once('fb.php');
// vim: ts=4:sw=4:nu:fdc=4

// get posted values
//$user2 = $user->username;
//$user = $_COOKIE['auth_userID'];

$cmd = isset($_POST["cmd"]) ? $_POST["cmd"] : false;
$clientArgs->id = isset($_POST["id"]) ? $_POST["id"] : 1;
$clientArgs->user = isset($_POST["user"]) ? $_POST["user"] : "anwar369";
//$clientArgs->user = $user2;
$clientArgs->session = isset($_POST["session"]) ? $_POST["session"] : "session";
$clientArgs->data = isset($_POST["data"]) ? $_POST["data"] : array();
//fb("state:". $_POST["cmd"]);
// get variables and connection to sqlite
$stateFile = "state.sqlite";
//$DSN="sqlite:" . realpath(".") . "/$stateFile";
$DSN = 'sqlite:' . realpath(".") . '/state.sqlite';

//echo $DSN;

//$DSN = 'sqlite:http://69.39.236.234/~ospsante/administrator/components/com_jcode/source/sqlite/state.sqlite';

//echo $DSN;

$odb = new PDO("$DSN");
$odb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

createTable($odb);

if(!$cmd) {
	echo '{"success":false,"error":"No command"}';
	exit;
}

// execute command
$cmd($odb, $clientArgs);
exit;

// {{{
/**
  * readState: reads state
  *
  * @author    Ing. Jozef Sakáloš <jsakalos@aariadne.com>
  * @date      24. March 2008
  * @return    void
  * @param     PDO $odb
  * @param     object $clientArgs
  */
function readState($odb, $clientArgs) {
	$sql = 
		 "select name,value from state where "
		."id={$clientArgs->id} and user='{$clientArgs->user}' and session='{$clientArgs->session}'"
	;
	try {
		$ostmt = $odb->query($sql);
		$data = $ostmt->fetchAll(PDO::FETCH_OBJ);
	}
	catch(PDOException $e) {
		echo "{\"success\":false,\"error\":\"$e\"}";
		exit;
	}

	$o = array(
		 "success"=>true
		,"data"=>json_encode($data)
	);
	echo json_encode($o);
} // eo function readState
// }}}
// {{{
/**
  * saveState: saves state
  *
  * @author    Ing. Jozef Sakáloš <jsakalos@aariadne.com>
  * @date      24. March 2008
  * @return    void
  * @param     PDO $odb
  * @param     object $clientArgs
  */
function saveState($odb, $clientArgs) {
	foreach($clientArgs->data as $row) {
		$sql = "replace into state (id,user,session,name,value) values"
			." ({$clientArgs->id},'{$clientArgs->user}','{$clientArgs->session}','{$row['name']}','{$row['value']}')";
		//echo $sql;
		
		try {
			$odb->exec($sql);
		}
		catch(PDOException $e) {
			echo "{\"success\":false,\"error\":\"$e\"}";
			exit;
		}
		
		//print_r($row);
		//echo $row['name'];
	}
	echo '{"success":true}';
} // eo function saveState
// }}}
// {{{
/**
  * createTable: create state table if it doesn't exist
  *
  * @author    Ing. Jozef Sakáloš <jsakalos@aariadne.com>
  * @date      24. March 2008
  * @param     PDO $odb
  * @return    void
  */
function createTable($odb) {
	// check if table exists
	$ostmt = $odb->query("select name from sqlite_master where type='table' and name='state'");
	$table = $ostmt->fetchAll(PDO::FETCH_NUM);
	if(!sizeof($table)) {
		// create table
		$sql = 
			 "create table state"
			."(id integer"
			.",user varchar(40)"
			.",session varchar(80)"
			.",name varchar(80)"
			.",value text"
			.")"
		;
		$odb->exec($sql);

		// create unique index
		$sql = "create unique index idx on state(id,user,session,name)";
		$odb->exec($sql);
	} 
} // eo function createTable
// }}}

// eof
?> 
