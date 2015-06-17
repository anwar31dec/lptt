<?php
include_once ("function_lib.php");

$task = '';
if (isset($_REQUEST['operation'])) {
	$task = $_REQUEST['operation'];
}

switch($task) {
	case "getRiskProducts" :
		getRiskProducts();
		break;	
	default :
		echo "{failure:true}";
		break;
}



function getRiskProducts() {			
	echo '{
	"sEcho": ' . intval($_GET['sEcho']) .',
	"iTotalRecords": "10",
	"iTotalDisplayRecords": "10",
	"aaData": [["1", "Abacavir /Lamivudine 600/300 mg tab", "3,700", "2900", "0.78", "Guinea"],
			["2", "Nevirapine 200MG/tab", "5,655", "562", "0.09", "Benin"],
			["3", "Lamivudine-Zidovudine 150+300MG/tab", "6,554", "6,321", "0.96", "Burkina Faso"],
			["4", "Indinavir 400mg/tab", "5,654", "8,985", "1.58", "Burkina Faso"],
			["5", "Ritonavir 100MG/cap", "3,200", "12,300", "3.84", "Burkina Faso"],
			["6", "Efavirenz 600MG/tab", "5,020", "9,200", "1.83", "Togo"],
			["7", "Abacavir/Lamivudine/Zidovudine 300mg/150mg/300mg /tab", "560", "2,900", "5.2", "Togo"],
			["8", "Lamivudine-Zidovudine-Nevirapine 150+300+200MG/tab", "13,800", "48,600", "3.52", "Togo"],
			["9", "Didanosine 25MG/tab", "784", "900", "1.14", "Togo"],
			["10", "Tenofovir/Lamivudine 300/300 MG/tab", "9,000", "12700", "1.41", "Togo"]
		]
	}';	
	
	//echo '{"sEcho":' . intval($_GET['sEcho']) .',"iTotalRecords":"189","iTotalDisplayRecords":"54","aaData":[["Population","93,25000"],["Population growth rate","2.9%"],["Language","French"],["Life Expectancy","44"],["GDP per capita","567.91"],["Expenditure of health","39"],["Human Index Ranking","0.436 (166)"],["HIV Prevalence","1.4"],["Number of people living with HIV","73,000"],["Number of people on HIV Treatment","37,000"],["HIV Treatment Gap","49,000"],["Percentage of eligible people receiving HIV treatment","32%"],["New HIV infections among adults","6,000"],["New HIV infections among Children","<1,000"],["PMTCT coverage","1,115"],["Number of AIDS-related death","3,400"],["% of people on treatment 12 months after initiation of ART","93%"],["% of HIV-Positive incidents TB Cases that received treatment for Both TB and HIV.","57%"],["Number of ART sites",""],["Number of  PMTCT sites",""],["HIV Testing, Multiple sexual Partnership and Condom Use",""],["Percentage of Sex Workers reached with HIV Prevention program",""],["Percentage of Sex workers who are living with HIV",""],["Percentage of Infant Born to HIV-Positive Women receiving a Virological Test for HIV within 2 months of Birth",""],["Estimated Children receiving and needing Antiretroval Therapy, and Coverage.",""],["HIV+TB Patients on ART",""],["Number of HIV Infected Female Adults",""],["Population",""],["Population growth rate",""],["Language",""],["Life Expectancy",""],["GDP per capita",""],["Expenditure of health",""],["Human Index Ranking",""],["HIV Prevalence",""],["Number of people living with HIV",""],["Number of people on HIV Treatment",""],["HIV Treatment Gap",""],["Percentage of eligible people receiving HIV treatment",""],["New HIV infections among adults",""],["New HIV infections among Children",""],["PMTCT coverage",""],["Number of AIDS-related death",""],["% of people on treatment 12 months after initiation of ART",""],["% of HIV-Positive incidents TB Cases that received treatment for Both TB and HIV.",""],["Number of ART sites",""],["Number of  PMTCT sites",""],["HIV Testing, Multiple sexual Partnership and Condom Use",""],["Percentage of Sex Workers reached with HIV Prevention program",""],["Percentage of Sex workers who are living with HIV",""],["Percentage of Infant Born to HIV-Positive Women receiving a Virological Test for HIV within 2 months of Birth",""],["Estimated Children receiving and needing Antiretroval Therapy, and Coverage.",""],["HIV+TB Patients on ART",""],["Number of HIV Infected Female Adults",""]]}';
}
?>