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
* Switchboard for Systems
*/
/* Include System Functions */
require_once("../../SystemFunctions.php");

/* Init System */
Init(false);

$PageName = "Billing Portal";
require_once('../../SystemAssets/Views/BillingPortalHeader.php'); 

?>
<!-- Main -->
	<div id="main" style="width: 95vw;">
	<!-- Content -->
		<section id="content" class="default">
			<header class="major">
				<h2>Billing Portal</h2>
				<h3>Welcome back <?php echo $_SESSION['Name']; ?></h3>
			</header>
			<div class="content">
				<h3 style="text-align: center;"><?php if(isset($Message)) echo $Message; ?></h3>
				<section style="text-align: center;">
					<div class="row" style="justify-content: center;">
						<div class="column">
							<a href="Slips" class="button large">Slips</a>
						</div>
						<div class="column">
							<a href="Invoices" class="button large">Invoices</a>
						</div>
						<div class="column">
							<a href="Clients" class="button large">Clients</a>
						</div>
					</div>
				</section>
			</div>
		</section>
	</div>
<?php require_once('../../SystemAssets/Views/BillingPortalFooter.php');  ?>