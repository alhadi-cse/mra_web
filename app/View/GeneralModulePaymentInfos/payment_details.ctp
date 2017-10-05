
<div>
    <?php
        if (!empty($payment_type_id)) {
            if ($payment_type_id == 1) {
                $title = "License Fee Payment Information";
            } else if ($payment_type_id == 2) {
                $title = "Annual Fee Payment Information";
            } else if ($payment_type_id == 3) {
                $title = "Renewal Fee Payment Information";
            } else if ($payment_type_id == 4) {
                $title = "Others Type of Payment Information";
            }
        }
        else {
            $title = "Payment Information";
        }
    ?>
    
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <div class="form">

            <table cellpadding="8" cellspacing="8" border="0">
                <tr>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td><?php echo $paymentDetails['BasicModuleBasicInformation']['full_name_of_org']; ?></td>
                </tr>
                <tr>
                    <td>Payment Type</td>
                    <td class="colons">:</td>
                    <td><?php echo $paymentDetails['LookupPaymentType']['payment_type']; ?></td>
                </tr>
                <tr>
                    <td>Payment Amount (BDT.)</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Number->precision($paymentDetails['GeneralModulePaymentInfo']['payment_amount'], 2); ?></td>
                </tr>
                <tr>
                    <td>Date Of Payment</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Time->format($paymentDetails['GeneralModulePaymentInfo']['date_of_payment'],'%d-%m-%Y',''); ?></td>
                </tr>                
                <tr>
                    <td>Payment Document No.</td>
                    <td class="colons">:</td>
                    <td><?php echo $paymentDetails['GeneralModulePaymentInfo']['payment_document_no']; ?></td>
                </tr>
            </table>

        </div>
    </fieldset>

</div>


