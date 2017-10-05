<?php
    if (!empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }

    $title = 'License Permission Information';

    $user_group_id = $this->Session->read('User.GroupIds');
    $isAdmin = (!empty($user_group_id) && in_array(1,$user_group_id));

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
                echo $this->Form->create('LicenseModuleLicensePermissionDetail');
                ?>
                <table cellpadding="0" cellspacing="0" border="0">                           
                    <tr>
                        <td style="padding-left:15px; text-align:right;">Search Option</td>
                        <td>
                            <?php
                            echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:200px',
                                'options' =>
                                array('BasicModuleBasicInformation.full_name_of_org' => 'Organization\'s Full Name',
                                    'BasicModuleBasicInformation.short_name_of_org' => 'Organization\'s Short Name',
                                    'BasicModuleBasicInformation.form_serial_no' => 'Form No.')
                            ));
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
                <legend>License Issue</legend>

                <?php
                if ($values_licensed == null || !is_array($values_licensed) || count($values_licensed) < 1) {
                    echo '<p class="error-message">No data is available !</p>';
                } else {
                ?>

                    <table class="view">
                        <tr>
                            <?php
                            if (!$this->Paginator->param('options'))
                                echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.', array('class' => 'asc')) . "</th>";
                            else
                                echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                            echo "<th style='min-width:200px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                            echo "<th style='width:90px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'Total Marks Obtained') . '<br /> <strong style="color:#faf0af;">(out of ' . $this->Number->precision($total_marks, 1) . ')</strong>' . "</th>";
                            echo "<th style='width:120px;'>License No.</th>";
                            echo "<th style='width:80px;'>Date of License</th>";
                            echo "<th style='width:85px;'>Action</th>";
                            ?>
                        </tr>
                        
                        <?php foreach ($values_licensed as $value) { ?>
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
                            <td>
                                <?php  echo $value['BasicModuleBasicInformation']['license_no']; ?>
                            </td>
                            <td>
                                <?php
                                if(!empty($value['BasicModuleBasicInformation']['license_issue_date']))
                                    echo date("d-m-Y", strtotime($value['BasicModuleBasicInformation']['license_issue_date']));
                                ?>
                            </td>
                            <td style="height:30px; padding:2px; text-align:center;"> 
                                <?php
                                echo $this->Js->link('Pre. Details', array('controller' => 'LicenseModuleLicenseEvaluationAdminApprovalDetails', 'action' => 'preview', $value['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                                
                                //echo $this->Js->link('Pre. Details', array('controller' => 'LicenseModuleLicenseEvaluationAdminApprovalDetails', 'action' => 'preview', $value['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                                ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                <?php } ?>
                
                <?php if ($this->Paginator->param('pageCount') > 1) { ?>
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
                <legend>Selected for License</legend>

                <?php
                if ($values_selected == null || !is_array($values_selected) || count($values_selected) < 1) {
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
                        echo "<th style='min-width:230px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:90px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'Total Marks Obtained') . '<br /> <strong style="color:#faf0af;">(out of ' . $this->Number->precision($total_marks, 1) . ')</strong>' . "</th>";
                        echo "<th style='width:70px;'>" . $this->Paginator->sort('LookupLicenseApprovalStatus.approval_status', 'Administrative Approval Status') . "</th>";
                        echo "<th style='width:70px;'>" . $this->Paginator->sort('LicenseModuleLicenseEvaluationAdminApprovalDetail.approval_date', 'Approval Date') . "</th>";
                        echo "<th style='width:82px;'>Action</th>";
                        ?>
                    </tr>
                    <?php foreach ($values_selected as $value) { ?>
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
                        <td style="text-align:center;"><?php echo $value['LookupLicenseApprovalStatus']['approval_status']; ?></td>                        
                        <td style="text-align:center;"><?php echo $this->Time->format($value['LicenseModuleLicenseEvaluationAdminApprovalDetail']['approval_date'], '%d-%m-%Y', ''); ?></td>
                        <td style="text-align:center; padding:2px; height:30px;">
                            <?php
                            if ($isAdmin)
                                echo $this->Js->link('License Issue', array('controller' => 'LicenseModuleLicensePermissionDetails', 'action' => 'license_issue', $value['BasicModuleBasicInformation']['id'], $this_state_ids), 
                                                                    array_merge($pageLoading, array('class' => 'btnlink', 'title' => 'Send notification with terms and conditions.')));//, 'confirm' => 'Are you sure to send notification with terms and conditions ?'

                            echo $this->Js->link('Pre. Details', array('controller' => 'LicenseModuleLicenseEvaluationAdminApprovalDetails', 'action' => 'preview', $value['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                            ?>     
                        </td>
                    </tr>
                    <?php } ?>
                </table>
                <?php } ?>


                <?php if ($values_selected && $this->Paginator->param('pageCount') > 1) { ?>
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

<!--                    <div class="btns-div" style="padding:7px; text-align:center;">
                        <?php
                        //echo $this->Js->link('Notify All', array('controller' => 'LicenseModuleLicenseEvaluationAdminApprovalDetails', 'action' => 'approve_edit_all'), array_merge($pageLoading, array('class' => 'mybtns', 'title' => 'Send notification to all the organization.')));
                        ?>
                    </div>-->

                    <?php } ?>

            </fieldset>

        </div>
    </fieldset>
</div>
