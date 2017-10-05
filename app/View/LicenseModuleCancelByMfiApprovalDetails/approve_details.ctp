<div>
    <fieldset>
        <legend>Approval for License Cancellation</legend>
            <div class="datagrid" style="max-height:430px; overflow-y:auto; color:#232428;">
                <?php if(!empty($approvalDetails)) { ?>
                <table style="width:90%;">
                    <tr>
                        <th>Authorized Person</th>
                        <th>Approval Status</th>
                        <th>Comments</th>
                    </tr>
                    <tr>
                        <td>Assistant Director(AD)</td>
                        <td><?php echo $approvalDetails['LookupLicenseApprovalStatus_AD']['approval_status'];?></td>
                        <td><?php echo $approvalDetails['LicenseModuleCancelByMfiApprovalDetail']['comments_or_notes_of_ad'];?></td>
                    </tr>
                    <tr>
                        <td>Senior Assistant Director(SAD)</td>
                        <td><?php echo $approvalDetails['LookupLicenseApprovalStatus_SAD']['approval_status'];?></td>
                        <td><?php echo $approvalDetails['LicenseModuleCancelByMfiApprovalDetail']['comments_or_notes_of_sad'];?></td>
                    </tr>
                    <tr>
                        <td>Deputy Director(DD)</td>
                        <td><?php echo $approvalDetails['LookupLicenseApprovalStatus_DD']['approval_status'];?></td>
                        <td><?php echo $approvalDetails['LicenseModuleCancelByMfiApprovalDetail']['comments_or_notes_of_dd'];?></td>
                    </tr>
                    <tr>
                        <td>Senior Deputy Director(SDD)</td>
                        <td><?php echo $approvalDetails['LookupLicenseApprovalStatus_SDD']['approval_status'];?></td>
                        <td><?php echo $approvalDetails['LicenseModuleCancelByMfiApprovalDetail']['comments_or_notes_of_sdd'];?></td>
                    </tr>
                    <tr>
                        <td>Director</td>
                        <td><?php echo $approvalDetails['LookupLicenseApprovalStatus_Director']['approval_status'];?></td>
                        <td><?php echo $approvalDetails['LicenseModuleCancelByMfiApprovalDetail']['comments_or_notes_of_director'];?></td>
                    </tr>
                    <tr>
                        <td>Executive Vice Chairman(EVC)</td>
                        <td><?php echo $approvalDetails['LookupLicenseApprovalStatus_EVC']['approval_status'];?></td>
                        <td><?php echo $approvalDetails['LicenseModuleCancelByMfiApprovalDetail']['comments_or_notes_of_evc'];?></td>
                    </tr>                                        
                </table> 
                <?php } else { echo '<p class="error-message">No data is available!</p>'; } ?>
            </div>
    </fieldset>
</div>