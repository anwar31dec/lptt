<?php
$user = JFactory::getUser();

$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
$jBaseUrl = JURI::base();
$userName = $user->username; 
$lang = JFactory::getLanguage();
$lan = $lang->getTag();
?>

<?php 
	include_once ('database_conn.php');
	include_once ('init_month_year.php');
	include_once ('function_lib.php');
	include_once ('language/lang_en.php');
	include_once ('language/lang_fr.php');
	include_once ('language/lang_switcher.php');
	include_once ('combo_script.php');
?>
 
<script>
    var baseUrl = '<?php echo $baseUrl; ?>';
    var lan = '<?php echo $lan;?>';
    var jbaseUrl = '<?php echo $jBaseUrl; ?>';
    var vLang = '<?php echo $lan; ?>';
</script>

<?php
$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());
mysql_query('SET CHARACTER SET utf8');

function getMOSbox(){

	$user = JFactory::getUser();
	$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
	$jBaseUrl = JURI::base();
	$lang = JFactory::getLanguage();
	$lan = $lang->getTag();

 if($lan == 'en-GB'){
           $mosTypeName = 'MosTypeName';
		   $mos = 'MOS';
        }else{
            $mosTypeName = 'MosTypeNameFrench';
			$mos = 'MSD';
        }
    
    $sql = "SELECT $mosTypeName MosTypeName, MosLabel, ColorCode FROM t_mostype ORDER BY MosTypeId";
   	$result = mysql_query($sql);
	$total = mysql_num_rows($result);
    	
	$x = "<table><tr>";	
    $z = "</tr><tr>"; 
    $y = "</tr><tr>";   
	if($total>0){
		while($row=mysql_fetch_object($result)){
            $x.="<td><div style='width:100%;background-color:".$row->ColorCode.";'>&nbsp;</div></td>";
            $z.="<td>".$row->MosTypeName."</td>";
			$y.="<td> ".$mos.": ".$row->MosLabel."</td>";
		}                    
	}    
	$x = $x.$z.$y."</tr></table>";
    
	$x = str_replace("\n", '', $x);
	$x = str_replace("\r", '', $x);
    return $x;
}
?>


<div class="container">
	<div class="content_fullwidth lessmar">
		<div class="azp_col-md-12 one_full">
						
		<div class="row">
			<div class="col-md-12 col-sm-12 col-sx-12">
				<div class="panel panel-default">
					<div class="panel-body">
								<div class="row">
									<div class="col-md-12 col-sm-12 col-sx-12">	
										<center>									
											<table id="month-year">
												<tbody>
													<tr>
													<td><?php echo $TEXT['Country']; ?>&nbsp;:&nbsp;&nbsp;</td>
														<td>
															<select class="form-control" id="country-list">
															<?php echo user_all_test();?>
															</select>
														</td>
														<td>&nbsp;&nbsp;</td>
														
														<td><?php echo $TEXT['Product Group']; ?>&nbsp;:&nbsp;&nbsp;</td>
														<td>
														<select class="form-control" id="item-group"></select>
														</td>
														<td>&nbsp;&nbsp;</td>
														
														<td><?php echo $TEXT['Report By']; ?>&nbsp;:&nbsp;&nbsp;</td>
														<td>
														<select class="form-control" id="report-by"></select>
														</td>
													</tr>
												</tbody>
											</table>	
										</center>										
									</div>
								</div>
								<div class="clearfix"><br></div>
								<div class="row">
									<div class="col-md-12 col-sm-12 col-sx-12">	
										<center>									
											<table id="month-year">
												<tbody>
													<tr>
														<td valign="middle" align="right">
														<button class="btn btn-info" type="button" id="left-arrow"><span class="fa fa-arrow-left fntC"> </span></button></td>
														<td>&nbsp;&nbsp;</td><td valign="middle" align="left"><select class="form-control" id="month-list"></select></td>
														<td>&nbsp;&nbsp;</td><td valign="middle" align="left"><select class="form-control" id="year-list"></select></td>
														<td>&nbsp;&nbsp;</td><td width="" valign="middle" align="left">
														<button class="btn btn-info" type="button" id="right-arrow"><span class="fa fa-arrow-right fntC"></span></button></td>
													</tr>
												</tbody>
											</table>
										</center>										
									</div>
								</div>
								<div class="clearfix"><br></div>	
								<div class="row">
									<div class="col-md-12 col-sm-12 col-sx-12">
										<center>
											<table id="nav-country">
												<tbody>
													<tr>	 
														<td><b><?php echo $TEXT['Reporting Rate']; ?></b> &nbsp;&nbsp;</td>									
														<td><?php echo $TEXT['Total']; ?>:&nbsp;&nbsp;</td>
														<td><span class="badge badge-success" id="Total"></span></td>
														
														<td>&nbsp;&nbsp;</td>
														<td><?php echo $TEXT['Facility Level']; ?>:&nbsp;&nbsp;</td>
														<td><span class="badge badge-success" id="Facility"></span></td>
														
														 <td>&nbsp;&nbsp;</td>
														<td><?php echo $TEXT['District Level']; ?>:&nbsp;&nbsp;</td>
														<td><span class="badge badge-success" id="District"></span></td>
														
														 <td>&nbsp;&nbsp;</td>
														<td><?php echo $TEXT['Region Level']; ?>:&nbsp;&nbsp;</td>
														<td><span class="badge badge-success" id="Region"></span></td>
														
														 <td>&nbsp;&nbsp;</td>
														<td><?php echo $TEXT['ppm Central']; ?>:&nbsp;&nbsp;</td>
														<td><span class="badge badge-success" id="Central"></span></td>
													</tr>
												</tbody>
											</table>
										</center>
									</div>       
								</div>
							</div>
					</div>
				</div>
			</div>
		</div>
		
		
		<div class="clearfix"><br></div>			
			
		
