var agreementTable;
var RecordId = '';
var userId = '';
var engbId = '';
var FundingSourceId=0;

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
	resetForm("agreement_form");
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

function onComboFundingSource() {
	$.getJSON(baseUrl + "t_combo.php", {
		action : 'getFundingSource',
		ItemGroup : $("#ItemGroup").val()
	}, function(response) {
		str = '<option value="">Funding Source</option>';
		for (var i = 0; i < response.length; i++) {
			str += '<option value="' + response[i].FundingSourceId + '">' + response[i].FundingSourceName + '</option>';
		}
		$('#FundingSourceId').html(str);
		$('#FundingSourceId').val(FundingSourceId);
	});
}

function onConfirmWhenAddEdit() {
	$.ajax({
		"type" : "POST",
		"url" : baseUrl + "stage_one_datasourse.php",
		"data" : $('#agreement_form').serialize() + '&jUserId=' + userId + '&language=' + engbId,
		"success" : function(response) {
			$msgType = JSON.parse(response)['msgType'];
			$msg = JSON.parse(response)['msg'];
			if ($msgType == "success") {
				agreementTable.fnDraw();
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
		"data" : 'action=deleteAgreementData&RecordId=' + RecordId + '&jUserId=' + userId + '&language=' + engbId,
		"success" : function(response) {
			$msgType = JSON.parse(response)['msgType'];
			$msg = JSON.parse(response)['msg'];
			if ($msgType == "success") {
				agreementTable.fnDraw();
				onSuccessMsg($msg);
			} else {
				onErrorMsg($msg);
			}
		}
	});
}


jQuery('#agreement_form').parsley({
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

    $.each(gItemGroupList, function(i, obj) {
        $('#ItemGroup').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
        $('#item-group').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
    });

    //$('#item-group').val(gUserCountryId);
    //$('#item-group').val(gUserCountryId);

    $("#ItemGroup").change(function() {
		onComboFundingSource();
	});
	
	onListPanel();
	resetForm("agreement_form");

	$('.btn-form-success').click(function() {
		$("#agreement_form").submit();
	});
	
    $('#item-group').change(function() {
        agreementTable.fnDraw();
    });
	
	agreementTable = $('#agreementTable').dataTable({
		"bFilter" : true,		
		"bSort" : true,
		"bInfo" : true,
		"bPaginate" : true,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : true,
		"aaSorting" : [[5, 'asc'],[3, 'asc'],[4, 'asc']],
		"aLengthMenu" : [[25, 50, 100], [25, 50, 100]],
		"iDisplayLength" : 25,
		"sPaginationType" : "full_numbers",
		//"sScrollX": "100%",
		"sAjaxSource" : baseUrl + "stage_one_datasourse.php",
		"fnDrawCallback" : function(oSettings) {

			if (oSettings.aiDisplay.length == 0) {
				return;
			}
			var nTrs = $('#agreementTable tbody tr');
			var iColspan = nTrs[0].getElementsByTagName('td').length;
			var sLastGroup = "";
			for (var i = 0; i < nTrs.length; i++) {
				var iDisplayIndex = i;
				var sGroup = oSettings.aoData[oSettings.aiDisplay[iDisplayIndex]]._aData[2];
				if (sGroup != sLastGroup) {
					var nGroup = document.createElement('tr');
					var nCell = document.createElement('td');
					nCell.colSpan = iColspan;
					nCell.className = "group";
					nCell.innerHTML = sGroup;
					nGroup.appendChild(nCell);
					nTrs[i].parentNode.insertBefore(nGroup, nTrs[i]);
					sLastGroup = sGroup;
				}
			}

			$('a.itmEdit', agreementTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = agreementTable.fnGetData(nTr);
                    RecordId = aData[0];
                    $('#RecordId').val(aData[0]);
                    //$('#FundingSourceId').val(aData[6]);
                    $('#AgreementName').val(aData[4]);
                    $('#ItemGroup').val(aData[7]);
                    onComboFundingSource();
                    FundingSourceId=aData[6];
					msg = "Do you really want to edit this record?";
					onCustomModal(msg, "onEditPanel");
				});
			});
			$('a.itmDrop', agreementTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = agreementTable.fnGetData(nTr);
					RecordId = aData[0];
					msg = "Do you really want to delete this record?";
					//alert(RecordId);
					onCustomModal(msg, "onConfirmWhenDelete");
				});
			});
			$('a.itmMore', agreementTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					if ($(this).children('span').attr('class') == 'label label-info faminus') {
						$(this).children('span').attr('class', 'label label-info');
						var nRemove = $(nTr).next()[0];
						nRemove.parentNode.removeChild(nRemove);
					} else {
						$(this).children('span').attr('class', 'label label-info faminus');
						agreementTable.fnOpen(nTr, fnFormatDetails(nTr), 'details');
					}
				});
			});
		},
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "action",
				"value" : 'getAgreementData'
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
                "name": "itemGroupId",
                "value": $('#item-group').val()
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
			"bVisible" : false
		}, {
			"sClass" : "center-aln",
			// SL#
			"sWidth": "5%",
			"bSortable": false
		}, {
			"sClass" : "left-aln",
			// Product Group
			"sWidth": "10%",
			"bVisible": false
		}, {
			"sClass" : "left-aln",
			// Funding Source
			"sWidth": "40%",
			"bSortable" : true
		}, {
			"sClass" : "left-aln",
			// Agreement Name
			"sWidth": "45%",
			"bSortable" : true
		}, {
			"sClass" : "center-aln",
			// Action
			"sWidth": "10%",
			"bSortable": false
		}]
	});

	onComboFundingSource();

}); 