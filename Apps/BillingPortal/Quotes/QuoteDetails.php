<?php
/********************************
* Project: IT Web Essentials - Modular based Portal System for Inventory, Billing, Service Desk and More! 
* Code Version: 2.2
* Author: Benjamin Sommer - BenSommer.net | GitHub @remmosnimajneb
* Company: The Berman Consulting Group - BermanGroup.com
* Theme Design by: Pixelarity [Pixelarity.com]
* Licensing Information: https://pixelarity.com/license
***************************************************************************************/

/** 
* Allow Users to Add or Edit Quotes
*/

/* Page Variables */
$PageSecurityLevel = 1;
$AppName = "BillingPortalAdmin";
$PageName = "Quotes Details";

/* Include System Functions */
require_once("../../../InitSystem.php");

/* If we are Editing or Updating */
if(isset($_REQUEST['Action'])){

	//Make dates
		$_POST['Date'] = date('Y-m-d', strtotime(EscapeSQLEntry($_POST['Date'])));
		$_POST['ExpDate'] = date('Y-m-d', strtotime(EscapeSQLEntry($_POST['ExpDate'])));
		$_POST['SignedDate'] = date('Y-m-d', strtotime(EscapeSQLEntry($_POST['SignedDate'])));
	
	// Build Line Items
		$LineItems = BuildLineItems();

	
	/* Update Quote */
	if($_REQUEST['Action'] == "Edit"){

		/* Delete Quote */
		if($_POST['DeleteQuote'] == "Yes"){

			$Query = "DELETE FROM `Quote` WHERE `QuoteID` = " . EscapeSQLEntry($_POST['ID']);
			$stm = $DatabaseConnection->prepare($Query);
			$stm->execute();
			$Message = "Quote Deleted Successfully!";

		} else {

			$Query = "UPDATE `Quote` SET `ClientID` = '" . EscapeSQLEntry($_POST['ClientID']) . "', `Name` = '" . EscapeSQLEntry($_POST['Name']) . "', `Date` = '" . EscapeSQLEntry($_POST['Date']) . "', `ExpDate` = '" . EscapeSQLEntry($_POST['ExpDate']) . "', `LineItems` = '" . json_encode($LineItems) . "', `Discount` = '" . EscapeSQLEntry($_POST['Discount']) . "', `Fee` = '" . EscapeSQLEntry($_POST['Fee']) . "', `QuoteStatus` = '" . EscapeSQLEntry($_POST['QuoteStatus']) . "', `Approved` = '" . EscapeSQLEntry($_POST['Approved']) . "', `SignedName` = '" . EscapeSQLEntry($_POST['SignedName']) . "', `SignedDate` = '" . EscapeSQLEntry($_POST['SignedDate']) . "', `InvoiceID` = '" . EscapeSQLEntry($_POST['InvoiceID']) . "' WHERE `QuoteID` = '" . EscapeSQLEntry($_POST['ID']) . "'";
			$stm = $DatabaseConnection->prepare($Query);
			$stm->execute();
			$Message = "Quote ID # <a href='" . GetSysConfig("SystemURL") . "/Apps/BillingPortal/Quotes/QuoteDetails.php?ID=" . $_POST['ID'] . "' style='font-decoration:none;color:orange;'>" . $_POST['ID'] . "</a> Updated Successfully!";

		} // End Else

	}

	/* Add Invoice */	
	if($_REQUEST['Action'] == "Add"){

		$Query = "INSERT INTO `Quote` (`QuoteID`, `Hash`, `ClientID`, `Name`, `Date`, `ExpDate`, `LineItems`, `Discount`, `Fee`, `Approved`, `QuoteStatus`, `SignedName`, `Signature`, `SignedDate`, `InvoiceID`) VALUES (NULL, '" . bin2hex(random_bytes(32)) . "', '" . EscapeSQLEntry($_POST['ClientID']) . "', '" . EscapeSQLEntry($_POST['Name']) . "', '" . EscapeSQLEntry($_POST['Date']) . "', '" . EscapeSQLEntry($_POST['ExpDate']) . "', '" . json_encode($LineItems) . "', '" . EscapeSQLEntry($_POST['Discount']) . "', '" . EscapeSQLEntry($_POST['Fee']) . "', '" . EscapeSQLEntry($_POST['Approved']) . "', '" . EscapeSQLEntry($_POST['QuoteStatus']) . "', '" . EscapeSQLEntry($_POST['SignedName']) . "', '', '" . EscapeSQLEntry($_POST['SignedDate']) . "', '')";
		$stm = $DatabaseConnection->prepare($Query);
		$stm->execute();
		header('Location: ' . GetSysConfig("SystemURL") . '/Apps/BillingPortal/Quotes/QuoteDetails.php?ID=' . $DatabaseConnection->lastInsertId());
		die();
	}

	/* Quote to Invoice */
		include_once(SYSPATH . '/Apps/BillingPortal/Quotes/Quote2Invoice.php');

		if($_REQUEST['Action'] == "Quote2Invoice"){

			$InvID = Quote2Invoice($_GET['ID']);

			header('Location: ' . GetSysConfig("SystemURL") . '/Apps/BillingPortal/Invoices/InvoiceDetails.php?ID=' . $InvID);
			die();

		}
}