<!-- Stock out block start -->
<div class="row">
	<div class="col-md-6 col-sm-6 col-sx-12">
		<div class="panel panel-default">	
			<div id="stockoutpiecharthead" class="panel-heading clearfix">
				<?php //echo $TEXT['ARV Product Stock Status']; ?>
				<a href="index.php/<?php echo $TEXT['CurrLangShort']; ?>/national-level-reports/national-inventory-control" title="See national inventory control" class="pull-right"> <i class="fa fa-external-link fa-lg"></i></a>
			</div>		
			<div class="panel-body">
					<div class="row" style="min-height: 247px; height:auto">
						<div class="col-md-6 col-sm-6 col-sx-12">
							<div id="stockout-pie-chart"></div>
							<div ><?php echo $TEXT['put cursor on pie segments to see different values']; ?></div>
						</div>
						<div class="col-md-6 col-sm-6 col-sx-12">
							<table id="product-stock-status">
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
				<!--<center>	
				<div class="panel-footer">
					<div id="barchartlegend" class="legend-80">
						<?php echo getMOSbox(); ?>
					</div>
				</div>	
			</center>	-->
			</div>
			<!-- <div class="panel-footer"></div> -->
		</div>
	
	<div class="col-md-6 col-sm-6 col-sx-12">
			<div class="panel panel-default">							
					<div class="panel-heading clearfix">
						<?php echo $TEXT['Focus Countries']; ?>
					</div>
					<div class="panel-body">
						<div id="map-inner"  style="min-height: 247px; height:auto">
							<div id="map"></div>
							<script type="x-jst" id="tooltip-template">
								<div id="tooltip-main">
								<div id="tooltip-country"><%= name %></div>
								</div>
							</script>
						</div>
					</div>
			</div>
	</div>
</div>

		<div class="clearfix"><br></div>			
			

<div class="row">
	<div class="col-md-6 col-sm-6 col-sx-12">
		<div class="panel panel-default">	
            <div class="panel-heading clearfix">
        		<a href="index.php/<?php echo $TEXT['CurrLangShort']; ?>/national-level-reports/national-stock-summary" title="See national stock summary" class="pull-right"> <i class="fa fa-external-link fa-lg"></i></a>
        	</div>       
			<div class="panel-body">
				<div  id="stock-bar-chart" style="position: relative;"></div>	
						
				<div class="panel-footer">
					<center>
						<div id="barchartlegend" class="legend-80">
							<?php echo getMOSbox(); ?>
						</div>
					</center>	
				</div>	
			</div>			
         </div>				
	</div>
	
	
	<div class="col-md-6 col-sm-6 col-sx-12">	
		<div id="wrap-line-chart">
			<div  id="patients-line-chart" style="height: 260px; position: relative;"></div>
		</div>
		
		<div id="list-panel" >
			<div class="panel-heading">
				<?php echo $TEXT['% of Health Facilities Stocked Out']; ?>
	        </div>
	        <div class="panel-body" style="height: 333px; overflow:auto;">
				<div id="stockoutpercenttable">				
				</div>	
			</div>	
		</div>	
		
	</div> 

	<!-- <div class="col-md-3 col-sm-3 col-sx-12">
		<div class="stat-panel">
			<div class="stat-cell valign-middle" style="background-color: #857198; color: #fff;">
				<span id="male-id" class="text-xlg1">0%</span>
				<img src="<?php echo $baseUrl; ?>images/male_0.png" width="50px"/>
				<img src="<?php echo $baseUrl; ?>images/female_0.png" width="50px"/>
				<span id="female-id" class="text-xlg1">0%</span>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-sm-6 col-sx-12">	
		<div id="wrap-line-chart">
			<div  id="patients-line-chart" style="height: 160px; position: relative;"></div>
		</div>					
	</div> -->
	
	
	
	
	
	
