var profileTable;
var defaultYear;
var ycRegimenPatient;
var ycFundingSource;
var currentStep_1;
var RequirementYear;

function formatNumber(n) {
	return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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

function onComboCountryName() {
	$.getJSON(baseUrl + "t_combo.php", {
		action: 'getCountryName'
	}, function(response) {
		str = '<option value="">Country</option>';
		for (var i = 0; i < response.length; i++) {
			str += '<option value="' + response[i].CountryId + '">' + response[i].CountryName + '</option>';
		}
		$('#CountryName').html(str);
	});
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
	$.each(gYearList, function(i, obj) {
		$('#Year').append($('<option></option>').val(obj.YearName).html(obj.YearName));
		$('#Year').val(objInit.initialYear);
	});
}

function countryOnchange(value) {
    $('#CountryName').val(value);
	profileTable.fnDraw();
	ycRegimenPatient.fnDraw();
	ycFundingSource.fnDraw();  
	onPledgedDynamicTable();
    $("#table-div").show();
    $("#save-div").show();
	cYear=$('#Year option[value='+$('#Year').val()+']').text();
	cYear=parseInt(cYear);
	$('.cYear').html(cYear);
	$('.nYear').html(cYear+1);
	$('.nnYear').html(cYear+2);
	if(value){
		$('.country-form-wizard').show();
		$('#Year').attr('disabled',true);
	}
	else{
		$('.country-form-wizard').hide();
		$('#Year').attr('disabled',false);
	}
};
//  PledgedFunding
/*
var dynamicColumns = [];
for (var i = 0; i < 5; i++) {
    var col = new Object();
    col.data = "Name"+i;
    col.title = "Name " + i.toString();
    col.type = "text";
    dynamicColumns.push(col);
}
*/
var dynamicData = [             
];
function onPledgedDynamicTable(){	
	if(RequirementYear==1){
		$('.pf:eq(0)').attr('class','btn btn-info btn-sm pf');
		$('.pf:eq(1)').attr('class','btn btn- btn-sm pf');
		$('.pf:eq(2)').attr('class','btn btn- btn-sm pf');		
	}else if(RequirementYear==2){
		$('.pf:eq(0)').attr('class','btn btn- btn-sm pf');
		$('.pf:eq(1)').attr('class','btn btn-info btn-sm pf');
		$('.pf:eq(2)').attr('class','btn btn- btn-sm pf');
	}else if(RequirementYear==3){
		$('.pf:eq(0)').attr('class','btn btn- btn-sm pf');
		$('.pf:eq(1)').attr('class','btn btn- btn-sm pf');
		$('.pf:eq(2)').attr('class','btn btn-info btn-sm pf');		
	}
	cYear= $('#Year option[value='+$('#Year').val()+']').text();
	console.log(RequirementYear);
	cYear=parseInt(cYear);
	$('.pf:eq(0)').html(cYear);
	$('.pf:eq(1)').html(cYear+1);
	$('.pf:eq(2)').html(cYear+2);
	$.ajax({
		"type" : "POST",
        "url" : baseUrl + "stage_two_datasource.php",
        "data" : {
            action: 'getPledgedFundingData',            
			year: $('#Year option[value='+$('#Year').val()+']').text(),
			country:$('#CountryName').val(),
			RequirementYear:RequirementYear
        },
        "success" : function(response) {
			response=$.parseJSON(response);
			if(response){				
				dynamicColumns=response.dynamicColumns;
				dynamiccolWidths=response.dynamiccolWidths;
				dynamicData=response.dynamicData;
				onTableStructure(dynamicColumns, dynamiccolWidths);
				$('.htCore tr td').attr('style','text-align:right')
			}
        }
  	});
}

function onTableStructure(dynamicColumns, dynamiccolWidths){
	//console.log(dynamicData);	
	$plegedFundingDetails=$('#yc_pledged_funding');
	
	$plegedFundingDetails.handsontable({
	  data:dynamicData,	 
	  startRows: 2,
	  startCols: 2,
	  colWidths: dynamiccolWidths,
	  columnSorting: false,
	  columns: 	dynamicColumns,
	  afterChange: function (changes, source) {
		if (source === 'loadData') {
            return;
        } 		
		row=changes[0][0];
		col=changes[0][1];				
		newval=changes[0][3];
				
		//console.log(row); 
		console.log(col); 
		FundingSourceId=col.split('-');
		FundingSourceId=FundingSourceId[1];			
		ItemGroupId=dynamicData[row]['Name'+dynamicColumns.length];
		FormulationId=dynamicData[row]['Name'+(+dynamicColumns.length+1)];
		
		$.ajax({
			"type" : "POST",
			"url" : baseUrl + "stage_two_datasource.php",
			"data" : {
				action: 'updatePledgedFundingData',				
				year: $('#Year option[value='+$('#Year').val()+']').text(),
				country:$('#CountryName').val(),
				ItemGroupId:ItemGroupId,
				FormulationId:FormulationId,
				FundingSourceId:FundingSourceId,
				TotalFund:newval
			},
			"success" : function(response) {
				if (response == 1) {
					onPledgedDynamicTable();
				} else {					
				}
			}
		});
		
	  }
	});
	//console.log(dynamicColumns);
	$plegedFundingDetails.handsontable('loadData', dynamicData);
}
function onPledgedFunding(){
	onPledgedDynamicTable();	 
}
$(function() {
	
	profileTable = $('#profileTable').dataTable({
		"bFilter": false,
		"bJQueryUI": true,
		"bSort": true,
		"bInfo": false,
		"bPaginate": false,
		"bSortClasses": false,
		"bProcessing": true,
		"bServerSide": true,
		"aaSorting": [[4, 'asc']],
		"sPaginationType": "full_numbers",
		"aLengthMenu": [[25, 50, 100],[25, 50, 100]],
		"iDisplayLength": 25,
		"sScrollX": "100%",
		//"sClass": "readonly",
		"sAjaxSource": baseUrl + "stage_two_datasource.php",
		// "fnDrawCallback": function(oSettings) {
			// $('td input.datacell', profileTable.fnGetNodes()).each(function() {
				// $(this).change(function() {
				    // var nTr = this.parentNode.parentNode;
					// var aData = profileTable.fnGetData(nTr);
					// var RecordId = aData[0];
				    // var value = $(this).val().trim();
                    // if (isNaN(value)){}else{value = addCommas(value);}
				    // $.ajax({
                		// "type" : "POST",
                		// "url" : baseUrl + "stage_two_datasource.php",
                		// "data" : {
                		      // action: 'updateYcProfileData',
                              // ProfileId: RecordId,
                              // Pvalue: value
                        // },
                		// "success" : function(response) {
                			// if (response == 1) {
                				// profileTable.fnDraw();
                				// //msg = "Profile parameter value updated successfully.";
                				// //onSuccessMsg(msg);
                			// } else {
                				// msg = "Server processing Error.";
                				// onErrorMsg(msg);
                			// }
                		// }
                	// });
				// });
			// });
			// $('td input.items', profileTable.fnGetNodes()).each(function() {
				// $(this).change(function() {				    
					// var RecordId = $(this).attr('id');
					// ln=$('#multiselect .items').length;
					// value='';
					// for(i=0;i<ln;i++){
						// if($('#multiselect .items:eq('+i+')').prop('checked')==true)
							// value+=$('#multiselect .items:eq('+i+')').val()+',';
					// }									
                    // if (value.length>0){
						// value=value.substr(0,value.length - 1);
					// }
					// //console.log(value);				
				    // $.ajax({
                		// "type" : "POST",
                		// "url" : baseUrl + "stage_two_datasource.php",
                		// "data" : {
                		      // action: 'updateYcProfileMultipleData',
                              // ProfileId: RecordId,
                              // Pvalue: value,
							  // year: $('#Year option[value='+$('#Year').val()+']').text(),
							  // country:$('#CountryName').val()
                        // },
                		// "success" : function(response) {
                			// if (response == 1) {
                				// profileTable.fnDraw();
								// onPledgedDynamicTable();
                				// //msg = "Profile parameter value updated successfully.";
                				// //onSuccessMsg(msg);
                			// } else {
                				// msg = "Server processing Error.";
                				// onErrorMsg(msg);
                			// }
                		// }
                	// });
				// });
			// });			
		//},
		"fnServerData": function(sSource, aoData, fnCallback) {
			aoData.push({
				"name": "action",
				"value": 'getYcProfileData',
				"lan":lan
			});
  	        aoData.push({
				"name": "country",
				"value": $('#CountryName').val()
			});
            aoData.push({
				"name": "year",
				"value": $('#Year option[value='+$('#Year').val()+']').text()
			});
			$.ajax({
				"dataType": 'json',
				"type": "POST",
				"url": sSource,
				"data": aoData,
				"success": fnCallback
			});
		},
		"aoColumns": [{
			"bVisible": false
		},
		{
			"sClass": "SL",
            "sWidth": "15%",
			"bSortable": false
		},
		{
			"sClass": "ParamName",
            "bSortable": true,
            "sWidth": "70%"
		},
		{
			"sClass": "Value",
			"bVisible": false,
            "sWidth": "10%",
            "bSortable": false           
		},
        {
			"bVisible": false
		}]
	});
	
	// regements table
	ycRegimenPatient = $('#tbl-yc-regimen-patient').dataTable({
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
			"sAjaxSource" : baseUrl + "stage_two_datasource.php",
			// "fnDrawCallback" : function(oSettings) {				
				// // edit cell
				// $('td input.datacell', ycRegimenPatient.fnGetNodes()).each(function() {
					// $(this).change(function() {						
						// var nTr = this.parentNode.parentNode;
						// var aData = ycRegimenPatient.fnGetData(nTr);
						// var RecordId = aData[1];
						// var value = $(this).val().trim();
						// if (isNaN(value)){}else{value = addCommas(value);}
						// $.ajax({
							// "type" : "POST",
							// "url" : baseUrl + "stage_two_datasource.php",
							// "data" : {
								  // action: 'updateYcRegimenData',
								  // YearlyRegPatientId: RecordId,
								  // Pvalue: value
							// },
							// "success" : function(response) {
								// if (response == 1) {
									// ycRegimenPatient.fnDraw();
									// //msg = "Profile parameter value updated successfully.";
									// //onSuccessMsg(msg);
								// } else {
									// msg = "Server processing Error.";
									// onErrorMsg(msg);
								// }
							// }
						// });
					// });
				// });
				// // grouping 
				// if (oSettings.aiDisplay.length == 0) {
					// return;
				// }
				// var nTrs = $('#tbl-yc-regimen-patient tbody tr');
				// var iColspan = nTrs[0].getElementsByTagName('td').length;
				// var sLastGroup = "";
				// for (var i = 0; i < nTrs.length; i++) {
					// var iDisplayIndex = i;
					// var sGroup = oSettings.aoData[oSettings.aiDisplay[iDisplayIndex]]._aData[3];
					// if (sGroup != sLastGroup) {
						// var nGroup = document.createElement('tr');
						// var nCell = document.createElement('td');
						// nCell.colSpan = iColspan;
						// nCell.className = "group";
						// nCell.innerHTML = sGroup;
						// nGroup.appendChild(nCell);
						// nTrs[i].parentNode.insertBefore(nGroup, nTrs[i]);
						// sLastGroup = sGroup;
					// }
				// }
			// },

			"fnServerData" : function(sSource, aoData, fnCallback) {
				aoData.push({
					"name" : "action",
					"value" : 'getYcRegimenPatient',
                    "lan":lan
				});
				aoData.push({
					"name" : "country",
					"value" : $('#CountryName').val()
				});
				aoData.push({
					"name" : "year",
					"value": $('#Year option[value='+$('#Year').val()+']').text()
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
				"bVisible" : false
			}, {
				"sClass" : "left-aln",
				"bSortable" : false
			}, {
				"sClass" : "left-aln",
				"bSortable" : false,
				"bVisible" : false
			}, {
				"sClass" : "right-aln",
				"bSortable" : false,
				fnRender : function(oDt) {
					return formatNumber(oDt.aData[4]);
				}
			}]
	});
	// Funding Source table
	ycFundingSource = $('#tbl-yc-funding-requirement').dataTable({
			"bFilter" : false,
			"bJQueryUI" : false,
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
				// $('td input.datacell', ycFundingSource.fnGetNodes()).each(function() {
					// $(this).change(function() {						
						// var nTr = this.parentNode.parentNode;
						// var aData = ycFundingSource.fnGetData(nTr);
						// var RecordId = aData[1];
						// var value = $(this).val().trim();
						// currentY=$(this).attr('class');	
						// currentY=currentY.split('datacell '); 
						// currentY=currentY[1];
						// if (isNaN(value)){}else{value = addCommas(value);}
						// $.ajax({
							// "type" : "POST",
							// "url" : baseUrl + "stage_two_datasource.php",
							// "data" : {
								  // action: 'updateFundingRequirementData',
								  // FundingReqId: RecordId,
								  // currentY:currentY,
								  // Pvalue: value
							// },
							// "success" : function(response) {
								// if (response == 1) {
									// ycFundingSource.fnDraw();
									// //msg = "Profile parameter value updated successfully.";
									// //onSuccessMsg(msg);
								// } else {
									// msg = "Server processing Error.";
									// onErrorMsg(msg);
								// }
							// }
						// });
					// });
				// });
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
					"value" : 'getYcFundingSource',
					"lan":lan
				});
				aoData.push({
					"name" : "country",
					"value" : $('#CountryName').val()
				});
				aoData.push({
					"name" : "year",
					"value": $('#Year option[value='+$('#Year').val()+']').text()
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
			
			},{	
				"bVisible" : false
			},{	
				"bVisible" : false
			},{
			},{
				fnRender : function(oDt) {
					
					return formatNumber(oDt.aData[4]);					
				}
			},{
				fnRender : function(oDt) {
					return formatNumber(oDt.aData[5]);					
				}
			},{
				fnRender : function(oDt) {
					return formatNumber(oDt.aData[6]);					
				}
			},{
				fnRender : function(oDt) {
					return formatNumber(oDt.aData[7]);					
				}
			}]
			/*
			"aoColumns" : [{
				"sClass" : "center-aln",
				"bSortable" : false
			}, {
				"sClass" : "left-aln",
				"bSortable" : false,
				"bVisible" : false
			}, {
				"sClass" : "left-aln",
				"bSortable" : false			
			}, {
				"sClass" : "left-aln",
				"bSortable" : false			
			}, {
				"sClass" : "left-aln",
				"bSortable" : false			
			}, {
				"sClass" : "left-aln",
				"bSortable" : false
			}, {
				"sClass" : "left-aln",
				"bSortable" : false
			}, {
				"sClass" : "right-aln",
				"bSortable" : false,
				fnRender : function(oDt) {
					//return formatNumber(oDt.aData[4]);
				}
			}]*/
		});
	//Form Wizard 1
	currentStep_1 = 1;
	
	
    $('#country_form_wizard').parsley( { listeners: {
		onFieldValidate: function ( elem ) {
			// if field is not visible, do not apply Parsley validation!
			if ( !$( elem ).is( ':visible' ) ) {
				return true;
			}

			return false;
		},
        onFormSubmit: function ( isFormValid, event ) {
			//event.preventDefault();
			//return false;
						
            if(isFormValid)	{
					
				currentStep_1++;
				
				if(currentStep_1 == 2)	{
					onSecondTab();
				}
				else if(currentStep_1 == 3)	{
					onThirdTab();					
				}
				else if(currentStep_1 == 4)	{
					onFourthTab();
				}
				
				return false;
			}
        }
    }});	
	

	$('#country_form_wizard').submit(function(e) {
			e.preventDefault();			
			return false; 	  
	});
	$('#nextStep1').click(function()	{
		
		currentStep_1++;
				
		if(currentStep_1 == 2)	{
			onSecondTab();
		}
		else if(currentStep_1 == 3)	{
			onThirdTab();					
		}
		else if(currentStep_1 == 4)	{
			onFourthTab();
		}
				
		return false;
	});	
	$('#prevStep1').click(function()	{
		
		currentStep_1--;
		//console.log(currentStep_1);
		if(currentStep_1 == 1)	{
		
			onFirstTab();
		}
		else if(currentStep_1 == 2)	{			
			onSecondTab();
		}
		else if(currentStep_1 == 3)	{
		
			onThirdTab();
		}
		return false;
	});	
	RequirementYear=1;
    onComboCountryName();
	onComboYearName();
	onPledgedFunding();
	$("#table-div").hide();
    $("#save-div").hide();
	$('.country-form-wizard').hide();
    $('#wizardDemo1 li a').attr('disabled',true);
});

