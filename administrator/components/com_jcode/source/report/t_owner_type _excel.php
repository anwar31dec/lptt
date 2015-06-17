<?php
include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());

/*
header('Content-type: application/excel');
$filename = 'OwnerTypeName.xls';
header('Content-Disposition: attachment; filename='.$filename);

echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel"><head>
    <!--[if gte mso 9]>
    <xml>
        <x:ExcelWorkbook>
            <x:ExcelWorksheets>
                <x:ExcelWorksheet>
                    <x:Name>Sheet 1</x:Name>
                    <x:WorksheetOptions>
                        <x:Print>
                            <x:ValidPrinterInfo/>
                        </x:Print>
                    </x:WorksheetOptions>
                </x:ExcelWorksheet>
            </x:ExcelWorksheets>
        </x:ExcelWorkbook>
    </xml>
    <![endif]-->
</head>
<body><table  class="table table-striped display" width="100%" border="0.5" style="margin:0 auto;"> <thead><tr role="row"><th aria-label="SL#: activate to sort column ascending" colspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" rowspan="2" style="text-align: center; width: 44px;"><div class="DataTables_sort_wrapper">SL#<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="Product Name: activate to sort column descending" aria-sort="ascending" colspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" rowspan="2" style="text-align: center; width: 150px;"><div class="DataTables_sort_wrapper">Product Name<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span></div></th><th rowspan="1" colspan="3" style="text-align:center;">Central Warehouse </th><th rowspan="1" colspan="3" style="text-align:center;">Regional Warhouse</th><th rowspan="1" colspan="3" style="text-align:center;">District Warehouse</th><th rowspan="1" colspan="3" style="text-align:center;">Health Faclilities</th><th rowspan="1" colspan="3" style="text-align:center;">National</th></tr><tr role="row"><th aria-label="AMC: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 60px;"><div class="DataTables_sort_wrapper">AMC<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="SOH: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 57px;"><div class="DataTables_sort_wrapper">SOH<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="MOS: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 61px;"><div class="DataTables_sort_wrapper">MOS<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="AMC: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 60px;"><div class="DataTables_sort_wrapper">AMC<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="SOH: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 58px;"><div class="DataTables_sort_wrapper">SOH<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="MOS: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 62px;"><div class="DataTables_sort_wrapper">MOS<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="AMC: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 59px;"><div class="DataTables_sort_wrapper">AMC<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="SOH: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 57px;"><div class="DataTables_sort_wrapper">SOH<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="MOS: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 61px;"><div class="DataTables_sort_wrapper">MOS<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="AMC: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 53px;"><div class="DataTables_sort_wrapper">AMC<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="SOH: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 51px;"><div class="DataTables_sort_wrapper">SOH<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="MOS: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 54px;"><div class="DataTables_sort_wrapper">MOS<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="AMC: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 53px;"><div class="DataTables_sort_wrapper">AMC<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="SOH: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 51px;"><div class="DataTables_sort_wrapper">SOH<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="MOS: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 55px;"><div class="DataTables_sort_wrapper">MOS<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th></tr></thead><tbody aria-relevant="all" aria-live="polite" role="alert"><tr class="odd"><td class="">1</td><td class="">Arthémether + Luméfantrine /Plq de 12,</td><td class="">3,250</td><td class="">10,000</td><td class="">3.1</td><td class="">3,250</td><td class="">5,000</td><td class="">1.5</td><td class="">3,250</td><td class="">1,000</td><td class="">0.3</td><td class="">3,250</td><td class="">2,450</td><td class="">0.8</td><td class="">3,250</td><td class="">18,450</td><td class="">5.7</td></tr><tr class="even"><td class="">2</td><td class="">Arthémether + Luméfantrine /Plq de 18,</td><td class="">4,300</td><td class="">10,000</td><td class="">2.3</td><td class=""></td><td class=""></td><td class=""></td><td class="">4,300</td><td class="">13,000</td><td class="">3.0</td><td class="">4,300</td><td class="">2,800</td><td class="">0.7</td><td class="">4,300</td><td class="">25,800</td><td class="">6.0</td></tr><tr class="odd"><td class="">3</td><td class="">Arthémether + Luméfantrine /Plq de 24,</td><td class="">3,200</td><td class="">30,000</td><td class="">9.4</td><td class="">3,200</td><td class="">16,000</td><td class="">5.0</td><td class="">3,200</td><td class="">15,000</td><td class="">4.7</td><td class="">3,200</td><td class="">6,500</td><td class="">2.0</td><td class="">3,200</td><td class="">67,500</td><td class="">21.1</td></tr><tr class="even"><td class="">4</td><td class="">Arthémether + Luméfantrine /Plq de 6,</td><td class="">1,200</td><td class="">5,000</td><td class="">4.2</td><td class="">1,200</td><td class="">22,000</td><td class="">18.3</td><td class="">1,200</td><td class="">17,000</td><td class="">14.2</td><td class="">1,200</td><td class="">5,400</td><td class="">4.5</td><td class="">1,200</td><td class="">49,400</td><td class="">41.2</td></tr><tr class="odd"><td class="">5</td><td class="">AS/AQ 100mg/270mg</td><td class="">2,100</td><td class="">30,000</td><td class="">14.3</td><td class="">2,100</td><td class="">19,000</td><td class="">9.0</td><td class="">2,100</td><td class="">30,000</td><td class="">14.3</td><td class="">2,100</td><td class="">12,400</td><td class="">5.9</td><td class="">2,100</td><td class="">91,400</td><td class="">43.5</td></tr><tr class="even"><td class="">6</td><td class="">AS/AQ 25mg/67.5mg</td><td class="">1,500</td><td class="">10,000</td><td class="">6.7</td><td class="">1,500</td><td class="">18,000</td><td class="">12.0</td><td class="">1,500</td><td class="">13,000</td><td class="">8.7</td><td class="">1,500</td><td class="">11,100</td><td class="">7.4</td><td class="">1,500</td><td class="">52,100</td><td class="">34.7</td></tr><tr class="odd"><td class="">7</td><td class="">AS/AQ 50mg/135mg</td><td class="">900</td><td class="">10,000</td><td class="">11.1</td><td class="">900</td><td class="">4,000</td><td class="">4.4</td><td class="">900</td><td class="">5,000</td><td class="">5.6</td><td class="">900</td><td class="">10,800</td><td class="">12.0</td><td class="">900</td><td class="">29,800</td><td class="">33.1</td></tr><tr class="even"><td class="">8</td><td class="">Atesunate </td><td class="">3,100</td><td class="">16,000</td><td class="">5.2</td><td class="">3,100</td><td class="">20,000</td><td class="">6.5</td><td class="">3,100</td><td class="">11,000</td><td class="">3.5</td><td class="">3,100</td><td class="">12,200</td><td class="">3.9</td><td class="">3,100</td><td class="">59,200</td><td class="">19.1</td></tr><tr class="odd"><td class="">9</td><td class="">Nets</td><td class="">4,300</td><td class="">26,000</td><td class="">6.0</td><td class="">4,300</td><td class="">19,000</td><td class="">4.4</td><td class="">4,300</td><td class="">11,000</td><td class="">2.6</td><td class="">4,300</td><td class="">10,700</td><td class="">2.5</td><td class="">4,300</td><td class="">66,700</td><td class="">15.5</td></tr><tr class="even"><td class="">10</td><td class="">Quinine 200mg 2ml</td><td class="">3,800</td><td class="">55,000</td><td class="">14.5</td><td class="">3,800</td><td class="">20,000</td><td class="">5.3</td><td class="">3,800</td><td class="">20,000</td><td class="">5.3</td><td class="">3,800</td><td class="">4,600</td><td class="">1.2</td><td class="">3,800</td><td class="">99,600</td><td class="">26.2</td></tr><tr class="odd"><td class="">11</td><td class="">Quinine 300mg</td><td class="">4,300</td><td class="">26,000</td><td class="">6.0</td><td class="">4,300</td><td class="">14,000</td><td class="">3.3</td><td class="">4,300</td><td class="">18,000</td><td class="">4.2</td><td class="">4,300</td><td class="">5,400</td><td class="">1.3</td><td class="">4,300</td><td class="">63,400</td><td class="">14.7</td></tr><tr class="even"><td class="">12</td><td class="">Quinine 400mg 4ml</td><td class="">1,950</td><td class="">26,000</td><td class="">13.3</td><td class="">1,950</td><td class="">20,000</td><td class="">10.3</td><td class="">1,950</td><td class="">13,000</td><td class="">6.7</td><td class="">1,950</td><td class="">12,450</td><td class="">6.4</td><td class="">1,950</td><td class="">71,450</td><td class="">36.6</td></tr><tr class="odd"><td class="">13</td><td class="">RDTs</td><td class="">1,050</td><td class="">19,000</td><td class="">18.1</td><td class="">1,050</td><td class="">14,000</td><td class="">13.3</td><td class="">1,050</td><td class="">18,000</td><td class="">17.1</td><td class="">1,050</td><td class="">13,450</td><td class="">12.8</td><td class="">1,050</td><td class="">64,450</td><td class="">61.4</td></tr><tr class="even"><td class="">14</td><td class="">SP</td><td class="">4,100</td><td class="">1,000</td><td class="">0.2</td><td class="">4,100</td><td class="">22,000</td><td class="">5.4</td><td class="">4,100</td><td class="">13,000</td><td class="">3.2</td><td class="">4,100</td><td class="">6,400</td><td class="">1.6</td><td class="">4,100</td><td class="">42,400</td><td class="">10.3</td></tr><tr class="odd"><td class="">15</td><td class="">SP+Amodiaquine 500md/25md/150mg</td><td class="">5,600</td><td class="">13,000</td><td class="">2.3</td><td class="">5,600</td><td class="">31,000</td><td class="">5.5</td><td class="">5,600</td><td class="">17,000</td><td class="">3.0</td><td class="">5,600</td><td class="">6,800</td><td class="">1.2</td><td class="">5,600</td><td class="">67,800</td><td class="">12.1</td></tr></tbody> </table></body></html>';


return;
*/
/*
$file="demo.xlsx";
//$test="<table  ><tr><td>Cell 1</td><td>Cell 2</td></tr></table>";
//header("Content-type: application/vnd.ms-excel");
//header("Content-Disposition: attachment; filename=$file");
header('Content-Type: application/force-download');
header('Content-disposition: attachment; filename=export.xls');
//echo $test;
echo '<table  class="table table-striped display" width="100%" border="0.5" style="margin:0 auto;"> <thead><tr role="row"><th aria-label="SL#: activate to sort column ascending" colspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" rowspan="2" style="text-align: center; width: 44px;"><div class="DataTables_sort_wrapper">SL#<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="Product Name: activate to sort column descending" aria-sort="ascending" colspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" rowspan="2" style="text-align: center; width: 150px;"><div class="DataTables_sort_wrapper">Product Name<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span></div></th><th rowspan="1" colspan="3" style="text-align:center;">Central Warehouse </th><th rowspan="1" colspan="3" style="text-align:center;">Regional Warhouse</th><th rowspan="1" colspan="3" style="text-align:center;">District Warehouse</th><th rowspan="1" colspan="3" style="text-align:center;">Health Faclilities</th><th rowspan="1" colspan="3" style="text-align:center;">National</th></tr><tr role="row"><th aria-label="AMC: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 60px;"><div class="DataTables_sort_wrapper">AMC<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="SOH: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 57px;"><div class="DataTables_sort_wrapper">SOH<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="MOS: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 61px;"><div class="DataTables_sort_wrapper">MOS<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="AMC: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 60px;"><div class="DataTables_sort_wrapper">AMC<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="SOH: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 58px;"><div class="DataTables_sort_wrapper">SOH<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="MOS: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 62px;"><div class="DataTables_sort_wrapper">MOS<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="AMC: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 59px;"><div class="DataTables_sort_wrapper">AMC<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="SOH: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 57px;"><div class="DataTables_sort_wrapper">SOH<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="MOS: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 61px;"><div class="DataTables_sort_wrapper">MOS<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="AMC: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 53px;"><div class="DataTables_sort_wrapper">AMC<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="SOH: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 51px;"><div class="DataTables_sort_wrapper">SOH<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="MOS: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 54px;"><div class="DataTables_sort_wrapper">MOS<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="AMC: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 53px;"><div class="DataTables_sort_wrapper">AMC<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="SOH: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 51px;"><div class="DataTables_sort_wrapper">SOH<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="MOS: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-patient-trend-time-series" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 55px;"><div class="DataTables_sort_wrapper">MOS<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th></tr></thead><tbody aria-relevant="all" aria-live="polite" role="alert"><tr class="odd"><td class="">1</td><td class="">Arthémether + Luméfantrine /Plq de 12,</td><td class="">3,250</td><td class="">10,000</td><td class="">3.1</td><td class="">3,250</td><td class="">5,000</td><td class="">1.5</td><td class="">3,250</td><td class="">1,000</td><td class="">0.3</td><td class="">3,250</td><td class="">2,450</td><td class="">0.8</td><td class="">3,250</td><td class="">18,450</td><td class="">5.7</td></tr><tr class="even"><td class="">2</td><td class="">Arthémether + Luméfantrine /Plq de 18,</td><td class="">4,300</td><td class="">10,000</td><td class="">2.3</td><td class=""></td><td class=""></td><td class=""></td><td class="">4,300</td><td class="">13,000</td><td class="">3.0</td><td class="">4,300</td><td class="">2,800</td><td class="">0.7</td><td class="">4,300</td><td class="">25,800</td><td class="">6.0</td></tr><tr class="odd"><td class="">3</td><td class="">Arthémether + Luméfantrine /Plq de 24,</td><td class="">3,200</td><td class="">30,000</td><td class="">9.4</td><td class="">3,200</td><td class="">16,000</td><td class="">5.0</td><td class="">3,200</td><td class="">15,000</td><td class="">4.7</td><td class="">3,200</td><td class="">6,500</td><td class="">2.0</td><td class="">3,200</td><td class="">67,500</td><td class="">21.1</td></tr><tr class="even"><td class="">4</td><td class="">Arthémether + Luméfantrine /Plq de 6,</td><td class="">1,200</td><td class="">5,000</td><td class="">4.2</td><td class="">1,200</td><td class="">22,000</td><td class="">18.3</td><td class="">1,200</td><td class="">17,000</td><td class="">14.2</td><td class="">1,200</td><td class="">5,400</td><td class="">4.5</td><td class="">1,200</td><td class="">49,400</td><td class="">41.2</td></tr><tr class="odd"><td class="">5</td><td class="">AS/AQ 100mg/270mg</td><td class="">2,100</td><td class="">30,000</td><td class="">14.3</td><td class="">2,100</td><td class="">19,000</td><td class="">9.0</td><td class="">2,100</td><td class="">30,000</td><td class="">14.3</td><td class="">2,100</td><td class="">12,400</td><td class="">5.9</td><td class="">2,100</td><td class="">91,400</td><td class="">43.5</td></tr><tr class="even"><td class="">6</td><td class="">AS/AQ 25mg/67.5mg</td><td class="">1,500</td><td class="">10,000</td><td class="">6.7</td><td class="">1,500</td><td class="">18,000</td><td class="">12.0</td><td class="">1,500</td><td class="">13,000</td><td class="">8.7</td><td class="">1,500</td><td class="">11,100</td><td class="">7.4</td><td class="">1,500</td><td class="">52,100</td><td class="">34.7</td></tr><tr class="odd"><td class="">7</td><td class="">AS/AQ 50mg/135mg</td><td class="">900</td><td class="">10,000</td><td class="">11.1</td><td class="">900</td><td class="">4,000</td><td class="">4.4</td><td class="">900</td><td class="">5,000</td><td class="">5.6</td><td class="">900</td><td class="">10,800</td><td class="">12.0</td><td class="">900</td><td class="">29,800</td><td class="">33.1</td></tr><tr class="even"><td class="">8</td><td class="">Atesunate </td><td class="">3,100</td><td class="">16,000</td><td class="">5.2</td><td class="">3,100</td><td class="">20,000</td><td class="">6.5</td><td class="">3,100</td><td class="">11,000</td><td class="">3.5</td><td class="">3,100</td><td class="">12,200</td><td class="">3.9</td><td class="">3,100</td><td class="">59,200</td><td class="">19.1</td></tr><tr class="odd"><td class="">9</td><td class="">Nets</td><td class="">4,300</td><td class="">26,000</td><td class="">6.0</td><td class="">4,300</td><td class="">19,000</td><td class="">4.4</td><td class="">4,300</td><td class="">11,000</td><td class="">2.6</td><td class="">4,300</td><td class="">10,700</td><td class="">2.5</td><td class="">4,300</td><td class="">66,700</td><td class="">15.5</td></tr><tr class="even"><td class="">10</td><td class="">Quinine 200mg 2ml</td><td class="">3,800</td><td class="">55,000</td><td class="">14.5</td><td class="">3,800</td><td class="">20,000</td><td class="">5.3</td><td class="">3,800</td><td class="">20,000</td><td class="">5.3</td><td class="">3,800</td><td class="">4,600</td><td class="">1.2</td><td class="">3,800</td><td class="">99,600</td><td class="">26.2</td></tr><tr class="odd"><td class="">11</td><td class="">Quinine 300mg</td><td class="">4,300</td><td class="">26,000</td><td class="">6.0</td><td class="">4,300</td><td class="">14,000</td><td class="">3.3</td><td class="">4,300</td><td class="">18,000</td><td class="">4.2</td><td class="">4,300</td><td class="">5,400</td><td class="">1.3</td><td class="">4,300</td><td class="">63,400</td><td class="">14.7</td></tr><tr class="even"><td class="">12</td><td class="">Quinine 400mg 4ml</td><td class="">1,950</td><td class="">26,000</td><td class="">13.3</td><td class="">1,950</td><td class="">20,000</td><td class="">10.3</td><td class="">1,950</td><td class="">13,000</td><td class="">6.7</td><td class="">1,950</td><td class="">12,450</td><td class="">6.4</td><td class="">1,950</td><td class="">71,450</td><td class="">36.6</td></tr><tr class="odd"><td class="">13</td><td class="">RDTs</td><td class="">1,050</td><td class="">19,000</td><td class="">18.1</td><td class="">1,050</td><td class="">14,000</td><td class="">13.3</td><td class="">1,050</td><td class="">18,000</td><td class="">17.1</td><td class="">1,050</td><td class="">13,450</td><td class="">12.8</td><td class="">1,050</td><td class="">64,450</td><td class="">61.4</td></tr><tr class="even"><td class="">14</td><td class="">SP</td><td class="">4,100</td><td class="">1,000</td><td class="">0.2</td><td class="">4,100</td><td class="">22,000</td><td class="">5.4</td><td class="">4,100</td><td class="">13,000</td><td class="">3.2</td><td class="">4,100</td><td class="">6,400</td><td class="">1.6</td><td class="">4,100</td><td class="">42,400</td><td class="">10.3</td></tr><tr class="odd"><td class="">15</td><td class="">SP+Amodiaquine 500md/25md/150mg</td><td class="">5,600</td><td class="">13,000</td><td class="">2.3</td><td class="">5,600</td><td class="">31,000</td><td class="">5.5</td><td class="">5,600</td><td class="">17,000</td><td class="">3.0</td><td class="">5,600</td><td class="">6,800</td><td class="">1.2</td><td class="">5,600</td><td class="">67,800</td><td class="">12.1</td></tr></tbody> </table>';
/*

/*
$table = 't_owner_type';
$outstr = NULL;

header("Content-Type: application/csv");
header("Content-Disposition: attachment;Filename=cars-models.xlsx");

//$conn = mysql_connect("localhost", "mysql_user", "mysql_password");
//mysql_select_db("db",$conn);

// Query database to get column names 
$result = mysql_query("show columns from $table",$conn);
// Write column names
while($row = mysql_fetch_array($result)){
    $outstr.= $row['Field'].',';
} 
$outstr = substr($outstr, 0, -1)."\n";

// Query database to get data
$result = mysql_query("select * from $table",$conn);
// Write data rows
while ($row = mysql_fetch_assoc($result)) {
    $outstr.= join(',', $row)."\n";
}

echo $outstr;
//mysql_close($conn);

*/
/*

$table = 't_owner_type';
$outstr = NULL;

header("Content-Type: application/csv");
header("Content-Disposition: attachment;Filename=cars-models.csv");

//$conn = mysql_connect("localhost", "mysql_user", "mysql_password");
//mysql_select_db("db",$conn);

// Query database to get column names 
$result = mysql_query("show columns from $table",$conn);
// Write column names
while($row = mysql_fetch_array($result)){
    $outstr.= $row['Field'].',';
} 
$outstr = substr($outstr, 0, -1)."\n";

// Query database to get data
$result = mysql_query("select * from $table",$conn);
// Write data rows
while ($row = mysql_fetch_assoc($result)) {
    $outstr.= join(',', $row)."\n";
}

//echo $outstr;
echo '<table  class="table table-striped display" width="100%" border="0.5" style="margin:0 auto;"> 
						<thead>
							<tr role="row"><th colspan="1" rowspan="1" role="columnheader" class="sorting_disabled" style="width: 73px;">SL.</th><th colspan="1" rowspan="1" role="columnheader" class="sorting_disabled" style="width: 404px;">Products</th><th colspan="1" rowspan="1" role="columnheader" class="sorting_disabled right-aln" style="width: 222px;">Reported Closing Balance</th><th colspan="1" rowspan="1" role="columnheader" class="sorting_disabled right-aln" style="width: 222px;">Average Monthly Consumption</th><th colspan="1" rowspan="1" role="columnheader" class="sorting_disabled right-aln" style="width: 221px;">MOS</th></tr>
						</thead>
						
					<tbody aria-relevant="all" aria-live="polite" role="alert"><tr class="odd"><td class="">1</td><td class="">Arthémether + Luméfantrine /Plq de 24,</td><td class="right-aln">72,500</td><td class="right-aln">5,100</td><td class="right-aln">14.2</td></tr><tr class="even"><td class="">2</td><td class="">Arthémether + Luméfantrine /Plq de 18,</td><td class="right-aln">44,100</td><td class="right-aln">4,500</td><td class="right-aln">9.8</td></tr><tr class="odd"><td class="">3</td><td class="">Arthémether + Luméfantrine /Plq de 12,</td><td class="right-aln">40,750</td><td class="right-aln">3,475</td><td class="right-aln">11.7</td></tr><tr class="even"><td class="">4</td><td class="">Arthémether + Luméfantrine /Plq de 6,</td><td class="right-aln">65,900</td><td class="right-aln">3,100</td><td class="right-aln">21.3</td></tr><tr class="odd"><td class="">5</td><td class="">AS/AQ 25mg/67.5mg</td><td class="right-aln">53,900</td><td class="right-aln">4,350</td><td class="right-aln">12.4</td></tr><tr class="even"><td class="">6</td><td class="">AS/AQ 50mg/135mg</td><td class="right-aln">46,100</td><td class="right-aln">5,050</td><td class="right-aln">9.1</td></tr><tr class="odd"><td class="">7</td><td class="">AS/AQ 100mg/270mg</td><td class="right-aln">134,200</td><td class="right-aln">6,150</td><td class="right-aln">21.8</td></tr><tr class="even"><td class="">8</td><td class="">Quinine 300mg</td><td class="right-aln">75,400</td><td class="right-aln">6,150</td><td class="right-aln">12.3</td></tr><tr class="odd"><td class="">9</td><td class="">Quinine 200mg 2ml</td><td class="right-aln">83,600</td><td class="right-aln">6,400</td><td class="right-aln">13.1</td></tr><tr class="even"><td class="">10</td><td class="">Quinine 400mg 4ml</td><td class="right-aln">83,950</td><td class="right-aln">4,725</td><td class="right-aln">17.8</td></tr><tr class="odd"><td class="">11</td><td class="">Atesunate</td><td class="right-aln">74,200</td><td class="right-aln">5,550</td><td class="right-aln">13.4</td></tr><tr class="even"><td class="">12</td><td class="">Nets</td><td class="right-aln">61,600</td><td class="right-aln">6,700</td><td class="right-aln">9.2</td></tr><tr class="odd"><td class="">13</td><td class="">SP+Amodiaquine 500md/25md/150mg</td><td class="right-aln">38,800</td><td class="right-aln">6,800</td><td class="right-aln">5.7</td></tr><tr class="even"><td class="">14</td><td class="">SP</td><td class="right-aln">53,600</td><td class="right-aln">5,850</td><td class="right-aln">9.2</td></tr><tr class="odd"><td class="">15</td><td class="">RDTs</td><td class="right-aln">71,950</td><td class="right-aln">4,275</td><td class="right-aln">16.8</td></tr></tbody> </table>';
mysql_close($conn);


*/