</div>

<div class="margin_top1"><br></div>
<div class="clearfix"><br></div>			
<div class="row">

<!--
	<div class="col-md-6 col-sm-6 col-sx-12" id="patientpie">
		<div class="panel panel-default">
			<div class="panel-heading clearfix">
				<?php echo $TEXT['Simple Malaria Cases']; ?>
				<a href="index.php/<?php echo $TEXT['CurrLangShort']; ?>/national-level-reports/patient-ratio" title="See patients details" class="pull-right"> <i class="fa fa-external-link fa-lg"></i></a>				
			</div>
			<div class="panel-body">
				<div id="patients-pie-col" class="col-md-6 col-sm-12 col-sx-12">
					<div id="patients-pie" style="height: 200px;"></div>
				</div>
				<div id="patients-pie-details-col" class="col-md-6 col-sm-12 col-sx-12">
					 <table id="current-patients" class="table table-hover table-striped">		
						
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div class="panel-footer"></div>
		</div>
	</div>
	-->
<div class="col-md-6 col-sm-6 col-sx-12" id="rty">

	<div class="row">
		<div class="col-md-6 col-sm-6 col-sx-12" id="rty">
		
			<!--<div class="panel-heading clearfix">
							<a href="index.php/<?php echo $TEXT['CurrLangShort']; ?>/reports/facility-service-indicators" title="See facility Service indicators" class="pull-right"> <i class="fa fa-external-link fa-lg"></i></a>
				</div>-->
			<div class="stat-panel">
				<!-- Danger background, vertically centered text -->
				<div class="stat-cell bg-danger valign-middle">
					<!-- Stat panel bg icon -->
					<i class="fa fa-user bg-icon"></i>
					<!-- Extra large text -->
					<span id="total-patients" class="text-xlg"> <!-- <span class="text-lg text-slim">$</span> --> 0</span>
					<br>
					<!-- Big text -->
					<span class="text-bg" id="totalcase"></span>
					<br>
					<!-- Small text -->
					<!-- <span class="text-sm"><a href="#">See details in your profile</a></span> -->
				</div>
				<!-- /.stat-cell -->
			</div>
					
		</div>
		
		<div class="col-md-6 col-sm-6 col-sx-12" id="simple-vs-severe-div">
			<div class="panel panel-default">
			<!--<div class="panel-heading clearfix">
							<a href="index.php/<?php echo $TEXT['CurrLangShort']; ?>/national-level-reports/patient-ratio" title="See national stock summary" class="pull-right"> <i class="fa fa-external-link fa-lg"></i></a>
				</div>-->
				<div id="simple-vs-severe-div" class="panel-body" style="padding:2px;">
					<table id="simple-vs-severe123">
						<tbody>
							<tr>
								<td>
								<p style="margin: 0 10px; color:#AC92C2; font-size:2.0em;" class="text-xlg1">
									0%
								</p></td>
								<td>
								<p style="text-align:right; margin: 0 10px;color:#57BFB8; font-size:2.0em;" class="text-xlg1">
									0%
								</p></td>
							</tr>
							<tr>
								<td width="50%">
								<div style="background-color: #AC92C2;height: 30px;border-radius: 5px 0 0 5px;">
									&nbsp;
								</div></td>
								<td width="50%">
								<div style="background-color: #57BFB8;height: 30px;border-radius: 0 5px 5px 0;">
									&nbsp;
								</div></td>
							</tr>
							<tr>
								<td>
								<p style="margin: 0 10px;font-size:1.1em;">
									<!-- Simple Malaria -->
								</p></td>
								<td>
								<p style="text-align:right; margin: 0 10px;font-size:1.1em;">
									<!-- Severe Malaria -->
								</p></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		
	</div>	
		
	<div class="row">
		<div class="col-md-12 col-sm-12 col-sx-12" id="case-chart">	
			<div class="panel panel-default">
				<div class="panel-body">				
					<div id="case-bar-chart"></div>				
				</div>	
			</div>				
		</div>		
	</div>	



