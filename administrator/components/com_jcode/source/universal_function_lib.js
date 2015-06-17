function prevMonth() {
	//alert("hello");
	var thisMonth = this.getMonth();
	this.setMonth(thisMonth-1);
	if(this.getMonth() != thisMonth-1 && (this.getMonth() != 11 || (thisMonth == 11 && this.getDate() == 1)))
		this.setDate(0);
	//return this.getMonth()+1;
}

function nextMonth() {
	var thisMonth = this.getMonth();
	this.setMonth(thisMonth+1);
	if(this.getMonth() != thisMonth+1 && this.getMonth() != 0)
		this.setDate(0);
	//return this.getMonth()+1;
}

function prevYear() {
	//alert("hello");	
	var thisMonth = this.getMonth();
	this.setMonth(thisMonth-11);
	if(this.getMonth() != thisMonth-1 && (this.getMonth() != 11 || (thisMonth == 11 && this.getDate() == 1))){		
		this.setDate(0);
	}
}

// function getYearForPrevMonth() {
// var thisMonth = this.getMonth();
// this.setMonth(thisMonth-1);
// if(this.getMonth() != thisMonth-1 && (this.getMonth() != 11 || (thisMonth == 11 && this.getDate() == 1)))
// this.setDate(0);
// return this.getFullYear();
// }
//
// function getYearForNextMonth() {
// var thisMonth = this.getMonth();
// this.setMonth(thisMonth+1);
// if(this.getMonth() != thisMonth+1 && this.getMonth() != 0)
// this.setDate(0);
// return this.getFullYear();
// }

Date.prototype.prevMonth = prevMonth;
Date.prototype.nextMonth = nextMonth;
Date.prototype.prevYear = prevYear;

// Date.prototype.getYearForPrevMonth = getYearForPrevMonth;
// Date.prototype.getYearForNextMonth = getYearForNextMonth;

// function prevMonthMcDt() {
// var now = new Date();
// var thisMonth = now.getMonth();
// now.setMonth(thisMonth-1);
// if(now.getMonth() != thisMonth-1 && (now.getMonth() != 11 || (thisMonth == 11 && now.getDate() == 1)))
// now.setDate(0);
// return now.getMonth()+1;
// }
//
// function nextMonthMcDt() {
// var now = new Date();
// var thisMonth = now.getMonth();
// now.setMonth(thisMonth+1);
// if(now.getMonth() != thisMonth+1 && now.getMonth() != 0)
// now.setDate(0);
// return now.getMonth()+1;
// }
//
// function getYearForPrevMonthMcDt() {
// var now = new Date();
// var thisMonth = now.getMonth();
// now.setMonth(thisMonth-1);
// if(now.getMonth() != thisMonth-1 && (now.getMonth() != 11 || (thisMonth == 11 && now.getDate() == 1)))
// now.setDate(0);
// return now.getFullYear();
// }
//
// function getYearForNextMonthMcDt() {
// var now = new Date();
// var thisMonth = now.getMonthMcDt();
// now.setMonth(thisMonth+1);
// if(now.getMonth() != thisMonth+1 && now.getMonth() != 0)
// now.setDate(0);
// return now.getFullYear();
// }

// Date.prototype.prevMonthMcDt = prevMonthMcDt;
// Date.prototype.nextMonthMcDt = nextMonthMcDt;
// Date.prototype.getYearForPrevMonthMcDt = getYearForPrevMonthMcDt;
// Date.prototype.getYearForNextMonthMcDt = getYearForNextMonthMcDt;
// function getFacilityReportingRate($frrpMonthId, $frrpYear) {
// //Ext.getCmp("reportingRate").add("Hello");
// Ext.Ajax.request({
// waitMsg: 'Please wait...',
// url: 'universal_function_lib.php',
// params: {
// 'action' : "getFacilityRecordOfThisMonth",
// 'pFacilityId' : pFacilityId,
// 'frrpMonthId' : frrpMonthId,
// 'frrpMonthId' : frrpMonthId
// },
// success: function(response) {
// eval(response.responseText);
// if(ReportingRate <= 0) {
// //getFacilityRecordOfPrevMonth();
// // Ext.Msg.show({
// // msg: ('#ReportingRate').html("[ Reporting rate "+ReportRate+"% ]"),
// // icon: Ext.Msg.QUESTION,
// // minWidth: 200,
// // buttons: Ext.Msg.YESNO,
// // scope: this,
// // fn: function(response) {
// // if('yes' == response) {
// // //alert(response);
// // insertIntoStockData();
// // }
// // }
// // });
// //Ext.getCmp("reportingRate").add("Hello");
// }
// }
// });
// }

function isNumeric(sText,decimals,negatives) {
	var isNumber=true;
	var numDecimals = 0;
	var validChars = "0123456789";
	if (decimals)
		validChars += ".";
	if (negatives)
		validChars += "-";
	var thisChar;
	for (i = 0; i < sText.length && isNumber == true; i++) {
		thisChar = sText.charAt(i);
		if (negatives && thisChar == "-" && i > 0)
			isNumber = false;
		if (decimals && thisChar == ".") {
			numDecimals = numDecimals + 1;
			if (i==0 || i == sText.length-1)
				isNumber = false;
			if (numDecimals > 1)
				isNumber = false;
		}
		if (validChars.indexOf(thisChar) == -1)
			isNumber = false;
	}
	return isNumber;
}