function onRequirementYear(localData){
	RequirementYear=localData;
	onPledgedFunding();
}
function onFirstTab(){
	currentStep_1 =1;
	$('#wizardDemo1 li:eq(0) a').tab('show');
	$('#wizardProgress').css("width","50%");
		
	$('#nextStep1').attr('disabled',false);
	$('#nextStep1').removeClass('disabled');
	
	$('#prevStep1').attr('disabled',true);
	$('#prevStep1').addClass('disabled');
			
	$('#wizardProgress').css("width","25%");
	profileTable.fnDraw();
}
function onSecondTab(){
	currentStep_1 =2;
	$('#wizardDemo1 li:eq(1) a').tab('show');
	$('#wizardProgress').css("width","50%");
					
	$('#prevStep1').attr('disabled',false);
	$('#prevStep1').removeClass('disabled');
	//$('#edit input,#edit textarea, #edit select').attr('disabled',true);
					
	ycRegimenPatient.fnDraw();
}
function onThirdTab(){
	currentStep_1 =3;
	$('#wizardDemo1 li:eq(2) a').tab('show');
	$('#wizardProgress').css("width","50%");
					
	$('#nextStep1').attr('disabled',false);
	$('#nextStep1').removeClass('disabled');
			
	$('#wizardProgress').css("width","75%");
			
	ycFundingSource.fnDraw();
}
function onFourthTab(){
	currentStep_1 =4;
	$('#wizardDemo1 li:eq(3) a').tab('show');
	$('#wizardProgress').css("width","100%");
					
	$('#nextStep1').attr('disabled',true);
	$('#nextStep1').addClass('disabled');
	$('#prevStep1').attr('disabled',false);
	$('#prevStep1').removeClass('disabled');
	onPledgedFunding();
}
// function onClearWizardInformation(localData){
	// // localData=1, Basic Information
	// // localData=2, Regimens
	// // localData=3, Funding Requirements	
	// $.ajax({
		// "type" : "POST",
        // "url" : baseUrl + "stage_two_datasource.php",
        // "data" : {
            // action: 'clearCountryProfileData',
            // CountryProfileType: localData,
			// year: $('#Year option[value='+$('#Year').val()+']').text(),
			// country:$('#CountryName').val()
        // },
        // "success" : function(response) {
			// if (response == 1) {
				// if(localData==1)
					// profileTable.fnDraw();                
				// else if(localData==2)
					// ycRegimenPatient.fnDraw();                	
				// else if(localData==3)
					// ycFundingSource.fnDraw();   
				// else if(localData==4)
					// onPledgedFunding();
			// } else {
				// msg = "Server processing Error.";
				// onErrorMsg(msg);
			// }
        // }
  	// });
// }
// $('#edit').click(function() {
    // $('.editable').attr("disabled", "true");
// })

// $("#edit").on("click", function(e) {
  // e.stopPropagation();
  // // Here you can do additional stuff, which in your case might not be needed
// });
// $('#profileTable input:text').click(function() {
    // var row = $(this).closest('th');
// 
    // row.find('input:text').attr('disabled', true);
    // //row.siblings().find('input:text').attr('disabled', true);
// });
//$('.input').prop('disabled', true);
