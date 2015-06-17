<?php

error_reporting(E_ERROR);
// vim: ts=4:sw=4:nu:fdc=4
$user2 = $user->username;
//setcookie("user", $user, time() + 365 * 24 * 3600);

//$user = $_COOKIE['auth_userID'];

//$clientArgs = new StdClass();

// get posted values
$clientArgs->id = isset($_POST["id"]) ? $_POST["id"] : 1;
$clientArgs->user = isset($_POST["user"]) ? $_POST["user"] : $user2;
//$clientArgs->user = isset($user) ? $user : $user;
$clientArgs->session = isset($_POST["session"]) ? $_POST["session"] : "session";

// get variables and connection to sqlite
$stateFile = "state.sqlite";
//$DSN="sqlite:" . realpath(".") . "/$stateFile";
$DSN = 'sqlite:' . realpath(".") . '/administrator/components/com_jcode/source/sqlite/state.sqlite';

//echo $DSN;

$odb = new PDO("$DSN");
// echo "id: ". $clientArgs->id;
$sql = 
	 "select name,value from state where "
	."id={$clientArgs->id} and user='{$clientArgs->user}' and session='{$clientArgs->session}'"
;

//echo "sql: ". $sql;

$ostmt = $odb->query($sql);
$state = $ostmt->fetchAll(PDO::FETCH_OBJ);

$outputState = array();

foreach ($state as $row) {	
	$outputState = array_merge($outputState, array($row->name => ($row->value)));	
}

//echo "sql: ". $state;

?>
<script type="text/javascript">
	var hProvider = new HttpProvider({
		 url: '<?php echo $baseUrl;?>sqlite/state-sqlite.php?#state'
		,user:'<?php echo $clientArgs->user ?>'
		,session:'<?php echo $clientArgs->session?>'
		,id:'<?php echo $clientArgs->id?>'
		,readBaseParams:{cmd:'readState'}
		,saveBaseParams:{cmd:'saveState'}
		,autoRead:false
	//	,logFailure:true
	//	,logSuccess:true
	});	
	
	var ghp = <?php echo json_encode($outputState); ?>;

</script>
<?php
// eof
?>
