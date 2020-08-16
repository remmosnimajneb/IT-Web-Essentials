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
* 403 Access Denied
*/

/* Include System Functions */
require_once("../../InitSystem.php");

$PageName = "403 Access Denied";
require_once(SYSPATH . '/Assets/Views/Header.php');
?>
<!-- Main -->
	<div id="main" style="width: 95vw;">
	<!-- Content -->
		<section id="content" class="default">
			<header class="major">
				<h2>403 Access Denied</h2>
			</header>
			<div class="content" style="text-align: center;">
				<?php
					if(GetUserSecurityLevel($_SESSION['UserID']) > 0){
						echo '<a class="button large" href="/Switchboard.php">Switchboard</a>';
					} else {
						echo "Please contact Support for more information";
					}
				?>
			</div>
		</section>
	</div>
<?php require_once(SYSPATH . '/Assets/Views/Footer.php');  ?>