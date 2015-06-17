var initMY;

var monthList = [
    {"MonthId" : 1,"MonthName" : "Jan-Mar"}, 
    {"MonthId" : 2,"MonthName" : "Apr-jun"}, 
    {"MonthId" : 3,"MonthName" : "Jul-Sep"}, 
    {"MonthId" : 4,"MonthName" : "Oct-Dec"}];

function getYearList() {
	var startYear = 2002;
	var endYear = (new Date()).getFullYear();
	var arrayYear = [];
	for (var year = endYear; year >= startYear; year--) {
		var objYear = {};
		objYear.YearId = year;
		objYear.YearName = year;
		arrayYear.push(objYear);
	}
	return arrayYear;
};


/**************************************************Variable Declaration****************************************************/
var currentDate = new Date();
var nFacilityId = 1;
var ItemGroupList = [];
var $patient_overview = $('#overview_page');
var PatientOverviewData = [];
var $patient_adult = $('#adult_page');
var PatientAdultData = [];
var $patient_Paediatric = $('#pad_page');
var PatientPaediatricData = [];
var $art_page = $('#art_page');
var ARTData = [];
var $rtk_page = $('#rtk_page');
var RTKData = [];

/*********************************************************Combo***************************************************************/

function onComboCountryName() {
	$.getJSON(baseUrl + "t_combo.php", {
		action: 'getCountryName'
	}, function(response) {
		str = '<option value="">Country</option>';
		for (var i = 0; i < response.length; i++) {
			str += '<option value="' + response[i].CountryId + '">' + response[i].CountryName + '</option>';
		}
		$('#CountryId').html(str);
        $('#CountryId').val(1);
        onInsertGroupdata();
	});
}

$('#CountryId').change(function() {
    $('#mainTab ul li:first').addClass('active');
    $('#mainTab ul li:last').removeClass('active');   
	onInsertGroupdata();
});

/********************************************************Other Function****************************************************/

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

function patientClick(){
    $('#patients').show();  
    $('#stock').hide();  
}

function stockClick(){
    $('#patients').hide();  
    $('#stock').show();  
}

$("#mainTab ul li a").click(function() {
    $('#mainTab ul li.active').removeClass('active');
    $(this).closest('li').addClass('active');
});

function call_patient_overview(){
     /*$.ajax({
	    type: 'post',
		url: baseUrl + 't_monthlystatus_datasource.php',	
		data:{
			action:'getPatientOverView',						
			Month: $("#month-list").val(),
			Year: $("#year-list").val(),
            Country: $("#CountryId").val(),
			Facility: nFacilityId,
			UserId: USERNAME
		},
		success:function(response){
		    $('#mainTab').show();
            $('#mainTab_content').show();    
			PatientOverviewData = $.parseJSON(response);
			$patient_overview.handsontable('loadData', PatientOverviewData);											
		}
	});*/
    
    PatientOverviewData = [
                            ["1", "Number of adult patients on first-line regimen", "38,828"],
                            ["2", "Number of adult patients on second-line regimen", "1,110"],
                            ["3", "Number of pediatric patients on first-line regimen", "1,772"],
                            ["4", "Number of pediatric patients on second-line regimen", "139"],
                            ["5", "Number of PMTCT patients", "218"],
                            ["6", "Number of RTK patients", "418"],
                        ];
	$patient_overview.handsontable('loadData', PatientOverviewData);
    	
	var cellRendererRight = function (instance, td, row, col, prop, value, cellProperties) {
			Handsontable.TextCell.renderer.apply(this, arguments);
			$(td).css({'text-align': 'right'});
	};
    var cellRendererCenter = function (instance, td, row, col, prop, value, cellProperties) {
			Handsontable.TextCell.renderer.apply(this, arguments);
			$(td).css({'text-align': 'center'});
	};
    $('body').animate({opacity:1},500,function(){	
        $patient_overview.handsontable({
            data: PatientOverviewData,
            contextMenu: true,
            rowHeaders: false,
            colHeaders: ["SL#", "Formulation", "Patient Count"],
            colWidths: [50, 400, 150],
            cells: function (row, col, prop) {
                var cellProperties = {};
                if (col === 0 || col === 1 ) {
                    cellProperties.readOnly = true; 
                }
                if (col === 0) {
                    cellProperties.renderer = cellRendererCenter;	
                }
                if (col === 2) {
                    cellProperties.type = 'numeric';
                    cellProperties.renderer = cellRendererRight;	
                }
                return cellProperties;
            },
            afterChange: function (changes, source) {
                if (source === 'loadData') {
                    return;
                } 
				row=changes[0][0];
				col=changes[0][1];				
				newval=changes[0][3];
            },
            minSpareRows: 0,              
            contextMenu: true
        });
    });
}

