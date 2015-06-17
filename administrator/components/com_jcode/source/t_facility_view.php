<?php
$user = JFactory::getUser();
$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
$jBaseUrl = JURI::base();
$lang = JFactory::getLanguage();
$lan = $lang->getTag();
$userName = $user->username; 
?>

<script>
    var baseUrl = '<?php echo $baseUrl; ?>';
    var lan = '<?php echo $lan; ?>';
	var userName = '<?php echo $userName; ?>';
</script>

<?php
include_once ('database_conn.php');
include_once ('function_lib.php');
include_once ('init_month_year.php');
include_once ('combo_script.php');
include_once ('language/lang_en.php');
include_once ('language/lang_fr.php');
include_once ('language/lang_switcher.php');
?>

<script type="text/javascript">
    var vLang = '<?php echo $vLang; ?>';
</script>

<script type="text/javascript" src="<?php echo $baseUrl; ?>js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>js/jquery.foundation.reveal.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyCD7OEdGUC1V__0-mBJIoYifI5UtEILYbg&sensor=false" type="text/javascript"></script>

<div id="filter_panel" class="nav-data">
	<div class="row" id="filter_panel_1">
		<div class="col-md-3 col-padding"> 
			<select class="form-control chzn-select" name="ACountryId" id="ACountryId">
	         </select>
	    </div>
		<div class="col-md-3 col-padding"> 
			<select class="form-control chzn-select" name="ARegionId" id="ARegionId">
	           <option value="" selected="true"><?php echo $TEXT['All Region']; ?></option>
	        </select>
	    </div>
		<div class="col-md-3 col-padding"> 
			<select class="form-control chzn-select" name="District-list" id="District-list">
                <option value="" selected="true"><?php echo $TEXT['All District']; ?></option>
            </select>
        </div>
		<div class="col-md-3 col-padding"> 
			<select class="form-control chzn-select" name="OwnerType" id="OwnerType">
	            <option value="" selected="true"><?php echo $TEXT['All Owner Type']; ?></option>
	        </select>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-2 col-padding"> 
			 <select class="form-control chzn-select" name="ServiceAreaId" id="ServiceAreaId">
                <option value="" selected="true"><?php echo $TEXT['All Service Area']; ?></option>
            </select>
        </div>
		<div class="col-md-3 col-padding">  
			<select class="form-control chzn-select" name="AFTypeId" id="AFTypeId">
                <option value="" selected="true"><?php echo $TEXT['All Facility Type']; ?></option>
            </select>
        </div>
		<div class="col-md-3 col-padding">  
			<select class="form-control chzn-select" name="AFLevelId" id="AFLevelId">
		        <option value="" selected="true"><?php echo $TEXT['All Facility Level']; ?></option>
		    </select>
        </div>
		<div class="col-md-4 col-padding">
			<div class="tbl-header1 pull-right">
				<label>					
					<a id="PrintBTN" data-mce-href="#" class="but_print" href="javascript:void(0);" onclick="print_function()"><i data-mce-bootstrap="1" class="fa fa-print fa-lg">&nbsp;</i> <?php echo $TEXT['Print']; ?></a>
					<a id="PrintBTN1" data-mce-href="#" class="but_excel" href="javascript:void(0);" onclick="excel_function()"><i data-mce-bootstrap="1" class="fa fa-file-excel-o fa-lg">&nbsp;</i> <?php echo $TEXT['Excel']; ?></a>
				</label>
			</div>
		</div>
	</div>
</div>

<div class="row row-margin" id="list-panel">
    <div class="col-md-12"> 
        <table id="facilityTable"  class="table table-striped table-bordered display table-hover" cellspacing="0">
            <thead>
                <tr>
                    <th><?php echo $TEXT['Facility Id']; ?></th>
                    <th style="text-align:center;">SL#</th>
                    <th><?php echo $TEXT['Facility Code']; ?></th>
                    <th><?php echo $TEXT['Facility Name']; ?></th>
                    <th><?php echo $TEXT['Facility Type']; ?></th>
                    <th><?php echo $TEXT['Region Name']; ?></th>
                    <th><?php echo $TEXT['District']; ?></th>
                    <th><?php echo $TEXT['Owner Type']; ?></th>
                    <th><?php echo $TEXT['PPM']; ?></th>
                    <th><?php echo $TEXT['Service Area']; ?></th>
                    <th><?php echo $TEXT['Received From']; ?></th>
                    <th><?php echo $TEXT['Facility Address']; ?></th>
                    <th><?php echo $TEXT['Assigned Group']; ?></th>
                    <th style="display:none;"><?php echo $TEXT['Action']; ?></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>    