function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
}

function render_val(value, metadata, record, rowIndex, colIndex, store) {
	if( value == 0 )
		return "";
	else
		return Ext.util.Format.number(value, '0,000');
}
function renderer_val(value, metadata, record, rowIndex, colIndex, store) {
	if((record.data.OpStock_A > 0 || record.data.ReceiveQty > 0 || record.data.AdjustQty > 0 || record.data.ClStock_A > 0) && value == 0 )
		return Ext.util.Format.number(value, '0,000');
	else if((record.data.OpStock_A == 0 && record.data.ReceiveQty == 0 && record.data.AdjustQty == 0 && record.data.ClStock_A == 0) && value == 0 )
		return "";
	else
		return value;
}

function captureALLevents() {
	// to capture ALL events use:
	Ext.util.Observable.prototype.fireEvent =
	Ext.util.Observable.prototype.fireEvent.createInterceptor( function() {
		console.log(this.name);
		console.log(arguments);
		return true;
	});
}

function initialize() {

	// initMonthId = document.getElementById("init_month").value;
	// initYearId = document.getElementById("init_year").value;

	// Ext.override(Ext.Window, {
		// toFront : function(e) {
			// if(this.manager.bringToFront(this)) {
				// if(e && !e.getTarget().focus) {
					// this.focus();
				// }
			// }
			// return this;
		// }
	// });

	//Ext 3.3.1
	//added feature: moveEditorOnEnterLikeOnTab
	Ext.override(Ext.grid.RowSelectionModel, {
		moveEditorOnEnterLikeOnTab: false, // patch

		onEditorKey : function(field, e) {
			var k = e.getKey(),
			newCell,
			g = this.grid,
			last = g.lastEdit,
			ed = g.activeEditor,
			shift = e.shiftKey,
			ae, last, r, c;
			
			// var rowCount = g.getStore().getCount();
			// var gridrecord = g.getSelectionModel().getSelected();			
			// var rowIndex = g.getStore().indexOf(gridrecord);
			//alert(rowIndex);

			if(k == e.TAB) {
				e.stopEvent();
				ed.completeEdit();
				if(shift) {
					newCell = g.walkCells(ed.row, ed.col-1, -1, this.acceptsNav, this);
				} else {
					newCell = g.walkCells(ed.row, ed.col+1, 1, this.acceptsNav, this);
				}
			} else if(k == e.ENTER) {
				if (this.moveEditorOnEnterLikeOnTab)    // patch
				{
					if(shift) {						
						newCell = g.walkCells(last.row, last.col-1, -1, this.acceptsNav, this);
					} else {	
						//alert(rowCount +" | "+ rowIndex + " | "+ last.row+" | "+last.col);
						//if(rowIndex+1 == rowCount && ((opStock_A + receiveQty + adjustQty - clStock_A) != dispenseQty))
						// if(rowIndex == rowCount-1 && rowIndex == last.row && last.col == 16)
							// newCell = g.walkCells(0, 0, 1, this.acceptsNav, this);
						// else					
							newCell = g.walkCells(last.row, last.col+1, 1, this.acceptsNav, this);
					}
				} else if(this.moveEditorOnEnter !== false) {
					if(shift) {
						newCell = g.walkCells(last.row - 1, last.col, -1, this.acceptsNav, this);
					} else {
						newCell = g.walkCells(last.row + 1, last.col, 1, this.acceptsNav, this);
					}
				}
			}
			// RMT: add UP, DOWN, LEFT, RIGHT handlers
			else if(k == e.RIGHT) {
				e.stopEvent();
				ed.completeEdit();
				newCell = g.walkCells(ed.row, ed.col+1, 1, this.acceptsNav, this);
			} else if(k == e.LEFT) {
				e.stopEvent();
				ed.completeEdit();
				newCell = g.walkCells(ed.row, ed.col-1, -1, this.acceptsNav, this);
			} else if(k == e.DOWN) {
				e.stopEvent();
				ed.completeEdit();
				newCell = g.walkCells(last.row + 1, last.col, 1, this.acceptsNav, this);
			} else if(k == e.UP) {
				e.stopEvent();
				ed.completeEdit();
				newCell = g.walkCells(last.row - 1, last.col, -1, this.acceptsNav, this);
			}
			// RMT: end insert
			if(newCell) {
				r = newCell[0];
				c = newCell[1];

				this.onEditorSelect(r, last.row);

				if(g.isEditor && g.editing) { // *** handle tabbing while editorgrid is in edit mode
					ae = g.activeEditor;
					if(ae && ae.field.triggerBlur) {
						// *** if activeEditor is a TriggerField, explicitly call its triggerBlur() method
						ae.field.triggerBlur();
					}
				}
				g.startEditing(r, c);
			}
		}
	});
}