var facilityTable;
var tblFacilityGroupMap;
var RecordId = '';
var CountryId;
var CenterLat;
var CenterLong;
var ZoomLevel;
var marker;
var geocoder;
var map;
var infowindow = new google.maps.InfoWindow();
var markersArray = [];
var LatLong;
var button = 1;
var nomore = 0;
var Point;
var msg;
var userId = '';
var engbId = '';
var AssignedItemList = new Array();
var gCountryId;
var facilityId;
/****************************************Map Function***********************************************/
var $ = jQuery.noConflict();

google.maps.visualRefresh = true;

function initialize() {
    geocoder = new google.maps.Geocoder();
    var mapOptions = {
        center: new google.maps.LatLng(9.65057659, 2.40600585),
        zoom: 7,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map"), mapOptions);
}

google.maps.event.addDomListener(window, 'load', initialize);

function clearOverlays() {
    for (var i = 0; i < markersArray.length; i++) {
        markersArray[i].setMap(null);
    }
    markersArray = [];
}

function updateMarkerPosition(latLng) {
    document.getElementById('location').value = [latLng.lat(), latLng.lng()].join(', ');
    LatLong = latLng;
}

function updateMarkerAddress(str) {
    $('#FacilityAddress').val(str);
    document.getElementById('address').innerHTML = str;
}

function getinfocon(str) {
    infowindow.setContent(str);
    infowindow.open(map, marker);
}

function geocodePosition(pos) {
    geocoder.geocode({
        latLng: pos
    }, function(responses) {
        if (responses && responses.length > 0) {
            updateMarkerAddress(responses[0].formatted_address);
        } else {
            updateMarkerAddress('Cannot determine address at this location.');
        }
    });
}

function geocodePositionForInfoW(pos) {
    geocoder.geocode({
        latLng: pos
    }, function(responses) {
        if (responses && responses.length > 0) {
            getinfocon(responses[0].formatted_address);
        } else {
            getinfocon('Cannot determine address at this location.');
        }
    });
}

function addpoint() {

    google.maps.event.addListener(map, "mouseover", function(e) {

        if (button == 1 && nomore == 0) {
            clearOverlays();
            var image = {
                url: baseUrl + 'images/icon/mloc.png',
                size: new google.maps.Size(32, 32),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(16, 32),
                scaledSize: new google.maps.Size(32, 32)
            };
            marker = new google.maps.Marker({
                position: e.latLng,
                map: map,
                icon: image,
                draggable: true
            });
            markersArray.push(marker);
            google.maps.event.addListener(marker, "dragstart", function() {
                infowindow.close();
            });
            google.maps.event.addListener(marker, "drag", function() {
                updateMarkerPosition(marker.getPosition());
            });
            google.maps.event.addListener(marker, 'dragend', function() {
                geocodePosition(marker.getPosition());
            });
            google.maps.event.addListener(marker, 'click', function() {
                button = 0;
                geocodePositionForInfoW(marker.getPosition());
                geocodePosition(marker.getPosition());
            });
            nomore = 1;
        }
    });
    google.maps.event.addListener(map, "mousemove", function(p) {
        if (button == 1) {
            marker.setPosition(p.latLng);
            updateMarkerPosition(marker.getPosition());
        }
    });
    google.maps.event.addListener(map, 'click', function() {
        button = 0;
        geocodePositionForInfoW(marker.getPosition());
        geocodePosition(marker.getPosition());
    });
}

function searchLocations() {
    if (button == 1 && nomore == 0) {
        button = 0;
        nomore = 1;
        var address = document.getElementById("addressInput").value;
        var add = address;
        if (address == "") {
            msg = "Please enter an area name to see nearby location.";
            onErrorMsg(msg);
            return;
        }
        var address = document.getElementById("addressInput").value + " " + $('#ACountryId option[value=' + $('#ACountryId').val() + ']').text();
        clearOverlays();

        geocoder.geocode({
            'address': address
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                var image = {
                    url: baseUrl + 'images/icon/mloc.png',
                    size: new google.maps.Size(32, 32),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(16, 32),
                    scaledSize: new google.maps.Size(32, 32)
                };
                marker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location,
                    icon: image,
                    draggable: true
                });
                markersArray.push(marker);
                updateMarkerPosition(marker.getPosition());
                var bounds = new google.maps.LatLngBounds();
                bounds.extend(marker.getPosition());
                map.fitBounds(bounds);
                map.setZoom(10);
                geocodePosition(marker.getPosition());
                geocodePositionForInfoW(marker.getPosition());

                google.maps.event.addListener(marker, "dragstart", function() {
                    infowindow.close();
                });
                google.maps.event.addListener(marker, "drag", function() {
                    updateMarkerPosition(marker.getPosition());
                });
                google.maps.event.addListener(marker, 'dragend', function() {
                    geocodePosition(marker.getPosition());
                });
                google.maps.event.addListener(marker, 'click', function() {
                    geocodePositionForInfoW(marker.getPosition());
                });
            } else {
                msg = "Search was not successful for the following reason: " + status;
                onErrorMsg(msg);
            }
        });
    } else {
        msg = "Already one location point is placed.";
        onErrorMsg(msg);
    }
}

