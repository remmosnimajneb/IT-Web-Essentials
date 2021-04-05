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
* Index File
* This allows us to check if the Request Path is a Jot, let's redirect to it, if not, show an error
*/

/* Page Variables */
$PageSecurityLevel = 0;

/* Include System Functions */
require_once("InitSystem.php");

	/* Check if Jot */
	$Jot = "SELECT `JotID` FROM `Jot` WHERE `JotSlug` = '" . $_GET['path'] . "'";
	$stm = $DatabaseConnection->prepare($Jot);
	$stm->execute();

	if($stm->rowCount() > 0){
		header('Location: ' . GetSysConfig("SystemURL") . '/Apps/Jots/Public/' . $_GET['path']);
		exit();
	} 

	/* Otherwise let's throw an Error */
	header('Location: ' . GetSysConfig("SystemURL") . '/System/Error/403.php');