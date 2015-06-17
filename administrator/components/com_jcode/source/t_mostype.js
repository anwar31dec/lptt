var MOStypeTable;
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
	resetForm("t_MOSType_form");
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
		"data" : $('#t_MOSType_form').serialize() + '&jUserId=' + userId + '&language=' + engbId,
		"success" : function(response) {
			$msgType = JSON.parse(response)['msgType'];
			$msg = JSON.parse(response)['msg'];
			if ($msgType == "success") {
				MOStypeTable.fnDraw();
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
		"data" : 'action=deleteMOSTypeData&RecordId=' + RecordId + '&jUserId=' + userId + '&language=' + engbId,
		"success" : function(response) {
			$msgType = JSON.parse(response)['msgType'];
			$msg = JSON.parse(response)['msg'];
			if ($msgType == "success") {
				MOStypeTable.fnDraw();
				onSuccessMsg($msg);
			} else {
				onErrorMsg($msg);
			}
		}
	});
}


jQuery('#t_MOSType_form').parsley({
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

function checkMinMax() {
    if ($('#MinMos').val() == '' || $('#MaxMos').val() == '')
        return true;

    var minVal = parseFloat($('#MinMos').val());
    var maxVal = parseFloat($('#MaxMos').val());
    if (minVal < maxVal)
        return true;
    else
        return false;
}

$(function() {
	
	//$('#example').dataTable();
	
	userId = $('#userId').val();
	engbId = $('#en-GBId').val();

	onListPanel();
	resetForm("t_MOSType_form");

	$('#submitItemList').click(function() {
	
		if (!checkMinMax()) {
				alert('Maximum MOS cannot be  less than  Minimum MOS');
				return false;
			}
	
		$("#t_MOSType_form").submit();
	});

	MOStypeTable = $('#MOStypeTable').dataTable({
		"bFilter" : true,		
		"bSort" : true,
		"bInfo" : true,
		"bPaginate" : true,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : true,
		"aaSorting" : [[1, 'asc']],
		"aLengthMenu" : [[25, 50, 100], [25, 50, 100]],
		"iDisplayLength" : 25,
		"sPaginationType" : "full_numbers",
		//"sScrollX": "100%",
		"sAjaxSource" : baseUrl + "stage_one_datasourse.php",
		"fnDrawCallback" : function(oSettings) {

			if (oSettings.aiDisplay.length == 0) {
				return;
			}

			$('a.itmEdit', MOStypeTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = MOStypeTable.fnGetData(nTr);
						RecordId = aData[0];
						$('#RecordId').val(aData[0]);
						$('#MosTypeName').val(aData[2]);
						$('#MosTypeNameFrench').val(aData[3]);
						$('#MinMos').val(aData[4]);
						$('#MaxMos').val(aData[5]);
						$('#ColorCode').val(aData[9]);
						$('#MosLabel').val(aData[7]);
					msg = "Do you really want to edit this record?";
					onCustomModal(msg, "onEditPanel");
				});
			});
			$('a.itmDrop', MOStypeTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = MOStypeTable.fnGetData(nTr);
					RecordId = aData[0];
					msg = "Do you really want to delete this record?";
					//alert(RecordId);
					onCustomModal(msg, "onConfirmWhenDelete");
				});
			});
			$('a.itmMore', MOStypeTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					if ($(this).children('span').attr('class') == 'label label-info faminus') {
						$(this).children('span').attr('class', 'label label-info');
						var nRemove = $(nTr).next()[0];
						nRemove.parentNode.removeChild(nRemove);
					} else {
						$(this).children('span').attr('class', 'label label-info faminus');
						MOStypeTable.fnOpen(nTr, fnFormatDetails(nTr), 'details');
					}
				});
			});
		},
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "action",
				"value" : 'getMOSTypeData'
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
			"sClass" : "center-aln",
			"bVisible" : false
		}, {
			"sClass" : "center-aln",
			// SL#
			"sWidth" : "5%",
			"bSortable" : false
		}, {
			"sClass" : "left-aln",
			// mosTypeName
			"sWidth" : "15%",
			"bSortable": false
		}, {
			"sClass" : "left-aln",
			// mosTypeNameFrench
			"sWidth" : "30%",
			"bSortable": false
		}, {
			"sClass" : "center-aln",
			// minMos
			"sWidth" : "10%",
			"bSortable" : false
		}, {
			"sClass" : "center-aln",
			// maxMos
			"sWidth" : "10%",
			"bSortable" : false
		}, {
			"sClass" : "center-aln",
			// colorCode
			"sWidth" : "10%",
			"bSortable" : false
		}, {
			"sClass" : "center-aln",
			// MosLabel
			"sWidth" : "10%",
			"bSortable" : false
		}, {
			"sClass" : "center-aln",
			// Action
			"bVisible" : true,
			"sWidth" : "10%",
			"bSortable" : false
		}]
	});

    $(".numberinput").forceNumeric();
    $("#colorpicker3").colorpicker();
}); 