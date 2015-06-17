var RegimenMasterTable;
var RecordId = '';
var userId = '';
var engbId = '';
var ProductSubGroupId = " ";

var $ = jQuery.noConflict();

function resetForm(id) {
	$('#' + id).each(function() {
		this.reset();
	});
}

/* When you click on "Back to List" button*/
function onListPanel() {
	$('#list-panel, .btn-form').show();
	$('#form-panel, .btn-list').hide();
	$('#PrintBTN, #PrintBTN1').show();
	$('#filter_panel').show();
}

/* When you click on "Add Record" button*/
function onFormPanel() {
	resetForm("t_Regimen_Master_form");
	RecordId = '';
	$('#list-panel, .btn-form').hide();
	$('#form-panel, .btn-list').show();
	$('#PrintBTN, #PrintBTN1').hide();
	$('#filter_panel').hide();
}

function onEditPanel() {
	$('#list-panel, .btn-form').hide();
	$('#form-panel, .btn-list').show();
	$('#PrintBTN, #PrintBTN1').hide();
	$('#filter_panel').hide();
}

function onConfirmWhenAddEdit() {
	$.ajax({
		"type" : "POST",
		"url" : baseUrl + "stage_one_datasourse.php",
		"data" : $('#t_Regimen_Master_form').serialize() + '&jUserId=' + userId + '&language=' + engbId,
		"success" : function(response) {
			$msgType = JSON.parse(response)['msgType'];
			$msg = JSON.parse(response)['msg'];
			if ($msgType == "success") {
				RegimenMasterTable.fnDraw();
				onSuccessMsg($msg);
				onListPanel();
			} else {
				onErrorMsg($msg);
			}
		}
	});
}

function onConfirmWhenDelete() {
	$.ajax({
		"type" : "POST",
		"url" : baseUrl + "stage_one_datasourse.php",
		"data" : 'action=deleteRegimenMasterData&RecordId=' + RecordId+ '&jUserId=' + userId + '&language=' + engbId,
		"success" : function(response) {
			$msgType = JSON.parse(response)['msgType'];
			$msg = JSON.parse(response)['msg'];
			if ($msgType == "success") {
				RegimenMasterTable.fnDraw();
				onSuccessMsg($msg);
			} else {
				onErrorMsg($msg);
			}
		}
	});
}


jQuery('#t_Regimen_Master_form').parsley({
	listeners : {
		onFieldValidate : function(elem) {
			if (!$(elem).is(':visible')) {
				return true;
			}
			return false;
		},
		onFormSubmit : function(isFormValid, event) {
			if (isFormValid) {
				onConfirmWhenAddEdit();
				return false;
			}
		}
	}
});



$(function() {
	
	//$('#example').dataTable();
	
	userId = $('#userId').val();
	engbId = $('#en-GBId').val();
	
	//console.log(gMonthList);

	$.each(gItemGroupList, function(i, obj) {
		$('#item-group').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
	});

	$('#item-group').val(gUserItemGroupId);

	$.each(gItemGroupList, function(i, obj) {
		$('#ItemGroupId').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
	});

	$('#ItemGroupId').val(gUserItemGroupId);

	$("#item-group").change(function() {
		RegimenMasterTable.fnDraw();
	});

	$.each(gGenderTypeList, function(i, obj) {
		$('#AGenderTypeId').append($('<option></option>').val(obj.GenderTypeId).html(obj.GenderType));
	});

	$('#AGenderTypeId').val();

	$.each(gGenderTypeList, function(i, obj) {
		$('#GenderTypeId').append($('<option></option>').val(obj.GenderTypeId).html(obj.GenderType));
	});	
	
	$('#GenderTypeId').val();

	$("#AGenderTypeId").change(function() {
		RegimenMasterTable.fnDraw();
	});	

	onListPanel();
	resetForm("t_Regimen_Master_form");

	$('#submitItemList').click(function() {
		$("#t_Regimen_Master_form").submit();
	});

	RegimenMasterTable = $('#RegimenMasterTable').dataTable({
		"bFilter" : true,		
		"bSort" : true,
		"bInfo" : true,
		"bPaginate" : true,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : true,
		"aaSorting" : [[2, 'asc'], [3, 'asc'], [4, 'asc']],
		"aLengthMenu" : [[25, 50, 100], [25, 50, 100]],
		"iDisplayLength" : 25,
		"sPaginationType" : "full_numbers",
		//"sScrollX": "100%",
		"sAjaxSource" : baseUrl + "stage_one_datasourse.php",
		"fnDrawCallback" : function(oSettings) {

			if (oSettings.aiDisplay.length == 0) {
				return;
			}

			$('a.itmEdit', RegimenMasterTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = RegimenMasterTable.fnGetData(nTr);
                        RegMasterId = aData[0];
                        $('#RegMasterId').val(aData[0]);
                        $('#ItemGroupId').val(aData[2]);
                        $('#RegimenMasterName').val(aData[3]);
                        $('#GenderTypeId').val(aData[4]);
                        $('#ColorCode').val(aData[9]);
                        $('#ItemGroupId').val(aData[6]);
                        $('#GenderTypeId').val(aData[7]);

					msg = "Do you really want to edit this record?";
					onCustomModal(msg, "onEditPanel");
				});
			});
			$('a.itmDrop', RegimenMasterTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = RegimenMasterTable.fnGetData(nTr);
					RecordId = aData[0];
					msg = "Do you really want to delete this record?";
					//alert(RecordId);
					onCustomModal(msg, "onConfirmWhenDelete");
				});
			});
			$('a.itmMore', RegimenMasterTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					if ($(this).children('span').attr('class') == 'label label-info faminus') {
						$(this).children('span').attr('class', 'label label-info');
						var nRemove = $(nTr).next()[0];
						nRemove.parentNode.removeChild(nRemove);
					} else {
						$(this).children('span').attr('class', 'label label-info faminus');
						RegimenMasterTable.fnOpen(nTr, fnFormatDetails(nTr), 'details');
					}
				});
			});
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
			"sClass" : "center-aln",
			"bVisible" : false
		}, {
			"sClass" : "center-aln",
			// SL#
			"sWidth" : "5%",
			"bSortable" : false
		}, {
			"sClass" : "left-aln",
			// GroupName
			"sWidth" : "10%",
			"bSortable" : true
		}, {
			"sClass" : "left-aln",
			// RegimenName
			"sWidth" : "25%",
			"bSortable" : true
		}, {
			"sClass" : "left-aln",
			// GenderType
			"sWidth" : "12%",
			"bSortable" : true
		}, {
			"sClass" : "left-aln",
			// ColorCode
			"sWidth" : "10%",
			"bSortable" : false
		}, {
			"sClass" : "left-aln",
			//ItemGroupId
			"sWidth" : "12%",
			"bSortable": false,
			"bVisible": false
		}, {
			"sClass" : "left-aln",
			//GenderTypeId
			"sWidth" : "12%",
			"bSortable": false,
			"bVisible": false
		}, {
			"sClass" : "center-aln",
			// Action
			"bVisible" : true,
			"sWidth" : "12%",
			"bSortable" : false
		}]
	});

	//onComboProductSubGroup();
	$("#colorpicker3").colorpicker();
}); 