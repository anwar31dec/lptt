var gFacilityCode;
var gMonthId;
var gYearId;
var gCountryId;
var gItemGroupId = 1;
var gFrequencyId=1;
var gStartYearId;
var gSartMonthId;
var gRegionId = 0;
var gOwnerTypeId;
var gDistrictId = 0;
var endDate = new Date();
var oTblFacilityReportingStatus;
var $ = jQuery.noConflict();

function getFillRegion() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: baseUrl + "combo_generic.php",
        data: {
            "operation": 'getFillRegion',
            "CountryId": gCountryId,
            "UserId": userId,
            "lan": lan
        },
        success: function(response) {
            $.each(response, function(i, obj) {
                $('#Region-list').append($('<option></option>').val(obj.RegionId).html(obj.RegionName));
            });

            var RegionList = response || [];
            var html = $.map(RegionList, function(obj) {
                return '<option value=' + obj.RegionId + '>' + obj.RegionName + '</option>';
            }).join('');

            $('#Region-list').html(html);
            gRegionId = $('#Region-list').val() == null ? 0 : $('#Region-list').val();
			getFillDistrict();
        }
    });
}


function getFillDistrict() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: baseUrl + "combo_generic.php",
        data: {
            "operation": 'getFillDistrict',
            "CountryId": gCountryId,
            "RegionId": gRegionId,
            "lan": lan
        },
        success: function(response) {
            $.each(response, function(i, obj) {
                $('#District-list').append($('<option></option>').val(obj.DistrictId).html(obj.DistrictName));
            });

            var DistrictList = response || [];
            var html = $.map(DistrictList, function(obj) {
                return '<option value=' + obj.DistrictId + '>' + obj.DistrictName + '</option>';
            }).join('');

            $('#District-list').html(html);
            gDistrictId = $('#District-list').val() == null ? 0 : $('#District-list').val();
        }
    });
}


var easyPieChartDefaults = {
    animate: 2000,
    //easing: 'easeOutBounce',
    // scaleColor: false,
    lineWidth: 6,
    size: 110,
    lineCap: 'square',
    barColor: '#9AD268',
    trackColor: '#e5e5e5'
            // ,
            // onStep: function(from, to, percent) {
            // $(this.el).find('.percent-entered').text(Math.round(percent));
            // }
};


function getReportGeneratePercentage() {
    $.ajax({
        type: "POST",
        url: baseUrl + "reporting_rate.php",
        data: {
            operation: 'getPercentage',
            CountryId: gCountryId,
            ItemGroupId: 0,
            OwnerTypeId: $("#OwnerType").val(),
            Year: gYearId,
            Month: gMonthId,
            RegionId: $("#Region-list").val(),
            DistrictId: $("#District-list").val(),
            lan: lan
        },
        success: function(response) {
            response = JSON.parse(response);
            $('#Total').html(response.Total + ' %');
            $('#Facility').html(response.HealthFaclilities + ' %');
            $('#District').html(response.DistrictWarehouse + ' %');
            $('#Region').html(response.RegionalWarhouse + ' %');
            $('#Central').html(response.CentralWarehouse + ' %');
        }
    });
}


function updateEasyPieValue(value1, value2, value3, value4) {

    $('#chart-entered').easyPieChart($.extend({}, easyPieChartDefaults, {
        onStep: function(from, to, percent) {
            $(this.el).find('#percent-entered').text(percent.toFixed(1));
        }
    }));
    var chartEntered = window.chartEntered = $('#chart-entered').data('easyPieChart').update(value1);

    $('#chart-submitted').easyPieChart($.extend({}, easyPieChartDefaults, {
        onStep: function(from, to, percent) {
            $(this.el).find('#percent-submitted').text(percent.toFixed(1));
        }
    }));
    var chartSubmitted = window.chartSubmitted = $('#chart-submitted').data('easyPieChart').update(value2);
/*
    $('#chart-accepted').easyPieChart($.extend({}, easyPieChartDefaults, {
        onStep: function(from, to, percent) {
            $(this.el).find('#percent-accepted').text(percent.toFixed(1));
        }
    }));
    var chartAccepted = window.chartAccepted = $('#chart-accepted').data('easyPieChart').update(value3);
*/
    $('#chart-published').easyPieChart($.extend({}, easyPieChartDefaults, {
        onStep: function(from, to, percent) {
            $(this.el).find('#percent-published').text(percent.toFixed(1));
        }
    }));
    var chartPublished = window.chartPublished = $('#chart-published').data('easyPieChart').update(value4);
}

function getItemGroupFrequency() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: baseUrl + "combo_generic.php",
        data: {
            "operation": 'getItemGroupFrequency',
            "CountryId": gCountryId,
            "ItemGroupId": gItemGroupId
        },
        success: function(response) {
            gFrequencyId = response[0].FrequencyId;
            gSartMonthId = response[0].StartMonthId;
            gStartYearId = response[0].StartYearId;
            getMonthByFrequencyId();
        }
    });
}

