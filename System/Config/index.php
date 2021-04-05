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
* System Configuration
*/

/* Page Variables */
$PageSecurityLevel = 2;
$AppName = "SystemConfiguration";
$PageName = "System Configuration";

/* Include System Functions */
require_once("../../InitSystem.php");

/* Edit Config */
if(isset($_POST) && $_POST['Action'] == "EditConfig"){

	/* Activated Systems */
	$AvailableSystems = json_decode(GetSysConfig("ActivatedSystems"));
	$AvailSystems;
	foreach($AvailableSystems as $SysKey => $SysValue) {
		$AvailSystems->$SysKey = "0";
		if(in_array($SysKey, $_POST['ActivatedSystems'])){
			$AvailSystems->$SysKey = "1";
		}
	}	

	/* Customer Portal Checkboxes */
	if(!empty($_POST['CustomerPortalCanViewInvoices'])){ $_POST['CustomerPortalCanViewInvoices'] = 1; } else { $_POST['CustomerPortalCanViewInvoices'] = 0;}
	if(!empty($_POST['CustomerPortalCanSubmitTickets'])){ $_POST['CustomerPortalCanSubmitTickets'] = 1; } else { $_POST['CustomerPortalCanSubmitTickets'] = 0;}

	/* Update each Config */
	foreach($_POST as $SysKey => $SysValue) {
		$Update = "UPDATE `Configuration` SET `ConfigurationValue` = '" . EscapeSQLEntry($SysValue) . "' WHERE `ConfigurationKey` = '" . $SysKey . "'";
			if($SysKey == "ActivatedSystems"){
				$Update = "UPDATE `Configuration` SET `ConfigurationValue` = '" . json_encode($AvailSystems) . "' WHERE `ConfigurationKey` = '" . $SysKey . "'";
			}
		$stm = $DatabaseConnection->prepare($Update);
		$stm->execute();
	}
	$Message = "Configuration Updated!";
}

/* Header */
require_once(SYSPATH . '/Assets/Views/Header.php');

?>
<!-- Main -->
	<div id="main" style="width: 95vw;">
	<!-- Content -->
		<section id="content" class="default">
			<header class="major">
				<h2>System Configuration</h2>
			</header>
			<div class="content">
				<h3 style="text-align: center;"><?php if(isset($Message)) echo $Message; ?></h3>
				<form action="index.php" method="POST">
					<input type="hidden" name="Action" value="EditConfig">	
						<br>
					<h3>System Info</h3>
					<div class="row">
						<div class="column">
							Site Title: <input type="text" name="SiteTitle" required="required" value="<?php echo GetSysConfig('SiteTitle'); ?>">
						</div>
						<div class="column">
							System Public URL: <input type="text" name="SystemURL" required="required" value="<?php echo GetSysConfig('SystemURL'); ?>">
						</div>
					</div>
					<div class="row">
						<div class="column">
							<br>
							Activated Systems:
							<?php 
								$ActivatedSystems = json_decode(GetSysConfig("ActivatedSystems"));
								foreach ($ActivatedSystems as $SysKey => $SysValue) {
									echo "<br><input type='checkbox' id='" . $SysKey . "' name='ActivatedSystems[]' value='" . $SysKey . "'";
										if($SysValue == "1"){
											echo " checked='checked'";
										}
									echo "><label for='" . $SysKey. "'>" . preg_replace('/(?<!\ )[A-Z]/', ' $0', $SysKey) . "</label>";
									
								}
							?>
						</div>
						<div class="column">
							<br>
							Customer Portal Options:
								<br><input type='checkbox' id='CustomerPortalCanViewInvoices' name='CustomerPortalCanViewInvoices' value='1' <?php if(GetSysConfig("CustomerPortalCanViewInvoices") == 1){ echo " checked='checked'"; } ?>><label for='CustomerPortalCanViewInvoices'>Customers can view Invoices</label>
								<br><input type='checkbox' id='CustomerPortalCanSubmitTickets' name='CustomerPortalCanSubmitTickets' value='1' <?php if(GetSysConfig("CustomerPortalCanSubmitTickets") == 1){ echo " checked='checked'"; } ?>><label for='CustomerPortalCanSubmitTickets'>Customer can use Service Desk</label>
						</div>
					</div>
					<br><h3>Branding Info</h3>
					<div class="row">
						<div class="column">
							Company Website: <input type="text" name="BrandingCompanyURL" value="<?php echo GetSysConfig('BrandingCompanyURL'); ?>">
						</div>
						<div class="column">
							Phone Number: <input type="text" name="BrandingFooterPhoneNumber" value="<?php echo GetSysConfig('BrandingFooterPhoneNumber'); ?>">
						</div>
						<div class="column">
							Office Address: <input type="text" name="BrandingCompanyAddress" value="<?php echo GetSysConfig('BrandingCompanyAddress'); ?>">
						</div>
						<div class="column">
							Logo (Full URL): <input type="text" name="InvoiceBrandingLogo" value="<?php echo GetSysConfig('InvoiceBrandingLogo'); ?>">
						</div>
					</div>
					<div class="row">
						<div class="column">
							About Header Text: <textarea name="BrandingAboutHeader"><?php echo GetSysConfig('BrandingAboutHeader'); ?></textarea>
						</div>
						<div class="column">
							About Sub-Header: <textarea name="BrandingAboutSubHeader"><?php echo GetSysConfig('BrandingAboutSubHeader'); ?></textarea>
						</div>
					</div>
					<br><h3>Customer Portal Branding</h3>
					<div class="row">
						<div class="column">
							Footer Header: <textarea name="CustomerPortalFooterHeader"><?php echo GetSysConfig('CustomerPortalFooterHeader'); ?></textarea>
						</div>
						<div class="column">
							Footer Sub-Header: <textarea name="CustomerPortalFooterSubHeader"><?php echo GetSysConfig('CustomerPortalFooterSubHeader'); ?></textarea>
						</div>
					</div>
					<br><h3>Email Options</h3>
					<div class="row">
						<div class="column">
							Public Email: <input type="email" name="BrandingFooterEmail" value="<?php echo GetSysConfig('BrandingFooterEmail'); ?>">
						</div>
						<div class="column">
							Support Email: <input type="email" name="BrandingSupportEmail" value="<?php echo GetSysConfig('BrandingSupportEmail'); ?>">
						</div>
						<div class="column">
							Invoice Email: <input type="email" name="InvoiceEmailAddress" value="<?php echo GetSysConfig('InvoiceEmailAddress'); ?>">
						</div>
					</div>
						<br>
					<input type="submit" value="Edit">
				</form>
			</div>
		</section>
	</div>
<?php /* Footer */ require_once(SYSPATH . '/Assets/Views/Footer.php');  ?>