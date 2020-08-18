<?php
/********************************
* Project: IT Web Essentials - Modular based Portal System for Inventory, Billing, Service Desk and More! 
* Code Version: 2.0
* Author: Benjamin Sommer - BenSommer.net | GitHub @remmosnimajneb
* Company: The Berman Consulting Group - BermanGroup.com
* Theme Design by: Pixelarity [Pixelarity.com]
* Licensing Information: https://pixelarity.com/license
***************************************************************************************/

/** 
* Allow Users to Add or Edit Slips
*/

/* Page Variables */
    $PageSecurityLevel = 1;
    $AppName = "BillingPortalAdmin";
    $PageName = "Build Invoice";

    /* Include System Functions */
    require_once("../../../../InitSystem.php");


/* If editing, check the ID exists */
if(isset($_GET['ID']) && $_GET['ID'] != ""){
	$Query = "SELECT * FROM `Invoice` AS I INNER JOIN `Client` AS C ON C.`ClientID` = I.`ClientID` WHERE `InvoiceID` = " . EscapeSQLEntry($_GET['ID']);
	$stm = $DatabaseConnection->prepare($Query);
	$stm->execute();
	$Invoice = $stm->fetchAll();
	$Invoice = $Invoice[0];
	$RowCount = $stm->rowCount();

	if($RowCount == 0){
		header('Location: index.php?Error=Error - Invalid ID');
		die();
		exit();
	}
}

/* Get Header */
	require_once(SYSPATH . '/Assets/Views/Header.php');
?>

<div id="main" style="width: 95%;">
<!-- Content -->
	<section id="content" class="default">
		<header class="major">
			<h2>Build Invoice</h2>
		</header>
		<div class="content">

			<p style="color: orange;text-align: center;"><?php if(isset($Message)) echo $Message; ?></p>
			<div class="row">
				<div class="column">
					<a id="AddSingle" class="button large">Add Individual Slip</a>
				</div>
				<div class="column">
					<a id="AddBulk" class="button large">Bulk Add Slips</a>
				</div>
			</div>

			<hr>

			<table class="alt">
				<thead>
				    <tr>
				      <th scope="col" style="width: 50px;">ID</th>
				      <th scope="col" style="width: 100px;">Type</th>
				      <th scope="col" style="width: 100px;">Date</th>
				      <th scope="col" style="width: 100px;">Consultant</th>
				      <th scope="col" style="width: 100px;">Hours</th>
				      <th scope="col" style="">Description</th>
				      <th scope="col" style="width: 150px;">Options</th>
				      <th scope="col" style="width: 50px;">Delete</th>
				      <th scope="col" style="width: 230px;"></th>
				    </tr>
				</thead>
				<tbody id="SlipsBody">
					<?php
						$Query = "SELECT * FROM `Slip` WHERE `InvoiceID` = " . EscapeSQLEntry($_GET['ID']) . " ORDER BY `StartDate` ASC, `SlipID` ASC";
						$stm = $DatabaseConnection->prepare($Query);
						$stm->execute();
						$Slips = $stm->fetchAll();
						foreach ($Slips as $Slip) {
					?>
						<tr id="Row_<?php echo $Slip['SlipID']; ?>">
							<td><a href="../../Slips/Slip.php?ID=<?php echo $Slip['SlipID']; ?>"><?php echo $Slip['SlipID']; ?></a></td>
							<td><?php echo $Slip['TSType']; ?></td>
							<td><?php echo date('m/d/Y', strtotime($Slip['StartDate'])); ?></td>
							<td><?php echo $Slip['Consultant']; ?></td>
							<td><?php echo $Slip['Hours']; ?></td>
							<td><?php echo $Slip['Description']; ?></td>
							<td>
								<select onchange="Changed(<?php echo $Slip['SlipID']; ?>)" autocomplete="off" id="SlipStatus_<?php echo $Slip['SlipID']; ?>">
									<option value="BAR" <?php if($Slip['SlipStatus'] == "BAR") echo "selected='selected'"; ?>>Bill</option>
									<option value="NC" <?php if($Slip['SlipStatus'] == "NC") echo "selected='selected'"; ?>>No Charge</option>
									<option value="DNB" <?php if($Slip['SlipStatus'] == "DNB") echo "selected='selected'"; ?>>Do Not Bill</option>
								</select>
							</td>
							<td><input type="checkbox" name="Delete_<?php echo $Slip['SlipID']; ?>" id="Delete_<?php echo $Slip['SlipID']; ?>" onchange="Changed(<?php echo $Slip['SlipID']; ?>)"> <label for="Delete_<?php echo $Slip['SlipID']; ?>"></label>
							<td><button onclick="Save(<?php echo $Slip['SlipID']; ?>)" disabled="disabled" id="Save_<?php echo $Slip['SlipID']; ?>">Save</button></td>
						</tr>
					<?php
						}
					?>
				</tbody>
			</table>
		</div>
			<!-- The Modal -->
			<div id="modal" class="modal">

			  <!-- Modal content -->
			  <div class="modal-content">
				  <div class="modal-header">
				    <span class="close">&times;</span>
				    <h2>Add Slip(s)</h2>
				  </div>
				  <div class="modal-body">
				  	<br>
				  	<p style="text-align: center;">Note, slips are added to bottom of page, but are sorted on Invoice according to Date!</p>
				    <input type="hidden" name="ModalType">
					    <section id="Section_Single">
					    	<h2>Add Individual TS</h2>
					    	ID: <input type="number" id="SingleID" style="color:black;"><br><br>
					    	<button onclick="Add('Single');">Add</button>
					    	<br>
					    	<br>
					    </section>
					    <section id="Section_Bulk">
					    	<h2>Bulk Add</h2>
					    	Start Date: <input type="date" id="StartDate"><br><br>
					    	End Date: <input type="date" id="EndDate"><br><br>
					    	<button onclick="Add('Bulk');">Add</button>
					    	<br>
					    	<br>
					    </section>
					    <p style="color: red; text-align: center;" id="AJAXModalErrorMessage"></p>
				  </div>
				  <div class="modal-footer">
				    <h3></h3>
				  </div>
				</div>			    			
			  </div>
	</section>
