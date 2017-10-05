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
        <legend>Loan Acquisition on Loan Size Partition Details</legend>
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
                    <td>Loan Size Partition on the basis of Loan Disbursed</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LookupLoanSizePartitionOnDisburse']['loan_size_partition_on_disbursed']; ?></td>
                </tr> 
                <tr>
                    <td>Loan Size Partition on the basis of Loan Outstanding</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LookupLoanSizePartitionOnOutstanding']['loan_size_partition_on_outstanding']; ?></td>
                </tr>
                
                <tr>
                    <td>Year & Month</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Time->format($loanDetails['LoanModuleLoanAcquisitionOnLoanSize']['year_and_month'], '%B, %Y', ''); ?></td>
                </tr>                                        
                <tr>
                    <td>Date of Update</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Time->format($loanDetails['LoanModuleLoanAcquisitionOnLoanSize']['date_of_update'], '%d-%m-%Y', ''); ?></td>
                </tr>
                <tr>
                    <td>No. Of Borrowers</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LoanModuleLoanAcquisitionOnLoanSize']['no_of_borrowers']; ?></td>
                </tr>               
                <tr>
                    <td>No. of Male Borrowers</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LoanModuleLoanAcquisitionOnLoanSize']['no_of_male_borrowers']; ?></td>
                </tr>
                <tr>
                    <td>No. of Female Borrowers</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LoanModuleLoanAcquisitionOnLoanSize']['no_of_female_borrowers']; ?></td>
                </tr>
                <tr>
                    <td>Amount of Total Principal Balance</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LoanModuleLoanAcquisitionOnLoanSize']['amount_of_total_principal_balance']; ?></td>
                </tr>
                <tr>
                    <td>No. of Loans Disbursed</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LoanModuleLoanAcquisitionOnLoanSize']['no_of_loans_disbursed']; ?></td>
                </tr>                
                <tr>
                    <td>Amount of Total Disbursed Loan</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LoanModuleLoanAcquisitionOnLoanSize']['amount_of_total_disbursed_loan_balance']; ?></td>
                </tr>
<!--                <tr>
                    <td>Comment</td>
                    <td class="colons">:</td>
                    <td><?php //echo $loanDetails['LoanModuleLoanAcquisitionOnLoanSize']['comments']; ?></td>
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
