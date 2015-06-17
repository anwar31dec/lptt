<?php
include_once ("function_lib.php");

$task = '';
if (isset($_REQUEST['operation'])) {
	$task = $_REQUEST['operation'];
}

switch($task) {
	case "getPatientByProduct" :
		getPatientByProduct();
		break;	
	default :
		echo "{failure:true}";
		break;
}



function getPatientByProduct() {			
	echo '{
	"sEcho": ' . intval($_GET['sEcho']) .',
	"iTotalRecords": "10",
	"iTotalDisplayRecords": "10",
	"aaData": [["1", "ABACAVIR(300mg); 60 Tabs", "26529", "529", "1560", "155", "7.25", "2000", "8.03", "0"],
			["2", "ABACAVIR(20mg/ml); 240 Susp", "36527", "531", "1575", "145", "8.44", "2500", "9.15", "0"],
			["3", "DIDANOSINE(400mg); 30 Caps", "46523", "456", "1352", "148", "12.11", "3500", "13.07", "0"],
			["4", "DIDANOSINE(25mg); 60 Tabs", "56526", "398", "1185", "148", "14.24", "1500", "15.32", "0"],
			["5", "DIDANOSINE(200mg); 30 Caps", "66524", "259", "750", "176", "14.08", "1500", "15.98", "0"],
			["6", "DIDANOSINE(250mg); 30 Caps", "76521", "456", "1386", "201", "5.24", "2200", "6.08", "0"],
			["7", "LOPINAVIR/RITONAVIR(200/50mg); 120 Tabs", "86529", "764", "2153", "310", "5.11", "1800", "6.10", "0"],
			["8", "CO-TRIMOXAZOLE(100/20mg); 100 Tabs", "96524", "728", "2143", "157", "5.19", "1900", "6.56", "0"],
			["9", "CO-TRIMOXAZOLE(240mg/5ml); 100 Susp", "106526", "589", "1682", "200", "9.54", "3200", "10.46", "0"],
			["10", "LAMIVUDINE(50mg/5ml); 100 Susp", "53524", "629", "1852", "303", "9.43", "3400", "10.72", "0"]
		]
	}';	
	
	//echo '{"sEcho":' . intval($_GET['sEcho']) .',"iTotalRecords":"189","iTotalDisplayRecords":"54","aaData":[["Population","93,25000"],["Population growth rate","2.9%"],["Language","French"],["Life Expectancy","44"],["GDP per capita","567.91"],["Expenditure of health","39"],["Human Index Ranking","0.436 (166)"],["HIV Prevalence","1.4"],["Number of people living with HIV","73,000"],["Number of people on HIV Treatment","37,000"],["HIV Treatment Gap","49,000"],["Percentage of eligible people receiving HIV treatment","32%"],["New HIV infections among adults","6,000"],["New HIV infections among Children","<1,000"],["PMTCT coverage","1,115"],["Number of AIDS-related death","3,400"],["% of people on treatment 12 months after initiation of ART","93%"],["% of HIV-Positive incidents TB Cases that received treatment for Both TB and HIV.","57%"],["Number of ART sites",""],["Number of  PMTCT sites",""],["HIV Testing, Multiple sexual Partnership and Condom Use",""],["Percentage of Sex Workers reached with HIV Prevention program",""],["Percentage of Sex workers who are living with HIV",""],["Percentage of Infant Born to HIV-Positive Women receiving a Virological Test for HIV within 2 months of Birth",""],["Estimated Children receiving and needing Antiretroval Therapy, and Coverage.",""],["HIV+TB Patients on ART",""],["Number of HIV Infected Female Adults",""],["Population",""],["Population growth rate",""],["Language",""],["Life Expectancy",""],["GDP per capita",""],["Expenditure of health",""],["Human Index Ranking",""],["HIV Prevalence",""],["Number of people living with HIV",""],["Number of people on HIV Treatment",""],["HIV Treatment Gap",""],["Percentage of eligible people receiving HIV treatment",""],["New HIV infections among adults",""],["New HIV infections among Children",""],["PMTCT coverage",""],["Number of AIDS-related death",""],["% of people on treatment 12 months after initiation of ART",""],["% of HIV-Positive incidents TB Cases that received treatment for Both TB and HIV.",""],["Number of ART sites",""],["Number of  PMTCT sites",""],["HIV Testing, Multiple sexual Partnership and Condom Use",""],["Percentage of Sex Workers reached with HIV Prevention program",""],["Percentage of Sex workers who are living with HIV",""],["Percentage of Infant Born to HIV-Positive Women receiving a Virological Test for HIV within 2 months of Birth",""],["Estimated Children receiving and needing Antiretroval Therapy, and Coverage.",""],["HIV+TB Patients on ART",""],["Number of HIV Infected Female Adults",""]]}';
}
?>