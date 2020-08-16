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
* Switchboard for Systems
*/

/* Page Variables */
$PageSecurityLevel = 1;
$PageName = "Switchboard";

/* Include System Functions */
require_once("InitSystem.php");

/* If User doesn't have Admin Access, just go to Apps */
if(GetUserSecurityLevel($_SESSION['UserID']) == 1){
	header('Location: /Apps/Switchboard.php');
	die();
}

/* Include Header */
require_once(SYSPATH . '/Assets/Views/Header.php');
?>
<!-- Main -->
	<div id="main" style="width: 95vw;">
	<!-- Content -->
		<section id="content" class="default">
			<header class="major">
				<h2>Switchboard</h2>
				<h3>Welcome back <?php echo $_SESSION['Name']; ?></h3>
			</header>
			<div class="content">
				<h3 style="text-align: center;"><?php if(isset($Message)) echo $Message; ?></h3>
				<section style="text-align: center;">
					<div class="row" style="justify-content: center;">
						<div class="column">
							<a href="Apps" class="button large">Apps</a>
						</div>
						<div class="column">
							<a href="System" class="button large">System Configuration</a>
						</div>
					</div>
				</section>
			</div>
		</section>
	</div>
<?php require_once(SYSPATH . '/Assets/Views/Footer.php');  ?>