/* Receive all parameter*/
$jBaseUrl = $_REQUEST['jBaseUrl'];
$reportSaveName = $_REQUEST['reportSaveName'];
$reportHeaderList = json_decode($_REQUEST['reportHeaderList'], true );
array_unshift($reportHeaderList,'Health Commodity Dashboard');

$parameterList = array();
$parameterList['action'] = $_REQUEST['action'];
$parameterList['lan'] = $_REQUEST['lan'];
$parameterList['tableHeaderList'] = json_decode($_REQUEST['tableHeaderList'], true );
$parameterList['groupBySqlIndex'] = ($_REQUEST['groupBySqlIndex'] == '')? -1 : $_REQUEST['groupBySqlIndex'];
$parameterList['tableHeaderWidth'] = json_decode($_REQUEST['tableHeaderWidth'], true );
$parameterList['alignment'] = array("numeric"=>"right","string"=>"left",""=>"center");

$parameterList['dataType'] = json_decode($_REQUEST['dataType'], true );
$parameterList['sqlParameterList'] = json_decode($_REQUEST['sqlParameterList'], true );
$chart = $_REQUEST['chart'];
//====================================Dynamic Design======================================
$reportHeaderListCount = count($reportHeaderList);


$tableHeaderListCount =3;// count($parameterList['tableHeaderList']);

