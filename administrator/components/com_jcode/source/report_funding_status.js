var gMonthId;
var gYearId;
var dataY = new Array();
var dataColor = new Array();
var nationalSumProducts;
var endDate = new Date();
var chart;
var $ = jQuery.noConflict();
function onChartUpdate(){
	yearList=$('#year-list').val();
	countryList=$('#country-list').val();
    ItemGroupList=$('#item-group').val();
	$.ajax({
		type: "POST",
		url : baseUrl + "report_funding_status_server.php",
		data:{action:'getBarChartFundingStatus',Year:yearList,Country:countryList,lan: lan,ItemGroup:ItemGroupList},
		success:function(response){
			response=$.parseJSON(response);			
			//console.log(response);
			if(response.categories.length>0){
			// alert(response.categories.length);
				drawBarChart(response.categories, response.dataSeries,response.title);				
			}else{
				$('#barchart-container').html('').html('<div id="FundingStatusBarChart"></div>');
			}
			fundingStatus.fnDraw();
		}
	});
}

function drawBarChart(categories, dataSeries, title){
	$('#barchart-container').html('').html('<div id="FundingStatusBarChart"></div>');
	
	$('#FundingStatusBarChart').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: TEXT['Funding Status']
            },
            subtitle: {
                //text: 'Source: WorldClimate.com'
            },
            xAxis: {
                categories: categories
            },
            yAxis: {
                min: 0,
                title: {
                    text: TEXT['Pledged Quantity']
                }
            },
			credits: {
            enabled: false
			},
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:,f}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.1,
                    borderWidth: 0
                }
            },
            series: dataSeries
        });
 }


$(function() {	

	$.each(gYearList, function(i, obj) {
		$('#year-list').append($('<option></option>').val(obj.YearName).html(obj.YearName));
	});
	
	$("#year-list").val(endDate.getFullYear());
	
	$.each(gCountryList, function(i, obj) {
		$('#country-list').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
	});
	
	$('#country-list').val(gUserCountryId);
	
	$('#country-list').change(function() {
        onChartUpdate();
    });
    
    $.each(gItemGroupList, function(i, obj) {
		$('#item-group').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
	});

	$('#item-group').val(gUserItemGroupId);

	$('#item-group').change(function() {
		 onChartUpdate();
        fundingStatus.fnDraw();
	});
  
	
	$("#year-list").change(function() {
		onChartUpdate();
	});
	
	$("#left-arrow").click(function() {		
		$("#year-list").val(endDate.getFullYear()-1);
		onChartUpdate();
	});

	$("#right-arrow").click(function() {
		$("#year-list").val(endDate.getFullYear()+1);
		onChartUpdate();		
	});
    
	fundingStatus = $('#tbl-funding-status').dataTable({
		"bFilter" : false,
		"bJQueryUI" : false,
		"bSort" : false,
		"bInfo" : false,
		"bPaginate" : false,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : true,
		"aaSorting" : [[0, 'asc']],
		"sPaginationType" : "full_numbers",
		"aLengthMenu" : [[25, 50, 100], [25, 50, 100]],
		"iDisplayLength" : 25,
		"sAjaxSource" : baseUrl + "report_funding_status_server.php",
		"fnDrawCallback" : function(oSettings) {				 
				if (oSettings.aiDisplay.length == 0) {
					return;
				}
				var nTrs = $('#tbl-funding-status tbody tr');
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
				
				var sLastGroup = "";
					for (var i = 0, k = 0; i < nTrs.length; i++) {
						var iDisplayIndex = i;
						data = oSettings.aoData[oSettings.aiDisplay[iDisplayIndex]]._aData;
						if (data[0] == data[1] + ' Total') {
							ln = $('#tbl-funding-status tr:eq(' + (i + (k + 1) + 1) + ') td').length;
							for ( m = 0; m < ln; m++) {
								myClass = $('#tbl-funding-status tr:eq(' + (i + (k + 1) + 1) + ') td:eq(' + m + ')').attr('class');
								$('#tbl-funding-status tr:eq(' + (i + (k + 1) + 1) + ') td:eq(' + m + ')').attr('class', myClass + ' groupTotal');
							}
							ln = $('#tbl-funding-status tr:eq(' + (i + (k + 1) + 1) + ') td:eq(0)').attr('colspan', 2);
							ln = $('#tbl-funding-status tr:eq(' + (i + (k + 1) + 1) + ') td:eq(1)').remove();
							k++;
						} else if (data[0] == 'Grand Total') {
							ln = $('#tbl-funding-status tr:eq(' + (i + k + 1) + ') td').length;
							for ( m = 0; m < ln; m++) {
								myClass = $('#tbl-funding-status tr:eq(' + (i + k + 1) + ') td:eq(' + m + ')').attr('class');
								$('#tbl-funding-status tr:eq(' + (i + k + 1) + ') td:eq(' + m + ')').attr('class', myClass + ' supergroupTotal');
							}
							ln = $('#tbl-funding-status tr:eq(' + (i + k + 1) + ') td:eq(0)').attr('colspan', 2);
							ln = $('#tbl-funding-status tr:eq(' + (i + k + 1) + ') td:eq(1)').remove();
							k++;
						}
					}
			},
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "action",
				"value" : "getFundingStatusData"
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
                "name" : "Year",
                "value" : $('#year-list').val()
            });           
            aoData.push({
                "name" : "Country",
                "value" : $('#country-list').val()
            });
            aoData.push({
				"name" : "ItemGroup",
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
		},
		"aoColumns" : [{
			"bVisible" : true,
			"bSortable" : false,
			"sWidth" : "5%"
		}, {
			"sWidth" : "25%",
			"bVisible" : false,
		}, {
			"sWidth" : "14%",
			"sClass" : "left-aln"
		}, {
			"sClass" : "right-aln",
			"sWidth" : "14%",
			"bSortable" : true
		}, {
			"sClass" : "right-aln",
			"sWidth" : "14%",
			"bSortable" : true
		}]
	});
	onChartUpdate();
});