var gMonthId;
var gYearId;
var gCountryId;
var gCountryName = '';
var gItemGroupId;
var gItemNo;
var gRegionId = 0;
var gFLevelId = 0;
var gDistrictId = 0;
var gOwnerTypeId = 0;
var oTableStockFacility;
var endDate = new Date();
var ccLat = 0;
var ccLong = 0;
var fLevelMosList;
var fLevelMosListDetails;
var vImage = new Image();
var yearList;
var gFacilityCode;
var gFrequencyId=1;
var gStartYearId;
var gSartMonthId;
var gMosTypeId = 0;
var map;
var $ = jQuery.noConflict();
function getReportGeneratePercentage() {
    $.ajax({
        type: "POST",
	    url : baseUrl + "reporting_rate.php",
		data : {
			 operation : 'getPercentage',
			 CountryId : gCountryId,
			 ItemGroupId : gItemGroupId,
			 OwnerTypeId : $("#OwnerType").val(),
			 Year : gYearId,
			 Month : gMonthId,
             RegionId: $("#region-list").val(),
             DistrictId: $("#District-list").val(),
			 lan : lan
     
	 
		},
		success: function(response) {
		response = JSON.parse(response);

		 $('#Total').html(response.Total+' %'); 
		 $('#Facility').html(response.HealthFaclilities+' %'); 
		 $('#District').html(response.DistrictWarehouse+' %'); 
		 $('#Region').html(response.RegionalWarhouse+' %'); 
		 $('#Central').html(response.CentralWarehouse+' %'); 
		}
	});
}

function getFillRegion() {

    $.ajax({
        type: "POST",
        dataType: "json",
        url: baseUrl + "combo_generic.php",
        data: {
            "operation": 'getFillRegion',
            "CountryId": gCountryId,
            "UserId": userName,
            "lan": lan
        },
        success: function(response) {
            $.each(response, function(i, obj) {
                $('#region-list').append($('<option></option>').val(obj.RegionId).html(obj.RegionName));
            });

            var RegionList = response || [];
            var html = $.map(RegionList, function(obj) {
                return '<option value=' + obj.RegionId + '>' + obj.RegionName + '</option>';
            }).join('');

            $('#region-list').html(html);
            gRegionId = $('#region-list').val() == null ? 0 : $('#region-list').val();
			getFillDistrict();
        }
    });
}

