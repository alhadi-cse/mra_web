
<div>
    <fieldset>
        <legend>Final Approval Details</legend>
            <div class="form">

                <table cellpadding="7" cellspacing="8" border="0">                    
                    <tr>
                        <td>Name of Organization</td>
                        <td class="colons">:</td>
                        <td><?php if(!empty($licApprovalDetails)) echo $licApprovalDetails['BasicModuleBasicInformation']['full_name_of_org']; ?></td>
                    </tr>                
                    <tr>
                        <td>Approval Status</td>
                        <td class="colons">:</td>
                        <td><?php if(!empty($licApprovalDetails)) echo $licApprovalDetails['LookupLicenseApprovalStatus']['approval_status']; ?></td>
                    </tr>
                    <?php 
                        if(!empty($licApprovalDetails) && $licApprovalDetails['LicenseModuleAdministrativeApproval']['approval_status_id']=="1")
                        {
                    ?>
                    <tr>
                        <td>Date of Approval</td>
                        <td class="colons">:</td>
                        <td><?php if(!empty($licApprovalDetails)) echo $this->Time->format($licApprovalDetails['LicenseModuleAdministrativeApproval']['approval_date'],'%d-%m-%Y',''); ?></td>
                    </tr>
                    <?php 
                        }
                        else if(!empty($licApprovalDetails) && $licApprovalDetails['LicenseModuleAdministrativeApproval']['approval_status_id']=="2")
                        {
                    ?>
                    <tr>
                        <td>Reason (if not approved)</td>
                        <td class="colons">:</td>
                        <td><?php if(!empty($licApprovalDetails)) echo $licApprovalDetails['LicenseModuleAdministrativeApproval']['reason_if_not_approved']; ?></td>
                    </tr>
                    <?php 
                        }
                    ?>
                    <tr>
                        <td>Comment</td>
                        <td class="colons">:</td>
                        <td><?php if(!empty($licApprovalDetails)) echo $licApprovalDetails['LicenseModuleAdministrativeApproval']['comment']; ?></td>
                    </tr>
                </table> 
            </div>
    </fieldset>
</div>


