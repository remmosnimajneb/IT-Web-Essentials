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
* Switchboard for Apps
*/

/* Page Variables */
$PageSecurityLevel = 1;
$AppName = "Apps";
$PageName = "Apps";

/* Include System Functions */
require_once("../InitSystem.php");

require_once(SYSPATH . '/Assets/Views/Header.php');
?>
<!-- Main -->
	<div id="main" style="width: 95vw;">
	<!-- Content -->
		<section id="content" class="default">
			<header class="major">
				<h2>Apps</h2>
			</header>
			<div class="content">
				<h3 style="text-align: center;"><?php if(isset($Message)) echo $Message; ?></h3>
				<section style="text-align: center;">
					<div class="row" style="justify-content: center;">
						<?php
							if( ((json_decode(GetSysConfig("ActivatedSystems"), true)["BillingPortalUser"] == "1") && (json_decode(GetUserSysPermissions($_SESSION['UserID']), true)["BillingPortalUser"] == "1")) || ((json_decode(GetSysConfig("ActivatedSystems"), true)["BillingPortalAdmin"] == "1") && (json_decode(GetUserSysPermissions($_SESSION['UserID']), true)["BillingPortalAdmin"] == "1")) ){
								?>
									<div class="column">
										<a href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal" class="button large">Billing</a>
									</div>
						<?php }

							if( (json_decode(GetSysConfig("ActivatedSystems"), true)["Jots"] == "1") && (json_decode(GetUserSysPermissions($_SESSION['UserID']), true)["Jots"] == "1") ){
								?>
									<div class="column">
										<a href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/Jots" class="button large">Jots</a>
									</div>
						<?php }

							if( (json_decode(GetSysConfig("ActivatedSystems"), true)["Inventory"] == "1") && (json_decode(GetUserSysPermissions($_SESSION['UserID']), true)["Inventory"] == "1") ){
								?>
									<div class="column">
										<a href="<?php echo GetSysConfig("SystemURL"); ?>/Apps/Inventory" class="button large">Inventory</a>
									</div>
						<?php } ?>
					</div>
				</section>
			</div>
		</section>
	</div>
<?php require_once(SYSPATH . '/Assets/Views/Footer.php');  ?>