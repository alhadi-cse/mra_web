
<div id="basicInfo" title="General Information of Organization" style="margin:0px; padding:10px; background-color:#fafdff;"> 

    <?php
    $title = (!empty($paymentDetails['LookupLicensePaymentType']['payment_type'])) ? $paymentDetails['LookupLicensePaymentType']['payment_type'] . "Payment Information" : "Payment Information";

    $mfiName = $paymentDetails['BasicModuleBasicInformation']['short_name_of_org'];
    $mfiFullName = $paymentDetails['BasicModuleBasicInformation']['full_name_of_org'];

    $mfiName = ((!empty($mfiName) && !empty($mfiFullName)) ? "$mfiFullName <strong>($mfiName)</strong>" : "$mfiFullName$mfiName");
    ?>

    <fieldset>
        <legend><?php echo $title; ?></legend>
        <div class="form" style="max-height:430px; overflow-y:auto; color:#232428;">

            <table cellpadding="7" cellspacing="8" border="0" style="width:85%;">
                <tr>
                    <td style="width:25%;">Name of Organization</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiName; ?></td>
                </tr>
                <tr>
                    <td>Payment Type</td>
                    <td class="colons">:</td>
                    <td><?php echo $paymentDetails['LookupLicensePaymentType']['payment_type']; ?></td>
                </tr>
                <?php
                if (!empty($paymentDetails['LicenseModulePaymentDetail']['payment_reason'])) {
                    echo '<tr>'
                    . '<td>Payment Reason</td>'
                    . '<td class="colons">:</td>'
                    . '<td>' . $paymentDetails['LicenseModulePaymentDetail']['payment_reason'] . '</td>'
                    . '</tr>';
                }
                ?>
                <tr>
                    <td>Payment Amount</td>
                    <td class="colons">:</td>
                    <td>
                        <?php
                        if (!empty($paymentDetails['LicenseModulePaymentDetail']['payment_amount']))
                            echo $this->Number->precision($paymentDetails['LicenseModulePaymentDetail']['payment_amount'], 2) . " Tk.";
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Payment Fiscal Year</td>
                    <td class="colons">:</td>
                    <td><?php echo $paymentDetails['LicenseModulePaymentDetail']['payment_fiscal_year']; ?></td>
                </tr>
                <tr>
                    <td>Date Of Payment</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Time->format($paymentDetails['LicenseModulePaymentDetail']['payment_date'], '%d-%m-%Y', ''); ?></td>
                </tr>
                <tr>
                    <td>Payment Document</td>
                    <td class="colons">:</td>
                    <td><?php echo $paymentDetails['LicenseModulePaymentDetail']['payment_document_no']; ?></td>
                </tr>
            </table>

        </div>
    </fieldset>

</div>

<script>
    $(function () {
        $("#basicInfo").dialog({
            modal: true, width: 870,
            buttons: {
                Close: function () {
                    $(this ).dialog("close");
                }
            }
        });
    });
</script>

