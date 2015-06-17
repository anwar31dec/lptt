var rptFrequencyTable;
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
	resetForm("region_form");
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

function onComboYearList() {
    $.getJSON(baseUrl + "t_combo.php", {
        action: 'getYearList'
    }, function(response) {
        str = '<option value="">Select Year</option>';
        for (var i = 0; i < response.length; i++) {
            str += '<option value="' + response[i].YearName + '">' + response[i].YearName + '</option>';
        }
        $('#StartYearId').html(str);
    });
}

function onComboMonthList() {
    $.getJSON(baseUrl + "t_combo.php", {
        action: 'getQuadMonthList',
        FrequencyId: $('#FrequencyId').val()

    }, function(response) {
        str = '<option value="">Select Month</option>';
        for (var i = 0; i < response.length; i++) {
            str += '<option value="' + response[i].MonthId + '">' + response[i].MonthName + '</option>';
        }
        $('#StartMonthId').html(str);
    });
}


function onComboFrequencyList() {
    $.getJSON(baseUrl + "t_combo.php", {
        action: 'getFrequencyList'
    }, function(response) {
        str = '<option value="">Select Frequency</option>';
        for (var i = 0; i < response.length; i++) {
            str += '<option value="' + response[i].FrequencyId + '">' + response[i].FrequencyName + '</option>';
        }
        $('#FrequencyId').html(str);
    });
}


function onComboItemGroupList() {
    $.getJSON(baseUrl + "t_combo.php", {
        action: 'getItemGroup'
    }, function(response) {
        str = '<option value="">Select Product Group</option>';
        for (var i = 0; i < response.length; i++) {
            str += '<option value="' + response[i].ItemGroupId + '">' + response[i].GroupName + '</option>';
        }
        $('#ItemGroupId').html(str);
    });
}

function onConfirmWhenAddEdit() {
	$.ajax({
		"type" : "POST",
		"url" : baseUrl + "t_reportingfrequency_server.php",
		"data" : $('#region_form').serialize() + '&jUserId=' + userId + '&language=' + engbId,
		"success" : function(response) {
			$msgType = JSON.parse(response)['msgType'];
			$msg = JSON.parse(response)['msg'];
			if ($msgType == "success") {
				rptFrequencyTable.fnDraw();
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
		"url" : baseUrl + "t_reportingfrequency_server.php",
		"data" : 'action=deleteFrequencyData&RecordId=' + RecordId + '&jUserId=' + userId + '&language=' + engbId,
		"success" : function(response) {
			$msgType = JSON.parse(response)['msgType'];
			$msg = JSON.parse(response)['msg'];
			if ($msgType == "success") {
				rptFrequencyTable.fnDraw();
				onSuccessMsg($msg);
			} else {
				onErrorMsg($msg);
			}
		}
	});
}


jQuery('#region_form').parsley({
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

    onComboYearList();
    onComboFrequencyList();
    onComboMonthList();
    onComboItemGroupList();	

	onListPanel();
	resetForm("region_form");

	$('#submitItemList').click(function() {
		$("#region_form").submit();
	});	
	
	//console.log(gMonthList);

    $.each(gCountryList, function(i, obj) {
        $('#AllCountryId').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
        $('#CountryId').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
    });

    $('#AllCountryId').val(gUserCountryId);
    $('#CountryId').val(gUserCountryId);

    $('#AllCountryId').change(function() {
        rptFrequencyTable.fnDraw();
    });

    $('#FrequencyId').change(function() {
        onComboMonthList();
    });
	
	rptFrequencyTable = $('#rptFrequencyTable').dataTable({
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
		"sAjaxSource" : baseUrl + "t_reportingfrequency_server.php",
		"fnDrawCallback" : function(oSettings) {

			if (oSettings.aiDisplay.length == 0) {
				return;
			}
			
			$('a.itmEdit', rptFrequencyTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = rptFrequencyTable.fnGetData(nTr);
						$('#RecordId').val(aData[0]);
						$('#CountryId').val(aData[8]);
						$('#ItemGroupId').val(aData[9]);
						$('#FrequencyId').val(aData[10]);
						onComboMonthList();
						$("body").animate({opacity: 1}, 800, function() {
							// initialize
							$('#StartMonthId').val(aData[11]);
							$('#StartYearId').val(aData[12]);
						});
					msg = "Do you really want to edit this record?";
					onCustomModal(msg, "onEditPanel");
				});
			});
			$('a.itmDrop', rptFrequencyTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = rptFrequencyTable.fnGetData(nTr);
					RecordId = aData[0];
					msg = "Do you really want to delete this record?";
					//alert(RecordId);
					onCustomModal(msg, "onConfirmWhenDelete");
				});
			});
			$('a.itmMore', rptFrequencyTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					if ($(this).children('span').attr('class') == 'label label-info faminus') {
						$(this).children('span').attr('class', 'label label-info');
						var nRemove = $(nTr).next()[0];
						nRemove.parentNode.removeChild(nRemove);
					} else {
						$(this).children('span').attr('class', 'label label-info faminus');
						rptFrequencyTable.fnOpen(nTr, fnFormatDetails(nTr), 'details');
					}
				});
			});
		},
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "action",
				"value" : 'getrptFrequencyData'
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
                "name": "CountryId",
                "value": $('#AllCountryId').val()
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
                "sWidth": "8%",
                "bSortable": false
            }, {
				"sClass" : "left-aln",
                "sWidth": "25%",
                "bVisible": true
            }, {
				"sClass" : "left-aln",
                "sWidth": "15%",
                "bVisible": true
            }, {
				"sClass" : "center-aln",
                "sWidth": "18%",
                "bVisible": true
            }, {
				"sClass" : "center-aln",
                "sWidth": "10%",
                "bVisible": true
            }, {
				"sClass" : "center-aln",
                "sWidth": "10%",
                "bVisible": true
            }, {
				"sClass" : "center-aln",
                //Action
                "bSortable": false,
                "sWidth": "10%"
            }, {
                "bVisible": false
            }, {
                "bVisible": false
            }, {
                "bVisible": false
            }, {
                "bVisible": false
            }, {
                "bVisible": false
		}]
	});

}); 