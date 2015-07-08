var initMY;

// var monthList = [{
// "MonthId" : 1,
// "MonthName" : "Jan-Mar"
// }, {
// "MonthId" : 2,
// "MonthName" : "Apr-Jun"
// }, {
// "MonthId" : 3,
// "MonthName" : "Jul-Sep"
// }, {
// "MonthId" : 4,
// "MonthName" : "Oct-Dec"
// }];

var monthList1 = [{
	"MonthId" : 1,
	"MonthName" : "January"
}, {
	"MonthId" : 2,
	"MonthName" : "February"
}, {
	"MonthId" : 3,
	"MonthName" : "March"
}, {
	"MonthId" : 4,
	"MonthName" : "April"
}, {
	"MonthId" : 5,
	"MonthName" : "May"
}, {
	"MonthId" : 6,
	"MonthName" : "June"
}, {
	"MonthId" : 7,
	"MonthName" : "July"
}, {
	"MonthId" : 8,
	"MonthName" : "August"
}, {
	"MonthId" : 9,
	"MonthName" : "September"
}, {
	"MonthId" : 10,
	"MonthName" : "October"
}, {
	"MonthId" : "11",
	"MonthName" : "November"
}, {
	"MonthId" : 12,
	"MonthName" : "December"
}];

function getYearList1() {
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

function prevMonth() {
	var thisMonth = this.getMonth();
	this.setMonth(thisMonth - 1);
	if (this.getMonth() != thisMonth - 1 && (this.getMonth() != 11 || (thisMonth == 11 && this.getDate() == 1)))
		this.setDate(0);
}

function nextMonth() {
	var thisMonth = this.getMonth();
	this.setMonth(thisMonth + 1);
	if (this.getMonth() != thisMonth + 1 && this.getMonth() != 0)
		this.setDate(0);
}

function prevMonths(cntMonths) {
	for (var i = 0; i < cntMonths; i++) {
		this.prevMonth();
	}
}

function nextMonths(cntMonths) {
	for (var i = 0; i < cntMonths; i++) {
		this.nextMonth();
	}
}

function lastQuarter() {
	for (var i = 0; i < 12; i++) {
		if (this.getMonth() == 2 || this.getMonth() == 5 || this.getMonth() == 8 || this.getMonth() == 11)
			return;
		this.prevMonth();
	}
}

function getMonthName() {
	var m = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
	return m[this.getMonth()];
};

Date.prototype.prevMonth = prevMonth;
Date.prototype.prevMonths = prevMonths;
Date.prototype.nextMonth = nextMonth;
Date.prototype.nextMonths = nextMonths;
Date.prototype.lastQuarter = lastQuarter;
Date.prototype.getMonthName = getMonthName;

function getInitialMonth() {
	$.ajax({
		"type" : "POST",
		"url" : baseUrl + 'init_month_year2.php',
		"data" : {
			operation : 'getInitialMonth'
		},
		"success" : function(response) {
			var retResponse = JSON.parse('{"initialYear":"2014","initialMonth":2}');
			initMY = retResponse;
			//console.log(response);
		}
	});
}