/*
* Helper Function
*/
function BuildLineItems(){
	
	global $_POST;

	$LineItems = new stdClass();
	$LineItems->Items = array();

		for ($i = 1; $i <= $_POST['TotalLineItems']; $i++) { 
			
			$Item = new stdClass();

				$Item->Name = EscapeSQLEntry($_POST['Name_' . $i]);
				$Item->Type = EscapeSQLEntry($_POST['SlipType_' . $i]);
				$Item->Hours = EscapeSQLEntry($_POST['Hours_' . $i]);
				$Item->Price = EscapeSQLEntry($_POST['Price_' . $i]);
				$Item->Quantity = EscapeSQLEntry($_POST['Quantity_' . $i]);
				$Item->Status = EscapeSQLEntry($_POST['Status_' . $i]);

			array_push($LineItems->Items, $Item);

		}

	return $LineItems;
}


/* If editing, check the ID exists */
if(isset($_GET['ID']) && $_GET['ID'] != ""){
	$Query = "SELECT * FROM `Quote` WHERE `QuoteID` = " . EscapeSQLEntry($_GET['ID']);
	$stm = $DatabaseConnection->prepare($Query);
	$stm->execute();
	$Quote = $stm->fetchAll()[0];

	// No row exists, throw an error
	if($stm->rowCount() == 0){
		header('Location: index.php?Error=Error - Invalid ID');
		die();
	}
}

	/* Get Header */
	require_once(SYSPATH . '/Assets/Views/Header.php');
?>

