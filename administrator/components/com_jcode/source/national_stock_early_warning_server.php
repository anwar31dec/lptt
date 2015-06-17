<?php
include_once ("function_lib.php");

$task = '';
if (isset($_REQUEST['operation'])) {
	$task = $_REQUEST['operation'];
}

switch($task) {
	case "getNationalStockEarlyWarning" :
		getNationalStockEarlyWarning();
		break;	
	default :
		echo "{failure:true}";
		break;
}



function getNationalStockEarlyWarning() {			
	echo '{
	"sEcho": ' . intval($_GET['sEcho']) .',
	"iTotalRecords": "10",
	"iTotalDisplayRecords": "10",
	"aaData": [["1", "ABACAVIR(300mg); 60 Tabs", "34507", "16082", "2876", "5.59", "Very high risk"],
			["2", "ABACAVIR(20mg/ml); 240 Susp", "44220", "25795", "3685", "7.00", "Very high risk"],
			["3", "DIDANOSINE(400mg); 30 Caps", "43212", "24787", "3601", "6.88", "Very high risk"],
			["4", "DIDANOSINE(25mg); 60 Tabs", "48320", "29895", "4027", "7.42", "Very high risk"],
			["5", "DIDANOSINE(250mg); 30 Caps", "47502", "29077", "3959", "7.35", "Very high risk"],
			["6", "LOPINAVIR/RITONAVIR(200/50mg); 120 Tabs", "36412", "17987", "3034", "5.93", "Very high risk"],
			["7", "CO-TRIMOXAZOLE(100/20mg); 100 Tabs", "22790", "4365", "1899", "2.30", "Very high risk"],
			["8", "CO-TRIMOXAZOLE(240mg/5ml); 100 Susp", "28305", "9880", "2359", "4.19", "Very high risk"],
			["9", "LAMIVUDINE(50mg/5ml); 100 Susp", "33526", "15101", "2794", "5.41", "Very high risk"],
			["10", "DIDANOSINE(200mg); 30 Caps", "37506", "19081", "3126", "6.10", "Very high risk"]
		]
	}';	
	
	//echo '{"sEcho":' . intval($_GET['sEcho']) .',"iTotalRecords":"189","iTotalDisplayRecords":"54","aaData":[["Population","93,25000"],["Population growth rate","2.9%"],["Language","French"],["Life Expectancy","44"],["GDP per capita","567.91"],["Expenditure of health","39"],["Human Index Ranking","0.436 (166)"],["HIV Prevalence","1.4"],["Number of people living with HIV","73,000"],["Number of people on HIV Treatment","37,000"],["HIV Treatment Gap","49,000"],["Percentage of eligible people receiving HIV treatment","32%"],["New HIV infections among adults","6,000"],["New HIV infections among Children","<1,000"],["PMTCT coverage","1,115"],["Number of AIDS-related death","3,400"],["% of people on treatment 12 months after initiation of ART","93%"],["% of HIV-Positive incidents TB Cases that received treatment for Both TB and HIV.","57%"],["Number of ART sites",""],["Number of  PMTCT sites",""],["HIV Testing, Multiple sexual Partnership and Condom Use",""],["Percentage of Sex Workers reached with HIV Prevention program",""],["Percentage of Sex workers who are living with HIV",""],["Percentage of Infant Born to HIV-Positive Women receiving a Virological Test for HIV within 2 months of Birth",""],["Estimated Children receiving and needing Antiretroval Therapy, and Coverage.",""],["HIV+TB Patients on ART",""],["Number of HIV Infected Female Adults",""],["Population",""],["Population growth rate",""],["Language",""],["Life Expectancy",""],["GDP per capita",""],["Expenditure of health",""],["Human Index Ranking",""],["HIV Prevalence",""],["Number of people living with HIV",""],["Number of people on HIV Treatment",""],["HIV Treatment Gap",""],["Percentage of eligible people receiving HIV treatment",""],["New HIV infections among adults",""],["New HIV infections among Children",""],["PMTCT coverage",""],["Number of AIDS-related death",""],["% of people on treatment 12 months after initiation of ART",""],["% of HIV-Positive incidents TB Cases that received treatment for Both TB and HIV.",""],["Number of ART sites",""],["Number of  PMTCT sites",""],["HIV Testing, Multiple sexual Partnership and Condom Use",""],["Percentage of Sex Workers reached with HIV Prevention program",""],["Percentage of Sex workers who are living with HIV",""],["Percentage of Infant Born to HIV-Positive Women receiving a Virological Test for HIV within 2 months of Birth",""],["Estimated Children receiving and needing Antiretroval Therapy, and Coverage.",""],["HIV+TB Patients on ART",""],["Number of HIV Infected Female Adults",""]]}';
}
?>