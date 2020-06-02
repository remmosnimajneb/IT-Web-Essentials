<?php
/********************************
* Project: IT Web Essentials 
* Code Version: 1.0
* Author: Benjamin Sommer
* Company: The Berman Consulting Group - https://bermangroup.com
* GitHub: https://github.com/remmosnimajneb
* Theme Design by: Pixelarity [Pixelarity.com]
* Licensing Information: https://pixelarity.com/license
***************************************************************************************/

/*
* Logout page
* Destroys all Sessions
*/

$_SESSION['IsLoggedIn'] = false;
$_SESSION['Email'] = null;
$_SESSION['Name'] = null;
$_SESSION['ConsultantSlug'] = null;
header('Location: Login.php?msg=Logout Success');

?>