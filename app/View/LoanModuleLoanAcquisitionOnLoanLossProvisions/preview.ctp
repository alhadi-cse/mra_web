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

<div id="basicInfo" title="Loan Information of Organization" style="margin:0px; padding:10px; background-color:#fafdff;"> 

    <fieldset>
        <legend>Loan Acquisition on Loan Loss Provision</legend>
        <div class="form" style="max-height:430px; overflow-y:auto; color:#232428;">

            <table cellpadding="7" cellspacing="8" border="0">
                <tr>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['BasicModuleBasicInformation']['full_name_of_org']; ?></td>
                </tr>
                <tr>
                    <td>Branch Name</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['BasicModuleBranchInfo']['branch_name']; ?></td>
                </tr>
                <tr>
                    <td>Loan Provision</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LookupLoanLossProvisioning']['loan_loss_provisionings']; ?></td>
                </tr>
                <tr>
                    <td>Provision Required</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LoanModuleLoanAcquisitionOnLoanLossProvision']['provision_required']; ?></td>
                </tr>
                <tr>
                    <td>Provision Maintained</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LoanModuleLoanAcquisitionOnLoanLossProvision']['provision_maintained']; ?></td>
                </tr>
                <tr>
                    <td>Provision Shortfall</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LoanModuleLoanAcquisitionOnLoanLossProvision']['provision_shortfall']; ?></td>
                </tr>
                <tr>
                    <td>Year & Month</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Time->format($loanDetails['LoanModuleLoanAcquisitionOnLoanLossProvision']['year_and_month'], '%B, %Y', ''); ?></td>
                </tr>
                <tr>
                    <td>Date of Update</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Time->format($loanDetails['LoanModuleLoanAcquisitionOnLoanLossProvision']['date_of_update'], '%d-%m-%Y', ''); ?></td>
                </tr>
                <tr>
                    <td>Amount of Loan</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LoanModuleLoanAcquisitionOnLoanLossProvision']['amount_of_loan']; ?></td>
                </tr>
                <tr>
                    <td>Number Of Borrowers</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LoanModuleLoanAcquisitionOnLoanLossProvision']['no_of_borrowers']; ?></td>
                </tr>               
                <tr>
                    <td>Amount of Reserve for Provision</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LoanModuleLoanAcquisitionOnLoanLossProvision']['amount_of_reserve']; ?></td>
                </tr>
<!--                <tr>
                    <td>Comment</td>
                    <td class="colons">:</td>
                    <td><?php //echo $loanDetails['LoanModuleLoanAcquisitionOnLoanLossProvision']['comments']; ?></td>
                </tr>-->
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