function callmap() {
    $.ajax({
        "type": "POST",
        "url": baseUrl + 't_facility_datasource.php',
        "data": {
            action: 'getCountryLocation',
            countryId: $('#ACountryId').val()
        },
        "success": function(response) {
            response = JSON.parse(response);
            if (response.success == true) {
                CountryId = response.CountryId;
                CenterLat = response.CenterLat;
                CenterLong = response.CenterLong;
                ZoomLevel = parseInt(response.ZoomLevel);
            }
        }
    });
}

function addMarkerOnEdit(latlng) {
    if (latlng == "") {
        msg = "Location not set.";
        onInfoMsg(msg);

        var bounds = new google.maps.LatLngBounds();
        var point = new google.maps.LatLng(parseFloat(CenterLat), parseFloat(CenterLong));
        bounds.extend(point);
        map.setCenter(bounds.getCenter(), map.fitBounds(bounds));
        map.setZoom(ZoomLevel);

        button = 1;
        nomore = 0;
    } else {
        button = 0;
        nomore = 1;
        clearOverlays();
        var image = {
            url: baseUrl + 'images/icon/mloc.png',
            size: new google.maps.Size(32, 32),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(16, 32),
            scaledSize: new google.maps.Size(32, 32)
        };
        geocoder.geocode({
            'latLng': latlng
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                marker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    icon: image,
                    draggable: true
                });
                markersArray.push(marker);
                geocodePositionForInfoW(marker.getPosition());
                updateMarkerPosition(marker.getPosition());

                var bounds = new google.maps.LatLngBounds();
                bounds.extend(marker.getPosition());
                map.fitBounds(bounds);
                map.setZoom(10);
                geocodePosition(marker.getPosition());

                google.maps.event.addListener(marker, "dragstart", function() {
                    infowindow.close();
                });
                google.maps.event.addListener(marker, "drag", function() {
                    updateMarkerPosition(marker.getPosition());
                });
                google.maps.event.addListener(marker, 'dragend', function() {
                    geocodePositionForInfoW(marker.getPosition());
                    geocodePosition(marker.getPosition());
                });
                google.maps.event.addListener(marker, 'click', function() {
                    geocodePositionForInfoW(marker.getPosition());
                });
            }
        });
    }
}

/******************************************************Facility Related**********************************************/

function resetForm(id) {
    $('#' + id).each(function() {
        this.reset();
    });
}

/* When you click on "Back to List" button*/
function onListPanel() {
    $('.list-panel, .btn-form').show();
    $('.form-panel, .btn-list').hide();
    $('#filter_panel_1, #ServiceAreaId, #AFTypeId, #AFLevelId ').show();
    $('#PrintBTN, #PrintBTN1 ').show();
    //$('#filter_panel').show();
    $('#list-panel').show();
    $('#entry_panel').hide();
    clearOverlays();
    button = 1;
    nomore = 0;
    $('#row-facility-groupmap-grid').hide();
    $('#row-facility-groupmap-entry').hide();
}
function onListPanelFacilityGroupMap() {
    $('#row-facility-groupmap-grid').show();
    $('#row-facility-groupmap-entry').hide();
    clearOverlays();
}
/* When you click on "Add Record" button*/
function onFormPanel() {
	 
	 $('#filter_panel_1, #ServiceAreaId, #AFTypeId, #AFLevelId ').hide();
	 
    if ($('#ACountryId').val() == '') {
        msg = "Select a country First.";
        onErrorMsg(msg);
    } else {
        resetForm("facility_form");
        RecordId = '';
        AssignedItemList = new Array();       
        $('#PrintBTN, #PrintBTN1 ').hide();
        $('#counId').val($('#ACountryId').val());
        $('#RegionId').val($('#ARegionId').val());
        $('#FTypeId').val($('#AFTypeId').val());
        $('#FLevelId').val($('#AFLevelId').val());
        $('#ADistrict-list').val($('#District-list').val());
        $('#AOwnerType').val($('#OwnerType').val());
        $('#AServiceAreaId').val($('#ServiceAreaId').val());


        getFacilityCode();
        $('.list-panel, .btn-form').hide();
        $('.form-panel, .btn-list').show();
       // $('#filter_panel').hide();
        $('#list-panel').hide();
        $('#entry_panel').show();
        $("#btnAdd").show();
        $("#addressInput").val('');
        initialize();

        var bounds = new google.maps.LatLngBounds();
        var point = new google.maps.LatLng(parseFloat(CenterLat), parseFloat(CenterLong));
        bounds.extend(point);
        map.setCenter(bounds.getCenter(), map.fitBounds(bounds));
        map.setZoom(ZoomLevel);
    }
}

