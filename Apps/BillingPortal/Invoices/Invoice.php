<?php
/********************************
* Project: IT Web Essentials - Modular based Portal System for Inventory, Billing, Service Desk and More! 
* Code Version: 2.0
* Author: Benjamin Sommer - BenSommer.net | GitHub @remmosnimajneb
* Company: The Berman Consulting Group - BermanGroup.com
* Theme Design by: Pixelarity [Pixelarity.com]
* Licensing Information: https://pixelarity.com/license
***************************************************************************************/

/*
* Show Invoice
*/

   /* Page Variables */
	$PageSecurityLevel = 0;
	$AppName = "BillingPortalAdmin";
	$PageName = "Invoice";

	/* Include System Functions */
	require_once("../../../InitSystem.php");

    $IsAdmin = true;

    /* Check it's a real Invoice */
    $Invoice = "SELECT * FROM `Invoice` AS I INNER JOIN `Client` AS C ON C.`ClientID` = I.`ClientID` WHERE `InvoiceHash` = '" . EscapeSQLEntry($_GET['ID']) . "'";
    $stm = $DatabaseConnection->prepare($Invoice);
    $stm->execute();
    $Invoice = $stm->fetchAll();
    $Invoice = $Invoice[0];

    //Now check the InvID is a reall invoice
    if($stm->rowCount() == 0){
        header('Location: ../../../Switchboard.php');
        die();
    }

    /* Check if User is Loggedin */
    if(!$_SESSION['IsLoggedIn']){
    	$IsAdmin = false;
    	if($Invoice['InvoiceStatus'] == "0"){
    		header('Location: ' . $SystemPublicURL . "/404");
    		die();
    	}
    }


    /* Get Header */
    require_once(SYSPATH . "/Apps/BillingPortal/Invoices/Assets/Views/InvoiceHeader.php");

    	/* Now get the columns to print out */
    	$Cols = json_decode($Invoice["ColumnsToShow"], true);
