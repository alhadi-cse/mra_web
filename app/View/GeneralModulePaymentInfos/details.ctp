
<?php                 
    //echo $this->Form->create('BasicModuleBasicInformation');            
    //echo debug($paymentDetails);
?>

<div>
    <div id="basicInfo" title="General Information of Organization" style="margin:0px; padding:10px; background-color:#fafdff;"> 

        <fieldset>
            <legend>
                Payment Information Details
            </legend>
            <div class="form" style="max-height:430px; overflow-y:auto; color:#232428;">

                <table cellpadding="7" cellspacing="8" border="0">
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
                        <td>Payment Reason</td>
                        <td class="colons">:</td>
                        <td><?php echo $paymentDetails['GeneralModulePaymentInfo']['payment_reason']; ?></td>
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

</div>

