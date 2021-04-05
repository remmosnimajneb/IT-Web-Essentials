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
* System CRON Job Handler
*
* This script fires off all the various CRON jobs needed for the system
* (Note, as of V2.2 it's really just for Subscriptions.)
*/

	/* First we need to "Init" */
		$PageSecurityLevel = 3;
		$AppName = "SYSTEM_INTERNAL";
		require_once("../../InitSystem.php");

	/* Next let's pull all invoices which need a new Slip */

		// A Word to the wise, if you think you can edit this, don't.
		$SQL = "SELECT 
				    *
				FROM
				    `Subscription` AS Subscr
				WHERE
				    (
				        (`Frequency` = 'Daily' AND `Status` = 1)    /* Daily Recurr date can be whatever, we ignore it, as we ALWAYS output Daily */
				            OR
				        (
				            /* End Of Month */
				            (`RecurrenceOn` = 'Last')
				                AND
				                (
				                    (`Frequency` = 'Monthly' AND (DAY(LAST_DAY(CURDATE())) = DAY(CURDATE())) )  /* Every Month, Last day of the Month */
				                        OR
				                    (`Frequency` = 'Yearly'  AND (MONTH(NOW()) = MONTH(`StartDate`) AND ( DAY(NOW()) = DAY(LAST_DAY(`StartDate`)) ) ))  /* Only once a month, Last day of it */
				                )
				        )
				            OR
				        (
				            /* End Of Month */
				            (`RecurrenceOn` = 'First')
				                AND
				                (
				                    (`Frequency` = 'Monthly' AND (DAY(CURDATE()) = 1 ) )  /* Every Month, First day of the Month */
				                        OR
				                    (`Frequency` = 'Yearly'  AND (MONTH(NOW()) = MONTH(`StartDate`) AND ( DAY(NOW()) = 1 ) ))  /* Only once a month, First day of it */
				                )
				        )
				            OR
				        (
				            (`RecurrenceOn` = 'Date')	/* Specific Date */
				                AND
				                (
				                    ( MONTH(NOW()) = MONTH(`RecurrenceDate`) ) AND ( DAY(NOW()) = DAY(`RecurrenceDate`) )	/* Month and Day equal */
				                )
				        )
				    )
				        AND
				    (
				        `Status` = 1			/* Status OK */
				    )";

		$Stm = $DatabaseConnection->prepare($SQL);
		$Stm->execute();

			foreach ($Stm->fetchAll() as $Subscr) {

				$Message = "Auto-Generated via Subscription ID #" . $Subscr['SubscriptionID'] . " on " . date("m/d/Y") . ".";

				if($Subscr['SlipType'] == "TS"){
					$Subscr['Price'] = 0;
					$Subscr['Quantity'] = 0;
					$Subscr['Hours'] = $Subscr['Cost'];
				} else {
					$Subscr['Price'] = $Subscr['Cost'];
					$Subscr['Quantity'] = $Subscr['Quantity'];
					$Subscr['Hours'] = 0;
				}
				
				$Slip = "INSERT INTO `Slip` (
							TSType, 
							Consultant, 
							ClientID, 
							StartDate, 
							EndDate, 
							Hours, 
							DNB, 
							Description, 
							Price, 
							Quantity,
							InternalNotes
						) VALUES (
							'" . EscapeSQLEntry($Subscr['SlipType']) . "',
							'1',
							'" . EscapeSQLEntry($Subscr['ClientID']) . "',
							'" . date("Y-m-d") . "',
							'" . date("Y-m-d") . "',
							'" . EscapeSQLEntry($Subscr['Hours']) . "',
							'0',
							'" . EscapeSQLEntry($Subscr['SubscriptionName']) . "',
							'" . EscapeSQLEntry($Subscr['Price']) . "',
							'" . EscapeSQLEntry($Subscr['Quantity']) . "',
							'" . $Message . "'
						)";
				$Stm = $DatabaseConnection->prepare($Slip);
				$Stm->execute();
			}

	/* Update Last Run */
		$SQL = "UPDATE 
				    `Subscription`
				SET `LastRunDate` = CURDATE()
				WHERE
				    (
				        (`Frequency` = 'Daily' AND `Status` = 1)    /* Daily Recurr date can be whatever, we ignore it, as we ALWAYS output Daily */
				            OR
				        (
				            /* End Of Month */
				            (`RecurrenceOn` = 'Last')
				                AND
				                (
				                    (`Frequency` = 'Monthly' AND (DAY(LAST_DAY(CURDATE())) = DAY(CURDATE())) )  /* Every Month, Last day of the Month */
				                        OR
				                    (`Frequency` = 'Yearly'  AND (MONTH(NOW()) = MONTH(`StartDate`) AND ( DAY(NOW()) = DAY(LAST_DAY(`StartDate`)) ) ))  /* Only once a month, Last day of it */
				                )
				        )
				            OR
				        (
				            /* End Of Month */
				            (`RecurrenceOn` = 'First')
				                AND
				                (
				                    (`Frequency` = 'Monthly' AND (DAY(CURDATE()) = 1 ) )  /* Every Month, First day of the Month */
				                        OR
				                    (`Frequency` = 'Yearly'  AND (MONTH(NOW()) = MONTH(`StartDate`) AND ( DAY(NOW()) = 1 ) ))  /* Only once a month, First day of it */
				                )
				        )
				            OR
				        (
				            (`RecurrenceOn` = 'Date')	/* Specific Date */
				                AND
				                (
				                    ( MONTH(NOW()) = MONTH(`RecurrenceDate`) ) AND ( DAY(NOW()) = DAY(`RecurrenceDate`) )	/* Month and Day equal */
				                )
				        )
				    )
				        AND
				    (
				        `Status` = 1			/* Status OK */
				    )";

		$Stm = $DatabaseConnection->prepare($SQL);
		$Stm->execute();
