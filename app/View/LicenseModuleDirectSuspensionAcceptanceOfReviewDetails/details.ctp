<div>
    <fieldset>
        <legend>Accept/Reject Review Application Details</legend>
            <div class="form">

                <table cellpadding="7" cellspacing="8" border="0" style="width:90%;">                    
                    <tr>
                        <td style="width:20%;">Name of Organization</td>
                        <td class="colons">:</td>
                        <td style="width:80%;"><?php if(!empty($licApprovalDetails)) echo $licApprovalDetails['BasicModuleBasicInformation']['full_name_of_org']; ?></td>
                    </tr>                
                    <tr>
                        <td>Status</td>
                        <td class="colons">:</td>
                        <td>
                            <?php 
                            if($licApprovalDetails['LicenseModuleDirectSuspensionAcceptanceOfReviewDetail']['status_id']=='0'){
                                echo 'Review Rejected';
                            }
                            else if($licApprovalDetails['LicenseModuleDirectSuspensionAcceptanceOfReviewDetail']['status_id']=='1'){
                                echo 'Review Accepted';
                            }
                            ?>
                        </td>
                    </tr>
                    <?php 
                        if(!empty($licApprovalDetails) && $licApprovalDetails['LicenseModuleDirectSuspensionAcceptanceOfReviewDetail']['status_id']=='1')
                        {
                    ?>
                    <tr>
                        <td>Date</td>
                        <td class="colons">:</td>
                        <td><?php if(!empty($licApprovalDetails)) echo $this->Time->format($licApprovalDetails['LicenseModuleDirectSuspensionAcceptanceOfReviewDetail']['acceptance_date'],'%d-%m-%Y',''); ?></td>
                    </tr>
                    <?php 
                        }                       
                    ?>
                    <tr>
                        <td>Comment</td>
                        <td class="colons">:</td>
                        <td><?php if(!empty($licApprovalDetails)) echo $licApprovalDetails['LicenseModuleDirectSuspensionAcceptanceOfReviewDetail']['comment']; ?></td>
                    </tr>
                </table> 
            </div>
    </fieldset>
</div>