function getFillDistrict() {
	$.ajax({
		type : "POST",
		dataType : "json",
		url : baseUrl + "combo_generic.php",
		data : {
			"operation" : 'getFillDistrict',
			"CountryId": gCountryId,
			"RegionId" : gRegionId,
			"lan" : lan
		},
		success : function(response) {	
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

function getLegendMos() {
	$.ajax({
		type : "POST",
		dataType : "json",
		url : baseUrl + "stock_status_at_facility_server.php",
		data : {
			"operation" : 'getLegendMos',
			"CountryId" : gCountryId,
			"FLevelId" : gFLevelId,
			"lan" : lan
		},
		success : function(response) {
			fLevelMosList = response.output1;
			fLevelMosListDetails = response.output2;
			var legendTable = '';

			$('#barchartlegend').html('');

			var x = "<table><tr>";
			var y = "</tr><tr>";
			var z = "</tr><tr>";
			
			var mos='';
			if(lan=='en-GB')
				mos='MOS';
			else
				mos='MSD';

			for (var i = 0; i < fLevelMosList.length; i++) {
				x +="<td><div style='width:100%;background-color:"+ fLevelMosList[i].ColorCode +";'>&nbsp;</div></td>";
				y += "<td>" + fLevelMosList[i].MosTypeName + "</td>";
				z += "<td>"+ mos +": " + fLevelMosList[i].MosLabel + "</td>";

			};
			x +="<td rowspan='2' style='vertical-align: bottom !important;'><img alt='Smiley face' height='60' width='42' src="+baseUrl+"leafletjs/images/NR.png /></td>";
			z += "<td>"+TEXT['Non-reported']+"</td>";
			legendTable = x + y + z + "</tr></table>";
			$('#barchartlegend').html(legendTable);
		}
	});
}

function getMostypeNameForBtn() {
	$.ajax({
		type : "POST",
		dataType : "json",
		url : baseUrl + "stock_status_at_facility_server.php",
		data : {
			"operation" : 'getLegendMos',
			"CountryId" : gCountryId,
			"FLevelId" :  gFLevelId,
			"lan" : lan
		},
		success : function(response) {
           
            var x1 = '<div class="btn-group pull-left">' + '<button id="0" class="btn btn-default active" type="button">All</button>';
          
            for (var i = 0; i < response.output1.length; i++) {
                x1 += '<button id = "' + response.output1[i].MosTypeId + '" class="btn btn-default" type="button">' + response.output1[i].MosTypeName + '</button>';
            }
			x1 = x1 + "</div>";
        
            
		
        	$('#fic-group-button').html('');

			$('#fic-group-button').html(x1);

			$(".btn-default").click(function() {
				gMosTypeId = this.id;
				//alert(gMosTypeId);
				$('.btn-default').removeClass('active');
				$('#' + this.id).addClass('active');
				callServer();
			
			});
		}
	});
	callServer();
}

function getFacilityLevel() {
	$.ajax({
		type : 'post',
		dataType : "json",
		url : baseUrl + 'stock_status_at_facility_server.php',
		data : {
			"operation" : 'getFacilityLevel',
            "lan" :lan
		},
		success : function(response) {
			$.each(response, function(i, obj) {
				$('#facility-level-list').append($('<option></option>').val(obj.FLevelId).html(obj.FLevelName));
			});
			$("#facility-level-list").val(99);
			gFLevelId = $("#facility-level-list").val();
			getLegendMos();
           
          getMostypeNameForBtn();
		}
	});
}
/*
function getRegionList() {
	$.ajax({
		type : 'post',
		dataType : "json",
		url : baseUrl + 'stock_status_at_facility_server.php',
		data : {
			"operation" : 'getRegionList',
			"CountryId" : gCountryId,
			"userName" : userName
		},
		success : function(response) {
			var regionList = response || [];
			
			var html = $.map(regionList, function(obj) {
				return '<option value=' + obj.RegionId + '>' + obj.RegionName + '</option>';
			}).join('');

			$('#region-list').html(html);

			gRegionId = $('#region-list').val() == null ? -11 : $('#region-list').val();
            //getDistrictList();
		}
	});
}

function getDistrictList() {
	$.ajax({
		type : 'post',
		dataType : "json",
		url : baseUrl + 'stock_status_at_facility_server.php',
		data : {
			"operation" : 'getDistrictList',
			"RegionId" : gRegionId
		},
		success : function(response) {
			var districtList = response || [];
			
			var html = $.map(districtList, function(obj) {
				return '<option value=' + obj.DistrictId + '>' + obj.DistrictName + '</option>';
			}).join('');

			$('#District-list').html(html);

			gDistrictId = $('#District-list').val() == null ? -11 : $('#District-list').val();
		}
	});
}
*/
function getCountryInfoById() {

	$.ajax({
		type : 'post',
		dataType : "json",
		url : baseUrl + 'stock_status_at_facility_server.php',
		data : {
			"operation" : 'getCountryInfoById',
			"CountryId" : gCountryId
		},
		success : function(response) {
			gCountryId = $("#country-list").val();
			gCountryName = response[0].CountryName;
			ccLat = parseFloat(response[0].CenterLat);
			ccLong = parseFloat(response[0].CenterLong);
			//map.panTo(new L.LatLng(ccLat, ccLong));
			//map.setZoom(response[0].ZoomLevel);
			getFillRegion();
			getCountryProductList();
		}
	});
}

function getCountryProductList() {

	$.ajax({
		type : 'post',
		dataType : "json",
		url : baseUrl + 'stock_status_at_facility_server.php',
		data : {
			"operation" : 'getCountryProductList',
			"MonthId" : gMonthId,
			"YearId" : gYearId,
			"CountryId" : gCountryId,
			"ItemGroupId" : gItemGroupId
		},
		success : function(response) {
			var itemList = response || [];
			var html = $.map(itemList, function(obj) {
				return '<option value=' + obj.ItemNo + '>' + obj.ItemName + '</option>';
			}).join('');

			$('#item-list').html(html);

			gItemNo = $('#item-list').val() == null ? -11 : $('#item-list').val();

			//callServer();
		}
	});
}

var markers = [];
var markers2 = [];
/*
function getStockStatusAtFacilityForMap() {

	$.ajax({
		type : 'post',
		dataType : "json",
		url : baseUrl + 'stock_status_at_facility_server.php',
		data : {
			"operation" : 'getStockStatusAtFacilityForMap',
			"MonthId" : gMonthId,
			"YearId" : gYearId,
			"CountryId" : gCountryId,
			"ItemGroupId" : gItemGroupId,
			"ItemNo" : gItemNo,
			"RegionId" : gRegionId,
			"FLevelId" : gFLevelId,
            "DistrictId" : gDistrictId,
            "OwnerTypeId" : gOwnerTypeId,
            "MosTypeId" : gMosTypeId
		},
		success : function(data) {			

			if (markers.length > 0)
				removeAllMarkers();

			for (var i = 0; i < data.length; i++) {
				
				if (data[i].Latitude != '' && data[i].Longitude != '') {

					var vLat = parseFloat(data[i].Latitude);
					var vLong = parseFloat(data[i].Longitude);
					

					if (data[i].MOS == null) {
						
						var marker = L.marker([vLat, vLong], {
							icon : markerIconNr
						}).addTo(map).bindPopup("<strong>Facility: " + data[i].FacilityName + "</strong><br/>Closing Balance:<br/>AMC:<br/>MOS: ");

						markers.push(marker);
						markers2[data[i].FacilityId] = marker;
					}
					
					else {
						var markerIconMos = '';
						var vMOS = parseFloat(data[i].MOS);
						var vAMC = parseInt(data[i].AMC);
						
						var width = 0;
						var height = 0;

						$.each(fLevelMosListDetails, function(i, obj) {

							width = parseInt(obj.IconMos_Width);
							height = parseInt(obj.IconMos_Height);

							if (vMOS >= obj.MinMos && vMOS < obj.MaxMos) {
								
								var MosIcon = L.Icon.extend({
									options : {
										shadowUrl : baseUrl + 'leafletjs/images/marker-shadow.png',
										iconSize : [width, height],
										shadowSize : [height, height],
										iconAnchor : [height / 3.5, height + 1],
										shadowAnchor : [height / 3.5, height + 1],
										popupAnchor : [0, -1 * height + 5]
									}
								});

								markerIconMos = new MosIcon({
									iconUrl : baseUrl + 'leafletjs/images/' + obj.IconMos
								});
							}

						});

						var marker = L.marker([vLat, vLong], {
							icon : markerIconMos
						}).addTo(map).bindPopup("<strong>Facility: " + data[i].FacilityName + "</strong><br/>Closing Balance:" + data[i].ClStock + "<br/>AMC: " + data[i].AMC + "<br/>MOS: " + vMOS);

						markers.push(marker);
						markers2[data[i].FacilityId] = marker;
					}

				}
			}
		}
	});
}
*/
function removeAllMarkers() {
	for (i in markers) {
		if (markers[i] != undefined) {
			try {
				map.removeLayer(markers[i]);
			} catch(e) {
				console.log("problem with " + e + markers[i]);
			}
		}
	}
}

function clearMap() {
	for (i in map._layers) {
		if (map._layers[i]._path != undefined) {
			try {
				map.removeLayer(map._layers[i]);
			} catch(e) {
				console.log("problem with " + e + map._layers[i]);
			}
		}
	}
}

function getItemGroupFrequency() {
	$.ajax({
		type : "POST",
		dataType : "json",
		url : baseUrl + "combo_generic.php",
		data : {
			"operation" : 'getItemGroupFrequency',
			"CountryId" : gCountryId,
			"ItemGroupId" : gItemGroupId
		},
		success : function(response) {
			gFrequencyId = response[0].FrequencyId;
			gSartMonthId = response[0].StartMonthId;
			gStartYearId = response[0].StartYearId;
			getMonthByFrequencyId();
		}
	});
}

function getMonthByFrequencyId() {
	$.ajax({
		type : "POST",
		dataType : "json",
		url : baseUrl + "combo_generic.php",
		data : {
			"operation" : 'getMonthByFrequencyId',
			"FrequencyId" : gFrequencyId,
			"lan" : lan
		},
		success : function(response) {
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
			} else if (gFrequencyId == 2) {
				endDate.setMonth(objInit.svrLastMonth - 1);
				endDate.setFullYear(objInit.svrLastYear);
				endDate.lastQuarter();
			}

			$("#month-list").val(endDate.getMonth() + 1);
			$("#year-list").val(endDate.getFullYear());

			gMonthId = $('#month-list').val() == null ? -99 : $('#month-list').val();

			getCountryProductList();
			oTableStockFacility.fnDraw();
            getReportGeneratePercentage();

		}
	});
}


$('#stock-status-at-facility').click(function(event) {
	var id = $(event.target.parentNode).attr('id');

	var aData;
	$(oTableStockFacility.fnSettings().aoData).each(function() {
		if ($(this.nTr).attr('id') == id) {
			$(this.nTr).addClass('row_selected');
			aData = oTableStockFacility.fnGetData(this.nTr);
		} else
			$(this.nTr).removeClass('row_selected');
	});
	if (aData) {
		if (aData[6] != '' && aData[7] != '') {
			//map.panTo(new L.LatLng(parseFloat(aData[6]), parseFloat(aData[7])));
			// var mapCenter = new L.LatLng(parseFloat(aData[6]), parseFloat(aData[7]));
			// map.setView(mapCenter, 13)
			//map.setZoom(10);
			//markers2[id].openPopup();

			//map.panTo(markers2[id].getLatLng());
			panPopup(parseFloat(aData[6]), parseFloat(aData[7]), id);
		}
	}
});

function panPopup(lat, lng, fid) {
	
	map.once('moveend', function(e) {
		map.setZoom(12);
		//markers2[fid].openPopup();
	});
	map.panTo([lat, lng]);

}

function callServer() {
	$('.country-map-head').html('Country map of ' + gCountryName);
	//$('#itemname-header').html('Stock status of <span id="itemno-head">' + $("#item-list").find('option:selected').text() + '</span>');
   
	$('.btn-default').removeClass('active');
	$('#' + gMosTypeId).addClass('active');
    
	oTableStockFacility.fnDraw();
	//getStockStatusAtFacilityForMap();
    getReportGeneratePercentage();
	
	//add rubel
	getStockStatusAtFacilityForleafletMap();
}

$(function() {

	$.each(gMonthList, function(i, obj) {
		$('#month-list').append($('<option></option>').val(obj.MonthId).html(obj.MonthName));
	});

	endDate.setMonth(objInit.svrLastMonth - 1);
	$("#month-list").val(objInit.svrLastMonth);

	gMonthId = $('#month-list').val();

	$.each(gYearList, function(i, obj) {
		$('#year-list').append($('<option></option>').val(obj.YearName).html(obj.YearName));
	});

	$("#year-list").val(endDate.getFullYear());

	gYearId = $('#year-list').val();

	$.each(gCountryListFLevel, function(i, obj) {
		$('#country-list').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
	});
	
	$('#country-list').val(gUserCountryId);

	gCountryId = $('#country-list').val();
	//getFillRegion();
	
	$.each(gItemGroupList, function(i, obj) {
		$('#item-group-list').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
	});
	
	$('#item-group-list').val(gUserItemGroupId);
	gItemGroupId = $("#item-group-list").val();
	/*$.each(gDistrictList, function(i, obj) {
	$('#District-list').append($('<option></option>').val(obj.DistrictId).html(obj.DistrictName));
	});
	gDistrictId = $("#District-list").val();*/
    /*
	$.each(gRegionList, function(i, obj) {
	$('#region-list').append($('<option></option>').val(obj.RegionId).html(obj.RegionName));
	});
	gRegionId = $("#region-list").val();
    getFillDistrict();
	*/
	$.each(gOwnerTypeList, function(i, obj) {
	$('#OwnerType').append($('<option></option>').val(obj.OwnerTypeId).html(obj.OwnerTypeName));
	});
    
	$('#OwnerType').val(gDetaultOwnerTypeId);
	gOwnerTypeId = $("#OwnerType").val();
	
	//gItemGroupId = $('#item-group-list').val();
    
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

		callServer();

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

		callServer();
	});

	$("#month-list").change(function() {
		endDate.setMonth($("#month-list").val() - 1);

		gMonthId = $("#month-list").val();
		gYearId = $("#year-list").val();

		callServer();
	});

	$("#year-list").change(function() {
		endDate.setYear($("#year-list").val());
		endDate.setMonth($("#month-list").val() - 1);

		gMonthId = $("#month-list").val();
		gYearId = $("#year-list").val();

		callServer();
	});

	//gCountryId = $("#country-list").val();

	$("#country-list").change(function() {
		gCountryId = $("#country-list").val();
		getCountryInfoById();
		//getItemGroupFrequency();
		//getFillRegion();
		gRegionId = 0;        
		gDistrictId = 0;
        callServer();
		
	});

	$("#item-group-list").change(function() {
		gItemGroupId = $("#item-group-list").val();
		getCountryProductList();
		callServer();
	});

	$("#item-list").change(function() {
		gItemNo = $("#item-list").val();

		callServer();

	});

	$("#region-list").change(function() {
		gRegionId = $("#region-list").val();
        getFillDistrict();
		gDistrictId = 0;
		callServer();

	});
    
    $("#District-list").change(function() {
		gDistrictId = $("#District-list").val();
			callServer();
	});
    $("#OwnerType").change(function() {
		gOwnerTypeId = $("#OwnerType").val();
		callServer();
	});
    //rumaiya
    $("#"+ gMosTypeId).change(function() {
		gMosTypeId = this.id;
			callServer();
	});
    //

	$("#facility-level-list").change(function() {
		gFLevelId = $("#facility-level-list").val();
		getMostypeNameForBtn();
        //getLegendMos();
		callServer();
	});
   
	getCountryInfoById();
	getFacilityLevel();
	
	callServer();

});




oTableStockFacility = $('#stock-status-at-facility').dataTable({
	"bFilter" : true,
	"bJQueryUI" : true,
	"bSort" : true,
	"bInfo" : true,
	"bPaginate" : true,
	"bSortClasses" : false,
	"bProcessing" : true,
	"bServerSide" : true,
	"aaSorting" : [[1, 'asc']],
	"sPaginationType" : "full_numbers",
	"aLengthMenu" : [[25, 50, 100], [25, 50, 100]],
	"iDisplayLength" : 25,
	"sAjaxSource" : baseUrl + "stock_status_at_facility_server.php",
	"fnRowCallback" : function(nRow, aData, iDisplayIndex) {
		$(nRow).attr('id', aData[5]);
		return nRow;
	},
	"fnServerData" : function(sSource, aoData, fnCallback) {
		aoData.push({
			"name" : "operation",
			"value" : "getStockStatusAtFacility"
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
			"name" : "MonthId",
			"value" : gMonthId
		});
		aoData.push({
			"name" : "YearId",
			"value" : gYearId
		});
		aoData.push({
			"name" : "CountryId",
			"value" : gCountryId
		});
		aoData.push({
			"name" : "ItemGroupId",
			"value" : gItemGroupId
		});
		aoData.push({
			"name" : "ItemNo",
			"value" : gItemNo
		});
		aoData.push({
			"name" : "RegionId",
			"value" : gRegionId
		});
		aoData.push({
			"name" : "FLevelId",
			"value" : gFLevelId
		});
        aoData.push({
			"name" : "DistrictId",
			"value" : gDistrictId
		});
        aoData.push({
			"name" : "OwnerTypeId",
			"value" : gOwnerTypeId
		});
		aoData.push({
			"name" : "MosTypeId",
			"value" : gMosTypeId
		});
		$.ajax({
			"dataType" : 'json',
			"type" : "GET",
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
		"sWidth" : "10%",
		"bSearchable" : false
	}, {
		"sClass" : "param-value",
		"sWidth" : "30%",
		"bSortable" : true,
		"bSearchable" : true

	}, {
		"sWidth" : "20%",
		"bSortable" : true,
		"sClass" : "right-aln",
		"bSearchable" : true
	}, {
		"sWidth" : "20%",
		"bSortable" : true,
		"sClass" : "right-aln",
		"bSearchable" : true
	}, {
		"sWidth" : "20%",
		"bSortable" : true,
		"sClass" : "right-aln",
		"bSearchable" : true
	}]
});



function getStockStatusAtFacilityForleafletMap(){
if (map != undefined) { map.remove(); } 
	//console.log(ccLat+'='+ ccLong);
	map = L.map("map", {
		fullscreenControl: true,
        attributionControl: false,		
        zoomControl: false
    }).setView(new L.LatLng(ccLat, ccLong), 5);//setView(new L.LatLng(17.814666, -1.700953), 5);

    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        detectRetina: true,
        maxNativeZoom: 17
    }).addTo(map);
	
	
	new L.Control.Zoom({ position: 'topleft' }).addTo(map);
	
    var leafletView = new PruneClusterForLeaflet();
    leafletView.BuildLeafletClusterIcon = function(cluster) {
        var e = new L.Icon.MarkerCluster();

        e.stats = cluster.stats;
        e.population = cluster.population;
        return e;
    };

   // var colors = ['#ff4b00', '#bac900', '#EC1813', '#55BCBE', '#D2204C', '#FF0000', '#ada59a', '#3e647e'],
    var colors = ['#ada59a', '#ada59a', '#ff4b00', '#ff4b00', '#ff4b00', '#ff4b00', '#ff4b00', '#ff4b00'],
        pi2 = Math.PI * 2;

    L.Icon.MarkerCluster = L.Icon.extend({
        options: {
            iconSize: new L.Point(44, 44),
            className: 'prunecluster leaflet-markercluster-icon'
        },

        createIcon: function () {
            // based on L.Icon.Canvas from shramov/leaflet-plugins (BSD licence)
            var e = document.createElement('canvas');
            this._setIconStyles(e, 'icon');
            var s = this.options.iconSize;
            e.width = s.x;
            e.height = s.y;
            this.draw(e.getContext('2d'), s.x, s.y);
            return e;
        },

        createShadow: function () {
            return null;
        },
		
        draw: function(canvas, width, height) {

			var lol = 0;

            var start = 0;
            for (var i = 0, l = colors.length; i < l; ++i) {

                var size = this.stats[i] / this.population;


                if (size > 0) {
                    canvas.beginPath();
                    canvas.moveTo(22, 22);
                    canvas.fillStyle = colors[i];
                    var from = start + 0.14,
                        to = start + size * pi2;

                    if (to < from) {
                        from = start;
                    }
                    canvas.arc(22,22,22, from, to);

                    start = start + size*pi2;
                    canvas.lineTo(22,22);
                    canvas.fill();
                    canvas.closePath();
                }

            }

            canvas.beginPath();
            canvas.fillStyle = 'white';
            canvas.arc(22, 22, 18, 0, Math.PI*2);
            canvas.fill();
            canvas.closePath();

            canvas.fillStyle = '#555';
            canvas.textAlign = 'center';
            canvas.textBaseline = 'middle';
            canvas.font = 'bold 12px sans-serif';

            canvas.fillText(this.population, 22, 22, 40);
        }
    });


	
/*
    var size = 10000;
    var markers = [];
    for (var i = 0; i < size; ++i) {
        var marker = new PruneCluster.Marker(59.91111 + (Math.random() - 0.5) * Math.random() * 0.00001 * size, 10.752778 + (Math.random() - 0.5) * Math.random() * 0.00002 * size);

        // This can be a string, but numbers are nice too
        marker.category = Math.floor(Math.random() * Math.random() * colors.length);

        markers.push(marker);
        leafletView.RegisterMarker(marker);
    }
	*/
	$.ajax({
		type : 'post',
		dataType : "json",
		url : baseUrl + 'stock_status_at_facility_server.php',
		data : {
			"operation" : 'getStockStatusAtFacilityForMap',
			"MonthId" : gMonthId,
			"YearId" : gYearId,
			"CountryId" : gCountryId,
			"ItemGroupId" : gItemGroupId,
			"ItemNo" : gItemNo,
			"RegionId" : gRegionId,
			"FLevelId" : gFLevelId,
            "DistrictId" : gDistrictId,
            "OwnerTypeId" : gOwnerTypeId,
            "MosTypeId" : gMosTypeId
		},
		success : function(data) {			
		var size = data.length;
		var markers = [];
		
			//if (markers.length > 0)
			//	removeAllMarkers();
		for (var i = 0; i < size; ++i) {
			if (data[i].Latitude != '' && data[i].Longitude != '') {
				var vLat = parseFloat(data[i].Latitude);
				var vLong = parseFloat(data[i].Longitude);
				var vMOS = parseFloat(data[i].MOS);
				var vAMC = parseInt(data[i].AMC);
				var width = 0;
				var height = 0;
				//var marker = new PruneCluster.Marker(vLat, vLong);
				if (data[i].MOS == null) {
					 var marker = new PruneCluster.Marker(vLat, vLong, {
						popup: "<strong>Facility: " + data[i].FacilityName + "</strong><br/>Closing Balance:<br/>AMC:<br/>MOS: ",
						icon: L.icon({
							iconUrl: baseUrl+'leafletjs/images/NR.png',
							iconSize: [45, 70]
						})
					});
				}
				else{
					
					$.each(fLevelMosListDetails, function(i, obj) {
							width = parseInt(obj.IconMos_Width);
							height = parseInt(obj.IconMos_Height);

							if (vMOS >= obj.MinMos && vMOS < obj.MaxMos) {
								/*
								var MosIcon = L.Icon.extend({
									options : {
										shadowUrl : baseUrl + 'leafletjs/images/marker-shadow.png',
										iconSize : [width, height],
										shadowSize : [height, height],
										iconAnchor : [height / 3.5, height + 1],
										shadowAnchor : [height / 3.5, height + 1],
										popupAnchor : [0, -1 * height + 5]
									}
								});*/

								markerIconMos = 'leafletjs/images/' + obj.IconMos;
								//markerIconMos = new MosIcon({
								//	iconUrl : baseUrl + 'leafletjs/images/' + obj.IconMos
								//});
							}

						});
						
					var marker = new PruneCluster.Marker(vLat, vLong, {
						popup: "<strong>Facility: " + data[i].FacilityName + "</strong><br/>Closing Balance:" + data[i].ClStock + "<br/>AMC: " + data[i].AMC + "<br/>MOS: " + vMOS,
						icon: L.icon({
							//iconUrl: baseUrl+'leafletjs/images/marker-shadow.png',
							iconUrl: baseUrl+markerIconMos,
							//iconSize: [width, height]
							iconSize: [45, 70]
						})
					});

					
				}
				//// This can be a string, but numbers are nice too
				//marker.category = Math.floor(Math.random() * Math.random() * colors.length);

				markers.push(marker);
				leafletView.RegisterMarker(marker);
				}
		}
	
	
		map.setZoom(7);		
		}
	});
	
	
	
	
	
/*
    window.setInterval(function () {
        for (i = 0; i < size / 2; ++i) {
            var coef = i < size / 8 ? 10 : 1;
            var ll = markers[i].position;
            ll.lat += (Math.random() - 0.5) * 0.00001 * coef;
            ll.lng += (Math.random() - 0.5) * 0.00002 * coef;
        }

        leafletView.ProcessView();
    }, 500);
*/
    map.addLayer(leafletView);

    
	
}