function call_adult_patient(){
     /*$.ajax({
	    type: 'post',
		url: baseUrl + 't_monthlystatus_datasource.php',	
		data:{
			action:'getRegimenList',						
			Month: $("#month-list").val(),
			Year: $("#year-list").val(),
            Country: $("#CountryId").val(),
			Facility: nFacilityId,
  	        Fromulation: 1,
			UserId: USERNAME
		},
		success:function(response){
		    $('#mainTab').show();
            $('#mainTab_content').show();    
			PatientAdultData = $.parseJSON(response);
			$patient_adult.handsontable('loadData', PatientAdultData);											
		}
	});*/
    
    PatientAdultData = [
                        ["", "Adult: 1st Line", ""],
                        ["1", "AZT(300mg)+3TC(150mg)+EFV(600mg)", "35,000"],
                        ["2", "AZT(300mg)+3TC(150mg)+EFV(600mg)", "13,000"],
                        ["3", "AZT(300mg)+3TC(150mg)+LPV/r(200/50mg)", "2,308"],
                        ["4", "ABC(300mg)+3TC(150mg)+LPV/r(200/50mg)", "280"],
                        ["", "Adult: 2nd Line", ""],
                        ["5", "ABC(300mg)+DDI(200mg)+LPV/r(200/50mg)", "1,000"],
                        ["6", "ABC(300mg)+DDI(400mg)+LPV/r(200/50mg)", "108"],
                        ["7", "AZT(300mg)+3TC(150mg)+LPV/r(200/50mg)", "5"],
                        ["8", "AZT(60mg)+3TC(30mg)+NVP(50mg)", "2"],
                        ["", "Pediatric: 1st Line", ""],
                        ["9", "AZT(300mg)+3TC(150mg)+EFV(600mg)", "500"],
                        ["10", "AZT(300mg)+3TC(150mg)+EFV(600mg)", "500"],
                        ["11", "AZT(300mg)+3TC(150mg)+LPV/r(200/50mg)", "700"],
                        ["12", "ABC(300mg)+3TC(150mg)+LPV/r(200/50mg)", "72"],
                        ["", "Pediatric: 2nd Line", ""],
                        ["13", "AZT(300mg)+3TC(150mg)+EFV(600mg)", "100"],
                        ["14", "AZT(300mg)+3TC(150mg)+EFV(600mg)", "20"],
                        ["15", "AZT(300mg)+3TC(150mg)+LPV/r(200/50mg)", "10"],
                        ["16", "ABC(300mg)+3TC(150mg)+LPV/r(200/50mg)", "9"],
                        ["", "PMTCT", "2,418"],
                        ["", "RTK", "4,418"]
                    ];
	$patient_adult.handsontable('loadData', PatientAdultData);
    
	var cellRendererRight = function (instance, td, row, col, prop, value, cellProperties) {
		Handsontable.TextCell.renderer.apply(this, arguments);
		$(td).css({'text-align': 'right'});
	};
    var cellRendererCenter = function (instance, td, row, col, prop, value, cellProperties) {
		Handsontable.TextCell.renderer.apply(this, arguments);
		$(td).css({'text-align': 'center'});
	};
    $('body').animate({opacity:1},500,function(){	
        $patient_adult.handsontable({
            data: PatientAdultData,
            contextMenu: true,
            rowHeaders: false,
            colHeaders: ["SL#", "Regimens", "Patients"],
            colWidths: [50, 400, 150],
            cells: function (row, col, prop) {
                var cellProperties = {};
                if (col === 0 || col === 1 ) {
                    cellProperties.readOnly = true; 
                }
                if (col === 0) {
                    cellProperties.renderer = cellRendererCenter;	
                }
                if (col === 2) {
                    cellProperties.type = 'numeric';
                    cellProperties.renderer = cellRendererRight;	
                }
                return cellProperties;
            },
            afterChange: function (changes, source) {
                if (source === 'loadData') {
                    return;
                } 
				row=changes[0][0];
				col=changes[0][1];				
				newval=changes[0][3];
            },
            minSpareRows: 0,              
            contextMenu: true
        });
    });
}

