<?php
/********************************
* Project: IT Web Essentials - Modular based Portal System for Inventory, Billing, Service Desk and More! 
* Code Version: 2.2
* Author: Benjamin Sommer - BenSommer.net | GitHub @remmosnimajneb
* Company: The Berman Consulting Group - BermanGroup.com
* Theme Design by: Pixelarity [Pixelarity.com]
* Licensing Information: https://pixelarity.com/license
***************************************************************************************/

/*
* Show Quote
*/

   /* Page Variables */
	$PageSecurityLevel = 0;
	$AppName = "BillingPortalAdmin";
	$PageName = "Quote";

	/* Include System Functions */
	require_once("../../../InitSystem.php");

    $IsAdmin = true;

    /* If Quote is being Approved */
	if(isset($_POST['Action'])){

		if($_POST['Action'] == "ApproveQuote"){

			$SQL = "UPDATE `Quote` SET `Approved` = '1', `SignedName` = '" . EscapeSQLEntry($_POST['SignPrint']) . "', `Signature` = '" . EscapeSQLEntry($_POST['Base64SignPadVal']) . "', `SignedDate` = CURRENT_TIMESTAMP WHERE `Hash` = '" . EscapeSQLEntry($_POST['ID']) . "'";
			$stm = $DatabaseConnection->prepare($SQL);
			$stm->execute();

			$Msg = "<span style='color:green'>Quote has been approved successfully!</span>";

		}

	}


    /* Check it's a real Quote */
    $Quote = "SELECT * FROM `Quote` AS Q INNER JOIN `Client` AS C ON C.`ClientID` = Q.`ClientID` WHERE `Hash` = '" . EscapeSQLEntry($_GET['ID']) . "'";
    $stm = $DatabaseConnection->prepare($Quote);
    $stm->execute();
    $Quote = $stm->fetchAll()[0];
    $SendToID = $Quote['QuoteID'];

    //Now check the ID is a reall Quote
    if($stm->rowCount() == 0 || ($Quote['QuoteStatus'] == "0" && $_SESSION['IsLoggedIn'] != TRUE)){
        header('Location: ' . GetSysConfig("SystemURL") . '/404');
        die();
    }

    /* Check if User is Loggedin */
    if(!$_SESSION['IsLoggedIn']){
    	$IsAdmin = false;
    }

    /* Get Header */
    require_once(SYSPATH . "/Apps/BillingPortal/Assets/Views/InvoiceHeader.php");

    /* Explode Line Items */
    	$LineItems = json_decode($Quote['LineItems'], true)['Items'];

    	$TotalQuoteCost = 0;