</div>

<?php

function getMultiSelectBox() {

    $sql = "SELECT ItemGroupId, GroupName FROM t_itemgroup ORDER BY GroupName asc ";
    $result = mysql_query($sql);
    $total = mysql_num_rows($result);

    $x = "<table border='0' id='multiselect' class = 'table'>";
    if ($total > 0) {
        while ($row = mysql_fetch_object($result)) {
            $x.="<tr><td><input type='checkbox' value='" . $row->ItemGroupId . "' id='" . $row->ItemGroupId . "' class='items' name='multiselectitems[]' onclick='selStatus(this.value)'><span class='custom-checkbox'></span>" . $row->GroupName . "</td></tr>";
        }
    }
    $x.="</table>";
    $x = str_replace("\n", '', $x);
    $x = str_replace("\r", '', $x);
    return $x;
}
?>
<div class="row row-margin" id="entry_panel" style="display:none;">

    <div class="col-md-6">
        <div class="form-panel">
            <div class="panel-heading">
                <?php echo $TEXT['Facility Detail Form']; ?>
                <span class="pull-right btn btn-success" onclick="addpoint()" id="btnAdd"><?php echo $TEXT['Add new Location']; ?></span>                   
            </div>	

            <div class="panel-body">

                <form class="no-margin" data-validate="parsley" id="facility_form">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?php echo $TEXT['Facility Code']; ?>*</label>
                                <input type="text" class="form-control input-sm" id="FacilityCode" name="FacilityCode" data-required="true" placeholder="Facility Code"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?php echo $TEXT['Facility Name']; ?>*</label>
                                <input type="text" class="form-control input-sm" id="FacilityName" data-required="true" name="FacilityName" placeholder="Facility Name"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?php echo $TEXT['Region Name']; ?>*</label>
                                <select class="form-control input-sm" id="RegionId" name="RegionId" data-required="true" placeholder="Region Name">
                                    <option value="" selected="true"><?php echo $TEXT['Region Name']; ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?php echo $TEXT['Facility Type']; ?>*</label>
                                <select class="form-control input-sm" name="FTypeId" id="FTypeId" data-required="true">
                                    <option value="" selected="true"><?php echo $TEXT['Facility Type']; ?></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?php echo $TEXT['Facility Level']; ?>*</label>
                                <select class="form-control input-sm" name="FLevelId" id="FLevelId" data-required="true">
                                    <option value="" selected="true"><?php echo $TEXT['Facility Level']; ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?php echo $TEXT['District']; ?>*</label>
                                <select class="form-control input-sm" name="ADistrict-list" id="ADistrict-list" data-required="true">
                                    <option value="" selected="true"><?php echo $TEXT['District']; ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?php echo $TEXT['Owner Type']; ?>*</label>
                                <select class="form-control input-sm" name="AOwnerType" id="AOwnerType" data-required="true">
                                    <option value="" selected="true"><?php echo $TEXT['Owner Type']; ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?php echo $TEXT['Service Area']; ?>*</label>
                                <select class="form-control input-sm" name="AServiceAreaId" id="AServiceAreaId" data-required="true">
                                    <option value="" selected="true"><?php echo $TEXT['Service Area']; ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">  
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="AgentType"><?php echo $TEXT['PPM']; ?></label>
                                <input type="checkbox" name="AgentType" id="AgentType" onclick="bPPM()"/>
                                <span class="custom-checkbox"></span> 
                            </div>
                        </div>


                        <!--<div class="col-lg-3">
                                
                        </div>-->
                        <div class="col-md-6">
                            <div class="form-group">

                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php echo $TEXT['Facility Address']; ?>*</label>
                        <input type="text" class="form-control input-sm" id="FacilityAddress" maxlength="300" name="FacilityAddress" data-required="true" placeholder="Facility Address"/>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?php echo $TEXT['Facility Phone']; ?></label>
                                <input type="text" class="form-control input-sm" id="FacilityPhone" maxlength="100" name="FacilityPhone"  placeholder="Facility Phone"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?php echo $TEXT['Facility Fax']; ?></label>
                                <input type="text" class="form-control input-sm" id="FacilityFax" name="FacilityFax"  maxlength="100"  placeholder="Facility Fax"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?php echo $TEXT['Facility Email']; ?></label>
                                <input type="text" class="form-control input-sm" type="email" id="FacilityEmail" name="FacilityEmail" placeholder="Facility Email"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?php echo $TEXT['Placement of Location']; ?></label>
                                <input type="text" class="form-control input-sm" id="location" name="location" placeholder="Location"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" style="display:none;"><?php echo $TEXT['Number of Facility']; ?></label>
                                <input type="text" style="display:none;" class="form-control input-sm" id="FacilityCount" name="FacilityCount" placeholder="Number of facility"  disabled/>
                            </div>                   
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">

                            </div>
                        </div>
                    </div>


                    <div class="panel-footer text-right">
                        <input type="text" style="display:none;" value="insertUpdateFacilityData" id="action" name="action"/>
                        <input type="text" style="display:none;" id="counId" name="counId"/>
                        <input type="text" style="display:none;" id="RecordId" name="RecordId"/>	
                       <input type="hiidden" style="display:none;" id="userId" value="<?php echo $user->name; ?>"/>
                        <input type="hiidden" style="display:none;" id="en-GBId" value="<?php echo $lan; ?>"/>
                        <!--						
                        <a href="javascript:void(0);" class="btn btn-success btn-form-success"><?php echo $TEXT['Submit']; ?></a>
                        <a href="javascript:void(0);" class="btn btn-default" onClick="onListPanel()"><?php echo $TEXT['Cancel']; ?></a>
                        -->
                        <a id="btn-submit-facility" href="javascript:void(0);" class="btn btn-success btn-form-success">Submit</a>
                        <a id="btn-cancel-facility" href="javascript:void(0);" class="btn btn-default" onClick="onListPanel()">Cancel</a>


                    </div>

                </form>
            </div>       
        </div>  
    </div>  

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php echo $TEXT['Map']; ?>
                <span class="pull-right">
                    <input type='text' id="addressInput"/>
                    <a href="javascript:void(0);" class="btn btn-success" onclick="searchLocations()"><?php echo $TEXT['Search']; ?></a></span>        
            </div>	
       	    <div class="padding-md" id="map" style="height:450px;">
            </div>
            <div style="height: 50px;">
                <span style="padding-left: 20px;">
                    <?php echo $TEXT['Closest matching address']; ?>:
                    <div style="padding-left: 20px;" id="address">
                    </div>
                </span>                
            </div>
        </div>
    </div>

