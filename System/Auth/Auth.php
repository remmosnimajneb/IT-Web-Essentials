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
* Authentication Page
* Check if user exists and password hash is correct
* If good, Set proper cookies for user
*/

/* Page Variables */
$PageSecurityLevel = 0;

/* Include System Functions */
require_once("../../InitSystem.php");

//Hash the Password from the form and SQL Safe the Username
$Email = EscapeSQLEntry($_POST['Email']);
$Password = EscapeSQLEntry($_POST['Password']);

//Check Database if a user exists
$Login = "SELECT * FROM `User` WHERE `Email` = '" . $Email . "' LIMIT 1";
$stm = $DatabaseConnection->prepare($Login);
$stm->execute();
$records = $stm->fetchAll();
$RowCount = $stm->rowCount();

//Now check if a user exists and that the password matches
if($RowCount > 0 && password_verify($Password, $records[0]["Password"])){
	
	// Set the Sessions
	$_SESSION['IsLoggedIn'] = true;
	$_SESSION['UserID'] = $records[0]["UserID"];
	$_SESSION['Email'] = $records[0]["Email"];
	$_SESSION['Name'] = $records[0]["Name"];
	$_SESSION['ConsultantSlug'] = $records[0]["ConsultantSlug"];

	//Redirect
		if($_POST['Resource'] != ""){
			header('Location: ' . $_POST['Resource']);
			die();
		}
			header('Location: /Switchboard.php');
} else {
	// Kill the Sessions and back to Login

	// Set the Sessions
	$_SESSION['IsLoggedIn'] = false;
	$_SESSION['UserID'] = null;
	$_SESSION['Email'] = null;
	$_SESSION['Name'] = null;
	$_SESSION['ConsultantSlug'] = null;
	header('Location: /System/Auth/Login.php?Message=Error, Username or Password incorrect!');
}