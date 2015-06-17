var RegimenMasterTable;
var RecordId = '';
var userId = '';
var engbId = '';
var ProductSubGroupId = " ";

var $ = jQuery.noConflict();

$(function() {
	
    $.each(gItemGroupList, function(i, obj) {
        $('#item-group').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
        $('#ItemGroupId').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));

    });

    $('#item-group').val(gUserItemGroupId);
    $('#ItemGroupId').val(gUserItemGroupId);

    $.each(gGenderTypeList, function(i, obj) {
        $('#AGenderTypeId').append($('<option></option>').val(obj.GenderTypeId).html(obj.GenderType));
        $('#GenderTypeId').append($('<option></option>').val(obj.GenderTypeId).html(obj.GenderType));
    });
	
	$('#AGenderTypeId').change(function() {
		RegimenMasterTable.fnDraw();
	});
	$('#item-group').change(function() {
		RegimenMasterTable.fnDraw();
	})
	
	RegimenMasterTable = $('#RegimenMasterTable').dataTable({
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
		"sAjaxSource" : baseUrl + "stage_one_datasourse.php",
		"fnDrawCallback" : function(oSettings) {
			if (oSettings.aiDisplay.length == 0) {
				return;
			}
		},
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "action",
				"value" : 'getRegimenMasterData'
			});
			aoData.push({
				"name" : "lan",
				"value" : lan
			});
			aoData.push({
				"name" : "baseUrl",
				"value" : baseUrl
			});
			aoData.push({
				"name": "ItemGroupId",
				"value": $('#item-group').val()
			});
			aoData.push({
				"name": "AGenderTypeId",
				"value": $('#AGenderTypeId').val()
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
		},{
			"sClass": "center-aln",
			"sWidth": "10%",
			"bSortable": false
		},{
			"sClass": "left-aln",
			"sWidth": "20%",
			"bSortable": false
		}, {
			"sClass": "left-aln",
			"sWidth": "20%",
			"bSortable": false
		},{
			"sClass": "left-aln",
			"sWidth": "15%",
			"bSortable": false
		},{
			"sClass": "left-aln",
			"sWidth": "15%",
			"bSortable": false
		},{
			"sClass": "ItemGroupId",
			"sWidth": "10%",
			"bSortable": false,
			"bVisible": false
		},{
			"sClass": "GenderTypeId",
			"sWidth": "10%",
			"bSortable": false,
			"bVisible": false
		},{
			"sClass": "Action",
			"sWidth": "15%",
			"bSortable": false,
			"bVisible": false
		}]
	});
}); 