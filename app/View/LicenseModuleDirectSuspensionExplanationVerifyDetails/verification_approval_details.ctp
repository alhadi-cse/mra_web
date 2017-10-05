
<div>
    <fieldset>
            <legend> Explanation Against Suspension Verification Approval Details</legend>
            <div class="form">

                <?php
                if ($vrificationApprovalDetails == null || !is_array($vrificationApprovalDetails) || count($vrificationApprovalDetails) < 1) {
                    echo '<p class="error-message">';
                    echo 'No data is available !';
                    echo '</p>';
                } else {
                ?>
        
                <table class="view">
                    <tr>
                        <?php
                        echo "<th style='width:80px;'>" . $this->Paginator->sort('LookupLicenseApprovalStatus.approval_status', 'Status') . "</th>";
                        echo "<th style='width:80px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentVerificationApprovalDetail.approval_date', 'Date') . "</th>";
                        echo "<th style='width:250px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentVerificationApprovalDetail.approval_comment', 'Comment') . "</th>";
                        echo "<th style='width:200px;'>" . $this->Paginator->sort('AdminModuleUserProfile.full_name_of_user', 'Approved by') . "</th>";
                        ?>
                    </tr>
                    <?php foreach ($vrificationApprovalDetails as $value) { ?>
                        <tr>
                            <td style="text-align:center;"><?php echo $value['LookupLicenseApprovalStatus']['approval_status']; ?></td>
                            <td style="text-align:center;"><?php echo $this->Time->format($value['LicenseModuleInitialAssessmentVerificationApprovalDetail']['approval_date'], '%d-%m-%Y', ''); ?></td>
                            <td style="text-align:left;"><?php echo $value['LicenseModuleInitialAssessmentVerificationApprovalDetail']['approval_comment']; ?></td>
                            <td style="text-align:left;"><?php echo $value['AdminModuleUserProfile']['full_name_of_user']; ?></td>
                        </tr>
                    <?php } ?>
                </table> 
                <?php } ?>


            </div>
    </fieldset>
</div>
