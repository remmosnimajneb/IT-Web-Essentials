/********************************
* Project: IT Web Essentials 
* Code Version: 1.0
* Author: Benjamin Sommer
* Company: The Berman Consulting Group - https://bermangroup.com
* GitHub: https://github.com/remmosnimajneb
* Theme Design by: Pixelarity [Pixelarity.com]
* Licensing Information: https://pixelarity.com/license
***************************************************************************************/

/* SQL INSTALL FOR V1.0 */

/*
* PLEASE TAKE NOTE: This file will be obselete in V2.0 and will require reformatting of the Database to upgrade to V2.0! */

CREATE TABLE `ClientNotes` (
  `NoteID` int(9) NOT NULL,
  `Note` text,
  `NoteDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `NoteUser` int(3) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `Clients` (
  `ClientID` int(9) NOT NULL,
  `ClientName` varchar(140) NOT NULL,
  `Slug` varchar(140) NOT NULL,
  `StreetName` varchar(140) NOT NULL,
  `City` varchar(140) NOT NULL,
  `State` varchar(140) NOT NULL,
  `ZIP` varchar(140) NOT NULL,
  `Email` varchar(140) NOT NULL,
  `Phone` varchar(140) NOT NULL,
  `FlatRate` varchar(140) DEFAULT NULL,
  `HourlyDefaultRate` varchar(140) DEFAULT NULL,
  `ActiveServices` text,
  `active` int(1) DEFAULT NULL,
  `ClientNotes` longtext
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `Invoice` (
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

CREATE TABLE `Slips` (
  `TSID` int(9) NOT NULL,
  `TSType` varchar(140) NOT NULL,
  `Consultant` varchar(140) NOT NULL,
  `ClientName` varchar(140) NOT NULL,
  `StartDate` date NOT NULL,
  `EndDate` date DEFAULT NULL,
  `Hours` varchar(140) DEFAULT NULL,
  `DNB` varchar(140) NOT NULL DEFAULT '0',
  `Description` text NOT NULL,
  `InternalNotes` text,
  `Category` varchar(140) DEFAULT NULL,
  `Price` varchar(140) DEFAULT NULL,
  `Quantity` varchar(140) DEFAULT NULL,
  `SlipStatus` varchar(40) DEFAULT NULL,
  `InvoiceID` int(9) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `SysUsers` (
  `UserID` int(9) NOT NULL,
  `Name` varchar(140) DEFAULT NULL,
  `ConsultantSlug` varchar(140) NOT NULL,
  `Email` varchar(140) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `SysUsers` (`UserID`, `Name`, `ConsultantSlug`, `Email`, `Password`) VALUES
(1, 'Admin', 'SysAdmin', 'admin@example.com', '$2y$10$iuCQ4EZ41RY/UK5JOhZVoefMEY3IY5vUzbCgFnG9IaNfAJF8d2FKe'),


ALTER TABLE `ClientNotes`
  ADD PRIMARY KEY (`NoteID`);
ALTER TABLE `Clients`
  ADD PRIMARY KEY (`ClientID`);
ALTER TABLE `Invoice`
  ADD PRIMARY KEY (`InvoiceID`);
ALTER TABLE `Jot`
  ADD PRIMARY KEY (`JotID`);
ALTER TABLE `Slips`
  ADD PRIMARY KEY (`TSID`),
  ADD UNIQUE KEY `TSID` (`TSID`);
ALTER TABLE `SysUsers`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `UserID` (`UserID`);

ALTER TABLE `ClientNotes`
  MODIFY `NoteID` int(9) NOT NULL AUTO_INCREMENT;
ALTER TABLE `Clients`
  MODIFY `ClientID` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
ALTER TABLE `Invoice`
  MODIFY `InvoiceID` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13000;
ALTER TABLE `Jot`
  MODIFY `JotID` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `Slips`
  MODIFY `TSID` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21000;
ALTER TABLE `SysUsers`
  MODIFY `UserID` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;