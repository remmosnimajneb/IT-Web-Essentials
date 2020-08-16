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
* Build Worksheet
*/

    /* Page Variables */
    $PageSecurityLevel = 1;
    $AppName = "BillingPortalAdmin";
    $PageName = "Worksheet";

    /* Include System Functions */
    require_once("../../../InitSystem.php");

    /* Get Header */
    require_once(SYSPATH . "/Apps/BillingPortal/Invoices/Assets/Views/InvoiceHeader.php");

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
            $Client = $stm->fetchAll();

            ?>
            <div class="col invoice-to">
                <div class="text-gray-light">INVOICE TO:</div>
                <h2 class="to"><?php echo $Client[0]["ClientName"]; ?></h2>
                <div class="address"><?php echo $Client[0]["StreetName"] . ", " . $Client[0]["City"] . ", " . $Client[0]["State"] . "  " . $Client[0]["ZIP"]; ?></div>
                <div class="email"><a href="mailto:<?php echo $Client[0]["Email"]; ?>"><?php echo $Client[0]["Email"]; ?></a></div>
            </div>
            <div class="col invoice-details">
                <h3>Report Generated at: <?php echo date('m/d/Y h:i:s'); ?></h3>
                <h4>Client Default Hourly Rate: <?php echo "$" . $Client[0]["HourlyDefaultRate"]; ?></h4>
            </div>
        </div>
       
        <?php
            /*
            * Now we need to make the worksheet
            */

            $sql = "SELECT * FROM `Slip` WHERE `ClientID` = '" . EscapeSQLEntry($_GET['ClientID']) . "' AND `StartDate` between '" . EscapeSQLEntry($_GET['StartDate']) . "' AND '" . EscapeSQLEntry($_GET['EndDate']) . "' ORDER BY `StartDate` ASC";
            $stm = $DatabaseConnection->prepare($sql);
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
                    echo "<tr>";
                        echo "<td class='no'>" . $Slip['StartDate'] . "<br>ID: <a href='../Slips/Slip.php?ID=" . $Slip['SlipID'] . "' style='color:yellow;' target='_blank'>" . $Slip['SlipID'] . "</a><br>Type: " . $Slip['TSType'] . "</td>";
                        echo "<td>" . $Slip['Consultant'] . "<br><i>Category: " . $Slip['Category'] . "</i><br>" . $Slip['Description'] . "<br><hr>Internal Notes: <br> " . $Slip['InternalNotes'] . "</td>";
                        echo "<td>$" . $Client[0]['HourlyDefaultRate'] . "/h</td>";
                            if($Slip['TSType'] == "TS"){
                                echo "<td>" . $Slip['Hours'] . "<br>" . $Slip['DNB'] . "</td>";
                                echo "<td>$" . $Slip['Hours'] * $Client[0]["HourlyDefaultRate"] . "<br>$" . $Slip['DNB'] * $Client[0]["HourlyDefaultRate"] . "</td>";
                            } else if($Slip['TSType'] == "Expense"){
                                echo "<td>" . $Slip['Price'] . "<br>" . $Slip['Quantity'] . "</td>";
                                echo "<td>$" . $Slip['Price'] * $Slip['Quantity'] . "</td>";
                            }
                        echo "<td>" . $Slip['SlipStatus'] . "</td>";
                    echo "</tr>";

                    if($Slip['TSType'] == "TS"){
                        $TotalCosts += ($Slip['Hours'] * $Client[0]["HourlyDefaultRate"]) - ($Slip['DNB'] * $Client[0]["HourlyDefaultRate"]);
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
<?php require_once(SYSPATH ."/Apps/BillingPortal/Invoices/Assets/Views/InvoiceFooter.php"); ?>