function onFormPanelFacilityGroupMap() {
    //alert('');
    resetForm("frm-facility-groupmap");
    $('#FacilityId').val(facilityId);
    //$('#FacilityServiceId').val('');
    $('#row-facility-groupmap-grid').hide();
    $('#row-facility-groupmap-entry').show();
}

function onEditPanel() {
    $('.list-panel, .btn-form').hide();
    $('.form-panel, .btn-list').show();
    $('#PrintBTN, #PrintBTN1 ').hide();
    //$('#filter_panel').hide();
    $('#filter_panel_1, #ServiceAreaId, #AFTypeId, #AFLevelId ').hide();
    $('#list-panel').hide();
    $('#entry_panel').show();
    $("#addressInput").val('');
    initialize();
    //$('#RegionId').val($('#ARegionId').val());
    //$('#FTypeId').val($('#AFTypeId').val());
    //$('#FLevelId').val($('#AFLevelId').val());
    /*$('#ADistrict-list').val($('#District-list').val());
     $('#AOwnerType').val($('#OwnerType').val());
     $('#AServiceAreaId').val($('#ServiceAreaId').val());*/
    addMarkerOnEdit(Point);
}

function bPPM() {
    var checkboxstate = document.getElementById('AgentType').checked;
    $('#AgentType').val(checkboxstate);
}

function onComboFacilityType() {
    $.getJSON(baseUrl + "t_combo.php", {
        action: 'getFacilityType'
    }, function(response) {
        for (var i = 0; i < response.length; i++) {
            $('#AFTypeId').append($('<option></option>').val(response[i].FTypeId).html(response[i].FTypeName));
            $('#FTypeId').append($('<option></option>').val(response[i].FTypeId).html(response[i].FTypeName));
        }
    });
}



function onComboRegionList() {
    $.getJSON(baseUrl + "t_combo.php", {
        action: 'getRegionList',
        CountryId: $('#ACountryId').val()
    }, function(response) {

        var regionList = response || [];
        //var regionList2 = response || [];

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

        //gItemNo = $('#item-list').val() == null ? -11 : $('#item-list').val();

        // for (var i = 0; i < response.length; i++) {
        // $('#ARegionId').append($('<option></option>').val(response[i].RegionId).html(response[i].RegionName));
        // $('#RegionId').append($('<option></option>').val(response[i].RegionId).html(response[i].RegionName));
        // }
    });
}

function onComboFacilityWarehouse() {
    $.getJSON(baseUrl + "t_facility_datasource.php", {
        action: 'getFacilityWarehouse',
        CountryId: $('#ACountryId').val()
    }, function(response) {
        for (var i = 0; i < response.length; i++) {
            $('#PFacilityId').append($('<option></option>').val(response[i].FacilityId).html(response[i].FacilityName));
        }
        for (var i = 0; i < response.length; i++) {
            $('#SupplyFrom').append($('<option></option>').val(response[i].FacilityId).html(response[i].FacilityName));
        }
    });
}
/*
 ///OLD
 function onComboFacilityWarehouse() {
 $.getJSON(baseUrl + "t_facility_datasource.php", {
 action : 'getFacilityWarehouse',
 CountryId : $('#ACountryId').val()
 }, function(response) {
 // for (var i = 0; i < response.length; i++) {
 // $('#PFacilityId').append($('<option></option>').val(response[i].FacilityId).html(response[i].FacilityName));
 // }
 
 var parentFacilityList = response || [];
 
 var html = $.map(parentFacilityList, function(obj) {
 return '<option value=' + obj.FacilityId + '>' + obj.FacilityName + '</option>';
 }).join('');
 
 $('#PFacilityId').html(html);
 
 });
 
 }
 */




$('#ACountryId').change(function() {
    callmap();
    getFillRegion();
    onComboFacilityWarehouse();
});

$('#AFTypeId').change(function() {
    facilityTable.fnDraw();
});

$('#AFLevelId').change(function() {
    facilityTable.fnDraw();
});

$('#ARegionId').change(function() {
    getFillDistrict();
});

$('#District-list').change(function() {
    facilityTable.fnDraw();
});

$('#OwnerType').change(function() {
    facilityTable.fnDraw();
});

$('#ServiceAreaId').change(function() {
    facilityTable.fnDraw();
});