?>
	<main>
		<div class="row contacts">
	        <div class="col invoice-to">
	            <div class="text-gray-light">Prepared for:</div>
	            <h2 class="to"><?php echo $Quote["ClientName"]; ?></h2>
	            <div class="address"><?php echo $Quote["StreetName"] . ", " . $Quote["City"] . ", " . $Quote["State"] . "  " . $Quote["ZIP"]; ?></div>
	            <div class="email"><a href="mailto:<?php echo $Quote["Email"]; ?>"><?php echo $Quote["Email"]; ?></a></div>
	        </div>
	        <div class="col invoice-details">
	            <h1 class="invoice-id">Quote #<?php echo $Quote["QuoteID"]; ?></h1>
                <div class="date">Date of Quote: <?php echo date("m/d/Y", strtotime( $Quote["Date"])); ?></div>
	        </div>
	    </div>
	    	<br>
	    <?php
	    	if($Quote['Approved'] != "1" && (strtotime($Quote['ExpDate']) >= time())){
	    		?>
	    			<div class="quote_alert">
	    				<i class="fa fa-exclamation-circle"></i>
	    				<p><a href="#approvequote">This quote has not been approved.<br>Please review and sign below.</a></p>
	    			</div>
	    		<?php
	    	}

	    	// Message
	    	if(isset($Msg)){
	    		?><p><?php echo $Msg; ?></p><?php
	    	}
	    ?>
	    	<br>
		<table border="0" cellspacing="0" cellpadding="0">
	        <thead>
	            <tr>
	            	<th></th>
	                <th>Name</th>
	                <th>Price</th>
	                <th>Quantity</th>
	                <th>Ext. Price</th>
	            </tr>
	        </thead>
	        <tbody>
	        		<?php
	        				$i = 0;
	        				foreach ($LineItems as $Item) {
	        					$i++;
	        					?>

	        						<tr>
	        							<td class="no"><?php echo $i; ?></td>
	        							<td><?php echo $Item['Name']; ?></td>
		        						
		        							<?php
		        								$ItemTotal = $Item['Hours'] * $Quote['HourlyDefaultRate'];
			        								
			        								if($Item['Type'] == "Expense"){
			        									$ItemTotal = $Item['Price'];
			        								}
		        							?>

		        						<td>$<?php echo number_format($ItemTotal, 2); ?></td>

		        							<?php
		        								$ItemQty = "N/A";
			        								if($Item['Type'] == "Expense"){
			        									$ItemQty = $Item['Quantity'];
			        								}
			        						?>

		        						<td><?php echo $ItemQty; ?></td>
		        							
		        							<?php
		        								$ItemExtTotal = $Item['Hours'] * $Quote['HourlyDefaultRate'];
			        								if($Item['Type'] == "Expense"){
			        									$ItemExtTotal = $Item['Price'] * $Item['Quantity'];
			        								}

		        								$TotalQuoteCost += $ItemExtTotal;
			        						?>

			        					<td>$<?php echo number_format($ItemExtTotal, 2); ?></td>
	        						</tr>

	        					<?php
	    						
	    					} // End ForEach()
	    		?>

			        <tr class="no-bck">
		                <td colspan="2"></td>
		                <td colspan="2">Total: </td>
		                <td colspan="2">
			                <?php
			                    echo "$" . number_format($TotalQuoteCost, 2);
			                ?>
		                </td>
		            </tr>

		            <?php
		            /* Discount */
		            	if($Quote["Discount"] > 0){
		            ?>
		            	<tr class="no-bck">
			                <td colspan="2"></td>
			                <td colspan="2">Discount: </td>
			                <td colspan="2">
				                <?php 
				                   
				                   	echo "-$" . number_format($Quote['Discount'], 2);
				                    $TotalQuoteCost -= $Quote['Discount']; 

				                ?>
			                </td>
			            </tr>
		            <?php
		            	}
		          	?>

		          	<?php
		            /* Fee */
		            	if($Quote["Fee"] > 0){
		            ?>
		            	<tr class="no-bck">
			                <td colspan="2"></td>
			                <td colspan="2">Fee's: </td>
			                <td colspan="2">
				                <?php 
				                   
				                   	echo "+$" . number_format($Quote['Fee'], 2);
				                    $TotalQuoteCost += $Quote['Fee']; 

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
		                        echo "$" . number_format($TotalQuoteCost, 2);
		                    ?>
		                </td>
		            </tr>
		        </tbody>
		    </table>
		    	<hr>
		    <div class="approval" id="approvequote">
		    	<?php
		    		if(strtotime($Quote['ExpDate']) < time() && $Quote['Approved'] != "1"){

		    			?>
		    				<h2>This Quote has expired. Please email us at <a href="mailto:<?php echo GetSysConfig("BrandingSupportEmail"); ?>"><?php echo GetSysConfig("BrandingSupportEmail"); ?></a> for more help.</h2>
		    			<?php

		    		} else if($Quote['Approved'] != "1"){
		    			?>
		    				<h2>Approve Quote</h2>
		    					<br>
					    	<form method="POST" action="<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Quotes/Quote.php?ID=<?php echo $_GET['ID']; ?>" onsubmit="return ValidateSign()">
					    		
					    		<input type="hidden" name="ID" value="<?php echo $_GET['ID']; ?>">
					    		<input type="hidden" name="Action" value="ApproveQuote">
					    			
					    			<p>Signature (Print):</p>
					    				<input type="text" name="SignPrint" required="required">
					    				<br>
									<p>Signature (Signed):</p>
										<canvas id="Signature" style="border: 1px black solid"></canvas>
										<input type="hidden" name="Base64SignPadVal" value="" id="Base64SignPadVal">
									<br><br>
								<span class="button-fancy" onclick="ClearPad()">Clear Ink</span>
									<br><br>
								<input type="submit" name="Submit" value="Submit" class="button-fancy">
					    	</form>
		    			<?php
		    		} else {
		    			?>
		    				<h2>Quote approved by <u><?php echo $Quote['SignedName']; ?></u> on <u><?php echo date("m/d/Y", strtotime($Quote["SignedDate"])); ?></u></h2>
		    					<br><br>
		    				<h4>Need to make adjustments? Want to schedule an install time? Something else? Email us at <a href="mailto:<?php echo GetSysConfig("BrandingSupportEmail"); ?>"><?php echo GetSysConfig("BrandingSupportEmail"); ?></a>.</h4>
		    			<?php
		    		}
		    	?>
		    </div>
		    
		    <div class="thanks"></div>
		    <div class="notices"></div>
	</main>
<?php require_once(SYSPATH ."/Apps/BillingPortal/Assets/Views/InvoiceFooter.php"); ?>