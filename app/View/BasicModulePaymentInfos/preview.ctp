
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

<div id="basicInfo" title="General Information of Organization" style="margin:0px; padding:10px; background-color:#fafdff;"> 

    <fieldset>
        <legend>Payment Information Details</legend>
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
                    <td>Payment No.</td>
                    <td class="colons">:</td>
                    <td><?php echo $paymentDetails['BasicModulePaymentInfo']['payment_no']; ?></td>
                </tr>
                <tr>
                    <td>Payment Amount</td>
                    <td class="colons">:</td>
                    <td><?php echo $paymentDetails['BasicModulePaymentInfo']['paymentAmount']; ?></td>
                </tr>
                <tr>
                    <td>Date Of Payment</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Time->format($paymentDetails['BasicModulePaymentInfo']['dateOfPayment'],'%d-%m-%Y',''); ?></td>
                </tr>                
                <tr>
                    <td>Payment Doc Number</td>
                    <td class="colons">:</td>
                    <td><?php echo $paymentDetails['BasicModulePaymentInfo']['paymentDocNumber']; ?></td>
                </tr>
            </table>

        </div>
    </fieldset>

</div>

<script>
    $(function () {
        $("#basicInfo").dialog({
            modal: true, width: 'auto', height: 'auto', resizable: false, dialogClass: 'my-dialog-box', 
            buttons: {
                Close: function () {
                    $(this).dialog("close");
                }
            },
            create: function(evt, ui) {
                $(this).css("minWidth", "850px").css("maxWidth", "1000px");
            }
        });
    });
</script>

<?php } ?>