<?php
$user = JFactory::getUser();

//print_r($user);
//exit;

$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
$jBaseUrl = JURI::base();
$lang = JFactory::getLanguage();
$lan = $lang->getTag();
?>

<script>
    var baseUrl = '<?php echo $baseUrl; ?>';
    var lan = '<?php echo $lan; ?>';
</script>

<?php
include_once ('database_conn.php');
include_once ('function_lib.php');
include_once ('init_month_year.php');
include_once ('combo_script.php');
include_once ('language/lang_en.php');
include_once ('language/lang_fr.php');
include_once ('language/lang_switcher.php');

$ProcessId = $aUserProcess['ProcessId'];
?>

<script type="text/javascript">
    var vLang = '<?php echo $vLang; ?>';
</script>

<div class="row"> 
    <?php switch ($ProcessId): case 8: ?>
        <?php case 18: ?>
            <div class="col-md-4">	
                <?php break; ?>
            <?php default: ?>
                <div class="col-md-6">	
                    <?php break; ?>
            <?php endswitch; ?>	
            <div id="form-panel99">
                <div class="panel-heading">
                    <?php echo $aUserProcess['ProcessName']; ?>           
                </div>
                <div class="panel-body">
                    <?php switch ($ProcessId): case 1: ?>
                        <?php case 2: ?>
                            <!-- Code block for CASE 1 and CASE 2 (Inward and Photo Taking)<br> -->
                            <form novalidate="" data-validate="parsley" id="frmProcessTracking" class="form-horizontal form-border no-margin">

                                <div class="form-group"  id="TrackingNo-group-id">
                                    <label class="control-label col-md-4" for="TrackingNo"><?php echo 'Inward No :'; ?></label>
                                    <div class="col-md-8">
                                        <input class="form-control input-sm parsley-validated" maxlength="100" type="text" name="TrackingNo" id="TrackingNo" data-required="true" placeholder="scan here..."/>
                                    </div>									
                                </div> 
                                <?php if ($ProcessId == 1): ?>
                                    <div class="form-group">
                                        <div class="col-md-4">
                                        </div>
                                        <div class="col-md-8">
                                            <label class="control-label">Select Department/Unit</label>
                                            <label class="radio inline">
                                                <input type="radio" name="OptUnit" value="1" checked />
                                                Textile
                                            </label>
                                            <label class="radio inline">
                                                <input type="radio" name="OptUnit" value="2"/>
                                                Analytical
                                            </label>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="form-group">
                                    <div class="col-md-4">
                                        <input type="text" value="insertUpdateProcessTracking" id="action" name="action" style="display: none;"/>
                                        <input type="hiidden" style="display:none;"name="ProcessId" id="ProcessId" value="<?php echo $aUserProcess['ProcessId']; ?>"/>

                                        <input type="hiidden" style="display:none;"name="ParentProcessId" id="ParentProcessId" value="<?php echo $aUserProcess['ParentProcessId']; ?>"/>




                                        <input type="hiidden" style="display:none;" id="userId" value="<?php echo $user->username; ?>"/>
                                        <input type="hiidden" style="display:none;" id="en-GBId" value="<?php echo $lan; ?>"/>
                                    </div>
                                    <div class="col-md-8">		                    	
                                        <a style="display:none;" href="javascript:void(0);" class="btn btn-success btn-form-success" id="submitProcessTracking"><?php echo $TEXT['Submit']; ?></a>
                                        <a style="display:none;" href="javascript:void(0);" class="btn btn-default" onClick="onListPanel()"><?php echo $TEXT['Cancel']; ?></a>			                    
                                    </div>
                                </div>
                            </form>
                            <?php break; ?>				
                        <?php case 3: ?>
                            <!-- Code block for CASE 3 (Sample Registration)<br> -->
                            <form novalidate="" data-validate="parsley" id="frmProcessTracking" class="form-horizontal form-border no-margin">
                                <div class="form-group">
                                    <label class="control-label col-md-6" for="InwardNoPt"><?php echo 'Inward No (For Photo Taking):'; ?></label>
                                    <div class="col-md-6">
                                        <input class="form-control input-sm parsley-validated" maxlength="100" type="text" name="TrackingNoPt" id="TrackingNoPt" data-required="true" placeholder="scan here..."/>
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <label class="control-label col-md-6" for="TrackingNo"><?php echo 'Inward No (For Sample Reg):'; ?></label>
                                    <div class="col-md-6">
                                        <input class="form-control input-sm parsley-validated" maxlength="100" type="text" name="TrackingNo" id="TrackingNo" data-required="true" placeholder="scan here..."/>
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <label class="control-label col-md-6" for="RegNo"><?php echo 'Registration No (For Sample Reg):'; ?></label>
                                    <div class="col-md-6">
                                        <input class="form-control input-sm parsley-validated" maxlength="100" type="text" name="RegNo" id="RegNo" data-required="true" placeholder="scan here..."/>
                                    </div>
                                </div> 

                                <div class="form-group999">
                                    <div class="col-md-4999">
                                        <input type="text" value="insertUpdateProcessTracking" id="action" name="action" style="display: none;"/>
                                        <input type="hiidden" style="display:none;"name="ProcessId" id="ProcessId" value="<?php echo $aUserProcess['ProcessId']; ?>"/>

                                        <input type="hiidden" style="display:none;"name="ParentProcessId" id="ParentProcessId" value="<?php echo $aUserProcess['ParentProcessId']; ?>"/>




                                        <input type="hiidden" style="display:none;" id="userId" value="<?php echo $user->username; ?>"/>
                                        <input type="hiidden" style="display:none;" id="en-GBId" value="<?php echo $lan; ?>"/>
                                    </div>
                                    <div class="col-md-8999">		                    	
                                        <a style="display:none;" href="javascript:void(0);" class="btn btn-success btn-form-success" id="submitProcessTracking"><?php echo $TEXT['Submit']; ?></a>
                                        <a style="display:none;" href="javascript:void(0);" class="btn btn-default" onClick="onListPanel()"><?php echo $TEXT['Cancel']; ?></a>			                    
                                    </div>
                                </div>
                            </form>
                            <?php break; ?>				
                        <?php case 8: ?>
                            <!-- Code block for CASE 8 (Phy Reporting Team)<br> -->
                            <form novalidate="" data-validate="parsley" id="frmProcessTracking" class="form-horizontal form-border no-margin">

                                <div class="form-group">
                                    <label class="control-label col-md-4" for="TrackingNo"><?php echo 'Wet Lab:'; ?></label>
                                    <div class="col-md-8">
                                        <input class="form-control input-sm parsley-validated" maxlength="100" type="text" name="RegNoWet" id="RegNoWet" data-required="true" placeholder="scan here..."/>
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <label class="control-label col-md-4" for="RegNo"><?php echo 'Mechanical Lab:'; ?></label>
                                    <div class="col-md-8">
                                        <input class="form-control input-sm parsley-validated" maxlength="100" type="text" name="RegNoMec" id="RegNoMec" data-required="true" placeholder="scan here..."/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4" for="RegNo"><?php echo 'Pilling-abrasion Lab:'; ?></label>
                                    <div class="col-md-8">
                                        <input class="form-control input-sm parsley-validated" maxlength="100" type="text" name="RegNoPil" id="RegNoPil" data-required="true" placeholder="scan here..."/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-4">
                                        <input type="text" value="insertUpdateProcessTracking" id="action" name="action" style="display: none;"/>
                                        <input type="hiidden" style="display:none;"name="ProcessId" id="ProcessId" value="<?php echo $aUserProcess['ProcessId']; ?>"/>

                                        <input type="hiidden" style="display:none;"name="ParentProcessId" id="ParentProcessId" value="<?php echo $aUserProcess['ParentProcessId']; ?>"/>




                                        <input type="hiidden" style="display:none;" id="userId" value="<?php echo $user->username; ?>"/>
                                        <input type="hiidden" style="display:none;" id="en-GBId" value="<?php echo $lan; ?>"/>
                                    </div>
                                    <div class="col-md-8">		                    	
                                        <a style="display:none;" href="javascript:void(0);" class="btn btn-success btn-form-success" id="submitProcessTracking"><?php echo $TEXT['Submit']; ?></a>
                                        <a style="display:none;" href="javascript:void(0);" class="btn btn-default" onClick="onListPanel()"><?php echo $TEXT['Cancel']; ?></a>			                    
                                    </div>
                                </div>
                            </form>
                            <?php break; ?>				
                        <?php case 18: ?>
                            <!-- Code block for CASE 18 (Physical Lab Data Entry)<br> -->
                            <form novalidate="" data-validate="parsley" id="frmProcessTracking" class="form-horizontal form-border no-margin">

                                <div class="form-group">
                                    <label class="control-label col-md-4" for="TrackingNo"><?php echo 'For Physical:'; ?></label>
                                    <div class="col-md-8">
                                        <input class="form-control input-sm parsley-validated" maxlength="100" type="text" name="RegNoPhy" id="RegNoPhy" data-required="true" placeholder="scan here..."/>
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <label class="control-label col-md-4" for="RegNo"><?php echo 'For Color Fastness:'; ?></label>
                                    <div class="col-md-8">
                                        <input class="form-control input-sm parsley-validated" maxlength="100" type="text" name="RegNoCol" id="RegNoCol" data-required="true" placeholder="scan here..."/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4" for="RegNo"><?php echo 'For Fibre:'; ?></label>
                                    <div class="col-md-8">
                                        <input class="form-control input-sm parsley-validated" maxlength="100" type="text" name="RegNoFib" id="RegNoFib" data-required="true" placeholder="scan here..."/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-4">
                                        <input type="text" value="insertUpdateProcessTracking" id="action" name="action" style="display: none;"/>
                                        <input type="hiidden" style="display:none;"name="ProcessId" id="ProcessId" value="<?php echo $aUserProcess['ProcessId']; ?>"/>

                                        <input type="hiidden" style="display:none;"name="ParentProcessId" id="ParentProcessId" value="<?php echo $aUserProcess['ParentProcessId']; ?>"/>




                                        <input type="hiidden" style="display:none;" id="userId" value="<?php echo $user->username; ?>"/>
                                        <input type="hiidden" style="display:none;" id="en-GBId" value="<?php echo $lan; ?>"/>
                                    </div>
                                    <div class="col-md-8">		                    	
                                        <a style="display:none;" href="javascript:void(0);" class="btn btn-success btn-form-success" id="submitProcessTracking"><?php echo $TEXT['Submit']; ?></a>
                                        <a style="display:none;" href="javascript:void(0);" class="btn btn-default" onClick="onListPanel()"><?php echo $TEXT['Cancel']; ?></a>			                    
                                    </div>
                                </div>
                            </form>
                            <?php break; ?>
                        <?php case 19: ?>
                            <!-- Code block for CASE 19 (Documentation)<br> -->
                            <form novalidate="" data-validate="parsley" id="frmProcessTracking" class="form-horizontal form-border no-margin">

                                <div class="form-group">
                                    <label class="control-label col-md-4" for="TrackingNo"><?php echo 'For Compilation:'; ?></label>
                                    <div class="col-md-8">
                                        <input class="form-control input-sm parsley-validated" maxlength="100" type="text" name="RegNoCom" id="RegNoCom" data-required="true" placeholder="scan here..."/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4" for="RegNo"><?php echo 'For Subcontractor:'; ?></label>
                                    <div class="col-md-8">
                                        <input class="form-control input-sm parsley-validated" maxlength="100" type="text" name="RegNoSub" id="RegNoSub" data-required="true" placeholder="scan here..."/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-4">
                                        <input type="text" value="insertUpdateProcessTracking" id="action" name="action" style="display: none;"/>
                                        <input type="hiidden" style="display:none;"name="ProcessId" id="ProcessId" value="<?php echo $aUserProcess['ProcessId']; ?>"/>

                                        <input type="hiidden" style="display:none;"name="ParentProcessId" id="ParentProcessId" value="<?php echo $aUserProcess['ParentProcessId']; ?>"/>




                                        <input type="hiidden" style="display:none;" id="userId" value="<?php echo $user->username; ?>"/>
                                        <input type="hiidden" style="display:none;" id="en-GBId" value="<?php echo $lan; ?>"/>
                                    </div>
                                    <div class="col-md-8">		                    	
                                        <a style="display:none;" href="javascript:void(0);" class="btn btn-success btn-form-success" id="submitProcessTracking"><?php echo $TEXT['Submit']; ?></a>
                                        <a style="display:none;" href="javascript:void(0);" class="btn btn-default" onClick="onListPanel()"><?php echo $TEXT['Cancel']; ?></a>			                    
                                    </div>
                                </div>
                            </form>
                            <?php break; ?>
                        <?php case 22: ?>
                            <!-- Code block for CASE 22 (Invoice Generation)<br> -->
                            <form novalidate="" data-validate="parsley" id="frmProcessTracking" class="form-horizontal form-border no-margin">

                                <div class="form-group">
                                    <label class="control-label col-md-4" for="TrackingNo"><?php echo 'For Job Receive:'; ?></label>
                                    <div class="col-md-8">
                                        <input class="form-control input-sm parsley-validated" maxlength="100" type="text" name="RegNoRec" id="RegNoRec" data-required="true" placeholder="scan here..."/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4" for="RegNo"><?php echo 'For Job Delivered:'; ?></label>
                                    <div class="col-md-8">
                                        <input class="form-control input-sm parsley-validated" maxlength="100" type="text" name="RegNoDel" id="RegNoDel" data-required="true" placeholder="scan here..."/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-4">
                                        <input type="text" value="insertUpdateProcessTracking" id="action" name="action" style="display: none;"/>
                                        <input type="hiidden" style="display:none;"name="ProcessId" id="ProcessId" value="<?php echo $aUserProcess['ProcessId']; ?>"/>

                                        <input type="hiidden" style="display:none;"name="ParentProcessId" id="ParentProcessId" value="<?php echo $aUserProcess['ParentProcessId']; ?>"/>




                                        <input type="hiidden" style="display:none;" id="userId" value="<?php echo $user->username; ?>"/>
                                        <input type="hiidden" style="display:none;" id="en-GBId" value="<?php echo $lan; ?>"/>
                                    </div>
                                    <div class="col-md-8">		                    	
                                        <a style="display:none;" href="javascript:void(0);" class="btn btn-success btn-form-success" id="submitProcessTracking"><?php echo $TEXT['Submit']; ?></a>
                                        <a style="display:none;" href="javascript:void(0);" class="btn btn-default" onClick="onListPanel()"><?php echo $TEXT['Cancel']; ?></a>			                    
                                    </div>
                                </div>
                            </form>
                            <?php break; ?>

                        <?php case 4: ?>  
                        <?php case 10: ?>   
                        <?php case 14: ?>   
                            <!-- Code block for CASE 4<br> -->
                            <form novalidate="" data-validate="parsley" id="frmProcessTracking" class="form-horizontal form-border no-margin">

                                <div class="form-group">
                                    <label class="control-label col-md-4" for="RegNo"><?php echo 'Registration No:'; ?></label>
                                    <div class="col-md-8">
                                        <input class="form-control input-sm parsley-validated" maxlength="100" type="text" name="RegNo" id="RegNo" data-required="true" placeholder="scan here..."/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div for="SubContract" class="col-md-4" style="text-align:right;">Is it Sub-Contract?</div>
                                    <div class="col-md-8">
                                        <input type="checkbox" id="bSubContract" name="bSubContract">
                                        <span class="custom-checkbox"></span> 
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-4">
                                        <input type="text" value="insertUpdateProcessTracking" id="action" name="action" style="display: none;"/>
                                        <input type="hiidden" style="display:none;"name="ProcessId" id="ProcessId" value="<?php echo $aUserProcess['ProcessId']; ?>"/>
                                        <input type="hiidden" style="display:none;"name="ParentProcessId" id="ParentProcessId" value="<?php echo $aUserProcess['ParentProcessId']; ?>"/>
                                        <input type="hiidden" style="display:none;" id="userId" value="<?php echo $user->username; ?>"/>
                                        <input type="hiidden" style="display:none;" id="en-GBId" value="<?php echo $lan; ?>"/>
                                    </div>
                                    <div class="col-md-8">		                    	
                                        <a style="display:none;" href="javascript:void(0);" class="btn btn-success btn-form-success" id="submitProcessTracking"><?php echo $TEXT['Submit']; ?></a>
                                        <a style="display:none;" href="javascript:void(0);" class="btn btn-default" onClick="onListPanel()"><?php echo $TEXT['Cancel']; ?></a>			                    
                                    </div>
                                </div>
                            </form>
                            <?php break; ?>
                        <?php default: ?>
                            <!-- Code block for CASE default<br> -->
                            <form novalidate="" data-validate="parsley" id="frmProcessTracking" class="form-horizontal form-border no-margin">

                                <div class="form-group">
                                    <label class="control-label col-md-4" for="RegNo"><?php echo 'Registration No:'; ?></label>
                                    <div class="col-md-8">
                                        <input class="form-control input-sm parsley-validated" maxlength="100" type="text" name="RegNo" id="RegNo" data-required="true" placeholder="scan here..."/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-4">
                                        <input type="text" value="insertUpdateProcessTracking" id="action" name="action" style="display: none;"/>
                                        <input type="hiidden" style="display:none;"name="ProcessId" id="ProcessId" value="<?php echo $aUserProcess['ProcessId']; ?>"/>

                                        <input type="hiidden" style="display:none;"name="ParentProcessId" id="ParentProcessId" value="<?php echo $aUserProcess['ParentProcessId']; ?>"/>




                                        <input type="hiidden" style="display:none;" id="userId" value="<?php echo $user->username; ?>"/>
                                        <input type="hiidden" style="display:none;" id="en-GBId" value="<?php echo $lan; ?>"/>
                                    </div>
                                    <div class="col-md-8">		                    	
                                        <a style="display:none;" href="javascript:void(0);" class="btn btn-success btn-form-success" id="submitProcessTracking"><?php echo $TEXT['Submit']; ?></a>
                                        <a style="display:none;" href="javascript:void(0);" class="btn btn-default" onClick="onListPanel()"><?php echo $TEXT['Cancel']; ?></a>			                    
                                    </div>
                                </div>
                            </form>
                            <?php break; ?>
                    <?php endswitch; ?>    
                </div>      
            </div>
        </div>
        <?php switch ($ProcessId): case 8: ?>
            <?php case 18: ?>
                <div class="col-md-8">	
                    <?php break; ?>
                <?php default: ?>
                    <div class="col-md-6">	
                        <?php break; ?>
                <?php endswitch; ?>	
                <div id="list-panel">
                    <?php switch ($ProcessId): case 8: ?>
                            <table  id="tblProcessTracking8" class="table table-striped table-bordered display table-hover" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th style="text-align: center;">SL#</th>
                                        <th><?php echo 'Tracking#'; ?></th>
                                        <th><?php echo 'Process Name'; ?></th>
                                        <th><?php echo 'In Time'; ?></th>
                                        <th><?php echo 'Out Time'; ?></th>
                                        <th><?php echo 'Current Duration'; ?></th>
                                        <th><?php echo 'Status' ?></th>
                                        <th><?php echo 'Wet' ?></th>
                                        <th><?php echo 'Mechanical' ?></th>
                                        <th><?php echo 'Pilling' ?></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <?php break; ?>
                        <?php case 18: ?>
                            <table  id="tblProcessTracking18" class="table table-striped table-bordered display table-hover" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th style="text-align: center;">SL#</th>
                                        <th><?php echo 'Tracking#'; ?></th>
                                        <th><?php echo 'Process Name'; ?></th>
                                        <th><?php echo 'In Time'; ?></th>
                                        <th><?php echo 'Out Time'; ?></th>
                                        <th><?php echo 'Current Duration'; ?></th>
                                        <th><?php echo 'Status' ?></th>
                                        <th><?php echo 'Physical' ?></th>
                                        <th><?php echo 'Color Fastness' ?></th>
                                        <th><?php echo 'Fibre' ?></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <?php break; ?>
                        <?php case 19: ?>
                            <table  id="tblProcessTracking19" class="table table-striped table-bordered display table-hover" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th style="text-align: center;">SL#</th>
                                        <th><?php echo 'Tracking#'; ?></th>
                                        <th><?php echo 'Process Name'; ?></th>
                                        <th><?php echo 'In Time'; ?></th>
                                        <th><?php echo 'Out Time'; ?></th>
                                        <th><?php echo 'Current Duration'; ?></th>
                                        <th><?php echo 'Status' ?></th>
                                        <th><?php echo 'Compilation' ?></th>
                                        <th><?php echo 'Subcontractor' ?></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <?php break; ?>
                        <?php default: ?>
                            <table  id="tblProcessTracking" class="table table-striped table-bordered display table-hover" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th style="text-align: center;">SL#</th>
                                        <th><?php echo 'Tracking#'; ?></th>
                                        <th><?php echo 'Process Name'; ?></th>
                                        <th><?php echo 'In Time'; ?></th>
                                        <th><?php echo 'Out Time'; ?></th>
                                        <th><?php echo 'Current Duration'; ?></th>
                                        <th><?php echo 'Status' ?></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <?php break; ?>
                    <?php endswitch; ?>
                </div>            
            </div>  
        </div>


        <style>
            #itemlist_form select, #itemlist_form input{
                max-width: 300px;
            }
        </style>

        <link href="<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.css" rel="stylesheet"/>
        <link href="<?php echo $baseUrl; ?>css/jquery.dataTables.custom.css" rel="stylesheet"/>
        <script src='<?php echo $baseUrl; ?>media/datatable/js/jquery.dataTables.min.js'></script>
        <script src='<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.min.js'></script>

        <script src='<?php echo $baseUrl; ?>t_process_tracking.js'></script>

        <script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_en.js"></script>
        <script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_fr.js"></script>
        <script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_switcher.js"></script>