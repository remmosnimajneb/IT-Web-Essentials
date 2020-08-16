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
* Main System Page
* Get the Slug and redirect
*/


/* Page Variables */
$PageSecurityLevel = 0;
$AppName = "Jots";
$PageName = "Jots";

/* Include System Functions */
require_once("../../../InitSystem.php");

//Parse out the Slug
$JotValue = EscapeSQLEntry(basename($_SERVER['REQUEST_URI']));

// If Passcode has been submitted we need to specifiy the Slug
if(isset($_POST['JotSlug'])){
	$JotValue = EscapeSQLEntry($_POST['JotSlug']);
}

	//If Slug is blank, redirect to Company Home
	if($JotValue == "" || !isset($_SERVER['REQUEST_URI'])){
		header('Location: ' . $BrandingCompanyURL);
	}

//Check it's a real URL/ Short Note
$sql = "SELECT * FROM `Jot` WHERE `JotSlug` = '" . $JotValue . "'";
$stm = $DatabaseConnection->prepare($sql);
$stm->execute();
$records = $stm->fetchAll();
$RowCount = $stm->rowCount();

$BypassPassword = false;

// Now, check:
	// A. It's a real Jot
	// B. Passcode is not set OR Passcode is set and user got it right!

if( ($RowCount > 0) && (($records[0]["JotPassword"] == NULL) || ($records[0]["JotPassword"] == $_POST['JotPasscode'])) ){
	/* Add one to Hits Counter */
	$UpdateStats = "UPDATE `Jot` SET `JotHits` = `JotHits` + 1 WHERE `JotSlug` = '" . $JotValue . "'";
	$stm = $DatabaseConnection->prepare($UpdateStats);
	$stm->execute();
	$BypassPassword = true;
}

// Now if we redirect, we need to do so NOW before text outputted.
if($BypassPassword && $records[0]["JotType"] == "link" && $records[0]["JotDeleteOnOpen"] == 0){
	header('Location: ' . $records[0]['JotContent']);
}

// Include GUI Header
require_once('Assets/Views/Header.php');

?>
<!-- Main -->
	<section id="main" class="wrapper alt">
		<div class="inner">
			<?php
				/* 1. We can show user link or note */
				if($BypassPassword){
					
					echo "<header class=\"major special\"><h1>" . GetSysConfig("SiteTitle") . "</h1></header>";

					/* 1A. Check it's not set to Delete on Open */
					if($records[0]["JotDeleteOnOpen"] == 1){
						echo "<div class='deleteonopen'><strong>Please note! This link/note has been marked to delete after opening by your operator! Please ensure you note any needed information as you will <i>not be able to access it again!</i>. Please direct any question to your operator.</strong></div><br><br>";
						$DeleteAfterOpen = "DELETE FROM Jot WHERE `JotSlug` = '" . $JotValue . "'";
						$stm = $DatabaseConnection->prepare($DeleteAfterOpen);
						$stm->execute();
					}

					/* 1B. If note show note, otherwise redirect */
						if($records[0]["JotType"] == "note"){
							echo "<textarea readonly=\"readonly\">" . $records[0]['JotContent'] . "</textarea><br><button>Copy Text</button>";
						} else if($records[0]["JotType"] == "link"){
							echo "<a href='" . $records[0]['JotContent'] . "'><button>Continue</button></a>";
						}

					/* 1C. Disclamer */
					?>
						<br>
						<br>
						<hr>
					<p>Need help with this? Email us at <a href="<?php echo GetSysConfig("BrandingSupportEmail"); ?>"><?php echo GetSysConfig("BrandingSupportEmail"); ?></a> for assistance.</p>
					<?php
					
				} else if($records[0]["JotPassword"] != NULL && $records[0]["JotPassword"] != "") {

				/* 2. PW Is Required */
				
			?>
				<header class="major special">
					<h1>Passcode Required</h1>
				</header>
				<form action="index.php" method="POST">
					<input type="hidden" name="JotSlug" value="<?php echo $JotValue; ?>">
					Passcode: <input type="password" name="JotPasscode"><br>
					<input type="submit" name="submit" value="Submit">
				</form>
			<?php
				} else {
			?>
				
				<header class="major special">
					<h1>Error</h1>
				</header>
				<h2>Sorry, we couldn't find that for you!</h2>
				<p>Please contact your support agent or email us at <a href="mailto:<?php echo $BrandingFooterEmail; ?>"><?php echo $BrandingFooterEmail; ?></a> for more help</p>
			
			<?php
				}

			?>
		</div>
	</section>
	<script type="text/javascript">
		document.querySelector("button").onclick = function(){
		    document.querySelector("textarea").select();
		    document.execCommand('copy');
		}
	</script>
<?php
	require_once('Assets/Views/Footer.php');
?>