<div id="main" style="width: 95%;">
<!-- Content -->
	<section id="content" class="default">
		<header class="major">
			<h2>Quote Details</h2>
		</header>
		<div class="content">
			<p style="color: orange;text-align: center;"><?php if(isset($Message)) echo $Message; ?></p>
				<?php

					if(isset($Quote['Approved'])){
						?><h2>Edit Quote</h2><?php
					} else {
						?><h2>Create Quote</h2><?php
					}
				?>

					<a href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Quotes/Quote.php?ID=<?php echo $Quote['Hash']; ?>" class="button large">View Quote</a>

				<form action="<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Quotes/QuoteDetails.php?ID=<?php echo $Quote['QuoteID']; ?>" method="POST">
					
					<?php

						/* Is Invoice Made Yet */
						if(!empty($Quote["InvoiceID"])){
							?>
								<hr>
								<h3 style="color: orange;">An Invoice has been attached to this Quote.</h3>
								<a href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Invoices/InvoiceDetails.php?ID=<?php echo $Quote['InvoiceID']; ?>" class="button large">View Invoice</a>
									<br>
								<a href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Quotes/QuoteDetails.php?ID=<?php echo $Quote['QuoteID']; ?>&Action=Quote2Invoice" class="button large" id="UpdateQuote2Invoice">Update Quote to Invoice</a>
							<?php
						} else {
							?>
								<a href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Quotes/QuoteDetails.php?ID=<?php echo $Quote['QuoteID']; ?>&Action=Quote2Invoice" class="button large">Create Quote to Invoice</a>
							<?php
						}


						/* Heading */
						if(isset($Quote['Approved'])){
							?><input type="hidden" name="Action" value="Edit"><?php	
						} else {
							?><input type="hidden" name="Action" value="Add"><?php	
						}

						?><input type="hidden" name="ID" value='<?php echo EscapeSQLEntry($Quote['QuoteID']); ?>'><?php
					?>
						
				<hr>
					
					<!-- Quotes Fields -->	
					<div class="row">
						<div class="column">
							Client: 	<select name="ClientID" required="required">
											<?php 
												$ClientsQuery = "SELECT
																`ClientID`, 
																`ClientName`,
												  				`ClientSlug`
										  					FROM `Client`
										  				ORDER BY `ClientName` ASC";
												$stm = $DatabaseConnection->prepare($ClientsQuery);
												$stm->execute();
												$Clients = $stm->fetchAll();
												foreach ($Clients as $Client) {
													echo "<option value=" . $Client['ClientID'] . "";
														if($Quote['ClientID'] == $Client['ClientID']){
															echo " selected='selected'";
														}
													echo ">" . $Client['ClientName'] . "</option>";
												}
											?>
										</select>
							</div>
							<div class="column">
								Name: <br>
									<input type="text" name="Name" value="<?php echo $Quote["Name"];?>" required="required">
							</div>
						</div>
							<br>
						<div class="row">
							<div class="column">
									Date: <br>
										<input type="date" name="Date" value="<?php echo $Quote["Date"]; ?>">
							</div>
							<div class="column">
									Expiration Date: <br>
										<input type="date" name="ExpDate" value="<?php echo $Quote["ExpDate"]; ?>">
							</div>
							<!--
								<div class="column">
									Discount: <br>
										<input type="number" name="Discount" value="<?php echo isset($Quote["Discount"]) ? $Quote["Discount"]:0; ?>" min="0" step="any">				
								</div>
							
								<div class="column">
									Fee's: <br>
										<input type="number" name="Fee" value="<?php echo isset($Quote["Fee"]) ? $Quote["Fee"]:0; ?>" min="0" step="any">				
								</div>
							-->
						</div>
							<br>
						<div class="row">
							<div class="column">
								Quote Status: <br>
									<select name="QuoteStatus" required="required">
										<option value="-1" disabled="disabled" selected="selected">-- Please Select --</option>
										<option value="1" <?php if($Quote['QuoteStatus'] == "1") echo "selected='selected'"; ?>>Published (Client can view)</option>
										<option value="0" <?php if($Quote['QuoteStatus'] == "0") echo "selected='selected'"; ?>>Not Published (Client cannot view)</option>
									</select>
							</div>
							<div class="column">
								Approved (Client): <br>
									<select name="Approved" required="required">
										<option value="-1" disabled="disabled" selected="selected">-- Please Select --</option>
										<option value="1" <?php if($Quote['Approved'] == "1") echo "selected='selected'"; ?>>Approved</option>
										<option value="0" <?php if($Quote['Approved'] == "0") echo "selected='selected'"; ?>>Not Approved</option>
									</select>
							</div>
						</div>
							<br>
						<div class="row">
							<div class="column">
								Signature (Print): <br>
									<input type="text" name="SignedName" value="<?php echo $Quote["SignedName"]; ?>">
							</div>
							<?php
								if(!empty($Quote['Signature'])){
									?>
										<div class="column">
											Signature: <br>
												<img src="<?php echo $Quote['Signature']; ?>">
										</div>
									<?php
								}
							?>
							<div class="column">
								Signed Date: <br>
									<input type="date" name="SignedDate" value="<?php echo $Quote["SignedDate"]; ?>">
							</div>
						</div>
							<br>
						<div class="row">
							<div class="column">
								Connected Invoice: <select name="InvoiceID">
										<option selected="selected" value="0">Not connected to an Invoice</option>
										<?php 
											$Query = "SELECT
															`InvoiceID`
									  					FROM `Invoice`
									  				ORDER BY `InvoiceID` DESC";
											$stm = $DatabaseConnection->prepare($Query);
											$stm->execute();
											$Invoices = $stm->fetchAll();
											foreach ($Invoices as $Invoice) {
												echo "<option value=" . $Invoice['InvoiceID'] . "";
													if($Quote['InvoiceID'] == $Invoice['InvoiceID']){
														echo " selected='selected'";
													}
												echo ">" . $Invoice['InvoiceID'] . "</option>";
											}
										?>
									</select>
							</div>
						</div>
							<hr>
						<h2>Line Items</h2>
							<section class="LineItems">
								<?php
								$LineItems = json_decode($Quote['LineItems'], true);
								$i = 0;
								foreach ($LineItems['Items'] as $Item) {
										$i++;
									?>
										<div class="LineItem Item_<?php echo $i; ?>" data-id="<?php echo $i; ?>">
											<div class="row">
												<h3 class="ItemTitle">Item #<?php echo $i; ?></h3>
											</div>
											<div class="row">
												<div class="col">
													Name: <br>
														<input type="text" name="Name_<?php echo $i; ?>" class="Name_<?php echo $i; ?>" value="<?php echo $Item["Name"];?>" required="required">
												</div>
												<div class="col">
													Type: <br>
														<select name="SlipType_<?php echo $i; ?>" class="SlipType SlipType_<?php echo $i; ?>" data-id="<?php echo $i; ?>" required="required">
															<option value="-1" disabled="disabled" selected="selected">-- Please Select --</option>
															<option value="TS" <?php if($Item['Type'] == "TS") echo "selected='selected'"; ?>>Professional Service</option>
															<option value="Expense" <?php if($Item['Type'] == "Expense") echo "selected='selected'"; ?>>Expense</option>
														</select>
												</div>
											</div>
												<br>
											<div class="row">
												<div class="col" id="Hours_<?php echo $i; ?>">
													Hours: <br>
														<input type="number" name="Hours_<?php echo $i; ?>" value="<?php echo isset($Item["Hours"]) ? $Item["Hours"]:0; ?>">
												</div>
												<div class="col" id="Price_<?php echo $i; ?>" >
													Price: <br>
														<input type="number" name="Price_<?php echo $i; ?>" value="<?php echo isset($Item["Price"]) ? $Item["Price"]:0; ?>">
												</div>
												<div class="col" id="Quantity_<?php echo $i; ?>" >
													Quantity: <br>
														<input type="number" name="Quantity_<?php echo $i; ?>" value="<?php echo isset($Item["Quantity"]) ? $Item["Quantity"]:0; ?>">
												</div>
												<div class="col">
													Status: <br>
														<select name="Status_<?php echo $i; ?>" class="Status_<?php echo $i; ?>" required="required">
															<option value="-1" disabled="disabled" selected="selected">-- Please Select --</option>
															<option value="BAR" <?php if($Item['Status'] == "BAR") echo "selected='selected'"; ?>>Bill</option>
															<option value="NC" <?php if($Item['Status'] == "NC") echo "selected='selected'"; ?>>No Charge</option>
														</select>
												</div>
											</div>
												<br>
											<div class="row">
												<div class="col" style="flex-basis: 50%">
													<button class="RemoveItem RI_<?php echo $i; ?>" data-id="<?php echo $i; ?>" type="button">Remove Item</button>
												</div>
											</div>
											<br>
										</div>
										
									<?php
								}
							?>
							</section>
								<input type="hidden" name="TotalLineItems" id="TotalLineItems" value="<?php echo $i; ?>">
									<br>
							<div class="row">
								<div class="col" style="flex-basis: 50%">
									<button class="AddItem" type="button">Add Item</button>
								</div>
							</div>
							<hr>
								<?php
									if(isset($_GET['ID']) && $_GET['ID'] != ""){
										?>
										<input type='checkbox' name='DeleteQuote' value='Yes' id='DeleteQuote'>
											<label for='DeleteQuote'>Delete Quote?</label><br>
										<?php
									}
								?>
							<br>
					<input type="submit" name="Submit" value="Save">
				</form>			
		</div>
	</section>