function call_paediatric_patient(){
    /* $.ajax({
	    type: 'post',
		url: baseUrl + 't_monthlystatus_datasource.php',	
		data:{
			action:'getRegimenList',						
			Month: $("#month-list").val(),
			Year: $("#year-list").val(),
            Country: $("#CountryId").val(),
			Facility: nFacilityId,
            Fromulation: 2,
			UserId: USERNAME
		},
		success:function(response){
		    $('#mainTab').show();
            $('#mainTab_content').show();    
			PatientPaediatricData = $.parseJSON(response);
			$patient_Paediatric.handsontable('loadData', PatientPaediatricData);											
		}
	});*/
    
    PatientPaediatricData = [
                        ["1", "3TC+DDI+EFV", "6"],
                        ["2", "3TC+DDI+IDV", "9"],
                        ["3", "3TC+DDI+LPV/r", "4"],
                        ["4", "3TC+EFV+LPV/r", "6"],
                        ["5", "ABC+3TC+EFV", "9"],
                        ["6", "ABC+3TC+LPV/r", "4"]
                    ];
	$patient_Paediatric.handsontable('loadData', PatientPaediatricData);
    
    
	var cellRendererRight = function (instance, td, row, col, prop, value, cellProperties) {
			Handsontable.TextCell.renderer.apply(this, arguments);
			$(td).css({'text-align': 'right'});
	};
    var cellRendererCenter = function (instance, td, row, col, prop, value, cellProperties) {
			Handsontable.TextCell.renderer.apply(this, arguments);
			$(td).css({'text-align': 'center'});
	};
    $('body').animate({opacity:1},500,function(){	
        $patient_Paediatric.handsontable({
            data: PatientPaediatricData,
            contextMenu: true,
            rowHeaders: false,
            colHeaders: ["SL#", "Regimens", "Number of Patient"],
            colWidths: [50, 400, 150],
            cells: function (row, col, prop) {
                var cellProperties = {};
                if (col === 0 || col === 1 ) {
                    cellProperties.readOnly = true; 
                }
                if (col === 0) {
                    cellProperties.renderer = cellRendererCenter;	
                }
                if (col === 2) {
                    cellProperties.type = 'numeric';
                    cellProperties.renderer = cellRendererRight;	
                }
                return cellProperties;
            },
            afterChange: function (changes, source) {
                if (source === 'loadData') {
                    return;
                } 
				row=changes[0][0];
				col=changes[0][1];				
				newval=changes[0][3];
            },
            minSpareRows: 0,              
            contextMenu: true
        });
    });
}

