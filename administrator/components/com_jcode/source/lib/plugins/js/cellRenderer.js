function addCommas(nStr) {
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
/*function formatDouble (val){
	return  addCommas(val.toFixed(2));
}*/
function formatDouble (val){
	var stringArray=new Array();
	var tempVal=val+'.00';
	stringArray=tempVal.split(".");
	var tempSubstr=stringArray[1].substring(0,2);
	
	return addCommas(stringArray[0]+'.'+tempSubstr);
}



function add(mm,dd,yy,addNumber)
{ 
 
 if(mm=='Jan') {var temp_m='01'};
 if(mm=='Feb') {var temp_m='02'};
 if(mm=='Mar') {var temp_m='03'};
 if(mm=='Apr') {var temp_m='04'};
 if(mm=='May') {var temp_m='05'};
 if(mm=='Jun') {var temp_m='06'};
 if(mm=='Jul') {var temp_m='07'};
 if(mm=='Aug') {var temp_m='08'};
 if(mm=='Sep') {var temp_m='09'};
 if(mm=='Oct') {var temp_m='10'};
 if(mm=='Nov') {var temp_m='11'};
 if(mm=='Dec') {var temp_m='12'};
	
 if(addNumber==-1)
 {
	date=dd+'/'+temp_m+'/'+yy;	 
 }
 else
 {   
	 var total=(parseInt(temp_m)+Math.floor((addNumber)/30));
	 var date;
	 if(total>12)
	 {
		var month=total-12;
		var year=parseInt(yy)+1;
		date=dd+'/0'+month+'/'+year;
	 }
	 else if(total==12||total==11||total==10) date=dd+'/'+total+'/'+yy;
	 else date=dd+'/0'+total+'/'+yy;
 }
  return date;
}


function multilineColumn(value, metaData, record, rowIndex, colIndex, store) {
                    metaData.css = 'multilineColumn';
					if(value==0) return ' ';
					else return value;
                }
				
function formInvalidationMsg(x,y,messg,leng)
{
    var msgobj=Ext.Msg.show({ msg:messg,
				   icon: Ext.Msg.INFO,
				   width:leng,
				   buttons: Ext.Msg.OK,
				   closable:false
				}).getDialog().setPagePosition(x,y);
		return msgobj;
}

function formResetFunction(formobj)
{
	var blankformobj=formobj.getForm().items.each(function(f){ f.originalValue=''; formobj.form.reset(); });
	return blankformobj;
}
