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
* Login Page
*/

/* Page Variables */
$PageSecurityLevel = 0;
$PageName = "Login";

/* Include System Functions */
require_once("../../InitSystem.php");

/* Include Header */
require_once(SYSPATH . '/Assets/Views/Header.php');
?>
<!-- Main -->
<div id="main">
	<!-- Content -->
		<section id="content" class="default">
			<header class="major">
				<h2>Please Login</h2>
			</header>
			<div class="content">
				<h3 style="text-align: center;">
					<?php if(isset($_GET['Message'])) echo $_GET['Message']; ?>
				</h3>
				<form action="/System/Auth/Auth.php" method="POST">
					<input type="hidden" name="Resource" value="<?php echo $_GET['Resource']; ?>">
					Email: <input type="text" name="Email"><br>
					Password: <input type="password" name="Password"><br>
					<input type="submit" name="" value="Login">
				</form>
			</div>
		</section>
</div>
<?php require_once(SYSPATH . '/Assets/Views/Footer.php');  ?>