
<?php
    if(!empty($msg)) {
        if(is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init('.json_encode($msg).');', array('inline'=>true));
        }
        else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline'=>true));
        }
    }    
    else { 
?>

<div>
    <?php
    
        $payment_type_id = $paymentDetails['GeneralModulePaymentInfo']['payment_type_id'];
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
        } else {
            $title = "Payment Information";
        }

        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 'class' => 'mybtns',
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer'=>false)));
    ?>
    
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <div class="form" style="max-height:430px; overflow-y:auto; color:#232428;">

            <table cellpadding="7" cellspacing="8" border="0">
                <tr>
                    <td style="width:25%;">Name of Organization</td>
                    <td class="colons">:</td>
                    <td><?php echo $paymentDetails['BasicModuleBasicInformation']['full_name_of_org']; ?></td>
                </tr>
                <tr>
                    <td>Payment Type</td>
                    <td class="colons">:</td>
                    <td><?php echo $paymentDetails['LookupPaymentType']['payment_type']; ?></td>
                </tr>
                <tr>
                    <td>Payment No.</td>
                    <td class="colons">:</td>
                    <td><?php echo $paymentDetails['GeneralModulePaymentInfo']['payment_document_no']; ?></td>
                </tr>
                <tr>
                    <td>Payment Amount (BDT.)</td>
                    <td class="colons">:</td>
                    <td><?php echo $paymentDetails['GeneralModulePaymentInfo']['payment_amount']; ?></td>
                </tr>
                <tr>
                    <td>Date Of Payment</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Time->format($paymentDetails['GeneralModulePaymentInfo']['date_of_payment'],'%d-%m-%Y',''); ?></td>
                </tr>                
                <tr>
                    <td>Payment Doc Number</td>
                    <td class="colons">:</td>
                    <td><?php echo $paymentDetails['GeneralModulePaymentInfo']['payment_document_no']; ?></td>
                </tr>
            </table>

        </div>
    </fieldset>

</div>
<?php } ?>
