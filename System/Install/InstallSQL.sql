/********************************
* Project: IT Web Essentials - Modular based Portal System for Inventory, Billing, Service Desk and More! 
* Code Version: 2.2
* Author: Benjamin Sommer - BenSommer.net | GitHub @remmosnimajneb
* Company: The Berman Consulting Group - BermanGroup.com
* Theme Design by: Pixelarity [Pixelarity.com]
* Licensing Information: https://pixelarity.com/license
***************************************************************************************/

/* SQL Install File 
* If your having issues with the Auto-Installer, either import or run each of these queries on the SQL Database yourself
*/

CREATE TABLE `Client` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `ClientNote` (
  `NoteID` int(9) NOT NULL,
  `ClientID` int(9) NOT NULL,
  `UserID` int(9) NOT NULL,
  `Note` text,
  `NoteDate` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `ClientUser` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `Configuration` (
  `OptionID` int(9) NOT NULL,
  `ConfigurationKey` varchar(140) NOT NULL,
  `ConfigurationValue` varchar(600) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `Inventory` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `Invoice` (
  `InvoiceID` int(9) NOT NULL,
  `InvoiceHash` varchar(80) NOT NULL,
  `ClientID` int(9) NOT NULL,
  `InvoiceType` varchar(10) NOT NULL,
  `InvoiceDate` date DEFAULT NULL,
  `FlatRateMonths` int(3) DEFAULT NULL,
  `PreviousBalance` DOUBLE NULL,
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `Jot` (
  `JotID` int(9) NOT NULL,
  `JotSlug` varchar(20) NOT NULL,
  `JotPassword` varchar(40) DEFAULT NULL,
  `JotDeleteOnOpen` int(1) NOT NULL DEFAULT '0',
  `JotType` varchar(10) NOT NULL,
  `JotContent` text,
  `JotHits` int(9) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `Slip` (
  `SlipID` int(9) NOT NULL,
  `TSType` varchar(140) NOT NULL,
  `Consultant` int(9) NOT NULL,
  `ClientID` int(9) NOT NULL,
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `User` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `Quote` (
  `QuoteID` int(9) NOT NULL,
  `Hash` varchar(32) NOT NULL,
  `ClientID` int(9) NOT NULL,
  `Name` varchar(300) NOT NULL,
  `Date` date DEFAULT NULL,
  `ExpDate` date DEFAULT NULL,
  `LineItems` text DEFAULT NULL,
  `Discount` double DEFAULT NULL,
  `Fee` double DEFAULT NULL,
  `Approved` int(1) DEFAULT NULL,
  `QuoteStatus` int(9) DEFAULT NULL,
  `Signature` text DEFAULT NULL,
  `SignedName` varchar(200) DEFAULT NULL,
  `SignedDate` date DEFAULT NULL,
  `InvoiceID` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `Subscription` (
  `SubscriptionID` int(9) NOT NULL,
  `SubscriptionName` varchar(200) NOT NULL,
  `ClientID` int(9) NOT NULL,
  `SlipType` varchar(15) DEFAULT NULL,
  `Cost` double DEFAULT 0,
  `Quantity` int(9) DEFAULT NULL,
  `StartDate` date DEFAULT NULL,
  `Frequency` varchar(30) NOT NULL,
  `RecurrenceOn` varchar(30) NOT NULL,
  `RecurrenceDate` date NOT NULL,
  `LastRunDate` date DEFAULT NULL,
  `Status` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `User` (`UserID`, `Name`, `ConsultantSlug`, `StreetName`, `City`, `State`, `ZIP`, `Phone`, `Fax`, `Email`, `Password`, `SecurityLevel`, `SysPermissions`, `Notes`) VALUES
(1, 'Sys Admin', 'SysAdmin', '', '', '', '', '', '', 'admin@example.com', '$2y$10$X/2GIOgtwvy.DYG/Qs9NJeCB3HxABCv4hFuNHnFFw6WFhIVoLfsei', 2, '{\"Jots\":\"1\",\"BillingPortalUser\":\"1\",\"BillingPortalAdmin\":\"1\",\"ServiceDesk\":\"1\",\"Inventory\":\"1\",\"CustomerPortal\":\"1\"}', '');

INSERT INTO `Configuration` (`OptionID`, `ConfigurationKey`, `ConfigurationValue`) VALUES
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
(17, 'CustomerPortalCanSubmitTickets', '0');

ALTER TABLE `Client`
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
ALTER TABLE `Subscription`
  ADD PRIMARY KEY (`SubscriptionID`);
ALTER TABLE `Quote`
  ADD PRIMARY KEY (`QuoteID`);

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
  MODIFY `InvoiceID` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5000;
ALTER TABLE `Jot`
  MODIFY `JotID` int(9) NOT NULL AUTO_INCREMENT;
ALTER TABLE `Slip`
  MODIFY `SlipID` int(9) NOT NULL AUTO_INCREMENT;
ALTER TABLE `User`
  MODIFY `UserID` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `Subscription`
  MODIFY `SubscriptionID` int(9) NOT NULL AUTO_INCREMENT;
ALTER TABLE `Quote`
  MODIFY `QuoteID` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4000;

/* Create Function */

  DELIMITER $$
CREATE FUNCTION `ComputeInvoiceTotal`(`Invoice_ID` INT(9)) RETURNS double
    DETERMINISTIC
BEGIN
   
    # End Total
    DECLARE Total DOUBLE;
  

    # First get Invoice Vars
  
    DECLARE Invoice_Type VARCHAR(30);
    DECLARE Invoice_FlatRateMonths INT;

    DECLARE Inv_Discount_Type INT;
    DECLARE Inv_Discount_Amount DOUBLE;
    DECLARE Inv_Discount_Previous_Balance DOUBLE;

    DECLARE Inv_ClientID INT;

    DECLARE TotalHours INT;
    DECLARE DNBHours INT;

    DECLARE ExpenseCosts DOUBLE;

    DECLARE Client_FlatRate DOUBLE;
    DECLARE Client_HourlyRate DOUBLE;

    DECLARE TotalDiscounts DOUBLE;

    SELECT InvoiceType INTO Invoice_Type
        FROM Invoice
        WHERE InvoiceID = Invoice_ID;

    SELECT FlatRateMonths INTO Invoice_FlatRateMonths
        FROM Invoice
        WHERE InvoiceID = Invoice_ID;

    SELECT DIscountType INTO Inv_Discount_Type
        FROM Invoice
        WHERE InvoiceID = Invoice_ID;

    SELECT DiscountAmount INTO Inv_Discount_Amount
        FROM Invoice
        WHERE InvoiceID = Invoice_ID;

    SELECT PreviousBalance INTO Inv_Discount_Previous_Balance
        FROM Invoice
        WHERE InvoiceID = Invoice_ID;

    SELECT ClientID INTO Inv_ClientID
        FROM Invoice
        WHERE InvoiceID = Invoice_ID;

  # Get Client Vars

    SELECT FlatRate INTO Client_FlatRate
      FROM Client
      WHERE ClientID = Inv_ClientID;

    SELECT HourlyDefaultRate INTO Client_HourlyRate
      FROM Client
      WHERE ClientID = Inv_ClientID;

  # Finally get total Hours

    SELECT SUM(Hours) INTO TotalHours
      FROM Slip
      WHERE InvoiceID = Invoice_ID
        AND
          SlipStatus = 'BAR'
        AND 
          TSType = 'TS';

    SELECT SUM(DNB) INTO DNBHours
      FROM Slip
      WHERE InvoiceID = Invoice_ID
        AND
            SlipStatus = 'BAR'
        AND 
          TSType = 'TS';

    SELECT SUM(Price * Quantity) INTO ExpenseCosts
      FROM Slip
      WHERE InvoiceID = Invoice_ID
        AND
            SlipStatus = 'BAR'
        AND 
          TSType = 'Expense';


  # Setup Discounts
    IF Inv_Discount_Amount > 0 THEN
        IF Inv_Discount_Type = 1 THEN
          SET TotalDiscounts = COALESCE(Inv_Discount_Amount, 0);
        ELSEIF Inv_Discount_Type = 2 THEN
            IF Invoice_Type = 'Flat' THEN
              SET TotalDiscounts = ((COALESCE(Invoice_FlatRateMonths,0) * COALESCE(Client_FlatRate,0)) * 100) / Inv_Discount_Amount;
            ELSEIF Invoice_Type = 'Hourly' THEN
              SET TotalDiscounts = (((COALESCE(TotalHours,0) - COALESCE(DNBHours,0)) * COALESCE(Client_HourlyRate,0)) * 100) / Inv_Discount_Amount;
            END IF;
        END IF;
    END IF;

  # Get Total
    IF Invoice_Type = 'Flat' THEN
      SET Total = (COALESCE(Invoice_FlatRateMonths, 0) * COALESCE(Client_FlatRate, 0)) + (COALESCE(ExpenseCosts, 0)) + (COALESCE(Inv_Discount_Previous_Balance, 0)) - COALESCE(Inv_Discount_Amount, 0);
    ELSEIF Invoice_Type = 'Hourly' THEN
      SET Total = ((COALESCE(TotalHours,0) - COALESCE(DNBHours,0)) * COALESCE(Client_HourlyRate,0)) + (COALESCE(ExpenseCosts,0)) + (COALESCE(Inv_Discount_Previous_Balance,0))  - COALESCE(Inv_Discount_Amount, 0);
      END IF;

  RETURN Total;

END$$
DELIMITER ;