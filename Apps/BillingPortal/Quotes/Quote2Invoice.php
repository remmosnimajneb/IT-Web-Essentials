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
* Quote2Invoice
* Convert a Price Quote into an Invoice
* Kept seperate for Cleanlieness
*/

/* Check we're not calling directly */
	if(!defined('SYSPATH')){
		header('Location: ' . GetSysConfig("SystemURL") . '/404');
		exit();
	}

/* No security checks since this can only be called AFTER we've already Authenticate()'ed */
	
	/*
	* @Param $QuoteID - int() ID of Quote
	* @Return (int) - Invoice ID of created Invoice
	*/
	function Quote2Invoice($QuoteID){

		global $DatabaseConnection;

		/*
		* Get the Quote
		*/
			$SQL = "SELECT * FROM `Quote` WHERE `QuoteID` = '" . EscapeSQLEntry($QuoteID) . "'";
			$stm = $DatabaseConnection->prepare($SQL);
			$stm->execute();
			$Quote = $stm->fetchAll()[0];

		/*
		* Check Invoice not already made for this Quote
		*/	
			$InvoiceID = 0;

			if(!empty($Quote['InvoiceID'])){

				$InvoiceID = $Quote['InvoiceID'];

				/*
				* Update Invoice 
				*/
					$SQL = "UPDATE `Invoice` SET `Date` = '" . $Quote['Date'] . "', `ClientID` = '" . $Quote['ClientID'] . " WHERE `InvoiceID` = '" . $InvoiceID . "'";
					$stm = $DatabaseConnection->prepare($SQL);
					$stm->execute();

					/* Drop all previous Slips so we can re-add properly */
						$SQL = "DELETE FROM `Slip` WHERE `InvoiceID` = '" . $InvoiceID . "'";
						$stm = $DatabaseConnection->prepare($SQL);
						$stm->execute();

			} else {

				/*
				* Create the Invoice
				*/
					$ColumnsToShow->Consultant = "1";
					$ColumnsToShow->Description = "1";
					$ColumnsToShow->Hours = "1";
					$ColumnsToShow->Rate = "1";
					$ColumnsToShow->Amount = "1";

					$ColumnsToShow = json_encode($ColumnsToShow);

					$SQL = "INSERT INTO `Invoice` (
								`InvoiceID`, 
								`InvoiceHash`, 
								`ClientID`, 
								`InvoiceType`, 
								`InvoiceDate`, 
								`FlatRateMonths`, 
								`PreviousBalance`, 
								`DiscountType`, 
								`DiscountAmount`, 
								`ColumnsToShow`, 
								`InvoiceStatus`, 
								`PaymentStatus`, 
								`PaymentAmount`, 
								`PaymentNotes`, 
								`PaymentDate`, 
								`InternalNotes`, 
								`InvoiceNotes`
							) VALUES (
								NULL, 
								'" . bin2hex(random_bytes(32)) . "', 
								'" . $Quote['ClientID'] . "', 
								'Hourly', 
								'" . $Quote['Date'] . "', 
								'0', 
								'0', 
								'1', 
								'', 
								'" . $ColumnsToShow . "', 
								'0', 
								'0', 
								'',
								'', 
								CURRENT_TIMESTAMP(), 
								NULL,
								NULL
							)";
					$stm = $DatabaseConnection->prepare($SQL);
					$stm->execute();

					$InvoiceID = $DatabaseConnection->lastInsertId();

				/*
				* Update Quote with new InvoiceID
				*/
					$SQL = "UPDATE `Quote` SET `InvoiceID` = '" . $InvoiceID . "'";
					$stm = $DatabaseConnection->prepare($SQL);
					$stm->execute();
			}

		/*
		* Insert Line Items as Slips
		*/	
			$LineItems = json_decode($Quote['LineItems'], true);
			foreach ($LineItems['Items'] as $Item) {
				
				$SQL = "INSERT INTO `Slip` (
							`SlipID`, 
							`TSType`, 
							`Consultant`, 
							`ClientID`, 
							`StartDate`, 
							`EndDate`, 
							`Hours`, 
							`DNB`, 
							`Description`, 
							`InternalNotes`, 
							`CategoryID`, 
							`Price`,
							`Quantity`, 
							`SlipStatus`, 
							`InvoiceID`
						) VALUES (
							NULL, 
							'" . $Item['Type'] . "',
							'1',
							'" . $Item['ClientID'] . "',
							'" . $Quote['Date'] . "',
							'" . $Quote['Date'] . "',
							'" . $Item['Hours'] . "',
							'0', 
							'" . $Item['Name'] . "',
							NULL, 
							NULL, 
							'" . $Item['Price'] . "',
							'" . $Item['Quantity'] . "',
							'" . $Item['Status'] . "',
							'" . $InvoiceID . "'
				)";
				$stm = $DatabaseConnection->prepare($SQL);
				$stm->execute();

			}


		/* Return InvoiceID */
		return $InvoiceID;

	}