function getMonthByFrequencyId() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: baseUrl + "combo_generic.php",
        data: {
            "operation": 'getMonthByFrequencyId',
            "FrequencyId": gFrequencyId,
            "lan": lan
        },
        success: function(response) {
            $.each(response, function(i, obj) {
                $('#month-list').append($('<option></option>').val(obj.MonthId).html(obj.MonthName));
            });

            var monthList = response || [];
            var html = $.map(monthList, function(obj) {
                return '<option value=' + obj.MonthId + '>' + obj.MonthName + '</option>';
            }).join('');

            $('#month-list').html(html);

            if (gFrequencyId == 1) {
                endDate.setMonth(objInit.svrLastMonth - 1);
                endDate.setFullYear(objInit.svrLastYear);
            }
            else if (gFrequencyId == 2) {
                endDate.setMonth(objInit.svrLastMonth - 1);
                endDate.setFullYear(objInit.svrLastYear);
                endDate.lastQuarter();
            }

            $("#month-list").val(endDate.getMonth() + 1);
            $("#year-list").val(endDate.getFullYear());

            gMonthId = $('#month-list').val() == null ? -99 : $('#month-list').val();
            gYearId = $('#year-list').val() == null ? -99 : $('#year-list').val();

            oTblFacilityReportingStatus.fnDraw();
            getReportGeneratePercentage();

        }
    });
}

