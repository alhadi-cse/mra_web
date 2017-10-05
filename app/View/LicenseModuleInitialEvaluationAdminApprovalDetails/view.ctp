<?php
    $title = 'Administrative Approval of Initial Evaluation';
    
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
                'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
                'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading); ?>

<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        
        <div class="form">
            
            <?php if(empty($org_id)) { 
                echo $this->Form->create('LicenseModuleInitialEvaluationAdminApprovalDetail'); ?>
                <table cellpadding="0" cellspacing="0" border="0">                           
                    <tr>
                        <td style="padding-left:15px; text-align:right;">Search Option</td>
                        <td>
                            <?php 
                                echo $this->Form->input('search_option', 
                                        array('label' => false, 'style'=>'width:200px',
                                            'options' => 
                                                array('BasicModuleBasicInformation.full_name_of_org' => 'Organization\'s Full Name',
                                                    'BasicModuleBasicInformation.short_name_of_org' => 'Organization\'s Short Name',
                                                    'BasicModuleBasicInformation.form_serial_no'=>'Form No.')
                                                ));
                            ?>
                        </td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('search_keyword',array('label' => false,'style'=>'width:250px')); ?></td>
                        <td style="text-align:left;">
                           <?php
                               echo $this->Js->submit('Search', array_merge($pageLoading, array('class'=>'btnsearch')));
                            ?>
                       </td>                                
                    </tr>
                </table>
            <?php echo $this->Form->end(); 
            } ?>
            
            
            <fieldset>
                <legend>Administrative Approval Completed</legend>
                
                <?php 
                    if(empty($values_approved) || !is_array($values_approved) || count($values_approved)<1) {
                        echo '<p class="error-message">';
                        echo 'No data is available!';
                        echo '</p>';
                    }
                    else { 
                        if (empty($total_marks) || $total_marks < 1) $total_marks = 100;
                ?>

                <table class="view">
                    <tr>
                        <?php 
                        
                        echo "<th style='width:70px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                        if(!$this->Paginator->param('options'))
                            echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization', array('class'=>'asc')) . "</th>";
                        else 
                            echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:90px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'Total Marks Obtained').'<br /> <strong style="color:#faf0af;">(out of '.$this->Number->precision($total_marks, 1).')</strong>'. "</th>";
                        echo "<th style='width:70px;'>" . $this->Paginator->sort('LookupLicenseApprovalStatus.approval_status', 'Status') . "</th>";
                        echo "<th style='width:70px;'>" . $this->Paginator->sort('LicenseModuleInitialEvaluationAdminApprovalDetail.approval_date', 'Approval Date') . "</th>";
                        echo "<th style='width:85px;'>Action</th>";
                        
                        ?>
                    </tr>
                    <?php foreach($values_approved as $value){ ?>
                    <tr>
                        <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['form_serial_no']; ?></td>
                        <td>
                            <?php 
                                $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                                if (!empty($mfiName))
                                    $mfiName = "<strong>".$mfiName.":</strong> ";

                                if (!empty($mfiFullName))
                                    $mfiName = $mfiName.$mfiFullName;        

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
                        <td style="text-align:center;"><?php echo $this->Time->format($value['LicenseModuleInitialEvaluationAdminApprovalDetail']['approval_date'], '%d-%m-%Y', ''); ?></td>
                        <td style="text-align:center; padding:2px; height:30px;">
                           <?php 
                                echo $this->Js->link('Re-Approve', array('controller' => 'LicenseModuleInitialEvaluationAdminApprovalDetails', 'action' => 're_approve', $value['LicenseModuleInitialEvaluationAdminApprovalDetail']['org_id']), 
                                                                array_merge($pageLoading, array('class'=>'btnlink')))
                                    . $this->Js->link('Details', array('controller' => 'LicenseModuleInitialEvaluationAdminApprovalDetails', 'action' => 'preview', $value['LicenseModuleInitialEvaluationAdminApprovalDetail']['org_id']), 
                                                                array_merge($pageLoading, array('class'=>'btnlink', 'update' => '#popup_div')));
                           ?>     
                        </td>
                    </tr>
                    <?php  } ?>
                </table>
                
                <?php
                    $user_group_id = $this->Session->read('User.GroupIds');
                    //$isAdmin = (!empty($user_group_id) && in_array(1,$user_group_id));
