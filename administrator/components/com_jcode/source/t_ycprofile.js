var profileTable;
var defaultYear;
var ycFundingSourceAssign;
var ycRegimenPatient;
var ycFundingSource;
var currentStep_1;
var wizardProgresspercent = 20;
var RequirementYear;
var oYcpledgedfunding;
var bPatientInfo = 1;
var msg;


var $ = jQuery.noConflict();

function formatNumber(n) {
	return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	return n;
}
function addCommas(nStr) {
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

function removeCommas(nStr) {
	var temp = nStr.split(',');
	return temp.join('');
}

function onComboYearName() {
	/*
	 $.getJSON(baseUrl + "t_combo.php", {
	 action: 'getYear'
	 }, function(response) {
	 str = '';
	 for (var i = 0; i < response.length; i++) {
	 if (response[i].DefaultYear == 1)
	 defaultYear = response[i].YearID;
	 str += '<option value="'+response[i].YearID +'">' + response[i].YearName + '</option>';
	 }
	 $('#Year').html(str);
	 //$('#Year').val(defaultYear);
	 $('#Year').val(objInit.initialYear);
	 //alert(objInit.initialYear);
	 });*/
	 
	/* $.each(gCountryList, function(i, obj) {
		$('#CountryName').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
	});
	$('#CountryName').val(gUserCountryId);
	
	$.each(gItemGroupList, function(i, obj) {
		$('#item-group').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
	});	
	$('#item-group').val(gUserItemGroupId);
			
	$.each(gYearList, function(i, obj) {
		$('#Year').append($('<option></option>').val(obj.YearName).html(obj.YearName));
		$('#Year').val(objInit.initialYear);
	});
	*/
	//Onchange($('#CountryName').val());
}

function Onchange(value) {
	$('#CountryName').val(value);
	profileTable.fnDraw();
	ycRegimenPatient.fnDraw();
	ycFundingSourceAssign.fnDraw();
	ycFundingSource.fnDraw();
	onPledgedDynamicTable();
	$("#table-div").show();
	$("#save-div").show();
	cYear = $('#Year option[value=' + $('#Year').val() + ']').text();
	cYear = parseInt(cYear);
	$('.cYear').html(cYear);
	$('.nYear').html(cYear + 1);
	$('.nnYear').html(cYear + 2);
	if (value) {
		$('.country-form-wizard').show();
	} else {
		$('.country-form-wizard').hide();
	}
};
var dynamicColumnsAll = [];
var dynamicData = [];



function onPledgedDynamicTable() {
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
	
	cYear = $('#Year option[value=' + $('#Year').val() + ']').text();
	cYear = parseInt(cYear);
	$('.pf:eq(0)').html(cYear);
	$('.pf:eq(1)').html(cYear + 1);
	$('.pf:eq(2)').html(cYear + 2);
	/*$.ajax({
		"type" : "POST",
		"url" : baseUrl + "stage_two_datasource.php",
		"data" : {
			action : 'getPledgedFundingData',
			year : $('#Year option[value=' + $('#Year').val() + ']').text(),
			country : $('#CountryName').val(),
			RequirementYear : RequirementYear,
			ItemGroupId : $('#item-group').val()
		},
		"success" : function(response) {
			response = $.parseJSON(response);
			if (response) {
				dynamicColumns = response.dynamicColumns;
				dynamiccolWidths = response.dynamiccolWidths;
				dynamicData = response.dynamicData;
				dynamicColumnsAll = response.dynamicColumns;
				onTableStructure(dynamicColumns, dynamiccolWidths);
				$('.htCore tr td').attr('style', 'text-align:right');
			}
		}
	});	
	*/
	onPledgeFunding();
	
}
////////////////////////////////////new /////////last step/////

function onPledgeFunding() {	
	$('#tbl-block1').html('');	
	html = '<table class="table table-striped display" id="ycpledgedfunding" style="width:100%">';
	html += '<thead></thead>';
	html += '<tbody></tbody>';
	html += '</table>';
	$('#tbl-block1').html(html);	
	
	$.ajax({
		type : "POST",
		url : baseUrl + "stage_two_datasource.php",
		data : {
			action : 'getPledgedFundingData',			
			year : $('#Year option[value=' + $('#Year').val() + ']').text(),
			country : $('#CountryName').val(),
			RequirementYear : RequirementYear,
			ItemGroupId : $('#item-group').val(),
			lan:lan
		},

		success : function(results) {
			results = $.parseJSON(results);
			
			oYcpledgedfunding = $('#ycpledgedfunding').dataTable({
				"bFilter" : false,
				"bJQueryUI" : true,
				"bDestroy": true,
				"bSort" : false,
				"bInfo" : false,
				"bPaginate" : false,
				"bSortClasses" : false,
				"bProcessing" : true,
				"bServerSide" : true,
				"sPaginationType" : "full_numbers",
				"sAjaxSource" : baseUrl + "stage_two_datasource.php",
				"fnDrawCallback" : function(oSettings) {
					if (oSettings.aiDisplay.length == 0) {
						return;
					}
					/*
						$('a.itmEdit', oEnergyConsumptionDataTable.fnGetNodes()).each(function() {
								$(this).click(function() {
									var nTr = this.parentNode.parentNode;
									var aData = oEnergyConsumptionDataTable.fnGetData(nTr);
									RecordId = aData[0];
									//alert('hi ');
									ConsumptionMonthWisePopup();
									//$('#RecordId').val(aData[0]);
									//$('#YearName').val(aData[2]);                   	
									//msg = "Do you really want to edit this record?";
									//onCustomModal(msg, "onEditDetails");                  			
								});
							});
*/
							
					$('td input.datacell', oYcpledgedfunding.fnGetNodes()).each(function() {
						$(this).change(function() {
							var nTr = this.parentNode.parentNode;
							var aData = oYcpledgedfunding.fnGetData(nTr);
							//var RecordId = aData[0];
							
							var PledgedFundingId=$(this).attr('id');
							var value = $(this).val().trim();
							
						//	console.log($(this).attr('class').split(' ')[1]);
							var hasclass=$(this).attr('class').split(' ')[1];
							                   
							if (isNaN(value)){}else{value = value;}
							$.ajax({
								"type" : "POST",
								"url" : baseUrl + "stage_two_datasource.php",
								"data" : {
									  action: 'updatePledgedFundingData',
									  pPledgedFundingId: PledgedFundingId,
									  pRequirementYear:RequirementYear,
									  pValue:value,
									  language : lan,
									  jUserId : jUserId
								},
								"success": function(response) {
									response = response.split('*');
									$msgType = JSON.parse(response)['msgType'];
									$msg = JSON.parse(response)['msg'];
									if ($msgType == "success") {
										
										
										var idFunding = hasclass.split('_')[1];
										
										var sum = 0;						
										$("."+hasclass).each(function() {                                                                                    
											sum = sum+parseFloat($(this).val());                                            
										});//alert(sum);
										$("."+'yctotal_'+idFunding).val(sum.toFixed(1));
										
										var requirementQty = 0;
										$("."+'ycreq_'+idFunding).each(function() {                                                                                    
											requirementQty = parseFloat($(this).val());                                            
										});
										$("."+'ycgaporsurplus_'+idFunding).val((requirementQty-sum).toFixed(1));
										
										onSuccessMsg($msg);
										
										
									} else {
										onErrorMsg($msg);
									}
								}
							});
						});
					});
				},
				"fnServerData" : function(sSource, aoData, fnCallback) {
					aoData.push({
						"name" : "action",
						"value" : 'getPledgedFundingData'
					});
					aoData.push({
				           "name" : "lan",
				           "value" : lan
			            });
					aoData.push({
						"name" : "year",
						"value" : $('#Year option[value=' + $('#Year').val() + ']').text()
					});
		            aoData.push({
						"name" : "country",
						"value" : $('#CountryName').val()
					});		
					aoData.push({
						"name" : "RequirementYear",
						"value" : RequirementYear
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
						"success" : function(json) {
							fnCallback(json);						
						}
					});
					// end of $.ajax()
				},
				"aoColumns" : results.COLUMNS
				
			});
			
		}
	}); 
	$('body').animate({opacity:1},1200,function(){
        $('#ycpledgedfunding td:nth-last-child(1) input').prop('disabled', true);   
		$('#ycpledgedfunding td:nth-last-child(2) input').prop('disabled', true);   	
		$('#ycpledgedfunding td:nth-child(3) input').prop('disabled', true);  			
	});   
	 
}

function onPledgedFunding() {
	onPledgedDynamicTable();
}

$(function() {
	RequirementYear = 1;	
	//onComboYearName();
	 $.each(gCountryList, function(i, obj) {
		$('#CountryName').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
	});
	$('#CountryName').val(gUserCountryId);
	
//	$.each(gItemGroupList, function(i, obj) {
	//	$('#item-group').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
	//});	
	$.each(gItemGroupList, function(i, obj) {
		$('#item-group').append($('<option value="'+obj.ItemGroupId+'" bPatientInfo="'+obj.bPatientInfo+'"> '+obj.GroupName+' </option>'));
	});	
	
	$('#item-group').val(gUserItemGroupId);
			
	$.each(gYearList, function(i, obj) {
		$('#Year').append($('<option></option>').val(obj.YearName).html(obj.YearName));
		$('#Year').val(objInit.initialYear-1);
	});
	
	//onPledgedFunding();

	$('#Year').change(function() {
		Onchange($('#CountryName').val());
	});
	$('#CountryName').change(function() {
		Onchange($('#CountryName').val());
	});
	$('#item-group').change(function() {
		Onchange($('#CountryName').val());
		
		bShowDiv();
		/////////////////////////////////////////////////////////////////
		/*var select = document.getElementById('item-group');  
            var opt = $(select.options[select.selectedIndex]);
            //var name = opt.attr('data-name');
             var bPatientInfo = opt.attr('bPatientInfo'); //alert(bPatientInfo);

            if( bPatientInfo == 1){
				$('#step3').show();  
            }
            else{
               $('#step3').hide();
            }
			*/
			////////////////////////////////////////////////////////////
			
			
			
			
	});

	bShowDiv();

	profileTable = $('#profileTable').dataTable({
		"bFilter" : false,
		"bJQueryUI" : true,
		"bDestroy": true,
		"bSort" : true,
		"bInfo" : false,
		"bPaginate" : false,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : true,
		"aaSorting" : [[4, 'asc']],
		"sPaginationType" : "full_numbers",
		"aLengthMenu" : [[25, 50, 100], [25, 50, 100]],
		"iDisplayLength" : 25,
		"sAjaxSource" : baseUrl + "stage_two_datasource.php",
		"fnDrawCallback" : function(oSettings) {
			$('td input.datacell', profileTable.fnGetNodes()).each(function() {
				$(this).change(function() {
					var nTr = this.parentNode.parentNode;
					var aData = profileTable.fnGetData(nTr);
					var RecordId = aData[0];
					var value = $(this).val().trim();
					if (isNaN(value)) {
					} else {
						value = addCommas(value);
					}
					$.ajax({
						"type" : "POST",
						"url" : baseUrl + "stage_two_datasource.php",
						"data" : {
							action : 'updateYcProfileData',
							ProfileId : RecordId,
							Pvalue : value,
							language : lan,
							jUserId : jUserId
						},
						"success": function(response) {
							$msgType = JSON.parse(response)['msgType'];
							$msg = JSON.parse(response)['msg'];
							if ($msgType == "success") {
								onSuccessMsg($msg);
							} else {
								onErrorMsg($msg);
							}
						}
					});
				});
			});
			$('td input.items', profileTable.fnGetNodes()).each(function() {
				$(this).change(function() {
					var RecordId = $(this).attr('id');
					ln = $('#multiselect .items').length;
					value = '';
					for ( i = 0; i < ln; i++) {
						if ($('#multiselect .items:eq(' + i + ')').prop('checked') == true)
							value += $('#multiselect .items:eq(' + i + ')').val() + ',';
					}
					if (value.length > 0) {
						value = value.substr(0, value.length - 1);
					}
					//console.log(value);
					$.ajax({
						"type" : "POST",
						"url" : baseUrl + "stage_two_datasource.php",
						"data" : {
							action : 'updateYcProfileMultipleData',
							ProfileId : RecordId,
							Pvalue : value,
							year : $('#Year option[value=' + $('#Year').val() + ']').text(),
							country : $('#CountryName').val()
						},
						"success" : function(response) {
							if (response == 1) {
								profileTable.fnDraw();
								onPledgedDynamicTable();
								//msg = "Profile parameter value updated successfully.";
								//onSuccessMsg(msg);
							} else {
								msg = "Server processing Error.";
								onErrorMsg(msg);
							}
						}
					});
				});
			});
		},
		"fnServerData" : function(sSource, aoData, fnCallback) {		
			aoData.push({
				"name" : "action",
				"value" : 'getYcProfileData'
			});
			aoData.push({
				"name" : "country",
				"value" : $('#CountryName').val()
			});
			aoData.push({
				"name" : "lan",
				"value" : lan
			});
			aoData.push({
				"name" : "ItemGroupId",
				"value" : $('#item-group').val()
			});
			aoData.push({
				"name" : "year",
				"value" : $('#Year option[value=' + $('#Year').val() + ']').text()
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
			"sClass" : "SL",
			"sWidth" : "15%",
			"bSortable" : false
		}, {
			"sClass" : "left-aln",
			"bSortable" : false,
			"sWidth" : "70%"
		}, {
			"sClass" : "right-aln",
			"sWidth" : "10%",
			"bSortable" : false
		}, {
			"bVisible" : false
		}]
	});
	// Funding Source Assign table
	ycFundingSourceAssign = $("#tbl-yc-funding-source-assign").dataTable({
		"bFilter" : false,
		"bJQueryUI": true,
        "sPaginationType": "full_numbers",
		"bDestroy": true,
		"bSort": true,
		"bInfo": false,
		"bPaginate": false,
		"bSortClasses": false,
		"bProcessing": true,
		"bServerSide": true,
        // "aLengthMenu": [[25, 50, 100], [25, 50, 100]],
		// "iDisplayLength": 25,
		"sAjaxSource": baseUrl + "stage_two_datasource.php",
		"oLanguage": {
			"sLengthMenu": "Display _MENU_ Records",
			"sZeroRecords": "No Record Found",
			"sInfo": "Showing _START_ to _END_ of _TOTAL_ Records",
			"sInfoEmpty": "Showing 0 to 0 of 0 Records",
			"sInfoFiltered": "(filtered from _MAX_ total Records)"
		},
		"fnDrawCallback": function() {
		  $('td input.datacell', ycFundingSourceAssign.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = ycFundingSourceAssign.fnGetData(nTr);
					var checkcon = $(this).prop('checked');  
					//var ItemGroupMapId = aData[0];
					var YearlyFundingSrcId = aData[0];
                    //var ItemGroupId = aData[2];  
					var FundingSourceId = aData[2];  
					$.ajax({
						"type": "POST",
						"url": baseUrl + "stage_two_datasource.php",
						"data": {
						      action: 'updateYcProfileMultipleData',
                              //ItemGroupMapId: ItemGroupMapId,
							  YearlyFundingSrcId: YearlyFundingSrcId,
                              FundingSourceId: FundingSourceId,
                              userName: 'admin',
                              checkVal: checkcon,
							  year: $('#Year option[value=' + $('#Year').val() + ']').text(),
							  country : $('#CountryName').val(),
							  ItemGroupId : $('#item-group').val(),
							  language : lan,
							  jUserId : jUserId
				
                        },
						"success": function(response) {
							$msgType = JSON.parse(response)['msgType'];
							$msg = JSON.parse(response)['msg'];
							if ($msgType == "success") {
								onSuccessMsg($msg);
									   ycFundingSourceAssign.fnDraw();
									   onPledgedDynamicTable();
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
				"value": "getYcProfileFundingSourceAssign"
			});
			aoData.push({
				"name" : "country",
				"value" : $('#CountryName').val()
			});
			aoData.push({
				"name" : "lan",
				"value" : lan
			});
			aoData.push({
				"name" : "ItemGroupId",
				"value" : $('#item-group').val()
			});
			aoData.push({
				"name" : "year",
				"value" : $('#Year option[value=' + $('#Year').val() + ']').text()
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
		},{
			"sClass": "ItemProup",
			"bSortable": false
		}]
	});
	
	// regements table
	ycRegimenPatient = $('#tbl-yc-regimen-patient').dataTable({
		"bFilter" : false,
		"bJQueryUI" : false,
		"bDestroy": true,
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
		"sAjaxSource" : baseUrl + "stage_two_datasource.php",
		"fnDrawCallback" : function(oSettings) {
			// edit cell
			$('td input.datacell', ycRegimenPatient.fnGetNodes()).each(function() {
				$(this).change(function() {
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
							Pvalue : value,
							language : lan,
							jUserId : jUserId
						},
						"success": function(response) {
							$msgType = JSON.parse(response)['msgType'];
							$msg = JSON.parse(response)['msg'];
							if ($msgType == "success") {
								onSuccessMsg($msg);
							} else {
								onErrorMsg($msg);
							}
						}	

						
					});
				});
			});
			// grouping
			/*
			if (oSettings.aiDisplay.length == 0) {
				return;
			}
			var nTrs = $('#tbl-yc-regimen-patient tbody tr');
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
				"name" : "action",
				"value" : 'getYcRegimenPatient'
			});
			
			aoData.push({
				"name" : "lan",
				"value" :lan
			});
			
			aoData.push({
				"name" : "country",
				"value" : $('#CountryName').val()
			});
			aoData.push({
				"name" : "year",
				"value" : $('#Year option[value=' + $('#Year').val() + ']').text()
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
			"bSortable" : false,
			"sWidth" : "8%"
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
			//,
			//fnRender : function(oDt) {
			//	return formatNumber(oDt.aData[4]);
			//}
		}]
	});
	// Funding Source table
	ycFundingSource = $('#tbl-yc-funding-requirement').dataTable({
		"bFilter" : false,
		"bJQueryUI" : false,
		"bDestroy": true,
		"bSort" : false,
		"bInfo" : false,
		"bPaginate" : false,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : true,
		"aaSorting" : [[2, 'asc']],
		"sPaginationType" : "full_numbers",
		"aLengthMenu" : [[50, 100, 200], [50, 100, 200]],
		"iDisplayLength" : 50,
		"sAjaxSource" : baseUrl + "stage_two_datasource.php",
		"fnDrawCallback" : function(oSettings) {
			// edit cell
			$('td input.datacell', ycFundingSource.fnGetNodes()).each(function() {
				$(this).change(function() {
					var nTr = this.parentNode.parentNode;
					var aData = ycFundingSource.fnGetData(nTr);
					var RecordId = aData[1];
					var value = $(this).val().trim();
					currentY = $(this).attr('class');
					currentY = currentY.split('datacell ');
					currentY = currentY[1];
					if (isNaN(value)) {
					} else {
						value = addCommas(value);
					}
					$.ajax({
						"type" : "POST",
						"url" : baseUrl + "stage_two_datasource.php",
						"data" : {
							action : 'updateFundingRequirementData',
							FundingReqId : RecordId,
							currentY : currentY,
							Pvalue : value,
							language : lan,
							jUserId : jUserId
						},
						"success": function(response) {
							response = response.split('*');
							$msgType = JSON.parse(response)['msgType'];
							$msg = JSON.parse(response)['msg'];
							if ($msgType == "success") {
								onSuccessMsg($msg);
								//value = response[1].toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
								//$('.TR-' + RecordId + '').html(value);
							} else {
								onErrorMsg($msg);
							}
						}	
					});
				});
			});
			// grouping
			if (oSettings.aiDisplay.length == 0) {
				return;
			}
			var nTrs = $('#tbl-yc-funding-requirement tbody tr');
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
		},
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "action",
				"value" : 'getYcFundingSource'
			});
			
			aoData.push({
				"name" : "lan",
				"value" :lan
			});
			
			aoData.push({
				"name" : "country",
				"value" : $('#CountryName').val()
			});
			aoData.push({
				"name" : "year",
				"value" : $('#Year option[value=' + $('#Year').val() + ']').text()
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

		}, {
			"bVisible" : false
		}, {
			"bVisible" : false
		}, {
		}, {
			"bVisible" : true
			//fnRender : function(oDt) {
			//	return formatNumber(oDt.aData[4]);
			//}
		}, {
			"bVisible" : true
		}, {
			"bVisible" : true
		}, {
			"bVisible" : true
		}]
	});

	//Form Wizard 1
	currentStep_1 = 1;

	$('#country_form_wizard').parsley({
		listeners : {
			onFieldValidate : function(elem) {
				// if field is not visible, do not apply Parsley validation!
				if (!$(elem).is(':visible')) {
					return true;
				}

				return false;
			},
			onFormSubmit : function(isFormValid, event) {
				//event.preventDefault();
				//return false;

				if (isFormValid) {

					currentStep_1++;
					if (currentStep_1 == 2) {
						onSecondTab();
					} else if (currentStep_1 == 3) {
						onThirdTab();
					} else if (currentStep_1 == 4) {
						onFourthTab();
					} else if (currentStep_1 == 5) {
						onFifthTab();
					}

					return false;
				}
			}
		}
	});

	$('#country_form_wizard').submit(function(e) {
		e.preventDefault();
		return false;
	});
	$('#nextStep1').click(function() {
	
			if( bPatientInfo == 1){
				currentStep_1 = currentStep_1+1;
            }
            else{
				if(currentStep_1 == 2)
					currentStep_1 = currentStep_1+2;
				else
					currentStep_1 = currentStep_1+1;
            }		
		
		//currentStep_1++;

		if (currentStep_1 == 2) {
			onSecondTab();
		} else if (currentStep_1 == 3) {
			onThirdTab();
		} else if (currentStep_1 == 4) {
			onFourthTab();
		} else if (currentStep_1 == 5) {
			onFifthTab();
		}

		return false;
	});
	$('#prevStep1').click(function() {

		if( bPatientInfo == 1){
				currentStep_1 = currentStep_1-1;
            }
            else{
				if(currentStep_1 == 4)
					currentStep_1 = currentStep_1-2;
				else
					currentStep_1 = currentStep_1-1;
            }		
		//currentStep_1--;
		
		//console.log(currentStep_1);
		if (currentStep_1 == 1) {
			onFirstTab();
		}else if (currentStep_1 == 2) {
			onSecondTab();
		} else if (currentStep_1 == 3) {
			onThirdTab();
		} else if (currentStep_1 == 4) {

			onFourthTab();
		}
		return false;
	});
	
	
	$("#table-div").hide();
	$("#save-div").hide();
	$('.country-form-wizard').hide();
	$('#wizardDemo1 li a').attr('disabled', true);	
	Onchange($('#CountryName').val());


});

function onRequirementYear(localData) {
	RequirementYear = localData;
	onPledgedFunding();
}
function onFirstTab() {
	currentStep_1 = 1;
	$('#wizardDemo1 li:eq(0) a').tab('show');
	$('#wizardProgress').css("width", "50%");

	$('#nextStep1').attr('disabled', false);
	$('#nextStep1').removeClass('disabled');

	$('#prevStep1').attr('disabled', true);
	$('#prevStep1').addClass('disabled');

	//$('#wizardProgress').css("width", "25%");
	$('#wizardProgress').css("width", (currentStep_1*wizardProgresspercent)+'%');
	
	profileTable.fnDraw();
}


function onSecondTab() {
	currentStep_1 = 2;
	$('#wizardDemo1 li:eq(1) a').tab('show');
	//$('#wizardProgress').css("width", x);
	$('#wizardProgress').css("width", (currentStep_1*wizardProgresspercent)+'%');

	$('#nextStep1').attr('disabled', false);
	$('#nextStep1').removeClass('disabled');
	$('#prevStep1').attr('disabled', false);
	$('#prevStep1').removeClass('disabled');
	ycFundingSourceAssign.fnDraw();
}

function onThirdTab() {
	currentStep_1 = 3;
	$('#wizardDemo1 li:eq(2) a').tab('show');
	//$('#wizardProgress').css("width", x);
	$('#wizardProgress').css("width", (currentStep_1*wizardProgresspercent)+'%');

	$('#nextStep1').attr('disabled', false);
	$('#nextStep1').removeClass('disabled');
	$('#prevStep1').attr('disabled', false);
	$('#prevStep1').removeClass('disabled');
	ycRegimenPatient.fnDraw();
}

function onFourthTab() {
	currentStep_1 = 4;
	$('#wizardDemo1 li:eq(3) a').tab('show');
	//$('#wizardProgress').css("width", "50%");
	$('#wizardProgress').css("width", "50%");

	$('#nextStep1').attr('disabled', false);
	$('#nextStep1').removeClass('disabled');
	$('#prevStep1').attr('disabled', false);
	$('#prevStep1').removeClass('disabled');
	$('#wizardProgress').css("width", (currentStep_1*wizardProgresspercent)+'%');

	ycFundingSource.fnDraw();
}

function onFifthTab() {

	currentStep_1 = 5;
	$('#wizardDemo1 li:eq(4) a').tab('show');
	//$('#wizardProgress').css("width", "100%");
	$('#wizardProgress').css("width", (currentStep_1*wizardProgresspercent)+'%');

	$('#nextStep1').attr('disabled', true);
	$('#nextStep1').addClass('disabled');
	$('#prevStep1').attr('disabled', false);
	$('#prevStep1').removeClass('disabled');
	onPledgedFunding();
}

function onClearWizardInformation(localData) {
	// localData=1, Basic Information
	// localData=2, Regimens
	// localData=3, Funding Requirements
	$.ajax({
		"type" : "POST",
		"url" : baseUrl + "stage_two_datasource.php",
		"data" : {
			action : 'clearCountryProfileData',
			CountryProfileType : localData,
			year : $('#Year option[value=' + $('#Year').val() + ']').text(),
			country : $('#CountryName').val(),
			ItemGroupId : $('#item-group').val()				
		},
		"success" : function(response) {
			if (response == 1) {
				if (localData == 1)
					profileTable.fnDraw();
				else if (localData == 2)
					ycFundingSourceAssign.fnDraw();
				else if (localData == 3)
					ycRegimenPatient.fnDraw();
				else if (localData == 4)
					ycFundingSource.fnDraw();
				else if (localData == 5)
					onPledgedFunding();
			} else {
				msg = "Server processing Error.";
				onErrorMsg(msg);
			}
		}
	});
}

function bShowDiv(){
			var select = document.getElementById('item-group');  
            var opt = $(select.options[select.selectedIndex]);
            //var name = opt.attr('data-name');
             bPatientInfo = opt.attr('bPatientInfo'); //alert(bPatientInfo);

            if( bPatientInfo == 1){
				$('#step3').show();
				//wizardProgresspercent = 20;
            }
            else{
               $('#step3').hide();
			   //wizardProgresspercent = 25;
            }
}
