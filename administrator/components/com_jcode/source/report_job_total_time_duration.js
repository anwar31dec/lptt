var gMonthId;
var gYearId;
var dataY = new Array();
var dataColor = new Array();
var stockStatusDifferentLevel;
var endDate = new Date();
var chart;
var gFrequencyId=1;
var patientTrendTimeSeries;
var $ = jQuery.noConflict();


function onPatientTrendTable() {
	$('body').animate({
		opacity : 1
	}, 500, function() {

	$.ajax({
		type : "POST",
		url : baseUrl + "report_job_total_time_duration_server.php",
		data : {
			action : 'getDiffLevelTableData',
			Year: $('#year-list').val(),
			Month: $('#month-list').val(),
			Country: $('#country-list').val(),
			ItemGroup: $('#item-group').val(),
			OwnnerTypeId : $('#report-by').val(),
			lan:lan			
		},
		success : function(results) {
		results = $.parseJSON(results);
		var ColumnNo = results.COLUMNS.length;
	
	$('#tbl-pf').html('');
	html = '<table class="table table-striped table-bordered display table-hover" cellspacing="0" id="tbl-patient-trend-time-series">';
	html += '<thead>';
		
		
	if(ColumnNo == 0){
		html += '<tr><th style="text-align:center; width:5%;">SL#</th><th rowspan="2" style="text-align:center; width:10%;">Tracking#</th>';
		html += '<th  style="text-align:center; width:10%;">National Level AMC</th>';
		html += '<th style="text-align:center; width:10%;">National Level SOH</th>';	
		html += '<th style="text-align:center; width:10%;">National Level MOS</th></tr>'
	}
	else{		
		html += '<tr><th rowspan="2" style="text-align:center; width:5%;">SL#</th>';
		html += '<th rowspan="2" style="text-align:center; width:10%;">Tracking#</th>';
		
		var Header = '-1';
		for (var i = 0; i < ColumnNo; i++) {
			if(Header != results.COLUMNS[i]){
				html += '<th colspan="3" style="text-align:center;">'+results.COLUMNS[i]+'</th>';
				Header = results.COLUMNS[i];
				}
		}
		
		var index = 0;
		html += '<th rowspan="2" style="text-align:center; width:10%;">Total Time</th>';
		html += '</tr><tr>'
			for (var i = 0; i < ColumnNo; i++) {
				index++;
				if(index == 1)
					html += '<th  style="text-align:center; width:13%;">In Time</th>';
				else if(index == 2)
					html += '<th  style="text-align:center; width:13%;">Out Time</th>';
				else if(index == 3)
					html += '<th  style="text-align:center; width:13%;">Duration</th>';
				
				if(index == 3)
					index = 0;
				
		}		
		html += '</tr>'	
	}
	
	
	html += '</thead>';
	html += '<tbody></tbody>';
	html += '</table>';
	$('#tbl-pf').html(html);
				
patientTrendTimeSeries = $('#tbl-patient-trend-time-series').dataTable({
	"bFilter" : false,
	"bJQueryUI" : true,
	"bSort" : true,
	"bInfo" : false,
	"bPaginate" : false,
	"bSortClasses" : false,
	"bProcessing" : true,
	"bServerSide" : false,
	"bSortable": false,
	// scrollY:        "400px",
	// scrollX:        "100%",
	// scrollCollapse: true,
	// paging:         false,
	"aaSorting" : [[1, 'asc']],
	"sPaginationType" : "full_numbers",
	"aLengthMenu" : [[25, 50, 100], [25, 50, 100]],
	"iDisplayLength" : 25,
	"sAjaxSource": baseUrl + "report_job_total_time_duration_server.php",
	"fnDrawCallback": function(oSettings) {			
	
	},
	"fnServerData": function(sSource, aoData, fnCallback) {
		aoData.push({
			"name": "action",
			"value": 'getDiffLevelTableData'
		});
		aoData.push({
			"name": "lan",
			"value": lan
		});
		aoData.push({
			"name": "baseUrl",
			"value": baseUrl
		});
		/* aoData.push({
			"name": "Year",
			"value": $('#year-list').val()
		});
		aoData.push({
			"name": "Month",
			"value": $('#month-list').val()
		});
		aoData.push({
			"name": "Country",
			"value": $('#country-list').val()
		});
		
		aoData.push({
			"name": "ItemGroup",
			"value": $('#item-group').val()
		});
		aoData.push({
			"name": "OwnnerTypeId",
			"value": $('#report-by').val()
		});	 */
		
		
		$.ajax({
			"dataType": 'json',
			"type": "POST",
			"url": sSource,
			"data": aoData,
			"success": function(json) {
				fnCallback(json);						
			}
		});
	  }
	 });	
		 
	 }
	});
  });
}


$(function() {
	onPatientTrendTable();	
});