oTblFacilityReportingStatus = $('#tbl-facility-reporting-status').dataTable({
    "bFilter": true,
        "bJQueryUI": true,
        "bSort": true,
        "bInfo": true,
        "bPaginate": true,
        "bSortClasses": false,
        "bProcessing": true,
        "bServerSide": true,
		"sInfoEmpty":    "Showing 0 to 0 of 0 entries",
		"sInfoFiltered": "(filtered from _MAX_ total entries)",
        "aaSorting": [[13, 'asc'],[2, 'asc']],
        "sPaginationType": "full_numbers",
        "aLengthMenu": [[25, 50, 100], [25, 50, 100]],
        "iDisplayLength": 25,		
		"sAjaxSource": baseUrl + "facility_reporting_status_server.php",
		"fnDrawCallback" : function(oSettings) {

			if (oSettings.aiDisplay.length == 0) {
				return;
			}
			var nTrs = $('#tbl-facility-reporting-status tbody tr');
			var iColspan = nTrs[0].getElementsByTagName('td').length;
			var sLastGroup = "";
			for (var i = 0; i < nTrs.length; i++) {
				var iDisplayIndex = i;
				var sGroup = oSettings.aoData[oSettings.aiDisplay[iDisplayIndex]]._aData[12];
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
            "value": "getFacilityReportingStatus"
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
            "name": "MonthId",
            "value": gMonthId
        });
        aoData.push({
            "name": "YearId",
            "value": gYearId
        });
        aoData.push({
            "name": "ItemGroupId",
            "value": gItemGroupId
        });
        aoData.push({
            "name": "CountryId",
            "value": gCountryId
        });
        aoData.push({
            "name": "RegionId",
            "value": gRegionId
        });
        aoData.push({
            "name": "DistrictId",
            "value": gDistrictId
        });
        aoData.push({
            "name": "OwnerTypeId",
            "value": gOwnerTypeId
        });

        $.ajax({
            "dataType": 'json',
            "type": "GET",
            "url": sSource,
            "data": aoData,
            "success": function(json) {
                fnCallback(json);

                updateEasyPieValue(json.aaData2.Entered, json.aaData2.Submitted, json.aaData2.Accepted, json.aaData2.Published);

            }
        });
    },
    "aoColumns": [{
            "sClass": 'left-aln', //'SL'
            "bSortable": false,
            "bVisible": false,
            "bSearchable": false
        }, {
            "sClass": 'left-aln', //'FacilityId'
            "bSortable": true,
            "bVisible": false,
            "bSearchable": false
        }, {
            "sClass": 'left-aln', //'FacilityCode'
            "bSortable": true,
            "bVisible": true
        }, {
            "sClass": 'left-aln', //'FacilityName'
            "bSortable": true,
            "bVisible": true
        }, {
            "sClass": 'center-aln', //'bEntered'
            "bSortable": true,
            "bVisible": true,
            "bSearchable": false
        }, {
            "sClass": 'left-aln', //'CreatedDt'
            "bSortable": true,
            "bVisible": true,
            "bSearchable": false
        }, {
            "sClass": 'center-aln', //'bSubmitted'
            "bSortable": true,
            "bVisible": true,
            "bSearchable": false
        }, {
            "sClass": 'right-aln', //'LastSubmittedDt'
            "bSortable": true,
            "bVisible": true,
            "bSearchable": false
        }, {
            "sClass": 'center-aln', //'bAccepted'
            "bSortable": true,
            "bVisible": false,
            "bSearchable": false
        }, {
            "sClass": 'left-aln', //'AcceptedDt'
            "bSortable": true,
            "bVisible": false,
            "bSearchable": false
        }, {
            "sClass": 'center-aln', //'bPublished'
            "bSortable": true,
            "bVisible": true,
            "bSearchable": false
        }, {
            "sClass": 'right-aln', //'PublishedDt'
            "bSortable": true,
            "bVisible": true,
            "bSearchable": false
        }, {
            "sClass": 'left-aln', //'Facility Level'
            "bSortable": true,
            "bVisible": false,
			"bSearchable": true
        }, {
            "sClass": 'left-aln', //'Facility Level Id'
            "bSortable": true,
            "bVisible": false,
			"bSearchable": false
        }]
});
$(function() {
	$.each(gMonthList, function(i, obj) {
		$('#month-list').append($('<option></option>').val(obj.MonthId).html(obj.MonthName));
	});
	
    $.each(gYearList, function(i, obj) {
        $('#year-list').append($('<option></option>').val(obj.YearName).html(obj.YearName));
    });

	endDate.setMonth(objInit.svrLastMonth - 1);
	endDate.setFullYear(objInit.svrLastYear);
	$("#month-list").val(endDate.getMonth() + 1);
	$("#year-list").val(endDate.getFullYear());

	gMonthId = $('#month-list').val() == null ? -99 : $('#month-list').val();
	gYearId = $('#year-list').val() == null ? -99 : $('#year-list').val();

    $.each(gCountryListFLevel, function(i, obj) {
        $('#country-list').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
    });

    $('#country-list').val(gUserCountryId);
	gCountryId = $("#country-list").val();
	getFillRegion();
	
    $.each(gOwnerTypeList, function(i, obj) {
        $('#OwnerType').append($('<option></option>').val(obj.OwnerTypeId).html(obj.OwnerTypeName));
    });
    $('#OwnerType').val(gDetaultOwnerTypeId);
    gOwnerTypeId = $("#OwnerType").val();

	
/*
    $.each(gRegionList, function(i, obj) {
        $('#Region-list').append($('<option></option>').val(obj.RegionId).html(obj.RegionName));
    });
    gRegionId = $("#Region-list").val();
    getFillDistrict();
	*/
	


   

    $("#left-arrow").click(function() {
        if (gFrequencyId == 1) {
            if (endDate.getMonth() == 0 && endDate.getFullYear() == gYearList[0].YearName)
                return;

            endDate.prevMonth();
        } else {
            if (endDate.getMonth() == 2 && endDate.getFullYear() == gYearList[0].YearName)
                return;
            endDate.prevMonths(3);
        }

        $("#month-list").val(endDate.getMonth() + 1);
        $("#year-list").val(endDate.getFullYear());

        gMonthId = $("#month-list").val();
        gYearId = $("#year-list").val();

        oTblFacilityReportingStatus.fnDraw();
        getReportGeneratePercentage();

    });

    $("#right-arrow").click(function() {
        if (gFrequencyId == 1) {
            if (endDate.getMonth() == 11 && endDate.getFullYear() == gYearList[gYearList.length - 1].YearName)
                return;
            endDate.nextMonth();
        } else {
            if (endDate.getMonth() == 11 && endDate.getFullYear() == gYearList[gYearList.length - 1].YearName)
                return;
            endDate.nextMonths(3);
        }
        $("#month-list").val(endDate.getMonth() + 1);
        $("#year-list").val(endDate.getFullYear());
        gMonthId = $("#month-list").val();
        gYearId = $("#year-list").val();

        oTblFacilityReportingStatus.fnDraw();
        getReportGeneratePercentage();
    });

    $("#month-list").change(function() {
        endDate.setMonth($("#month-list").val() - 1);

        gMonthId = $("#month-list").val();
        gYearId = $("#year-list").val();
        oTblFacilityReportingStatus.fnDraw();
        getReportGeneratePercentage();
    });

    $("#year-list").change(function() {
        endDate.setYear($("#year-list").val());
        endDate.setMonth($("#month-list").val() - 1);

        gMonthId = $("#month-list").val();
        gYearId = $("#year-list").val();

        oTblFacilityReportingStatus.fnDraw();
        getReportGeneratePercentage();
    });

    $("#country-list").change(function() {
        gCountryId = $("#country-list").val();
		getFillRegion();
        gRegionId = 0;        
		gDistrictId = 0;
        oTblFacilityReportingStatus.fnDraw();
        getReportGeneratePercentage();
    });
  //  $("#item-group-list").change(function() {
  //      gItemGroupId = $("#item-group-list").val();
  //      getItemGroupFrequency();
   // });


    $("#Region-list").change(function() {
        gRegionId = $("#Region-list").val();
        getFillDistrict();
		gDistrictId = 0;
        oTblFacilityReportingStatus.fnDraw();
        getReportGeneratePercentage();
    });

    $("#District-list").change(function() {
        gDistrictId = $("#District-list").val();
        //if(gDistrictId==0){
        //	$("#Region-list").val(0);
        //	gRegionId = $("#Region-list").val();
        //}		
        oTblFacilityReportingStatus.fnDraw();
        getReportGeneratePercentage();
    });
    $("#OwnerType").change(function() {
        gOwnerTypeId = $("#OwnerType").val();
        oTblFacilityReportingStatus.fnDraw();
        getReportGeneratePercentage();
    });

	oTblFacilityReportingStatus.fnDraw();
	getReportGeneratePercentage();

});