function fnFormatDetails(nTr) {
    var aData = facilityTable.fnGetData(nTr);
    var sOut = "<div id='firstsec'>";
    sOut += "<label><b>" + TEXT['Facility Phone'] + "</b></label><span class='colon'>: </span><span class='data'>" + aData[14] + "</span><br/>";
    sOut += "<label><b>" + TEXT['Facility Email'] + "</b></label><span class='colon'>:</span><span class='data'>" + unescape(aData[16]) + "</span><br/>";
    if (aData[18] != '' && aData[19] != '') {
        sOut += "<label><b>" + TEXT['Point of Location'] + "</b></label><span class='colon'>:</span><span class='data'>" + aData[18] + ", " + aData[19] + "</span>";
    } else {
        sOut += "<label><b>" + TEXT['Point of Location'] + "</b></label><span class='colon'>:</span><span class='data'>" + "N/A" + "</span>";
    }
    sOut += "</div><div id='secondsec'>";
    sOut += "<label><b>" + TEXT['Facility Fax'] + "</label></b><span class='colon'>:</span><span class='data'>" + unescape(aData[15]) + "</span><br/>";
    sOut += "<label><b>" + TEXT['Facility Manager'] + "</b></label><span class='colon'>:</span><span class='data'>" + aData[18] + "</span><br/>";
    //sOut += "<label><b>No of Facility</b></label><span class='colon'>:</span><span class='data'>" + aData[26] + "</span><br/>";
    sOut += "</div>";
    return sOut;
}

function getFacilityCode() {
    $.ajax({
        "type": "POST",
        "url": baseUrl + 't_facility_datasource.php',
        "data": {
            action: 'getFacilityCode',
            countryId: $('#ACountryId').val()
        },
        "success": function(response) {
            response = JSON.parse(response);
            if (response.success == true) {
                facCode = response.newFCode;
                $('#FacilityCode').val(facCode);
            }
        }
    });
}

function selStatus(gvalue) {
    checkStatus = $('#' + gvalue).prop('checked');
    if (checkStatus == true) {
        AssItem = {};
        AssItem['Id'] = 0;
        AssItem['ItemGroupNo'] = gvalue;
        AssignedItemList.push(AssItem);
    } else {
        AssItem = {};
        AssItem['Id'] = -1;
        AssItem['ItemGroupNo'] = gvalue;
        AssignedItemList.push(AssItem);
    }
}/************************************************Save/Update*******************************************************/


$('#facility_form').parsley({
    listeners: {
        onFieldValidate: function(elem) {
            if (!$(elem).is(':visible')) {
                return true;
            }
            return false;
        },
        onFormSubmit: function(isFormValid, event) {
            if (isFormValid) {
                onConfirmWhenAddEdit();
                return false;
            }
        }
    }
});


$('#frm-facility-groupmap').parsley({
    listeners: {
        onFieldValidate: function(elem) {
            if (!$(elem).is(':visible')) {
                return true;
            }
            return false;
        },
        onFormSubmit: function(isFormValid, event) {
            if (isFormValid) {
                onConfirmWhenAddEdit_FacilityGroupMap();
                return false;
            }
        }
    }
});

/*function onConfirmWhenAddEdit() {
 var count = 0;
 $('.items').each(function() {
 var checkcon = $(this).prop('checked');
 if (checkcon == true) {
 count++;
 }
 });
 //if (count > 0) {
 
 var fvalues = $('#facility_form').serialize() + '&AssignedItemList=' + JSON.stringify(AssignedItemList);
 
 $.ajax({
 "type" : "POST",
 "url" : baseUrl + "t_facility_datasource.php",
 "data" : fvalues,
 "success" : function(response) {
 if (response == 1) {
 var oSettings = facilityTable.fnSettings();
 var page = Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength);
 facilityTable.fnDraw();
 facilityTable.fnPageChange(page);
 if (RecordId == '') {
 msg = "Facility added successfully.";
 } else {
 msg = "Facility updated successfully.";
 }
 onSuccessMsg(msg);
 onListPanel();
 } else {
 msg = "Server processing Error.";
 onErrorMsg(msg);
 }
 }
 });
 //} else {
 //	msg = "Choose assigned group.";
 //	onErrorMsg(msg);
 //}
 }*/

function onConfirmWhenAddEdit() {
    //userId = $('#userId').val();
    //engbId = $('#en-GBId').val();
    
    var count = 0;
    var fvalues = $('#facility_form').serialize() + '&jUserId=' + userId + '&language=' + engbId;

    $.ajax({
        "type": "POST",
        "url": baseUrl + "t_facility_datasource.php",
        "data": fvalues,
        "success": function(response) {

            $msgType = JSON.parse(response)['msgType'];
            $msg = JSON.parse(response)['msg'];
            if ($msgType == "success") {
                var oSettings = facilityTable.fnSettings();
                var page = Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength);
                facilityTable.fnDraw();
                facilityTable.fnPageChange(page);
                onSuccessMsg($msg);
                onListPanel();
            } else {
                onErrorMsg($msg);
            }
        }
    });
}

