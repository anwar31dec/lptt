<?php 
$user = JFactory::getUser();
$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
$jBaseUrl = JURI::base();
?>
<script> 
    var baseUrl = '<?php echo $baseUrl; ?>'; 
    var USERNAME = '<?php echo $user; ?>';
</script>

<link href="<?php echo $baseUrl;?>lib/handsontable/dist/jquery.handsontable.full.css" rel="stylesheet"/>
<script src="<?php echo $baseUrl;?>lib/handsontable/dist/jquery.handsontable.full.js" type="text/javascript"></script>

<div class="page-title">
	<h3 class="no-margin">Quarterly Patient And Stock Status</h3></br>
	<button class="btn btn-info" type="button" id="PrintBTN" onclick="printfunction()" > Print </button>
</div>

<br />

<div class="row">
    <div class="col-lg-7">
        <div class="panel panel-default">
        	<div class="panel-body">
                <div class="clearfix">
               	    <div id="month-year-block">
                        <center>
        					<table id="month-year">
        						<tr>
        							<td width="" align="right" valign="middle">
        							<button id="left-arrow" type="button" class="btn btn-info">
        								<span class="glyphicon icon-arrow-left">
        							</button></span></td>
        							<td>&nbsp;</td><td align="left" valign="middle"><select id="month-list" class="form-control"></select></td>
        							<td>&nbsp;</td><td align="left" valign="middle"><select id="year-list" class="form-control"></select></td>
        							<td>&nbsp;</td><td width=""  align="left"  valign="middle">
        							<button id="right-arrow" type="button" class="btn btn-info">
        								<span class="glyphicon icon-arrow-right"></span>
        							</button></td>
                                </tr>
        					</table>
                        </center>
        	       </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
    	<div class="panel panel-default">
    		<div class="panel-body">
    			<table id="month-year1" class="pull-right">
    				<tbody>
    					<tr>
    					   <td>&nbsp;</td><td>Select Country:&nbsp;</td><td align="left" valign="middle">
                                <select class="form-control" name="CountryId" id="CountryId"></select>
                           </td>    						
    					</tr>
    				</tbody>
    			</table>
    		</div>
    	</div>
    </div>
</div>

<div class="col-lg-13">
	<div class="panel panel-default">
		<div class="panel-heading clearfix">
			Quaterly Patient And Stock Status
		</div>
		<div class="panel-body" id="stock-div">
            <center>
                <div id="mainTab">
    				<ul class="pagination pagination-sm">
    					<li class="active" style="padding: 10px;"><a onclick="patientClick()"> Patient</a></li>
    					<li><a onclick="stockClick()"> Stock</a></li>				
    				</ul>
    			</div>
            </center>
            <br />
                <div id="mainTab_content">
            		<div id="patients">
                		<div class="panel-tab clearfix" id="childTab1">
                             <ul class="tab-bar">
                				<li class="active"><a href="#overview" data-toggle="tab"><i class="fa fa-edit"></i> Patient Overview</a></li>
                                <li><a href="#adult_Regimen" data-toggle="tab"><i class="fa fa-edit"></i> Patient by Regimen</a></li>
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content" id="childTab1_content">
                                <div class="tab-pane fade in active" id="overview">				                     
                                    <div id="overview_page"></div>
                                </div>
                                <div class="tab-pane fade" id="adult_Regimen">
                                    <div id="adult_page"></div>
                                </div>                    
                            </div>
                	   </div>
            	   </div>
                   <div id="stock">				                     
                        <div id="art_page"></div>
                   </div>
            </div>
	   </div>
    </div>
</div>

<style type="text/css">
	.SL, .Action {
		text-align: center !important;
	}
    .panel-heading {
        background-color: #EDF5FA !important;
        background-image: -moz-linear-gradient(center top , #FDFEFE, #EDF5FA) !important;
        border-bottom: 1px solid #C3DDEC !important;
        color: #3784B1 !important;
    }
    .pagination-sm > li > a{
        padding: 7px 75px;
        cursor: pointer;
    }
    #mainTab{
        margin-top:-25px;
    }
</style>

<script>
function printfunction()
{
	var baseUrl = '<?php echo $jBaseUrl; ?>'; 
	window.open("http://softworks02/warp/administrator/components/com_jcode/source/report/printProcessing.php?baseUrl="+baseUrl+"&action=getCountryName");			 
 
} 
		
 </script>

<link href="<?php echo $baseUrl;?>css/custom.css" rel="stylesheet"/>
<link href="<?php echo $jBaseUrl;?>/templates/protostar/endless/css/jquery.dataTables_themeroller.css" rel="stylesheet"/>
<link href="<?php echo $jBaseUrl;?>/templates/protostar/endless/css/endless.min.css" rel="stylesheet"/>

<script src="<?php echo $jBaseUrl;?>/templates/protostar/endless/js/parsley.min.js"></script>
<script src="<?php echo $jBaseUrl;?>/templates/protostar/endless/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $baseUrl;?>t_monthlystatus_entry.js" type="text/javascript"></script>