</div>
<script type="text/javascript">

	/* Line Item Scripts */

		/*
		* So when we remove an item, since they are numbered we'd get a random assortment of item 1, 4, 9 and 12
		* we need to re-number them each remove() so they stay numerical ordered
		*/
		function ReNumberLineItems(){

			var i = 0;
			

				$(".LineItem").each(function(){

					i++;

					// Get Current Elem OLD ID
					var current_id = $(this).attr("data-id");


					// If the current and new # are the same, deleting will cause wonky effects to the page
					$(".Item_" + current_id).addClass("Item_" + new_id);
					
						if(new_id != current_id){
							$(".Item_" + current_id).removeClass("Item_" + current_id);
							$(".Name_" + current_id).removeClass("Name_" + current_id);
							$(".SlipType_" + current_id).addClass("SlipType_" + new_id);
							$(".Status_" + current_id).removeClass("Status_" + current_id);
							$(".RI_" + current_id).removeClass("RI_" + current_id);

						}

					$(".Item_" + new_id).attr("data-id", "" + new_id + "");

					$(this).find("h3").first().html("Item #" + new_id);

					$(".Name_" + current_id).addClass("Name_" + new_id);
					$(".Name_" + new_id).attr("name", "Name_" + new_id);

					$(".SlipType_" + current_id).attr("data-id", new_id);
					$(".SlipType_" + current_id).attr("name", "SlipType_" + new_id);
					

					$("#Hours_" + current_id).attr("id", "Hours_" + new_id);
					$('input[name="Hours_' + current_id + '"]').attr("name", "Hours_" + new_id);

					$("#Price_" + current_id).attr("id", "Price_" + new_id);
					$('input[name="Price_' + current_id + '"]').attr("name", "Price_" + new_id);

					$("#Quantity_" + current_id).attr("id", "Quantity_" + new_id);
					$('input[name="Quantity_' + current_id + '"]').attr("name", "Quantity_" + new_id);

					$(".Status_" + current_id).attr("name", "Status_" + new_id);
					$(".Status_" + current_id).addClass("Status_" + new_id);


					$(".RI_" + current_id).attr("data-id", new_id);
					$(".RI_" + current_id).addClass("RI_" + new_id);

					$(this).find(".RemoveItem").first().attr("data-id", new_id);
					
				});
			
			// Set total Line Items
			$("#TotalLineItems").val(i);

		}
		
		/*
		* Setup Event Handlers for SlipType and RemoveItem
		*/
		function __InitEvntHndlrs(){
			
			// Handle hide Price/Qty or Hours if TS or Expense
			$(".SlipType").change(function(){

				var item_no = $(this).attr("data-id");

				if($(this).val() == "TS"){

					// Hide Price and Qty
					$("#Price_" + item_no).css("display", "none");
					$("#Quantity_" + item_no).css("display", "none");
					$("#Hours_" + item_no).css("display", "block");

				} else {

					// Hide Cost
					$("#Price_" + item_no).css("display", "block");
					$("#Quantity_" + item_no).css("display", "block");
					$("#Hours_" + item_no).css("display", "none");

				}

			});

			// Handle Remove
			$(".RemoveItem").click(function(){

				// Delete
				$(this).closest(".LineItem").remove();

				// Re-Number 
				ReNumberLineItems();

			});
		}

		/*
		* Add's new Line Item box to the form
		*/

		$(".AddItem").click(function(){

			$(".LineItems").append("<div class=\"LineItem Item_" + (Number.parseInt($("#TotalLineItems").val())+1) + "\" data-id=\"" + (Number.parseInt($("#TotalLineItems").val())+1) + "\">	<div class=\"row\">		<h3 class=\"ItemTitle\">Item #" + (Number.parseInt($("#TotalLineItems").val())+1) + "</h3>	</div>	<div class=\"row\">		<div class=\"col\">			Name: <br>				<input type=\"text\" name=\"Name_" + (Number.parseInt($("#TotalLineItems").val())+1) + "\" class=\"Name_" + (Number.parseInt($("#TotalLineItems").val())+1) + "\" value=\"\" required=\"required\">		</div>		<div class=\"col\">			Type: <br>				<select name=\"SlipType_" + (Number.parseInt($("#TotalLineItems").val())+1) + "\" class=\"SlipType SlipType_" + (Number.parseInt($("#TotalLineItems").val())+1) + "\" data-id=\"" + (Number.parseInt($("#TotalLineItems").val())+1) + "\" required=\"required\">					<option value=\"-1\" disabled=\"disabled\" selected=\"selected\">-- Please Select --</option>					<option value=\"TS\">Professional Service</option>					<option value=\"Expense\">Expense</option>				</select>		</div>	</div>		<br>	<div class=\"row\">		<div class=\"col\" style='display:none;' id=\"Hours_" + (Number.parseInt($("#TotalLineItems").val())+1) + "\">			Hours: <br>				<input type=\"number\" name=\"Hours_" + (Number.parseInt($("#TotalLineItems").val())+1) + "\" value=\"\">		</div>		<div class=\"col\" style='display:none;' id=\"Price_" + (Number.parseInt($("#TotalLineItems").val())+1) + "\" >			Price: <br>				<input type=\"number\" name=\"Price_" + (Number.parseInt($("#TotalLineItems").val())+1) + "\" value=\"\">		</div>		<div class=\"col\" style='display:none;' id=\"Quantity_" + (Number.parseInt($("#TotalLineItems").val())+1) + "\" >			Quantity: <br>				<input type=\"number\" name=\"Quantity_" + (Number.parseInt($("#TotalLineItems").val())+1) + "\" value=\"\">		</div>		<div class=\"col\">			Status: <br>				<select name=\"Status_" + (Number.parseInt($("#TotalLineItems").val())+1) + "\" class=\"Status_" + (Number.parseInt($("#TotalLineItems").val())+1) + "\" required=\"required\">					<option value=\"-1\" disabled=\"disabled\" selected=\"selected\">-- Please Select --</option>					<option value=\"BAR\">Bill</option>					<option value=\"NC\">No Charge</option>				</select>		</div>	</div>		<br>	<div class=\"row\">		<div class=\"col\" style=\"flex-basis: 50%\">			<button class=\"RemoveItem RI_" + (Number.parseInt($("#TotalLineItems").val())+1) + "\" data-id=\"" + (Number.parseInt($("#TotalLineItems").val())+1) + "\" type=\"button\">Remove Item</button>		</div>	</div><br></div>");

			// Incrememnt TotalItems
			$("#TotalLineItems").val((Number.parseInt($("#TotalLineItems").val())+1));

			// Reload Init Event Handlers
			__InitEvntHndlrs();

		});
	
	/*
	* On-Loads
	*/
	$( window ).ready(function(){

		// Setup Event Handlers
		__InitEvntHndlrs();

		// Hide/Show fields for SlipType
		$(".SlipType").each(function(){

			var item_no = $(this).attr("data-id");

			if($(this).val() == "TS"){

				// Hide Price and Qty
				$("#Price_" + item_no).css("display", "none");
				$("#Quantity_" + item_no).css("display", "none");
				$("#Hours_" + item_no).css("display", "block");

			} else {

				// Hide Cost
				$("#Price_" + item_no).css("display", "block");
				$("#Quantity_" + item_no).css("display", "block");
				$("#Hours_" + item_no).css("display", "none");

			}
		});

	});

	/*
	* Show Alert when Delete is clicked.
	*/
	$("#DeleteQuote").change(function(){
		if($("#DeleteQuote").is(":checked")){
			alert("Warning: this will delete the Quote completely! Just make sure your deleting the right thing!");
		}
	});
</script>
<?php 	/* Get Footer */ require_once(SYSPATH . '/Assets/Views/Footer.php');  ?>