<?php

if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}

$title = 'License Evaluation Verification';

$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

$this->Paginator->options($pageLoading);
?>

<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>

        <div class="form">

            <?php if (empty($org_id)) {
                echo $this->Form->create('LicenseModuleLicenseEvaluationVerificationDetail');
                ?>
                <table cellpadding="0" cellspacing="0" border="0">                           
                    <tr>
                        <td style="padding-left:15px; text-align:right;">Search Option</td>
                        <td>
                            <?php
                            echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:200px',
                                                        'options' => array('BasicModuleBasicInformation.full_name_of_org' => 'Organization\'s Full Name',
                                                                            'BasicModuleBasicInformation.short_name_of_org' => 'Organization\'s Short Name',
                                                                            'BasicModuleBasicInformation.form_serial_no' => 'Form No.')));
                            ?>
                        </td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('search_keyword', array('label' => false, 'style' => 'width:250px')); ?></td>
                        <td style="text-align:left;">
                            <?php
                            echo $this->Js->submit('Search', array_merge($pageLoading, array('class' => 'btnsearch')));
                            ?>
                        </td>                                
                    </tr>
                </table>
                <?php echo $this->Form->end();
            }
            ?>


            <fieldset>
                <legend>License Evaluation Verification Completed</legend>

                <?php
                if (empty($values_verified) || !is_array($values_verified) || count($values_verified) < 1) {
                    echo '<p class="error-message">';
                    echo 'No data is available !';
                    echo '</p>';
                } else {
                ?>

                <table class="view">
                    <tr>
                        <?php
                        if (!$this->Paginator->param('options'))
                            echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.', array('class' => 'asc')) . "</th>";
                        else
                            echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                        echo "<th style='width:250px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:90px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'Total Marks Obtained') . '<br /> <strong style="color:#faf0af;">(out of ' . $this->Number->precision($total_marks, 1) . ')</strong>' . "</th>";
                        echo "<th style='width:80px;'>" . $this->Paginator->sort('LookupLicenseApprovalStatus.verification_status', 'Verification Status') . "</th>";
                        echo "<th style='width:80px;'>" . $this->Paginator->sort('LicenseModuleLicenseEvaluationVerificationDetail.verification_date', 'Verification Date') . "</th>";
                        echo "<th style='width:85px;'>Action</th>";
                        ?>
                    </tr>
                    <?php foreach ($values_verified as $value) { ?>
                        <tr>
                            <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['form_serial_no']; ?></td>
                            <td>
                                <?php
                                $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                                if (!empty($mfiName))
                                    $mfiName = "<strong>" . $mfiName . ":</strong> ";

                                if (!empty($mfiFullName))
                                    $mfiName = $mfiName . $mfiFullName;

                                echo $mfiName;
                                ?>
                            </td>
                            <td style="font-weight:bold; text-align:center;">
                                <?php
                                $obtained_marks = $value['LicenseModuleInitialAssessmentMark']['total_assessment_marks'];
                                echo ($obtained_marks >= $pass_min_marks ? '<span style="color:#13af24;">' : '<span style="color:#fa2413;">')
                                . $this->Number->toPercentage(($obtained_marks / $total_marks) * 100)
                                . '</span><br/>(' . $this->Number->precision($obtained_marks, 1) . ')';
                                ?>
                            </td>
                            <td style="text-align:center;"><?php echo $value['LookupLicenseApprovalStatus']['verification_status']; ?></td>
                            <td style="text-align:center;"><?php echo $this->Time->format($value['LicenseModuleLicenseEvaluationVerificationDetail']['verification_date'], '%d-%m-%Y', ''); ?></td>
                            <td style="text-align:center; padding:2px; height:30px;">
                                <?php
                                //echo $this->Js->link('Re-Verify', array('controller' => 'LicenseModuleLicenseEvaluationVerificationDetails', 'action' => 're_verification', $value['LicenseModuleLicenseEvaluationVerificationDetail']['org_id']), array_merge($pageLoading, array('class' => 'btnlink')));
                                echo $this->Js->link('Details', array('controller' => 'LicenseModuleLicenseEvaluationVerificationDetails', 'action' => 'preview', $value['LicenseModuleLicenseEvaluationVerificationDetail']['org_id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                                ?>     
                            </td>
                        </tr>
                    <?php } ?>
                </table> 
                <?php } ?>


                <?php if (!empty($values_verified) && $this->Paginator->param('pageCount') > 1) { ?>
                <div class="paginator">
                    <?php
                    if ($this->Paginator->param('pageCount') > 10) {
                        echo $this->Paginator->first('<<', array('class' => 'prevPg', 'title' => 'Goto first page.'), null, array('class' => 'prevPg no_link')) .
                        $this->Paginator->prev('<', array('class' => 'numbers', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')) .
                        $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                        $this->Paginator->next('>', array('class' => 'numbers', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link')) .
                        $this->Paginator->last('<<', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
                    } else {
                        echo $this->Paginator->prev('<<', array('class' => 'prevPg', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')) .
                        $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                        $this->Paginator->next('>>', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
                    }
                    ?>
                </div>
                <?php } ?>

            </fieldset>
            
            <fieldset>
                <legend>License Evaluation Verification Not Approved</legend>

                <?php
                if (empty($values_not_verified) && empty($values_not_verified_by_all)) {
                    echo '<p class="error-message">';
                    echo 'No data is available !';
                    echo '</p>';
                } else {
                ?>

                <table class="view">
                    <tr>
                        <?php
                        if (!$this->Paginator->param('options'))
                            echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.', array('class' => 'asc')) . "</th>";
                        else
                            echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                        echo "<th style='width:250px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:90px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'Total Marks Obtained') . '<br /> <strong style="color:#faf0af;">(out of ' . $this->Number->precision($total_marks, 1) . ')</strong>' . "</th>";
                        echo "<th style='width:90px;'>" . $this->Paginator->sort('LookupLicenseApprovalStatus.verification_status', 'Verification Status') . "</th>";
                        echo "<th style='width:80px;'>" . $this->Paginator->sort('LicenseModuleLicenseEvaluationVerificationDetail.verification_date', 'Verification Date') . "</th>";
                        echo "<th style='width:85px;'>Action</th>";
                        ?>
                    </tr>
                    <?php if (!empty($values_not_verified)) {
                        foreach ($values_not_verified as $value) { ?>
                    <tr>
                        <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['form_serial_no']; ?></td>
                        <td>
                            <?php
                            $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                            $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                            if (!empty($mfiName))
                                $mfiName = "<strong>" . $mfiName . ":</strong> ";

                            if (!empty($mfiFullName))
                                $mfiName = $mfiName . $mfiFullName;

                            echo $mfiName;
                            ?>
                        </td>
                        <td style="font-weight:bold; text-align:center;">
                            <?php
                            $obtained_marks = $value['LicenseModuleInitialAssessmentMark']['total_assessment_marks'];
                            echo ($obtained_marks >= $pass_min_marks ? '<span style="color:#13af24;">' : '<span style="color:#fa2413;">')
                            . $this->Number->toPercentage(($obtained_marks / $total_marks) * 100)
                            . '</span><br/>(' . $this->Number->precision($obtained_marks, 1) . ')';
                            ?>
                        </td>
                        <td style="text-align:center;"><?php echo $value['LookupLicenseApprovalStatus']['verification_status']; ?></td>
                        <td style="text-align:center;"><?php echo $this->Time->format($value['LicenseModuleLicenseEvaluationVerificationDetail']['verification_date'], '%d-%m-%Y', ''); ?></td>
                        <td style="text-align:center; padding:2px; height:30px;">
                            <?php
                            if (!empty($user_is_committee_member))
                                echo $this->Js->link('Approve', array('controller' => 'LicenseModuleLicenseEvaluationVerificationDetails', 'action' => 'verification_approval', $value['LicenseModuleLicenseEvaluationVerificationDetail']['org_id']), array_merge($pageLoading, array('class' => 'btnlink')));
                            echo $this->Js->link('Details', array('controller' => 'LicenseModuleLicenseEvaluationVerificationDetails', 'action' => 'preview', $value['LicenseModuleLicenseEvaluationVerificationDetail']['org_id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                            ?>     
                        </td>
                    </tr>
                    <?php }
                    } ?>
                    
                    <?php if (!empty($values_not_verified_by_all)) {
                        foreach ($values_not_verified_by_all as $value) { ?>
                    <tr>
                        <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['form_serial_no']; ?></td>
                        <td>
                            <?php
                            $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                            $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                            if (!empty($mfiName))
                                $mfiName = "<strong>" . $mfiName . ":</strong> ";

                            if (!empty($mfiFullName))
                                $mfiName = $mfiName . $mfiFullName;

                            echo $mfiName;
                            ?>
                        </td>
                        <td style="font-weight:bold; text-align:center;">
                            <?php
                            $obtained_marks = $value['LicenseModuleInitialAssessmentMark']['total_assessment_marks'];
                            echo ($obtained_marks >= $pass_min_marks ? '<span style="color:#13af24;">' : '<span style="color:#fa2413;">')
                            . $this->Number->toPercentage(($obtained_marks / $total_marks) * 100)
                            . '</span><br/>(' . $this->Number->precision($obtained_marks, 1) . ')';
                            ?>
                        </td>
                        <td style="text-align:center;"><?php echo $value['LookupLicenseApprovalStatus']['verification_status']; ?></td>
                        <td style="text-align:center;"><?php echo $this->Time->format($value['LicenseModuleLicenseEvaluationVerificationDetail']['verification_date'], '%d-%m-%Y', ''); ?></td>
                        <td style="text-align:center; padding:2px; height:30px;">
                            <?php
                            //echo $this->Js->link('Re-verify', array('controller' => 'LicenseModuleLicenseEvaluationVerificationDetails', 'action' => 're_verification', $value['LicenseModuleLicenseEvaluationVerificationDetail']['org_id']), array_merge($pageLoading, array('class' => 'btnlink')));
                            echo $this->Js->link('Details', array('controller' => 'LicenseModuleLicenseEvaluationVerificationDetails', 'action' => 'preview', $value['LicenseModuleLicenseEvaluationVerificationDetail']['org_id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                            ?>
                        </td>
                    </tr>
                    <?php }
                        } ?>
                        
                </table> 
                <?php } ?>


                <?php if (!empty($values_not_verified) && $this->Paginator->param('pageCount') > 1) { ?>
                <div class="paginator">
                    <?php
                    if ($this->Paginator->param('pageCount') > 10) {
                        echo $this->Paginator->first('<<', array('class' => 'prevPg', 'title' => 'Goto first page.'), null, array('class' => 'prevPg no_link')) .
                        $this->Paginator->prev('<', array('class' => 'numbers', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')) .
                        $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                        $this->Paginator->next('>', array('class' => 'numbers', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link')) .
                        $this->Paginator->last('<<', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
                    } else {
                        echo $this->Paginator->prev('<<', array('class' => 'prevPg', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')) .
                        $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                        $this->Paginator->next('>>', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
                    }
                    ?>
                </div>

<!--                <div class="btns-div" style="padding:7px; text-align:center;">
                    <?php
                    //echo $this->Js->link('Update All', array('controller' => 'LicenseModuleLicenseEvaluationVerificationDetails', 'action' => 'verification_edit_all'), array_merge($pageLoading, array('class' => 'mybtns', 'title' => 'Administrative Verification Update(From EVC)')));
                    ?>
                </div>-->
                <?php } ?>

            </fieldset>

            <fieldset>
                <legend>License Evaluation Verification Pending</legend>
                <?php
                if (empty($values_pending) || !is_array($values_pending) || count($values_pending) < 1) {
                    echo '<p class="error-message">';
                    echo 'No data is available !';
                    echo '</p>';
                } else {
                    if (empty($total_marks) || $total_marks < 1)
                        $total_marks = 100;
                    ?>

                <table class="view">
                    <tr>
                        <?php
                        if (!$this->Paginator->param('options'))
                            echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.', array('class' => 'asc')) . "</th>";
                        else
                            echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                        echo "<th style='min-width:280px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:90px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'Total Marks Obtained') . '<br /> <strong style="color:#faf0af;">(out of ' . $this->Number->precision($total_marks, 1) . ')</strong>' . "</th>";
                        echo "<th style='width:90px;'>Action</th>";
                        ?>
                    </tr>

                    <?php foreach ($values_pending as $value) { ?>
                    <tr>
                        <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['form_serial_no']; ?></td>
                        <td>
                            <?php
                            $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                            $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                            if (!empty($mfiName))
                                $mfiName = "<strong>" . $mfiName . ":</strong> ";
                            if (!empty($mfiFullName))
                                $mfiName = $mfiName . $mfiFullName;

                            echo $mfiName;
                            ?>
                        </td>
                        <td style="font-weight:bold; text-align:center;">
                            <?php
                            $obtained_marks = $value['LicenseModuleInitialAssessmentMark']['total_assessment_marks'];
                            echo ($obtained_marks >= $pass_min_marks ? '<span style="color:#13af24;">' : '<span style="color:#fa2413;">')
                            . $this->Number->toPercentage(($obtained_marks / $total_marks) * 100)
                            . '</span><br/>(' . $this->Number->precision($obtained_marks, 1) . ')';
                            ?>
                        </td>
                        <td style="height:30px; padding:2px; text-align:center;"> 
                            <?php
                            echo $this->Js->link('Details', array('controller' => 'LicenseModuleInitialAssessmentDetails', 'action' => 'preview', $value['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                            //echo $this->Js->link('Re-Assess', array('controller' => 'LicenseModuleInitialAssessmentDetails', 'action' => 're_assess', $value['BasicModuleBasicInformation']['id'], 1, 1), array_merge($pageLoading, array('class' => 'btnlink')));
                            if (!empty($user_is_committee_member))
                                echo $this->Js->link('Verification', array('controller' => 'LicenseModuleLicenseEvaluationVerificationDetails', 'action' => 'verification', $value['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class' => 'btnlink')));
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                </table>

                <?php
                if (count($values_pending) > 1 && !empty($user_is_committee_member)) {
                    echo '<div class="btns-div" style="padding:7px; text-align:center;">'
                    . $this->Js->link('Verify All', array('controller' => 'LicenseModuleLicenseEvaluationVerificationDetails', 'action' => 'verification_all'), array_merge($pageLoading, array('class' => 'mybtns', 'title' => 'License Evaluation Verify All')))
                    . '</div>';
                }
            }
            ?>
            </fieldset>

        </div>
    </fieldset>
</div>
