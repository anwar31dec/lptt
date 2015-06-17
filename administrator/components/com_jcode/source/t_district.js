
var DistrictTable;
var RecordId = '';
var Country = '';
var Facility = '';
var CountryId = '';
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
	resetForm("t_district_form");
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

function onComboRegionList() {
    $.getJSON(baseUrl + "t_combo.php", {
        action: 'getRegionList',
        CountryId: CountryId
    }, function(response) {

        var regionList = response || [];
        var html2 = $.map(regionList, function(obj) {
            return '<option value=' + obj.RegionId + '>' + obj.RegionName + '</option>';
        }).join('');

        $('#RegionId').html(html2);

        regionList.unshift({
            "RegionId": "",
            "RegionName": "All"
        });

        var html1 = $.map(regionList, function(obj) {
            return '<option value=' + obj.RegionId + '>' + obj.RegionName + '</option>';
        }).join('');
        $('#ARegionId').html(html1);
    });
}

$('#ARegionId').change(function() {
    DistrictTable.fnDraw();
});


function onConfirmWhenAddEdit() {
	$.ajax({
		"type" : "POST",
		"url" : baseUrl + "stage_one_datasourse.php",
		"data" : $('#t_district_form').serialize() + '&jUserId=' + userId + '&language=' + engbId,
		"success" : function(response) {
			$msgType = JSON.parse(response)['msgType'];
			$msg = JSON.parse(response)['msg'];
			if ($msgType == "success") {
				DistrictTable.fnDraw();
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
		"data" : 'action=deleteDistrictData&RecordId=' + RecordId + '&jUserId=' + userId + '&language=' + engbId,
		"success" : function(response) {
			$msgType = JSON.parse(response)['msgType'];
			$msg = JSON.parse(response)['msg'];
			if ($msgType == "success") {
				DistrictTable.fnDraw();
				onSuccessMsg($msg);
			} else {
				onErrorMsg($msg);
			}
		}
	});
}


jQuery('#t_district_form').parsley({
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
	resetForm("t_district_form");

	$('#submitItemList').click(function() {
		$("#t_district_form").submit();
	});	
	
	//console.log(gMonthList);

    $.each(gCountryList, function(i, obj) {
        $('#country-list').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
        $('#CountryId').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
    });

    $('#country-list').val(gUserCountryId);
    $('#CountryId').val(gUserCountryId);
    CountryId = $('#country-list').val();
      
    $('#country-list').change(function() {
        CountryId = $('#country-list').val();
        onComboRegionList();
        DistrictTable.fnDraw();
    });
    
     $('#CountryId').change(function() {
        CountryId = $('#CountryId').val();
        onComboRegionList();
    });
	
	DistrictTable = $('#DistrictTable').dataTable({
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
			
			$('a.itmEdit', DistrictTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = DistrictTable.fnGetData(nTr);
                        RecordId = aData[0];
                        $('#RecordId').val(aData[0]);
                        $('#DistrictName').val(aData[2]);
                        $('#CountryId').val(aData[3]);
                        $('#RegionId').val(aData[4]);
					msg = "Do you really want to edit this record?";
					onCustomModal(msg, "onEditPanel");
				});
			});
			$('a.itmDrop', DistrictTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = DistrictTable.fnGetData(nTr);
					RecordId = aData[0];
					msg = "Do you really want to delete this record?";
					//alert(RecordId);
					onCustomModal(msg, "onConfirmWhenDelete");
				});
			});
			$('a.itmMore', DistrictTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					if ($(this).children('span').attr('class') == 'label label-info faminus') {
						$(this).children('span').attr('class', 'label label-info');
						var nRemove = $(nTr).next()[0];
						nRemove.parentNode.removeChild(nRemove);
					} else {
						$(this).children('span').attr('class', 'label label-info faminus');
						DistrictTable.fnOpen(nTr, fnFormatDetails(nTr), 'details');
					}
				});
			});
		},
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "action",
				"value" : 'getDistrictData'
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
				"value": $('#country-list').val()
			});
			aoData.push({
				"name": "ARegionId",
				"value": $('#ARegionId').val()
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
			"sWidth": "8%",
			"bSortable": false
		},{
			"sClass": "left-aln",
			//DistrictName
			"sWidth": "75%",
			"bSortable": false
		},{
			"sClass": "CountryId",
			"sWidth": "10%",
			"bSortable": false,
			"bVisible": false
		},{
			"sClass": "RegionId",
			"sWidth": "9%",
			"bSortable": false,
			"bVisible": false
		},{
			"sClass": "center-aln",
			"sWidth": "15%",
			"bSortable": false
		}]
	});
onComboRegionList();
}); 