</div>
	

<div class="col-md-6 col-sm-6 col-sx-12" id="severepie">
	<!--<div class="panel panel-default">
	
		<div class="panel-heading clearfix">
			<?php echo $TEXT['Severe Malaria Cases']; ?>
			<a href="index.php/<?php echo $TEXT['CurrLangShort']; ?>/national-level-reports/patient-ratio" title="See patients details" class="pull-right"> <i class="fa fa-external-link fa-lg"></i></a>				
		</div>
		<div class="panel-body" style="height:310px;">
			<div id="severe-pie-col" class="col-md-6 col-sm-12 col-sx-12">
				<div id="severe-pie" style="height: 250px;"></div>
			</div>
			<div id="severe-pie-details-col" class="col-md-6 col-sm-12 col-sx-12">
				<table id="severe-patients" class="table table-hover table-striped">					
					<tbody>
					</tbody>
				</table>
			</div>
		</div>				
		<div class="panel-footer">			
		</div>		
		
	</div>-->
	<div class="panel panel-default">
	
		<div class="panel-heading clearfix">
			<?php echo $TEXT['Simple Malaria Cases']; ?>
			<a href="index.php/<?php echo $TEXT['CurrLangShort']; ?>/national-level-reports/patient-ratio" title="See patients details" class="pull-right"> <i class="fa fa-external-link fa-lg"></i></a>				
		</div>
		<div class="panel-body" style="height:310px;">
			<div id="patients-pie-col" class="col-md-6 col-sm-12 col-sx-12">
				<div id="patients-pie" style="height: 250px;"></div>
			</div>
			<div id="patients-pie-details-col" class="col-md-6 col-sm-12 col-sx-12">
				<table id="current-patients" class="table table-hover table-striped">					
					<tbody>
					</tbody>
				</table>
			</div>
		</div>				
		<div class="panel-footer">			
		</div>		
		
	</div>
	
</div>
	
</div>
<!--<div class="row">
	<div class="col-md-6 col-sm-6 col-sx-12">	
		<div class="panel panel-default">
        <a href="index.php/<?php echo $TEXT['CurrLangShort']; ?>/national-level-reports/national-stock-summary" title="See national stock summary" class="pull-right"> <i class="fa fa-external-link fa-lg"></i></a>
	
			<div id="simple-vs-severe-div11" class="panel-body">
            <div id="wrap-stock-bar-chart1">
			<div  id="case-bar-chart" style="height: 300px; position: relative;"></div>
		</div>	
			</div>	
			</div>				
	</div>
	
</div>-->












			    
		</div>		
	</div>
</div>
<link href="<?php echo $baseUrl; ?>/css/custom.css" rel="stylesheet">
<link href="<?php echo $baseUrl; ?>dashboard/css/jquery.dataTables_themeroller.css" rel="stylesheet">


<script src="<?php echo $baseUrl; ?>lib/fnc-lib.js" type="text/javascript"></script>
<link href="<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>css/jquery.dataTables.custom.css" rel="stylesheet"/>
<script src='<?php echo $baseUrl; ?>media/datatable/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.min.js'></script>