</div>
<script type="text/javascript">
	// Un-Disable Save()
	function Changed(ID){
		$("#Save_" + ID).removeAttr("disabled");
	}

	// Save Slip Info
	function Save(ID){

		var SlipStatus = $("#SlipStatus_" + ID).val();
		var Delete = $("#Delete_" + ID).is(":checked");

		$.ajax({data: {'InvoiceID': <?php echo $_GET['ID']; ?>, 'ClientID': '<?php echo $Invoice['ClientID']; ?>', 'Action': 'Edit', 'SlipStatus': SlipStatus, 'Delete': Delete, 'SlipID': ID}, type: 'POST', url: "BuildInvoiceAJAX.php", success: function(Result){

				/* Deleted */
			    	if(Result.substring(0,1) == "0"){
			    		$("#Row_" + ID).remove();
			    /* Updated */
			    	} else if(Result.substring(0,1) == "1") {
			    		$("#Save_" + ID).attr("disabled", "disabled");
			    	} else {
			    		document.getElementById("AJAXModalErrorMessage").innerHTML = Result.substring(2);
			    	}
		}});

	}


	// Add Function
	function Add(AddType){
		var SingleID = $("#SingleID").val();
		var StartDate = $("#StartDate").val();
		var EndDate = $("#EndDate").val();

		$.ajax({data: {'InvoiceID': <?php echo $_GET['ID']; ?>, 'ClientID': '<?php echo $Invoice['ClientID']; ?>', 'Action': 'AddQuestion', 'Type': AddType, 'SingleID': SingleID, 'StartDate': StartDate, 'EndDate': EndDate}, type: 'POST', url: "BuildInvoiceAJAX.php", success: function(Result){
			    // Check for Error:
			    	if(Result.substring(0,1) == "0"){
			    		document.getElementById("AJAXModalErrorMessage").innerHTML = Result.substring(2);
			    	} else {
			    		$("#SlipsBody").append(Result);
			    		document.getElementById("modal").style.display = "none";
			    	}
		}});

	}


	// Show Modal
		// Get the modal
		var modal = document.getElementById("modal");

		// Get the button that opens the modal
		var single = document.getElementById("AddSingle");
		var bulk = document.getElementById("AddBulk");

		// Get the <span> element that closes the modal
		var span = document.getElementsByClassName("close")[0];

		// When the user clicks on the button, open the modal
		single.onclick = function() {
		  document.getElementById("AJAXModalErrorMessage").innerHTML = "";
		  modal.style.display = "block";
		  $("#Section_Single").css("display", "block");
		  $("#Section_Bulk").css("display", "none");
		}

		bulk.onclick = function() {
		  document.getElementById("AJAXModalErrorMessage").innerHTML = "";			
		  modal.style.display = "block";
		  $("#Section_Single").css("display", "none");
		  $("#Section_Bulk").css("display", "block");
		}

		// When the user clicks on <span> (x), close the modal
		span.onclick = function() {
		  modal.style.display = "none";
		}

		// When the user clicks anywhere ouSlipIDe of the modal, close it
		window.onclick = function(event) {
		  if (event.target == modal) {
		    modal.style.display = "none";
		  }
		} 
</script>
<?  /* Get Footer */ 	require_once(SYSPATH . '/Assets/Views/Footer.php'); ?>