function call_artdata(){
    /* $.ajax({
	    type: 'post',
		url: baseUrl + 't_monthlystatus_datasource.php',	
		data:{
			action:'getItemsData',						
			Month: $("#month-list").val(),
			Year: $("#year-list").val(),
            Country: $("#CountryId").val(),
			Facility: nFacilityId,
			ItemGroupList: 1,
			UserId: USERNAME
		},
		success:function(response){
		    $('#mainTab').show();
            $('#mainTab_content').show();    
			ARTData = $.parseJSON(response);
			$art_page.handsontable('loadData', ARTData);											
		}
	});*/
   
     ARTData = [
                ["1", "Abacavir /Lamivudine 600/300 mg tab", "239,000","33,000","180,000","","","92,000","1.5","60,000"],
                ["2", "Efavirenz 600MG/tab", "120,700","12,900","47,900","","","85,700","5.4","15,967"],
                ["3", "Nevirapine 200MG/tab", "34,000","69,000","22,900","","","80,100","10.5","7,633"],
                ["4", "Lamivudine-Zidovudine 150+300MG/tab", "11,900","23,900","12,700","","","23,100","5.5","4,233"],
                ["5", "Indinavir 400mg/tab", "3,000",0,"900","","","2,100","7.0", "300"],
                ["6", "Abacavir/Lamivudine/Zidovudine 300mg/150mg/300mg /tab", "12,890","9,000","4,900","","","16,990","10.4","1,633"],
                ["7", "Lamivudine-Zidovudine-Nevirapine 150+300+200MG/tab", "11,200","0","1,900","","","9,300","14.7", "633"],            
                ["8", "Tenofovir/Lamivudine 300/300 MG/tab", "0","1,000","600",,,"400","2.0", "200"],
                ["9", "Atazanavir-Ritonavir  300+100MG/tab", "0","1,000","120","","","880","22.0", "40"],
                ["10", "Lamivudine-Zidovudine 150+300MG/tab", "1,200","2,500","800","","","2,900","10.9", "267"]
            ];
   
    
	$art_page.handsontable('loadData', ARTData);
    
	var cellRendererRight = function (instance, td, row, col, prop, value, cellProperties) {
			Handsontable.TextCell.renderer.apply(this, arguments);
			$(td).css({'text-align': 'right'});
	};
    var cellRendererCenter = function (instance, td, row, col, prop, value, cellProperties) {
			Handsontable.TextCell.renderer.apply(this, arguments);
			$(td).css({'text-align': 'center'});
	};    
    $('body').animate({opacity:1},200,function(){	
        $art_page.handsontable({
            data: ARTData,
            contextMenu: true,
            rowHeaders: false,
            colHeaders: ["SL#", "Product Name", "Opening Balance", "Received", "Distribution", "Adjust(+)", "Adjust(-)", "Closing Balance", "MOS", "AMC"],
            colWidths: ["40", "270", "120", "100", "100", "70", "70", "120", "60", "80"],
            cells: function (row, col, prop) {
                var cellProperties = {};
                if (col === 0 || col === 1 || col === 2 || col === 8 || col === 9) {
                    cellProperties.readOnly = true; 
                }
                if (col === 0) {
                    cellProperties.renderer = cellRendererCenter;	
                }
                if (col === 2 || col === 3 || col === 4|| col === 5 || col === 6 || col === 7|| col === 8 || col === 9) {
                    cellProperties.type = 'numeric';
                    cellProperties.renderer = cellRendererRight;	
                }
                return cellProperties;
            },
            afterChange: function (changes, source) {
                if (source === 'loadData') {
                    return;
                } 
				row=changes[0][0];
				col=changes[0][1];				
				newval=changes[0][3];
            },
            minSpareRows: 0,              
            contextMenu: true
        });
    });
}

function call_rtkdata(){
   /*  $.ajax({
	    type: 'post',
		url: baseUrl + 't_monthlystatus_datasource.php',	
		data:{
			action:'getItemsData',						
			Month: $("#month-list").val(),
			Year: $("#year-list").val(),
            Country: $("#CountryId").val(),
			Facility: nFacilityId,
			ItemGroupList: 2,
			UserId: USERNAME
		},
		success:function(response){
		    $('#mainTab').show();
            $('#mainTab_content').show();    
			RTKData = $.parseJSON(response);
			$rtk_page.handsontable('loadData', RTKData);											
		}
	});*/
    
    var country =  $("#CountryId").val();
    if(country == 2){
        RTKData = [
                    ["1", "EFV 600", "12,500", "4,500", "500", "10", "10", "4,000", "9", "6"],
                    ["2", "LPV/r", "13,500", "4,500", "500", "10", "10", "4,000", "7.4", "11"],
                    ["3", "TDF/3TC", "14,500", "4,500", "500", "10", "10", "4,000", "1.2", "222"],
                    ["1", "TDF/FTC", "10,500", "4,500", "500", "10", "10", "4,000", "0.2", "426"]
                ];
    }else if(country == 3){
         RTKData = [
                    ["1", "TDF/3TC/EFV", "12,500", "4,500", "500", "10", "10", "4,000", "9", "6"],
                    ["2", "AZT/3TC/LPV-r", "13,500", "4,500", "500", "10", "10", "5,000", "7.4", "11"],
                    ["3", "TDF/3TC/LPV-r", "14,500", "4,500", "500", "10", "10", "4,000", "1.2", "222"],
                    ["1", "TDF/3TC/NVP", "10, 500", "4,500", "500", "10", "10", "6,000", "0.2", "426"]
                ];
    }else{
         RTKData = [
                    ["1", "TDF/3TC", "12,500", "4,500", "500", "10", "10", "4,000", "9", "6"],
                    ["2", "TDF/3TC/EFV", "13,500", "4,500", "500", "10", "10", "4,000", "7.4", "11"],
                    ["3", "EFV", "14,500", "4,500", "500", "10", "10", "4,000", "1.2", "222"],
                    ["1", "AZT/3TC/NVP Ped", "10,500", "4,500", "500", "10", "10", "4,000", "0.2", "426"]
                ];
    }
    $rtk_page.handsontable('loadData', RTKData);
    
	var cellRendererRight = function (instance, td, row, col, prop, value, cellProperties) {
		Handsontable.TextCell.renderer.apply(this, arguments);
		$(td).css({'text-align': 'right'});
	};
    var cellRendererCenter = function (instance, td, row, col, prop, value, cellProperties) {
			Handsontable.TextCell.renderer.apply(this, arguments);
			$(td).css({'text-align': 'center'});
	};    
    $('body').animate({opacity:1},1200,function(){
        $rtk_page.handsontable({
            data: RTKData,
            contextMenu: true,
            rowHeaders: false,
            colHeaders: ["SL#", "Product Name", "Opening Balance", "Received", "Distribution", "Adjust(+)", "Adjust(-)", "Closing Balance", "MOS", "AMC"],
            colWidths: [50, 200, 110, 110, 110, 110, 110, 110, 50, 50],
            cells: function (row, col, prop) {
                var cellProperties = {};
                if (col === 0 || col === 1 || col === 2 || col === 8 || col === 9) {
                    cellProperties.readOnly = true; 
                }
                if (col === 0) {
                    cellProperties.renderer = cellRendererCenter;	
                }
                if(col === 2 || col === 3 || col === 4|| col === 5 || col === 6 || col === 7|| col === 8 || col === 9) {
                    cellProperties.type = 'numeric';
                    cellProperties.renderer = cellRendererRight;		
                }
                return cellProperties;
            },
            afterChange: function (changes, source) {
            },
            minSpareRows: 0,              
            contextMenu: true
        });
    });
}

