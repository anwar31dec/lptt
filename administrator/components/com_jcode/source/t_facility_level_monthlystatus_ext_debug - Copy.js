$ = jQuery;
var POMasterEntryform;
var savePOMasterEntryform;
var ResetPOMasterEntryform;
var POMasterEntryformWindow;
var dsPatientOverview;
var dsAdultRegimens;
var dsMasterStockData;
var dsArvData;
var ClosePOMasterEntryform;
var searchfield;
var searchfield_patientOverview;
var searchfield_adultRegimens;
var searchfield_paediatricRegimens;
var searchfield_OIsAndProphylaxis;
var searchfield_ArvData;
var recPatientOverview;
var gridAdultRegimens;
var gridArvData;
var tabArvdata;
var facilityId;
var dsFacility;
var dsMonth;
var dsYear;
var dsRegion;
var vTotalRec;
var facilityIndex;
var monthIndex;
var yearIndex;
var vFacilityId;
var vYearId;
var vMonthId;
var vFacility;
var vYear;
var vMonth;
var curDate;
var curMonth;
var prevMonth;
var nextMonth;
var curYear;
var prevYear;
var nextYear;
var vOpStock_A;
var vReceiveQty;
var vAdjustQty;
var vAdjustReason;
var vStockoutDays;
var vStockOutReasonId;
var vOrderQty = 0;
var vClStock_A = 0;
var vClStockSourceId;
var vDispenseQty = 0;
var vClStock_C = 0;
var vAMC = 0;
var vAMC_C = 0;
var vAmcChangeReasonId = 0;
var vMOS = 0;
var vMaxQty = 0;
var vOrderQty = 0;
var vActualQty = 0;
var pActualQty = 0;
var vARVDataId = 0;
var pARVDataId;
var lmisStartMonth;
var lmisStartYear;
var initMonthId;
var initYearId;
var supplyFrom;
var invalidMsgCount = 0;
var bShowMsgBox = 0;
var bYesNoMsg = 0;
var rIndex = -1, cIndex = -1;
var pItemGroupId;
var pReportId = 0;
var vReportId = 0;
var reportId = 0;
var vBeforeLastMonthDispensed = 0;
var vBeforeLastMonthDispensedDevisor = 0;
var vLastMonthDispensed = 0;
var vLastMonthDispensedDevisor = 0;
var vDispenseQtyDevisor = 0;
var v3MonthTotal = 0;
var vDevisor = 0;
var bSubmitted = 0;
var bReadOnly = true;
var dsAdjustReason;
var dsClStockSource;
var dsAmcChangeReason;
var dsOrderQtyChangeReason;
var pAdjustId;
var gItemGroupId = 0;
var pStatusId = 0;
var pFacilityCount = 0;
var bClickButton = false;
var gRowIndex = 0;
var stockOutDays = 0;
var stockOutReason = '';
var minActQtyFactor = 2;
var maxActQtyFactor = 3;
var bPatient = 0;
var gClosingAlert = '';
var pCountryName;
var pFacilityName;
var pMonthId = null;
var pYearId = null;
var pCountryId = null;
var pRegionId = null;
var pDistrictId = null;
var pOwnerTypeId = null;
var pItemGroupId = null;
var pFacilityId = null;

var oOpStock_A = 0;
var oReceiveQty = 0;
var oAdjustQty = 0;
var oClStock_A = 0;
var oDispenseQty = 0;

var gRowIndex = 0;
var start = 0;
var tmpDistrictId = -1;
var selectedTab;
var jstore;
var pMonthName;

// Stock information columns
var CLM_ARVDATAID = 0;
var CLM_FACILITYID = 1;
var CLM_MONTHID = 2;
var CLM_YEAR = 3;
var CLM_ITEMGROUPID = 4;
var CLM_ITEMNO = 5;
var CLM_ITEMSL = 6;
var CLM_ITEMNAME = 7;
var CLM_OPSTOCK_C = 8;
var CLM_OPSTOCK_A = 9;
var CLM_RECEIVEQTY = 10;
var CLM_DISPENSEQTY = 11;
var CLM_LASTMONTHDISPENSED = 12;
var CLM_BEFORELASTMONTHDISPENSED = 13;
var CLM_ADJUSTQTY = 14;
var CLM_ADJUSTID = 15;
var CLM_STOCKOUTDAYS = 16;
var CLM_STOCKOUTREASONID = 17;
var CLM_CLSTOCK_C = 18;
var CLM_CLSTOCK_A = 19;
var CLM_CLSTOCKSOURCEID = 20;
var CLM_AMC_C = 21;
var CLM_AMC = 22;
var CLM_AMCCHANGEREASONID = 23;
var CLM_MOS = 24;
var CLM_MAXQTY = 25;
var CLM_ORDERQTY = 26;
var CLM_ACTUALQTY = 27;
var CLM_OUREASONID = 28;
var CLM_USERID = 29;
var CLM_FORMULATIONNAME = 30;


