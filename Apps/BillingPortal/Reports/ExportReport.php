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
* Export Report
*/

/* Page Variables */
$PageSecurityLevel = 1;
$AppName = "BillingPortalAdmin";
$PageName = "Export Reports";

/* Include System Functions */
require_once("../../../InitSystem.php");

/* Headers */
	
	$FileName = GetSysConfig("SiteTitle") . " BillingPortal Slips Report - " . date("l jS \of F Y h:i:s A") . ".csv";

	header('Content-Type: text/csv; charset=utf-8');
	header("Content-Disposition:attachment;filename=" . $FileName); 
	$Output = fopen("php://output",'w') or die("Can't open php://output");
	
	/* Get Report Type [Slips, Invoices] */
		$ReportType = EscapeSQLEntry($_POST['ReportType']);

			/* Slips */
				if($ReportType == "Slips"){

					/* We need to Build the SQL Query */

						/* Columns */
							$Columns = array();
							$ColumnsNice = array();

							/* Slips */
							foreach ($_POST['Slip_SlipColumns'] as $Col) {
								array_push($Columns, "S.`" . $Col . "`");
								array_push($ColumnsNice, $Col);
							}

							/* Invoice */
							foreach ($_POST['Slip_InvoiceColumns'] as $Col) {
								array_push($Columns, "I.`" . $Col . "`");
								array_push($ColumnsNice, $Col);
							}

							/* Client */
							foreach ($_POST['Slip_ClientColumns'] as $Col) {
								array_push($Columns, "C.`" . $Col . "`");
								array_push($ColumnsNice, $Col);
							}

							/* Consultant */
							foreach ($_POST['Slip_UserColumns'] as $Col) {
								array_push($Columns, "U.`" . $Col . "`");
								array_push($ColumnsNice, $Col);
							}
					
						/* Now let's build the WHERE clauses */
							// As of V2.2 all WHERE's are just AND's we don't support OR!
							$Where = "";

							for ($i = 1; $i <= $_POST['Slip_NumberOfFilters']; $i++) { 
								
								if(empty($Where)){
									$Where = " WHERE ";
								} else {
									$Where .= " AND ";
								}

								$Where .= EscapeSQLEntry($_POST['Filter_' . $i . '_Column']);

									// Condition
										switch (EscapeSQLEntry($_POST['Filter_' . $i . '_Condition'])) {
											case 'Equal':
												$Where .= " = '" . EscapeSQLEntry($_POST['Filter_' . $i . '_Value']) . "'";
												break;

											case 'LIKE':
												$Where .= " LIKE '" . EscapeSQLEntry($_POST['Filter_' . $i . '_Value']) . "'";
												break;

											case '%Like%':
												$Where .= " LIKE '%" . EscapeSQLEntry($_POST['Filter_' . $i . '_Value']) . "%'";
												break;

											case 'LessThan':
												$Where .= " < '" . EscapeSQLEntry($_POST['Filter_' . $i . '_Value']) . "'";
												break;

											case 'MoreThan':
												$Where .= " > '" . EscapeSQLEntry($_POST['Filter_' . $i . '_Value']) . "'";
												break;

											case 'NotLike':
												$Where .= " != '" . EscapeSQLEntry($_POST['Filter_' . $i . '_Value']) . "'";
												break;
										}

							}

						/* Finally let's assemble the Query */
							$Query = "SELECT ";

							// Cols
								foreach ($Columns as $Col) {
									$Query .= $Col . ",";
								}

							// Trim the ending "," of the last Col
								$Query = rtrim($Query, ",");


							// Add Tables
								$Query .= " FROM 
												`Slip` AS S 
													INNER JOIN
												`Client` AS C
														ON C.`ClientID` = S.`ClientID`
													INNER JOIN
												`User` AS U
														ON U.`UserID` = S.`Consultant`
													LEFT JOIN
												`Invoice` AS I
														ON I.`InvoiceID` = S.`InvoiceID`
											";
							// Add WHERE

								$Query .= $Where;

							// Order By

								$Query .= " ORDER BY `SlipID` ASC";

						/* Run and Export! */

							$Stm = $DatabaseConnection->prepare($Query);
							$Stm->execute();
							$Slips = $Stm->fetchAll(PDO::FETCH_ASSOC);

							/* Make the Array */
								// Add the headers
									fputcsv($Output, $ColumnsNice);

								// Each Slip Row
									foreach ($Slips as $Slip) {
										fputcsv($Output, $Slip);
									}

						// Export!
							fputcsv($Output);

				} else if($ReportType == "Invoice"){

					/* We need to Build the SQL Query */

						/* Columns */
							$Columns = array();
							$ColumnsNice = array();

							/* Invoice */
							foreach ($_POST['Invoice_InvoiceColumns'] as $Col) {

								if($Col == "InvoiceTotal"){
									array_push($Columns, "ComputeInvoiceTotal(`InvoiceID`)");
								} else {
									array_push($Columns, "I.`" . $Col . "`");
								}
								array_push($ColumnsNice, $Col);
							}

							/* Client */
							foreach ($_POST['Invoice_ClientColumns'] as $Col) {
								array_push($Columns, "C.`" . $Col . "`");
								array_push($ColumnsNice, $Col);
							}

					
						/* Now let's build the WHERE clauses */
							// As of V2.2 all WHERE's are just AND's we don't support OR!
							$Where = "";

							for ($i = 1; $i <= $_POST['Invoice_NumberOfFilters']; $i++) { 
								
								if(empty($Where)){
									$Where = " WHERE ";
								} else {
									$Where .= " AND ";
								}

								$Where .= EscapeSQLEntry($_POST['Filter_' . $i . '_Column']);

									// Condition
										switch (EscapeSQLEntry($_POST['Filter_' . $i . '_Condition'])) {
											case 'Equal':
												$Where .= " = '" . EscapeSQLEntry($_POST['Filter_' . $i . '_Value']) . "'";
												break;

											case 'LIKE':
												$Where .= " LIKE '" . EscapeSQLEntry($_POST['Filter_' . $i . '_Value']) . "'";
												break;

											case '%Like%':
												$Where .= " LIKE '%" . EscapeSQLEntry($_POST['Filter_' . $i . '_Value']) . "%'";
												break;

											case 'LessThan':
												$Where .= " < '" . EscapeSQLEntry($_POST['Filter_' . $i . '_Value']) . "'";
												break;

											case 'MoreThan':
												$Where .= " > '" . EscapeSQLEntry($_POST['Filter_' . $i . '_Value']) . "'";
												break;

											case 'NotLike':
												$Where .= " != '" . EscapeSQLEntry($_POST['Filter_' . $i . '_Value']) . "'";
												break;
										}

							}

						/* Finally let's assemble the Query */
							$Query = "SELECT ";

							// Cols
								foreach ($Columns as $Col) {
									$Query .= $Col . ",";
								}

							// Trim the ending "," of the last Col
								$Query = rtrim($Query, ",");


							// Add Tables
								$Query .= " FROM 
												`Invoice` AS I
													INNER JOIN
												`Client` AS C
														ON C.`ClientID` = I.`ClientID`
											";
							// Add WHERE

								$Query .= $Where;

							// Order By

								$Query .= " ORDER BY `InvoiceID` ASC";

						/* Run and Export! */

							$Stm = $DatabaseConnection->prepare($Query);
							$Stm->execute();
							$Slips = $Stm->fetchAll(PDO::FETCH_ASSOC);

							/* Make the Array */
								// Add the headers
									fputcsv($Output, $ColumnsNice);

								// Each Slip Row
									foreach ($Slips as $Slip) {
										fputcsv($Output, $Slip);
									}

						// Export!
							fputcsv($Output);

				}	