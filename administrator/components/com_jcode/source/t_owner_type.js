var OwnerTypeTable;
var RecordId = '';
var userId = '';
var engbId = '';

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
	resetForm("t_Owner_Type_form");
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
		"data" : $('#t_Owner_Type_form').serialize() + '&jUserId=' + userId + '&language=' + engbId,
		"success" : function(response) {
			$msgType = JSON.parse(response)['msgType'];
			$msg = JSON.parse(response)['msg'];
			if ($msgType == "success") {
				OwnerTypeTable.fnDraw();
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
		"data" : 'action=deleteOwnerTypeData&RecordId=' + RecordId + '&jUserId=' + userId + '&language=' + engbId,
		"success" : function(response) {
			$msgType = JSON.parse(response)['msgType'];
			$msg = JSON.parse(response)['msg'];
			if ($msgType == "success") {
				OwnerTypeTable.fnDraw();
				onSuccessMsg($msg);
			} else {
				onErrorMsg($msg);
			}
		}
	});
}


jQuery('#t_Owner_Type_form').parsley({
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

	onListPanel();
	resetForm("t_Owner_Type_form");

	$('#submitItemList').click(function() {
		$("#t_Owner_Type_form").submit();
	});

	OwnerTypeTable = $('#OwnerTypeTable').dataTable({
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
			$('a.itmEdit', OwnerTypeTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = OwnerTypeTable.fnGetData(nTr);
                    RecordId = aData[0];
                    $('#RecordId').val(aData[0]);
                    $('#OwnerTypeName').val(aData[2]);
                    $('#OwnerTypeNameFrench').val(aData[3]);
					msg = "Do you really want to edit this record?";
					onCustomModal(msg, "onEditPanel");
				});
			});
			$('a.itmDrop', OwnerTypeTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = OwnerTypeTable.fnGetData(nTr);
					RecordId = aData[0];
					msg = "Do you really want to delete this record?";
					//alert(RecordId);
					onCustomModal(msg, "onConfirmWhenDelete");
				});
			});
			$('a.itmMore', OwnerTypeTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					if ($(this).children('span').attr('class') == 'label label-info faminus') {
						$(this).children('span').attr('class', 'label label-info');
						var nRemove = $(nTr).next()[0];
						nRemove.parentNode.removeChild(nRemove);
					} else {
						$(this).children('span').attr('class', 'label label-info faminus');
						OwnerTypeTable.fnOpen(nTr, fnFormatDetails(nTr), 'details');
					}
				});
			});
		},
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "action",
				"value" : 'getOwnerTypeData'
			});
			aoData.push({
				"name" : "lan",
				"value" : lan
			});
			aoData.push({
				"name" : "baseUrl",
				"value" : baseUrl
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
			//SL
			"sWidth": "10%",
			"bSortable": false
		},{
			"sClass": "left-aln",
			//OwnerTypeName
			"bSortable": true
		},{
			"sClass": "left-aln",
			//OwnerTypeNameFrench
			"bSortable": true
		},{
			"sClass": "center-aln",
			//Action
			"sWidth": "15%",
			"bSortable": false
		}]
	});

}); 