//                if ($isAdmin && ((!empty($values_evaluated_watch_out) && is_array($values_evaluated_watch_out) && count($values_evaluated_watch_out) > 0) 
//                    || (!empty($values_evaluated_failed) && is_array($values_evaluated_failed) && count($values_evaluated_failed) > 0))) {
//                    
                    if (!empty($user_group_id) && in_array(1,$user_group_id)) {
                        echo '<div style="text-align:center;">'
                            . $this->Js->link('Application Rejection', array('controller' => 'LicenseModuleApplicationRejectionDetails', 'action' => 'view', 6, 21, 50), array_merge($pageLoading, array('class' => 'mybtns', 'style' => 'margin:10px auto;')))
                            . '</div>';
                }
                ?>
                
                <?php  } ?>
                            
        
                <?php if(!empty($values_approved) && $this->Paginator->param('pageCount')>1) { ?>
                <div class="paginator">
                    <?php 

                    if($this->Paginator->param('pageCount')>10)
                    {
                       echo $this->Paginator->first('<<', array('class' => 'prevPg', 'title' => 'Goto first page.'), null, array('class' => 'prevPg no_link')).
                            $this->Paginator->prev('<', array('class' => 'numbers', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')).
                            $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')).
                            $this->Paginator->next('>', array('class' => 'numbers', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link')).
                            $this->Paginator->last('<<', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
                    }
                    else {
                       echo $this->Paginator->prev('<<', array('class' => 'prevPg', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')).
                            $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')).
                            $this->Paginator->next('>>', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
                    }
                  ?>
                </div>
                
                <div class="btns-div" style="padding:7px; text-align:center;">
                    <?php
                        echo $this->Js->link('Update All', array('controller' => 'LicenseModuleInitialEvaluationAdminApprovalDetails','action' => 'approve_edit_all'), array_merge($pageLoading, array('class'=>'mybtns', 'title'=>'Administrative Approval Update(From EVC)')));                            
                    ?>
                </div>
                
                <?php } ?>

            </fieldset>
            
            <fieldset>
                <legend>Administrative Approval Pending</legend>
                <?php 
                    if(empty($values_not_approved) || !is_array($values_not_approved) || count($values_not_approved) < 1) {
                        echo '<p class="error-message">';
                        echo 'No data is available !';
                        echo '</p>';
                    }
                    else {
                        if (empty($total_marks) || $total_marks < 1) $total_marks = 100;
                ?>

                <table class="view">
                    <tr>
                        <?php
                        echo "<th style='width:70px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                        if(!$this->Paginator->param('options'))
                            echo "<th style='min-width:230px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization', array('class'=>'asc')) . "</th>";
                        else 
                            echo "<th style='min-width:230px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:90px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'Total Marks Obtained').'<br /> <strong style="color:#faf0af;">(out of '.$this->Number->precision($total_marks, 1).')</strong>'. "</th>";
                        echo "<th style='width:85px;'>Action</th>";
                        ?>
                    </tr>

                    <?php 
                    foreach ($values_not_approved as $value) {
                    ?>
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
                                echo $this->Js->link('Approve', array('controller'=>'LicenseModuleInitialEvaluationAdminApprovalDetails','action'=>'approve', $value['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class'=>'btnlink')))
                                        . $this->Js->link('Reject and Notify', array('controller' => 'LicenseModuleApplicationRejectionDetails', 'action' => 'rejection', $value['BasicModuleBasicInformation']['id'], 1, '7_48_50', 1), array_merge($pageLoading, array('class' => 'btnlink')))
                                        . $this->Js->link('Details', array('controller' => 'LicenseModuleInitialEvaluationVerificationDetails','action' => 'preview', $value['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class'=>'btnlink', 'update' => '#popup_div')));
                            ?>
                        </td>
                    </tr>
                  <?php } ?>
                </table>
                
                <?php
                
                    if(count($values_not_approved) > 1 ) {
                        echo '<div class="btns-div" style="padding:7px; text-align:center;">'
                                . $this->Js->link('Approve All', array('controller' => 'LicenseModuleInitialEvaluationAdminApprovalDetails','action' => 'approve_all'), array_merge($pageLoading, array('class'=>'mybtns', 'title'=>'Initial Assessment Approve All')))
                            . '</div>';
                    }
                }
                ?>

            </fieldset>

        </div>
    </fieldset>
</div>
