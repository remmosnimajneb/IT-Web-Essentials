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
* Switchboard for System Setup
*/
/* Include System Functions */
require_once("../InitSystem.php");

$PageName = "System Setup";
require_once(SYSPATH . '/Assets/Views/Header.php');
?>
<!-- Main -->
	<div id="main" style="width: 95vw;">
	<!-- Content -->
		<section id="content" class="default">
			<header class="major">
				<h2>System Setup</h2>
			</header>
			<div class="content">
				<h3 style="text-align: center;"><?php if(isset($Message)) echo $Message; ?></h3>
				<section style="text-align: center;">
					<div class="row" style="justify-content: center;">
						<div class="column">
							<a href="Users" class="button large">Users</a>
						</div>
						<div class="column">
							<a href="Config" class="button large">System Preferences</a>
						</div>
					</div>
				</section>
			</div>
		</section>
	</div>
<?php require_once(SYSPATH . '/Assets/Views/Footer.php');  ?>