Ext.onReady(function() {
	Ext.QuickTips.init();

	searchfield = new Ext.ux.grid.Search({
		searchText : TEXT['Search'],
		mode : 'remote',
		position : top,
		width : 220,
		minChars : 1,
		autoFocus : true,
		showSelectAll : true
	});
	//console.log(searchfield);
	pUserId = jUserId;
	// lmisStartMonth = 1
	// lmisStartYear = 2014

	Date.prototype.getMonthName = function() {
		var m = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
		return m[this.getMonth()];
	};
	// initMonthId = objInit.initialMonth;
	// initYearId = objInit.initialYear;
	//
	// var startDate = new Date(initYearId, initMonthId - 1);
	// var startManthName = startDate.getMonthName();

	initMonthId = -1;
	initYearId = -1;

	var startDate;
	var startManthName;

	var selYearId = 0;
	var selMonth = 0;

	var selInitYearId = 0;
	var selInitMonthId = 0;

	var currentDate = new Date();
	currentDate.prevMonth();
	//captureALLevents();
	initialize();
	//Ext 3.3.1

	var acEditor;

	//added feature: moveEditorOnEnterLikeOnTab
	Ext.override(Ext.grid.RowSelectionModel, {
		moveEditorOnEnterLikeOnTab : false, // patch

		onEditorKey : function(field, e) {
			var k = e.getKey(), newCell, g = this.grid, last = g.lastEdit, ed = g.activeEditor, shift = e.shiftKey, ae, last, r, c;

			var rowCount = g.getStore().getCount();
			var gridrecord = g.getSelectionModel().getSelected();
			var rowIndex = g.getStore().indexOf(gridrecord);

			if (k == e.TAB) {
				e.stopEvent();
				ed.completeEdit();
				if (shift) {
					newCell = g.walkCells(ed.row, ed.col - 1, -1, this.acceptsNav, this);
				} else {
					newCell = g.walkCells(ed.row, ed.col + 1, 1, this.acceptsNav, this);
				}
			} else if (k == e.ENTER) {
				if (this.moveEditorOnEnterLikeOnTab)// patch
				{
					if (shift) {
						newCell = g.walkCells(last.row, last.col - 1, -1, this.acceptsNav, this);
					} else {
						//alert(rowCount +" | "+ rowIndex + " | "+ last.row+" | "+last.col);
						//if(rowIndex+1 == rowCount &&
						//((gridrecord.opStock_A + gridrecord.receiveQty + gridrecord.adjustQty - gridrecord.clStock_A) == gridrecord.dispenseQty))
						//alert(last.col);
						if (rowIndex == rowCount - 1 && rowIndex == last.row && last.col == 20)
							//&& ((gridrecord.data.OpStock_A + gridrecord.data.ReceiveQty + gridrecord.data.AdjustQty - gridrecord.data.ClStock_A) != gridrecord.data.DispenseQty))
							newCell = g.walkCells(0, 0, 1, this.acceptsNav, this);
						else
							newCell = g.walkCells(last.row, last.col + 1, 1, this.acceptsNav, this);
					}
				} else if (this.moveEditorOnEnter !== false) {
					if (shift) {
						newCell = g.walkCells(last.row - 1, last.col, -1, this.acceptsNav, this);
					} else {
						newCell = g.walkCells(last.row + 1, last.col, 1, this.acceptsNav, this);
					}
				}
			}
			// RMT: add UP, DOWN, LEFT, RIGHT handlers
			else if (k == e.RIGHT) {
				e.stopEvent();
				ed.completeEdit();
				newCell = g.walkCells(ed.row, ed.col + 1, 1, this.acceptsNav, this);
				// r = ed.row;
				// c = ed.col + 1;
			} else if (k == e.LEFT) {
				e.stopEvent();
				ed.completeEdit();
				newCell = g.walkCells(ed.row, ed.col - 1, -1, this.acceptsNav, this);
				// r = ed.row;
				// c = ed.col - 1;
			} else if (k == e.DOWN) {
				e.stopEvent();
				ed.completeEdit();
				newCell = g.walkCells(last.row + 1, last.col, 1, this.acceptsNav, this);
				// r = last.row + 1;
				// c = last.col;
			} else if (k == e.UP) {
				e.stopEvent();
				ed.completeEdit();
				newCell = g.walkCells(last.row - 1, last.col, -1, this.acceptsNav, this);
				// r = last.row - 1;
				// c = last.col;
			}
			// RMT: end insert
			if (newCell) {
				r = newCell[0];
				c = newCell[1];

				this.onEditorSelect(r, last.row);

				if (g.isEditor && g.editing) {// *** handle tabbing while editorgrid is in edit mode
					ae = g.activeEditor;
					if (ae && ae.field.triggerBlur) {
						// *** if activeEditor is a TriggerField, explicitly call its triggerBlur() method
						ae.field.triggerBlur();
					}
				}
				g.startEditing(r, c);
			}
		}
	});

	var fm = Ext.form;

	function savePatientOverview(oGrid_event) {
		var vCFMPOId = oGrid_event.record.data.CFMPOId;
		// var vRefillPatient = oGrid_event.record.data.RefillPatient;
		// var vNewPatient = oGrid_event.record.data.NewPatient;
		//var vTotalPatient = oGrid_event.record.data.RefillPatient + oGrid_event.record.data.NewPatient;
		//oGrid_event.record.set("TotalPatient", vTotalPatient);
		var vTotalPatient = oGrid_event.record.data.TotalPatient;
		dsPatientOverview.commitChanges();
		Ext.Ajax.request({
			waitMsg : 'Please wait...',
			url : baseUrl + 't_facility_level_monthlystatus_ext_server.php',
			params : {
				action : "updatePatientOverview",
				cFMPOId : vCFMPOId,
				totalPatient : vTotalPatient,
				pFacilityId : pFacilityId,
				pMonthId : pMonthId,
				pYearId : pYearId,
				gItemGroupId : gItemGroupId,
				'lang' : lan
			},
			success : function(response) {
				eval(response.responseText);
				switch(success) {
				case 1:
					break;
				case 0:

					Ext.Msg.show({
						msg : error,
						icon : Ext.Msg.INFO,
						minWidth : 300,
						buttons : Ext.Msg.OK,
						scope : this
					});
					break;
				default:
					Ext.Msg.show({
						msg : response.responseText,
						icon : Ext.Msg.INFO,
						minWidth : 300,
						buttons : Ext.Msg.OK,
						scope : this
					});
					break;
				}
			}
		});
	}

	function saveAdultRegimens(oGrid_event) {
		// console.log(oGrid_event.record.data[oGrid_event.field+'_Id']);
		// console.log(oGrid_event);
		//eval(oGrid_event.field+'_Id');
		// switch (oGrid_event.field) {
		// case "C0to4M":
		// //console.log(oGrid_event.record.data.C0to4M_Id);
		//
		// default:
		// break;
		// }

		// var vCFMPatientStatusId = oGrid_event.record.data.CFMPatientStatusId;
		// var vRefillPatient = oGrid_event.record.data.RefillPatient;
		// var vNewPatient = oGrid_event.record.data.NewPatient;

		var vTotalPatient = isNorE(oGrid_event.record.data.C0to4M) + isNorE(oGrid_event.record.data.C0to4F) + isNorE(oGrid_event.record.data.C5to14M) + isNorE(oGrid_event.record.data.C5to14F) + isNorE(oGrid_event.record.data.C15PlusM) + isNorE(oGrid_event.record.data.C15PlusF) + isNorE(oGrid_event.record.data.PregnantWomen);
		oGrid_event.record.set("TotalPatient", vTotalPatient);

		//dsAdultRegimens.commitChanges();
		Ext.Ajax.request({
			waitMsg : 'Please wait...',
			url : baseUrl + 't_facility_level_monthlystatus_ext_server.php',
			params : {
				action : "updateRegimenPatient",
				pCFMPatientStatusId : oGrid_event.record.data[oGrid_event.field + '_Id'],
				pPatients : oGrid_event.value,
				pItemGroupId : pItemGroupId,
				pFacilityId : pFacilityId,
				pMonthId : pMonthId,
				pYearId : pYearId,
				'lang' : lan
			},
			success : function(response) {
				eval(response.responseText);

				switch(success) {
				case 1:
					dsAdultRegimens.commitChanges();
					break;
				case 0:
					Ext.Msg.show({
						msg : error,
						icon : Ext.Msg.INFO,
						minWidth : 300,
						buttons : Ext.Msg.OK,
						scope : this
					});
					break;
				default:
					Ext.Msg.show({
						msg : response.responseText,
						icon : Ext.Msg.INFO,
						minWidth : 300,
						buttons : Ext.Msg.OK,
						scope : this
					});
					break;
				}
			}
		});
	}

	function isNorE(value) {
		return isNaN(value) || value == "" ? parseInt(0) : parseInt(value);
	}

	function saveTheCell(oGrid_event) {

		//console.log(this);
		// rIndex = oGrid_event.row;
		// cIndex = oGrid_event.column;
		//
		lastEditRc = this.lastEdit;
		// // console.log(lastEditRc);
		//
		rIndex = lastEditRc.row;
		cIndex = lastEditRc.col;

		// alert(rIndex);
		//alert(cIndex);
		//console.log(oGrid_event.record.data.DispenseQty);

		vARVDataId = oGrid_event.record.data.ARVDataId;
		vOpStock_C = isNorE(oGrid_event.record.data.OpStock_A);
		vOpStock_A = isNorE(oGrid_event.record.data.OpStock_A);
		vReceiveQty = isNorE(oGrid_event.record.data.ReceiveQty);
		vDispenseQty = isNorE(oGrid_event.record.data.DispenseQty);
		vAdjustQty = isNorE(oGrid_event.record.data.AdjustQty);
		vAdjustReason = oGrid_event.record.data.AdjustReason;
		vOUReasonId = oGrid_event.record.data.OUReasonId;
		vStockoutDays = isNorE(oGrid_event.record.data.StockoutDays);
		vStockOutReasonId = oGrid_event.record.data.StockOutReasonId;

		//vOrderQty = isNaN(oGrid_event.record.data.OrderQty) || oGrid_event.record.data.OrderQty == "" ? 0 : oGrid_event.record.data.OrderQty;

		//vActualQty = isNaN(oGrid_event.record.data.ActualQty) || oGrid_event.record.data.ActualQty == "" ? 0 : oGrid_event.record.data.ActualQty;

		//alert(oGrid_event.record.data.ClStock_A);

		vClStock_C = isNorE(oGrid_event.record.data.ClStock_A);

		vClStock_A = isNorE(oGrid_event.record.data.ClStock_A);

		//alert(vClStock_A);

		vClStockSourceId = oGrid_event.record.data.ClStockSourceId;

		vAmcChangeReasonId = oGrid_event.record.data.AmcChangeReasonId;

		vBeforeLastMonthDispensed = isNorE(oGrid_event.record.data.BeforeLastMonthDispensed);
		vBeforeLastMonthDispensedDevisor = (vBeforeLastMonthDispensed > 0 ? 1 : 0 );
		vLastMonthDispensed = isNaN(oGrid_event.record.data.LastMonthDispensed) || oGrid_event.record.data.LastMonthDispensed == "" ? 0 : oGrid_event.record.data.LastMonthDispensed;
		vLastMonthDispensedDevisor = (vLastMonthDispensed > 0 ? 1 : 0 );
		vDispenseQtyDevisor = (vDispenseQty > 0 ? 1 : 0 );
		v3MonthTotal = isNorE(vBeforeLastMonthDispensed) + isNorE(vLastMonthDispensed) + isNorE(vDispenseQty);
		vDevisor = isNorE(vBeforeLastMonthDispensedDevisor) + isNorE(vLastMonthDispensedDevisor) + isNorE(vDispenseQtyDevisor);

		vAMC_C = Math.round(v3MonthTotal / vDevisor);
		vAMC_C = isNaN(vAMC_C) || vAMC_C == "" ? 0 : vAMC_C;
		oGrid_event.record.set("AMC_C", vAMC_C);

		//alert(pFacilityCount);

		switch (oGrid_event.field) {
		case "ClStock_A":
			vClStock_A = isNorE(oGrid_event.record.data.ClStock_A);
			//alert(vClStock_A);
			vAMC = isNorE(oGrid_event.record.data.AMC);
			if (pFacilityCount == 0) {
				vMOS = (vAMC != 0 ? vClStock_A / vAMC : 0 ).toFixed(2);
				vMOS = isNaN(vMOS) || vMOS == "" ? 0 : vMOS;
				oGrid_event.record.set("AMC", vAMC);
				oGrid_event.record.set("MOS", vMOS);
				oGrid_event.record.set("MaxQty", vAMC * 3);

			} else {
				//vAMC = isNorE(oGrid_event.record.data.AMC);
				//oGrid_event.record.set("AMC", vAMC);
				vMOS = (vAMC != 0 ? vClStock_A / vAMC : 0 ).toFixed(2);
				vMOS = isNaN(vMOS) || vMOS == "" ? 0 : vMOS;
				oGrid_event.record.set("MOS", vMOS);
				oGrid_event.record.set("MaxQty", vAMC * 3);
			}

			vMaxQty = vAMC * 3;

			vOrderQty = ((vMaxQty - vClStock_A) < 0 ? 0 : (vMaxQty - vClStock_A));

			oGrid_event.record.set("OrderQty", vOrderQty);

			vOrderQty = isNorE(oGrid_event.record.data.OrderQty);

			oGrid_event.record.set("ActualQty", vOrderQty);

			// vMaxQty = isNaN(oGrid_event.record.data.MaxQty) || oGrid_event.record.data.MaxQty == "" ? 0 : oGrid_event.record.data.MaxQty;
			// oGrid_event.record.set("OrderQty", ((vMaxQty - vClStock_A) < 0 ? 0 : (vMaxQty - vClStock_A)));
			// //vOrderQty = isNaN(oGrid_event.record.data.OrderQty) || oGrid_event.record.data.OrderQty == "" ? 0 : oGrid_event.record.data.OrderQty;
			//oGrid_event.record.set("ActualQty", ((vMaxQty - vClStock_A) < 0 ? 0 : (vMaxQty - vClStock_A)));

			vActualQty = isNorE(oGrid_event.record.data.ActualQty);

			oOpStock_A = isNorE(oGrid_event.record.data.OpStock_A);
			oReceiveQty = isNorE(oGrid_event.record.data.ReceiveQty);
			oAdjustQty = isNorE(oGrid_event.record.data.AdjustQty);
			oClStock_A = isNorE(oGrid_event.record.data.ClStock_A);
			oDispenseQty = isNorE(oGrid_event.record.data.DispenseQty);

			//alert(oOpStock_A+'+'+oReceiveQty+'+'+oAdjustQty+'-'+oClStock_A+'!= '+oDispenseQty);

			if ((oOpStock_A + oReceiveQty + oAdjustQty - oClStock_A) != oDispenseQty) {
				//gridArvData.stopEditing();
				//var aed = gridArvData.activeEditor;
				//console.log(aed);
				//console.log(acEditor);
				//aed.completeEdit();
				alert(TEXT['Msg_Validation'] + (oOpStock_A + oReceiveQty + oAdjustQty - oDispenseQty));

				//alert("Hello again! This is how we"+"\n"+"add line breaks to an alert box!");

				// gClosingAlert = '';

				// gClosingAlert = TEXT['Msg_Validation'] + (oOpStock_A + oReceiveQty + oAdjustQty - oDispenseQty);

				//this.stopEditing();
				//var lEd = this.lastActiveEditor;

				//console.log(lEd);

				//Ext.Msg.alert('Status', 'Changes saved successfully.');

				//lEd.cancelEdit();

				//gridArvData.stopEditing();

				// //if (1 != 0) {
				// //alert(receiveQty );
				// gridArvData.stopEditing();
				// var aed = gridArvData.activeEditor;
				// msgboxpos = Ext.Msg.show({
				// title : TEXT['Data validation'],
				// msg : TEXT['Msg_Validation'] + (oOpStock_A + oReceiveQty + oAdjustQty - oDispenseQty),
				// icon : Ext.Msg.QUESTION,
				// buttons : Ext.Msg.OK,
				// fn : function(response) {
				// aed.completeEdit();
				// //gridArvData.getSelectionModel().selectRow(rIndex);
				// //gridArvData.startEditing(rIndex, cIndex);
				// return;
				// },
				// scope : this
				// }).getDialog();
				// //aed.completeEdit();
				// //
			}

			break;

		case "DispenseQty":
			if (pFacilityCount == 0) {
				vAMC = Math.round(v3MonthTotal / vDevisor);
				vAMC = isNorE(vAMC);

				oGrid_event.record.set("AMC", vAMC);
				vMOS = (vAMC != 0 ? vClStock_A / vAMC : 0 ).toFixed(2);
				vMOS = isNorE(vMOS);
				oGrid_event.record.set("MOS", vMOS);
				oGrid_event.record.set("MaxQty", vAMC * 3);
			}
			break;

		case "ActualQty":
			vMaxQty = isNorE(oGrid_event.record.data.MaxQty);
			vOrderQty = isNorE(oGrid_event.record.data.OrderQty);
			vActualQty = isNorE(oGrid_event.record.data.ActualQty);
			break;

		case "AMC":
			vAMC = isNorE(oGrid_event.record.data.AMC);
			//alert(vClStock_A);
			vMOS = (vAMC != 0 ? vClStock_A / vAMC : 0 ).toFixed(2);
			//alert(vMOS);
			vMOS = isNorE(vMOS);
			oGrid_event.record.set("MOS", vMOS);
			oGrid_event.record.set("MaxQty", vAMC * 3);
			break;

		case "OUReasonId":
			vActualQty = isNorE(oGrid_event.record.data.ActualQty);
			break;

		default:
			//alert('Default case');
			break;
		}

		if (oGrid_event.field != "ClStockSourceId") {
			oGrid_event.record.set("ClStockSourceId", 1);
		}
	}

	//********************************dsRegion : Region Data Store****************//
	var dsRegion = new Ext.data.Store({
		reader : new Ext.data.JsonReader({
			fields : ['RegionId', 'RegionName'],
			root : 'rows'
		}),
		proxy : new Ext.data.HttpProxy({
			url : baseUrl + 'combo_ext.php'
		}),
		baseParams : {
			action : "getRegionByCId",
			pCountryId : 1,
			lang : vLang
		}
	});

	var dsDistricts = new Ext.data.Store({
		id : 'dsDistrictsId',
		reader : new Ext.data.JsonReader({
			fields : [{
				name : 'DistrictId',
				type : 'int',
				mapping : 'DistrictId'
			}, {
				name : 'DistrictName',
				type : 'string',
				mapping : 'DistrictName'
			}],
			root : 'rows'
		}),
		proxy : new Ext.data.HttpProxy({
			url : baseUrl + 'combo_ext.php'
		}),
		baseParams : {
			action : "getDistrictsByCR",
			'lang' : lan
		},
		sortInfo : {
			field : 'DistrictName',
			direction : "ASC"
		}
	});

	dsFacility = new Ext.data.GroupingStore({
		id : 'FacilityWithMonthStatusId',
		proxy : new Ext.data.HttpProxy({
			url : baseUrl + 't_facility_level_monthlystatus_ext_server.php',
			method : 'POST'
		}),

		baseParams : {
			action : "getFacilityWithMonthStatus",
			jUserId : jUserId,
			'lang' : lan
		},
		reader : new Ext.data.JsonReader({
			root : 'results',
			totalProperty : 'total',
			id : 'id'
		}, [{
			name : 'FacilityId',
			type : 'int',
			mapping : 'FacilityId'
		}, {
			name : 'FacilityName',
			type : 'string',
			mapping : 'FacilityName'
		}, {
			name : 'FLevelId',
			type : 'int',
			mapping : 'FLevelId'
		}, {
			name : 'DistrictName',
			type : 'string',
			mapping : 'DistrictName'
		}, {
			name : 'StartMonthId',
			type : 'int',
			mapping : 'StartMonthId'
		}, {
			name : 'StartYearId',
			type : 'string',
			mapping : 'StartYearId'
		}, {
			name : 'SupplyFrom',
			type : 'int',
			mapping : 'SupplyFrom'
		}, {
			name : 'FacilityCount',
			type : 'int',
			mapping : 'FacilityCount'
		}, {
			name : 'CFMStockId',
			type : 'int',
			mapping : 'CFMStockId'
		}, {
			name : 'StatusId',
			type : 'int',
			mapping : 'StatusId'
		}]),

		groupField : 'DistrictName'
	});

	dsFacility.on('load', function(store, records, options) {
		//getOpeningEditable();
		gridFacility.getSelectionModel().selectRow(gRowIndex, true);
		//gRowIndex = 0;
		// else
		// gridFacility.getSelectionModel().selectFirstRow();
	});

	dsYear = new Ext.data.Store({
		id : 'dsYearId',
		reader : new Ext.data.JsonReader({
			fields : [{
				name : 'YearId',
				type : 'int',
				mapping : 'YearId'
			}, {
				name : 'YearName',
				type : 'string',
				mapping : 'YearName'
			}],
			root : 'rows'
		}),
		proxy : new Ext.data.HttpProxy({
			url : baseUrl + 'combo_ext.php'
		}),
		baseParams : {
			action : "getYear",
			'lang' : lan
		},
		autoLoad : true,
		sortInfo : {
			field : 'YearId',
			direction : "ASC"
		}
	});

	dsYear.on("load", setYearId, this, {
		single : true
	});

	function setYearId() {
		var curYear = currentDate.getFullYear();
		var store = Ext.getCmp("cboYearId").getStore();
		var index = store.find('YearId', curYear);
		if (index != -1)//the record has been found
		{
			Ext.getCmp("cboYearId").setValue(curYear);
			pYearId = Ext.getCmp("cboYearId").getValue();
			if (pYearId != "") {
				dsFacility.setBaseParam('pYearId', pYearId);
				setBaseParams('pYearId', pYearId);
			}
		}
	}

	dsMonth = new Ext.data.Store({
		id : 'dsMonthId',
		reader : new Ext.data.JsonReader({
			fields : [{
				name : 'MonthId',
				type : 'int',
				mapping : 'MonthId'
			}, {
				name : 'MonthName',
				type : 'string',
				mapping : 'MonthName'
			}],
			root : 'rows'
		}),
		proxy : new Ext.data.HttpProxy({
			url : baseUrl + 'combo_ext.php'
		}),
		baseParams : {
			action : "getMonth",
			lang : lan
		},
		autoLoad : true,
		sortInfo : {
			field : 'MonthId',
			direction : "ASC"
		}
	});

	dsMonth.on("load", setMonthId, this, {
		single : true
	});

	function setMonthId() {
		var curMonth = currentDate.getMonth() + 1;
		var store = Ext.getCmp("cboMonthId").getStore();
		var index = store.find('MonthId', curMonth);
		if (index != -1)//the record has been found
		{
			Ext.getCmp("cboMonthId").setValue(curMonth);
			pMonthId = Ext.getCmp("cboMonthId").getValue();
			if (pMonthId != "") {
				dsFacility.setBaseParam('pMonthId', pMonthId);
				setBaseParams('pMonthId', pMonthId);
			}
		}
	}

	dsAdjustReason = new Ext.data.Store({
		reader : new Ext.data.JsonReader({
			fields : ['AdjustId', 'AdjustReason'],
			root : 'rows'
		}),
		proxy : new Ext.data.HttpProxy({
			url : baseUrl + 'combo_ext.php'
		}),
		baseParams : {
			action : "getAdjust",
			'lang' : lan
		},
		autoLoad : true
	});

	dsClStockSource = new Ext.data.Store({
		reader : new Ext.data.JsonReader({
			fields : ['ClStockSourceId', 'SourceName'],
			root : 'rows'
		}),
		proxy : new Ext.data.HttpProxy({
			url : baseUrl + 'combo_ext.php'
		}),
		baseParams : {
			action : "getClStockSource",
			'lang' : lan
		},
		autoLoad : true
	});

	dsAmcChangeReason = new Ext.data.Store({
		reader : new Ext.data.JsonReader({
			fields : ['AmcChangeReasonId', 'AmcChangeReasonName'],
			root : 'rows'
		}),
		proxy : new Ext.data.HttpProxy({
			url : baseUrl + 'combo_ext.php'
		}),
		baseParams : {
			action : "getAmcChangeReason",
			'lang' : lan
		},
		autoLoad : true
	});

	dsOrderQtyChangeReason = new Ext.data.Store({
		reader : new Ext.data.JsonReader({
			fields : ['OUReasonId', 'OUReason'],
			root : 'rows'
		}),
		proxy : new Ext.data.HttpProxy({
			url : baseUrl + 'combo_ext.php'
		}),
		baseParams : {
			action : "getOrderQtyChangeReason",
			'lang' : lan
		},
		autoLoad : true
	});

	dsStockOutReason = new Ext.data.Store({
		reader : new Ext.data.JsonReader({
			fields : ['StockOutReasonId', 'StockOutReasonName'],
			root : 'rows'
		}),
		proxy : new Ext.data.HttpProxy({
			url : baseUrl + 'combo_ext.php'
		}),
		baseParams : {
			action : "getStockOutReason",
			'lang' : lan
		},
		autoLoad : true
	});

	//******************************** dsPatientOverview : Patient Overview Data Store ****************//
	dsPatientOverview = new Ext.data.Store({
		id : 'dsPatientOverviewId',
		proxy : new Ext.data.HttpProxy({
			url : baseUrl + 't_facility_level_monthlystatus_ext_server.php',
			method : 'POST'
		}),

		baseParams : {
			action : "getPatientOverview",
			jUserId : jUserId,
			'lang' : lan
		},
		reader : new Ext.data.JsonReader({
			root : 'results',
			totalProperty : 'total',
			id : 'id'
		}, [{
			name : 'CFMPOId',
			type : 'int',
			mapping : 'CFMPOId'
		}, {
			name : 'PatientTypeName',
			type : 'string',
			mapping : 'PatientTypeName'
		}, {
			name : 'RefillPatient',
			type : 'int',
			mapping : 'RefillPatient'
		}, {
			name : 'NewPatient',
			type : 'int',
			mapping : 'NewPatient'
		}, {
			name : 'TotalPatient',
			type : 'int',
			mapping : 'TotalPatient'
		}]),
		sortInfo : {
			field : 'PatientTypeName',
			direction : "ASC"
		}
	});

	dsPatientOverview.on('exception', function(DataProxy, type, action, options, response, arg) {
		//alert(response.responseText);

	}, this);

	var grid_summary = new Ext.grid.GridSummary();

	var gridPatientOverview = new Ext.grid.EditorGridPanel({
		id : 'patient-overview-id',
		region : 'center',
		style : 'text-align:left',
		stripeRows : true,
		store : dsPatientOverview,
		clicksToEdit : 1,
		region : 'fit',
		width : '100%',
		loadMask : {
			msg : TEXT['Loading data.'],
			enabled : true
		},
		sm : new Ext.grid.RowSelectionModel({
			singleSelect : true
		}),
		columns : [new Ext.grid.RowNumberer({
			header : '#'
		}), {
			header : 'CFMPOId',
			width : 120,
			dataIndex : 'CFMPOId',
			sortable : true,
			hidden : true
		}, {
			header : TEXT['Patient Type'],
			width : 200,
			align : 'left',
			dataIndex : 'PatientTypeName',
			sortable : true,
			hidden : false,
			summaryType : 'count',
			summaryRenderer : function(v, params, data) {
				return "Total";
			}
		}, {
			header : TEXT['Refill Patients'],
			width : 150,
			align : 'right',
			dataIndex : 'RefillPatient',
			sortable : true,
			hidden : true,
			summaryType : 'sum',
			renderer : render_val,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 10000000,
				selectOnFocus : true
			})
		}, {
			header : TEXT['New Patients'],
			width : 150,
			align : 'right',
			dataIndex : 'NewPatient',
			sortable : true,
			hidden : true,
			summaryType : 'sum',
			renderer : render_val,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 10000000,
				selectOnFocus : true
			})
		}, {
			header : TEXT['Total Patients'],
			width : 150,
			align : 'right',
			dataIndex : 'TotalPatient',
			sortable : true,
			hidden : false,
			summaryType : 'sum',
			renderer : render_val,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 10000000,
				selectOnFocus : true
			})
		}],
		plugins : grid_summary,
		viewConfig : {
		},
		autoHeight : true,
		align : 'center',
		columnLines : true
	});

	gridPatientOverview.on('afteredit', savePatientOverview);

	function handler_to_makeReadOnly(e) {
		return bReadOnly;
	}

	function makeReadOnly_patientOverview(e) {
		return false;
	}

	//function to make EditorGridPanel read only
	gridPatientOverview.on('beforeedit', makeReadOnly_patientOverview);

	// dsAdultRegimens = new Ext.data.GroupingStore({
	// id : 'dsRegimensId',
	// proxy : new Ext.data.HttpProxy({
	// url : baseUrl + 't_facility_level_monthlystatus_ext_server.php',
	// method : 'POST'
	// }),
	//
	// baseParams : {
	// action : "getRegimens",
	// pFormulationId : 1,
	// jUserId : jUserId
	// },
	// reader : new Ext.data.JsonReader({
	// root : 'results',
	// totalProperty : 'total',
	// id : 'id'
	// }, [{
	// name : 'CFMPatientStatusId',
	// type : 'int',
	// mapping : 'CFMPatientStatusId'
	// }, {
	// name : 'RegimenName',
	// type : 'string',
	// mapping : 'RegimenName'
	// }, {
	// name : 'FormulationName',
	// type : 'string',
	// mapping : 'FormulationName'
	// }, {
	// name : 'RefillPatient',
	// type : 'int',
	// mapping : 'RefillPatient'
	// }, {
	// name : 'NewPatient',
	// type : 'int',
	// mapping : 'NewPatient'
	// }, {
	// name : 'TotalPatient',
	// type : 'int',
	// mapping : 'TotalPatient'
	// }]),
	// sortInfo : {
	// field : 'RegimenName',
	// direction : "ASC"
	// },
	// groupField : 'FormulationName'
	// });

	dsAdultRegimens = new Ext.data.Store({
		id : 'dsPatientOverviewId',
		proxy : new Ext.data.HttpProxy({
			url : baseUrl + 't_facility_level_monthlystatus_ext_server.php',
			method : 'POST'
		}),

		baseParams : {
			action : "getRegimens",
			pFormulationId : 1,
			jUserId : jUserId,
			'lang' : lan
		},
		reader : new Ext.data.JsonReader({
			root : 'results',
			totalProperty : 'total',
			id : 'id'
		}, [{
			name : 'FormulationId',
			type : 'int',
			mapping : 'FormulationId'
		}, {
			name : 'FormulationName',
			type : 'string',
			mapping : 'FormulationName'
		}, {
			name : 'C0to4M',
			type : 'int',
			mapping : 'C0to4M'
		}, {
			name : 'C0to4M_Id',
			type : 'int',
			mapping : 'C0to4M_Id'
		}, {
			name : 'C0to4F',
			type : 'int',
			mapping : 'C0to4F'
		}, {
			name : 'C0to4F_Id',
			type : 'int',
			mapping : 'C0to4F_Id'
		}, {
			name : 'C5to14M',
			type : 'int',
			mapping : 'C5to14M'
		}, {
			name : 'C5to14M_Id',
			type : 'int',
			mapping : 'C5to14M_Id'
		}, {
			name : 'C5to14F',
			type : 'int',
			mapping : 'C5to14F'
		}, {
			name : 'C5to14F_Id',
			type : 'int',
			mapping : 'C5to14F_Id'
		}, {
			name : 'C15PlusM',
			type : 'int',
			mapping : 'C15PlusM'
		}, {
			name : 'C15PlusM_Id',
			type : 'int',
			mapping : 'C15PlusM_Id'
		}, {
			name : 'C15PlusF',
			type : 'int',
			mapping : 'C15PlusF'
		}, {
			name : 'C15PlusF_Id',
			type : 'int',
			mapping : 'C15PlusF_Id'
		}, {
			name : 'PregnantWomen',
			type : 'int',
			mapping : 'PregnantWomen'
		}, {
			name : 'PregnantWomen_Id',
			type : 'int',
			mapping : 'PregnantWomen_Id'
		}, {
			name : 'TotalPatient',
			type : 'int',
			mapping : 'TotalPatient'
		}]),
		sortInfo : {
			field : 'FormulationName',
			direction : "ASC"
		}
	});

	dsAdultRegimens.on('exception', function(DataProxy, type, action, options, response, arg) {
		//alert(response.responseText);
	}, this);

	// jstore = new Ext.data.Store({
	// id : 'mystore',
	// proxy : new Ext.data.HttpProxy({
	// url : baseUrl + 't_facility_level_monthlystatus_ext_server.php',
	// method : 'POST'
	// }),
	// baseParams : {
	// action : "getRegimens"
	// },
	// //remoteSort:true,
	// listeners : {
	// // load: function() {
	// // this.loadingBox.hide();
	// // },
	// // beforeLoad: function() {
	// // this.loadingBox = Ext.Msg.wait('Loading');
	// // },
	// // exception: function(d,s,a,o,r) {
	// // this.loadingBox.hide();
	// // Ext.Msg.show
	// // ({
	// // title:'Error',
	// // msg:'Failed to load data',
	// // icon:Ext.MessageBox.ERROR,
	// // buttons:Ext.Msg.OK
	// // });
	// // },
	// metachange : function(store, meta) {
	// //console.log(meta.fields);
	// var myColumns = [];
	// for (var i = 0, len = meta.fields.length; i < len; i++) {
	// var field = meta.fields[i];
	// //alert(field.name);
	// var this_column = {};
	// this_column['id'] = field.name;
	// this_column['header'] = field.name;
	// this_column['width'] = 100;
	// //this_column['sortable'] = true;
	// this_column['dataIndex'] = field.name;
	// this_column['style'] = 'color:#000000;';
	// this_column['align'] = (field.name == 'Patient_Overview' ? 'left' : 'right');
	// this_column['renderer'] = render_val;
	//
	// this_column['editor '] = new Ext.form.NumberField({
	// allowBlank : true,
	// allowNegative : false,
	// maxValue : 10000000,
	// selectOnFocus : true
	// });
	//
	// myColumns.push(this_column);
	// }
	// //console.log(myColumns);
	// var newColumnModel = new Ext.grid.ColumnModel({
	// columns : myColumns
	// //meta.fields
	// });
	// gridAdultRegimens.reconfigure(store, newColumnModel);
	// }
	// },
	// reader : new Ext.data.JsonReader()
	// });

	// gridAdultRegimens = new Ext.grid.EditorGridPanel({
	// id: 'gridPatientId',
	// store: jstore,
	// stripeRows: true,
	// height: 350,
	// width: 600,
	// stateful: true,
	// clicksToEdit : 1,
	//
	// title: '<center>Patient Trend Time Series</center>',
	// // sm : new Ext.grid.RowSelectionModel({
	// // singleSelect : true
	// // }),
	// cm: new Ext.grid.ColumnModel({
	// // defaults: {
	// // width: 100,
	// // sortable: true
	// // }
	// //columns: myColumns
	// }),
	// viewConfig: {
	// forceFit: true,
	// emptyText: 'No records found for this month',
	// getRowClass: function(record, index) {
	// // var c = record.get('Patient_Overview');
	// // if (c == "TOTAL") {
	// // return 'price-fall';
	// // }
	// }
	// }
	// });

	var categoryGroupRow = [];

	categoryGroupRow.push({
		header : '',
		align : 'center',
		colspan : 3
	});

	categoryGroupRow.push({
		header : TEXT['0-4 Years'],
		align : 'center',
		colspan : 2
	});
	categoryGroupRow.push({
		header : TEXT['5-14 Years'],
		align : 'center',
		colspan : 2
	});
	categoryGroupRow.push({
		header : TEXT['15+ Years'],
		align : 'center',
		colspan : 2
	});
	categoryGroupRow.push({
		header : '',
		align : 'center',
		colspan : 1
	});
	categoryGroupRow.push({
		header : '',
		align : 'center',
		colspan : 1
	});

	var catGroup = new Ext.ux.grid.ColumnHeaderGroup({
		rows : [categoryGroupRow]
	});

	var grid_summary_AdultRegimens = new Ext.grid.GridSummary();

	gridAdultRegimens = new Ext.grid.EditorGridPanel({
		//title: '<h3><center>Adult Regimens List</center></h3>',
		id : 'adult-regimens-id',
		region : 'center',
		style : 'text-align:left',
		stripeRows : true,
		store : dsAdultRegimens,
		clicksToEdit : 1,
		region : 'fit',
		width : '100%',
		loadMask : {
			msg : TEXT['Loading data.'],
			enabled : true
		},
		sm : new Ext.grid.RowSelectionModel({
			singleSelect : true,
			moveEditorOnEnterLikeOnTab : true
		}),
		columns : [new Ext.grid.RowNumberer({
			header : '#'
		}), {
			header : "FormulationId",
			width : 10,
			dataIndex : 'FormulationId',
			sortable : true,
			hidden : true
		}, {
			header : TEXT['Patient Type'],
			width : 200,
			align : 'left',
			dataIndex : 'FormulationName',
			sortable : true,
			hidden : false,
			summaryType : 'count',
			summaryRenderer : function(v, params, data) {
				return "Total";
			}
		}, {
			header : 'M',
			width : 100,
			align : 'right',
			dataIndex : 'C0to4M',
			sortable : true,
			hidden : false,
			summaryType : 'sum',
			renderer : render_val,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 10000000,
				selectOnFocus : true
			})
		}, {
			header : 'F',
			width : 100,
			align : 'right',
			dataIndex : 'C0to4F',
			sortable : true,
			hidden : false,
			summaryType : 'sum',
			renderer : render_val,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 10000000,
				selectOnFocus : true
			})
		}, {
			header : 'M',
			width : 100,
			align : 'right',
			dataIndex : 'C5to14M',
			sortable : true,
			hidden : false,
			summaryType : 'sum',
			renderer : render_val,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 10000000,
				selectOnFocus : true
			})
		}, {
			header : 'F',
			width : 100,
			align : 'right',
			dataIndex : 'C5to14F',
			sortable : true,
			hidden : false,
			summaryType : 'sum',
			renderer : render_val,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 10000000,
				selectOnFocus : true
			})
		}, {
			header : 'M',
			width : 100,
			align : 'right',
			dataIndex : 'C15PlusM',
			sortable : true,
			hidden : false,
			summaryType : 'sum',
			renderer : render_val,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 10000000,
				selectOnFocus : true
			})
		}, {
			header : 'F',
			width : 100,
			align : 'right',
			dataIndex : 'C15PlusF',
			sortable : true,
			hidden : false,
			summaryType : 'sum',
			renderer : render_val,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 10000000,
				selectOnFocus : true
			})
		}, {
			header : TEXT['Pregnant Women'],
			width : 120,
			align : 'right',
			dataIndex : 'PregnantWomen',
			sortable : true,
			hidden : false,
			summaryType : 'sum',
			renderer : render_val,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 10000000,
				selectOnFocus : true
			})
		}, {
			header : '<b>' + TEXT['Total Patients'] + '</b>',
			width : 100,
			align : 'right',
			dataIndex : 'TotalPatient',
			sortable : true,
			hidden : false,
			summaryType : 'sum',
			renderer : function(value, metadata, record, rowIndex, colIndex, store) {

				metadata.css = "arv-mos";
				// if (value == 0) {
				// return "";
				// } else
				return Ext.util.Format.number(value, '0,000');

			}
		}],
		viewConfig : {
			emptyText : TEXT['No rows to display']
		},
		autoHeight : true,
		align : 'center',
		columnLines : true,
		plugins : [catGroup, grid_summary_AdultRegimens]
	});

	gridAdultRegimens.on('afteredit', saveAdultRegimens);

	//function to make EditorGridPanel read only
	gridAdultRegimens.on('beforeedit', handler_to_makeReadOnly);

	dsMasterStockData = new Ext.data.Store({
		id : 'dsMasterStockDataId',
		proxy : new Ext.data.HttpProxy({
			url : baseUrl + 't_facility_level_monthlystatus_ext_server.php',
			method : 'POST'
		}),

		baseParams : {
			action : "getMasterStockData",
			pUserId : pUserId,
			'lang' : lan
		},
		reader : new Ext.data.JsonReader({
			root : 'results',
			totalProperty : 'total',
			id : 'id'
		}, [{
			name : 'ReportId',
			type : 'int',
			mapping : 'CFMStockId'
		}, {
			name : 'FacilityId',
			type : 'int',
			mapping : 'FacilityId'
		}, {
			name : 'MonthId',
			type : 'int',
			mapping : 'MonthId'
		}, {
			name : 'Year',
			type : 'string',
			mapping : 'Year'
		}, {
			name : 'CreatedBy',
			type : 'string',
			mapping : 'CreatedBy'
		}, {
			name : 'CreatedDt',
			type : 'string',
			mapping : 'CreatedDt'
			//dateFormat: 'm/d/Y H:i:s A'
			//'n/d/Y H:i:s A'

		}, {
			name : 'LastUpdateBy',
			type : 'string',
			mapping : 'LastUpdateBy'
		}, {
			name : 'LastUpdateDt',
			type : 'string',
			mapping : 'LastUpdateDt'
		}, {
			name : 'LastSubmittedBy',
			type : 'string',
			mapping : 'LastSubmittedBy'
		}, {
			name : 'LastSubmittedDt',
			type : 'string',
			mapping : 'LastSubmittedDt'
		}, {
			name : 'StatusId',
			type : 'int',
			mapping : 'StatusId'
		}, {
			name : 'StatusName',
			type : 'string',
			mapping : 'StatusName'
		}, {
			name : 'AcceptedDt',
			type : 'string',
			mapping : 'AcceptedDt'
		}, {
			name : 'PublishedBy',
			type : 'string',
			mapping : 'PublishedBy'
		}, {
			name : 'PublishedDt',
			type : 'string',
			mapping : 'PublishedDt'
		}]),
		sortInfo : {
			field : 'ReportId',
			direction : "ASC"
		}
	});

	var elePanelMaster = Ext.get('panelMaster');
	elePanelMaster.setVisibilityMode(Ext.Element.DISPLAY);
	clearMasterInfo();
	elePanelMaster.hide();

	dsMasterStockData.on('exception', function(DataProxy, type, action, options, response, arg) {
		alert(response.responseText);
	}, this);

	dsMasterStockData.on('load', function(store, records, options) {

		if (store.getCount() == 1)
			reportId = store.data.items[0].data.ReportId;
		else
			reportId = 0;
		pReportId = reportId;
		if (reportId > 0) {
			elePanelMaster.show();
			document.getElementById("txtReportIdDiv").innerHTML = TEXT["Report Id"] + " : " + reportId;

			pStatusId = store.data.items[0].data.StatusId;

			if (store.data.items[0].data.StatusId == 1) {
				Ext.getCmp('submitid').setText(TEXT['Submit']);
				if (jUserGroups[ENTRY_ADMIN] == ENTRY_ADMIN || jUserGroups[ENTRY_OPERATOR] == ENTRY_OPERATOR) {
					Ext.getCmp('submitid').enable();
					bReadOnly = true;
				} else {
					Ext.getCmp('submitid').disable();
					bReadOnly = false;
				}
			} else if (store.data.items[0].data.StatusId == 2) {
				Ext.getCmp('submitid').setText(TEXT['Publish']);
				if (jUserGroups[ENTRY_ADMIN] == ENTRY_ADMIN || jUserGroups[ENTRY_MANAGER] == ENTRY_MANAGER) {
					Ext.getCmp('submitid').enable();
					bReadOnly = true;
				} else if (jUserGroups[ENTRY_OPERATOR] == ENTRY_OPERATOR) {
					Ext.getCmp('submitid').disable();
					bReadOnly = false;
				} else {
					Ext.getCmp('submitid').disable();
				}
			} else if (store.data.items[0].data.StatusId == 5) {
				if (jUserGroups[ENTRY_ADMIN] == ENTRY_ADMIN) {
					Ext.getCmp('submitid').disable();
					bReadOnly = false;
				} else if (jUserGroups[ENTRY_MANAGER] == ENTRY_MANAGER || jUserGroups[ENTRY_OPERATOR] == ENTRY_OPERATOR) {
					Ext.getCmp('submitid').disable();
					bReadOnly = false;
				}
			} else {
				Ext.getCmp('submitid').enable();
				bReadOnly = true;
			}

			// if (jUserGroups[11] == 11)
			// Ext.getCmp('unpublishid').enable();
			// else
			// Ext.getCmp('unpublishid').disable();

			var statusColor = '#2A7907';
			if (store.data.items[0].data.StatusId == 1) {
				var statusColor = '#FF0000';
			} else {
				var statusColor = '#2A7907';
			}

			document.getElementById("txtSubmitStatusDiv").innerHTML = "<span style='font-size:2em;color:" + statusColor + ";'>" + store.data.items[0].data.StatusName + "</span>";

			// if (store.data.items[0].data.CreatedDt != '')
			// document.getElementById('created-date').innerHTML = TEXT["Created Date"] + " : " + store.data.items[0].data.CreatedDt;
			// if (store.data.items[0].data.LastSubmittedDt != '')
			// document.getElementById('submitted-date').innerHTML = TEXT["Submitted Date"] + " : " + store.data.items[0].data.LastSubmittedDt;
			// if (store.data.items[0].data.AcceptedDt != '')
			// document.getElementById('accepted-date').innerHTML = TEXT["Accepted Date"] + " : " + store.data.items[0].data.AcceptedDt;
			// if (store.data.items[0].data.PublishedDt != '')
			// document.getElementById('published-date').innerHTML = TEXT["Published Date"] + " : " + store.data.items[0].data.PublishedDt;

			if (store.data.items[0].data.CreatedBy != '')
				document.getElementById('created-by').innerHTML = "Created By : " + store.data.items[0].data.CreatedBy;
			if (store.data.items[0].data.CreatedDt != '')
				document.getElementById('created-date').innerHTML = TEXT["Created Date"] + " : " + store.data.items[0].data.CreatedDt;
			if (store.data.items[0].data.LastSubmittedBy != '')
				document.getElementById('submitted-by').innerHTML = "Submitted By : " + store.data.items[0].data.LastSubmittedBy;
			if (store.data.items[0].data.LastSubmittedDt != '')
				document.getElementById('submitted-date').innerHTML = "Submitted Date : " + store.data.items[0].data.LastSubmittedDt;
			if (store.data.items[0].data.LastUpdateBy != '')
				document.getElementById('updated-by').innerHTML = "Last Upadated By : " + store.data.items[0].data.LastUpdateBy;
			if (store.data.items[0].data.LastUpdateDt != '')
				document.getElementById('updated-date').innerHTML = "Last Updated Date : " + store.data.items[0].data.LastUpdateDt;
			if (store.data.items[0].data.PublishedBy != '')
				document.getElementById('published-by').innerHTML = "Published By : " + store.data.items[0].data.PublishedBy;
			if (store.data.items[0].data.PublishedDt != '')
				document.getElementById('published-date').innerHTML = "Published Date : " + store.data.items[0].data.PublishedDt;
		} else {
			clearMasterInfo();
			elePanelMaster.hide();
		}

		setBaseParams('pReportId', reportId);

		loadAllData();

		// if (store.getCount() == 1)
		// reportId = store.data.items[0].data.ReportId;
		// else
		// reportId = 0;
		// pReportId = reportId;
		// if (reportId > 0) {
		// elePanelMaster.show();
		// document.getElementById("txtReportIdDiv").innerHTML = TEXT["Report Id"] + " : " + reportId;
		//
		// pStatusId = store.data.items[0].data.StatusId;
		// if (store.data.items[0].data.StatusId == 5) {
		// Ext.getCmp('submitid').disable();
		// bReadOnly = false;
		// } else {
		// Ext.getCmp('submitid').enable();
		// bReadOnly = true;
		// }
		// //if (store.data.items[0].data.StatusId == 1)
		// Ext.getCmp('submitid').setText(TEXT['Submit']);
		// // else if (store.data.items[0].data.StatusId == 2)
		// // Ext.getCmp('submitid').setText(TEXT['Accept']);
		// // else if (store.data.items[0].data.StatusId == 3)
		// // Ext.getCmp('submitid').setText(TEXT['Publish']);
		//
		// var statusColor = '#2A7907';
		// if (store.data.items[0].data.StatusId == 1) {
		// var statusColor = '#FF0000';
		// } else {
		// var statusColor = '#2A7907';
		// }
		//
		// document.getElementById("txtSubmitStatusDiv").innerHTML = "<span style='font-size:2em;color:" + statusColor + ";'>" + store.data.items[0].data.StatusName + "</span>";
		//
		// //alert(store.data.items[0].data.CreatedBy);
		//
		// if (store.data.items[0].data.CreatedBy != '')
		// document.getElementById('created-by').innerHTML = "Created By : " + store.data.items[0].data.CreatedBy;
		// if (store.data.items[0].data.CreatedDt != '')
		// document.getElementById('created-date').innerHTML = TEXT["Created Date"] + " : " + store.data.items[0].data.CreatedDt;
		// if (store.data.items[0].data.LastSubmittedBy != '')
		// document.getElementById('submitted-by').innerHTML = "Submitted By : " + store.data.items[0].data.LastSubmittedBy;
		// if (store.data.items[0].data.LastSubmittedDt != '')
		// document.getElementById('submitted-date').innerHTML = "Submitted Date : " + store.data.items[0].data.LastSubmittedDt;
		// if (store.data.items[0].data.LastUpdateBy != '')
		// document.getElementById('updated-by').innerHTML = "Last Upadated By : " + store.data.items[0].data.LastUpdateBy;
		// if (store.data.items[0].data.LastUpdateDt != '')
		// document.getElementById('updated-date').innerHTML = "Last Updated Date : " + store.data.items[0].data.LastUpdateDt;
		// } else {
		// clearMasterInfo();
		// elePanelMaster.hide();
		// }
		//
		// dsArvData.setBaseParam('pReportId', reportId);
		//
		// loadAllData();

	}, this);

	function createReport() {

		vMonthId = Ext.getCmp('cboMonthId').getValue();
		vYearId = Ext.getCmp('cboYearId').getValue();

		//getOpeningEditable();

		vCountryId = Ext.getCmp('cboCountryId').getRawValue();
		vItemGroupId = Ext.getCmp('cboItemGroupId').getRawValue();

		vMonth = Ext.getCmp('cboMonthId').getRawValue();
		vYear = Ext.getCmp('cboYearId').getRawValue();

		if (vCountryId == "" || vMonthId == "" || vYearId == "") {
			var msgbox = Ext.Msg.show({
				msg : 'You must select country, itemgroup, month, year and facility.',
				icon : Ext.Msg.INFO,
				minWidth : 200,
				buttons : Ext.Msg.OK,
				scope : this
			}).getDialog();

			return;
		}

		selYearId = parseInt(vYearId);
		selMonth = parseInt(vMonthId);

		selInitYearId = parseInt(initYearId);
		selInitMonthId = parseInt(initMonthId);

		if (selYearId < selInitYearId) {
			var msgbox = Ext.Msg.show({
				msg : 'Starting Month-Year for ARV Data is ' + startManthName + '-' + initYearId + '',
				icon : Ext.Msg.INFO,
				minWidth : 200,
				buttons : Ext.Msg.OK,
				scope : this
			}).getDialog();
			return;
		} else if (selYearId == selInitYearId && selMonth < selInitMonthId) {
			var msgbox = Ext.Msg.show({
				msg : 'Starting Month-Year for ARV Data is ' + startManthName + '-' + initYearId + '',
				icon : Ext.Msg.INFO,
				minWidth : 200,
				buttons : Ext.Msg.OK,
				scope : this
			}).getDialog();
			return;
		} else {
			getFacilityRecordOfPrevMonth();
		}
	}

	function clearMasterInfo() {
		document.getElementById("txtReportIdDiv").innerHTML = "";
		document.getElementById("txtSubmitStatusDiv").innerHTML = "";
		document.getElementById('created-by').innerHTML = "";
		document.getElementById('created-date').innerHTML = "";
		document.getElementById('submitted-by').innerHTML = "";
		document.getElementById('submitted-date').innerHTML = "";
		document.getElementById('updated-by').innerHTML = "";
		document.getElementById('updated-date').innerHTML = "";
		document.getElementById('published-by').innerHTML = "";
		document.getElementById('published-date').innerHTML = "";
	}

	//******************************** dsArvData: ArvData Data Store ****************//
	dsArvData = new Ext.data.Store({
		id : 'dsArvDataId',
		proxy : new Ext.data.HttpProxy({
			url : baseUrl + 't_facility_level_monthlystatus_ext_server.php',
			method : 'POST'
		}),

		baseParams : {
			action : "getStockData",
			jUserId : jUserId,
			'lang' : lan
		},
		reader : new Ext.data.JsonReader({
			root : 'results',
			totalProperty : 'total',
			id : 'id'
		}, [{
			name : 'ARVDataId',
			type : 'int',
			mapping : 'CFMStockStatusId'
		}, {
			name : 'FacilityId',
			type : 'int',
			mapping : 'FacilityId'
		}, {
			name : 'MonthId',
			type : 'int',
			mapping : 'MonthId'
		}, {
			name : 'Year',
			type : 'string',
			mapping : 'Year'
		}, {
			name : 'ItemGroupId',
			type : 'int',
			mapping : 'ItemGroupId'
		}, {
			name : 'ItemNo',
			type : 'int',
			mapping : 'ItemNo'
		}, {
			name : 'ItemSL',
			type : 'int',
			mapping : 'ItemSL'
		}, {
			name : 'ItemName',
			type : 'string',
			mapping : 'ItemName'
		}, {
			name : 'OpStock_A',
			type : 'string',
			mapping : 'OpStock_A'
		}, {
			name : 'OpStock_C',
			type : 'string',
			mapping : 'OpStock_C'
		}, {
			name : 'ReceiveQty',
			type : 'string',
			mapping : 'ReceiveQty'
		}, {
			name : 'DispenseQty',
			type : 'string',
			mapping : 'DispenseQty'
		}, {
			name : 'BeforeLastMonthDispensed',
			type : 'int',
			mapping : 'BeforeLastMonthDispensed'
		}, {
			name : 'LastMonthDispensed',
			type : 'int',
			mapping : 'LastMonthDispensed'
		}, {
			name : 'AdjustQty',
			type : 'string',
			mapping : 'AdjustQty'
		}, {
			name : 'AdjustReason',
			type : 'string',
			mapping : 'AdjustReason'
		}, {
			name : 'StockoutDays',
			type : 'string',
			mapping : 'StockoutDays'
		}, {
			name : 'ClStock_A',
			type : 'string',
			mapping : 'ClStock_A'
		}, {
			name : 'ClStock_C',
			type : 'string',
			mapping : 'ClStock_C'
		}, {
			name : 'MOS',
			type : 'string',
			mapping : 'MOS'
		}, {
			name : 'AMC_C',
			type : 'string',
			mapping : 'AMC_C'
		}, {
			name : 'AMC',
			type : 'string',
			mapping : 'AMC'
		}, {
			name : 'MaxQty',
			type : 'string',
			mapping : 'MaxQty'
		}, {
			name : 'OrderQty',
			type : 'string',
			mapping : 'OrderQty'
		}, {
			name : 'ActualQty',
			type : 'string',
			mapping : 'ActualQty'
		}, {
			name : 'OUReasonId',
			type : 'int',
			mapping : 'OUReasonId'
		}, {
			name : 'UserId',
			type : 'int',
			mapping : 'UserId'
		}, {
			name : 'LastEditTime',
			type : 'string',
			mapping : 'LastEditTime'
		}, {
			name : 'FormulationName',
			type : 'string',
			mapping : 'FormulationName'
		}, {
			name : 'ClStockSourceId',
			type : 'int',
			mapping : 'ClStockSourceId'
		}, {
			name : 'AmcChangeReasonId',
			type : 'int',
			mapping : 'AmcChangeReasonId'
		}, {
			name : 'StockOutReasonId',
			type : 'int',
			mapping : 'StockOutReasonId'
		}]),
		sortInfo : {
			field : 'ItemSL',
			direction : "ASC"
		}
	});

	dsArvData.on('load', function(store, records, options) {

	});
	dsArvData.on('exception', function(DataProxy, type, action, options, response, arg) {
		alert(response.responseText);
	}, this);

	var cboAdjustReason = new Ext.form.ComboBox({
		id : 'cboAdjustId',
		displayField : 'AdjustReason',
		valueField : 'AdjustId',
		store : dsAdjustReason,
		mode : 'local',
		allowBlank : true,
		selectOnFocus : true,
		triggerAction : 'all',
		listeners : {
			'focus' : {
				fn : function(comboField) {
				}
			}
		}
	});

	var cboClStockSource = new Ext.form.ComboBox({
		id : 'cboClStockSource',
		displayField : 'SourceName',
		valueField : 'ClStockSourceId',
		store : dsClStockSource,
		mode : 'local',
		allowBlank : true,
		selectOnFocus : true,
		triggerAction : 'all',
		listeners : {
			'focus' : {
				fn : function(comboField) {
				}
			}
		}
	});

	var cboAmcChangeReason = new Ext.form.ComboBox({
		id : 'cboAmcChangeReason',
		displayField : 'AmcChangeReasonName',
		valueField : 'AmcChangeReasonId',
		store : dsAmcChangeReason,
		mode : 'local',
		allowBlank : true,
		selectOnFocus : true,
		triggerAction : 'all',
		listeners : {
			'focus' : {
				fn : function(comboField) {
				}
			}
		}
	});

	var cboOrderQtyChangeReason = new Ext.form.ComboBox({
		id : 'cboOrderQtyChangeReason',
		displayField : 'OUReason',
		valueField : 'OUReasonId',
		store : dsOrderQtyChangeReason,
		mode : 'local',
		allowBlank : true,
		selectOnFocus : true,
		triggerAction : 'all',
		listeners : {
			'focus' : {
				fn : function(comboField) {
				}
			}
		}
	});

	var cboStockOutReason = new Ext.form.ComboBox({
		id : 'cboStockOutReason',
		displayField : 'StockOutReasonName',
		valueField : 'StockOutReasonId',
		store : dsStockOutReason,
		mode : 'local',
		allowBlank : true,
		selectOnFocus : true,
		triggerAction : 'all',
		listeners : {
			'focus' : {
				fn : function(comboField) {
				}
			}
		}
	});

	function setBaseParams(key, value) {
		dsPatientOverview.setBaseParam(key, value);
		dsAdultRegimens.setBaseParam(key, value);
		dsArvData.setBaseParam(key, value);
		dsMasterStockData.setBaseParam(key, value);
		//console.log(dsArvData.baseParams);
	}

	var cboCountry = new Ext.form.ComboBox({
		id : 'cboCountryId',
		displayField : 'CountryName',
		valueField : 'CountryId',
		disabled : false,
		width : 90,
		store : new Ext.data.ArrayStore({
			autoDestroy : true,
			fields : ['CountryId', 'CountryName', 'StartMonth', 'StartYear'],
			data : gCountries
		}),
		emptyText : TEXT['Select Country...'],
		mode : 'local',
		allowBlank : true,
		selectOnFocus : true,
		triggerAction : 'all',
		forceSelection : true,
		listeners : {
			select : function(field, rec, selIndex) {
				pRegionId = 0;
				pDistrictId = 0;

				pCountryId = rec.get('CountryId');
				pCountryName = rec.get('CountryName');
				dsRegion.setBaseParam('pCountryId', pCountryId);

				dsRegion.removeAll(true);
				cboRegion.clearValue();
				cboRegion.applyEmptyText();
				dsRegion.load();

				dsDistricts.removeAll(true);
				cboDistrict.clearValue();
				cboDistrict.applyEmptyText();

				dsFacility.setBaseParam('pCountryId', pCountryId);
				setBaseParams('pCountryId', pCountryId);

				//dsMasterStockData.loadData([],false);

				// dsMasterStockData.load({
				// params : {
				// 'pFacilityId' : 0,
				// 'pMonthId' : 0,
				// 'pYearId' : '2000',
				// 'pCountryId' : 1,
				// 'pItemGroupId' : 1,
				// 'lang' : 'en-GB'
				// }
				// });

				//loadMasterStockData() //false call to refress or empty master stock position

				loadFacility();
			}
		}
	});

	//console.log(gRegionListArray);

	var cboRegion = new Ext.form.ComboBox({
		id : 'cboRegionId',
		displayField : 'RegionName',
		valueField : 'RegionId',
		disabled : false,
		width : 90,
		store : dsRegion,
		emptyText : TEXT['Select Region...'],
		mode : 'local',
		allowBlank : true,
		selectOnFocus : true,
		triggerAction : 'all',
		forceSelection : true,
		listeners : {
			select : function(field, rec, selIndex) {
				pDistrictId = 0;
				pRegionId = rec.get('RegionId');
				dsFacility.setBaseParam('pRegionId', pRegionId);
				setBaseParams('pRegionId', pRegionId);

				dsDistricts.setBaseParam('pCountryId', pCountryId);
				dsDistricts.setBaseParam('pRegionId', pRegionId);

				dsDistricts.removeAll(true);
				cboDistrict.clearValue();
				cboDistrict.applyEmptyText();
				//cboDistrict.getPicker().getSelectionModel().doMultiSelect([], false);

				dsDistricts.load();

				loadFacility();
			}
		}
	});

	var cboDistrict = new Ext.form.ComboBox({
		id : 'cboDistrictId',
		hiddenName : 'hCboDistrictId',
		mode : 'local',
		triggerAction : 'all',
		emptyText : TEXT['Select District...'],
		fieldLabel : TEXT['District'],
		displayField : 'DistrictName',
		valueField : 'DistrictId',
		anchor : '95%',
		allowBlank : true,
		selectOnFocus : true,
		store : dsDistricts,
		width : 100,
		listeners : {
			select : function(field, rec, selIndex) {
				pDistrictId = rec.get('DistrictId');
				dsFacility.setBaseParam('pDistrictId', pDistrictId);
				setBaseParams('pDistrictId', pDistrictId);
				loadFacility();
			}
		}
	});

	var cboOwnerType = new Ext.form.ComboBox({
		id : 'cboOwnerTypeId',
		displayField : 'OwnerTypeName',
		valueField : 'OwnerTypeId',
		disabled : false,
		width : 90,
		store : new Ext.data.ArrayStore({
			autoDestroy : true,
			fields : ['OwnerTypeId', 'OwnerTypeName'],
			data : gOwnerTypeListArray
		}),
		emptyText : TEXT['Select Owner...'],
		mode : 'local',
		allowBlank : true,
		selectOnFocus : true,
		triggerAction : 'all',
		forceSelection : true,
		listeners : {
			select : function(field, rec, selIndex) {
				pOwnerTypeId = rec.get('OwnerTypeId');
				dsFacility.setBaseParam('pOwnerTypeId', pOwnerTypeId);
				setBaseParams('pOwnerTypeId', pOwnerTypeId);
				loadFacility();
			}
		}
	});

	var cboItemGroup = new Ext.form.ComboBox({
		id : 'cboItemGroupId',
		displayField : 'GroupName',
		valueField : 'ItemGroupId',
		width : 90,
		store : new Ext.data.ArrayStore({
			autoDestroy : true,
			fields : ['ItemGroupId', 'GroupName', 'bPatient', 'UserId'],
			data : gItemGroupListArry
		}),
		emptyText : TEXT['Select Group...'],
		mode : 'local',
		allowBlank : true,
		selectOnFocus : true,
		triggerAction : 'all',
		listeners : {
			select : function(field, rec, selIndex) {
				pItemGroupId = rec.get('ItemGroupId');
				dsFacility.setBaseParam('pItemGroupId', pItemGroupId);
				setBaseParams('pItemGroupId', pItemGroupId);

				bPatient = rec.get('bPatient');

				// if (bPatient == 1) {
				// Ext.getCmp('tabItemPatientOverviewId').enable();
				// Ext.getCmp('tabItemAdultRegimens').enable();
				// } else if (bPatient == 0) {
				// Ext.getCmp('tabItemPatientOverviewId').disable();
				// Ext.getCmp('tabItemAdultRegimens').disable();
				// }

				//pMonthId = 0;
				pFacilityId = 0;
				pFLevelId = 0;
				gRowIndex = 0;

				loadFacility();

			}
		}
	});

	function loadFacility() {
		if (pCountryId == null || pRegionId == null || pDistrictId == null || pOwnerTypeId == null || pMonthId == null || pYearId == null)
			return;

		if (pDistrictId == tmpDistrictId) {
			if (!!gridFacility.store.lastOptions) {
				start = gridFacility.store.lastOptions.params.start;
			}
			tmpDistrictId = pDistrictId;
		} else {
			start = 0;
			tmpDistrictId = pDistrictId;
		}

		dsFacility.removeAll(true);

		dsFacility.load({
			params : {
				start : start,
				limit : 25
			}
		});
	}

	gridArvData = new Ext.grid.EditorGridPanel({
		id : 'gridArvDataId',
		style : 'text-align:left',
		stripeRows : true,
		store : dsArvData,
		clicksToEdit : 1,
		region : 'fit',
		width : '100%',
		loadMask : {
			msg : TEXT['Loading data.'],
			enabled : true
		},
		sm : new Ext.grid.RowSelectionModel({
			singleSelect : true,
			//moveEditorOnEnter: true
			moveEditorOnEnterLikeOnTab : true
		}),

		columns : [{
			header : "ARVDataId",
			width : 70,
			dataIndex : 'ARVDataId',
			sortable : false,
			hidden : true
		}, {
			header : "FacilityId",
			width : 70,
			align : 'left',
			dataIndex : 'FacilityId',
			sortable : false,
			hidden : true
		}, {
			header : "MonthId",
			width : 70,
			align : 'left',
			dataIndex : 'MonthId',
			sortable : false,
			hidden : true
		}, {
			header : "Year",
			width : 70,
			align : 'left',
			dataIndex : 'Year',
			sortable : false,
			hidden : true
		}, {
			header : "ItemGroupId",
			width : 70,
			align : 'left',
			dataIndex : 'ItemGroupId',
			sortable : true,
			hidden : true
		}, {
			header : "ItemNo",
			width : 70,
			align : 'left',
			dataIndex : 'ItemNo',
			sortable : false,
			hidden : true
		}, {
			header : "SL#",
			width : 50,
			align : 'center',
			dataIndex : 'ItemSL',
			sortable : true,
			hidden : false
		}, {
			header : TEXT["Item"] + "<br/>&nbsp",
			tooltip : TEXT["Item Name"],
			width : 170,
			align : 'left',
			dataIndex : 'ItemName',
			sortable : false,
			hidden : false
		}, {
			header : "OBL(c)<br/>(C)",
			tooltip : "Opening Balance",
			width : 70,
			align : 'right',
			dataIndex : 'OpStock_C',
			sortable : false,
			hidden : true,
			renderer : render_val
		}, {
			header : TEXT["OBL"] + "<br/>(A)",
			tooltip : TEXT['Opening Balance'] + " (OBL) -> (A)",
			width : 70,
			align : 'right',
			dataIndex : 'OpStock_A',
			sortable : false,
			hidden : false,
			renderer : function(value, metadata, record, rowIndex, colIndex, store) {
				// if ((record.data.OpStock_A + record.data.ReceiveQty + record.data.AdjustQty - record.data.ClStock_A) != record.data.DispenseQty) {
				// metadata.css = '';
				// } else
				// metadata.css = "arv-obla";
				// if (value == 0) {
				// return "";
				// } else
				return Ext.util.Format.number(value, '0,000');
			},
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : true,
				maxValue : 999999999,
				selectOnFocus : true
			})
		}, {
			header : TEXT["Received"] + "<br/>(B)",
			tooltip : TEXT['Received Quantity'] + " -> (B)",
			width : 70,
			align : 'right',
			dataIndex : 'ReceiveQty',
			sortable : false,
			hidden : false,
			//renderer : render_val,
			renderer : function(value, metadata, record, rowIndex, colIndex, store) {
				//console.log(value);
				//metadata.css = "arv-cblc";
				// if (value === 0) {
				//
				// return "0";
				// }
				// else if (value === "") {
				// return "";
				// } else
				return Ext.util.Format.number(value, '0,000');
			},
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 999999999,
				selectOnFocus : true
			})
		}, {
			header : TEXT["Dispensed"] + "<br/>(C)",
			tooltip : TEXT['Dispensed Quantity'] + " -> (C)",
			width : 70,
			align : 'right',
			dataIndex : 'DispenseQty',
			sortable : false,
			hidden : false,
			renderer : function(value, metadata, record, rowIndex, colIndex, store) {
				//console.log(value);
				//metadata.css = "arv-cblc";
				// if (value === 0) {
				//
				// return "0";
				// }
				// else if (value === "") {
				// return "";
				// } else
				return Ext.util.Format.number(value, '0,000');
			},
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 999999999,
				selectOnFocus : true
				// ,
				// parseValue: function(value){
				// //return isNaN(value) ? null : value;
				// if (value == null)
				// return "";
				// }
			})
		}, {
			header : "Last<br/>Month Dispensed",
			width : 70,
			align : 'right',
			dataIndex : 'lastMonthDispensed',
			sortable : false,
			hidden : true
		}, {
			header : "Before Last<br/>Month Dispensed",
			width : 70,
			align : 'right',
			dataIndex : 'BeforeLastMonthDispensed',
			sortable : false,
			hidden : true
		}, {
			header : TEXT["Adjusted"] + "<br/>(&#177;D)",
			tooltip : TEXT['Adjusted Quantity'] + " -> (&#177;D)",
			width : 70,
			align : 'right',
			dataIndex : 'AdjustQty',
			sortable : false,
			hidden : false,
			//renderer : render_val,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : true,
				maxValue : 999999999,
				selectOnFocus : true,
				listeners : {
					blur : function(base, eOpts) {
					}
				}
			})
		}, {
			header : TEXT["Adjust"] + "<br/>" + TEXT["Reason"],
			tooltip : TEXT["Adjusting Reason"],
			width : 100,
			align : 'left',
			dataIndex : 'AdjustId',
			sortable : false,
			hidden : false,
			editor : cboAdjustReason,
			renderer : function(value, meta, record) {
				if (value != undefined)
					record.set('AdjustReason', value);
				var sAdjReason = record.get('AdjustReason');
				var index;
				var displayValue;
				index = dsAdjustReason.find('AdjustId', sAdjReason);
				if (index != -1) {
					displayValue = dsAdjustReason.getAt(index).data.AdjustReason;
				}
				return (displayValue == 'None' ? "" : displayValue) || "";
			}
		}, {
			header : TEXT["Stock Out"] + "<br/>" + TEXT["Days"],
			tooltip : TEXT["Stock Out Days"],
			width : 70,
			align : 'right',
			dataIndex : 'StockoutDays',
			sortable : false,
			hidden : false,
			renderer : render_val,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				selectOnFocus : true
			})
		}, {
			header : TEXT['Stock Out Reason'],
			tooltip : TEXT['Stock Out Reason'],
			width : 100,
			align : 'left',
			dataIndex : 'StockOutReasonId',
			sortable : false,
			hidden : false,
			editor : cboStockOutReason,
			renderer : function(value, meta, record) {
				if (value != undefined)
					record.set('StockOutReasonId', value);
				var oStockOutReasonId = record.get('StockOutReasonId');
				var index;
				var displayValue;
				index = dsStockOutReason.find('StockOutReasonId', oStockOutReasonId);
				if (index != -1) {
					displayValue = dsStockOutReason.getAt(index).data.StockOutReasonName;
				}
				return (displayValue == 'None' ? "" : displayValue) || "";
			}
		}, {
			header : TEXT['CBL(c)'] + "<br/>(F=A+C+D+E)",
			tooltip : TEXT['Closing Balance Calculated'],
			width : 70,
			align : 'right',
			dataIndex : 'ClStock_C',
			sortable : false,
			hidden : true,
			renderer : function(value, metadata, record, rowIndex, colIndex, store) {

				metadata.css = "arv-cblc";
				if (value == 0) {
					return "";
				} else
					return Ext.util.Format.number(value, '0,000');
			}
		}, {
			header : TEXT["Closing Balance"] + "<br/>(E)",
			tooltip : TEXT['Closing Balance'] + " -> (E)",
			width : 70,
			align : 'right',
			dataIndex : 'ClStock_A',
			sortable : false,
			hidden : false,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 2999999997,
				selectOnFocus : true,
				listeners : {
					// 'hide' : function() {
					// console.log('hell');
					// },
					// 'focus' : function() {
					// alert('I AM FOCUS');
					// return;
					// },
					'blur' : function() {
						//alert(gClosingAlert);
						//Ext.Msg.alert('Status', 'Changes saved successfully.');
						//return;
					}
				}
			})
		}, {
			header : TEXT["CL Stock"] + "<br/>" + TEXT["Source"],
			tooltip : TEXT['Closing balance stock source'],
			width : 100,
			align : 'left',
			dataIndex : 'ClStockSourceId',
			sortable : false,
			hidden : true,
			editor : cboClStockSource,
			renderer : function(value, meta, record) {
				if (value != undefined)
					record.set('ClStockSourceId', value);
				var sAdjReason = record.get('ClStockSourceId');
				var index;
				var displayValue;
				index = dsClStockSource.find('ClStockSourceId', sAdjReason);
				if (index != -1) {
					displayValue = dsClStockSource.getAt(index).data.SourceName;
				}
				return (displayValue == 'None' ? "" : displayValue) || "";
			}
		}, {
			header : TEXT["AMC Calculated"] + "<br/>(F)",
			tooltip : TEXT['Average Monthly Calculation (AMC_C) Calculated'] + " -> (F=(P2C+C)/3)",
			width : 70,
			align : 'right',
			dataIndex : 'AMC_C',
			sortable : false,
			hidden : true,
			renderer : function(value, metadata, record, rowIndex, colIndex, store) {
				return Ext.util.Format.number(value, '0,000');
			},
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 8999999991,
				selectOnFocus : true,
				listeners : {
					blur : function(base, eOpts) {
					}
				}
			})

		}, {
			header : TEXT["AMC"] + "<br/>(F)",
			tooltip : TEXT['Average Monthly Calculation'] + " (AMC) -> (F=(P2C+C)/3)",
			width : 70,
			align : 'right',
			dataIndex : 'AMC',
			sortable : false,
			hidden : false,
			// renderer  : function(value, metadata, record, rowIndex, colIndex, store) {
			// ((record.data.OpStock_A + record.data.ReceiveQty + record.data.AdjustQty - record.data.ClStock_A) != record.data.DispenseQty)
			//
			// metadata.css = "arv-amc";
			// if( value == 0 ) {
			// return "";
			// } else
			// return Ext.util.Format.number(value, '0,000');
			// }
			//renderer : render_val,
			renderer : function(value, metadata, record, rowIndex, colIndex, store) {

				// if (record.data.OpStock_A === '' && record.data.ReceiveQty === '' && record.data.AdjustQty === '' && record.data.ClStock_A === '' &&  record.data.DispenseQty === '') {
				// alert('empty');
				// return "";
				// } else
				return Ext.util.Format.number(value, '0,000');
			},
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 8999999991,
				selectOnFocus : true,
				listeners : {
					blur : function(base, eOpts) {
					}
				}
			})

		}, {
			header : TEXT["AMC Change"] + "<br/>" + TEXT["Reason"],
			tooltip : TEXT['AMC change reason'],
			width : 100,
			align : 'left',
			dataIndex : 'AmcChangeReasonId',
			sortable : false,
			hidden : true,
			editor : cboAmcChangeReason,
			renderer : function(value, meta, record) {
				if (value != undefined)
					record.set('AmcChangeReasonId', value);
				var sAdjReason = record.get('AmcChangeReasonId');
				var index;
				var displayValue;
				index = dsAmcChangeReason.find('AmcChangeReasonId', sAdjReason);
				if (index != -1) {
					displayValue = dsAmcChangeReason.getAt(index).data.AmcChangeReasonName;
				}
				return (displayValue == 'None' ? "" : displayValue) || "";
			}
		}, {
			header : TEXT["MOS"] + "<br/>(G)",
			tooltip : TEXT['Month Of Supply (MOS)'] + " -> (G=E/F)",
			width : 70,
			align : 'right',
			dataIndex : 'MOS',
			sortable : false,
			hidden : false,
			renderer : function(value, metadata, record, rowIndex, colIndex, store) {

				metadata.css = "arv-mos";
				// if (value == 0) {
				// return "";
				// } else
				return Ext.util.Format.number(value, '0.0');

			}
		}, {
			header : TEXT['Max Qty'] + " <br/>(H)",
			tooltip : TEXT['Maximum Quantity'] + " -> (H=F*3)",
			width : 70,
			align : 'right',
			dataIndex : 'MaxQty',
			sortable : false,
			hidden : false,
			renderer : function(value, metadata, record, rowIndex, colIndex, store) {

				metadata.css = "arv-maxqty";
				// if (value == 0) {
				// return "";
				// } else
				return Ext.util.Format.number(value, '0,000');
			}
		}, {
			header : TEXT["Order Qty"] + " <br/>(I)",
			tooltip : TEXT["Order Quantity"] + " -> (I=H-E)",
			width : 70,
			align : 'right',
			dataIndex : 'OrderQty',
			sortable : false,
			hidden : false,
			renderer : function(value, metadata, record, rowIndex, colIndex, store) {

				metadata.css = "arv-orderqty";
				if (value < 0) {
					return "";
				} else
					return Ext.util.Format.number(value, '0,000');
			}
		}, {
			header : TEXT["Actual Order"] + " <br/>" + TEXT['Qty'] + "(J)",
			tooltip : TEXT['Actual Order Quantity'] + " -> (J=H-E&#177;X)",
			width : 70,
			align : 'right',
			dataIndex : 'ActualQty',
			sortable : false,
			hidden : false,
			renderer : function(value, metadata, record, rowIndex, colIndex, store) {

				if (value < 0) {
					return "";
				} else
					return Ext.util.Format.number(value, '0,000');
			},
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : true,
				maxValue : 8999999991,
				selectOnFocus : true
			})
		}, {
			header : TEXT["Order Qty"] + "<br/>" + TEXT["Update Reason"],
			tooltip : TEXT["Order quantity change reason"],
			width : 100,
			align : 'left',
			dataIndex : 'OUReasonId',
			sortable : false,
			hidden : false,
			editor : cboOrderQtyChangeReason,
			renderer : function(value, meta, record) {
				if (value != undefined)
					record.set('OUReasonId', value);
				var iOUReasonId = record.get('OUReasonId');
				//if (iOUReasonId>0)
				//console.log(iOUReasonId);
				var index;
				var displayValue;
				index = dsOrderQtyChangeReason.find('OUReasonId', iOUReasonId);
				if (index != -1) {
					displayValue = dsOrderQtyChangeReason.getAt(index).data.OUReason;
				}
				return (displayValue == 'None' ? "" : displayValue) || "";
			}
		}, {
			header : "UserId",
			width : 70,
			align : 'left',
			dataIndex : 'UserId',
			sortable : false,
			hidden : true,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 999999999,
			})
		}, {
			header : TEXT["Formulation"],
			width : 70,
			align : 'left',
			dataIndex : 'FormulationName',
			sortable : false,
			hidden : true
		}],
		viewConfig : {
			forceFit : true,
			emptyText : 'No rows to display',
			getRowClass : function(record, rowIndex) {
				if ((isNorE(record.data.OpStock_A) + isNorE(record.data.ReceiveQty) + isNorE(record.data.AdjustQty) - isNorE(record.data.ClStock_A)) != isNorE(record.data.DispenseQty)) {
					return 'clsinvalid';
				}
			}
		},
		width : '100%',
		// buttons : [{
		// text : TEXT['Save'],
		// icon : baseUrl + 'images/save_soft.png',
		// handler : saveAllRecords
		// }],
		buttonAlign : 'right',
		autoHeight : true,
		align : 'center',
		columnLines : true,
		bbar : new Ext.PagingToolbar({
			pageSize : 20,
			store : dsArvData,
			displayInfo : true,
			displayMsg : TEXT['Displaying'] + '  {0} - {1} ' + TEXT['of'] + ' {2}',
			emptyMsg : TEXT["No Records found"],
			items : []
		})
	});

	gridArvData.getSelectionModel().on("rowdeselect", saveGridRow);

	function saveGridRow(selModel, rowIndex, record) {
		gridArvData.stopEditing();
		var ed = gridArvData.activeEditor;
		//console.log(ed);
		//alert('rowdeselect');
		var msgboxpos;
		var msgboxpos2;
		var vMsg = '';
		var gposition = gridArvData.getPosition();

		if (record.dirty) {
			opStock_A = isNorE(record.data.OpStock_A);
			//
			receiveQty = isNorE(record.data.ReceiveQty);
			// == "" ? 0 : record.data.ReceiveQty;
			adjustQty = isNorE(record.data.AdjustQty);
			// == "" ? 0 : record.data.AdjustQty;
			clStock_A = isNorE(record.data.ClStock_A);
			dispenseQty = isNorE(record.data.DispenseQty);
			// == "" ? 0 : record.data.DispenseQty;
			adjustReason = record.data.AdjustReason == "" ? 0 : record.data.AdjustReason;
			stockOutDays = isNorE(record.data.StockoutDays);
			// == "" ? 0 : record.data.StockoutDays;
			stockOutReason = record.data.StockOutReasonId == "" ? 0 : record.data.StockOutReasonId;
			amc = isNorE(record.data.AMC);
			maxQty = isNorE(record.data.MaxQty);
			orderQty = isNorE(record.data.OrderQty);
			actualQty = isNorE(record.data.ActualQty);
			oUReasonId = record.data.OUReasonId == "" ? 0 : record.data.OUReasonId;

			if (adjustQty == 0 && adjustReason != '0') {
				msgboxpos = Ext.Msg.show({
					title : 'Data validation',
					msg : "Please fill adjust quantity first, then set the reason.",
					icon : Ext.Msg.QUESTION,
					buttons : Ext.Msg.OK,
					fn : function(response) {
						gridArvData.getSelectionModel().selectRow(rIndex);
						gridArvData.startEditing(rIndex, cIndex);
					},
					scope : this
				}).getDialog();
				ed.completeEdit();
				return;
			}

			//alert(adjustQty);

			if (adjustQty > 0 && adjustReason == '0') {
				msgboxpos = Ext.Msg.show({
					title : 'Data validation',
					msg : "You must select adjust reason.",
					icon : Ext.Msg.QUESTION,
					buttons : Ext.Msg.OK,
					fn : function(response) {
						gridArvData.getSelectionModel().selectRow(rIndex);
						gridArvData.startEditing(rIndex, cIndex);
					},
					scope : this
				}).getDialog();
				ed.completeEdit();
				return;
			}

			if (parseInt(stockOutDays) == 0 && stockOutReason != '0') {
				msgboxpos2 = Ext.Msg.show({
					title : 'Data validation',
					msg : "You must fill stockout days.",
					icon : Ext.Msg.QUESTION,
					buttons : Ext.Msg.OK,
					fn : function(response) {
						gridArvData.getSelectionModel().selectRow(rIndex);
						gridArvData.startEditing(rIndex, cIndex);
					},
					scope : this
				}).getDialog();
				ed.completeEdit();
				return;
			}

			if (parseInt(stockOutDays) > 0)
				if (!isValidDays(record.data.Year, record.data.MonthId, parseInt(stockOutDays))) {
					msgboxpos = Ext.Msg.show({
						title : 'Data validation',
						msg : "You must select a vaild number of days.",
						icon : Ext.Msg.QUESTION,
						buttons : Ext.Msg.OK,
						fn : function(response) {
							gridArvData.getSelectionModel().selectRow(rIndex);
							gridArvData.startEditing(rIndex, cIndex);
						},
						scope : this
					}).getDialog();
					ed.completeEdit();
					return;
				}

			if (parseInt(stockOutDays) > 0 && stockOutReason == '0') {
				msgboxpos2 = Ext.Msg.show({
					title : 'Data validation',
					msg : "You must select a stockout reason.",
					icon : Ext.Msg.QUESTION,
					buttons : Ext.Msg.OK,
					fn : function(response) {
						gridArvData.getSelectionModel().selectRow(rIndex);
						gridArvData.startEditing(rIndex, cIndex);
					},
					scope : this
				}).getDialog();
				ed.completeEdit();
				return;
			}

			//alert(clStock_A);

			if (record.data.ClStock_A === '') {
				//alert(receiveQty );
				msgboxpos = Ext.Msg.show({
					title : TEXT['Data validation'],
					msg : 'You must enter closing balance at least 0 or more',
					icon : Ext.Msg.QUESTION,
					buttons : Ext.Msg.OK,
					fn : function(response) {
						gridArvData.getSelectionModel().selectRow(rIndex);
						gridArvData.startEditing(rIndex, cIndex);
					}
				}).getDialog();
				ed.completeEdit();
				return;
			}

			// if ((opStock_A + receiveQty + adjustQty - clStock_A) != dispenseQty) {
			// //alert(receiveQty );
			// msgboxpos = Ext.Msg.show({
			// title : TEXT['Data validation'],
			// msg : TEXT['Msg_Validation'] + (opStock_A + receiveQty + adjustQty - dispenseQty),
			// icon : Ext.Msg.QUESTION,
			// buttons : Ext.Msg.OK
			// ,
			// fn : function(response) {
			// ed.completeEdit();
			// //gridArvData.getSelectionModel().selectRow(r+1);
			// //gridArvData.startEditing(r+1, c);
			// }
			// }).getDialog();
			// //ed.completeEdit();
			// //return;
			// }

			// var minActQty = parseInt(amc * minActQtyFactor);
			// var maxActQty = parseInt(amc * maxActQtyFactor);

			// //alert(minActQty);
			//
			// //if ((actualQty < minActQty || actualQty > maxActQty) && (  maxQty > 0 && maxActQty < maxQty) ) {
			// //if ((actualQty < minActQty || actualQty > maxActQty) && (clStock_A < minActQty)) {
			// if (actualQty < minActQty || actualQty > maxActQty) {
			// //if (orderQty != actualQty) {
			// msgboxpos = Ext.Msg.show({
			// title : 'Data validation',
			// msg : "Order Qty should be within Min(" + minActQty + ") and Max(" + maxActQty + ") range.",
			// icon : Ext.Msg.QUESTION,
			// buttons : Ext.Msg.OK,
			// fn : function(response) {
			// gridArvData.getSelectionModel().selectRow(rIndex);
			// gridArvData.startEditing(rIndex, cIndex);
			// },
			// scope : this
			// }).getDialog();
			// ed.completeEdit();
			// return;
			// }

			// if (orderQty != actualQty) {
			// msgboxpos = Ext.Msg.show({
			// title : 'Data validation',
			// msg : "Quantity to Order should be equal to " + orderQty + " ( Max Quantity - Stock on Hand ).",
			// icon : Ext.Msg.QUESTION,
			// buttons : Ext.Msg.OK,
			// fn : function(response) {
			// gridArvData.getSelectionModel().selectRow(rIndex);
			// gridArvData.startEditing(rIndex, cIndex);
			// },
			// scope : this
			// }).getDialog();
			// ed.completeEdit();
			// return;
			// }

			//if (actualQty != orderQty && oUReasonId == '0' && maxActQty < maxQty ) {
			if (actualQty != orderQty && oUReasonId == '0') {
				msgboxpos = Ext.Msg.show({
					title : 'Data validation',
					msg : "Quantity to Order should be equal to " + orderQty + "<br/>( Max Quantity - Stock on Hand ).<br/>You must select order quantity update reason.",
					icon : Ext.Msg.QUESTION,
					buttons : Ext.Msg.OK,
					fn : function(response) {
						gridArvData.getSelectionModel().selectRow(rIndex);
						gridArvData.startEditing(rIndex, cIndex);
					},
					scope : this
				}).getDialog();
				ed.completeEdit();
				return;
			}

			if (actualQty == orderQty && oUReasonId > 0) {
				msgboxpos = Ext.Msg.show({
					title : 'Data validation',
					msg : "Order Qty and Actual Qty are the same, Please select None in Order Qty update reason.",
					icon : Ext.Msg.QUESTION,
					buttons : Ext.Msg.OK,
					fn : function(response) {
						gridArvData.getSelectionModel().selectRow(rIndex);
						gridArvData.startEditing(rIndex, cIndex);
					},
					scope : this
				}).getDialog();
				ed.completeEdit();
				return;
			}

			// if ((opStock_A + receiveQty + adjustQty - clStock_A) != dispenseQty) {
			// //alert(receiveQty );
			// msgboxpos = Ext.Msg.show({
			// title : TEXT['Data validation'],
			// msg : TEXT['Msg_Validation'] + (opStock_A + receiveQty + adjustQty - dispenseQty),
			// icon : Ext.Msg.QUESTION,
			// buttons : Ext.Msg.OK,
			// fn : function(response) {
			// gridArvData.getSelectionModel().selectRow(rIndex);
			// gridArvData.startEditing(rIndex, cIndex);
			// },
			// scope : this
			// }).getDialog();
			// ed.completeEdit();
			//
			// } else {

			//alert(vOpStock_C);
			Ext.Ajax.request({
				waitMsg : 'Please wait...',
				url : baseUrl + 't_facility_level_monthlystatus_ext_server.php',
				params : {
					action : "updateStockData",
					pARVDataId : vARVDataId,
					pOpStock_C : record.data.OpStock_A, //vOpStock_C,
					pOpStock_A : record.data.OpStock_A,
					pReceiveQty : record.data.ReceiveQty,
					pDispenseQty : record.data.DispenseQty,
					pAdjustQty : record.data.AdjustQty,
					pAdjustReason : vAdjustReason,
					pStockoutDays : record.data.StockoutDays,
					pStockOutReasonId : vStockOutReasonId,
					pOrderQty : record.data.OrderQty,
					pMOS : record.data.MOS,
					pAMC_C : record.data.AMC_C,
					pAMC : record.data.AMC,
					pAmcChangeReasonId : vAmcChangeReasonId,
					pClStock_C : record.data.ClStock_C,
					pClStock_A : record.data.ClStock_A,
					pClStockSourceId : vClStockSourceId,
					pMaxQty : record.data.MaxQty,
					pOrderQty : record.data.OrderQty,
					pActualQty : record.data.ActualQty,
					pOUReasonId : vOUReasonId,
					pFacilityId : pFacilityId,
					pMonthId : pMonthId,
					pYearId : pYearId,
					pReportId : pReportId,
					pUserId : pUserId,
					'lang' : lan
				},
				success : function(response) {
					eval(response.responseText);
					switch(success) {
					case 1:

						break;
					case 0:
						Ext.Msg.show({
							msg : error,
							icon : Ext.Msg.INFO,
							minWidth : 300,
							buttons : Ext.Msg.OK,
							scope : this
						});
						break;
					default:
						Ext.Msg.show({
							msg : response.responseText,
							icon : Ext.Msg.INFO,
							minWidth : 300,
							buttons : Ext.Msg.OK,
							scope : this
						});
						break;
					}
				}
			});
			record.commit();
			gridArvData.getView().refresh();
			rIndex = -99;
			//}
		}
	}


	gridArvData.on('afteredit', saveTheCell);

	//function to make EditorGridPanel read only
	gridArvData.on('beforeedit', handler_to_makeReadOnly);

	Ext.EventManager.on(window, 'beforeunload', function() {
	});

	tabArvdata = new Ext.TabPanel({
		id : 'tabArvdataId',
		activeTab : 0,
		width : '100%',
		//height:'100%',
		autoHeight : true,
		frame : true,
		region : 'center',
		defaults : {
			autoScroll : true
		},
		closable : true,
		resizeTabs : true,
		minTabWidth : 170,
		bodyStyle : 'padding:0px; margin:0px; border:0px; background-color:#ffffff;',
		enableTabScroll : true,
		items : [{
			title : TEXT['Stock Information'],
			disabled : false,
			id : 'tabItemArvData',
			width : '100%',
			autoHeight : true,
			bodyStyle : 'padding:0px; margin:0px; border:0px; background-color:#DFE8F6;',
			items : [gridArvData]
		}, {
			title : TEXT['Patient Overview'],
			id : "tabItemPatientOverviewId",
			width : '100%',
			autoHeight : true,
			hideMode : 'display',
			style : {
				display : 'block'
			},
			bodyStyle : 'padding:0px; margin:0px; border:0px; background-color:#DFE8F6;',
			items : [gridPatientOverview]
		}, {
			title : TEXT['Patient by Regimen'],
			disabled : false,
			id : 'tabItemAdultRegimens',
			width : '100%',
			autoHeight : true,
			bodyStyle : 'padding:0px; margin:0px; border:0px; background-color:#DFE8F6;',
			items : [gridAdultRegimens]
		}],
		listeners : {
			tabchange : function(panel, tab) {
				selectedTab = panel.getActiveTab().id;
				if (selectedTab == 'tabItemArvData')
					gridArvData.getView().refresh();
				else if (selectedTab == 'tabItemPatientOverviewId')
					gridPatientOverview.getView().refresh();
				else if (selectedTab == 'tabItemAdultRegimens')
					gridAdultRegimens.getView().refresh();
			}
		}
	});

	tbarMaster = new Ext.Toolbar({
		items : [{
			xtype : 'label',
			text : 'Report Id:'
		}, {
			xtype : 'label',
			id : 'lblReportId'
		}, '-', '-', {
			xtype : 'label',
			text : 'Created By:'
		}, {
			xtype : 'label',
			id : 'lblCreatedBy'
		}, '-', '-', {
			xtype : 'label',
			text : 'Created Date:'
		}, {
			xtype : 'label',
			id : 'lblCreatedDt'
		}, '-', '-', {
			xtype : 'label',
			text : 'Changed By:'
		}, {
			xtype : 'label',
			id : 'lblLastUpdateBy'
		}, '-', '-', {
			xtype : 'label',
			text : 'Changed Date:'
		}, {
			xtype : 'label',
			id : 'lblLastUpdateDt'
		}, '-', '-', {
			xtype : 'label',
			text : 'Submitted By:'
		}, {
			xtype : 'label',
			id : 'lblLastSubmittedBy'
		}, '-', '-', {
			xtype : 'label',
			text : 'Submitted Date:'
		}, {
			xtype : 'label',
			id : 'lblLastSubmittedDt'
		}]

	});

	tbar2 = new Ext.Toolbar({
		items : [{
			xtype : 'label',
			text : 'Country:'
		}, {
			xtype : 'tbspacer',
			width : 10
		}, cboCountry, {
			xtype : 'tbspacer',
			width : 50
		}, {
			xtype : 'label',
			text : 'Product Group:'
		}, {
			xtype : 'tbspacer',
			width : 10
		}, cboItemGroup]
	});

	function showData() {
		vMonthId = Ext.getCmp('cboMonthId').getValue();
		vYearId = Ext.getCmp('cboYearId').getValue();

		//getOpeningEditable();

		vMonth = Ext.getCmp('cboMonthId').getRawValue();
		vYear = Ext.getCmp('cboYearId').getRawValue();

		if (vMonthId == "" || vYearId == "") {
			var msgbox = Ext.Msg.show({
				msg : 'You must select facility, month and year.',
				icon : Ext.Msg.INFO,
				minWidth : 200,
				buttons : Ext.Msg.OK,
				scope : this
			}).getDialog();
			return;
		}

		selYearId = parseInt(vYearId);
		selMonth = parseInt(vMonthId);

		selInitYearId = parseInt(initYearId);
		selInitMonthId = parseInt(initMonthId);

		if (selYearId < selInitYearId) {
			var msgbox = Ext.Msg.show({
				msg : 'Starting Month-Year for ARV Data is ' + startManthName + '-' + initYearId + '',
				icon : Ext.Msg.INFO,
				minWidth : 200,
				buttons : Ext.Msg.OK,
				scope : this
			}).getDialog();
			return;
		} else if (selYearId == selInitYearId && selMonth < selInitMonthId) {
			var msgbox = Ext.Msg.show({
				msg : 'Starting Month-Year for ARV Data is ' + startManthName + '-' + initYearId + '',
				icon : Ext.Msg.INFO,
				minWidth : 200,
				buttons : Ext.Msg.OK,
				scope : this
			}).getDialog();
			return;
		} else if (selYearId == selInitYearId && selMonth == selInitMonthId) {
			loadMasterStockData();
		} else {
			getFacilityRecordOfPrevMonth();
		}
	}

	tbar = new Ext.Toolbar({
		items : [{
			//text : 'Show/Hide Facility',
			tooltip : TEXT['Show/Hide Facility'],
			enableToggle : true,
			icon : baseUrl + 'images/show-hide.png',
			toggleHandler : showHideFacility
		}, '-', '-', {
			xtype : 'label',
			text : TEXT['Country'] + ':'
		}, cboCountry, '-', '-', {
			xtype : 'label',
			text : TEXT['Region'] + ':'
		}, cboRegion, '-', '-', {
			xtype : 'label',
			text : TEXT['District'] + ':'
		}, cboDistrict, '-', '-', {
			xtype : 'label',
			text : TEXT['Owner'] + ':'
		}, cboOwnerType, '-', '-', /*{
		 xtype : 'label',
		 text : TEXT['Product Group'] + ':'
		 }, , cboItemGroup, '-', '-', */
		{
			xtype : 'label',
			text : TEXT['Month'] + ':'
		}, {

			xtype : 'combo',
			hiddenName : 'hCboMonthId',
			id : 'cboMonthId',
			emptyText : TEXT['Select Month...'],
			fieldLabel : TEXT['Month'],
			mode : 'local',
			store : dsMonth,
			allowBlank : true,
			selectOnFocus : true,
			displayField : 'MonthName',
			valueField : 'MonthId',
			triggerAction : 'all',
			width : 100,

			listeners : {
				select : function(field, rec, selIndex) {
					pMonthId = rec.get('MonthId');
					pMonthName = rec.get('MonthName');
					currentDate.setMonth(pMonthId - 1);
					dsFacility.setBaseParam('pMonthId', pMonthId);
					setBaseParams('pMonthId', pMonthId);
					loadFacility();
				}
			}
		}, '-', '-', {
			xtype : 'label',
			text : TEXT['Year'] + ':'
		}, {
			xtype : 'combo',
			id : 'cboYearId',
			hiddenName : 'hCboYearId',
			mode : 'local',
			triggerAction : 'all',
			emptyText : TEXT['Select Year...'],
			fieldLabel : TEXT['Year'],
			displayField : 'YearName',
			valueField : 'YearId',
			anchor : '95%',
			allowBlank : true,
			selectOnFocus : true,
			store : dsYear,
			width : 100,
			listeners : {
				select : function(field, rec, selIndex) {
					pYearId = rec.get('YearId');
					currentDate.setYear(pYearId);
					dsFacility.setBaseParam('pYearId', pYearId);
					setBaseParams('pYearId', pYearId);
					loadFacility();
				}
			}
		}, '-', '-',
		// {
		// text : TEXT['Save'],
		// icon : baseUrl + 'images/save_soft.png',
		// handler : saveAllRecords
		// },

		{
			text : TEXT['Print'],
			icon : baseUrl + 'images/print-button.png',
			handler : printHtml
		}, '-', '-', {
			text : TEXT['Excel'],
			icon : baseUrl + 'images/excel-button.png',
			handler : printExcel
		}, '-', '-', {
			text : TEXT['PDF'],
			icon : baseUrl + 'images/pdf-button.png',
			handler : printPdf
		}, '-', '-', {
			tooltip : TEXT['Unpublish'],
			icon : baseUrl + 'images/un-publish.png',
			handler : makeUnpublished
		}]

	});

	function printHtml() {

		if (!pReportId > 0) {
			alert("Please select a valid report to print");
			return;
		}
<<<<<<< .mine
		window.open(baseUrl + "report/t_facility_data_entry_print.php?jBaseUrl=" + jBaseUrl + "&CFMStockId=" + pReportId + "&CountryId=" + pCountryId + "&RegionId=" + pRegionId + "&DistrictId=" + pDistrictId + "&OwnerTypeId=" + pOwnerTypeId + "&MonthId=" + pMonthId + "&MonthName=" + pMonthName + "&YearId=" + pYearId + "&FacilityId=" + pFacilityId + "&lan=" + lan);
=======
		window.open(baseUrl + "report/t_facility_data_entry_print.php?jBaseUrl=" + jBaseUrl + "&CFMStockId=" + pReportId + "&CountryId=" + pCountryId + "&RegionId=" + pRegionId + "&DistrictId=" + pDistrictId + "&OwnerTypeId=" + pOwnerTypeId + "&MonthId=" + pMonthId + "&YearId=" + pYearId + "&FacilityId=" + pFacilityId + "&MonthName=" + pMonthName + "&lan=" + lan);
>>>>>>> .r184
	}

	function printExcel() {

		if (!pReportId > 0) {
			alert("Please select a valid report to print");
			return;
		}
		window.open(baseUrl + "report/t_facility_data_entry_excel.php?jBaseUrl=" + jBaseUrl + "&CFMStockId=" + pReportId + "&CountryId=" + pCountryId + "&RegionId=" + pRegionId + "&DistrictId=" + pDistrictId + "&OwnerTypeId=" + pOwnerTypeId + "&MonthId=" + pMonthId + "&YearId=" + pYearId + "&FacilityId=" + pFacilityId + "&MonthName=" + pMonthName + "&lan=" + lan);
	}

	function printPdf() {
		if (!pReportId > 0) {
			alert("Please select a valid report to print");
			return;
		}

		$.ajax({
			url : baseUrl + 'report/t_facility_data_entry_pdf.php',
			type : 'post',
			data : {
				"action" : 'FacilityDataEntryReportPDF',
				"CFMStockId" : pReportId,
				"CountryId" : pCountryId,
				"RegionId" : pRegionId,
				"DistrictId" : pDistrictId,
				"OwnerTypeId" : pOwnerTypeId,
				"MonthId" : pMonthId,
				"MonthName" : pMonthName,
				"YearId" : pYearId,
				"FacilityId" : pFacilityId,
				"lan" : lan

			},
			success : function(response) {
				if (response == 'Processing Error') {
					alert('No Record Found.');
				} else {
					window.open(baseUrl + 'report/pdfslice/' + response);
				}
			}
		});
	}

	function showHideFacility() {
		var eGridFacility = Ext.get('grid-facility');
		var eTabArvdata = Ext.get('tabArvdata');
		eGridFacility.setVisibilityMode(Ext.Element.DISPLAY);
		eTabArvdata.setVisibilityMode(Ext.Element.DISPLAY);
		if (this.pressed) {
			// $('#grid-facility').hide();
			// $('#tabArvdata').css('width','100%');
			// gridArvData.getView().refresh();

			eGridFacility.hide();
			eTabArvdata.setStyle('width', '100%');

		} else {
			// $('#grid-facility').show();
			// $('#tabArvdata').css('width','80%');
			// gridArvData.getView().refresh();

			eGridFacility.show();
			eTabArvdata.setStyle('width', '80%');

		}

		tabArvdata.doLayout();

		var eTabItemArvData = Ext.getCmp('tabItemArvData');
		eTabItemArvData.doLayout();
		var eTabItemPatientOverviewId = Ext.getCmp('tabItemPatientOverviewId');
		eTabItemPatientOverviewId.doLayout();
		var eTabItemAdultRegimens = Ext.getCmp('tabItemAdultRegimens');
		eTabItemAdultRegimens.doLayout();

		if (selectedTab == 'tabItemArvData')
			gridArvData.getView().refresh();
		else if (selectedTab == 'tabItemPatientOverviewId')
			gridPatientOverview.getView().refresh();
		else if (selectedTab == 'tabItemAdultRegimens')
			gridAdultRegimens.getView().refresh();
	}

	function makeUnpublished() {

		if (pReportId <= 0) {
			alert('Please select a report first.');
			return;
		}

		if (pStatusId == 1)
			pStatusId = 2;
		else if (pStatusId == 2)
			pStatusId = 3;
		else if (pStatusId == 3)
			pStatusId = 5;

		var msgbox = Ext.Msg.show({
			msg : TEXT['Msg_Unpublish'],
			icon : Ext.Msg.QUESTION,
			minWidth : 200,
			buttons : Ext.Msg.YESNO,
			scope : this,
			fn : function(response) {
				if ('yes' == response) {
					Ext.Ajax.request({
						waitMsg : 'Please wait...',
						url : baseUrl + 't_facility_level_monthlystatus_ext_server.php',
						params : {
							action : "makeUnpublished",
							'pFacilityId' : pFacilityId,
							'pOwnerTypeId' : pOwnerTypeId,
							'pMonthId' : pMonthId,
							'pYearId' : pYearId,
							'pReportId' : pReportId,
							'pUserId' : pUserId,
							'pCountryId' : pCountryId,
							'pItemGroupId' : pItemGroupId,
							'pStatusId' : pStatusId,
							'lang' : lan

						},
						success : function(response) {
							loadMasterStockData();
							loadAllData();
							loadFacility();

							var msgbox = Ext.Msg.show({
								msg : TEXT['Your report unpublished successfully'],
								icon : Ext.Msg.INFO,
								minWidth : 200,
								buttons : Ext.Msg.OK,
								scope : this
							}).getDialog();
						}
					});
				}
			}
		}).getDialog();
	}

	function getFacilityRecordOfPrevMonth() {
		Ext.Ajax.request({
			waitMsg : 'Please wait...',
			url : baseUrl + 't_facility_level_monthlystatus_ext_server.php',
			params : {
				'action' : "getFacilityRecordOfPrevMonth",
				'pFacilityId' : pFacilityId,
				'pMonthId' : pMonthId,
				'pYearId' : pYearId,
				'pCountryId' : pCountryId,
				'pItemGroupId' : pItemGroupId,
				'lang' : lan

			},
			success : function(response) {
				eval(response.responseText);

				//alert(selYearId);

				if (selYearId == selInitYearId && selMonth == selInitMonthId) {
					vMonth = Ext.getCmp('cboMonthId').getRawValue();
					vYear = Ext.getCmp('cboYearId').getRawValue();

					var msgbox = Ext.Msg.show({
						msg : 'You (' + vFacility + ') don\'t have the report on ' + vMonth + '-' + vYear + ', Do you want to create report?',
						icon : Ext.Msg.QUESTION,
						minWidth : 200,
						buttons : Ext.Msg.YESNO,
						scope : this,
						fn : function(response) {
							if ('yes' == response) {
								insertIntoStockData();
							}
						}
					}).getDialog();
				} else if (prev_month_report <= 0) {
					var msgbox = Ext.Msg.show({
						msg : TEXT['Msg_NoReport'],
						icon : Ext.Msg.QUESTION,
						minWidth : 200,
						buttons : Ext.Msg.OK,
						scope : this
					}).getDialog();
					return;
				} else if (prev_month_report_unpub > 0) {
					var msgbox = Ext.Msg.show({
						msg : TEXT['Msg_UnPublished'],
						icon : Ext.Msg.QUESTION,
						minWidth : 200,
						buttons : Ext.Msg.OK,
						scope : this
					}).getDialog();
					return;
				} else {
					vMonth = Ext.getCmp('cboMonthId').getRawValue();
					vYear = Ext.getCmp('cboYearId').getRawValue();

					var msgbox = Ext.Msg.show({
						msg : 'You (' + vFacility + ') don\'t have the report on ' + vMonth + '-' + vYear + ', Do you want to create report?',
						icon : Ext.Msg.QUESTION,
						minWidth : 200,
						buttons : Ext.Msg.YESNO,
						scope : this,
						fn : function(response) {
							if ('yes' == response) {
								insertIntoStockData();
							}
						}
					}).getDialog();
				}

			}
		});
	}

	function saveAllRecords() {
		var gposition = gridArvData.getPosition();
		gridArvData.stopEditing();
		var data = [];
		invalidMsgCount = 0;
		Ext.each(gridArvData.getStore().getModifiedRecords(), function(record) {

			if ((record.data.OpStock_A + record.data.ReceiveQty + record.data.AdjustQty - record.data.ClStock_A) != record.data.DispenseQty) {
				invalidMsgCount++;
			} else
				data.push(record.data);
			if (invalidMsgCount > 0) {
				var msgboxpos = Ext.Msg.show({
					title : 'Data validation',
					msg : "Opening + Received + AdjustedQty - Closing != Dispensed",
					icon : Ext.Msg.QUESTION,
					buttons : Ext.Msg.OK
				}).getDialog();
			}
		});
		if (invalidMsgCount == 0) {
			Ext.Ajax.request({
				url : baseUrl + 't_facility_level_monthlystatus_ext_server.php',
				params : {
					action : "updateStockDataAll",
					pFacilityId : pFacilityId,
					pMonthId : pMonthId,
					pYearId : pYearId,
					pReportId : pReportId,
					pUserId : pUserId,
					data : Ext.encode(data),
					'lang' : lan
				},
				success : function() {
					gridArvData.getStore().commitChanges();
					gridArvData.getView().refresh();
					//rIndex = -99;
					data = [];

					var msgboxpos1 = Ext.Msg.show({
						title : TEXT['Success Message'],
						msg : TEXT["Records updated successfully"],
						icon : Ext.Msg.QUESTION,
						buttons : Ext.Msg.OK
					}).getDialog();

				},
				failure : function() {
					//...
				}
			});
		}
	}

	function insertIntoStockData() {
		Ext.Ajax.request({
			waitMsg : 'Please wait...',
			url : baseUrl + 't_facility_level_monthlystatus_ext_server.php',
			params : {
				'action' : "insertIntoStockData",
				'pFacilityId' : pFacilityId,
				'pFLevelId' : pFLevelId,
				'pMonthId' : pMonthId,
				'pYearId' : pYearId,
				'pFormulationId' : 1,
				'pUserId' : pUserId,
				'pCountryId' : pCountryId,
				'pItemGroupId' : pItemGroupId,
				'lang' : lan
			},
			success : function(response) {
				loadMasterStockData();
				loadAllData();
				loadFacility();

			}
		});
	}

	function loadMasterStockData() {
		dsMasterStockData.load({
			params : {
				'pFacilityId' : pFacilityId,
				'pMonthId' : pMonthId,
				'pYearId' : pYearId,
				'pCountryId' : pCountryId,
				'pItemGroupId' : pItemGroupId,
				'lang' : lan
			}
		});
	}

	function loadAllData() {
		dsPatientOverview.load({
			params : {
				'pFacilityId' : pFacilityId,
				'pMonthId' : pMonthId,
				'pYearId' : pYearId,
				'pCountryId' : pCountryId,
				'pItemGroupId' : pItemGroupId,
				'lang' : lan,
				start : 0,
				limit : 15
			}
		});

		dsAdultRegimens.load({
			params : {
				'pFacilityId' : pFacilityId,
				'pMonthId' : pMonthId,
				'pYearId' : pYearId,
				'pCountryId' : pCountryId,
				'pItemGroupId' : pItemGroupId,
				'pFormulationId' : 1,
				'lang' : lan,
				start : 0,
				limit : 15
			}
		});

		dsArvData.load({
			params : {
				'pFacilityId' : pFacilityId,
				'pMonthId' : pMonthId,
				'pYearId' : pYearId,
				'pCountryId' : pCountryId,
				'pItemGroupId' : pItemGroupId,
				start : 0,
				limit : 20
			}
		});

	}

	//jstore.load();
	function resetStoreAndGrid() {
		// elePanelMaster.hide();
		//
		// if (gridPatientOverview.getStore().getCount() != 0) {
		// dsPatientOverview.removeAll(true);
		// gridPatientOverview.getView().refresh();
		// }
		//
		// if (gridAdultRegimens.getStore().getCount() != 0) {
		// dsAdultRegimens.removeAll(true);
		// gridAdultRegimens.getView().refresh();
		// }
		//
		// // if (gridArvData.getStore().getCount() != 0){
		// // dsArvData.removeAll(true);
		// // gridArvData.getView().refresh();
		// // }
		// dsMasterStockData.removeAll(true);

	}

	var panelCombo = new Ext.Panel({
		id : 'panelComboId',
		frame : true,
		height : 40,
		layout : 'auto',
		region : 'north',
		items : [tbar]

	});

	var panelMaster = new Ext.Panel({
		id : 'panelComboId',
		frame : true,
		height : 40,
		layout : 'auto',
		region : 'north',
		items : [tbarMaster]

	});

	var postArray = [{}];

	function changeBsubmittedInMaster() {
		var strMsg = '';
		if (pStatusId == 1) {
			pStatusId = 2;
			strMsg = TEXT['Msg_Submit'];
		} else if (pStatusId == 2) {
			pStatusId = 5;
			strMsg = TEXT['Msg_Publish'];
		}

		var msgbox = Ext.Msg.show({
			msg : strMsg,
			icon : Ext.Msg.QUESTION,
			minWidth : 200,
			buttons : Ext.Msg.YESNO,
			scope : this,
			fn : function(response) {
				if ('yes' == response) {
					Ext.Ajax.request({
						waitMsg : 'Please wait...',
						url : baseUrl + 't_facility_level_monthlystatus_ext_server.php',
						params : {
							action : "changeBsubmittedInMaster",
							'pFacilityId' : pFacilityId,
							'pMonthId' : pMonthId,
							'pYearId' : pYearId,
							'pReportId' : pReportId,
							'pUserId' : pUserId,
							'pCountryId' : pCountryId,
							'pItemGroupId' : pItemGroupId,
							'pOwnerTypeId' : pOwnerTypeId,
							'pStatusId' : pStatusId,
							'lang' : lan

						},
						success : function(response) {
							loadMasterStockData();
							loadAllData();
							loadFacility();

							var msgbox = Ext.Msg.show({
								msg : 'Your report submitted successfully.',
								icon : Ext.Msg.INFO,
								minWidth : 200,
								buttons : Ext.Msg.OK,
								scope : this
							}).getDialog();
						}
					});
				}
			}
		}).getDialog();
	}

	var panelSubmit = new Ext.Panel({
		id : 'panelSubmitId',
		frame : true,
		height : 75,
		layout : 'auto',
		items : [{
			xtype : 'button',
			id : 'submitid',
			text : TEXT['Submit'],
			width : 200,
			height : 60,
			style : 'margin:0 auto;font-size:25px',
			handler : function() {
				changeBsubmittedInMaster();
			}
		}]

	});

	var gridFacility = new Ext.grid.EditorGridPanel({
		id : 'grid-facility-id',
		store : dsFacility,

		autoHeight : true,
		style : 'text-align:left',
		sm : new Ext.grid.RowSelectionModel({
			singleSelect : true
		}),
		columns : [{
			header : "Facility ID",
			width : 150,
			dataIndex : 'FacilityId',
			sortable : true,
			hidden : true
		}, {
			header : "CFMStockId",
			width : 150,
			dataIndex : 'CFMStockId',
			sortable : true,
			hidden : true
		}, {
			header : TEXT["Facility"],
			width : 160,
			align : 'left',
			dataIndex : 'FacilityName',
			sortable : true,
			hidden : false,
			renderer : multilineColumn
		}, {
			header : "District",
			width : 110,
			align : 'left',
			dataIndex : 'DistrictName',
			sortable : true,
			hidden : true
		}, {
			xtype : 'actioncolumn',
			header : TEXT['New'] + "/<br/>" + TEXT['Edit'],
			align : 'center',
			width : 80,
			items : [{
				getClass : function(v, meta, rec) {
					//console.log(rec.get('CFMStockId'));
					if (rec.get('CFMStockId') != 0) {
						this.items[0].tooltip = 'Report Published';
						if (rec.get('StatusId') == 5)
							return 'EditEntry';
					}

					//console.log(jUserGroups[ENTRY_OPERATOR]);

					if (rec.get('CFMStockId') == 0) {
						this.items[0].tooltip = 'New Entry';
						if (jUserGroups[ENTRY_OPERATOR] == ENTRY_OPERATOR)
							return 'NewEntry';
					}
				},
				handler : function(sm, rowIndex) {
					var rec = dsFacility.getAt(rowIndex);
					vFacilityId = rec.data.FacilityId;

					vFacility = rec.data.FacilityName;

					pFacilityId = rec.data.FacilityId;
					pFLevelId = rec.data.FLevelId;

					setBaseParams('pFacilityId', pFacilityId);

					gridFacility.getSelectionModel().selectRow(rowIndex, true);

					pFacilityCount = rec.data.FacilityCount;

					if (rec.data.CFMStockId != 0) {
						loadMaster();
					} else {
						gRowIndex = rowIndex;
						createReport();
					}
				}
			}, {
				icon : baseUrl + 'images/i_trans.png'
			}, {
				getClass : function(v, meta, rec) {
					//alert(rec.get('StatusId'));
					if (rec.get('StatusId') == 1 || rec.get('StatusId') == 2) {
						this.items[1].tooltip = 'Delete Record';
						if (jUserGroups[ENTRY_OPERATOR] == ENTRY_OPERATOR)
							return 'DeleteReocord';
					}

					/*if (rec.get('StatusId') > 0) {
					 this.items[1].tooltip = 'Delete Record';
					 if (jUserGroups[ENTRY_ADMIN] == ENTRY_ADMIN) {
					 return 'DeleteReocord';
					 }
					 } else {
					 this.items[1].tooltip = '';
					 //return 'Trans';
					 }*/
				},
				handler : function(sm, rowIndex) {
					var rec = dsFacility.getAt(rowIndex);

					vFacilityId = rec.data.FacilityId;

					vFacility = rec.data.FacilityName;

					pFacilityId = rec.data.FacilityId;
					pFLevelId = rec.data.FLevelId;

					setBaseParams('pFacilityId', pFacilityId);

					if (rec.data.StatusId > 0 && rec.data.StatusId <= 5) {
						deleteRecord();
					} else {
						//alert('nothing');
					}
				}
			}, {
				getClass : function(v, meta, rec) {
					if (rec.get('StatusId') == 1) {
						this.items[1].tooltip = 'Submit';
						if (jUserGroups[ENTRY_OPERATOR] == ENTRY_OPERATOR)
							return 'Submit-S';
					} else if (rec.get('StatusId') == 2) {
						this.items[1].tooltip = 'Publish';
						if (jUserGroups[ENTRY_MANAGER] == ENTRY_MANAGER)
							return 'Submit-P';
					}
				},
				handler : function(sm, rowIndex) {
					var rec = dsFacility.getAt(rowIndex);

					vFacilityId = rec.data.FacilityId;

					vFacility = rec.data.FacilityName;

					pFacilityId = rec.data.FacilityId;
					pFLevelId = rec.data.FLevelId;

					setBaseParams('pFacilityId', pFacilityId);

					gRowIndex = rowIndex;

					pStatusId = rec.data.StatusId;

					//gridFacility.getSelectionModel().selectRow(rowIndex, true);

					//alert(rec.data.StatusId);

					if (rec.data.StatusId > 0 && rec.data.StatusId < 5) {
						changeBsubmittedInMaster();
					} else {
						//alert('nothing');
					}
				}
			}]
		}],

		view : new Ext.grid.GroupingView({
			forceFit : true,
			groupTextTpl : '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Facilities" : "Facility"]})',
			getRowClass : function(record, rowIndex) {
				var isEven = function(someNumber) {
					return (someNumber % 2 == 0) ? true : false;
				};
				if (isEven(rowIndex) == true) {
					return 'evenRow';
				} else if (isEven(rowIndex) == false) {
					return 'oddRow';
				}
			}
		}),

		viewConfig : {

		},
		plugins : [searchfield],
		align : 'center',
		columnLines : true,
		tbar : new Ext.Toolbar({
			items : []
		}),

		bbar : new Ext.PagingToolbar({
			pageSize : 25,
			store : dsFacility,
			displayInfo : true,
			displayMsg : TEXT['Displaying'] + '  {0} - {1} ' + TEXT['of'] + ' {2}',
			emptyMsg : TEXT["No Records found"],
			items : [],
			listeners : {
				beforechange : function(pageToolbar, params) {
					dsFacility.setBaseParam('pMonthId', pMonthId);
					dsFacility.setBaseParam('pYearId', pYearId);
					dsFacility.setBaseParam('pCountryId', pCountryId);
					dsFacility.setBaseParam('pItemGroupId', pItemGroupId);
				}
			}
		})
	});

	function deleteRecord() {
		vMonthId = Ext.getCmp('cboMonthId').getValue();
		vYearId = Ext.getCmp('cboYearId').getValue();

		vMonth = Ext.getCmp('cboMonthId').getRawValue();
		vYear = Ext.getCmp('cboYearId').getRawValue();

		if (vMonthId == "" || vYearId == "") {
			var msgbox = Ext.Msg.show({
				msg : 'You must select Facility, Month and Year.',
				icon : Ext.Msg.INFO,
				minWidth : 200,
				buttons : Ext.Msg.OK,
				scope : this
			}).getDialog();
			return;
		}

		var msgbox = Ext.Msg.show({
			msg : 'Do you want to delete all the records of ' + vFacility + ' on ' + vMonth + '-' + vYear + '?',
			icon : Ext.Msg.QUESTION,
			minWidth : 200,
			buttons : Ext.Msg.YESNO,
			scope : this,
			fn : function(response) {
				if ('yes' == response) {

					Ext.Ajax.request({
						waitMsg : 'Please wait...',
						url : baseUrl + 't_facility_level_monthlystatus_ext_server.php',
						params : {
							'action' : "delete_data_from_yyyy",
							'pFacilityId' : pFacilityId,
							'pOwnerTypeId' : pOwnerTypeId,
							'pMonthId' : pMonthId,
							'pYearId' : pYearId,
							'pCountryId' : pCountryId,
							'pItemGroupId' : pItemGroupId,
							'lang' : lan
						},
						success : function(response) {
							eval(response.responseText);
							if (success == 1) {
								clearMasterInfo();
								elePanelMaster.hide();
								Ext.getCmp('submitid').enable();
								pReportId = 0;
								loadAllData();
								loadFacility();
							}
						}
					});
				}
			}
		}).getDialog();
	}


	gridFacility.getSelectionModel().on('rowselect', function(sm, rowIndex, record) {
		clearMasterInfo();

		gRowIndex = rowIndex;

		var rec = dsFacility.getAt(rowIndex);
		vFacilityId = rec.get('FacilityId');

		initMonthId = parseInt(rec.get('StartMonthId'));

		initYearId = parseInt(rec.get('StartYearId'));

		supplyFrom = parseInt(rec.get('SupplyFrom'));

		startDate = new Date(initYearId, initMonthId - 1);
		startManthName = startDate.getMonthName();

		vFacility = rec.get('FacilityName');

		pFacilityId = rec.get('FacilityId');
		pFLevelId = rec.get('FLevelId');

		setBaseParams('pFacilityId', pFacilityId);

		pFacilityCount = rec.get('FacilityCount');

		loadMaster();

	});

	function loadMaster() {
		resetStoreAndGrid();

		var clmModel = gridArvData.getColumnModel();

		if (pFacilityCount > 0) {
			//console.log(clmModel);
			clmModel.setColumnHeader(CLM_DISPENSEQTY, TEXT['Issued'] + '<br/>(C)');
			clmModel.setColumnTooltip(CLM_DISPENSEQTY, TEXT['Issued'] + ' Quantity -> (C)');

			//clmModel.setHidden(CLM_CLSTOCKSOURCEID, true);
			//clmModel.setHidden(CLM_AMC_C, true);
			clmModel.setHidden(CLM_AMC, true);
			//clmModel.setHidden(CLM_AMCCHANGEREASONID, true);
			clmModel.setHidden(CLM_MOS, true);
			clmModel.setHidden(CLM_MAXQTY, true);
			clmModel.setHidden(CLM_ORDERQTY, true);
			clmModel.setHidden(CLM_ACTUALQTY, true);
			clmModel.setHidden(CLM_OUREASONID, true);
			//clmModel.setHidden(CLM_USERID, true);
			//clmModel.setHidden(CLM_FORMULATIONNAME, true);

		} else {

			clmModel.setColumnHeader(CLM_DISPENSEQTY, TEXT['Dispensed'] + '<br/>(C)');
			clmModel.setColumnTooltip(CLM_DISPENSEQTY, TEXT['Dispensed'] + ' Quantity -> (C)');

			//clmModel.setHidden(CLM_CLSTOCKSOURCEID, false);
			//clmModel.setHidden(CLM_AMC_C, false);
			clmModel.setHidden(CLM_AMC, false);
			//clmModel.setHidden(CLM_AMCCHANGEREASONID, false);
			clmModel.setHidden(CLM_MOS, false);
			clmModel.setHidden(CLM_MAXQTY, false);
			clmModel.setHidden(CLM_ORDERQTY, false);
			clmModel.setHidden(CLM_ACTUALQTY, false);
			clmModel.setHidden(CLM_OUREASONID, false);
			//clmModel.setHidden(CLM_USERID, false);
			//clmModel.setHidden(CLM_FORMULATIONNAME, false);
			//clmModel.setHidden(28, false);
		}

		loadMasterStockData();

	}

	function getOpeningEditable() {
		vMonthId = Ext.getCmp('cboMonthId').getValue();
		vYearId = Ext.getCmp('cboYearId').getValue();
		var cm = Ext.getCmp('gridArvDataId').getColumnModel();
		var valCol = cm.getColumnAt(8);
		if (vMonthId == initMonthId && vYearId == initYearId) {
			valCol.editable = true;
		} else
			valCol.editable = false;
	}

	//panelParams.render('panel-params');
	panelCombo.render('panelCombo');
	//panelMaster.render('panelMaster');
	gridFacility.render('grid-facility');
	tabArvdata.render('tabArvdata');
	panelSubmit.render('panelSubmit');

	var countryStore = Ext.getCmp("cboCountryId").getStore();
	cboCountry.setValue(countryStore.data.items[0].data.CountryId);

	//pCountryId = Ext.getCmp("cboCountryId").getValue();

	//cboCountry.setValue(countryStore.data.items[0].data.CountryId);

	var cRecord = countryStore.data.items[0];

	cboCountry.fireEvent('select', cboCountry, cRecord);

	// var itemGroupStore = Ext.getCmp("cboItemGroupId").getStore();

	// cboItemGroup.setValue(itemGroupStore.data.items[0].data.ItemGroupId);

	// var cRecord = itemGroupStore.data.items[0];

	// cboItemGroup.fireEvent('select', cboItemGroup, cRecord);

	jQuery('#submitid button').attr('id', 'btn-sub-acc-pub');

	function isValidDays(year, month, cdays) {
		var days = new Date(year, month * 1, -.1).getDate();
		if (cdays >= 1 && cdays <= days)
			return true;
		else
			return false;
	}

	pCountryId = 1;
	cboCountry.setValue(pCountryId);

	dsRegion.setBaseParam('pCountryId', pCountryId);
	dsRegion.load();

	dsFacility.setBaseParam('pCountryId', pCountryId);

	pOwnerTypeId = 2;
	cboOwnerType.setValue(pOwnerTypeId);

	dsFacility.setBaseParam('pOwnerTypeId', pOwnerTypeId);

	Ext.getCmp('submitid').disable();

});
