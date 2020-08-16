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
* 404 Error
*/

/* Include System Functions */
require_once("../../InitSystem.php");

$PageName = "404 Page Not Found";
require_once(SYSPATH . '/Assets/Views/Header.php');
?>
<!-- Main -->
	<div id="main" style="width: 95vw;">
	<!-- Content -->
		<section id="content" class="default">
			<header class="major">
				<h2>404 Page Not Found</h2>
				<h3>Looks like we couldn't find that!</h3>
			</header>
			<div class="content" style="text-align: center;">
				<a class="button large" href="/Switchboard.php">Switchboard</a>
			</div>
		</section>
	</div>
<?php require_once(SYSPATH . '/Assets/Views/Footer.php');  ?>