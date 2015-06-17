var oTable_user;
var RecordId = '';
var userId = '';
var engbId = '';

var oTable_country;
var oTable_productgroup;
var oTable_ownerType;
var oTable_region;
var userName = "";
var Name = "";
var MapId = "";
var mode = 'display';

var $ = jQuery.noConflict();


$(function() {

	userId = $('#userId').val();
	engbId = $('#en-GBId').val();
	
	oTable_user = $('#gridDataUser').dataTable({
		"bFilter" : true,		
		"bSort" : true,
		"bInfo" : true,
		"bPaginate" : true,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : true,
		"aaSorting" : [[2, 'asc']],
		"aLengthMenu" : [[25, 50, 100], [25, 50, 100]],
		"iDisplayLength" : 25,
		"sPaginationType" : "full_numbers",
		//"sScrollX": "100%",
		"sAjaxSource" : baseUrl + "user_country_map_datasource.php",
		"fnDrawCallback" : function(oSettings) {
			if (oSettings.aiDisplay.length == 0) {
				return;
			}
		},

        "fnRowCallback": function(nRow, aData, iDisplayIndex) {
            $(nRow).attr('id', aData[0]);
            $(nRow).attr('Name', aData[2]);
            $(nRow).attr('userN', aData[3]);
            return nRow;
        },		
		
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "action",
				"value" : 'getJoomlaLabUsers'
			});
			$.ajax({
				"dataType" : 'json',
				"type" : "POST",
				"url" : sSource,
				"data" : aoData,
				"success" : fnCallback
			});
		},
		"aoColumns" : [{
                "bVisible": false
            }, {
                "sClass": "SL",
                "bSortable": false,
                "sWidth": "10%"
            }, {
                "sClass": "Users",
                "bSortable": true,
                "sWidth": "30%"
            }, {
                "sClass": "UserId",
                "bSortable": false,
                "bVisible": false
            }, {
                "sClass": "GroupTitle",
                "bSortable": true,
                "sWidth": "55%"
		}]
	});
	
    $('#gridDataUser').click(function(event) {
        var id = $(event.target.parentNode).attr('id');
        Name = $(event.target.parentNode).attr('Name');
        userName = $(event.target.parentNode).attr('userN');
        var aData;
        var t;
        $(oTable_user.fnSettings().aoData).each(function() {
            if ($(this.nTr).attr('id') == id) {
                $(this.nTr).addClass('row_selected');
                aData = oTable_user.fnGetData(this.nTr);
            } else
                $(this.nTr).removeClass('row_selected');
        });
        if (aData) {
            mode = 'edit';
            oTable_country.fnDraw();
            oTable_productgroup.fnDraw();
            oTable_ownerType.fnDraw();
            oTable_region.fnDraw();
        }
    });	

