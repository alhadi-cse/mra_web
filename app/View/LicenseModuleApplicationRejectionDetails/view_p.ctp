
<?php
if (isset($msg) && !empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}

$hasAnyData = false;
$user_group_id = $this->Session->read('User.GroupIds');
$isAdmin = (!empty($user_group_id) && in_array(1,$user_group_id));

$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

$this->Paginator->options($pageLoading);

?>

<div>

    <?php
    if ((!empty($values_waiting_for_appealed) && is_array($values_waiting_for_appealed) && count($values_waiting_for_appealed) > 0) || (!empty($values_evaluated_failed) && is_array($values_evaluated_failed) && count($values_evaluated_failed) > 0) || (!empty($values_not_approved_in_admin_approval) && is_array($values_not_approved_in_admin_approval) && count($values_not_approved_in_admin_approval))) {
        $hasAnyData = true;
    ?>
        <fieldset>
            <legend>Initial Rejection</legend>
            
                <?php if (!empty($values_waiting_for_appealed) && is_array($values_waiting_for_appealed) && count($values_waiting_for_appealed) > 0) { ?>

                <fieldset>
                    <legend>Waiting for Appeal</legend>

                    <table class="view">
                        <tr>
                            <?php
                            echo "<th style='width:85px;'>"
                                    . (!$this->Paginator->param('options') ? $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.', array('class' => 'asc')) : $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.')) 
                                    . "</th>";

                            echo "<th style='min-width:375px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                            echo "<th style='width:100px;'>" . $this->Paginator->sort('LicenseModuleStateHistory.date_of_deadline', 'Deadline Date') . "</th>";
                            echo "<th style='width:70px;'>Action</th>";
                            ?>
                        </tr>
                        <?php
                        $rc = -1;
                        foreach ($values_waiting_for_appealed as $orgDetail) {
                            ++$rc;
                        ?>
                        <tr>
                            <td style="text-align:center;">
                                <?php echo $orgDetail['BasicModuleBasicInformation']['form_serial_no']; ?>
                            </td>
                            <td>
                                <?php
                                $mfiName = $orgDetail['BasicModuleBasicInformation']['short_name_of_org'];
                                $mfiFullName = $orgDetail['BasicModuleBasicInformation']['full_name_of_org'];

                                if (!empty($mfiName))
                                    $mfiName = "<strong>" . $mfiName . ":</strong> ";

                                if (!empty($mfiFullName))
                                    $mfiName = $mfiName . $mfiFullName;

                                echo $mfiName;
                                ?>
                            </td>
                            <td style="font-weight:bold; text-align:center;">
                                <?php echo $this->Time->format($orgDetail['LicenseModuleStateHistory']['date_of_deadline'], '%d-%m-%Y', ''); ?>
                            </td>
                            <td style="height:30px; padding:2px; text-align:center;">
                                <?php
                                echo $this->Js->link('Details', array('controller' => 'LicenseModuleInitialAssessmentDetails', 'action' => 'preview', $orgDetail['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));

//                            echo $this->Js->link('Appeal', array('controller'=>'LicenseModuleApplicationRejectionDetails', 'action'=>'appeal', $orgDetail['BasicModuleBasicInformation']['id'], 23), 
//                                                            array_merge($pageLoading, array('class'=>'btnlink', )));
                                ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                </fieldset>
                <?php } ?>
            

                <?php if (!empty($values_evaluated_failed) && is_array($values_evaluated_failed) && count($values_evaluated_failed) > 0) { ?>

                <fieldset>
                    <legend>Failed in Initial Evaluation</legend>

                    <table class="view">
                        <tr>
                            <?php
                            echo "<th style='width:85px;'>"
                                    . (!$this->Paginator->param('options') ? $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.', array('class' => 'asc')) : $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.'))
                                    . (count($values_evaluated_failed) > 1 ? " <br /><span style='padding:0; color:#fa8713;'>Select All <input type='checkbox' id='chkbSelectAll'/></span>" : "")
                                    . "</th>";

                            echo "<th style='min-width:375px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                            echo "<th style='width:100px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'Total Marks Obtained') . '<br /> <strong style="color:#faf0af;">(out of ' . $this->Number->precision($total_marks, 1) . ')</strong>' . "</th>";
                            echo "<th style='width:70px;'>Action</th>";
                            ?>
                        </tr>
                        <?php
                        $rc = -1;
                        //debug($values_evaluated_failed);
                        foreach ($values_evaluated_failed as $orgDetail) {
                            ++$rc;
                        ?>
                        <tr>
                            <td style="text-align:center;">
                            <?php
                            echo $orgDetail['BasicModuleBasicInformation']['form_serial_no'];
                            ?>
                            </td>
                            <td>
                        <?php
                        $mfiName = $orgDetail['BasicModuleBasicInformation']['short_name_of_org'];
                        $mfiFullName = $orgDetail['BasicModuleBasicInformation']['full_name_of_org'];

                        if (!empty($mfiName))
                            $mfiName = "<strong>" . $mfiName . ":</strong> ";

                        if (!empty($mfiFullName))
                            $mfiName = $mfiName . $mfiFullName;

                        echo $mfiName;
                        ?>
                            </td>
                            <td style="font-weight:bold; text-align:center;">
                                <?php
                                $obtained_marks = $orgDetail['LicenseModuleInitialAssessmentMark']['total_assessment_marks'];

                                if ($obtained_marks >= $assessment_marks['watchOut_min_marks'])
                                    echo '<span style="color:#faaf13;">';
                                else
                                    echo '<span style="color:#fa2413;">';

                                if (!empty($total_marks) && $total_marks != 0 && !empty($assessment_marks)) {
                                    echo $this->Number->toPercentage(($obtained_marks / $total_marks) * 100) . '</span><br/>(' .
                                    $this->Number->precision($obtained_marks, 1) . ')';
                                } else
                                    echo $this->Number->precision($obtained_marks, 1);
                                ?>
                            </td>

                            <td style="height:30px; padding:2px; text-align:center;">
                                <?php
                                echo $this->Js->link('Details', array('controller' => 'LicenseModuleInitialAssessmentDetails', 'action' => 'preview', $orgDetail['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));

                                if ($isAdmin)
                                    echo $this->Js->link('Reject and Notify', array('controller' => 'LicenseModuleApplicationRejectionDetails', 'action' => 'initial_rejection', $orgDetail['BasicModuleBasicInformation']['id'], 4, 21), array_merge($pageLoading, array('class' => 'btnlink')));
                                ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                </fieldset>
                <?php } ?>
            
            
                <?php if (!empty($values_not_approved_in_admin_approval) && is_array($values_not_approved_in_admin_approval) && count($values_not_approved_in_admin_approval) > 0) { ?>

                <fieldset>
                    <legend>Failed in Administrative Approval</legend>

                    <table class="view">
                        <tr>
                            <?php
                            echo "<th style='width:85px;'>"
                                    . (!$this->Paginator->param('options') ? $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.', array('class' => 'asc')) : $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.'))
                                    . (count($values_not_approved_in_admin_approval) > 1 ? " <br /><span style='padding:0; color:#fa8713;'>Select All <input type='checkbox' id='chkbSelectAll'/></span>" : "")
                                    . "</th>";

                            echo "<th style='min-width:375px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                            echo "<th style='width:100px;'>" . $this->Paginator->sort('LookupLicenseApprovalStatus.approval_status', 'Approval Status') . "</th>";
                            echo "<th style='width:70px;'>Action</th>";
                            ?>
                        </tr>
                        <?php
                        $rc = -1;
                        foreach ($values_not_approved_in_admin_approval as $orgDetail) {
                            ++$rc;
                        ?>
                        <tr>
                            <td style="text-align:center;">
                                <?php echo $orgDetail['BasicModuleBasicInformation']['form_serial_no']; ?>
                            </td>
                            <td>
                                <?php
                                $mfiName = $orgDetail['BasicModuleBasicInformation']['short_name_of_org'];
                                $mfiFullName = $orgDetail['BasicModuleBasicInformation']['full_name_of_org'];

                                if (!empty($mfiName))
                                    $mfiName = "<strong>" . $mfiName . ":</strong> ";

                                if (!empty($mfiFullName))
                                    $mfiName = $mfiName . $mfiFullName;

                                echo $mfiName;
                                ?>
                            </td>
                            <td style="font-weight:bold; text-align:center; color:#fa2413;">
                                <?php echo $orgDetail['LookupLicenseApprovalStatus']['approval_status']; ?>
                            </td>

                            <td style="height:30px; padding:2px; text-align:center;">
                                <?php
                                echo $this->Js->link('Details', array('controller' => 'LicenseModuleInitialAssessmentAdminApprovalDetails', 'action' => 'preview', $orgDetail['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));

                                if ($isAdmin)
                                    echo $this->Js->link('Reject and Notify', array('controller' => 'LicenseModuleApplicationRejectionDetails', 'action' => 'initial_rejection', $orgDetail['BasicModuleBasicInformation']['id'], 9, 21), array_merge($pageLoading, array('class' => 'btnlink')));
                                ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                </fieldset>
                <?php } ?>
            
            

                <?php
                if ($isAdmin && $rc > 0)
                    echo '<div  class="btns-div" style="text-align:center;">'
                            . $this->Js->link('Initial Reject and Notify All', array('controller' => 'LicenseModuleApplicationRejectionDetails', 'action' => 'initial_rejection_all', 4, 21), array_merge($pageLoading, array('class' => 'mybtns', 'style' => 'margin:7px auto;')))
                            . '</div>';
                ?>
            </fieldset>
            <?php } ?>
    
    
        <?php
        if ((!empty($values_not_appealed) && is_array($values_not_appealed) && count($values_not_appealed) > 0) || (!empty($values_appeal_but_failed) && is_array($values_appeal_but_failed) && count($values_appeal_but_failed) > 0)) {
            $hasAnyData = true;
        ?>
        <fieldset style="margin-top:15px;">
            <legend>Final Rejection</legend>

            <?php
            $rc = -1;
            if (!empty($values_not_appealed) && is_array($values_not_appealed) && count($values_not_appealed) > 0) {
            ?>

            <fieldset>
                <legend>Rejected and Not Appealed</legend>

                <table class="view">
                    <tr>
                        <?php
                        echo "<th style='width:85px;'>"
                                . (!$this->Paginator->param('options') ? $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.', array('class' => 'asc')) : $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.'))
                                . (count($values_not_appealed) > 1 ? " <br /><span style='padding:0; color:#fa8713;'>Select All <input type='checkbox' id='chkbSelectAllRNA'/></span>" : "")
                                . "</th>";

                        echo "<th style='min-width:375px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:100px;'>" . $this->Paginator->sort('LicenseModuleStateHistory.date_of_deadline', 'Deadline Date') . "</th>";
                        echo "<th style='width:70px;'>Action</th>";
                        ?>
                    </tr>

                    <?php
                    foreach ($values_not_appealed as $orgDetail) {
                        ++$rc;
                    ?>
                    <tr>
                        <td style="text-align:center;">
                            <?php echo $orgDetail['BasicModuleBasicInformation']['form_serial_no']; ?>
                        </td>
                        <td>
                            <?php
                            $mfiName = $orgDetail['BasicModuleBasicInformation']['short_name_of_org'];
                            $mfiFullName = $orgDetail['BasicModuleBasicInformation']['full_name_of_org'];

                            if (!empty($mfiName))
                                $mfiName = "<strong>" . $mfiName . ":</strong> ";

                            if (!empty($mfiFullName))
                                $mfiName = $mfiName . $mfiFullName;

                            echo $mfiName;
                            ?>
                        </td>
                        <td style="font-weight:bold; text-align:center; color:#fa2413;">
                            <?php echo $this->Time->format($orgDetail['LicenseModuleStateHistory']['date_of_deadline'], '%d-%m-%Y', ''); ?>
                        </td>

                        <td style="height:30px; padding:2px; text-align:center;">
                            <?php
                            echo $this->Js->link('Details', array('controller' => 'LicenseModuleInitialAssessmentDetails', 'action' => 'preview', $orgDetail['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));

                            if ($isAdmin)
                                echo $this->Js->link('Reject and Notify', array('controller' => 'LicenseModuleApplicationRejectionDetails', 'action' => 'final_rejection', $orgDetail['BasicModuleBasicInformation']['id'], 4, 21, 50), array_merge($pageLoading, array('class' => 'btnlink')));
                            ?>
                        </td>
                    </tr>
                    <?php } ?>

                </table>

            </fieldset>
            <?php } ?>
            
            
            <?php
            if (!empty($values_appeal_but_failed) && is_array($values_appeal_but_failed) && count($values_appeal_but_failed) > 0) {
            ?>
            <fieldset>
                <legend>Appeal but Fail again</legend>

                <table class="view">
                    <tr>
                        <?php
                        echo "<th style='width:85px;'>"
                        . (!$this->Paginator->param('options') ?
                                $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.', array('class' => 'asc')) :
                                $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.'))
                        . "</th>";
                        echo "<th style='min-width:375px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:100px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'Total Marks Obtained') . '<br /> <strong style="color:#faf0af;">(out of ' . $this->Number->precision($total_marks, 1) . ')</strong>' . "</th>";
                        echo "<th style='width:70px;'>Action</th>";
                        ?>
                    </tr>
                    <?php
                    foreach ($values_appeal_but_failed as $orgDetail) {
                        ++$rc;
                    ?>
                    <tr>
                        <td style="text-align:center;">
                            <?php echo $orgDetail['BasicModuleBasicInformation']['form_serial_no']; ?>
                        </td>
                        <td>
                            <?php
                            $mfiName = $orgDetail['BasicModuleBasicInformation']['short_name_of_org'];
                            $mfiFullName = $orgDetail['BasicModuleBasicInformation']['full_name_of_org'];

                            if (!empty($mfiName))
                                $mfiName = "<strong>" . $mfiName . ":</strong> ";

                            if (!empty($mfiFullName))
                                $mfiName = $mfiName . $mfiFullName;

                            echo $mfiName;
                            ?>
                        </td>
                        <td style="font-weight:bold; text-align:center;">
                            <?php
                            $obtained_marks = $orgDetail['LicenseModuleInitialAssessmentMark']['total_assessment_marks'];

                            if ($obtained_marks >= $assessment_marks['watchOut_min_marks'])
                                echo '<span style="color:#faaf13;">';
                            else
                                echo '<span style="color:#fa2413;">';

                            if (!empty($total_marks) && $total_marks != 0 && !empty($assessment_marks)) {
                                echo $this->Number->toPercentage(($obtained_marks / $total_marks) * 100) . '</span><br/>(' .
                                $this->Number->precision($obtained_marks, 1) . ')';
                            } else
                                echo $this->Number->precision($obtained_marks, 1);
                            ?>
                        </td>
                        <td style="height:30px; padding:2px; text-align:center;">
                            <?php
                            echo $this->Js->link('Details', array('controller' => 'LicenseModuleInitialAssessmentDetails', 'action' => 'preview', $orgDetail['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                            if ($isAdmin)
                                echo $this->Js->link('Reject and Notify', array('controller' => 'LicenseModuleApplicationRejectionDetails', 'action' => 'rejection', $orgDetail['BasicModuleBasicInformation']['id'], 1, '7_48_50', 2), array_merge($pageLoading, array('class' => 'btnlink')));
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </fieldset>
            <?php } 

            if ($isAdmin && $rc > 0)
                echo '<div  class="btns-div" style="text-align:center;">'
                . $this->Js->link('Final Rejection and Notify All', array('controller' => 'LicenseModuleApplicationRejectionDetails', 'action' => 'final_rejection_all', 4, 21, 50), array_merge($pageLoading, array('class' => 'mybtns', 'style' => 'margin:7px auto;')))
                . '</div>';
            ?>

        </fieldset>
        <?php } ?>
    
    
        <?php
        if (!$hasAnyData)
            echo '<fieldset><legend>License Rejection</legend><p class="error-message">' . 'There is no pending form for Rejection !' . '</p></fieldset>';
        ?>

</div>
<?php //}  ?>
