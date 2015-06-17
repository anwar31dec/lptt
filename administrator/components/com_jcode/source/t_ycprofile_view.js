var profileTable;
var ycRegimenPatient;
var defaultYear;
var yearList;
var gYearId;
var gCountryId;
var RequirementYear;
var plegedFundingTable;
var currentFlag = new Array();
var currentId = new Array();
var currentCountry = new Array();
var initCountryId = 0;
var endDate = new Date();
var $ = jQuery.noConflict();
function formatNumber(n) {
	return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function onFlagUpdate(gCountryId) {
	match = 0;
	for (var i = 0; i < currentId.length; i++) {
		if (gCountryId == currentId[i]) {
			match = i;
			break;
		} else {
			match = 0;
		}
	}
	if (match >= 0) {
		$('.country-flag img').attr('src', baseUrl + 'flag/' + currentFlag[match]);
		$('.country-flag img').attr('alt', currentCountry[match]);
	} else
	$('.country-flag img').attr('src', baseUrl + 'dashboard/images/flag-benin.jpg');
}

function onRequirementYear(localData) {
	RequirementYear = localData;
	onPledgedFundingDraw();
}

function onPledgedFundingDraw() {
	if (RequirementYear == 1) {
		$('.pf:eq(0)').attr('class', 'btn btn-info btn-sm pf');
		$('.pf:eq(1)').attr('class', 'btn btn- btn-sm pf');
		$('.pf:eq(2)').attr('class', 'btn btn- btn-sm pf');
	} else if (RequirementYear == 2) {
		$('.pf:eq(0)').attr('class', 'btn btn- btn-sm pf');
		$('.pf:eq(1)').attr('class', 'btn btn-info btn-sm pf');
		$('.pf:eq(2)').attr('class', 'btn btn- btn-sm pf');
	} else if (RequirementYear == 3) {
		$('.pf:eq(0)').attr('class', 'btn btn- btn-sm pf');
		$('.pf:eq(1)').attr('class', 'btn btn- btn-sm pf');
		$('.pf:eq(2)').attr('class', 'btn btn-info btn-sm pf');
	}
	cYear = $('#year-list option[value=' + $('#year-list').val() + ']').text();
	//console.log(RequirementYear);
	cYear = parseInt(cYear);
	$('.pf:eq(0)').html(cYear);
	$('.pf:eq(1)').html(cYear + 1);
	$('.pf:eq(2)').html(cYear + 2);

	$('#tbl-pf').html('');
	html = '<table class="table table-hover table-striped" id="tbl-pledgedfundings">';
	html += '<thead></thead>';
	html += '<tbody></tbody>';
	html += '</table>';
	$('#tbl-pf').html(html);
	$.ajax({
		type: "POST",
		url: baseUrl + "t_ycprofile_view_server.php",
		data: {
			"operation": 'getYcPledgedFunding',
			'RequirementYear': RequirementYear,
			"country": $('#CountryName').val(),
			"year": $('#year-list option[value=' + $('#year-list').val() + ']').text(),
			"ItemGroupId": $('#item-group').val(),
			lan:lan
		},

		success: function(results) {
			results = $.parseJSON(results);
			TotalColumn = results.COLUMNS.length;
			plegedFundingTable = $('#tbl-pledgedfundings').dataTable({
				"bFilter": false,
				"bJQueryUI": true,
				"bSort": false,
				"bInfo": false,
				"bPaginate": false,
				"bSortClasses": false,
				"bProcessing": true,
				"bServerSide": true,

				// 
				"sPaginationType": "full_numbers",
				"sAjaxSource": baseUrl + "t_ycprofile_view_server.php",
				"fnDrawCallback": function(oSettings) {
					if (oSettings.aiDisplay.length == 0) {
						return;
					}
					var nTrs = $('#tbl-pledgedfundings tbody tr');
					var iColspan = nTrs[0].getElementsByTagName('td').length;
					var sLastGroup = "";
					for (var i = 0, k = 0; i < nTrs.length; i++) {
						var iDisplayIndex = i;
						var sGroup = oSettings.aoData[oSettings.aiDisplay[iDisplayIndex]]._aData[1];
						if (sGroup != sLastGroup) {
							var nGroup = document.createElement('tr');
							var nCell = document.createElement('td');
							nCell.colSpan = iColspan;
							nCell.className = "group";
							nCell.innerHTML = sGroup;
							nGroup.appendChild(nCell);
							nTrs[i].parentNode.insertBefore(nGroup, nTrs[i]);
							sLastGroup = sGroup;
							k++;
						}
					}
					var sLastGroup = "";
					for (var i = 0, k = 0; i < nTrs.length; i++) {
						var iDisplayIndex = i;
						data = oSettings.aoData[oSettings.aiDisplay[iDisplayIndex]]._aData;
						if (data[0] == data[1] + ' Total') {
							ln = $('#tbl-pledgedfundings tr:eq(' + (i + (k + 1) + 1) + ') td').length;
							for (m = 0; m < ln; m++) {
								myClass = $('#tbl-pledgedfundings tr:eq(' + (i + (k + 1) + 1) + ') td:eq(' + m + ')').attr('class');
								$('#tbl-pledgedfundings tr:eq(' + (i + (k + 1) + 1) + ') td:eq(' + m + ')').attr('class', myClass + ' groupTotal');
							}
							ln = $('#tbl-pledgedfundings tr:eq(' + (i + (k + 1) + 1) + ') td:eq(0)').attr('colspan', 2);
							ln = $('#tbl-pledgedfundings tr:eq(' + (i + (k + 1) + 1) + ') td:eq(2)').remove();
							k++;
						} else if (data[0] == 'Grand Total') {
							ln = $('#tbl-pledgedfundings tr:eq(' + (i + k + 1) + ') td').length;
							for (m = 0; m < ln; m++) {
								myClass = $('#tbl-pledgedfundings tr:eq(' + (i + k + 1) + ') td:eq(' + m + ')').attr('class');
								$('#tbl-pledgedfundings tr:eq(' + (i + k + 1) + ') td:eq(' + m + ')').attr('class', myClass + ' supergroupTotal');
							}
							ln = $('#tbl-pledgedfundings tr:eq(' + (i + k + 1) + ') td:eq(0)').attr('colspan', 2);
							ln = $('#tbl-pledgedfundings tr:eq(' + (i + k + 1) + ') td:eq(2)').remove();
							k++;
						}
					}

				},
				"fnServerData": function(sSource, aoData, fnCallback) {
					aoData.push({
						"name": "operation",
						"value": 'getYcPledgedFunding'
					});
					aoData.push({
						"name": "lan",
						"value": lan
					});
					aoData.push({
						"name": "baseUrl",
						"value": baseUrl
					});

					aoData.push({
						"name": "country",
						"value": $('#CountryName').val()
					});
					aoData.push({
						"name": "ItemGroupId",
						"value": $('#item-group').val()
					});
					
					aoData.push({
						"name": "year",
						"value": $('#year-list option[value=' + $('#year-list').val() + ']').text()
					});
					aoData.push({
						"name": "RequirementYear",
						"value": RequirementYear
					});
					
			
			
					$.ajax({
						"dataType": 'json',
						"type": "POST",
						"url": sSource,
						"data": aoData,
						"success": function(json) {
							fnCallback(json);
							$('.hideme').hide();
/*
							 ln=$('#tbl-pledgedfundings thead th').length;
							 console.log(ln);
							 for(m=0;m<ln;m++){
							 //$('#tbl-pledgedfundings thead th:eq('+m+')').attr('style','');
							 }	*/
						}
					});
					// end of $.ajax()
				},
				"aoColumns": results.COLUMNS
				//"aoColumns": [{ "sTitle": "Full Name", "mDataProp": "FullName" }, { "sTitle": "UserName", "mDataProp": "UserName" }, { "sTitle": "Email", "mDataProp": "Email" }, { "sTitle": "Role", "mDataProp": "RoleName"}]

			});
			//$('.hideme').hide();
		}
	});
}

var initCountry;
var vIsoCountryList;
$(function() {
	//alert(123546);
	gYearId = objInit.initialYear;

	$.each(gYearList, function(i, obj) {
		$('#year-list').append($('<option></option>').val(obj.YearName).html(obj.YearName));
		$('#year-list').val(objInit.initialYear-1);
		cYear = $("#year-list").val();
		cYear = parseInt(cYear);
		$('#cYear').html(cYear);
		$('#nYear').html(cYear + 1);
		$('#nnYear').html(cYear + 2);
	});

	$.each(gCountryListISO3Chain, function(i, obj) {
		$('#CountryName').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
		currentId.push(obj.CountryId);
		currentCountry.push(obj.CountryName);
		currentFlag.push(obj.ISO3 + '_flag.png');
		
	});
	
	$('#CountryName').val(gUserCountryId);
	getSelectZoomCountry($("#CountryName").val());
	//$.each(gItemGroupList, function(i, obj) {
	//	$('#item-group').append($('<option value="'+obj.ItemGroupId+'" bPatientInfo="'+obj.bPatientInfo+'"> '+obj.GroupName+' </option>'));
	//});	
	$.each(gItemGroupList, function(i, obj) {
		$('#item-group').append($('<option value="'+obj.ItemGroupId+'" bPatientInfo="'+obj.bPatientInfo+'"> '+obj.GroupName+' </option>'));
	});
	
	$('#item-group').val(gUserItemGroupId);
	
	endDate.setMonth(objInit.initialMonth - 1);
	endDate.setFullYear(objInit.initialYear);

	$("#left-arrow").click(function() {
		if (endDate.getFullYear() <= 2013) return;

		tmpMonthId = 0;
		endDate.getFullYear($("#year-list").val())
		endDate.setMonth(tmpMonthId);
		endDate.prevMonth();

		$("#year-list").val(endDate.getFullYear());

		gYearId = $("#year-list").val();
		gCountryId = $('#CountryName').val();
		profileTable.fnDraw();
		ycRegimenPatient.fnDraw();
		ycFundingSource.fnDraw();
		onPledgedFundingDraw();
		cYear = $("#year-list").val();
		cYear = parseInt(cYear);
		$('#cYear').html(cYear);
		$('#nYear').html(cYear + 1);
		$('#nnYear').html(cYear + 2);
	});

	$("#right-arrow").click(function() {
		tmpMonthId = 11;
		endDate.getFullYear($("#year-list").val())
		endDate.setMonth(tmpMonthId);
		endDate.nextMonth();

		$("#year-list").val(endDate.getFullYear());

		gYearId = $("#year-list").val();
		profileTable.fnDraw();
		ycRegimenPatient.fnDraw();
		ycFundingSource.fnDraw();
		onPledgedFundingDraw();
		cYear = $("#year-list").val();
		cYear = parseInt(cYear);
		$('#cYear').html(cYear);
		$('#nYear').html(cYear + 1);
		$('#nnYear').html(cYear + 2);
	});

	$("#year-list").change(function() {
		gYearId = $("#year-list").val();
		profileTable.fnDraw();
		ycRegimenPatient.fnDraw();
		ycFundingSource.fnDraw();
		onPledgedFundingDraw();
		cYear = $("#year-list").val();
		cYear = parseInt(cYear);
		$('#cYear').html(cYear);
		$('#nYear').html(cYear + 1);
		$('#nnYear').html(cYear + 2);
	});

	$("#CountryName").change(function() {
		gCountryId = $('#CountryName').val();
		profileTable.fnDraw();
		ycRegimenPatient.fnDraw();
		ycFundingSource.fnDraw();
		onPledgedFundingDraw();
		cYear = $("#year-list").val();
		cYear = parseInt(cYear);
		$('#cYear').html(cYear);
		$('#nYear').html(cYear + 1);
		$('#nnYear').html(cYear + 2);
		gCountryId = $("#CountryName").val();
		onFlagUpdate(gCountryId);
		//alert($("#CountryName").val());
		getSelectZoomCountry($("#CountryName").val());
	});
	$("#item-group").change(function() {
			profileTable.fnDraw();
			ycRegimenPatient.fnDraw();
			ycFundingSource.fnDraw();
			onPledgedFundingDraw();
			cYear = $("#year-list").val();
			cYear = parseInt(cYear);
			$('#cYear').html(cYear);
			$('#nYear').html(cYear + 1);
			$('#nnYear').html(cYear + 2);
			gCountryId = $("#CountryName").val();
			onFlagUpdate(gCountryId);
			//alert($("#CountryName").val());
			getSelectZoomCountry($("#CountryName").val());
			bShowDiv();
		});
		
	bShowDiv();	
	$("#table-div").hide();
	$("#save-div").hide();

	gCountryId = $("#CountryName").val();
	onFlagUpdate(gCountryId);
	profileTable = $('#tbl-country-profile').dataTable({
		"bFilter": false,
		"bJQueryUI": false,
		"bSort": false,
		"bInfo": false,
		"bPaginate": false,
		"bSortClasses": false,
		"bProcessing": true,
		"bServerSide": true,
		"aaSorting": [
			[0, 'asc']
		],
		"sPaginationType": "full_numbers",
		"aLengthMenu": [
			[50, 100, 200],
			[50, 100, 200]
		],
		"iDisplayLength": 50,
		"sAjaxSource": baseUrl + "t_ycprofile_view_server.php",
		"fnServerData": function(sSource, aoData, fnCallback) {
			aoData.push({
				"name": "operation",
				"value": 'getCountryProfileParams'
			});
			aoData.push({
				"name": "lan",
				"value": lan
			});
			aoData.push({
				"name": "baseUrl",
				"value": baseUrl
			});


			aoData.push({
				"name": "CountryId",
				"value": $('#CountryName').val()
			});
			aoData.push({
				"name": "Year",
				"value": $('#year-list option[value=' + $('#year-list').val() + ']').text()
			});			
			aoData.push({
				"name": "ItemGroupId",
				"value": $('#item-group').val()
			});
			
			$.ajax({
				"dataType": 'json',
				"type": "GET",
				"url": sSource,
				"data": aoData,
				"success": fnCallback
			});
		},
		"aoColumns": [{
			"sClass": "center-aln",
			"sWidth": "15%",
			"bSortable": false,
			"bSearchable": false
		},
		{
			"sClass": "left-aln",
			"bSortable": false,
			"sWidth": "70%"
		},
		{
			"sClass": "right-aln",
			"sWidth": "15%",
			"bSortable": false,
			//fnRender: function(oDt) {
			//	return formatNumber(oDt.aData[2]);
			//}
		}]
	});
/*
	ycRegimenPatient = $('#tblycregimenpatient').dataTable({
		"bFilter": false,
		"bJQueryUI": false,
		"bSort": true,
		"bInfo": false,
		"bPaginate": false,
		"bSortClasses": false,
		"bProcessing": true,
		"bServerSide": true,
		"aaSorting": [
			[3, 'asc']
		],
		"sPaginationType": "full_numbers",
		"aLengthMenu": [
			[50, 100, 200],
			[50, 100, 200]
		],
		"iDisplayLength": 50,
		"sAjaxSource": baseUrl + "t_ycprofile_view_server.php",
		"fnDrawCallback": function(oSettings) {
			if (oSettings.aiDisplay.length == 0) {
				return;
			}
			
			
			var nTrs = $('#tblycregimenpatient tbody tr');
			var iColspan = nTrs[0].getElementsByTagName('td').length;
			var sLastGroup = "";
			for (var i = 0; i < nTrs.length; i++) {
				var iDisplayIndex = i;
				var sGroup = oSettings.aoData[oSettings.aiDisplay[iDisplayIndex]]._aData[3];
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
		},

		"fnServerData": function(sSource, aoData, fnCallback) {
			aoData.push({
				"name": "operation",
				"value": 'getYcRegimenPatient'
			});
			aoData.push({
				"name": "lan",
				"value": lan
			});
			aoData.push({
				"name": "baseUrl",
				"value": baseUrl
			});

			aoData.push({
				"name": "CountryId",
				"value": $('#CountryName').val()
			});
			aoData.push({
				"name": "Year",
				"value": $('#year-list option[value=' + $('#year-list').val() + ']').text()
			});
			$.ajax({
				"dataType": 'json',
				"type": "GET",
				"url": sSource,
				"data": aoData,
				"success": fnCallback
			});
		},
		"aoColumns": [{
			"sClass": "center-aln",
			"bSortable": false
		},
		{
			"sClass": "left-aln",
			"bSortable": false
		},
		{
			"sClass": "right-aln",
			"bSortable": false,
			fnRender: function(oDt) {
				return formatNumber(oDt.aData[2]);
			}
		},
		{
			"sClass": "left-aln",
			"bSortable": true,
			"bVisible": false
		}]
	});
*/
// regements table
	ycRegimenPatient = $('#tblycregimenpatient').dataTable({
		"bFilter" : false,
		"bJQueryUI" : false,
		"bSort" : true,
		"bInfo" : false,
		"bPaginate" : false,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : true,
		"aaSorting" : [[3, 'asc']],
		"sPaginationType" : "full_numbers",
		"aLengthMenu" : [[50, 100, 200], [50, 100, 200]],
		"iDisplayLength" : 50,
		"sAjaxSource" : baseUrl + "t_ycprofile_view_server.php",
		"fnDrawCallback" : function(oSettings) {
			// edit cell
			$('td input.datacell', ycRegimenPatient.fnGetNodes()).each(function() {
				$(this).change(function() {
				/*
					var nTr = this.parentNode.parentNode;
					var aData = ycRegimenPatient.fnGetData(nTr);
					//var RecordId = aData[1];
					
					var RecordId = $(this).attr('id');
					//alert(id);
					
					var value = $(this).val().trim();
					if (isNaN(value)) {
					} else {
						value = addCommas(value);
					}
					$.ajax({
						"type" : "POST",
						"url" : baseUrl + "stage_two_datasource.php",
						"data" : {
							action : 'updateYcRegimenData',
							YearlyRegPatientId : RecordId,
							Pvalue : value
						},
						"success" : function(response) {
					//	alert(response);
							if (response == 1) {
								//ycRegimenPatient.fnDraw();
								//msg = "Profile parameter value updated successfully.";
								//onSuccessMsg(msg);
							} else {
								msg = "Server processing Error.";
								onErrorMsg(msg);
							}
						}
					});*/
				});
			});
			// grouping
			/*
			if (oSettings.aiDisplay.length == 0) {
				return;
			}
			var nTrs = $('#tblycregimenpatient tbody tr');
			var iColspan = nTrs[0].getElementsByTagName('td').length;
			var sLastGroup = "";
			for (var i = 0; i < nTrs.length; i++) {
				var iDisplayIndex = i;
				var sGroup = oSettings.aoData[oSettings.aiDisplay[iDisplayIndex]]._aData[3];
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
			*/
		},

		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "operation",
				"value" : 'getYcRegimenPatient'
			});
			aoData.push({
						"name": "lan",
						"value": lan
					});
			aoData.push({
				"name" : "country",
				"value" : $('#CountryName').val()
			});
			aoData.push({
				"name" : "year",
				"value" : $('#year-list option[value=' + $('#year-list').val() + ']').text()
			});
			aoData.push({
				"name" : "ItemGroupId",
				"value" : $('#item-group').val()
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
			"bSortable" : false
		}, {
			"sClass" : "left-aln",
			"bSortable" : false,
			"bVisible" : true,
			"sWidth" : "30%"
		}, {
			"sClass" : "right-aln",
			"bSortable" : false,
			"sWidth" : "15%"
		}, {
			"sClass" : "right-aln",
			"bSortable" : false,
			"bVisible" : true,
			"sWidth" : "15%"
		}, {
			"sClass" : "right-aln",
			"bSortable" : false,
			"bVisible" : true,
			"sWidth" : "15%"
		}, {
			"sClass" : "right-aln",
			"bSortable" : false,
			"sWidth" : "15%"
		}]
	});
	
	
	ycFundingSource = $('#tbl-yc-funding-requirement').dataTable({
		"bFilter": false,
		"bJQueryUI": false,
		"bSort": true,
		"bInfo": false,
		"bPaginate": false,
		"bSortClasses": false,
		"bProcessing": true,
		"bServerSide": true,
		"aaSorting": [
			[1, 'asc']
		],
		"sPaginationType": "full_numbers",
		"aLengthMenu": [
			[50, 100, 200],
			[50, 100, 200]
		],
		"iDisplayLength": 50,
		"sAjaxSource": baseUrl + "t_ycprofile_view_server.php",
		"fnDrawCallback": function(oSettings) {
			if (oSettings.aiDisplay.length == 0) {
				return;
			}
			var nTrs = $('#tbl-yc-funding-requirement tbody tr');
			var iColspan = nTrs[0].getElementsByTagName('td').length;
			var sLastGroup = "";
			for (var i = 0; i < nTrs.length; i++) {
				var iDisplayIndex = i;
				var sGroup = oSettings.aoData[oSettings.aiDisplay[iDisplayIndex]]._aData[1];
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
		},

		"fnServerData": function(sSource, aoData, fnCallback) {
			aoData.push({
				"name": "operation",
				"value": 'getYcFundingSource'
			});
			aoData.push({
				"name": "lan",
				"value": lan
			});
			aoData.push({
				"name": "baseUrl",
				"value": baseUrl
			});
			aoData.push({
				"name": "CountryId",
				"value": $('#CountryName').val()
			});
			aoData.push({
				"name": "Year",
				"value": $('#year-list option[value=' + $('#year-list').val() + ']').text()
			});
			aoData.push({
				"name": "ItemGroupId",
				"value": $('#item-group').val()
			});
			$.ajax({
				"dataType": 'json',
				"type": "GET",
				"url": sSource,
				"data": aoData,
				"success": fnCallback
			});
		},
		"aoColumns": [{
			"sClass": "center-aln",
			"bSortable": false
		},
		{
			"bVisible": false,
		},
		{},
		{
			"sClass": "right-aln",
			"bSortable": false,
			fnRender: function(oDt) {
				return formatNumber(oDt.aData[3]);
			}
		},
		{
			"sClass": "right-aln",
			"bSortable": false,
			fnRender: function(oDt) {
				return formatNumber(oDt.aData[4]);
			}
		},
		{
			"sClass": "right-aln",
			"bSortable": false,
			fnRender: function(oDt) {
				return formatNumber(oDt.aData[5]);
			}
		},
		{
			"sClass": "right-aln",
			"bSortable": true,
			fnRender: function(oDt) {
				return formatNumber(oDt.aData[6]);
			}
		}]
	});
	RequirementYear = 1;
	onPledgedFundingDraw();

});


function bShowDiv(){
			var select = document.getElementById('item-group');  
            var opt = $(select.options[select.selectedIndex]);
            //var name = opt.attr('data-name');
             bPatientInfo = opt.attr('bPatientInfo'); //alert(bPatientInfo);

            if( bPatientInfo == 1){
				$('#MalariaCases').show();
				//wizardProgresspercent = 20;
            }
            else{
               $('#MalariaCases').hide();
			   //wizardProgresspercent = 25;
            }
}
