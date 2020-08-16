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
* Logout of Client Portal
*/


$_SESSION['ClientPortal_IsLoggedIn'] = false;
$_SESSION['ClientPortal_ClientUserID'] = null;
$_SESSION['ClientPortal_Email'] = null;
$_SESSION['ClientPortal_Name'] = null;

header('Location: Login.php');