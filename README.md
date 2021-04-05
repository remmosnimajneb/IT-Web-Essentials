
# IT Web Essentials

- Project: IT Web Essentials
- Code Version: 2.2
- Author: Benjamin Sommer - https://BenSommer.net
- Company: The Berman Consulting Group - https://BermanGroup.com
- GitHub: https://github.com/remmosnimajneb
- Theme Design by: Pixelarity [Pixelarity.com] - Licensing Information: https://pixelarity.com/license

## Overview

So this project is something I've been meaning to do for a while. It's a "Modular" based system to include various systems you may want for your company as an all in one system, including a Billing System, Short URL maker, Website Uptime Monitor (~~V2.0~~ - Sorry V3.0), Service Desk (Support tickets, also ~~V2.0~~ - Sorry also V3.0) among others.

### NEW: Version 2.2 updates!
Version 2.2 is not QUITE Version 3.0 but heck it comes almost as jam packed as any update would come!

#### Major Updates

 - **Slip + Invoice Reporting** - You can now Export to CSV Slip and Invoice Reports from the Dashboard.
 - **Quotes** - Built into Version 2.2 is Price Quotes, you can create and email for approval Price Quotes, and then once approved, turn them right into Invoices with one click!
 - **Subscriptions** - Have an Expense or Action you do over and over? Create a Subscription to auto-generate Slips for you Daily, Monthly, or Yearly! (*Note: Requires Cron Job Setup to work!*).
 
##### Developer Updates
- Begin standardizing of use of GetSysConfig("SystemURL") for all URL's.
- New SQL function for Invoices to compute Total Invoice Due - ComputeInvoiceTotal(InvoiceID).
- Added new Cron Job Handler for Subscriptions (Future use for additional Cron Job's).
- Minor bug fixes and other updates throughout.

### Version 2 updates!
Version 2.0 comes with a bunch of awesome changed!
- Various Database cleanup changes
- Auto Installer for the program
- Inventory, Customer Portal & Billing Portal Contacts Apps added
- Upgraded Security/Permissions for Users
- Better Configuration abilities
- And other changes.....

### Version 3.0 Upgrades to come
1. Service Desk
2. Website Uptime Monitor

PSA: I'm too lazy to write actual documentation for this thing, so if you actual use this and need help - Let me know!


## Req's
1. Apache Web Server with PHP V 7.0+
2. MySQL Server
3. PDO-MYSQL Extention

## Installation

1. Create a new SQL Database
2. Dump files into a directory (meant to be used as a root directory, you can run into issues running this in a subdirectory)
3. Navigate to yoursite.tld and you'll automatically get the Auto Installer
4. (Optional) If you are using Subscriptions (V2.2+) you'll need to add the Cron job:
    php -q /System/Cron/CronJobHandler.php
    They should be set to run once per day (anytime).
    Settings up CRON Jobs are not covered in this documentation, if your confused, search Google.
