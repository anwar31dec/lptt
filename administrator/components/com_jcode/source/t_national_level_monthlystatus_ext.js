var POMasterEntryform;
var savePOMasterEntryform;
var ResetPOMasterEntryform;
var POMasterEntryformWindow;
var dsPatientOverview;
var dsAdultRegimens;
var dsPaediatricRegimens;
var dsOIsAndProphylaxis;
var dsMasterStockData;
var dsArvData;
var ClosePOMasterEntryform;
var searchfield_patientOverview;
var searchfield_adultRegimens;
var searchfield_paediatricRegimens;
var searchfield_OIsAndProphylaxis;
var searchfield_ArvData;
var recPatientOverview;
var gridAdultRegimens;
var gridPaediatricRegimens;
var gridOIsAndProphylaxis;
var gridArvData;
var tabArvdata;
var facilityId;
var dsMonth;
var dsYear;
var dsRegion;
var pFacilityId;
var pMonthId;
var pYearId;
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
var vOrderQty = 0;
var vClStock_A = 0;
var vClStockSourceId;
var vDispenseQty = 0;
var vClStock_C = 0;
var vAMC = 0;
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
var invalidMsgCount = 0;
var bShowMsgBox = 0;
var bYesNoMsg = 0;
var r = -1, c = -1;
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
var pAdjustId;
var gItemGroupId = 0;
var pCountryId = 0;
var pItemGroupId = 0;
var pStatusId = 0;

var vItemId;
var vItem;

var vMonthId;
var vMonth;

