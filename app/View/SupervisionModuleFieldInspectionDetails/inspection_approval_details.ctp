
<div>
    <?php
    if (!empty($inspection_type_id) && !empty($inspection_type_detail[$inspection_type_id]))
        $title = "$inspection_type_detail[$inspection_type_id] Approval Details";
    else
        $title = 'Field Inspection Approval Details';
    ?>

    <fieldset>
        <legend><?php echo $title; ?></legend>
        <div class="form">

            <?php
            if (empty($approvalDetails) || !is_array($approvalDetails) || count($approvalDetails) < 1) {
                echo '<p class="error-message">Not yet approved !</p>';
            } else {
                ?>

                <table class="view">
                    <tr>
                        <?php
                        echo "<th style='width:150px;'>" . $this->Paginator->sort('LookupLicenseApprovalStatus.approval_status', 'Status') . "</th>";
                        echo "<th style='width:80px;'>" . $this->Paginator->sort('SupervisionModuleFieldInspectionApprovalDetail.submission_date', 'Date') . "</th>";
                        echo "<th style='width:230px;'>" . $this->Paginator->sort('SupervisionModuleFieldInspectionApprovalDetail.inspection_comment', 'Comment') . "</th>";
                        echo "<th style='width:200px;'>" . $this->Paginator->sort('AdminModuleUserProfile.full_name_of_user', 'Approved by') . "</th>";
                        ?>
                    </tr>
                    <?php foreach ($approvalDetails as $value) { ?>
                        <tr>
                            <td style="text-align:center;"><?php echo $value['LookupLicenseApprovalStatus']['approval_status']; ?></td>
                            <td style="text-align:center;"><?php echo $this->Time->format($value['SupervisionModuleFieldInspectionApprovalDetail']['submission_date'], '%d-%m-%Y', ''); ?></td>
                            <td style="text-align:left;"><?php echo $value['SupervisionModuleFieldInspectionApprovalDetail']['inspection_comment']; ?></td>
                            <td style="text-align:left;"><?php echo $value['AdminModuleUserProfile']['full_name_of_user']; ?></td>
                        </tr>
                    <?php } ?>
                </table> 
            <?php } ?>

        </div>
    </fieldset>
</div>

