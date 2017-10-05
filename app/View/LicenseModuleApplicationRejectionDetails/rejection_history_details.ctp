<fieldset>
    <legend>Rejection History</legend>

    <div class="form" style="overflow:auto;">
        <?php
        if ($rejectionHistoryDetails == null || !is_array($rejectionHistoryDetails) || count($rejectionHistoryDetails) < 1) {
            echo '<p class="error-message">';
            echo 'This Organization has not yet rejected !';
            echo '</p>';
        } else {
        ?>

        <table class="view" style="width:150%;">
            <tr>
                <?php
                echo "<th style='width:200px;'>Previous State</th>";
                echo "<th style='width:200px;'>Rejection Type</th>";
                echo "<th style='width:250px;'>Rejection Category</th>";
                echo "<th style='width:270px;'>Rejection Reason</th>";
                echo "<th style='width:320px;'>Rejection Message</th>";
                echo "<th style='width:80px;'>Rejection Date</th>";
                echo "<th style='width:80px;'>Deadline Date</th>";
                ?>
            </tr>
            <?php foreach ($rejectionHistoryDetails as $value) { ?>
            <tr>
                <td style="text-align:left;"><?php echo $value['LicenseModuleStateName']['state_title']; ?></td>
                <td style="text-align:left;"><?php echo $value['LookupLicenseApplicationRejectionType']['rejection_type']; ?></td>
                <td style="text-align:left;"><?php echo $value['LookupLicenseApplicationRejectionCategory']['rejection_category']; ?></td>
                <td style="text-align:left;"><?php echo $value['LookupLicenseApplicationRejectionReason']['rejection_reason']; ?></td>
                <td style="text-align:left;"><?php echo $value['LicenseModuleApplicationRejectionHistoryDetail']['rejection_msg']; ?></td>
                <td style="text-align:center;"><?php echo $this->Time->format($value['LicenseModuleApplicationRejectionHistoryDetail']['rejection_date'], '%d-%m-%Y', ''); ?></td>
                <td style="text-align:center;"><?php echo $this->Time->format($value['LicenseModuleApplicationRejectionHistoryDetail']['deadline_date'], '%d-%m-%Y', ''); ?></td>
            </tr>
            <?php } ?>
        </table>
        <?php } ?>
    </div>
</fieldset>

