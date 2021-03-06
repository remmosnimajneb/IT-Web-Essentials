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
* Logout page
* Destroys all Sessions
*/

// No Init System needed, we're just killing sessions

$_SESSION['IsLoggedIn'] = false;
$_SESSION['UserID'] = null;
$_SESSION['Email'] = null;
$_SESSION['Name'] = null;
$_SESSION['ConsultantSlug'] = null;
header('Location: Login.php?Message=Logout Success');

?>