?>
	<main>
		<div class="row contacts">
	        <div class="col invoice-to">
	            <div class="text-gray-light">INVOICE TO:</div>
	            <h2 class="to"><?php echo $Invoice["ClientName"]; ?></h2>
	            <div class="address"><?php echo $Invoice["StreetName"] . ", " . $Invoice["City"] . ", " . $Invoice["State"] . "  " . $Invoice["ZIP"]; ?></div>
	            <div class="email"><a href="mailto:<?php echo $Invoice["Email"]; ?>"><?php echo $Invoice["Email"]; ?></a></div>
	        </div>
	        <div class="col invoice-details">
	            <h1 class="invoice-id">INVOICE #<?php echo $Invoice["InvoiceID"]; ?></h1>
                <div class="date">Date of Invoice: <?php echo date_format(date_create($Invoice["InvoiceDate"]), "m/d/Y"); ?></div>
	        </div>
	    </div>
	    	<br>
	    	<br>
	    <?php
	    				$HasExpenses = false;
	    				$HasProfServices = false;

	    				$HourlyTotalWithoutExpenses = 0;
	        			$TotalHours = 0;
	        			$TotalExpenses = 0;

	        			/* Slips */
		        			$Slips = "SELECT * FROM `Slip` WHERE `TSType` = 'TS' AND `SlipStatus` != 'DNB' AND `InvoiceID` = '" . $Invoice["InvoiceID"] . "' ORDER BY `StartDate` ASC";
		        			$stm = $DatabaseConnection->prepare($Slips);
	    					$stm->execute();
	    					$Slips = $stm->fetchAll();
	    					if($stm->rowCount() > 0){
	    ?>
	    <h2>Professional Services</h2>
		<table border="0" cellspacing="0" cellpadding="0">
	        <thead>
	            <tr>
	                <th>Date</th>
	                <?php if($Cols["Consultant"] == "1") echo "<th>Consultant</th>"; ?>
	                <?php if($Cols["Description"] == "1") echo "<th>Description</th>"; ?>
	                <?php if($Cols["Hours"] == "1") echo "<th>Hours</th>"; ?>
	                <?php if($Cols["Rate"] == "1") echo "<th>Rate</th>"; ?>
	                <?php if($Cols["Amount"] == "1") echo "<th>Amount</th>"; ?>
	            </tr>
	        </thead>
	        <tbody>
	        		<?php
	        				$HasProfServices = true;
	        				foreach ($Slips as $Slip) {
	    						echo "<tr>";
		    						/* Date */
		    							if(date_format(date_create($Slip['StartDate']), "m/d/Y") != date_format(date_create($Slip['EndDate']), "m/d/Y")){
		    								echo "<td class='no'>" . date_format(date_create($Slip['StartDate']), "m/d/Y") . " - <br> " . date_format(date_create($Slip['EndDate']), "m/d/Y") . "</td>";
		    							} else {
		    								echo "<td class='no'>" . date_format(date_create($Slip['StartDate']), "m/d/Y") . "</td>"; 
		    							}

		    						if($Cols["Consultant"] == "1") echo "<td>" . $Slip['Consultant'] . "</td>";
									if($Cols["Description"] == "1") echo "<td>" . htmlspecialchars_decode(stripslashes($Slip['Description'])) . "</td>";

									if($Cols["Hours"] == "1"){
										echo "<td>" . $Slip['Hours'] . "</td>";
									}

									if($Cols["Rate"] == "1"){
										echo "<td>$" . $Invoice['HourlyDefaultRate'] . "/h</td>";
									}

									if($Cols["Amount"] == "1"){
										if($Slip["SlipStatus"] == "BAR"){
											echo "<td>$" . $Slip['Hours'] * $Invoice['HourlyDefaultRate'] . "</td>";
										} else if($Slip["SlipStatus"] == "NC"){
											echo "<td>No Charge</td>";
										}
									}
								echo "</tr>";

								/* Calculate Totals */
									if($Slip['SlipStatus'] == "BAR"){
										$HourlyTotalWithoutExpenses += $Slip['Hours'] * $Invoice['HourlyDefaultRate'];
					        			$TotalHours += $Slip['Hours'];
									}
	    					} // End ForEach()
	    				}; // End if()
	    		?>

    			<?php
    				/* Expenses */
    					$Slips = "SELECT * FROM `Slip` WHERE `TSType` = 'Expense' AND `SlipStatus` != 'DNB' AND `InvoiceID` = '" . $Invoice["InvoiceID"] . "' ORDER BY `StartDate` ASC";
	        			$stm = $DatabaseConnection->prepare($Slips);
    					$stm->execute();
    					$Slips = $stm->fetchAll();
    					if($stm->rowCount() > 0){
    			?>
	    				</tbody>
		    		</table>
		    			<br>
		    			<br>
		    		<h2>Expenses</h2>
		    		<table border="0" cellspacing="0" cellpadding="0">
				        <thead>
				            <tr>
				            	<th>Date</th>
				            	<th>Description</th>
				            	<th>Price</th>
				            	<th>Quantity</th>
				            	<th>Total</th>
				            </tr>
				        </thead>
				        <tbody>
				            <?php
		    				
				            	$HasExpenses = true;

		    					foreach ($Slips as $Slip) {
		    						echo "<tr>";
			    						/* Date */
			    							if(date_format(date_create($Slip['StartDate']), "m/d/Y") != date_format(date_create($Slip['EndDate']), "m/d/Y")){
			    								echo "<td class='no'>" . date_format(date_create($Slip['StartDate']), "m/d/Y") . " - <br> " . date_format(date_create($Slip['EndDate']), "m/d/Y") . "</td>";
			    							} else {
			    								echo "<td class='no'>" . date_format(date_create($Slip['StartDate']), "m/d/Y") . "</td>"; 
			    							}

			    						echo "<td>" . htmlspecialchars_decode(stripslashes($Slip['Description'])) . "</td>";
			    						echo "<td>$" . $Slip['Price'] . "</td>";
			    						echo "<td>" . $Slip['Quantity'] . "</td>";

										if($Slip["SlipStatus"] == "BAR"){
											echo "<td>$" . $Slip['Price'] * $Slip['Quantity'] . "</td>";
										} else if($Slip["SlipStatus"] == "NC"){
											echo "<td>No Charge</td>";
										}

									echo "</tr>";

									/* Calculate Totals */
										if($Slip['SlipStatus'] == "BAR"){
						        			$TotalExpenses += $Slip['Price'] * $Slip['Quantity'];
										}
		    					} // End ForEach()
		    				}; // End if()
	        		?>

	        		<?php 
	        			if($HasProfServices){
	        		?>
				        <tr class="no-bck">
			                <td colspan="2"></td>
			                <td colspan="2">Total for Professional Services: </td>
			                <td colspan="2">
				                <?php 
				                    if($Invoice["InvoiceType"] == "Flat"){
				                        echo "$" . number_format($Invoice["FlatRate"] * $Invoice["FlatRateMonths"], 2);
				                    } else {
				                    	echo "$" . number_format($HourlyTotalWithoutExpenses, 2);
				                    }
				                ?>
			                </td>
			            </tr>
			        <?php 
			        	}

			        	if($HasExpenses){
			       	?>

			            <tr class="no-bck">
			                <td colspan="2"></td>
			                <td colspan="2">Total for Expenses: </td>
			                <td colspan="2">
				                <?php 
				                    echo "$" . number_format($TotalExpenses, 2);
				                ?>
			                </td>
			            </tr>
			        <?php } ?>

			        <tr class="no-bck">
		                <td colspan="2"></td>
		                <td colspan="2">Total: </td>
		                <td colspan="2">
			                <?php 
			                    /* Grand Total */
			                    $GrandTotal;
			                    if($Invoice["InvoiceType"] == "Flat"){
			                        $GrandTotal = $Invoice["FlatRate"] * $Invoice["FlatRateMonths"] + $TotalExpenses;
			                    } else {
			                    	$GrandTotal = $HourlyTotalWithoutExpenses + $TotalExpenses;
			                    }

			                    echo "$" . number_format($GrandTotal, 2);
			                ?>
		                </td>
		            </tr>

		            <tr class="no-bck">
		                <td colspan="2"></td>
		                <td colspan="2">Previous Balance: </td>
		                <td colspan="2">
			                <?php 
			                    /* Previous Balance */
			                    if($Invoice['PreviousBalance'] != "" || $Invoice['PreviousBalance'] != 0){
			                    	echo "$" . number_format($Invoice['PreviousBalance'], 2);

			                    	$GrandTotal += $Invoice['PreviousBalance'];
			                    }
			                 
			                ?>	
		                </td>
		            </tr>

		            <?php
		            /* Discount */
		            	if($Invoice["DiscountAmount"] > 0){
		            ?>
		            	<tr class="no-bck">
			                <td colspan="2"></td>
			                <td colspan="2">Discount: </td>
			                <td colspan="2">
				                <?php 
				                    if($Invoice["DiscountType"] == "1"){
				                        echo "-$" . number_format($Invoice['DiscountAmount'], 2);
				                        $GrandTotal -= $Invoice['DiscountAmount'];
				                    } else if($Invoice["DiscountType"] == "2"){
				                    	echo "-%" . $Invoice['DiscountAmount'];
				                        $GrandTotal -= ((100-$Invoice["DiscountAmount"]) / 100);
				                    }
				                ?>
			                </td>
			            </tr>
		            <?php
		            	}
		          	?>

		            <tr class="no-bck">
		                <td colspan="2"></td>
		                <td colspan="2">GRAND TOTAL</td>
		                <td colspan="2">
		                    <?php

		                        // Add Previous Bill....

		                        echo "$" . number_format($GrandTotal, 2);
		                    ?>
		                </td>
		            </tr>
		        </tbody>
		    </table>
		    
		    <div class="thanks">Thank you!</div>
		    <div class="notices">
		        <?php
		        	/* Flat Rate "Discount Includes" */
		        		if($Invoice["InvoiceType"] == "Flat"){
		        			$TotalMinusFlatRate = floor( ($TotalHours * $Invoice["HourlyDefaultRate"] - $Invoice["FlatRate"]) / 10) * 10;		        			
		        			if($TotalMinusFlatRate > 250){
		        				echo "<p><strong>Due to the annual agreement, the amount due already includes a discount of more then $" . $TotalMinusFlatRate . "!</strong></p>";
		        			}
		        		}

		        	/* Invoice Notes */
		        	echo "<p><strong>" . $Invoice['InvoiceNotes'] . "</p></strong>";
		        ?>
		    </div>
	</main>
<?php require_once(SYSPATH ."/Apps/BillingPortal/Invoices/Assets/Views/InvoiceFooter.php"); ?>