function onConfirmWhenAddEdit_FacilityGroupMap() {
    //userId = $('#userId').val();
    //engbId = $('#en-GBId').val();
    //alert(userId);
    
    var fvalues = $('#frm-facility-groupmap').serialize() + '&jUserId=' + userId + '&language=' + engbId;
    //console.log(fvalues);

    $.ajax({
        "type": "POST",
        "url": baseUrl + "t_facility_datasource.php",
        "data": fvalues,
        "success": function(response) {
            $msgType = JSON.parse(response)['msgType'];
            $msg = JSON.parse(response)['msg'];
            if ($msgType == "success") {
                var oSettings = tblFacilityGroupMap.fnSettings();
                console.log(oSettings);
                var page = Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength);
                tblFacilityGroupMap.fnDraw();
                tblFacilityGroupMap.fnPageChange(page);
                onSuccessMsg($msg);
                onListPanelFacilityGroupMap();
            } else {
                onErrorMsg($msg);
            }
        }
    });
}


function onConfirmWhenDeleteFacilityGroupMap() {
    $.ajax({
        "type": "POST",
        "url": baseUrl + "t_facility_datasource.php",
        "data": 'action=deleteFacilityGroupMap&FacilityServiceId=' + FacilityServiceId + '&jUserId=' + userId + '&language=' + engbId,
        "success": function(response) {

            $msgType = JSON.parse(response)['msgType'];
            $msg = JSON.parse(response)['msg'];

            if ($msgType == "success") {
                var oSettings = tblFacilityGroupMap.fnSettings();
                var page = Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength);
                tblFacilityGroupMap.fnDraw();
                tblFacilityGroupMap.fnPageChange(page);
                onSuccessMsg($msg);
                onListPanelFacilityGroupMap();
            } else {
                onErrorMsg($msg);
            }
        }
    });
}
function onConfirmWhenDelete() {
    //userId = $('#userId').val();
    //engbId = $('#en-GBId').val();
    $.ajax({
        "type": "POST",
        "url": baseUrl + "t_facility_datasource.php",
        "data": 'action=deleteFacilityData&RecordId=' + RecordId + '&jUserId=' + userId + '&language=' + engbId,
        "success": function(response) {

            $msgType = JSON.parse(response)['msgType'];
            $msg = JSON.parse(response)['msg'];

            if ($msgType == "success") {
                var oSettings = facilityTable.fnSettings();
                var page = Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength);
                facilityTable.fnDraw();
                facilityTable.fnPageChange(page);
                onSuccessMsg($msg);
                onListPanel();
            } else {
                onErrorMsg($msg);
            }
        }
    });
}

/*function onConfirmWhenDelete() {
 $.ajax({
 "type" : "POST",
 "url" : baseUrl + "t_facility_datasource.php",
 "data" : 'action=deleteFacilityData&RecordId=' + RecordId,
 "success" : function(response) {
 if (response == 1) {
 var oSettings = facilityTable.fnSettings();
 var page = Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength);
 facilityTable.fnDraw();
 facilityTable.fnPageChange(page);
 msg = "Record has been deleted successfully.";
 onSuccessMsg(msg);
 } else {
 msg = "Server processing Error.";
 onErrorMsg(msg);
 }
 }
 });
 }*/

/*********************************************************On Ready**********************************************/
// 
// function onComboFacilityLevel() {
// $.getJSON(baseUrl + "t_combo.php", {
// action : 'getFacilityLevel'
// }, function(response) {
// for (var i = 0; i < response.length; i++) {
// $('#AFLevelId').append($('<option></option>').val(response[i].FLevelId).html(response[i].FLevelName));
// $('#FLevelId').append($('<option></option>').val(response[i].FLevelId).html(response[i].FLevelName));
// }
// });
// }



function getFillRegion() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: baseUrl + "combo_generic.php",
        data: {
            "operation": 'getFillRegion',
            "CountryId": $('#ACountryId').val(),
            "UserId": userName,
            "lan": lan
        },
        success: function(response) {
            $.each(response, function(i, obj) {
                $('#ARegionId').append($('<option></option>').val(obj.RegionId).html(obj.RegionName));
               // $('#RegionId').append($('<option></option>').val(obj.RegionId).html(obj.RegionName));
            });
			
            var RegionList = response || [];
            var html = $.map(RegionList, function(obj) {
                return '<option value=' + obj.RegionId + '>' + obj.RegionName + '</option>';
            }).join('');

            $('#ARegionId').html(html);
            //$('#RegionId').html(html);
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
			"CountryId": $('#ACountryId').val(),
			"RegionId" : $('#ARegionId').val(),
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
			
			facilityTable.fnDraw();
		}
	});
}

