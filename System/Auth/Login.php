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
* Login Page
*/

//Include functions
require_once("../../SystemFunctions.php");

$PageName = "Login";
require_once('../../SystemAssets/Views/BillingPortalHeader.php');  

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
					Email: <input type="text" name="Email"><br />
					Password: <input type="password" name="Password"><br />
					<input type="submit" name="" value="Login">
				</form>
			</div>
		</section>
</div>
<?php require_once('../../SystemAssets/Views/BillingPortalFooter.php');  ?>