</div>


<style>
    .SL, .Action {
        text-align: center !important;
    }
    #firstsec{
        float: left;
        width: 45%;
        padding: 5px 5px 5px 15px;
    }    
    #secondsec{
        float: left !important;
        width: 45%;
        padding: 5px 5px 5px 50px;
    }    
    .colon{
        padding-right: 10px;
        line-height: 1.5;
    }    
    .data{
        line-height: 1.5;
    }   
    #firstsec label{
        padding-right: 5px;
    }  
    #secondsec label{
        padding-right: 5px;
    }
    
    #map img{
        max-width: inherit !important;
    }    
    .panel-heading {
	  height: 56px;
	}
</style>

<script>
    function print_function() {
		 var pfilter =$('#facilityTable_filter').find(":selected").text();

        window.open("<?php echo $baseUrl; ?>report/t_facility_print.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan; ?>&CountryId=" + $('#ACountryId').val()
                + "&ARegionId=" + $('#ARegionId').val()
                + "&FacilityLevel=" + $('#AFLevelId').val()
                + "&FacilityType=" + $('#AFTypeId').val()
                + "&OwnerType=" + $('#OwnerType').val()
                + "&District-list=" + $('#District-list').val()
                + "&ServiceAreaId=" + $('#ServiceAreaId').val()
                + "&CountryName=" + $('#ACountryId').find(":selected").text()
                + "&RegionName=" + $('#ARegionId').find(":selected").text()
                + "&FTypeName=" + $('#AFTypeId').find(":selected").text()
                + "&FLevelName=" + $('#AFLevelId').find(":selected").text()
                + "&OwnerTypeName=" + $('#OwnerType').find(":selected").text()
                + "&DistrictName=" + $('#District-list').find(":selected").text()
                + "&ServiceAreaName=" + $('#ServiceAreaId').find(":selected").text()
                + "&sSearch=" + pfilter);
/*
        var pCountryName = $("#ACountryId option:selected").text();
        var pRegionName = $("#ARegionId option:selected").text();
        var FTypeName = $("#AFTypeId option:selected").text();
        var FLevelName = $("#AFLevelId option:selected").text();
        var pfilter = $("#facilityTable_filter input").val();

        window.open("<?php echo $baseUrl; ?>report/t_facility_print.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan; ?>&CountryId=" + $('#ACountryId').val()
                + "&ARegionId=" + $('#ARegionId').val()
                + "&FacilityLevel=" + $('#AFLevelId').val()
                + "&FacilityType=" + $('#AFTypeId').val()
                + "&OwnerType=" + $('#OwnerType').val()
                + "&District-list=" + $('#District-list').val()
                + "&ServiceAreaId=" + $('#ServiceAreaId').val()
                + "&CountryName=" + $('#ACountryId option[value=' + $('#ACountryId').val() + ']').text()
                + "&RegionName=" + $('#ARegionId option[value=' + $('#ARegionId').val() + ']').text()
                + "&FTypeName=" + $('#AFTypeId option[value=' + $('#AFTypeId').val() + ']').text()
                + "&FLevelName=" + $('#AFLevelId option[value=' + $('#AFLevelId').val() + ']').text()
                + "&OwnerTypeName=" + $('#OwnerType option[value=' + $('#OwnerType').val() + ']').text()
                + "&DistrictName=" + $('#District-list option[value=' + $('#District-list').val() + ']').text()
                + "&ServiceAreaName=" + $('#ServiceAreaId option[value=' + $('#ServiceAreaId').val() + ']').text()
                + "&sSearch=" + pfilter);
				
				*/

    }
    function excel_function() {
		
		var pfilter =$('#facilityTable_filter').find(":selected").text();

        window.open("<?php echo $baseUrl; ?>report/t_facility_excel.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan; ?>&CountryId=" + $('#ACountryId').val()
                + "&ARegionId=" + $('#ARegionId').val()
                + "&FacilityLevel=" + $('#AFLevelId').val()
                + "&FacilityType=" + $('#AFTypeId').val()
                + "&OwnerType=" + $('#OwnerType').val()
                + "&District-list=" + $('#District-list').val()
                + "&ServiceAreaId=" + $('#ServiceAreaId').val()
                + "&CountryName=" + $('#ACountryId').find(":selected").text()
                + "&RegionName=" + $('#ARegionId').find(":selected").text()
                + "&FTypeName=" + $('#AFTypeId').find(":selected").text()
                + "&FLevelName=" + $('#AFLevelId').find(":selected").text()
                + "&OwnerTypeName=" + $('#OwnerType').find(":selected").text()
                + "&DistrictName=" + $('#District-list').find(":selected").text()
                + "&ServiceAreaName=" + $('#ServiceAreaId').find(":selected").text()
                + "&sSearch=" + pfilter);
		/*
        var pCountryName = $("#ACountryId option:selected").text();
        var pRegionName = $("#ARegionId option:selected").text();
        var FTypeName = $("#AFTypeId option:selected").text();
        var FLevelName = $("#AFLevelId option:selected").text();
        var pfilter = $("#facilityTable_filter input").val();

        window.open("<?php echo $baseUrl; ?>report/t_facility_excel.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan; ?>&CountryId=" + $('#ACountryId').val()
                + "&ARegionId=" + $('#ARegionId').val()
                + "&FacilityLevel=" + $('#AFLevelId').val()
                + "&FacilityType=" + $('#AFTypeId').val()
                + "&OwnerType=" + $('#OwnerType').val()
                + "&District-list=" + $('#District-list').val()
                + "&ServiceAreaId=" + $('#ServiceAreaId').val()
                + "&CountryName=" + $('#ACountryId option[value=' + $('#ACountryId').val() + ']').text()
                + "&RegionName=" + $('#ARegionId option[value=' + $('#ARegionId').val() + ']').text()
                + "&FTypeName=" + $('#AFTypeId option[value=' + $('#AFTypeId').val() + ']').text()
                + "&FLevelName=" + $('#AFLevelId option[value=' + $('#AFLevelId').val() + ']').text()
                + "&OwnerTypeName=" + $('#OwnerType option[value=' + $('#OwnerType').val() + ']').text()
                + "&DistrictName=" + $('#District-list option[value=' + $('#District-list').val() + ']').text()
                + "&ServiceAreaName=" + $('#ServiceAreaId option[value=' + $('#ServiceAreaId').val() + ']').text()
                + "&sSearch=" + pfilter);
				*/
    }
</script>
<link href="<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>css/jquery.dataTables.custom.css" rel="stylesheet"/>
<script src='<?php echo $baseUrl; ?>media/datatable/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.min.js'></script>

<script src='<?php echo $baseUrl; ?>t_facility_view.js'></script>
<script src='<?php echo $baseUrl; ?>js/jquery.check.alphabet.js'></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_en.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_fr.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_switcher.js"></script>