$cellIdentifire = array("1"=>"A","2"=>"B","3"=>"C","4"=>"D","5"=>"E","6"=>"F","7"=>"G","8"=>"H","9"=>"I","10"=>"J","11"=>"K","12"=>"L","13"=>"M","14"=>"N","15"=>"O","16"=>"P","17"=>"Q","18"=>"R","19"=>"S","20"=>"T","21"=>"U","22"=>"V","23"=>"W","24"=>"X","25"=>"Y","26"=>"Z");
//print_r($cellIdentifire);
//$ItemGroupName = $_GET['ItemGroupName'];
 /*	
	$sWhere = "";
	if ($_GET['sSearch'] != "") {
		$sWhere = " AND  (MosTypeName LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%'
							OR " . " MinMos LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' 
							OR " . " MaxMos LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' 
							OR " . " ColorCode LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' ) ";							
	}

*/
   $sql = "SELECT  OwnerTypeId, OwnerTypeName, OwnerTypeNameFrench
				FROM t_owner_type; ";

	 $r= mysql_query($sql);
	 $total = mysql_num_rows($r);
	 
	 if ($total>0){
        require('../lib/PHPExcel.php');	
        $objPHPExcel = new PHPExcel();
	
	//$pFilename='E://xampp/htdocs/ospsante/administrator/components/com_jcode/source/report/pdfslice/htmlTable.html';
	////PHPExcel_Reader_HTML::loadIntoExisting($pFilename,$objPHPExcel);
	//$reader = new PHPExcel_Reader_HTML; 
	//$content = $reader->load($pFilename); 
	//return;
	/*	
// Load the table view into a variable
$data = 'softworks';
$html = $this->load->view('table_view', $data, true);

// Put the html into a temporary file
$tmpfile = time().'.html';
file_put_contents($tmpfile, $html);

// Read the contents of the file into PHPExcel Reader class
$reader = new PHPExcel_Reader_HTML; 
$content = $reader->load($tmpfile); 

// Pass to writer and output as needed
$objWriter = PHPExcel_IOFactory::createWriter($content, 'Excel2007');
$objWriter->save('excelfile.xlsx');

// Delete temporary file
unlink($tmpfile);
return;


*/


//file_put_contents('tmp.html','<table border="1"><tr><td>123</td></tr></table>');
//$objReader = new PHPExcel_Reader_HTML;

//$objPHPExcel = $objReader->load('tmp.html');

//$dom = new domDocument;
// Reload the HTML file into the DOM object
//$loaded = $dom->loadHTMLFile('./pdfslice/htmlTable.html');
  
//$o = load('./pdfslice/htmlTable.html');
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$objWriter->save("myExcelFile.xlsx");

//$objReader = PHPExcel_IOFactory::createReader('HTML');
//$objPHPExcel = $objReader->load("./pdfslice/htmlTable.html");

 //$dom = new domDocument;
 // // Reload the HTML file into the DOM object
  //$loaded = $dom->loadHTMLFile("./pdfslice/htmlTable.html");
 // if ($loaded === FALSE) {
 // throw new PHPExcel_Reader_Exception('Failed to load ',$pFilename,' as a DOM Document');
 //}
// print_r($dom);
//  // Discard white space
//  $dom->preserveWhiteSpace = false;

//return;




/*
//Logo start
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing -> setPath('../images/logo.png');
	$objDrawing -> setCoordinates('A1');
	$objDrawing -> setWorksheet($objPHPExcel -> getActiveSheet());
//Logo end	
*/
	
	
	
	
	
	
	//$objReader = new PHPExcel_Reader_HTML;
	////$objReader -> loadHTMLFile('./pdfslice/htmlTable.html');
	
	
////$file = $DOCUMENT_ROOT. "test.html";
//$doc = new DOMDocument();
//$loder = $doc->loadHTMLFile('./pdfslice/htmlTable.html');
//$loder -> setWorksheet($objPHPExcel -> getActiveSheet());
	//$doc -> setCoordinates('A140');
	//$doc -> setWorksheet($objPHPExcel -> getActiveSheet());
	
	 // Create a new DOM object
  /*$dom = new domDocument;
  // Reload the HTML file into the DOM object
  $loaded = $dom->loadHTMLFile('./pdfslice/htmlTable.html');
  if ($loaded === FALSE) {
  throw new PHPExcel_Reader_Exception('Failed to load ',$pFilename,' as a DOM Document');
  }
  
 
  // Discard white space
  $dom->preserveWhiteSpace = false;
 $row = 0;
  $column = 'A4';
  $content = '';
  $dom->_processDomElement($dom,$objPHPExcel->getActiveSheet(),$row,$column,$content);
*/

//$objReader = setHTML('<Table style="border-collapse: collapse;" cellspacing="0" cellpadding="4"><THead><TR><TH style="background-color:#F8F8F8; border:1px solid #CCCCCC ; width:100px "  rowspan="5" >1</TH><TH style="background-color:#F8F8F8; border:1px solid #CCCCCC ; width:100px "  rowspan="5" >2</TH><TH style="background-color:#F8F8F8; border:1px solid #CCCCCC ; width:100px "  rowspan="5" >3</TH><TH style="background-color:#F8F8F8; border:1px solid #CCCCCC "  colspan="7"  rowspan="1" >4</TH><TH style="background-color:#F8F8F8; border:1px solid #CCCCCC ; width:100px "  rowspan="5" >16</TH></TR><TR><TH style="background-color:#F8F8F8; border:1px solid #CCCCCC "  rowspan="1" >5</TH><TH style="background-color:#F8F8F8; border:1px solid #CCCCCC ; width:100px "  rowspan="4" >7</TH><TH style="background-color:#F8F8F8; border:1px solid #CCCCCC "  colspan="5"  rowspan="1" >8</TH></TR><TR><TH style="background-color:#F8F8F8; border:1px solid #CCCCCC ; width:100px "  rowspan="3" >6</TH><TH style="background-color:#F8F8F8; border:1px solid #CCCCCC ; width:100px "  rowspan="3" >9</TH><TH style="background-color:#F8F8F8; border:1px solid #CCCCCC ; width:100px "  rowspan="3" >10</TH><TH style="background-color:#F8F8F8; border:1px solid #CCCCCC "  colspan="3"  rowspan="1" >11</TH></TR><TR><TH style="background-color:#F8F8F8; border:1px solid #CCCCCC "  colspan="2"  rowspan="1" >12</TH><TH style="background-color:#F8F8F8; border:1px solid #CCCCCC ; width:100px "  rowspan="2" >15</TH></TR><TR><TH style="background-color:#F8F8F8; border:1px solid #CCCCCC ; width:100px " >13</TH><TH style="background-color:#F8F8F8; border:1px solid #CCCCCC ; width:100px " >14</TH></TR></THead></Table>');
//$objReader ->  setPath('./pdfslice/htmlTable.txt');
//$objReader -> setWorksheet($objPHPExcel -> getActiveSheet());
	
	//$tmpfile = '<html><body><tr><td>I am rubel</td><td>softworks</td></tr></body></html>';
	//$reader = new PHPExcel_Reader_HTML; 
	//$content = $reader->load($tmpfile); 
		
		//Report Header Start
	/*	//$objPHPExcel->getActiveSheet()->SetCellValue('A2','Owner Type List');
		$objPHPExcel->getActiveSheet()->SetCellValue($cellIdentifire[1].'2',$reportHeaderList[0]); //A2
		$styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
		$objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '18', 'bold' => true)), 'A2');		
		$objPHPExcel -> getActiveSheet() -> mergeCells('A2:'.$cellIdentifire[$tableHeaderListCount].'2');//mergeCells('A2:C2')
		
	
		$objPHPExcel->getActiveSheet()->SetCellValue($cellIdentifire[1].'3',$reportHeaderList[1]); //A3
		//$styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
		$objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A3');						
		//$objPHPExcel -> getActiveSheet() -> mergeCells('A3:F3');
		$objPHPExcel -> getActiveSheet() -> mergeCells('A3:'.$cellIdentifire[$tableHeaderListCount].'3');//mergeCells('A2:C2')
*/
	for($i=1;$i<=$reportHeaderListCount;$i++){
		//$objPHPExcel->getActiveSheet()->SetCellValue('A2','Health Commodity Dashboard');
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i,$reportHeaderList[$i-1]);
		$styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
		//$objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel -> getActiveSheet() -> getStyle('A'.$i) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFont()->setBold(true);
		//$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '18', 'bold' => true)), 'A2');
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => (18-$i), 'bold' => true)), 'A'.$i);
		//$objPHPExcel -> getActiveSheet() -> mergeCells('A2:C2');
		$objPHPExcel -> getActiveSheet() -> mergeCells('A'.$i.':'.$cellIdentifire[$tableHeaderListCount].$i);//mergeCells('A2:C2')
	}
