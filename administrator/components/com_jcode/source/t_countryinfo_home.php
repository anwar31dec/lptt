<?php
$user = JFactory::getUser();
$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
$jBaseUrl = JURI::base();
$lang = JFactory::getLanguage();
$lan = $lang->getTag();
if($user->id>0){
?>

<?php
include_once('database_conn.php');

 global $gTEXT;
	$groupdataArr = array();
	 
	 if($lan == 'en-GB'){
            $GroupName = 'GroupName';
        }else{
            $GroupName = 'GroupNameFrench';
        }  
	$query = "SELECT $GroupName GroupName FROM  t_itemgroup ORDER BY ItemGroupId "; 
	 
	$result = mysql_query($query);
	$total = mysql_num_rows($result);
	if($total>0){ 
		while($row = mysql_fetch_array( $result )) {
			$groupdataArr[] = $row['GroupName'];
		}
	}
	 
	 
?>
<script>
    var baseUrl = '<?php echo $baseUrl; ?>'; 
    var lan = '<?php echo $lan;?>';
</script>

<?php
	include_once ('init_month_year.php');
	include_once ('language/lang_en.php');
	include_once ('language/lang_fr.php');
	include_once ('language/lang_switcher.php');
?>

<div class="panel"  style="overflow:hidden;">
	<div class="panel-heading">
	 <?php echo $TEXT['Profile of Focus Countries']; ?>	
	</div>
	
	<div class="tabbable"> 
	
		<ul class="nav nav-tabs">
		<li class="active"><a href="#tab1" onclick="getProfileParams(3)" data-toggle="tab">
		<img src="<?php echo $baseUrl; ?>/flag/GIN_flag.png" alt="Mountain View"  height="25" width="40">
		<?php echo $TEXT['Guinea']; ?>
		</a></li>
		
		<li><a href="#tab2" onclick="getProfileParams(1)" data-toggle="tab">
		<img src="<?php echo $baseUrl; ?>/flag/MLI_flag.png" alt="Mountain View"  height="25" width="40">
		<?php echo $TEXT['Mali']; ?>
		</a></li>
		<li><a href="#tab3" onclick="getProfileParams(2)" data-toggle="tab">
		<img src="<?php echo $baseUrl; ?>/flag/SSD_flag.png" alt="Mountain View"  height="25" width="40">
		<?php echo $TEXT['South Sudan']; ?>
		</a></li>
		
		</ul>
		<!--
		<div class="tab-content">
		
			<div class="tab-pane active" id="tab1" style="overflow:hidden;">
			<p>
			<?php 
			$_GET['CountryId'] = '3';
			include 't_countryinfo_home_serverdata.php';
			?>
			
			</p>
			</div>
			
			<div class="tab-pane" id="tab2" style="overflow:hidden;">
			<p>
			<?php 
			$_GET['CountryId'] = '1';
			include 't_countryinfo_home_serverdata.php';
			?>
			
			</p>
			</div>
			
			<div class="tab-pane" id="tab3" style="overflow:hidden;">
			<p>
			<?php 
			$_GET['CountryId'] = '2';
			include 't_countryinfo_home_serverdata.php';
			?>
			
			</p>
			</div>
		
		</div>
		-->
    </div>

	
<div class="col-lg-4 col-md-4 col-xs-12" style="padding-right:0">	
	<div class="panel-body">
		
		<!--<div class="panel-heading" style="border-bottom: 1px solid #000000; padding-bottom:10px">-->
		<div class="panel-heading">
           <?php echo $groupdataArr[0]; ?>
        </div>	
		
		<div class="clearfix list-panel" >
			<table class="table table-hover table-striped" id="tbl-country-profile">
				<thead>
					<tr>
						<th>SL</th>
						<th><?php echo $TEXT['Parameter']; ?></th>
						<th><?php echo $TEXT['Value']; ?></th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
</div>
<div class="col-lg-4 col-md-4 col-xs-12" style="padding-right:0">		
	<div class="panel-body">		
		<div class="panel-heading">
           <?php echo $groupdataArr[1]; ?>
        </div>	
		<div class="clearfix list-panel" >
			<table class="table table-hover table-striped" id="tbl-country-profile1">
				<thead>
					<tr>
						<th>SL</th>
						<th><?php echo $TEXT['Parameter']; ?></th>
						<th><?php echo $TEXT['Value']; ?></th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
</div>
<div class="col-lg-4 col-md-4 col-xs-12" style="padding-right:0">		
	<div class="panel-body">		
		<div class="panel-heading">
          <?php echo $groupdataArr[2]; ?>
        </div>
		<div class="clearfix list-panel" >
			<table class="table table-hover table-striped" id="tbl-country-profile2">
				<thead>
					<tr>
						<th>SL</th>
						<th><?php echo $TEXT['Parameter']; ?></th>
						<th><?php echo $TEXT['Value']; ?></th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
</div>
	
	
</div>
<style>
.right-aln{
	text-align:right !important;
}
</style>
<?php } ?>
<!-- Currently Load from Template index.php-->
<!--
<link href="<?php echo $jBaseUrl; ?>/templates/protostar/endless/css/jquery.dataTables_themeroller.css" rel="stylesheet"/>
<script src='<?php echo $jBaseUrl; ?>templates/protostar/endless/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>t_countryinfo_home.js'></script>
-->