function onInsertGroupdata(){
    
    $('#mainTab').show();
    $('#patients').show();    
    $('#stock').hide(); 
       
    call_patient_overview();
    call_adult_patient();
    call_artdata();	
    
    
    /*$.ajax({
        dataType : 'json',
	    type:'post',
		url: baseUrl + 't_monthlystatus_datasource.php',	
		data:{
			action: 'getFAssignedGroup',						
			pFacilityId: nFacilityId
		},
		success:function(response){	   
     	    $('#mainTab').show();
            $('#mainTab_content').show();            
	        for (var i = 0; i < response.length; i++) {
                ItemGroupList.push(response[i]);
                //$('#childTab a[href="#stockData_1"]').show();
            } 
            $.ajax({
        	    type: 'post',
        		url: baseUrl + 't_monthlystatus_datasource.php',	
        		data:{
        			action:'insertStock',						
        			Month: $("#month-list").val(),
        			Year: $("#year-list").val(),
                    Country: $("#CountryId").val(),
        			Facility: nFacilityId,
        			ItemGroupList: ItemGroupList,
        			UserId: USERNAME
        		},
        		success:function(response){
  		            call_patient_overview();
                    call_adult_patient();
                    call_paediatric_patient();
        		  	call_artdata();	
                    call_rtkdata();										
        		}
        	});
		}
	});  */     	
}
        


$(function() {
     
  /***************************************************Month & Year Combo Load*******************************************/
    
    yearList = getYearList();
    
	$.each(monthList, function(i, obj) {
		$('#month-list').append($('<option></option>').val(obj.MonthId).html(obj.MonthName));
	}); 
       
	$.each(yearList, function(i, obj) {
		$('#year-list').append($('<option></option>').val(obj.YearId).html(obj.YearName));	
  	});
    
    $("#left-arrow").click(function() {

		if (endDate.getMonth() == 0 && endDate.getFullYear() == yearList[yearList.length - 1].YearId)
			return;

		endDate.prevMonth();

		$("#month-list").val(endDate.getMonth() + 1);
		$("#year-list").val(endDate.getFullYear());

		gMonthId = $("#month-list").val();
		gYearId = $("#year-list").val();

		//oTableMonthlyStatus.fnDraw();

	});
	//
	$("#right-arrow").click(function() {

		if (endDate.getMonth() == 11 && endDate.getFullYear() == objInit.initialYear)
			return;

		endDate.nextMonth();
		$("#month-list").val(endDate.getMonth() + 1);
		$("#year-list").val(endDate.getFullYear());
		gMonthId = $("#month-list").val();
		gYearId = $("#year-list").val();

		//oTableMonthlyStatus.fnDraw();
	});
    
 /***************************************************Monthly Stock Part************************************************/  
    onComboCountryName();
    $('#mainTab').hide();
    $('#patients').hide();    
    $('#stock').hide(); 
    
    
    
    
    
    
    
    
});