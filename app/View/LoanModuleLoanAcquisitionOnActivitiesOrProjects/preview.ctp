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
        <legend>Loan Acquisition on Activities or Projects Details</legend>
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
                    <td>Activity/Project Category</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LookupLoanActivityCategory']['loan_activity_category']; ?></td>
                </tr>
                <tr>
                    <td>Activity/Project Sub-Category</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LookupLoanActivitySubcategory']['loan_activity_subcategory']; ?></td>
                </tr>
                <tr>
                    <td>Activity/Project Scheme</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LookupLoanActivityScheme']['loan_activity_scheme']; ?></td>
                </tr>
                <tr>
                    <td>Year & Month</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Time->format($loanDetails['LoanModuleLoanAcquisitionOnActivitiesOrProject']['year_and_month'], '%B, %Y', ''); ?></td>
                </tr>          
                <tr>
                    <td>Date of Update</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Time->format($loanDetails['LoanModuleLoanAcquisitionOnActivitiesOrProject']['date_of_update'], '%d-%m-%Y', ''); ?></td>
                </tr>
                <tr>
                    <td>Disbursed Total Loan Amount</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LoanModuleLoanAcquisitionOnActivitiesOrProject']['total_loan_disbursed']; ?></td>
                </tr>         
                <tr>
                    <td>No. Of Borrowers</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LoanModuleLoanAcquisitionOnActivitiesOrProject']['no_of_borrowers']; ?></td>
                </tr>
                <tr>
                    <td>Start Date Of Target Amount</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Time->format($loanDetails['LoanModuleLoanAcquisitionOnActivitiesOrProject']['start_date_of_targeted_amount'], '%d-%m-%Y', ''); ?>
                </tr>
                <tr>
                    <td>End Date Of Target Amount</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Time->format($loanDetails['LoanModuleLoanAcquisitionOnActivitiesOrProject']['end_date_of_targeted_amount'], '%d-%m-%Y', ''); ?>
                </tr>
                <tr>
                    <td>Total Target Loan Amount</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LoanModuleLoanAcquisitionOnActivitiesOrProject']['total_targeted_loan_amount']; ?></td>
                </tr>
                <tr>
                    <td>Total Recovered Loan Amount</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LoanModuleLoanAcquisitionOnActivitiesOrProject']['total_recovered_loan_amount']; ?></td>
                </tr>
                <tr>
                    <td>Total Actually Recovered Loan Amount</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LoanModuleLoanAcquisitionOnActivitiesOrProject']['total_actually_recovered_loan_amount']; ?></td>
                </tr>
                <tr>
                    <td>Total Early Recovered Loan Amount</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LoanModuleLoanAcquisitionOnActivitiesOrProject']['total_early_recovered_loan_amount']; ?></td>
                </tr>
                <tr>
                    <td>Duration Of Loan</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LoanModuleLoanAcquisitionOnActivitiesOrProject']['duration_of_loan']; ?></td>
                </tr>
                <tr>
                    <td>Amount Of Principal Balance</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LoanModuleLoanAcquisitionOnActivitiesOrProject']['amount_of_principle_balance']; ?></td>
                </tr>
                <tr>
                    <td>Amount Of Service Charge Calculated</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LoanModuleLoanAcquisitionOnActivitiesOrProject']['amount_of_service_charge']; ?></td>
                </tr>
                <tr>
                    <td>Amount Of Service Charge Recovered</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LoanModuleLoanAcquisitionOnActivitiesOrProject']['amount_of_service_charge_recovered']; ?></td>
                </tr>
                <tr>
                    <td>Amount Of Outstanding Principal at Field</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LoanModuleLoanAcquisitionOnActivitiesOrProject']['amount_of_outstanding']; ?></td>
                </tr>
                <tr>
                    <td>Comment</td>
                    <td class="colons">:</td>
                    <td><?php echo $loanDetails['LoanModuleLoanAcquisitionOnActivitiesOrProject']['comments']; ?></td>
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
