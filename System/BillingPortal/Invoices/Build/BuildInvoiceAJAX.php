<?php
/********************************
* Project: IT Web Essentials 
* Code Version: 1.0
* Author: Benjamin Sommer
* Company: The Berman Consulting Group - https://bermangroup.com
* GitHub: https://github.com/remmosnimajneb
* Theme Design by: Pixelarity [Pixelarity.com]
* Licensing Information: https://pixelarity.com/license
***************************************************************************************/

/** 
* AJAX for Building an Invoice
*/

/* Include System Functions */
require_once("../../Functions.php");

/* Init System */
Init(false);

// Now return data as required...

	/* Add new Slip to Invoice */
	if($_POST['Action'] == "AddQuestion" && $_POST['Type'] == "Single"){
		// Mark the Slip and then return the values

		$UpdateSlip = "UPDATE `Slips` SET `InvoiceID` = " . EscapeSQLEntry($_POST['InvoiceID']) . ", `SlipStatus` = 'BAR' WHERE `InvoiceID` IS NULL AND `TSID` = " . EscapeSQLEntry($_POST['SingleID']);
		$stm = $DatabaseConnection->prepare($UpdateSlip);
		$stm->execute();

		//For Indiv slip, check it's not attached to another invoice already
		if($stm->rowCount() == 0){
			echo "0,Slip attached to another invoice already! Please remove from that to attach here.";
		} else {

			$Query = "SELECT * FROM `Slips` WHERE `TSID` = " . EscapeSQLEntry($_POST['SingleID']);
			$stm = $DatabaseConnection->prepare($Query);
			$stm->execute();
			$Slip = $stm->fetchAll();
			
			echo '<tr id="Row_' . $Slip[0]['TSID'] . '">';
				echo'<td><a href="../../Slips/Slip.php?ID=' . $Slip[0]['TSID'] . '">' . $Slip[0]['TSID'] . '</a></td>';
				echo '<td>' . $Slip[0]['TSType'] . '</td>';
				echo '<td>' . date('m/d/Y', strtotime($Slip[0]['StartDate'])) . '</td>';
				echo '<td>' . $Slip[0]['Consultant'] . '</td>';
				echo '<td>' . $Slip[0]['Hours'] . '</td>';
				echo '<td>' . $Slip[0]['Description'] . '</td>';
				echo '<td>';
					echo '<select onchange="Changed(' . $Slip[0]['TSID'] . ')" autocomplete="off">';
						echo '<option value="BAR"'; if($Slip[0]['SlipStatus'] == "BAR"){echo "selected='selected'";} echo '>Bill</option>';
						echo '<option value="NC"'; if($Slip[0]['SlipStatus'] == "NC"){echo "selected='selected'";} echo '>No Charge</option>';
						echo '<option value="DNB"'; if($Slip[0]['SlipStatus'] == "DNB"){echo "selected='selected'";} echo '>Do Not Bill</option>';
					echo '</select>';
				echo '</td>';
				echo '<td><input type="checkbox" name="Delete_' . $Slip[0]['TSID'] . '" id="Delete_' . $Slip[0]['TSID'] . '" onchange="Changed(' . $Slip[0]['TSID'] . ')"> <label for="Delete_' . $Slip[0]['TSID'] . '"></label>';
				echo '<td><button onclick="Save(' . $Slip[0]['TSID'] . ')" disabled="disabled" id="Save_' . $Slip[0]['TSID'] . '">Save</button></td>';
			echo '</tr>';
		}

	} else if($_POST['Action'] == "AddQuestion" && $_POST['Type'] == "Bulk"){
		
		// Find all the Slips, then Mark InvoiceID and output them
		$Slips = "SELECT * FROM `Slips` WHERE `ClientName` = '" . EscapeSQLEntry($_POST['ClientID']) . "' AND `StartDate` >= '" . EscapeSQLEntry($_POST['StartDate']) . "' AND `EndDate` <= '" . EscapeSQLEntry($_POST['EndDate']) . "' AND `InvoiceID` IS NULL";
		
		$stm = $DatabaseConnection->prepare($Slips);
		$stm->execute();
		$Slips = $stm->fetchAll();

		// Not checking for if attached already as no need to....we do check for no slips found
		if($stm->rowCount() == 0){
			echo "0,Yikes! Looks like we can't find any slips for that date range!";
		} else {

			foreach ($Slips as $Slip) {
				echo '<tr id="Row_' . $Slip['TSID'] . '">';
					echo'<td><a href="../../Slips/Slip.php?ID=' . $Slip['TSID'] . '">' . $Slip['TSID'] . '</a></td>';
					echo '<td>' . $Slip['TSType'] . '</td>';
					echo '<td>' . date('m/d/Y', strtotime($Slip['StartDate'])) . '</td>';
					echo '<td>' . $Slip['Consultant'] . '</td>';
					echo '<td>' . $Slip['Hours'] . '</td>';
					echo '<td>' . $Slip['Description'] . '</td>';
					echo '<td>';
						echo '<select onchange="Changed(' . $Slip['TSID'] . ')" autocomplete="off">';
							echo '<option value="BAR"'; if($Slip['SlipStatus'] == "BAR"){echo "selected='selected'";} echo '>Bill</option>';
							echo '<option value="NC"'; if($Slip['SlipStatus'] == "NC"){echo "selected='selected'";} echo '>No Charge</option>';
							echo '<option value="DNB"'; if($Slip['SlipStatus'] == "DNB"){echo "selected='selected'";} echo '>Do Not Bill</option>';
						echo '</select>';
					echo '</td>';
					echo '<td><input type="checkbox" name="Delete_' . $Slip['TSID'] . '" id="Delete_' . $Slip['TSID'] . '" onchange="Changed(' . $Slip['TSID'] . ')"> <label for="Delete_' . $Slip['TSID'] . '"></label>';
					echo '<td><button onclick="Save(' . $Slip['TSID'] . ')" disabled="disabled" id="Save_' . $Slip['TSID'] . '">Save</button></td>';
				echo '</tr>';
			}

			// Mark as Invoice

			$UpdateSlips = "UPDATE `Slips` SET `InvoiceID` = '" . EscapeSQLEntry($_POST['InvoiceID']) . "', `SlipStatus` = 'BAR' WHERE `ClientName` = '" . EscapeSQLEntry($_POST['ClientID']) . "' AND `StartDate` >= '" . EscapeSQLEntry($_POST['StartDate']) . "' AND `EndDate` <= '" . EscapeSQLEntry($_POST['EndDate']) . "' AND `InvoiceID` IS NULL";
			$stm = $DatabaseConnection->prepare($UpdateSlips);
			$stm->execute();

		}
			
	} else if($_POST['Action'] == "Edit"){

		// Delete
		if($_POST['Delete'] == "true"){

			$RemoveFromInvoice = "Update `Slips` SET `InvoiceID` = NULL WHERE `TSID` = " . $_POST['SlipID'];
			$stm = $DatabaseConnection->prepare($RemoveFromInvoice);
			$stm->execute();

			echo "0";
		} else if($_POST['SlipStatus'] == "NC" || $_POST['SlipStatus'] == "BAR" || $_POST['SlipStatus'] == "DNB") {
			// Update Slip Status (And check status is of the right type :)

			$UpdateSlipStatus = "UPDATE `Slips` SET `SlipStatus` = '" . EscapeSQLEntry($_POST['SlipStatus']) . "' WHERE `TSID` = " . $_POST['SlipID'];
			$stm = $DatabaseConnection->prepare($UpdateSlipStatus);
			$stm->execute();

			echo "1";
		}

	}

?>