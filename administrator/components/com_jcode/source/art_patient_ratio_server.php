<?php
include_once ('database_conn.php');
include_once ("function_lib.php");

$task = '';
if (isset($_REQUEST['operation'])) {
	$task = $_REQUEST['operation'];
}

switch($task) {
	case "getPatientRatio" :
		getPatientRatio();
		break;
	default :
		echo "{failure:true}";
		break;
}

function getPatientRatio() {
	
	$countryId = $_POST['CountryId'];
	$monthId = $_POST['MonthId'];
	$yearId = $_POST['YearId'];
	

	$sQuery1 = "SELECT
			  t_formulation.ServiceTypeId,
			  t_servicetype.ServiceTypeName,
			  SUM(t_cnm_patientoverview.TotalPatient) STTotalPatient
			FROM t_cnm_patientoverview
			  INNER JOIN t_cnm_masterstockstatus
			    ON (t_cnm_patientoverview.CNMStockId = t_cnm_masterstockstatus.CNMStockId)
			  INNER JOIN t_formulation
			    ON (t_cnm_patientoverview.FormulationId = t_formulation.FormulationId)
			  INNER JOIN t_servicetype
			    ON (t_formulation.ServiceTypeId = t_servicetype.ServiceTypeId)
			WHERE (t_cnm_masterstockstatus.CountryId = $countryId
			       AND t_cnm_masterstockstatus.Year = '$yearId'
			       AND t_cnm_masterstockstatus.MonthId = $monthId
			       AND t_cnm_masterstockstatus.StatusId = 5)
			GROUP BY t_formulation.ServiceTypeId , t_servicetype.ServiceTypeName
			HAVING SUM(t_cnm_patientoverview.TotalPatient)>0;";

	$rResult1 = safe_query($sQuery1);

	$sQuery2 = "SELECT
			  t_formulation.ServiceTypeId,
			  t_formulation.FormulationName,
			  SUM(t_cnm_patientoverview.TotalPatient) FTotalPatient
			FROM t_cnm_patientoverview
			  INNER JOIN t_cnm_masterstockstatus
			    ON (t_cnm_patientoverview.CNMStockId = t_cnm_masterstockstatus.CNMStockId)
			  INNER JOIN t_formulation
			    ON (t_cnm_patientoverview.FormulationId = t_formulation.FormulationId)
			WHERE (t_cnm_masterstockstatus.CountryId = $countryId
			       AND t_cnm_masterstockstatus.Year = '$yearId'
			       AND t_cnm_masterstockstatus.MonthId = $monthId
			       AND t_cnm_masterstockstatus.StatusId = 5)
			GROUP BY t_formulation.ServiceTypeId, t_formulation.FormulationName
			HAVING SUM(t_cnm_patientoverview.TotalPatient)>0;";

	$rResult2 = safe_query($sQuery2);

	$sQuery3 = "SELECT
				  t_formulation.ServiceTypeId,
				  t_formulation.FormulationName,
				  t_regimen.RegimenName,
				  SUM(t_cnm_regimenpatient.TotalPatient) RTotalPatient
				FROM t_cnm_regimenpatient
				  INNER JOIN t_regimen
				    ON (t_cnm_regimenpatient.RegimenId = t_regimen.RegimenId)
				  INNER JOIN t_cnm_masterstockstatus
				    ON (t_cnm_regimenpatient.CNMStockId = t_cnm_masterstockstatus.CNMStockId)
				  INNER JOIN t_formulation
				    ON (t_regimen.FormulationId = t_formulation.FormulationId)
				WHERE (t_cnm_masterstockstatus.CountryId = $countryId
				       AND t_cnm_masterstockstatus.Year = '$yearId'
				       AND t_cnm_masterstockstatus.MonthId = $monthId
				       AND t_cnm_masterstockstatus.StatusId = 5)
				GROUP BY t_formulation.ServiceTypeId, t_formulation.FormulationName, t_regimen.RegimenName
				HAVING SUM(t_cnm_regimenpatient.TotalPatient)>0;";

	$rResult3 = safe_query($sQuery3);

	$output3 = array();

	while ($row = mysql_fetch_array($rResult3)) {
		$output3[$row['FormulationName']][] = array("name" => $row['RegimenName'], "color" => "#ff00ff", "value" => $row['RTotalPatient']);
	}

	$output2 = array();

	while ($row = mysql_fetch_array($rResult2)) {

		if (!is_array($output3[$row['FormulationName']])) {
			$output3[$row['FormulationName']] = array();
		}

		$output2[$row['ServiceTypeId']][] = array("name" => $row['FormulationName'], "color" => "#ff00ff", "totalval" => $row['FTotalPatient'], "children" => $output3[$row['FormulationName']]);
	}

	$output1 = array();
	$allTotal = 0;

	while ($row = mysql_fetch_array($rResult1)) {
		if (!is_array($output2[$row['ServiceTypeId']])) {
			$output2[$row['ServiceTypeId']] = array();
		}
		$allTotal += $row['STTotalPatient'];
		$output1[] = array("name" => $row['ServiceTypeName'], "color" => "#ff00ff", "totalval" => $row['STTotalPatient'], "children" => $output2[$row['ServiceTypeId']]);
	}

	$output = array("name" => "Patient Ratio", "color" => "#ff00ff", "totalval" => $allTotal, "children" => $output1);

	echo json_encode($output);
}
?>