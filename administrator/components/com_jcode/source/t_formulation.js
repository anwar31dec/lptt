var formulationTable;
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
	resetForm("formulation_form");
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

function onComboItemGroupName() {
    $.getJSON(baseUrl + "t_combo.php", {
        action: 'getItemGroup'
    }, function(response) {
        for (var i = 0; i < response.length; i++) {
            $('#ItemGroupId').append($('<option></option>').val(response[i].ItemGroupId).html(response[i].GroupName));
        }
    });
}

function onComboServiceName() {
    $.getJSON(baseUrl + "t_combo.php", {
        action: 'getServiceType'
    }, function(response) {
        for (var i = 0; i < response.length; i++) {
            $('#ServiceTypeId').append($('<option></option>').val(response[i].ServiceTypeId).html(response[i].ServiceTypeName));
        }
    });
}


function onConfirmWhenAddEdit() {
	$.ajax({
		"type" : "POST",
		"url" : baseUrl + "stage_two_datasource.php",
		"data" : $('#formulation_form').serialize() + '&jUserId=' + userId + '&language=' + engbId,
		"success" : function(response) {
			$msgType = JSON.parse(response)['msgType'];
			$msg = JSON.parse(response)['msg'];
			if ($msgType == "success") {
				formulationTable.fnDraw();
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
		"url" : baseUrl + "stage_two_datasource.php",
		"data" : 'action=deleteFormulationData&RecordId=' + RecordId + '&jUserId=' + userId + '&language=' + engbId,
		"success" : function(response) {
			$msgType = JSON.parse(response)['msgType'];
			$msg = JSON.parse(response)['msg'];
			if ($msgType == "success") {
				formulationTable.fnDraw();
				onSuccessMsg($msg);
			} else {
				onErrorMsg($msg);
			}
		}
	});
}


jQuery('#formulation_form').parsley({
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

    onComboItemGroupName();
    onComboServiceName();	

	onListPanel();
	resetForm("formulation_form");

	$('#submitItemList').click(function() {
		$("#formulation_form").submit();
	});	
	
	//console.log(gMonthList);

	$.each(gItemGroupList, function(i, obj) {
        $('#item-group').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
    });

    $('#item-group').val(gUserItemGroupId);

	$('#item-group').change(function() {
		formulationTable.fnDraw();
	});
	
	formulationTable = $('#formulationTable').dataTable({
		"bFilter" : true,		
		"bSort" : true,
		"bInfo" : true,
		"bPaginate" : true,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : true,
		"aaSorting" : [[4, 'asc'], [2, 'asc']],
		"aLengthMenu" : [[25, 50, 100], [25, 50, 100]],
		"iDisplayLength" : 25,
		"sPaginationType" : "full_numbers",
		//"sScrollX": "100%",
		"sAjaxSource" : baseUrl + "stage_two_datasource.php",
		"fnDrawCallback" : function(oSettings) {

			if (oSettings.aiDisplay.length == 0) {
				return;
			}
			
            var nTrs = $('#formulationTable tbody tr');
            var iColspan = nTrs[0].getElementsByTagName('td').length;
            var sLastGroup = "";
            for (var i = 0; i < nTrs.length; i++) {
                var iDisplayIndex = i;
                var sGroup = oSettings.aoData[oSettings.aiDisplay[iDisplayIndex]]._aData[5];
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
			
			$('a.itmEdit', formulationTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = formulationTable.fnGetData(nTr);
						RecordId = aData[0];
						$('#RecordId').val(aData[0]);
						$('#FormulationName').val(aData[2]);
						$('#FormulationNameFrench').val(aData[3]);
						$('#GroupName').val(aData[4]);
						$('#ServiceTypeName').val(aData[5]);
						$('#ColorCode').val(aData[6]);
						$('#ItemGroupId').val(aData[8]);
						$('#ServiceTypeId').val(aData[9]);
						$('#ColorCode').val(aData[10]);

					msg = "Do you really want to edit this record?";
					onCustomModal(msg, "onEditPanel");
				});
			});
			$('a.itmDrop', formulationTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = formulationTable.fnGetData(nTr);
					RecordId = aData[0];
					msg = "Do you really want to delete this record?";
					//alert(RecordId);
					onCustomModal(msg, "onConfirmWhenDelete");
				});
			});
			$('a.itmMore', formulationTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					if ($(this).children('span').attr('class') == 'label label-info faminus') {
						$(this).children('span').attr('class', 'label label-info');
						var nRemove = $(nTr).next()[0];
						nRemove.parentNode.removeChild(nRemove);
					} else {
						$(this).children('span').attr('class', 'label label-info faminus');
						formulationTable.fnOpen(nTr, fnFormatDetails(nTr), 'details');
					}
				});
			});
		},
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "action",
				"value" : 'getFormulationData'
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
			"sClass" : "center-aln",
			"bVisible" : false
		}, {
			"sClass" : "center-aln",
			// SL#
			"sWidth" : "5%",
			"bSortable" : false
		}, {
			"sClass" : "left-aln",
			// FormulationType
			"sWidth" : "30%",
			"bSortable" : true
		}, {
			"sClass" : "left-aln",
			// FormulationTypeFrench
			"sWidth" : "35%",
			"bSortable" : true
		}, {
			"sClass" : "left-aln",
			// ItemGroup
			"sWidth" : "10%",
			"bSortable" : true
		}, {
			"sClass" : "left-aln",
			// ServiceType
			"bSortable" : true,
			"bVisible": false
		}, {
			"sClass" : "left-aln",
			// ColorCode
			"sWidth" : "10%",
			"bSortable" : false
		}, {
			"sClass" : "center-aln",
			// Action
			"sWidth" : "10%",
			"bSortable" : false
		}]
	});

	$("#colorpicker3").colorpicker();
}); 