oTable_country = $("#gridDataCountry").dataTable({
        "bFilter": false,
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "bSort": true,
        "bInfo": false,
        "bPaginate": false,
        "bSortClasses": false,
        "bProcessing": true,
        "bServerSide": true,
        // "aLengthMenu": [[25, 50, 100], [25, 50, 100]],
        // "iDisplayLength": 25,
        "sAjaxSource": baseUrl + "user_country_map_datasource.php",
        "oLanguage": {
            "sLengthMenu": "Display _MENU_ Records",
            "sZeroRecords": "No Record Found",
            "sInfo": "Showing _START_ to _END_ of _TOTAL_ Records",
            "sInfoEmpty": "Showing 0 to 0 of 0 Records",
            "sInfoFiltered": "(filtered from _MAX_ total Records)"
        },
        "fnDrawCallback": function() {
            $('td input.datacell', oTable_country.fnGetNodes()).each(function() {
                $(this).click(function() {
                    var nTr = this.parentNode.parentNode;
                    var aData = oTable_country.fnGetData(nTr);
                    var checkcon = $(this).prop('checked');
                    var MapId = aData[0];
                    var CountryId = aData[2];
                    $.ajax({
                        "type": "POST",
                        "url": baseUrl + "user_country_map_datasource.php",
                        "data": {
                            action: 'insertAllorOneMapping',
                            MapId: MapId,
                            CountryId: CountryId,
                            userName: userName,
                            jUserId: userid,
                            language: lan,
                            checkVal: checkcon
                        },
                        "success": function(response) {
                            $msgType = JSON.parse(response)['msgType'];

                            $msg = JSON.parse(response)['msg']
                            if ($msgType == "success") {
                                oTable_country.fnDraw();
                                onSuccessMsg($msg);
                            } else {
                                onErrorMsg($msg);
                            }
                        }
                    });
                });
            });
        },
        "fnRowCallback": function(nRow, aData, iDisplayIndex) {
            return nRow;
        },
        "fnServerData": function(sSource, aoData, fnCallback) {
            aoData.push({
                "name": "action",
                "value": "getCountryList"
            });
            aoData.push({
				"name": "lan",
				"value": lan
			});
            aoData.push({
                "name": "MapId",
                "value": MapId
            });
            aoData.push({
                "name": "Name",
                "value": Name
            });
            aoData.push({
                "name": "userName",
                "value": userName
            });
            aoData.push({
                "name": "mode",
                "value": mode
            });
            $.ajax({
                "dataType": 'json',
                "type": "POST",
                "url": sSource,
                "data": aoData,
                "success": function(json) {
                    fnCallback(json);
                }
            });
        },
        "aoColumns": [{
                "bVisible": false
            }, {
                "sClass": "CountryName",
                "bSortable": false
            }]
    });


    /////////////////////////////////////For Item Group/////////////////////////////
    oTable_productgroup = $("#gridDataProductGroup").dataTable({
        "bFilter": false,
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "bSort": true,
        "bInfo": false,
        "bPaginate": false,
        "bSortClasses": false,
        "bProcessing": true,
        "bServerSide": true,
        // "aLengthMenu": [[25, 50, 100], [25, 50, 100]],
        // "iDisplayLength": 25,
        "sAjaxSource": baseUrl + "user_country_map_datasource.php",
        "oLanguage": {
            "sLengthMenu": "Display _MENU_ Records",
            "sZeroRecords": "No Record Found",
            "sInfo": "Showing _START_ to _END_ of _TOTAL_ Records",
            "sInfoEmpty": "Showing 0 to 0 of 0 Records",
            "sInfoFiltered": "(filtered from _MAX_ total Records)"
        },
        "fnDrawCallback": function() {
            $('td input.datacell', oTable_productgroup.fnGetNodes()).each(function() {
                $(this).click(function() {
                    var nTr = this.parentNode.parentNode;
                    var aData = oTable_productgroup.fnGetData(nTr);
                    var checkcon = $(this).prop('checked');
                    var ItemGroupMapId = aData[0];
                    var ItemGroupId = aData[2];
                    $.ajax({
                        "type": "POST",
                        "url": baseUrl + "user_country_map_datasource.php",
                        "data": {
                            action: 'insertAllorOneMappingItemGroup',
                            ItemGroupMapId: ItemGroupMapId,
                            ItemGroupId: ItemGroupId,
                            jUserId: userid,
                            language: lan,
                            userName: userName,
                            checkVal: checkcon
                        },
                        "success": function(response) {
                            $msgType = JSON.parse(response)['msgType'];
                            $msg = JSON.parse(response)['msg']
                            if ($msgType == "success") {
                                oTable_productgroup.fnDraw();
                                onSuccessMsg($msg);
                            } else {
                                onErrorMsg($msg);
                            }
                        }
                    });
                });
            });
        },
        "fnRowCallback": function(nRow, aData, iDisplayIndex) {
            return nRow;
        },
        "fnServerData": function(sSource, aoData, fnCallback) {
            aoData.push({
                "name": "action",
                "value": "getItemGroupList"
            });
            
            aoData.push({
				"name": "lan",
				"value": lan
			});
            aoData.push({
                "name": "MapId",
                "value": MapId
            });
            aoData.push({
                "name": "Name",
                "value": Name
            });
            aoData.push({
                "name": "userName",
                "value": userName
            });
            aoData.push({
                "name": "mode",
                "value": mode
            });
            $.ajax({
                "dataType": 'json',
                "type": "POST",
                "url": sSource,
                "data": aoData,
                "success": function(json) {
                    fnCallback(json);
                }
            });
        },
        "aoColumns": [{
                "bVisible": false
            }, {
                "sClass": "ItemProup",
                "bSortable": false
            }]
    });


    /////////////////////////////////////For Owner Typw/////////////////////////////
    oTable_ownerType = $("#gridDataOwnerType").dataTable({
        "bFilter": false,
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "bSort": true,
        "bInfo": false,
        "bPaginate": false,
        "bSortClasses": false,
        "bProcessing": true,
        "bServerSide": true,
        // "aLengthMenu": [[25, 50, 100], [25, 50, 100]],
        // "iDisplayLength": 25,
        "sAjaxSource": baseUrl + "user_country_map_datasource.php",
        "oLanguage": {
            "sLengthMenu": "Display _MENU_ Records",
            "sZeroRecords": "No Record Found",
            "sInfo": "Showing _START_ to _END_ of _TOTAL_ Records",
            "sInfoEmpty": "Showing 0 to 0 of 0 Records",
            "sInfoFiltered": "(filtered from _MAX_ total Records)"
        },
        "fnDrawCallback": function() {
            $('td input.datacell', oTable_ownerType.fnGetNodes()).each(function() {
                $(this).click(function() {
                    var nTr = this.parentNode.parentNode;
                    var aData = oTable_ownerType.fnGetData(nTr);
                    var checkcon = $(this).prop('checked');
                    var OwnerTypeMapId = aData[0];
                    var OwnerTypeId = aData[2];
                    $.ajax({
                        "type": "POST",
                        "url": baseUrl + "user_country_map_datasource.php",
                        "data": {
                            action: 'insertAllorOneMappingOwner',
                            OwnerTypeMapId: OwnerTypeMapId,
                            OwnerTypeId: OwnerTypeId,
                            userName: userName,
                            jUserId: userid,
                            language: lan,
                            checkVal: checkcon
                        },
                        "success": function(response) {
                            $msgType = JSON.parse(response)['msgType'];
                            $msg = JSON.parse(response)['msg']
                            if ($msgType == "success") {
                                oTable_ownerType.fnDraw();
                                onSuccessMsg($msg);
                            } else {
                                onErrorMsg($msg);
                            }
                        }
                    });
                });
            });
        },
        "fnRowCallback": function(nRow, aData, iDisplayIndex) {
            return nRow;
        },
        "fnServerData": function(sSource, aoData, fnCallback) {
            aoData.push({
                "name": "action",
                "value": "getOwnerTypeList"
            });
            aoData.push({
				"name": "lan",
				"value": lan
			});
            aoData.push({
                "name": "MapId",
                "value": MapId
            });
            aoData.push({
                "name": "Name",
                "value": Name
            });
            aoData.push({
                "name": "userName",
                "value": userName
            });
            aoData.push({
                "name": "mode",
                "value": mode
            });
            $.ajax({
                "dataType": 'json',
                "type": "POST",
                "url": sSource,
                "data": aoData,
                "success": function(json) {
                    fnCallback(json);
                }
            });
        },
        "aoColumns": [{
                "bVisible": false
            }, {
                "sClass": "ItemProup",
                "bSortable": false
            }]
    });


    /////////////////////////////////////For Region/////////////////////////////
    oTable_region = $("#gridDataRegion").dataTable({
        "bFilter": false,
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "bSort": true,
        "bInfo": false,
        "bPaginate": false,
        "bSortClasses": false,
        "bProcessing": true,
        "bServerSide": true,
        // "aLengthMenu": [[25, 50, 100], [25, 50, 100]],
        // "iDisplayLength": 25,
        "sAjaxSource": baseUrl + "user_country_map_datasource.php",
        "oLanguage": {
            "sLengthMenu": "Display _MENU_ Records",
            "sZeroRecords": "No Record Found",
            "sInfo": "Showing _START_ to _END_ of _TOTAL_ Records",
            "sInfoEmpty": "Showing 0 to 0 of 0 Records",
            "sInfoFiltered": "(filtered from _MAX_ total Records)"
        },
        "fnDrawCallback": function() {
            $('td input.datacell', oTable_region.fnGetNodes()).each(function() {
                $(this).click(function() {
                    var nTr = this.parentNode.parentNode;
                    var aData = oTable_region.fnGetData(nTr);
                    var checkcon = $(this).prop('checked');
                    var RegionMapId = aData[0];
                    var RegionId = aData[2];
                    $.ajax({
                        "type": "POST",
                        "url": baseUrl + "user_country_map_datasource.php",
                        "data": {
                            action: 'insertAllorOneMappingRegion',
                            RegionMapId: RegionMapId,
                            RegionId: RegionId,
                            jUserId: userid,
                            language: lan,
                            userName: userName,
                            checkVal: checkcon
                        },
                        "success": function(response) {
                            $msgType = JSON.parse(response)['msgType'];
                            $msg = JSON.parse(response)['msg']
                            if ($msgType == "success") {
                                oTable_region.fnDraw();
                                onSuccessMsg($msg);
                            } else {
                                onErrorMsg($msg);
                            }
                        }
                    });
                });
            });
        },
        "fnRowCallback": function(nRow, aData, iDisplayIndex) {
            return nRow;
        },
        "fnServerData": function(sSource, aoData, fnCallback) {
            aoData.push({
                "name": "action",
                "value": "getRegionList",
                "lan": lan
            });
            aoData.push({
                "name": "MapId",
                "value": MapId
            });
            aoData.push({
                "name": "Name",
                "value": Name
            });
            aoData.push({
                "name": "userName",
                "value": userName
            });
            aoData.push({
                "name": "mode",
                "value": mode
            });
            $.ajax({
                "dataType": 'json',
                "type": "POST",
                "url": sSource,
                "data": aoData,
                "success": function(json) {
                    fnCallback(json);
                }
            });
        },
        "aoColumns": [{
                "bVisible": false
            }, {
                "sClass": "ItemProup",
                "bSortable": false
            }]
    });	
	
}); 