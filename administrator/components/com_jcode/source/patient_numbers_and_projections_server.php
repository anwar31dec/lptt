<?php
include_once ("function_lib.php");

$task = '';
if (isset($_REQUEST['operation'])) {
	$task = $_REQUEST['operation'];
}

switch($task) {
	case "getPatientNumbersAndProjections" :
		getPatientNumbersAndProjections();
		break;	
	default :
		echo "{failure:true}";
		break;
}



function getPatientNumbersAndProjections() {			
	echo '{
	"sEcho": ' . intval($_GET['sEcho']) .',
	"iTotalRecords": "10",
	"iTotalDisplayRecords": "10",
	"aaData": [["1", "ABACAVIR(300mg); 60 Tabs", "5867", "1205", "3600", "14460", "3600", "2400"],
			["2", "ABACAVIR(20mg/ml); 240 Susp", "7629", "1503", "4505", "18036", "4500", "3000"],
			["3", "DIDANOSINE(400mg); 30 Caps", "9754", "1806", "5452", "21672", "5400", "3600"],
			["4", "DIDANOSINE(25mg); 60 Tabs", "2589", "920", "2720", "11040", "2700", "1800"],
			["5", "DIDANOSINE(250mg); 30 Caps", "3467", "1234", "3623", "14808", "3700", "2400"],
			["6", "LOPINAVIR/RITONAVIR(200/50mg); 120 Tabs", "8765", "1342", "4005", "16104", "4000", "2600"],
			["7", "CO-TRIMOXAZOLE(100/20mg); 100 Tabs", "4532", "1532", "4560", "18384", "4500", "3000"],
			["8", "CO-TRIMOXAZOLE(240mg/5ml); 100 Susp", "9876", "2637", "7900", "31644", "7900", "5200"],
			["9", "LAMIVUDINE(50mg/5ml); 100 Susp", "12543", "673", "1890", "8076", "2000", "1300"],
			["10", "DIDANOSINE(200mg); 30 Caps", "87654", "1235", "3635", "14820", "3700", "2400"]
		]
	}';	
	
	//echo '{"sEcho":' . intval($_GET['sEcho']) .',"iTotalRecords":"189","iTotalDisplayRecords":"54","aaData":[["Population","93,25000"],["Population growth rate","2.9%"],["Language","French"],["Life Expectancy","44"],["GDP per capita","567.91"],["Expenditure of health","39"],["Human Index Ranking","0.436 (166)"],["HIV Prevalence","1.4"],["Number of people living with HIV","73,000"],["Number of people on HIV Treatment","37,000"],["HIV Treatment Gap","49,000"],["Percentage of eligible people receiving HIV treatment","32%"],["New HIV infections among adults","6,000"],["New HIV infections among Children","<1,000"],["PMTCT coverage","1,115"],["Number of AIDS-related death","3,400"],["% of people on treatment 12 months after initiation of ART","93%"],["% of HIV-Positive incidents TB Cases that received treatment for Both TB and HIV.","57%"],["Number of ART sites",""],["Number of  PMTCT sites",""],["HIV Testing, Multiple sexual Partnership and Condom Use",""],["Percentage of Sex Workers reached with HIV Prevention program",""],["Percentage of Sex workers who are living with HIV",""],["Percentage of Infant Born to HIV-Positive Women receiving a Virological Test for HIV within 2 months of Birth",""],["Estimated Children receiving and needing Antiretroval Therapy, and Coverage.",""],["HIV+TB Patients on ART",""],["Number of HIV Infected Female Adults",""],["Population",""],["Population growth rate",""],["Language",""],["Life Expectancy",""],["GDP per capita",""],["Expenditure of health",""],["Human Index Ranking",""],["HIV Prevalence",""],["Number of people living with HIV",""],["Number of people on HIV Treatment",""],["HIV Treatment Gap",""],["Percentage of eligible people receiving HIV treatment",""],["New HIV infections among adults",""],["New HIV infections among Children",""],["PMTCT coverage",""],["Number of AIDS-related death",""],["% of people on treatment 12 months after initiation of ART",""],["% of HIV-Positive incidents TB Cases that received treatment for Both TB and HIV.",""],["Number of ART sites",""],["Number of  PMTCT sites",""],["HIV Testing, Multiple sexual Partnership and Condom Use",""],["Percentage of Sex Workers reached with HIV Prevention program",""],["Percentage of Sex workers who are living with HIV",""],["Percentage of Infant Born to HIV-Positive Women receiving a Virological Test for HIV within 2 months of Birth",""],["Estimated Children receiving and needing Antiretroval Therapy, and Coverage.",""],["HIV+TB Patients on ART",""],["Number of HIV Infected Female Adults",""]]}';
}
?>