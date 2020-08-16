<?php
/********************************
* Project: IT Web Essentials - Modular based Portal System for Inventory, Billing, Service Desk and More! 
* Code Version: 2.0
* Author: Benjamin Sommer - BenSommer.net | GitHub @remmosnimajneb
* Company: The Berman Consulting Group - BermanGroup.com
* Theme Design by: Pixelarity [Pixelarity.com]
* Licensing Information: https://pixelarity.com/license
***************************************************************************************/

/* Install File */

/* Helper Function */
function InArray($keySearch, $array){
    foreach ($array as $key => $item) {
        if ($key == $keySearch) {
            return true;
        } elseif (is_array($item) && findKey($item, $keySearch)) {
            return true;
        }
    }
    return false;
}

/* 1. Check if System has been installed already */
    if(file_exists('../../DatabaseConfig.php')){
    	header('Location: ../../System/Error/CoreError.php?Error=SystemInstalled');
     	die();
    }

/* 2. Check if SQL Login works, if so save it to the file and run the install */
	$InstallStatus = "";
	if(isset($_POST["Host"]) && isset($_POST["Database"])){

		/* Test Connection */
		try {
			$DatabaseConnection = new PDO("mysql:host=" . $_POST['Host'] . ";dbname=" . $_POST['Database'] . "", "" . $_POST['Username'] . "", "" . $_POST['Password'] . "");
			$DatabaseConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$InstallStatus = "DBConnectSuccessOK";
		} catch(Exception $e) {
			$InstallStatus = "DBConnectSuccessFailed";
			$Error = "Uh oh, we couldn't connect to the Database with that, try again!";
		}

		/* If it worked, let's install the system! */
		if($InstallStatus == "DBConnectSuccessOK"){
				$DatabaseConnection = new PDO("mysql:host=" . $_POST['Host'] . ";dbname=" . $_POST['Database'] . "", "" . $_POST['Username'] . "", "" . $_POST['Password'] . "");

				$DropTables = " DROP TABLE IF EXISTS `ClientNote`;
								DROP TABLE IF EXISTS `Client`
								DROP TABLE IF EXISTS `Invoice`
								DROP TABLE IF EXISTS `Jot`
								DROP TABLE IF EXISTS `Slip`
								DROP TABLE IF EXISTS `User`
								DROP TABLE IF EXISTS `Inventory`
								DROP TABLE IF EXISTS `SysOptions`";
				$stm = $DatabaseConnection->prepare($DropTables);
				$stm->execute();

				$Query = "CREATE TABLE `Client` (
						  `ClientID` int(9) NOT NULL,
						  `ClientName` varchar(140) NOT NULL,
						  `ClientSlug` varchar(140) NOT NULL,
						  `StreetName` varchar(140) DEFAULT NULL,
						  `City` varchar(140) DEFAULT NULL,
						  `State` varchar(140) DEFAULT NULL,
						  `ZIP` varchar(140) DEFAULT NULL,
						  `Email` varchar(140) DEFAULT NULL,
						  `Phone` varchar(140) DEFAULT NULL,
						  `Fax` varchar(20) DEFAULT NULL,
						  `FlatRate` varchar(140) DEFAULT NULL,
						  `HourlyDefaultRate` varchar(140) DEFAULT NULL,
						  `ActiveServices` text,
						  `Notes` longtext
						) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
				$stm = $DatabaseConnection->prepare($Query);
				$stm->execute();

				$Query = "CREATE TABLE `Client` (
						  `ClientID` int(9) NOT NULL,
						  `ClientName` varchar(140) NOT NULL,
						  `ClientSlug` varchar(140) NOT NULL,
						  `StreetName` varchar(140) NOT NULL,
						  `City` varchar(140) NOT NULL,
						  `State` varchar(140) NOT NULL,
						  `ZIP` varchar(140) NOT NULL,
						  `Email` varchar(140) NOT NULL,
						  `Phone` varchar(140) NOT NULL,
						  `FlatRate` varchar(140) DEFAULT NULL,
						  `HourlyDefaultRate` varchar(140) DEFAULT NULL,
						  `ActiveServices` text,
						  `Notes` longtext
						) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
				$stm = $DatabaseConnection->prepare($Query);
				$stm->execute();

				$Query = "CREATE TABLE `ClientNote` (
						  `NoteID` int(9) NOT NULL,
						  `ClientID` int(9) NOT NULL,
						  `UserID` int(9) NOT NULL,
						  `Note` text,
						  `NoteDate` datetime DEFAULT CURRENT_TIMESTAMP
						) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
				$stm = $DatabaseConnection->prepare($Query);
				$stm->execute();

				$Query = "CREATE TABLE `ClientUser` (
						  `ClientUserID` int(9) NOT NULL,
						  `Name` varchar(300) NOT NULL,
						  `PositionTitle` varchar(300) DEFAULT NULL,
						  `Email` varchar(300) NOT NULL,
						  `Password` varchar(300) NOT NULL DEFAULT 'password_not_generated',
						  `StreetName` varchar(300) DEFAULT NULL,
						  `City` varchar(100) DEFAULT NULL,
						  `State` varchar(100) DEFAULT NULL,
						  `ZIP` varchar(20) DEFAULT NULL,
						  `HomePhone` varchar(75) DEFAULT NULL,
						  `CellPhone` varchar(75) DEFAULT NULL,
						  `FaxNumber` varchar(75) DEFAULT NULL,
						  `Notes` text,
						  `ClientID` int(9) NOT NULL,
						  `ReceivesInvoices` int(1) DEFAULT '0',
						  `PortalAccessAllowed` int(1) DEFAULT '0',
						  `PortalCanViewInvoices` int(1) NOT NULL DEFAULT '0',
						  `PortalCanSubmitTickets` int(1) NOT NULL DEFAULT '0'
						) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
				$stm = $DatabaseConnection->prepare($Query);
				$stm->execute();

				$Query = "CREATE TABLE `Configuration` (
						  `OptionID` int(9) NOT NULL,
						  `ConfigurationKey` varchar(140) NOT NULL,
						  `ConfigurationValue` varchar(600) NOT NULL
						) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
				$stm = $DatabaseConnection->prepare($Query);
				$stm->execute();

				$Query = "CREATE TABLE `Inventory` (
						  `ItemID` int(9) NOT NULL,
						  `ItemName` varchar(140) NOT NULL,
						  `ItemDescription` varchar(300) DEFAULT NULL,
						  `ItemLocation` varchar(140) DEFAULT NULL,
						  `PurchaseDate` date DEFAULT NULL,
						  `WarrantyExpDate` date DEFAULT NULL,
						  `ModelNumber` varchar(140) DEFAULT NULL,
						  `SerialNumber` varchar(140) DEFAULT NULL,
						  `ClientID` int(9) NOT NULL,
						  `ItemNotes` text
						) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
				$stm = $DatabaseConnection->prepare($Query);
				$stm->execute();

				$Query = "CREATE TABLE `Invoice` (
						  `InvoiceID` int(9) NOT NULL,
						  `InvoiceHash` varchar(80) NOT NULL,
						  `ClientID` int(9) NOT NULL,
						  `InvoiceType` varchar(10) NOT NULL,
						  `InvoiceDate` date DEFAULT NULL,
						  `FlatRateMonths` int(3) DEFAULT NULL,
						  `PreviousBalance` int(11) NOT NULL DEFAULT '0',
						  `DiscountType` int(1) DEFAULT NULL,
						  `DiscountAmount` int(9) DEFAULT NULL,
						  `ColumnsToShow` varchar(200) NOT NULL,
						  `InvoiceStatus` int(2) NOT NULL DEFAULT '0',
						  `PaymentStatus` int(2) NOT NULL DEFAULT '0',
						  `PaymentAmount` int(9) NOT NULL DEFAULT '0',
						  `PaymentNotes` varchar(300) DEFAULT NULL,
						  `PaymentDate` date DEFAULT NULL,
						  `InternalNotes` text,
						  `InvoiceNotes` text
						) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
				$stm = $DatabaseConnection->prepare($Query);
				$stm->execute();

				$Query = "CREATE TABLE `Jot` (
						  `JotID` int(9) NOT NULL,
						  `JotSlug` varchar(20) NOT NULL,
						  `JotPassword` varchar(40) DEFAULT NULL,
						  `JotDeleteOnOpen` int(1) NOT NULL DEFAULT '0',
						  `JotType` varchar(10) NOT NULL,
						  `JotContent` text,
						  `JotHits` int(9) DEFAULT '0'
						) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
				$stm = $DatabaseConnection->prepare($Query);
				$stm->execute();

				$Query = "CREATE TABLE `Slip` (
						  `SlipID` int(9) NOT NULL,
						  `TSType` varchar(140) NOT NULL,
						  `Consultant` varchar(140) NOT NULL,
						  `ClientID` varchar(140) NOT NULL,
						  `StartDate` date NOT NULL,
						  `EndDate` date DEFAULT NULL,
						  `Hours` varchar(140) DEFAULT NULL,
						  `DNB` varchar(140) NOT NULL DEFAULT '0',
						  `Description` text NOT NULL,
						  `InternalNotes` text,
						  `CategoryID` int(9) DEFAULT NULL,
						  `Price` varchar(140) DEFAULT NULL,
						  `Quantity` varchar(140) DEFAULT NULL,
						  `SlipStatus` varchar(40) DEFAULT NULL,
						  `InvoiceID` int(9) DEFAULT NULL
						) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
				$stm = $DatabaseConnection->prepare($Query);
				$stm->execute();

				$Query = "CREATE TABLE `User` (
						  `UserID` int(9) NOT NULL,
						  `Name` varchar(140) DEFAULT NULL,
						  `ConsultantSlug` varchar(140) NOT NULL,
						  `StreetName` varchar(140) DEFAULT NULL,
						  `City` varchar(140) DEFAULT NULL,
						  `State` varchar(140) DEFAULT NULL,
						  `ZIP` varchar(140) DEFAULT NULL,
						  `Phone` varchar(20) DEFAULT NULL,
						  `Fax` varchar(20) DEFAULT NULL,
						  `Email` varchar(140) DEFAULT NULL,
						  `Password` varchar(255) DEFAULT NULL,
						  `SecurityLevel` int(2) NOT NULL,
						  `SysPermissions` varchar(500) NOT NULL,
						  `Notes` text
						) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
				$stm = $DatabaseConnection->prepare($Query);
				$stm->execute();

				$Query = "INSERT INTO `Configuration` (`OptionID`, `ConfigurationKey`, `ConfigurationValue`) VALUES
					(1, 'SiteTitle', 'Sample Company'),
					(2, 'BrandingCompanyURL', 'https://example.com'),
					(3, 'BrandingFooterPhoneNumber', '123-456-7890'),
					(4, 'BrandingFooterEmail', 'info@example.com'),
					(6, 'BrandingAboutHeader', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'),
					(7, 'BrandingAboutSubHeader', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer eget libero mollis, lacinia elit ut, vehicula odio. Maecenas id sagittis elit. Aenean vel sapien nulla. Nullam id molestie nibh. Nam maximus aliquet felis, sollicitudin finibus eros maximus quis. Integer eu libero a diam placerat aliquam non in nunc. Vestibulum at ipsum id purus viverra ullamcorper. Sed tristique luctus elit, vel tristique est cursus nec. Cras ut imperdiet sem, sed interdum nunc.'),
					(8, 'BrandingCompanyAddress', '64 Henry St. Cuyahoga Falls, OH 44221'),
					(9, 'InvoiceEmailAddress', 'billing@example.com'),
					(10, 'SystemURL', ''),
					(11, 'InvoiceBrandingLogo', 'https://example.com/logo.png'),
					(12, 'ActivatedSystems', '{\"Jots\":\"1\",\"BillingPortalUser\":\"1\",\"BillingPortalAdmin\":\"1\",\"ServiceDesk\":\"1\",\"Inventory\":\"1\",\"CustomerPortal\":\"1\"}'),
					(13, 'BrandingSupportEmail', 'support@example.com'),
					(14, 'CustomerPortalFooterHeader', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'),
					(15, 'CustomerPortalFooterSubHeader', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer eget libero mollis, lacinia elit ut, vehicula odio. Maecenas id sagittis elit. Aenean vel sapien nulla. Nullam id molestie nibh. Nam maximus aliquet felis, sollicitudin finibus eros maximus quis. Integer eu libero a diam placerat aliquam non in nunc. Vestibulum at ipsum id purus viverra ullamcorper. Sed tristique luctus elit, vel tristique est cursus nec. Cras ut imperdiet sem, sed interdum nunc.'),
					(16, 'CustomerPortalCanViewInvoices', '0'),
					(17, 'CustomerPortalCanSubmitTickets', '0');";
				$stm = $DatabaseConnection->prepare($Query);
				$stm->execute();
					
				$Query = "INSERT INTO `User` (`UserID`, `Name`, `ConsultantSlug`, `StreetName`, `City`, `State`, `ZIP`, `Phone`, `Fax`, `Email`, `Password`, `SecurityLevel`, `SysPermissions`, `Notes`) VALUES (1, 'Sys Admin', 'SysAdmin', '', '', '', '', '', '', 'admin@example.com', '$2y$10$X/2GIOgtwvy.DYG/Qs9NJeCB3HxABCv4hFuNHnFFw6WFhIVoLfsei', 2, '{\"Jots\":\"1\",\"BillingPortalUser\":\"1\",\"BillingPortalAdmin\":\"1\",\"ServiceDesk\":\"1\",\"Inventory\":\"1\",\"CustomerPortal\":\"1\"}', '');";
				$stm = $DatabaseConnection->prepare($Query);
				$stm->execute();

				$Query = "ALTER TABLE `Client`
						  ADD PRIMARY KEY (`ClientID`);
						ALTER TABLE `ClientNote`
						  ADD PRIMARY KEY (`NoteID`);  
						ALTER TABLE `ClientUser`
						  ADD PRIMARY KEY (`ClientUserID`);
						ALTER TABLE `Configuration`
						  ADD PRIMARY KEY (`OptionID`);
						ALTER TABLE `Inventory`
						  ADD PRIMARY KEY (`ItemID`);
						ALTER TABLE `Invoice`
						  ADD PRIMARY KEY (`InvoiceID`);
						ALTER TABLE `Jot`
						  ADD PRIMARY KEY (`JotID`);
						ALTER TABLE `Slip`
						  ADD PRIMARY KEY (`SlipID`),
						  ADD UNIQUE KEY `TSID` (`SlipID`);
						ALTER TABLE `User`
						  ADD PRIMARY KEY (`UserID`),
						  ADD UNIQUE KEY `UserID` (`UserID`);

						ALTER TABLE `Client`
						  MODIFY `ClientID` int(9) NOT NULL AUTO_INCREMENT;
						ALTER TABLE `ClientNote`
						  MODIFY `NoteID` int(9) NOT NULL AUTO_INCREMENT;
						ALTER TABLE `ClientUser`
						  MODIFY `ClientUserID` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT;
						ALTER TABLE `Configuration`
						  MODIFY `OptionID` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
						ALTER TABLE `Inventory`
						  MODIFY `ItemID` int(9) NOT NULL AUTO_INCREMENT;
						ALTER TABLE `Invoice`
						  MODIFY `InvoiceID` int(9) NOT NULL AUTO_INCREMENT;
						ALTER TABLE `Jot`
						  MODIFY `JotID` int(9) NOT NULL AUTO_INCREMENT;
						ALTER TABLE `Slip`
						  MODIFY `SlipID` int(9) NOT NULL AUTO_INCREMENT;
						ALTER TABLE `User`
						  MODIFY `UserID` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
						COMMIT;";
				$stm = $DatabaseConnection->prepare($Query);
				$stm->execute();

				/* Check tables were added */
				$Query = "SELECT table_name FROM `information_schema`.`tables` WHERE table_schema = '" . $_POST['Database'] . "';";
				$stm = $DatabaseConnection->prepare($Query);
				$stm->execute();
				$Tables = $stm->fetchAll();

				if(!InArray("ClientNote", $Tables[0]) || !InArray("Client", $Tables[0]) || !InArray("Invoice", $Tables[0]) || !InArray("Jot", $Tables[0]) || !InArray("Slip", $Tables[0]) || !InArray("User", $Tables[0]) || !InArray("Inventory", $Tables[0])){
				 	$InstallStatus = "CreateTablesFailed";
					$Error = "Uh oh - looks like the installation their didn't work....Check that the SQL Login has permissions to write new tables/data and try again.<br>If the error persists, run the SQL Import manually using the InstallSQL.sql and continue with DatabaseConfig-Sample.php";
				} else {
					/* Write Config File */			
				$File = "<?php
					/********************************
					* Project: IT Web Essentials - Modular based Portal System for Inventory, Billing, Service Desk and More! 
					* Code Version: 2.0
					* Author: Benjamin Sommer - BenSommer.net | GitHub @remmosnimajneb
					* Company: The Berman Consulting Group - BermanGroup.com
					* Theme Design by: Pixelarity [Pixelarity.com]
					* Licensing Information: https://pixelarity.com/license
					***************************************************************************************/

					/* SQL Config File */

					DEFINE( 'DB_HOST', '" . $_POST['Host'] . "' );					// HostName
					DEFINE( 'DB_NAME', '" . $_POST['Database'] . "' );				// Database Name
					DEFINE( 'DB_USER', '" . $_POST['Username'] . "' );				// Database Username
					DEFINE( 'DB_PASSWORD', '" . $_POST['Password'] . "' );			// Database Password
				?>";
													
						try{
							file_put_contents("../../DatabaseConfig.php", $File);
						} catch(Exception $e){
							$InstallStatus = "CannotWriteConfigFile";
						}
				} // End If();

				$InstallStatus = "AllSystemsGo";
		}
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Install System</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="/Assets/Theme/css/main.css" />
		<script type="text/javascript" src="/Assets/Theme/js/jquery.min.js"></script>
	</head>
	<body class="is-preload">

		<!-- Page Wrapper -->
			<div id="page-wrapper">
					<header id="header">
						<h1>IT Web Essentials Version 2.0</h1>
					</header>

				<div id="main">
					<!-- Content -->
						<section id="content" class="default">
						<!-- Header -->
							<header class="major">
								<h1>Install System</h1>
							</header>
								<div class="content">
									<?php
										if($InstallStatus == "CannotWriteConfigFile"){
											?>
												<p style='color:red'>Uh oh, we can't seem to write the Config file.</p>
												<p>Please copy the following text and save it to DatabaseConfig.php in the root of your system and then <a href='../Auth/Login.php'>login</a> using the email admin@example.com and admin1234</p>
												<textarea><?php echo $File; ?></textarea>
											<?php
										} else if($InstallStatus == "AllSystemsGo"){
											?>
												There we go! You made it! Now let's <a href='../Auth/Login.php'>login</a> using the email admin@example.com and admin1234</a>
											<?php
										} else {
											?>
											<p>Installation is pretty simple, let's get your Database login info, and then the system will auto install!</p>
											<p>Let's get started!</p>
											<p style="color: red"><?php if(isset($Error)) echo $Error; ?></p>
											<form action="Install.php" method="POST">
												Database Host: <input type="text" name="Host" required="required"><br>
												Database Name: <input type="text" name="Database" required="required"><br>
												Database Username: <input type="text" name="Username" required="required"><br>
												Database Password: <input type="text" name="Password"><br>
												<input type="submit" name="submit" value="Install">
											</form>
										<?php } ?>
								</div>
						</section>
				</div>

				<!-- Footer -->
					<section id="footer">
						<div class="copyright">
							<p>IT Web Essentials Version 2.0 By <a href="https://github.com/remmosnimajneb">@remmosnimajneb</a></p>
						</div>
					</section>

			</div>

		<!-- Scripts -->
			<script src="/Assets/Theme/js/jquery.min.js"></script>
			<script src="/Assets/Theme/js/browser.min.js"></script>
			<script src="/Assets/Theme/js/breakpoints.min.js"></script>
			<script src="/Assets/Theme/js/util.js"></script>
			<script src="/Assets/Theme/js/main.js"></script>
	</body>
</html>