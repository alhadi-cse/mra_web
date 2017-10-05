
<div>
    <fieldset>
        <legend>License Evaluation Verification Details</legend>

        <?php
        if (empty($vrificationDetails)) {
            echo 'Did not find any License Evaluation verification information !';
        } else {
        ?>
        <table cellpadding="7" cellspacing="8" border="0" style="width:90%;">                    
            <tr>
                <td style="width:20%;">Verification Status</td>
                <td class="colons">:</td>
                <td style="width:80%;"><?php echo $vrificationDetails['LookupLicenseApprovalStatus']['verification_status']; ?></td>
            </tr>
            <tr>
                <td>Verification Date</td>
                <td class="colons">:</td>
                <td><?php echo $vrificationDetails['LicenseModuleLicenseEvaluationVerificationDetail']['verification_date']; ?></td>
            </tr>
            <tr>
                <td>Comment</td>
                <td class="colons">:</td>
                <td><?php echo $vrificationDetails['LicenseModuleLicenseEvaluationVerificationDetail']['verification_comment']; ?></td>
            </tr>
            <tr>
                <td>Verified By</td>
                <td class="colons">:</td>
                <td><?php echo $vrificationDetails['AdminModuleUserProfile']['full_name_of_user']; ?></td>
            </tr>
        </table>
        <?php } ?>
    </fieldset>

    <fieldset style="margin-top:7px;">
        <legend>License Evaluation Verification Approval Details</legend>
        <?php
        if (empty($vrificationApprovalDetails) || !is_array($vrificationApprovalDetails) || count($vrificationApprovalDetails) < 1) {
            echo '<p class="error-message">Did not find any License Evaluation verification approval information !</p>';
        } else {
        ?>
        <table class="view">
            <tr>
            <?php
                echo "<th style='width:80px;'>" . $this->Paginator->sort('LookupLicenseApprovalStatus.approval_status', 'Approval Status') . "</th>";
                echo "<th style='width:80px;'>" . $this->Paginator->sort('LicenseModuleLicenseEvaluationVerificationApprovalDetail.approval_date', 'Approval Date') . "</th>";
                echo "<th style='width:250px;'>" . $this->Paginator->sort('LicenseModuleLicenseEvaluationVerificationApprovalDetail.approval_comment', 'Comment') . "</th>";
                echo "<th style='width:200px;'>" . $this->Paginator->sort('AdminModuleUserProfile.full_name_of_user', 'Approved by (Committee Member)') . "</th>";
            ?>
            </tr>
            <?php foreach ($vrificationApprovalDetails as $value) { ?>
            <tr>
                <td style="text-align:center;"><?php echo $value['LookupLicenseApprovalStatus']['approval_status']; ?></td>
                <td style="text-align:center;"><?php echo $this->Time->format($value['LicenseModuleLicenseEvaluationVerificationApprovalDetail']['approval_date'], '%d-%m-%Y', ''); ?></td>
                <td style="text-align:left;"><?php echo $value['LicenseModuleLicenseEvaluationVerificationApprovalDetail']['approval_comment']; ?></td>
                <td style="text-align:left;"><?php echo $value['AdminModuleUserProfile']['full_name_of_user']; ?></td>
            </tr>
            <?php } ?>
        </table>
        <?php } ?>
    </fieldset>

</div>