$(function() {

    userId = $('#userId').val();
    engbId = $('#en-GBId').val();

    $.each(gCountryList, function(i, obj) {
        $('#ACountryId').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
    });



    $('#ACountryId').val(gUserCountryId);

    gCountryId = $('#country-list').val();

    //$.each(gRegionList, function(i, obj) {
    //    $('#ARegionId').append($('<option></option>').val(obj.RegionId).html(obj.RegionName));
    //    $('#RegionId').append($('<option></option>').val(obj.RegionId).html(obj.RegionName));
    //});

    //$.each(gDistrictList, function(i, obj) {
   //     $('#District-list').append($('<option></option>').val(obj.DistrictId).html(obj.DistrictName));
   //     $('#ADistrict-list').append($('<option></option>').val(obj.DistrictId).html(obj.DistrictName));
   // });//

    $.each(gOwnerTypeList, function(i, obj) {
        $('#OwnerType').append($('<option></option>').val(obj.OwnerTypeId).html(obj.OwnerTypeName));
        $('#AOwnerType').append($('<option></option>').val(obj.OwnerTypeId).html(obj.OwnerTypeName));
    });

    $.each(gServiceAreaList, function(i, obj) {
        $('#ServiceAreaId').append($('<option></option>').val(obj.ServiceAreaId).html(obj.ServiceAreaName));
        $('#AServiceAreaId').append($('<option></option>').val(obj.ServiceAreaId).html(obj.ServiceAreaName));
    });
    $.each(gFLevelList, function(i, obj) {
        $('#AFLevelId').append($('<option></option>').val(obj.FLevelId).html(obj.FLevelName));
        $('#FLevelId').append($('<option></option>').val(obj.FLevelId).html(obj.FLevelName));
    });

    $.each(gItemGroupList, function(i, obj) {
        $('#ItemGroupId').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
    });

    $.each(gMonthList, function(i, obj) {
        $('#StartMonthId').append($('<option></option>').val(obj.MonthId).html(obj.MonthName));
    });

    $.each(gYearList, function(i, obj) {
        $('#StartYearId').append($('<option></option>').val(obj.YearName).html(obj.YearName));
    });

    document.getElementById('location').value = "";
    onListPanel();
    resetForm("facility_form");

	onComboFacilityType();
	getFillRegion();

    $('#btn-submit-facility').click(function() {
        if (!$("#facility_form").checkAlphabet())
            return false;
        $("#facility_form").submit();
    });

    $('#btn-submit-facility-groupmap').click(function() {
        if (!$("#frm-facility-groupmap").checkAlphabet())
            return false;
        $("#frm-facility-groupmap").submit();
    });

    $('body').animate({
        opacity: 1
    }, 500, function() {
        callmap();

        facilityTable = $('#facilityTable').dataTable({
            "bFilter": true,
            "bSort": true,
            "bInfo": true,
            "bPaginate": true,
            "bSortClasses": false,
            "bProcessing": true,
            "bServerSide": true,
            "aaSorting": [[2, 'asc']],
            "sPaginationType": "full_numbers",
            "aLengthMenu": [[25, 50, 100], [25, 50, 100]],
            "iDisplayLength": 25,
            "sAjaxSource": baseUrl + "t_facility_datasource.php",
            "fnDrawCallback": function(oSettings) {
                if (oSettings.aiDisplay.length == 0) {
                    return;
                }
                var nTrs = $('#facilityTable tbody tr');
                var iColspan = nTrs[0].getElementsByTagName('td').length;
                var sLastGroup = "";
                for (var i = 0; i < nTrs.length; i++) {
                    var iDisplayIndex = i;
                    var sGroup = oSettings.aoData[oSettings.aiDisplay[iDisplayIndex]]._aData[25];
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
                $('a.itmMore', facilityTable.fnGetNodes()).each(function() {
                    $(this).click(function() {
                        var nTr = this.parentNode.parentNode;
                        if ($(this).children('span').attr('class') == 'label label-success faminus') {
                            $(this).children('span').attr('class', 'label label-info');
                            var nRemove = $(nTr).next()[0];
                            nRemove.parentNode.removeChild(nRemove);
                        } else {
                            $(this).children('span').attr('class', 'label label-success faminus');
                            facilityTable.fnOpen(nTr, fnFormatDetails(nTr), 'details');
                        }
                    });
                });
                $('a.itmEdit', facilityTable.fnGetNodes()).each(function() {
                    $(this).click(function() {
                        var nTr = this.parentNode.parentNode;
                        var aData = facilityTable.fnGetData(nTr);
                        RecordId = aData[0];
                        AssignedItemList = new Array();

                        $('#row-facility-groupmap-grid').show();
                        facilityId = RecordId;
                        $('#FacilityId').val(facilityId);
                        tblFacilityGroupMap.fnDraw();

                        $.ajax({
                            "type": "POST",
                            "url": baseUrl + "t_facility_datasource.php",
                            "data": {
                                action: 'getAssignedGroup',
                                RecordId: RecordId
                            },
                            "success": function(response) {
                                if (response) {
                                    var ItemData = [];
                                    $('.items').prop('checked', false);
                                    ItemData = $.parseJSON(response);
                                    $.each(ItemData, function(index, value) {
                                        AssItem = {};
                                        AssItem['Id'] = 1;
                                        AssItem['ItemGroupNo'] = value;
                                        AssignedItemList.push(AssItem);

                                        $('#' + value).prop('checked', true);
                                    });
                                } else {
                                    msg = "Server processing Error.";
                                    onErrorMsg(msg);
                                }
                            }
                        });

                        $('body').animate({
                            opacity: 1
                        }, 500, function() {
                            facilityId = aData[0];

                            $('#RecordId').val(aData[0]);
                            $('#FacilityCode').val(aData[2]);
                            $('#FacilityName').val(aData[3]);
                            $('#RegionId').val(aData[5]);

                            if (aData[30] == 3) {
                                document.getElementById("AgentType").checked = true;
                            } else {
                                document.getElementById("AgentType").checked = false;
                            }

                            $('#PFacilityId').val(aData[10]);
                            $('#FacilityAddress').val(aData[11]);
                            $('#GroupName').val(aData[12]);
                            $('#FacilityPhone').val(aData[14]);
                            $('#FacilityFax').val(aData[15]);
                            $('#FacilityEmail').val(aData[16]);
                            $('#FacilityManager').val(aData[17]);
                            $('#FTypeId').val(aData[21]);
                            $('#FLevelId').val(aData[22]);
                            $('#RegionId').val(aData[23]);
                            $('#PFacilityId').val(aData[24]);
                            $('#FacilityCount').val(aData[26]);
                            $('#ADistrict-list').val(aData[27]);
                            $('#AOwnerType').val(aData[28]);
                            $('#AServiceAreaId').val(aData[29]);

                            if (isNaN(aData[18]) || (aData[18] == "")) {
                                $('#location').val("");
                                Point = "";
                                onEditPanel();
                                $("#btnAdd").show();
                            } else {
                                $("#btnAdd").hide();
                                Point = new google.maps.LatLng(parseFloat(aData[18]), parseFloat(aData[19]));
                                onEditPanel();
                                $('#location').val(aData[18] + ", " + aData[19]);
                            }
                            $('#counId').val($('#ACountryId').val());
                        });

                        /* msg = "Do you really want to edit this record?";
                         onCustomModal(msg, "onEditPanel");  */
                    });
                });

                $('a.itmDrop', facilityTable.fnGetNodes()).each(function() {
                    $(this).click(function() {
                        var nTr = this.parentNode.parentNode;
                        var aData = facilityTable.fnGetData(nTr);
                        RecordId = aData[0];
                        msg = "Do you really want to Delete this record?";
                        onCustomModal(msg, "onConfirmWhenDelete");
                    });
                });
            },
            "fnServerData": function(sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "action",
                    "value": 'getFacilityData'
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
                    "value": $('#ACountryId').val()
                });
                aoData.push({
                    "name": "ARegionId",
                    "value": $('#ARegionId').val()
                });
                aoData.push({
                    "name": "District-list",
                    "value": $('#District-list').val()
                });
                aoData.push({
                    "name": "OwnerType",
                    "value": $('#OwnerType').val()
                });
                aoData.push({
                    "name": "ServiceAreaId",
                    "value": $('#ServiceAreaId').val()
                });
                aoData.push({
                    "name": "FacilityType",
                    "value": $('#AFTypeId').val()
                });
                aoData.push({
                    "name": "FacilityLevel",
                    "value": $('#AFLevelId').val()
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
                }, {
                    "sClass": "SL",
                    "sWidth": "5%",
                    "bSortable": false,
                    "bVisible": false
                }, {
                    "sClass": "Code",
                    "sWidth": "5%",
                    "bSortable": true
                }, {
                    "sClass": "Name",
                    "sWidth": "5%",
                    "bSortable": true,
                    "bSearchble": true
                }, {
                    "sClass": "Type",
                    "sWidth": "7%",
                    "bSortable": true,
                    "bSearchble": true
                }, {
                    "sClass": "Region",
                    "sWidth": "7%",
                    "bSortable": true,
                    "bSearchble": true
                }, {
                    "sClass": "DistrictName",
                    "sWidth": "7%",
                    "bSortable": true,
                    "bSearchble": true
                }, {
                    "sClass": "OwnerTypeName",
                    "sWidth": "7%",
                    "bSortable": true,
                    "bSearchble": true
                }, {
                    "sClass": "AgentType",
                    "sWidth": "5%",
                    "bSortable": true,
                    "bSearchble": true
                }, {
                    "sClass": "ServiceAreaName",
                    "sWidth": "7%",
                    "bSortable": true,
                    "bSearchble": true
                }, {
                    "sClass": "ReceivedFrom",
                    "sWidth": "9%",
                    "bSortable": false,
                    "bSearchble": true,
                    "bVisible": false
                }, {
                    "sClass": "Adress",
                    "sWidth": "7%",
                    "bSortable": false,
                    "bSearchble": true
                }, {
                    "sClass": "Group",
                    "sWidth": "5%",
                    "bSortable": false,
                    "bSearchble": true
                }, {
                    "sClass": "Action",
                    "sWidth": "17%",
                    "bSortable": false,
					"bVisible": false
                }]
        });
        //onComboRegionList();
        onComboFacilityWarehouse();

        tblFacilityGroupMap = $('#tbl-facility-group-map').dataTable({
            "bFilter": false,
            "bSort": true,
            "bInfo": false,
            "bPaginate": false,
            "bSortClasses": false,
            "bProcessing": true,
            "bServerSide": true,
            "aaSorting": [[4, 'asc']],
            "sPaginationType": "full_numbers",
            "aLengthMenu": [[25, 50, 100], [25, 50, 100]],
            "iDisplayLength": 25,
            "sAjaxSource": baseUrl + "t_facility_datasource.php",
            "fnDrawCallback": function(oSettings) {
                if (oSettings.aiDisplay.length == 0) {
                    return;
                }
                var nTrs = $('#tbl-facility-group-map tbody tr');
                var iColspan = nTrs[0].getElementsByTagName('td').length;
                var sLastGroup = "";
                for (var i = 0; i < nTrs.length; i++) {
                    var iDisplayIndex = i;

                }

                $('a.itmEdit', tblFacilityGroupMap.fnGetNodes()).each(function() {
                    $(this).click(function() {

                        var nTr = this.parentNode.parentNode;
                        var aData = tblFacilityGroupMap.fnGetData(nTr);
                        $('#FacilityServiceId').val(aData[1]);
                        $('#FacilityId').val(aData[2]);
                        $('#ItemGroupId').val(aData[3]);
                        $('#StartMonthId').val(aData[5]);
                        $('#StartYearId').val(aData[7]);
                        $('#SupplyFrom').val(aData[8]);
                        $('#row-facility-groupmap-grid').hide();
                        $('#row-facility-groupmap-entry').show();
                    });
                });

                $('a.itmDrop', tblFacilityGroupMap.fnGetNodes()).each(function() {
                    $(this).click(function() {
                        var nTr = this.parentNode.parentNode;
                        var aData = tblFacilityGroupMap.fnGetData(nTr);
                        FacilityServiceId = aData[1];
                        msg = "Do you really want to Delete this record?";
                        onCustomModal(msg, "onConfirmWhenDeleteFacilityGroupMap");
                    });
                });
            },
            "fnServerData": function(sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "action",
                    "value": 'getFacilityGroupMap'
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
                    "name": "RecordId",
                    "value": 22
                });
                aoData.push({
                    "name": "FacilityId",
                    "value": facilityId
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
                    "sClass": "SL",
                    "sWidth": "10%",
                    "bSortable": false,
                    "bVisible": true,
                    "bSearchable": false
                }, {
                    "sClass": "FacilityServiceId",
                    "sWidth": "10%",
                    "bSortable": false,
                    "bVisible": false,
                    "bSearchable": false
                }, {
                    "sClass": "FacilityId",
                    "sWidth": "8%",
                    "bSortable": false,
                    "bVisible": false,
                    "bSearchable": false
                }, {
                    "sClass": "ItemGroupId",
                    "sWidth": "10%",
                    "bVisible": false,
                    "bSearchable": false
                }, {
                    "sClass": "GroupName",
                    "sWidth": "20%",
                    "bSortable": true,
                    "bVisible": true,
                    "bSearchable": true
                }, {
                    "sClass": "StartMonthId",
                    "sWidth": "10%",
                    "bVisible": false,
                    "bSearchable": false

                }, {
                    "sClass": "MonthName",
                    "sWidth": "10%",
                    "bSortable": true,
                    "bVisible": true,
                    "bSearchable": true
                }, {
                    "sClass": "StartYearId",
                    "sWidth": "10%",
                    "bSortable": true,
                    "bSearchable": true
                }, {
                    "sClass": "SupplyFrom",
                    "sWidth": "10%",
                    "bVisible": false,
                    "bSortable": false
                }, {
                    "sClass": "Supplier",
                    "sWidth": "40%",
                    "bSortable": true,
                    "bSearchable": true
                }, {
                    "sClass": "Action",
                    "sWidth": "12%",
                    "bSortable": false,
                    "bSearchable": false
                }]
        });

    });

    //onComboRegionList();

    $('#row-facility-groupmap-grid').hide();
    $('#row-facility-groupmap-entry').hide();

});