//Report Header End
		
		
		
		//Table Header Initialize start
	  //  $objPHPExcel->getActiveSheet()
	//							->SetCellValue('A6', 'SL.')
		//						->SetCellValue('B6','Owner Type')
		//						->SetCellValue('C6','Owner Type (French)');
		
		//$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
		//$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
		//$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
		
		
		//Column settings start
			//$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(18);
			//$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(18);
			//$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(18);
		//Column settings end
		
		//Table header Line color start		
		// $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'F00000') )));
 
	    //$objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
	   // $objPHPExcel -> getActiveSheet() -> getStyle('A6'  . ':A6') -> applyFromArray($styleThinBlackBorderOutline);
	   // $objPHPExcel -> getActiveSheet() -> getStyle('B6'  . ':B6') -> applyFromArray($styleThinBlackBorderOutline);
	    //$objPHPExcel -> getActiveSheet() -> getStyle('C6'  . ':C6') -> applyFromArray($styleThinBlackBorderOutline);
		//Table header Line color end    
		
		//Table Header settings start		
			//$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
			//$objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
			//$objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
		//Table Header settings end
		
		for($i=1;$i<=$tableHeaderListCount;$i++){				
			$objPHPExcel->getActiveSheet()->SetCellValue($cellIdentifire[$i].($reportHeaderListCount+2), $parameterList['tableHeaderList'][$i-1]);
			$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), $cellIdentifire[$i] . ($reportHeaderListCount+2));
			$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellIdentifire[$i]) -> setWidth(18);
			$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i].($reportHeaderListCount+2)  . ':'.$cellIdentifire[$i] . ($reportHeaderListCount+2)) -> applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i].($reportHeaderListCount+2))->getFont()->setBold(true);
		}
		
		
		
		//$objPHPExcel->getActiveSheet()
		//			->SetCellValue('A'.$j, $i)							
		//			->SetCellValue('B'.$j, $rec['OwnerTypeName'])
		//			->SetCellValue('C'.$j, $rec['OwnerTypeNameFrench']); 
		
		////Table body Line color start    
		//	 $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		 //    $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		//	 $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		////Table body Line color end   
		
		//Get data from database start
	   //$i=1; $j=7;
	   $sl = 1;
	   $cell = ($reportHeaderListCount+3); //Start table body
	  $tableFieldList = array("sl", "OwnerTypeName", "OwnerTypeNameFrench");

       while($rec=mysql_fetch_array($r)){
	      	//$objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  //  $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	    //    $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	      
			//$tableFieldCount = count($tableFieldList[0]);
			
			for($i=1; $i <= count($tableFieldList); $i++){
				
				if($i == 1)
					$objPHPExcel->getActiveSheet()->SetCellValue($cellIdentifire[$i].$cell, $sl);
				else
					$objPHPExcel->getActiveSheet()->SetCellValue($cellIdentifire[$i].$cell, $rec[$tableFieldList[$i-1]]);
					
				$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i] . $cell . ':' . $cellIdentifire[$i].$cell) -> applyFromArray($styleThinBlackBorderOutline);//Table body Line color     $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			}
			
								
			  			
		  //  $c=explode('#', $rec['ColorCode']);
		//    $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
		//				'fill' => array(
		//			'type' => PHPExcel_Style_Fill::FILL_SOLID,
		//			'color' => array('rgb'=>$c[1]),
		//		          )
		 //          );
		//	$objPHPExcel->getActiveSheet()
		//								->SetCellValue('F'.$j, $rec['MosLabel']);
			
			//$i++; $j++;
			$cell++; $sl++;
		}
	 //Get data from database end
	 
	// $objPHPExcel->getActiveSheet()
	// ->SetCellValue('A145', '<table  class="table table-striped display" width="100%" border="0.5" style="margin:0 auto;"> 
	//					<thead>
	//						<tr role="row"><th colspan="1" rowspan="1" role="columnheader" class="sorting_disabled" style="width: 73px;">SL.</th><th colspan="1" rowspan="1" role="columnheader" class="sorting_disabled" style="width: 404px;">Products</th><th colspan="1" rowspan="1" role="columnheader" class="sorting_disabled right-aln" style="width: 222px;">Reported Closing Balance</th><th colspan="1" rowspan="1" role="columnheader" class="sorting_disabled right-aln" style="width: 222px;">Average Monthly Consumption</th><th colspan="1" rowspan="1" role="columnheader" class="sorting_disabled right-aln" style="width: 221px;">MOS</th></tr>
	//					</thead>
	//					
	//				<tbody aria-relevant="all" aria-live="polite" role="alert"><tr class="odd"><td class="">1</td><td class="">Arthémether + Luméfantrine /Plq de 24,</td><td class="right-aln">72,500</td><td class="right-aln">5,100</td><td class="right-aln">14.2</td></tr><tr class="even"><td class="">2</td><td class="">Arthémether + Luméfantrine /Plq de 18,</td><td class="right-aln">44,100</td><td class="right-aln">4,500</td><td class="right-aln">9.8</td></tr><tr class="odd"><td class="">3</td><td class="">Arthémether + Luméfantrine /Plq de 12,</td><td class="right-aln">40,750</td><td class="right-aln">3,475</td><td class="right-aln">11.7</td></tr><tr class="even"><td class="">4</td><td class="">Arthémether + Luméfantrine /Plq de 6,</td><td class="right-aln">65,900</td><td class="right-aln">3,100</td><td class="right-aln">21.3</td></tr><tr class="odd"><td class="">5</td><td class="">AS/AQ 25mg/67.5mg</td><td class="right-aln">53,900</td><td class="right-aln">4,350</td><td class="right-aln">12.4</td></tr><tr class="even"><td class="">6</td><td class="">AS/AQ 50mg/135mg</td><td class="right-aln">46,100</td><td class="right-aln">5,050</td><td class="right-aln">9.1</td></tr><tr class="odd"><td class="">7</td><td class="">AS/AQ 100mg/270mg</td><td class="right-aln">134,200</td><td class="right-aln">6,150</td><td class="right-aln">21.8</td></tr><tr class="even"><td class="">8</td><td class="">Quinine 300mg</td><td class="right-aln">75,400</td><td class="right-aln">6,150</td><td class="right-aln">12.3</td></tr><tr class="odd"><td class="">9</td><td class="">Quinine 200mg 2ml</td><td class="right-aln">83,600</td><td class="right-aln">6,400</td><td class="right-aln">13.1</td></tr><tr class="even"><td class="">10</td><td class="">Quinine 400mg 4ml</td><td class="right-aln">83,950</td><td class="right-aln">4,725</td><td class="right-aln">17.8</td></tr><tr class="odd"><td class="">11</td><td class="">Atesunate</td><td class="right-aln">74,200</td><td class="right-aln">5,550</td><td class="right-aln">13.4</td></tr><tr class="even"><td class="">12</td><td class="">Nets</td><td class="right-aln">61,600</td><td class="right-aln">6,700</td><td class="right-aln">9.2</td></tr><tr class="odd"><td class="">13</td><td class="">SP+Amodiaquine 500md/25md/150mg</td><td class="right-aln">38,800</td><td class="right-aln">6,800</td><td class="right-aln">5.7</td></tr><tr class="even"><td class="">14</td><td class="">SP</td><td class="right-aln">53,600</td><td class="right-aln">5,850</td><td class="right-aln">9.2</td></tr><tr class="odd"><td class="">15</td><td class="">RDTs</td><td class="right-aln">71,950</td><td class="right-aln">4,275</td><td class="right-aln">16.8</td></tr></tbody> </table>'); 
	 
	 
	 
	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	
	
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = $reportSaveName.'_'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);

    }
    else{
   	    echo 'No record found';
    }



?>