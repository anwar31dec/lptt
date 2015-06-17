var profileTable;
var profileTable1;
var profileTable2;
var gCountryId = 1;
function formatNumber(n) {
	return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function onLoadCountryList() {
	$.ajax({
		url : baseUrl + "t_countryinfo_home_server.php",
		data : {
			operation : 'getCountryList'
		},
		type : 'post',
		success : function(response) {
			response = $.parseJSON(response);
			if (response.length > 0) {
				str = 'Focus Countries: ';
				for ( i = 0; i < response.length; i++) {
					if (i != response.length - 1)
						str += response[i].CountryName + ' - ';
					else
						str += response[i].CountryName;
						
				}
			//	$('.navbar-header h4').html(str);
			} else {
			//	$('.navbar-header h4').html('');
			}
		}
	});
}
function onLoadFlagList() {
	$.ajax({
		url : baseUrl + "t_countryinfo_home_server.php",
		data : {
			operation : 'getCountryList'
		},
		type : 'post',
		success : function(response) {
			response = $.parseJSON(response);
			if (response.length > 0) {
				str = '';
				for ( i = 0; i < response.length; i++) {
					str+='<img src="'+baseUrl+'/flag/'+response[i].ISO3+'_flag.png" width="48px" style="margin-right:5px;" title="'+response[i].CountryName+'" />';
				}
				$('.flags').html(str+'<p></p>');
			} 
		}
	});
}
function getProfileParams(pCountryId){
gCountryId = pCountryId;

	calServer();
}
function calServer(){

	profileTable = $('#tbl-country-profile').dataTable({
		"bFilter" : false,
		"bJQueryUI" : false,
		"bSort" : false,
		"bInfo" : false,
		"bPaginate" : false,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : false,
		"bDestroy": true,
		"aaSorting" : [[0, 'asc']],
		"sPaginationType" : "full_numbers",
		"aLengthMenu" : [[50, 100, 200], [50, 100, 200]],
		"iDisplayLength" : 50,
		"sAjaxSource" : baseUrl + "t_countryinfo_home_server.php",
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "operation",
				"value" : 'getCountryProfileParams'
			});
			aoData.push({
				"name" : "Year",
				"value" : objInit.initialYear
			});            
            aoData.push({
				"name": "lan",
				"value": lan
			});
			aoData.push({
				"name": "CountryId",
				"value": gCountryId
			});
			$.ajax({
				"dataType" : 'json',
				"type" : "post",
				"url" : sSource,
				"data" : aoData,
				"success" : function(json) {
					fnCallback(json);
				}
			});
		},
		"aoColumns" : [{
			"sClass" : "center-aln",
			"sWidth" : "15%",
			"bSortable" : false,
			"bSearchable" : false,
			"bVisible":false
		}, {
			"sClass" : "left-aln",
			"bSortable" : false,
			"sWidth" : "70%"
		}, {
			"sClass" : "right-aln",
			"sWidth" : "15%",
			"bSortable" : false,
			fnRender : function(oDt) {
				return formatNumber(oDt.aData[2]);

			}
		}]
	});
	
	
	profileTable1 = $('#tbl-country-profile1').dataTable({
		"bFilter" : false,
		"bJQueryUI" : false,
		"bSort" : false,
		"bInfo" : false,
		"bPaginate" : false,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : false,
		"bDestroy": true,
		"aaSorting" : [[0, 'asc']],
		"sPaginationType" : "full_numbers",
		"aLengthMenu" : [[50, 100, 200], [50, 100, 200]],
		"iDisplayLength" : 50,
		"sAjaxSource" : baseUrl + "t_countryinfo_home_server.php",
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "operation",
				"value" : 'getCountryProfileParams1'
			});
			aoData.push({
				"name" : "Year",
				"value" : objInit.initialYear
			});            
            aoData.push({
				"name": "lan",
				"value": lan
			});
			aoData.push({
				"name": "CountryId",
				"value": gCountryId
			});

			$.ajax({
				"dataType" : 'json',
				"type" : "post",
				"url" : sSource,
				"data" : aoData,
				"success" : function(json) {
					fnCallback(json);
				}
			});
		},
		"aoColumns" : [{
			"sClass" : "center-aln",
			"sWidth" : "15%",
			"bSortable" : false,
			"bSearchable" : false,
			"bVisible":false
		}, {
			"sClass" : "left-aln",
			"bSortable" : false,
			"sWidth" : "70%"
		}, {
			"sClass" : "right-aln",
			"sWidth" : "15%",
			"bSortable" : false,
			fnRender : function(oDt) {
				return formatNumber(oDt.aData[2]);

			}
		}]
	});
	
	
	profileTable2 = $('#tbl-country-profile2').dataTable({
		"bFilter" : false,
		"bJQueryUI" : false,
		"bSort" : false,
		"bInfo" : false,
		"bPaginate" : false,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : false,
		"bDestroy": true,
		"aaSorting" : [[0, 'asc']],
		"sPaginationType" : "full_numbers",
		"aLengthMenu" : [[50, 100, 200], [50, 100, 200]],
		"iDisplayLength" : 50,
		"sAjaxSource" : baseUrl + "t_countryinfo_home_server.php",
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "operation",
				"value" : 'getCountryProfileParams2'
			});
			aoData.push({
				"name" : "Year",
				"value" : objInit.initialYear
			});            
            aoData.push({
				"name": "lan",
				"value": lan
			});
			aoData.push({
				"name": "CountryId",
				"value": gCountryId
			});

			$.ajax({
				"dataType" : 'json',
				"type" : "post",
				"url" : sSource,
				"data" : aoData,
				"success" : function(json) {
					fnCallback(json);
				}
			});
		},
		"aoColumns" : [{
			"sClass" : "center-aln",
			"sWidth" : "15%",
			"bSortable" : false,
			"bSearchable" : false,
			"bVisible":false
		}, {
			"sClass" : "left-aln",
			"bSortable" : false,
			"sWidth" : "70%"
		}, {
			"sClass" : "right-aln",
			"sWidth" : "15%",
			"bSortable" : false,
			fnRender : function(oDt) {
				return formatNumber(oDt.aData[2]);

			}
		}]
	});
	
	
}
$(function() {

	calServer();
	//onLoadCountryList();
	//onLoadFlagList();
});
