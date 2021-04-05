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
* Build Worksheet
*/

    /* Page Variables */
    $PageSecurityLevel = 1;
    $AppName = "BillingPortalAdmin";
    $PageName = "Worksheet";

    /* Include System Functions */
    require_once("../../../InitSystem.php");

    /* Get Header */
    require_once(SYSPATH . "/Apps/BillingPortal/Assets/Views/InvoiceHeader.php");

?>
<main>
    <div class="row contacts">
        <?php
            /*
            * Build the worksheet!
            */

            /*
            * Step 1: Output client information and column headers
            */


            //Now let's do Client Info header
            $Client = "SELECT * FROM `Client` WHERE `ClientID` = '" . EscapeSQLEntry($_GET["ClientID"]) . "'";
            $stm = $DatabaseConnection->prepare($Client);
            $stm->execute();
            $Client = $stm->fetchAll()[0];

            ?>
            <div class="col invoice-to">
                <div class="text-gray-light">CLIENT:</div>
                <h2 class="to"><?php echo $Client["ClientName"]; ?></h2>
                <div class="address"><?php echo $Client["StreetName"] . ", " . $Client["City"] . ", " . $Client["State"] . "  " . $Client["ZIP"]; ?></div>
                <div class="email"><a href="mailto:<?php echo $Client["Email"]; ?>"><?php echo $Client["Email"]; ?></a></div>
            </div>
            <div class="col invoice-details">
                <h3>Report Generated at: <?php echo date('m/d/Y h:i:s'); ?></h3>
                <h4>Client Default Hourly Rate: <?php echo "$" . $Client["HourlyDefaultRate"]; ?></h4>
            </div>
        </div>
       
        <?php
            /*
            * Now we need to make the worksheet
            */

            $Query = "SELECT 
                        *,
                        CASE 
                            WHEN S.`SlipStatus` = 'BAR' THEN \"Bill At Rate\"
                            WHEN S.`SlipStatus` = 'NC' THEN \"No Charge\"
                            WHEN S.`SlipStatus` = 'DNB' THEN \"Do Not Bill\"
                            ELSE \"No Slip Status Set!\"
                        END AS ComputedSlipStatus
                        FROM 
                            `Slip` AS S
                                INNER JOIN
                            `User` AS U
                                ON S.`Consultant` = U.`UserID`
                        WHERE `ClientID` = '" . EscapeSQLEntry($_GET['ClientID']) . "' 
                                AND 
                            `StartDate` between '" . EscapeSQLEntry($_GET['StartDate']) . "' AND '" . EscapeSQLEntry($_GET['EndDate']) . "' 
                        ORDER BY `StartDate` ASC";
            $stm = $DatabaseConnection->prepare($Query);
            $stm->execute();
            $Slips = $stm->fetchAll();
        ?>

        <table border="0" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th>Date / ID / Slip Type</th>
                <th>Consultant / Category / Task / Internal Notes</th>
                <th>Rate</th>
                <th>Hours  / DNB Hours <br>Cost / Qty</th>
                <th>Total Cost <br> DNB Cost</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            /*
            * Now output the slips!
            */
                $TotalCosts = 0;      
                $TotalBillableHours = 0;
                $TotalDNBHours = 0;

                foreach ($Slips as $Slip) {

                    ?>
                    <tr>
                        <td class='no'>
                            <?php echo date("m/d/Y", strtotime($Slip['StartDate'])); ?>
                                <br>
                            ID: <a href='<?php echo GetSysConfig("SystemURL"); ?>/Apps/BillingPortal/Slips/Slip.php?ID=<?php echo $Slip['SlipID']; ?>' style='color:yellow;' target='_blank'><?php echo $Slip['SlipID']; ?></a>
                            Type: <?php echo $Slip['TSType']; ?>
                        </td>

                        <td>
                            <strong><?php echo $Slip['Name']; ?></strong>
                            <?php
                                if(!empty($Slip['Category'])){
                                    ?><br><i>Category: <?php echo $Slip['Category']; ?></i><?php
                                }
                            ?>
                            <?php echo $Slip['Description']; ?>
                            
                            <?php
                                if(!empty($Slip['InternalNotes'])){
                                    ?><hr>Internal Notes: <br> <?php echo $Slip['InternalNotes']; ?><?php
                                }
                            ?>
                        </td>

                        <td>$<?php echo $Client['HourlyDefaultRate']; ?>/h</td>

                            <?php
                                if($Slip['TSType'] == "TS"){
                                    ?>
                                        <td><?php echo $Slip['Hours']; ?><br><?php echo $Slip['DNB']; ?></td>
                                        <td>$<?php echo $Slip['Hours'] * $Client["HourlyDefaultRate"]; ?><br>$<?php echo $Slip['DNB'] * $Client["HourlyDefaultRate"]; ?></td>
                                    <?php
                                } else if($Slip['TSType'] == "Expense"){
                                   ?>
                                        <td><?php echo $Slip['Price']; ?><br><?php echo $Slip['Quantity']; ?></td>
                                        <td>$<?php echo $Slip['Price'] * $Slip['Quantity']; ?></td>
                                    <?php
                                }
                            ?>

                        <td><?php echo $Slip['ComputedSlipStatus']; ?></td>

                    </tr>
                    <?php


                        if($Slip['TSType'] == "TS"){
                            $TotalCosts += ($Slip['Hours'] * $Client["HourlyDefaultRate"]) - ($Slip['DNB'] * $Client["HourlyDefaultRate"]);
                        } else if($Slip['TSType'] == "Expense"){
                            $TotalCosts += ($Slip['Price'] * $Slip['Quantity']);
                        }

                        $TotalBillableHours += $Slip['Hours'];
                        $TotalDNBHours += $Slip['DNB'];

                }

            ?>

            <tr class="no-bck">
                <td colspan="2"></td>
                <td colspan="2">Total Billable hours: </td>
                <td colspan="2"><?php echo $TotalBillableHours; ?></td>
            </tr>
            <tr class="no-bck">
                <td colspan="2"></td>
                <td colspan="2">Total DNB hours: </td>
                <td colspan="2"><?php echo $TotalDNBHours; ?></td>
            </tr>
            <tr class="no-bck">
                <td colspan="2"></td>
                <td colspan="2">Total Billable Fee's: </td>
                <td colspan="2">$<?php echo number_format($TotalCosts,2); ?></td>
            </tr>
        </tbody>
    </table>
        
    </div>
</main>
<?php require_once(SYSPATH ."/Apps/BillingPortal/Assets/Views/InvoiceFooter.php"); ?>