<script type="text/javascript" src='<?php echo $baseUrl; ?>dashboard/js/rapheal.min.js' charset="UTF-8"></script>
<script src='<?php echo $baseUrl; ?>dashboard/js/d3.v3.min.js'></script>
<script src='<?php echo $baseUrl; ?>dashboard/js/topojson.v0.min.js'></script>
<script src='<?php echo $baseUrl; ?>dashboard/js/queue.min.js'></script>
<script src='<?php echo $baseUrl; ?>dashboard/js/underscore-min.js'></script>
<link href="<?php echo $baseUrl; ?>dashboard/css/dashboard.css" rel="stylesheet">
<script src='<?php echo $baseUrl; ?>/lib/fnc-lib.js'></script>
<script type="text/javascript" src='<?php echo $baseUrl; ?>dashboard_map.js' charset="UTF-8"></script>
<script type="text/javascript" src='<?php echo $baseUrl; ?>dashboard/js/dashboard.js' charset="UTF-8"></script>
<link type="text/javascript" href="<?php echo $baseUrl; ?>dashboard/css/morris.css" rel="stylesheet">
<script type="text/javascript" src='<?php echo $baseUrl; ?>dashboard/js/morris.min.js' charset="UTF-8"></script>
<script src='<?php echo $baseUrl; ?>media/highcharts/highcharts.js'></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>js/jquery.http.provider.js" charset="UTF-8"></script>
<?php require("sqlite/provider-sqlite.php")?>

<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_en.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_fr.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_switcher.js"></script>


<style type="text/css">
	

	#population-at-risk tbody > tr > td {
		vertical-align: text-top !important;
	}
	#population-at-risk thead {
		display: none;
	}

	#product-stock-status tbody > tr > td {
		vertical-align: text-top !important;
	}
	#product-stock-status thead {
		display: none;
	}

	#current-patients thead {
		display: none;
	}
	
	#severe-patients thead {
		display: none;
	}
	#severe-patients, #current-patients {
		font-size: 80%;
	}
	
	#panel-body norisk {
		background-color: #4DD4FD !important;
	}

	.no-border-hr {
		border-left-width: 0 !important;
		border-right-width: 0 !important;
	}

	.list-group li:first-child {
		border-top-width: 0 !important;
		margin-top: 10px;
	}

	.circle-num-rounded {
		border-radius: 50em;
		color: #ffffff;
		margin-right: 5px;
		padding: 3px 11px;
	}
	
	.circle-num-rounded2 {
	  border-radius: 50em;
	  color: #ffffff;
	  margin-right: 5px;
	  padding: 3px 9px;
	}

	table.dataTable tr.odd {
		background-color: #fff;
	}

	.odd {
		border-bottom: none;
		padding: 0;
	}

	.stat-panel {
		background: none repeat scroll 0 0 #fff;
		border-radius: 2px;
		display: table;
		margin-bottom: 22px;
		overflow: hidden;
		position: relative;
		table-layout: fixed !important;
		width: 100%;
	}

	.bg-danger, .bg-danger a, .bg-danger a:focus, .bg-danger:active, .bg-danger:focus, .bg-danger:hover {
		color: #fff;
	}

	.bg-danger {
		background: none repeat scroll 0 0 #a1a194 !important;
	}

	.valign-middle, .valign-middle td, .valign-middle th {
		vertical-align: middle !important;
	}

	.stat-cell {
		display: table-cell !important;
		overflow: hidden;
		padding: 20px;
		position: relative;
	}
	.stat-cell, .stat-row {
		float: none !important;
	}
	.bg-danger {
		background-color: #f2dede;
	}

	.fa-trophy:before {
		content: "";
	}
	[class*="bg-"] .bg-icon {
		color: rgba(0, 0, 0, 0.08);
	}
	.stat-cell .bg-icon {
		bottom: 0;
		font-size: 100px;
		height: 100px;
		line-height: 100px;
		position: absolute;
		right: 0;
		text-align: center;
		width: 120px;
	}
	.bg-danger * {
		border-color: #eb8073;
	}
	.fa {
		display: inline-block;
		font-family: FontAwesome;
		font-style: normal;
		font-weight: 400;
	}

	.text-xlg {
		font-size: 5em;
	}
	.text-xlg1 {
		font-size: 35px;
	}
	.stat-cell > * {
		position: relative;
	}

	.text-slim {
		font-weight: 300 !important;
	}
	.text-lg {
		font-size: 23px;
	}

	b, strong {
		font-weight: 700;
	}

	.text-bg {
		font-size: 17px;
	}
	.stat-cell > * {
		position: relative;
	}
	.text-sm {
		font-size: 12px;
	}
	.stat-cell > * {
		position: relative;
	}
	.bg-danger, .bg-danger a, .bg-danger a:focus, .bg-danger:active, .bg-danger:focus, .bg-danger:hover {
		color: #fff;
	}
	a, a:hover {
		text-decoration: none;
	}
	a {
		background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
	}


</style>