Ext.onReady(function() {
	Ext.QuickTips.init();

	// pUserId = document.getElementById("user_id").value;
	// lmisStartMonth = document.getElementById("init_month").value;
	// lmisStartYear = document.getElementById("init_year").value;

	//console.log(objInit);

	pUserId = jUserId;
	// lmisStartMonth = 1
	// lmisStartYear = 2014

	Date.prototype.getMonthName = function() {
		var m = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
		return m[this.getMonth()];
	}
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

	//added feature: moveEditorOnEnterLikeOnTab
	Ext.override(Ext.grid.RowSelectionModel, {
		moveEditorOnEnterLikeOnTab : false, // patch

		onEditorKey : function(field, e) {
			var k = e.getKey(), newCell, g = this.grid, last = g.lastEdit, ed = g.activeEditor, shift = e.shiftKey, ae, last, r, c;

			var rowCount = g.getStore().getCount();
			var gridrecord = g.getSelectionModel().getSelected();
			var rowIndex = g.getStore().indexOf(gridrecord);
			//alert(rowIndex);

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
			} else if (k == e.LEFT) {
				e.stopEvent();
				ed.completeEdit();
				newCell = g.walkCells(ed.row, ed.col - 1, -1, this.acceptsNav, this);
			} else if (k == e.DOWN) {
				e.stopEvent();
				ed.completeEdit();
				newCell = g.walkCells(last.row + 1, last.col, 1, this.acceptsNav, this);
			} else if (k == e.UP) {
				e.stopEvent();
				ed.completeEdit();
				newCell = g.walkCells(last.row - 1, last.col, -1, this.acceptsNav, this);
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
		var vCNMPOId = oGrid_event.record.data.CNMPOId;
		var vRefillPatient = oGrid_event.record.data.RefillPatient;
		var vNewPatient = oGrid_event.record.data.NewPatient;
		var vTotalPatient = oGrid_event.record.data.RefillPatient + oGrid_event.record.data.NewPatient;
		oGrid_event.record.set("TotalPatient", vTotalPatient);
		dsPatientOverview.commitChanges();
		Ext.Ajax.request({
			waitMsg : 'Please wait...',
			url : baseUrl + 't_national_level_monthlystatus_ext_server.php',
			params : {
				action : "updatePatientOverview",
				cNMPOId : vCNMPOId,
				refillPatient : vRefillPatient,
				newPatient : vNewPatient,
				totalPatient : vTotalPatient,
				pFacilityId : pFacilityId,
				pMonthId : pMonthId,
				pYearId : pYearId,
				gItemGroupId : gItemGroupId
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
		var vCNMPatientStatusId = oGrid_event.record.data.CNMPatientStatusId;
		var vRefillPatient = oGrid_event.record.data.RefillPatient;
		var vNewPatient = oGrid_event.record.data.NewPatient;
		var vTotalPatient = oGrid_event.record.data.RefillPatient + oGrid_event.record.data.NewPatient;
		oGrid_event.record.set("TotalPatient", vTotalPatient);
		dsAdultRegimens.commitChanges();
		dsPaediatricRegimens.commitChanges();
		dsOIsAndProphylaxis.commitChanges();
		Ext.Ajax.request({
			waitMsg : 'Please wait...',
			url : baseUrl + 't_national_level_monthlystatus_ext_server.php',
			params : {
				action : "updateRegimenPatient",
				CNMPatientStatusId : vCNMPatientStatusId,
				RefillPatient : vRefillPatient,
				NewPatient : vNewPatient,
				TotalPatient : vTotalPatient,
				pFacilityId : pFacilityId,
				pMonthId : pMonthId,
				pYearId : pYearId
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

	function saveTheCell(oGrid_event) {
		r = oGrid_event.row;
		c = oGrid_event.column;
		if (oGrid_event.field == "ActualQty") {
			vActualQty = oGrid_event.record.data.ActualQty <= 0 ? 0 : oGrid_event.record.data.ActualQty;
			vARVDataId = oGrid_event.record.data.ARVDataId;
			vOpStock_C = oGrid_event.record.data.OpStock_C == "" ? 0 : oGrid_event.record.data.OpStock_C;
			vOpStock_A = oGrid_event.record.data.OpStock_A == "" ? 0 : oGrid_event.record.data.OpStock_A;
			vReceiveQty = oGrid_event.record.data.ReceiveQty == "" ? 0 : oGrid_event.record.data.ReceiveQty;
			vDispenseQty = oGrid_event.record.data.DispenseQty == "" ? 0 : oGrid_event.record.data.DispenseQty;
			vAdjustQty = oGrid_event.record.data.AdjustQty == "" ? 0 : oGrid_event.record.data.AdjustQty;
			vAdjustReason = oGrid_event.record.data.AdjustReason;
			vStockoutDays = oGrid_event.record.data.StockoutDays == "" ? 0 : oGrid_event.record.data.StockoutDays;
			vOrderQty = oGrid_event.record.data.OrderQty == "" ? 0 : oGrid_event.record.data.OrderQty;
			vClStock_A = oGrid_event.record.data.ClStock_A;
			vClStockSourceId = oGrid_event.record.data.ClStockSourceId;

			//vLastTwoMonthsDispensed = oGrid_event.record.data.LastTwoMonthsDispensed == "" ? 0 : oGrid_event.record.data.LastTwoMonthsDispensed;
			vBeforeLastMonthDispensed = oGrid_event.record.data.BeforeLastMonthDispensed == "" ? 0 : oGrid_event.record.data.BeforeLastMonthDispensed;

			vBeforeLastMonthDispensedDevisor = (vBeforeLastMonthDispensed > 0 ? 1 : 0 );

			vLastMonthDispensed = oGrid_event.record.data.LastMonthDispensed == "" ? 0 : oGrid_event.record.data.LastMonthDispensed;

			vLastMonthDispensedDevisor = (vLastMonthDispensed > 0 ? 1 : 0 );

			vDispenseQtyDevisor = (vDispenseQty > 0 ? 1 : 0 );

			v3MonthTotal = vBeforeLastMonthDispensed + vLastMonthDispensed + vDispenseQty;

			vDevisor = vBeforeLastMonthDispensedDevisor + vLastMonthDispensedDevisor + vDispenseQtyDevisor;

			vAMC = Math.round(v3MonthTotal / vDevisor);
			//vAMC = vDispenseQty;

			vAmcChangeReasonId = oGrid_event.record.data.AmcChangeReasonId;

			vMOS = (vAMC != 0 ? vClStock_A / vAMC : 0 ).toFixed(2);
			vMaxQty = oGrid_event.record.data.MaxQty == "" ? 0 : oGrid_event.record.data.MaxQty;
			return;
		}

		if (oGrid_event.field == "AMC") {
			vARVDataId = oGrid_event.record.data.ARVDataId;
			vOpStock_C = isNaN(oGrid_event.record.data.OpStock_C) || oGrid_event.record.data.OpStock_C == "" ? 0 : oGrid_event.record.data.OpStock_C;
			vOpStock_A = isNaN(oGrid_event.record.data.OpStock_A) || oGrid_event.record.data.OpStock_A == "" ? 0 : oGrid_event.record.data.OpStock_A;
			vReceiveQty = isNaN(oGrid_event.record.data.ReceiveQty) || oGrid_event.record.data.ReceiveQty == "" ? 0 : oGrid_event.record.data.ReceiveQty;
			vDispenseQty = isNaN(oGrid_event.record.data.DispenseQty) || oGrid_event.record.data.DispenseQty == "" ? 0 : oGrid_event.record.data.DispenseQty;
			vAdjustQty = isNaN(oGrid_event.record.data.AdjustQty) || oGrid_event.record.data.AdjustQty == "" ? 0 : oGrid_event.record.data.AdjustQty;
			vAdjustReason = oGrid_event.record.data.AdjustReason;
			vStockoutDays = isNaN(oGrid_event.record.data.StockoutDays) || oGrid_event.record.data.StockoutDays == "" ? 0 : oGrid_event.record.data.StockoutDays;
			vOrderQty = isNaN(oGrid_event.record.data.OrderQty) || oGrid_event.record.data.OrderQty == "" ? 0 : oGrid_event.record.data.OrderQty;

			vActualQty = isNaN(oGrid_event.record.data.ActualQty) || oGrid_event.record.data.ActualQty == "" ? 0 : oGrid_event.record.data.ActualQty;

			vClStock_A = oGrid_event.record.data.ClStock_A;

			vClStockSourceId = oGrid_event.record.data.ClStockSourceId;

			vClStock_C = vOpStock_C + vReceiveQty - vDispenseQty + vAdjustQty;
			oGrid_event.record.set("ClStock_C", vClStock_C);
			oGrid_event.record.set("ClStock_A", vClStock_A);

			vAMC = oGrid_event.record.data.AMC;

			vBeforeLastMonthDispensed = isNaN(oGrid_event.record.data.BeforeLastMonthDispensed) || oGrid_event.record.data.BeforeLastMonthDispensed == "" ? 0 : oGrid_event.record.data.BeforeLastMonthDispensed;

			vBeforeLastMonthDispensedDevisor = (vBeforeLastMonthDispensed > 0 ? 1 : 0 );

			vLastMonthDispensed = isNaN(oGrid_event.record.data.LastMonthDispensed) || oGrid_event.record.data.LastMonthDispensed == "" ? 0 : oGrid_event.record.data.LastMonthDispensed;

			vLastMonthDispensedDevisor = (vLastMonthDispensed > 0 ? 1 : 0 );

			vDispenseQtyDevisor = (vDispenseQty > 0 ? 1 : 0 );

			v3MonthTotal = vBeforeLastMonthDispensed + vLastMonthDispensed + vDispenseQty;

			vDevisor = vBeforeLastMonthDispensedDevisor + vLastMonthDispensedDevisor + vDispenseQtyDevisor;

			vAMC = isNaN(vAMC) || vAMC == "" ? 0 : vAMC;

			vAmcChangeReasonId = oGrid_event.record.data.AmcChangeReasonId;

			vMOS = (vAMC != 0 ? vClStock_A / vAMC : 0 ).toFixed(2);
			vMOS = isNaN(vMOS) || vMOS == "" ? 0 : vMOS;
			oGrid_event.record.set("MOS", vMOS);
			oGrid_event.record.set("MaxQty", vAMC * 3);
			vMaxQty = isNaN(oGrid_event.record.data.MaxQty) || oGrid_event.record.data.MaxQty == "" ? 0 : oGrid_event.record.data.MaxQty;
			//oGrid_event.record.set("OrderQty", vMaxQty - vClStock_A);
			oGrid_event.record.set("OrderQty", ((vMaxQty - vClStock_A) < 0 ? 0 : (vMaxQty - vClStock_A)));
			vOrderQty = isNaN(oGrid_event.record.data.OrderQty) || oGrid_event.record.data.OrderQty == "" ? 0 : oGrid_event.record.data.OrderQty;

			oGrid_event.record.set("ActualQty", vMaxQty - vClStock_A);
			vActualQty = isNaN(oGrid_event.record.data.ActualQty) || oGrid_event.record.data.ActualQty <= 0 ? 0 : oGrid_event.record.data.ActualQty;
			return;
		}

		vARVDataId = oGrid_event.record.data.ARVDataId;
		vOpStock_C = isNaN(oGrid_event.record.data.OpStock_C) || oGrid_event.record.data.OpStock_C == "" ? 0 : oGrid_event.record.data.OpStock_C;
		vOpStock_A = isNaN(oGrid_event.record.data.OpStock_A) || oGrid_event.record.data.OpStock_A == "" ? 0 : oGrid_event.record.data.OpStock_A;
		vReceiveQty = isNaN(oGrid_event.record.data.ReceiveQty) || oGrid_event.record.data.ReceiveQty == "" ? 0 : oGrid_event.record.data.ReceiveQty;
		vDispenseQty = isNaN(oGrid_event.record.data.DispenseQty) || oGrid_event.record.data.DispenseQty == "" ? 0 : oGrid_event.record.data.DispenseQty;
		vAdjustQty = isNaN(oGrid_event.record.data.AdjustQty) || oGrid_event.record.data.AdjustQty == "" ? 0 : oGrid_event.record.data.AdjustQty;
		vAdjustReason = oGrid_event.record.data.AdjustReason;
		vStockoutDays = isNaN(oGrid_event.record.data.StockoutDays) || oGrid_event.record.data.StockoutDays == "" ? 0 : oGrid_event.record.data.StockoutDays;
		vOrderQty = isNaN(oGrid_event.record.data.OrderQty) || oGrid_event.record.data.OrderQty == "" ? 0 : oGrid_event.record.data.OrderQty;

		vActualQty = isNaN(oGrid_event.record.data.ActualQty) || oGrid_event.record.data.ActualQty == "" ? 0 : oGrid_event.record.data.ActualQty;

		vClStock_A = oGrid_event.record.data.ClStock_A;
		vClStockSourceId = oGrid_event.record.data.ClStockSourceId;
		vClStock_C = vOpStock_C + vReceiveQty - vDispenseQty + vAdjustQty;
		oGrid_event.record.set("ClStock_C", vClStock_C);
		oGrid_event.record.set("ClStock_A", vClStock_A);
		vBeforeLastMonthDispensed = isNaN(oGrid_event.record.data.BeforeLastMonthDispensed) || oGrid_event.record.data.BeforeLastMonthDispensed == "" ? 0 : oGrid_event.record.data.BeforeLastMonthDispensed;

		vBeforeLastMonthDispensedDevisor = (vBeforeLastMonthDispensed > 0 ? 1 : 0 );

		vLastMonthDispensed = isNaN(oGrid_event.record.data.LastMonthDispensed) || oGrid_event.record.data.LastMonthDispensed == "" ? 0 : oGrid_event.record.data.LastMonthDispensed;

		vLastMonthDispensedDevisor = (vLastMonthDispensed > 0 ? 1 : 0 );

		vDispenseQtyDevisor = (vDispenseQty > 0 ? 1 : 0 );

		v3MonthTotal = vBeforeLastMonthDispensed + vLastMonthDispensed + vDispenseQty;

		vDevisor = vBeforeLastMonthDispensedDevisor + vLastMonthDispensedDevisor + vDispenseQtyDevisor;

		vAMC = Math.round(v3MonthTotal / vDevisor);
		//vAMC = vDispenseQty;

		vAmcChangeReasonId = oGrid_event.record.data.AmcChangeReasonId;

		vAMC = isNaN(vAMC) || vAMC == "" ? 0 : vAMC;
		oGrid_event.record.set("AMC", vAMC);
		vMOS = (vAMC != 0 ? vClStock_A / vAMC : 0 ).toFixed(2);
		vMOS = isNaN(vMOS) || vMOS == "" ? 0 : vMOS;
		oGrid_event.record.set("MOS", vMOS);
		oGrid_event.record.set("MaxQty", vAMC * 3);
		vMaxQty = isNaN(oGrid_event.record.data.MaxQty) || oGrid_event.record.data.MaxQty == "" ? 0 : oGrid_event.record.data.MaxQty;
		//oGrid_event.record.set("OrderQty", vMaxQty - vClStock_A);
		oGrid_event.record.set("OrderQty", ((vMaxQty - vClStock_A) < 0 ? 0 : (vMaxQty - vClStock_A)));
		vOrderQty = isNaN(oGrid_event.record.data.OrderQty) || oGrid_event.record.data.OrderQty == "" ? 0 : oGrid_event.record.data.OrderQty;

		oGrid_event.record.set("ActualQty", vMaxQty - vClStock_A);
		vActualQty = isNaN(oGrid_event.record.data.ActualQty) || oGrid_event.record.data.ActualQty <= 0 ? 0 : oGrid_event.record.data.ActualQty;

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
			action : "getRegion"
		},
		autoLoad : true
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
			action : "getYear"
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
				dsPatientOverview.setBaseParam('pYearId', pYearId);
				dsAdultRegimens.setBaseParam('pYearId', pYearId);
				dsPaediatricRegimens.setBaseParam('pYearId', pYearId);
				dsOIsAndProphylaxis.setBaseParam('pYearId', pYearId);
				dsMasterStockData.setBaseParam('pYearId', pYearId);
				dsArvData.setBaseParam('pYearId', pYearId);
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
			action : "getMonth"
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
				dsPatientOverview.setBaseParam('pMonthId', pMonthId);
				dsAdultRegimens.setBaseParam('pMonthId', pMonthId);
				dsPaediatricRegimens.setBaseParam('pMonthId', pMonthId);
				dsOIsAndProphylaxis.setBaseParam('pMonthId', pMonthId);
				dsMasterStockData.setBaseParam('pMonthId', pMonthId);
				dsArvData.setBaseParam('pMonthId', pMonthId);
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
			action : "getAdjust"
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
			action : "getClStockSource"
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
			action : "getAmcChangeReason"
		},
		autoLoad : true
	});

	//******************************** dsPatientOverview : Patient Overview Data Store ****************//
	dsPatientOverview = new Ext.data.Store({
		id : 'dsPatientOverviewId',
		proxy : new Ext.data.HttpProxy({
			url : baseUrl + 't_national_level_monthlystatus_ext_server.php',
			method : 'POST'
		}),

		baseParams : {
			action : "getPatientOverview"
		},
		reader : new Ext.data.JsonReader({
			root : 'results',
			totalProperty : 'total',
			id : 'id'
		}, [{
			name : 'CNMPOId',
			type : 'int',
			mapping : 'CNMPOId'
		}, {
			name : 'FormulationName',
			type : 'string',
			mapping : 'FormulationName'
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
			field : 'CNMPOId',
			direction : "ASC"
		}
	});

	dsPatientOverview.on('exception', function(DataProxy, type, action, options, response, arg) {
		//alert(response.responseText);

	}, this);

	var gridPatientOverview = new Ext.grid.EditorGridPanel({
		region : 'center',
		style : 'text-align:left',
		stripeRows : true,
		store : dsPatientOverview,
		clicksToEdit : 1,
		loadMask : {
			msg : 'Loading data.',
			enabled : true
		},
		sm : new Ext.grid.RowSelectionModel({
			singleSelect : true
		}),
		columns : [new Ext.grid.RowNumberer({
			header : '#'
		}), {
			header : 'CNMPOId',
			width : 120,
			dataIndex : 'CNMPOId',
			sortable : true,
			hidden : true
		}, {
			header : TEXT['Patient Type'],
			width : 200,
			align : 'left',
			dataIndex : 'FormulationName',
			sortable : true,
			hidden : false
		}, {
			header : TEXT['Refill Patients'],
			width : 150,
			align : 'right',
			dataIndex : 'RefillPatient',
			sortable : true,
			hidden : false,
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
			hidden : false,
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
			renderer : render_val,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 10000000,
				selectOnFocus : true
			})
		}],
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

	//function to make EditorGridPanel read only
	gridPatientOverview.on('beforeedit', handler_to_makeReadOnly);

	//******************************** dsAdultRegimens : Patient Overview Data Store ****************//
	// dsAdultRegimens = new Ext.data.Store({
	// id: 'dsRegimensId',
	// proxy: new Ext.data.HttpProxy({
	// url: baseUrl + 't_national_level_monthlystatus_ext_server.php',
	// method: 'POST'
	// }),
	//
	// baseParams: {
	// action: "getRegimens",
	// pFormulationId: 1
	// },
	// reader: new Ext.data.JsonReader({
	// root: 'results',
	// totalProperty: 'total',
	// id: 'id'
	// },[{
	// name: 'RegimenPatientId',
	// type: 'int',
	// mapping: 'RegimenPatientId'
	// },{
	// name: 'RegimenName',
	// type: 'string',
	// mapping: 'RegimenName'
	// },{
	// name: 'PatientCount',
	// type: 'int',
	// mapping: 'PatientCount'
	// }])
	// ,
	// sortInfo: {
	// field: 'RegimenName',
	// direction: "ASC"
	// }
	// });

	dsAdultRegimens = new Ext.data.GroupingStore({
		id : 'dsRegimensId',
		proxy : new Ext.data.HttpProxy({
			url : baseUrl + 't_national_level_monthlystatus_ext_server.php',
			method : 'POST'
		}),

		baseParams : {
			action : "getRegimens",
			pFormulationId : 1
		},
		reader : new Ext.data.JsonReader({
			root : 'results',
			totalProperty : 'total',
			id : 'id'
		}, [{
			name : 'CNMPatientStatusId',
			type : 'int',
			mapping : 'CNMPatientStatusId'
		}, {
			name : 'RegimenName',
			type : 'string',
			mapping : 'RegimenName'
		}, {
			name : 'FormulationName',
			type : 'string',
			mapping : 'FormulationName'
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
			field : 'RegimenName',
			direction : "ASC"
		},
		groupField : 'FormulationName'
	});

	dsAdultRegimens.on('exception', function(DataProxy, type, action, options, response, arg) {
		//alert(response.responseText);
	}, this);
	gridAdultRegimens = new Ext.grid.EditorGridPanel({
		//title: '<h3><center>Adult Regimens List</center></h3>',
		region : 'center',
		style : 'text-align:left',
		stripeRows : true,
		store : dsAdultRegimens,
		clicksToEdit : 1,
		loadMask : {
			msg : 'Loading data.',
			enabled : true
		},
		sm : new Ext.grid.RowSelectionModel({
			singleSelect : true
		}),
		columns : [new Ext.grid.RowNumberer({
			header : '#'
		}), {
			header : "Regimen Patient Id",
			width : 120,
			dataIndex : 'CNMPatientStatusId',
			sortable : true,
			hidden : true
		}, {
			header : TEXT["Regimens"],
			width : 200,
			align : 'left',
			dataIndex : 'RegimenName',
			sortable : true,
			hidden : false
		}, {
			header : TEXT["Formulation"],
			width : 300,
			align : 'left',
			dataIndex : 'FormulationName',
			sortable : true,
			hidden : true
		}, {
			header : TEXT["Refill Patients"],
			width : 150,
			align : 'right',
			dataIndex : 'RefillPatient',
			sortable : true,
			hidden : false,
			renderer : render_val,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 10000000,
				selectOnFocus : true
			})
		}, {
			header : TEXT["New Patients"],
			width : 150,
			align : 'right',
			dataIndex : 'NewPatient',
			sortable : true,
			hidden : false,
			renderer : render_val,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 10000000,
				selectOnFocus : true
			})
		}, {
			header : TEXT["Total Patients"],
			width : 150,
			align : 'right',
			dataIndex : 'TotalPatient',
			sortable : true,
			hidden : false,
			renderer : render_val,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 10000000,
				selectOnFocus : true
			})
		}],
		view : new Ext.grid.GroupingView({
			forceFit : true,
			groupTextTpl : '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})',
			getRowClass : function(record, rowIndex) {
				// if((record.data.OpStock_A + record.data.ReceiveQty + record.data.AdjustQty - record.data.ClStock_A) != record.data.DispenseQty) {
				// return 'clsinvalid';
				// }
			}
		}),
		viewConfig : {
			emptyText : 'No rows to display'
		},
		autoHeight : true,
		align : 'center',
		columnLines : true
	});

	gridAdultRegimens.on('afteredit', saveAdultRegimens);

	function handler_to_makeReadOnly(e) {
		return bReadOnly;
	}

	//function to make EditorGridPanel read only
	gridAdultRegimens.on('beforeedit', handler_to_makeReadOnly);

	//******************************** dsPaediatricRegimens : Regions Data Store ****************//
	dsPaediatricRegimens = new Ext.data.Store({
		id : 'dsPaediatricRegimensId',
		proxy : new Ext.data.HttpProxy({
			url : baseUrl + 't_national_level_monthlystatus_ext_server.php',
			method : 'POST'
		}),

		baseParams : {
			action : "getRegimens",
			pFormulationId : 2
		},
		reader : new Ext.data.JsonReader({
			root : 'results',
			totalProperty : 'total',
			id : 'id'
		}, [{
			name : 'RegimenPatientId',
			type : 'int',
			mapping : 'RegimenPatientId'
		}, {
			name : 'RegimenName',
			type : 'string',
			mapping : 'RegimenName'
		}, {
			name : 'PatientCount',
			type : 'int',
			mapping : 'PatientCount'
		}]),
		sortInfo : {
			field : 'RegimenName',
			direction : "ASC"
		}
	});

	dsPaediatricRegimens.on('exception', function(DataProxy, type, action, options, response, arg) {
		//alert(response.responseText);
	}, this);
	gridPaediatricRegimens = new Ext.grid.EditorGridPanel({
		region : 'center',
		style : 'text-align:left',
		stripeRows : true,
		store : dsPaediatricRegimens,
		clicksToEdit : 1,
		loadMask : {
			msg : 'Loading data.',
			enabled : true
		},
		sm : new Ext.grid.RowSelectionModel({
			singleSelect : true
		}),
		columns : [new Ext.grid.RowNumberer({
			header : '#'
		}), {
			header : "Regimen Patient Id",
			width : 120,
			dataIndex : 'RegimenPatientId',
			sortable : true,
			hidden : true
		}, {
			header : "Regimens",
			width : 300,
			align : 'left',
			dataIndex : 'RegimenName',
			sortable : true,
			hidden : false
		}, {
			header : "# of Patients",
			width : 150,
			align : 'right',
			dataIndex : 'PatientCount',
			sortable : true,
			hidden : false,
			renderer : render_val,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 10000000,
				selectOnFocus : true
			})
		}],
		viewConfig : {
			emptyText : 'No records found for this month'
		},
		autoHeight : true,
		align : 'center',
		columnLines : true
	});

	gridPaediatricRegimens.on('afteredit', saveAdultRegimens);

	function handler_to_makeReadOnly(e) {
		return bReadOnly;
	}

	//function to make EditorGridPanel read only
	gridPaediatricRegimens.on('beforeedit', handler_to_makeReadOnly);

	//******************************** dsOIsAndProphylaxis : Regions Data Store ****************//

	dsOIsAndProphylaxis = new Ext.data.Store({
		id : 'dsPaediatricRegimensId',
		proxy : new Ext.data.HttpProxy({
			url : baseUrl + 't_national_level_monthlystatus_ext_server.php',
			method : 'POST'
		}),

		baseParams : {
			action : "getRegimens",
			pFormulationId : 3
		},
		reader : new Ext.data.JsonReader({
			root : 'results',
			totalProperty : 'total',
			id : 'id'
		}, [{
			name : 'RegimenPatientId',
			type : 'int',
			mapping : 'RegimenPatientId'
		}, {
			name : 'RegimenName',
			type : 'string',
			mapping : 'RegimenName'
		}, {
			name : 'PatientCount',
			type : 'int',
			mapping : 'PatientCount'
		}]),
		sortInfo : {
			field : 'RegimenName',
			direction : "ASC"
		}
	});

	dsOIsAndProphylaxis.on('exception', function(DataProxy, type, action, options, response, arg) {
		alert(response.responseText);
	}, this);
	gridOIsAndProphylaxis = new Ext.grid.EditorGridPanel({
		region : 'center',
		style : 'text-align:left',
		stripeRows : true,
		store : dsOIsAndProphylaxis,
		clicksToEdit : 1,
		loadMask : {
			msg : 'Loading data.',
			enabled : true
		},
		sm : new Ext.grid.RowSelectionModel({
			singleSelect : true
		}),
		columns : [new Ext.grid.RowNumberer({
			header : '#'
		}), {
			header : "Regimen Patient Id",
			width : 120,
			dataIndex : 'RegimenPatientId',
			sortable : true,
			hidden : true
		}, {
			header : "Regimens",
			width : 300,
			align : 'left',
			dataIndex : 'RegimenName',
			sortable : true,
			hidden : false
		}, {
			header : "# of Patients",
			width : 150,
			align : 'right',
			dataIndex : 'PatientCount',
			sortable : true,
			hidden : false,
			renderer : render_val,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 10000000,
				selectOnFocus : true
			})
		}],
		viewConfig : {

		},
		autoHeight : true,
		align : 'center',
		columnLines : true
	});

	gridOIsAndProphylaxis.on('afteredit', saveAdultRegimens);

	function handler_to_makeReadOnly(e) {
		return bReadOnly;
	}

	//function to make EditorGridPanel read only
	gridOIsAndProphylaxis.on('beforeedit', handler_to_makeReadOnly);

	dsMasterStockData = new Ext.data.Store({
		id : 'dsMasterStockDataId',
		proxy : new Ext.data.HttpProxy({
			url : baseUrl + 't_national_level_monthlystatus_ext_server.php',
			method : 'POST'
		}),

		baseParams : {
			action : "getMasterStockData",
			pUserId : pUserId
		},
		reader : new Ext.data.JsonReader({
			root : 'results',
			totalProperty : 'total',
			id : 'id'
		}, [{
			name : 'ReportId',
			type : 'int',
			mapping : 'CNMStockId'
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

		var reportId = 0;
		if (store.getCount() > 1) {
			alert("You have more than one report of this month");
			return
		}
		if (store.getCount() == 1) {
			reportId = store.data.items[0].data.ReportId;
			pReportId = reportId;
			if (reportId > 0) {
				elePanelMaster.show();
				document.getElementById("txtReportIdDiv").innerHTML = TEXT["Report Id"]+" : " + reportId;

				pStatusId = store.data.items[0].data.StatusId;
				if (store.data.items[0].data.StatusId == 5) {
					Ext.getCmp('submitid').disable();
					bReadOnly = false;
				} else {
					Ext.getCmp('submitid').enable();
					bReadOnly = true;
				}
				if (store.data.items[0].data.StatusId == 1)
					Ext.getCmp('submitid').setText(TEXT['Submit']);
				else if (store.data.items[0].data.StatusId == 2)
					Ext.getCmp('submitid').setText(TEXT['Accept']);
				else if (store.data.items[0].data.StatusId == 3)
					//Ext.getCmp('submitid').setText('<span style="font-size:2em;">Publish</span>');
					Ext.getCmp('submitid').setText(TEXT['Publish']);

				//console.log(Ext.getCmp('submitid'));

				var statusColor = '#2A7907';
				if (store.data.items[0].data.StatusId == 1) {
					var statusColor = '#FF0000';
				} else {
					var statusColor = '#2A7907';
				}

				document.getElementById("txtSubmitStatusDiv").innerHTML = "<span style='font-size:2em;color:" + statusColor + ";'>" + store.data.items[0].data.StatusName + "</span>";

				if (store.data.items[0].data.CreatedDt != '')
					document.getElementById('created-date').innerHTML = TEXT["Created Date"]+" : " + store.data.items[0].data.CreatedDt;
				if (store.data.items[0].data.LastSubmittedDt != '')
					document.getElementById('submitted-date').innerHTML = TEXT["Submitted Date"]+" : " + store.data.items[0].data.LastSubmittedDt;
				if (store.data.items[0].data.AcceptedDt != '')
					document.getElementById('accepted-date').innerHTML = TEXT["Accepted Date"]+" : " + store.data.items[0].data.AcceptedDt;
				if (store.data.items[0].data.PublishedDt != '')
					document.getElementById('published-date').innerHTML = TEXT["Published Date"]+" : " + store.data.items[0].data.PublishedDt;

				dsArvData.setBaseParam('pReportId', reportId);

				loadAllData();
			}
		} else {
			clearMasterInfo();
			elePanelMaster.hide();
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
			//msgbox.setPagePosition(200,150);
		}

	}, this);
	function clearMasterInfo() {
		document.getElementById("txtReportIdDiv").innerHTML = "";
		document.getElementById("txtSubmitStatusDiv").innerHTML = "";
		document.getElementById('created-date').innerHTML = "";
		document.getElementById('submitted-date').innerHTML = "";
		document.getElementById('accepted-date').innerHTML = "";
		document.getElementById('published-date').innerHTML = "";
	}

	//******************************** dsArvData: ArvData Data Store ****************//
	dsArvData = new Ext.data.GroupingStore({
		id : 'dsArvDataId',
		proxy : new Ext.data.HttpProxy({
			url : baseUrl + 't_national_level_monthlystatus_ext_server.php',
			method : 'POST'
		}),

		baseParams : {
			action : "getArvData"
		},
		reader : new Ext.data.JsonReader({
			root : 'results',
			totalProperty : 'total',
			id : 'id'
		}, [{
			name : 'ARVDataId',
			type : 'int',
			mapping : 'CNMStockStatusId'
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
			name : 'ItemNo',
			type : 'int',
			mapping : 'ItemNo'
		}, {
			name : 'ItemName',
			type : 'string',
			mapping : 'ItemName'
		}, {
			name : 'OpStock_A',
			type : 'int',
			mapping : 'OpStock_A'
		}, {
			name : 'OpStock_C',
			type : 'int',
			mapping : 'OpStock_C'
		}, {
			name : 'ReceiveQty',
			type : 'int',
			mapping : 'ReceiveQty'
		}, {
			name : 'DispenseQty',
			type : 'int',
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
			type : 'int',
			mapping : 'AdjustQty'
		}, {
			name : 'AdjustReason',
			type : 'string',
			mapping : 'AdjustReason'
		}, {
			name : 'StockoutDays',
			type : 'int',
			mapping : 'StockoutDays'
		}, {
			name : 'ClStock_A',
			type : 'int',
			mapping : 'ClStock_A'
		}, {
			name : 'ClStock_C',
			type : 'int',
			mapping : 'ClStock_C'
		}, {
			name : 'MOS',
			type : 'float',
			mapping : 'MOS'
		}, {
			name : 'AMC',
			type : 'int',
			mapping : 'AMC'
		}, {
			name : 'MaxQty',
			type : 'int',
			mapping : 'MaxQty'
		}, {
			name : 'OrderQty',
			type : 'int',
			mapping : 'OrderQty'
		}, {
			name : 'ActualQty',
			type : 'int',
			mapping : 'ActualQty'
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
		}]),
		sortInfo : {
			field : 'ItemName',
			direction : "ASC"
		},
		groupField : 'FormulationName'
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

	//console.log(gItemGroupListArry);

	var cboCountry = new Ext.form.ComboBox({
		id : 'cboCountryId',
		displayField : 'CountryName',
		valueField : 'CountryId',
		store : new Ext.data.ArrayStore({
			autoDestroy : true,
			fields : ['CountryId', 'CountryName' , 'StartMonth', 'StartYear'],
			data : gCountryListArry
		}),

		mode : 'local',
		allowBlank : true,
		selectOnFocus : true,
		forceSelection : true,
		triggerAction : 'all',
		listeners : {
			select : function(field, rec, selIndex) {				
				resetStoreAndGrid();
				pCountryId = rec.get('CountryId');
				vFacilityId = pCountryId;
				vFacility = rec.get('CountryName');
				dsPatientOverview.setBaseParam('pCountryId', pCountryId);
				dsAdultRegimens.setBaseParam('pCountryId', pCountryId);
				dsArvData.setBaseParam('pCountryId', pCountryId);
				dsMasterStockData.setBaseParam('pCountryId', pCountryId);
				
				
				initMonthId = rec.get('StartMonth');
				initYearId = rec.get('StartYear')
			
				startDate = new Date(initYearId, initMonthId - 1);
				startManthName = startDate.getMonthName();
				
			}
		}
	});

	var cboItemGroup = new Ext.form.ComboBox({
		id : 'cboItemGroupId',
		displayField : 'GroupName',
		valueField : 'ItemGroupId',
		store : new Ext.data.ArrayStore({
			autoDestroy : true,
			fields : ['ItemGroupId', 'GroupName'],
			data : gItemGroupListArry
		}),

		mode : 'local',
		allowBlank : true,
		selectOnFocus : true,
		forceSelection : true,
		triggerAction : 'all',
		listeners : {
			select : function(field, rec, selIndex) {
				resetStoreAndGrid();
				pItemGroupId = rec.get('ItemGroupId');
                vItemId = pItemGroupId;
				vItem = rec.get('GroupName');
				dsPatientOverview.setBaseParam('pItemGroupId', pItemGroupId);
				dsAdultRegimens.setBaseParam('pItemGroupId', pItemGroupId);
				dsArvData.setBaseParam('pItemGroupId', pItemGroupId);
				dsMasterStockData.setBaseParam('pItemGroupId', pItemGroupId);
			}
		}
	});

	gridArvData = new Ext.grid.EditorGridPanel({
		id : 'gridArvDataId',
		style : 'text-align:left',
		stripeRows : true,
		store : dsArvData,
		clicksToEdit : 1,
		region : 'fit',
		loadMask : {
			msg : 'Loading data.',
			enabled : true
		},
		sm : new Ext.grid.RowSelectionModel({
			singleSelect : true,
			//moveEditorOnEnter: true
			moveEditorOnEnterLikeOnTab : true
		}),

		columns : [new Ext.grid.RowNumberer({
			header : '#'
		}), {
			header : "ARVDataId",
			width : 70,
			dataIndex : 'ARVDataId',
			sortable : true,
			hidden : true
		}, {
			header : "FacilityId",
			width : 70,
			align : 'left',
			dataIndex : 'FacilityId',
			sortable : true,
			hidden : true
		}, {
			header : "MonthId",
			width : 70,
			align : 'left',
			dataIndex : 'MonthId',
			sortable : true,
			hidden : true
		}, {
			header : "Year",
			width : 70,
			align : 'left',
			dataIndex : 'Year',
			sortable : true,
			hidden : true
		}, {
			header : "ItemNo",
			width : 70,
			align : 'left',
			dataIndex : 'ItemNo',
			sortable : true,
			hidden : true
		}, {
			header : TEXT["Item"] + "<br/>&nbsp",
			tooltip : "Item Name",
			width : 170,
			align : 'left',
			dataIndex : 'ItemName',
			sortable : true,
			hidden : false
		}, {
			header : "OBL(c)<br/>(A)",
			width : 70,
			align : 'right',
			dataIndex : 'OpStock_C',
			sortable : true,
			hidden : true,
			renderer : render_val
		}, {
			header : TEXT["OBL"] + "<br/>(A)",
			tooltip : "Opening Balance (OBL) -> (A)",
			width : 70,
			align : 'right',
			dataIndex : 'OpStock_A',
			sortable : true,
			hidden : false,
			renderer : function(value, metadata, record, rowIndex, colIndex, store) {
				if ((record.data.OpStock_A + record.data.ReceiveQty + record.data.AdjustQty - record.data.ClStock_A) != record.data.DispenseQty) {
					metadata.css = '';
				} else
					metadata.css = "arv-obla";
				if (value == 0) {
					return "";
				} else
					return Ext.util.Format.number(value, '0,000');
			},
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : true,
				maxValue : 10000000,
				selectOnFocus : true
			})
		}, {
			header : TEXT["Received"] + "<br/>(B)",
			tooltip : "Received Quantity -> (B)",
			width : 70,
			align : 'right',
			dataIndex : 'ReceiveQty',
			sortable : true,
			hidden : false,
			renderer : render_val,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 10000000,
				selectOnFocus : true
			})
		}, {
			header : TEXT["Dispensed"] + "<br/>(C)",
			tooltip : "Dispensed Quantity -> (C)",
			width : 70,
			align : 'right',
			dataIndex : 'DispenseQty',
			sortable : true,
			hidden : false,
			renderer : render_val,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 10000000,
				selectOnFocus : true
			})
		}, {
			header : "Last<br/>Month Dispensed",
			width : 70,
			align : 'right',
			dataIndex : 'lastMonthDispensed',
			sortable : true,
			hidden : true
		}, {
			header : "Before Last<br/>Month Dispensed",
			width : 70,
			align : 'right',
			dataIndex : 'BeforeLastMonthDispensed',
			sortable : true,
			hidden : true
		}, {
			header : TEXT["Adjusted"]+"<br/>(&#177;D)",
			tooltip : "Adjusted Quantity -> (&#177;D)",
			width : 70,
			align : 'right',
			dataIndex : 'AdjustQty',
			sortable : true,
			hidden : false,
			renderer : render_val,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : true,
				maxValue : 10000000,
				selectOnFocus : true,
				listeners : {
					blur : function(base, eOpts) {
					}
				}
			})
		}, {
			header : TEXT["Adjust"] + "<br/>" + TEXT["Reason"],
			tooltip : "Adjusting Reason",
			width : 100,
			align : 'left',
			dataIndex : 'AdjustId',
			sortable : true,
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
			tooltip : "Stock Out Days",
			width : 70,
			align : 'right',
			dataIndex : 'StockoutDays',
			sortable : true,
			hidden : false,
			renderer : render_val,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 30,
				selectOnFocus : true
			})
		}, {
			header : "CBL(c)<br/>(F=A+C+D+E)",
			width : 70,
			align : 'right',
			dataIndex : 'ClStock_C',
			sortable : true,
			hidden : true,
			renderer : function(value, metadata, record, rowIndex, colIndex, store) {((record.data.OpStock_A + record.data.ReceiveQty + record.data.AdjustQty - record.data.ClStock_A) != record.data.DispenseQty)

				metadata.css = "arv-cblc";
				if (value == 0) {
					return "";
				} else
					return Ext.util.Format.number(value, '0,000');
			}
		}, {
			header : TEXT["Closing Balance"] + "<br/>(E)",
			tooltip : "Closing Balance -> (E)",
			width : 70,
			align : 'right',
			dataIndex : 'ClStock_A',
			sortable : true,
			hidden : false,
			renderer : renderer_val,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 10000000,
				selectOnFocus : true
			})
		}, {
			header : TEXT["CL Stock"] + "<br/>" + TEXT["Source"],
			tooltip : "Closing balance stock source",
			width : 100,
			align : 'left',
			dataIndex : 'ClStockSourceId',
			sortable : true,
			hidden : false,
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
			header : TEXT["AMC"] + "<br/>(F)",
			tooltip : "Average Monthly Calculation (AMC) -> (G=(P2C+C)/3)",
			width : 70,
			align : 'right',
			dataIndex : 'AMC',
			sortable : true,
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
			renderer : render_val,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 10000000,
				selectOnFocus : true,
				listeners : {
					blur : function(base, eOpts) {
					}
				}
			})

		}, {
			header : TEXT["AMC Change"] + "<br/>" + TEXT["Reason"],
			tooltip : "AMC change reason",
			width : 100,
			align : 'left',
			dataIndex : 'AmcChangeReasonId',
			sortable : true,
			hidden : false,
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
			tooltip : "Month Of Supply (MOS) -> (G=E/F)",
			width : 70,
			align : 'right',
			dataIndex : 'MOS',
			sortable : true,
			hidden : false,
			renderer : function(value, metadata, record, rowIndex, colIndex, store) {((record.data.OpStock_A + record.data.ReceiveQty + record.data.AdjustQty - record.data.ClStock_A) != record.data.DispenseQty)

				metadata.css = "arv-mos";
				if (value == 0) {
					return "";
				} else
					return Ext.util.Format.number(value, '0.0');

			}
		}, {
			header : "Max Qty <br/>(H)",
			tooltip : "Maximum Quantity -> (H=G*3)",
			width : 70,
			align : 'right',
			dataIndex : 'MaxQty',
			sortable : true,
			hidden : true,
			renderer : function(value, metadata, record, rowIndex, colIndex, store) {((record.data.OpStock_A + record.data.ReceiveQty + record.data.AdjustQty - record.data.ClStock_A) != record.data.DispenseQty)

				metadata.css = "arv-maxqty";
				if (value == 0) {
					return "";
				} else
					return Ext.util.Format.number(value, '0,000');
			}
		}, {
			header : "Order Qty <br/>(I)",
			tooltip : "Order Quantity -> (I=H-E)",
			width : 70,
			align : 'right',
			dataIndex : 'OrderQty',
			sortable : true,
			hidden : true,
			renderer : function(value, metadata, record, rowIndex, colIndex, store) {((record.data.OpStock_A + record.data.ReceiveQty + record.data.AdjustQty - record.data.ClStock_A) != record.data.DispenseQty)

				metadata.css = "arv-orderqty";
				if (value == 0) {
					return "";
				} else
					return Ext.util.Format.number(value, '0,000');
			}
		}, {
			header : "Actual Order<br/>Qty (J)",
			tooltip : "Actual Order Quantity -> (J=H-E&#177;X)",
			width : 70,
			align : 'right',
			dataIndex : 'ActualQty',
			sortable : true,
			hidden : true,
			renderer : function(value, metadata, record, rowIndex, colIndex, store) {

				if (value <= 0) {
					return "";
				} else
					return Ext.util.Format.number(value, '0,000');
			},
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : true,
				maxValue : 10000000,
				selectOnFocus : true
			})
		}, {
			header : "UserId",
			width : 70,
			align : 'left',
			dataIndex : 'UserId',
			sortable : true,
			hidden : true,
			editor : new fm.NumberField({
				allowBlank : true,
				allowNegative : false,
				maxValue : 10000000
			})
		}, {
			header : "Formulation",
			width : 70,
			align : 'left',
			dataIndex : 'FormulationName',
			sortable : true,
			hidden : true
		}],

		view : new Ext.grid.GroupingView({
			forceFit : true,
			groupTextTpl : '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})',
			getRowClass : function(record, rowIndex) {
				if ((record.data.OpStock_A + record.data.ReceiveQty + record.data.AdjustQty - record.data.ClStock_A) != record.data.DispenseQty) {
					return 'clsinvalid';
				}
			}
		}),
		viewConfig : {
			emptyText : 'No rows to display'
		},
		width : '100%',
		buttons : [{
			text : 'Save',
			icon : baseUrl + 'images/save_soft.png',
			handler : saveAllRecords
		}],
		buttonAlign : 'right',
		autoHeight : true,
		align : 'center',
		columnLines : true
	});

	gridArvData.getSelectionModel().on("rowdeselect", saveGridRow);

	function saveGridRow(selModel, rowIndex, record) {
		gridArvData.stopEditing();
		var ed = gridArvData.activeEditor;
		var msgboxpos;
		var vMsg = '';
		var gposition = gridArvData.getPosition();

		if (record.dirty) {
			opStock_A = record.data.OpStock_A == "" ? 0 : record.data.OpStock_A;
			receiveQty = record.data.ReceiveQty == "" ? 0 : record.data.ReceiveQty;
			adjustQty = record.data.AdjustQty == "" ? 0 : record.data.AdjustQty;
			clStock_A = record.data.ClStock_A == "" ? 0 : record.data.ClStock_A;
			dispenseQty = record.data.DispenseQty == "" ? 0 : record.data.DispenseQty;
			adjustReason = record.data.AdjustReason == "" ? 0 : record.data.AdjustReason;

			if (adjustQty == 0 && adjustReason != '0') {
				msgboxpos = Ext.Msg.show({
					title : 'Data validation',
					msg : "Please fill adjust quantity first, then set the reason.",
					icon : Ext.Msg.QUESTION,
					buttons : Ext.Msg.OK,
					fn : function(response) {
						gridArvData.getSelectionModel().selectRow(r);
						gridArvData.startEditing(r, c);
					},
					scope : this
				}).getDialog();
				//msgboxpos.setPagePosition(gposition[0]+300,gposition[1]+90+ r * 22);
				//msgboxpos.focus();
				ed.completeEdit();
				return;
			}

			if (adjustQty != 0 && adjustReason == '0') {
				msgboxpos = Ext.Msg.show({
					title : 'Data validation',
					msg : "You must select a adjusting reason.",
					icon : Ext.Msg.QUESTION,
					buttons : Ext.Msg.OK,
					fn : function(response) {
						gridArvData.getSelectionModel().selectRow(r);
						gridArvData.startEditing(r, c);
					},
					scope : this
				}).getDialog();
				//msgboxpos.setPagePosition(gposition[0]+300,gposition[1]+90+ r * 22);
				//msgboxpos.focus();
				ed.completeEdit();
				return;
			}

			if ((opStock_A + receiveQty + adjustQty - clStock_A) != dispenseQty) {
				msgboxpos = Ext.Msg.show({
					title : TEXT['Data validation'],
					msg : TEXT['Msg_Validation'] + (opStock_A + receiveQty + adjustQty - dispenseQty),
					icon : Ext.Msg.QUESTION,
					buttons : Ext.Msg.OK,
					fn : function(response) {
						gridArvData.getSelectionModel().selectRow(r);
						gridArvData.startEditing(r, c);
					},
					scope : this
				}).getDialog();
				//msgboxpos.setPagePosition(gposition[0]+300,gposition[1]+90+ r * 22);
				//msgboxpos.focus();
				ed.completeEdit();

			} else {
				Ext.Ajax.request({
					waitMsg : 'Please wait...',
					url : baseUrl + 't_national_level_monthlystatus_ext_server.php',
					params : {
						action : "updateStockData",
						pARVDataId : vARVDataId,
						pOpStock_A : vOpStock_A,
						pReceiveQty : vReceiveQty,
						pDispenseQty : vDispenseQty,
						pAdjustQty : vAdjustQty,
						pAdjustReason : vAdjustReason,
						pStockoutDays : vStockoutDays,
						pOrderQty : vOrderQty,
						pMOS : vMOS,
						pAMC : vAMC,
						pAmcChangeReasonId : vAmcChangeReasonId,
						pClStock_C : vClStock_C,
						pClStock_A : vClStock_A,
						pClStockSourceId : vClStockSourceId,
						pMaxQty : vMaxQty,
						pOrderQty : vOrderQty,
						pActualQty : vActualQty,
						pFacilityId : pFacilityId,
						pMonthId : pMonthId,
						pYearId : pYearId,
						pReportId : pReportId,
						pUserId : pUserId
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
				r = -99;
			}
		}
	}


	gridArvData.on('afteredit', saveTheCell);

	//handler function for 'beforeedit' event
	function handler_to_makeReadOnly(e) {
		return bReadOnly;
	}

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
		minTabWidth : 135,
		bodyStyle : 'padding:0px; margin:0px; border:0px; background-color:#ff00ff;',
		enableTabScroll : true,
		items : [{
			title : TEXT['Patient Overview'],
			iconCls : 'icon-tab',
			id : "tabItemPatientOverviewId",
			width : '100%',
			autoHeight : true,
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
		}, {
			title : TEXT['Stock Status'],
			disabled : false,
			id : 'tabItemArvData',
			width : '100%',
			autoHeight : true,
			bodyStyle : 'padding:0px; margin:0px; border:0px; background-color:#DFE8F6;',
			items : [gridArvData]
		}]
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
			text : TEXT['Country']+':'
		}, {
			xtype : 'tbspacer',
			width : 10
		}, cboCountry, {
			xtype : 'tbspacer',
			width : 50
		}, {
			xtype : 'label',
			text : TEXT['Product Group']+':'
		}, {
			xtype : 'tbspacer',
			width : 10
		}, cboItemGroup]
	});

	tbar = new Ext.Toolbar({
		items : [{
			xtype : 'label',
			text :  TEXT['Month']+':'
		}, {
			xtype : 'tbspacer',
			width : 10
		}, {
			xtype : 'combo',
			hiddenName : 'hCboMonthId',
			id : 'cboMonthId',
			emptyText : TEXT['Select Month...'],
			fieldLabel : 'Month',
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
					//resetStoreAndGrid();
					pMonthId = rec.get('MonthId');
                    vMonthId = pMonthId;
					vMonth = rec.get('MonthName');
					currentDate.setMonth(rec.get('MonthId') - 1);
					dsPatientOverview.setBaseParam('pMonthId', pMonthId);
					dsAdultRegimens.setBaseParam('pMonthId', pMonthId);
					dsPaediatricRegimens.setBaseParam('pMonthId', pMonthId);
					dsOIsAndProphylaxis.setBaseParam('pMonthId', pMonthId);
					dsArvData.setBaseParam('pMonthId', pMonthId);
					dsMasterStockData.setBaseParam('pMonthId', pMonthId);
					//////getReportId();
				}
			}
		}, {
			xtype : 'tbspacer',
			width : 10
		}, '-', '-', {
			xtype : 'tbspacer',
			width : 10
		}, {
			xtype : 'label',
			text : TEXT['Year']+':'
		}, {
			xtype : 'tbspacer',
			width : 10
		}, {
			xtype : 'combo',
			id : 'cboYearId',
			hiddenName : 'hCboYearId',
			mode : 'local',
			triggerAction : 'all',
			emptyText : TEXT['Select Year...'],
			fieldLabel : 'Year',
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
					currentDate.setYear(rec.get('YearId'));
					dsPatientOverview.setBaseParam('pYearId', pYearId);
					dsAdultRegimens.setBaseParam('pYearId', pYearId);
					dsPaediatricRegimens.setBaseParam('pYearId', pYearId);
					dsOIsAndProphylaxis.setBaseParam('pYearId', pYearId);
					dsArvData.setBaseParam('pYearId', pYearId);
					dsMasterStockData.setBaseParam('pYearId', pYearId);
					////getReportId();
				}
			}
		}, {
			xtype : 'tbspacer',
			width : 10
		}, '-', '-', {
			xtype : 'tbspacer',
			width : 10
		}, {
			xtype : 'button',
			text : TEXT['Show'],
			//iconCls: 'add16',
			icon : baseUrl + 'images/show_soft.png',
			handler : function() {
				vMonthId = Ext.getCmp('cboMonthId').getValue();
				vYearId = Ext.getCmp('cboYearId').getValue();
				vCountryId = Ext.getCmp('cboCountryId').getValue();
				vItemGroupId = Ext.getCmp('cboItemGroupId').getValue();

				var cm = Ext.getCmp('gridArvDataId').getColumnModel();
				var valCol = cm.getColumnAt(8);
				//alert(vMonthId);
				if (vMonthId == initMonthId && vYearId == initYearId) {
					valCol.editable = true;
				} else
					valCol.editable = false;

				vMonth = Ext.getCmp('cboMonthId').getRawValue();
				vYear = Ext.getCmp('cboYearId').getRawValue();
				
				vCountry = Ext.getCmp('cboCountryId').getRawValue();
				vItemGroup = Ext.getCmp('cboItemGroupId').getRawValue();

				if (vCountryId == "" || vItemGroupId == "" || vMonthId == "" || vYearId == "") {
					var msgbox = Ext.Msg.show({
						msg : TEXT['Msg_Select'],
						icon : Ext.Msg.INFO,
						minWidth : 200,
						buttons : Ext.Msg.OK,
						scope : this
					}).getDialog();
					//msgbox.setPagePosition(300,150);

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
					//msgbox.setPagePosition(300,150);
					return;
				} else if (selYearId == selInitYearId && selMonth < selInitMonthId) {
					var msgbox = Ext.Msg.show({
						msg : 'Starting Month-Year for ARV Data is ' + startManthName + '-' + initYearId + '',
						icon : Ext.Msg.INFO,
						minWidth : 200,
						buttons : Ext.Msg.OK,
						scope : this
					}).getDialog();
					//msgbox.setPagePosition(300,150);
					return;
				} else if (selYearId == selInitYearId && selMonth == selInitMonthId) {
					dsMasterStockData.load();
				} else {
					getFacilityRecordOfPrevMonth();
				}
			}
		}, {
			xtype : 'tbspacer',
			width : 10
		}, '-', '-', {
			xtype : 'tbspacer',
			width : 10
		}, {
			text : TEXT['Delete'],
			icon : baseUrl + 'images/i_drop.png',
			style : 'float:right;',
			handler : function() {
				vMonthId = Ext.getCmp('cboMonthId').getValue();
				vYearId = Ext.getCmp('cboYearId').getValue();
				vCountryId = Ext.getCmp('cboCountryId').getValue();
				vItemGroupId = Ext.getCmp('cboItemGroupId').getValue();
				
				vMonth = Ext.getCmp('cboMonthId').getRawValue();
				vYear = Ext.getCmp('cboYearId').getRawValue();
				
				vCountry = Ext.getCmp('cboCountryId').getRawValue();
				vItemGroup = Ext.getCmp('cboItemGroupId').getRawValue();

				if (vCountryId == "" || vItemGroupId == "" || vMonthId == "" || vYearId == "") {
					var msgbox = Ext.Msg.show({
						msg : TEXT['Msg_Select'],
						icon : Ext.Msg.INFO,
						minWidth : 200,
						buttons : Ext.Msg.OK,
						scope : this
					}).getDialog();
					//msgbox.setPagePosition(200,150);
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
								url : baseUrl + 't_national_level_monthlystatus_ext_server.php',
								params : {
									'action' : "delete_data_from_yyyy",
									'pFacilityId' : pFacilityId,
									'pMonthId' : pMonthId,
									'pYearId' : pYearId,
									'pCountryId' : pCountryId,
									'pItemGroupId' : pItemGroupId
								},
								success : function(response) {
									eval(response.responseText);
									if (success == 1) {
										clearMasterInfo();
										elePanelMaster.hide();
										Ext.getCmp('submitid').enable();
										var msgbox = Ext.Msg.show({
											msg : 'All records of ' + vFacility + ' heve been deleted on ' + vMonth + '-' + vYear + '.',
											icon : Ext.Msg.INFO,
											minWidth : 200,
											buttons : Ext.Msg.OK,
											scope : this
										}).getDialog();
										//msgbox.setPagePosition(200,150);
										pReportId = 0;
										loadAllData();
									}
								}
							});
						}
					}
				}).getDialog();
				//msgbox.setPagePosition(200,150);

			}
		}, {
			xtype : 'tbspacer',
			width : 10
		}, '-', '-', {
			xtype : 'tbspacer',
			width : 10
		}, {
			text : TEXT['Save'],
			icon : baseUrl + 'images/save_soft.png',
			handler : saveAllRecords
		}]

	});

	function getFacilityRecordOfPrevMonth() {
		Ext.Ajax.request({
			waitMsg : 'Please wait...',
			url : baseUrl + 't_national_level_monthlystatus_ext_server.php',
			params : {
				'action' : "getFacilityRecordOfPrevMonth",
				'pFacilityId' : pFacilityId,
				'pMonthId' : pMonthId,
				'pYearId' : pYearId,
				'pCountryId' : pCountryId,
				'pItemGroupId' : pItemGroupId

			},
			success : function(response) {
				eval(response.responseText);

				//alert(totalrec);

				if (totalrec > 0) {

					dsMasterStockData.load();

				} else if (totalprevnonreported <= 0) {
					var msgbox = Ext.Msg.show({
						msg : TEXT['Msg_NoReport'],
						icon : Ext.Msg.QUESTION,
						minWidth : 200,
						buttons : Ext.Msg.OK,
						scope : this
					}).getDialog();
					//msgbox.setPagePosition(300,150);
					return;
					// }
				} else if (totalsubmitted > 0) {
					var msgbox = Ext.Msg.show({
						msg : TEXT['Msg_UnPublished'],
						icon : Ext.Msg.QUESTION,
						minWidth : 200,
						buttons : Ext.Msg.OK,
						scope : this
					}).getDialog();
					//msgbox.setPagePosition(300,150);
					return;
					// }
				} else
					dsMasterStockData.load();
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
				//msgboxpos.setPosition(gposition[0]+300,gposition[1]+90+ r * 22);
			}
		});
		if (invalidMsgCount == 0) {
			Ext.Ajax.request({
				url : baseUrl + 't_national_level_monthlystatus_ext_server.php',
				params : {
					action : "updateStockDataAll",
					pFacilityId : pFacilityId,
					pMonthId : pMonthId,
					pYearId : pYearId,
					pReportId : pReportId,
					pUserId : pUserId,
					data : Ext.encode(data)
				},
				success : function() {
					gridArvData.getStore().commitChanges();
					gridArvData.getView().refresh();
					//r = -99;
					data = [];

					var msgboxpos1 = Ext.Msg.show({
						title : TEXT['Success Message'],
						msg : TEXT["Records updated successfully"],
						icon : Ext.Msg.QUESTION,
						buttons : Ext.Msg.OK
					}).getDialog();
					//msgboxpos1.setPosition(gposition[0]+300,gposition[1]+90+ r * 22);

				},
				failure : function() {
					//...
				}
			});
		}
	}

	function getFacilityRecordOfThisMonth(reportId) {
		if (reportId <= 0) {
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
			//msgbox.setPagePosition(200,150);
		} else {
			//getReportId();
			loadAllData();
		}
	}

	function insertIntoStockData() {
		Ext.Ajax.request({
			waitMsg : 'Please wait...',
			url : baseUrl + 't_national_level_monthlystatus_ext_server.php',
			params : {
				'action' : "insertIntoStockData",
				'pFacilityId' : pFacilityId,
				'pMonthId' : pMonthId,
				'pYearId' : pYearId,
				'pFormulationId' : 1,
				'pUserId' : pUserId,
				'pCountryId' : pCountryId,
				'pItemGroupId' : pItemGroupId
			},
			success : function(response) {
				///eval(response.responseText);
				dsMasterStockData.load();
				loadAllData();
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
				start : 0,
				limit : 15
			}
		});

		dsPaediatricRegimens.load({
			params : {
				'pFacilityId' : pFacilityId,
				'pMonthId' : pMonthId,
				'pYearId' : pYearId,
				'pCountryId' : pCountryId,
				'pItemGroupId' : pItemGroupId,
				'pFormulationId' : 2,
				start : 0,
				limit : 15
			}
		});

		dsOIsAndProphylaxis.load({
			params : {
				'pFacilityId' : pFacilityId,
				'pMonthId' : pMonthId,
				'pYearId' : pYearId,
				'pCountryId' : pCountryId,
				'pItemGroupId' : pItemGroupId,
				'pFormulationId' : 3,
				start : 0,
				limit : 15
			}
		});

		dsArvData.load({
			params : {
				start : 0,
				limit : 60
			}
		});
	}

	function resetStoreAndGrid() {
		// elePanelMaster.hide();
		//
		// if (gridPatientOverview.getStore().getCount() != 0){
		// dsPatientOverview.removeAll(true);
		// gridPatientOverview.getView().refresh();
		// }
		//
		// if (gridAdultRegimens.getStore().getCount() != 0){
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

	var panelParams = new Ext.Panel({
		id : 'panelParamsId',
		frame : true,
		height : 40,
		layout : 'auto',
		region : 'north',
		items : [tbar2]

	});

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
		if (pStatusId == 1)
			pStatusId = 2;
		else if (pStatusId == 2)
			pStatusId = 3;
		else if (pStatusId == 3)
			pStatusId = 5;

		var msgbox = Ext.Msg.show({
			msg : 'Once you submit this report, it can no longer be edited.Continue and submit?',
			icon : Ext.Msg.QUESTION,
			minWidth : 200,
			buttons : Ext.Msg.YESNO,
			scope : this,
			fn : function(response) {
				if ('yes' == response) {
					Ext.Ajax.request({
						waitMsg : 'Please wait...',
						url : baseUrl + 't_national_level_monthlystatus_ext_server.php',
						params : {
							action : "changeBsubmittedInMaster",
							'pFacilityId' : pFacilityId,
							'pMonthId' : pMonthId,
							'pYearId' : pYearId,
							'pReportId' : pReportId,
							'pUserId' : pUserId,
							'pCountryId' : pCountryId,
							'pItemGroupId' : pItemGroupId,
							'pStatusId' : pStatusId,

						},
						success : function(response) {
							//alert(pUserId);
							dsMasterStockData.load();
							var msgbox = Ext.Msg.show({
								msg : 'Your report submitted successfully.',
								icon : Ext.Msg.INFO,
								minWidth : 200,
								buttons : Ext.Msg.OK,
								scope : this
							}).getDialog();
							//msgbox.setPagePosition(300,150);
						}
					});
				}
			}
		}).getDialog();
		//msgbox.setPagePosition(300,150);
	}

	var panelSubmit = new Ext.Panel({
		id : 'panelSubmitId',
		frame : true,
		height : 75,
		layout : 'auto',
		region : 'north',
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

	panelParams.render('panel-params');
	panelCombo.render('panelCombo');
	//panelMaster.render('panelMaster');
	tabArvdata.render('tabArvdata');
	panelSubmit.render('panelSubmit');

	$('#submitid button').attr('id', 'btn-sub-acc-pub');
}); 