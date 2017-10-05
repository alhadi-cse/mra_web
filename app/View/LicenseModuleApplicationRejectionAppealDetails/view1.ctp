<?php
    if(isset($msg) && !empty($msg)) {
        if(is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init('.json_encode($msg).');', array('inline'=>true));
        }
        else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline'=>true));
        }
    }
    else {
        $title = "Initial Evaluation Information";
        $next_state_id = 21;
        $isAdmin = !empty($user_group_id) && in_array(1,$user_group_id);
        $pageLoading = array('update'=>'#ajax_div', 'evalScripts'=>true,
                'before'=>$this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer'=>false)),
                'complete'=>$this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer'=>false)));
        $this->Paginator->options($pageLoading);
?>

<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <div class="form">
            
            <?php if(empty($org_id)) { 
                echo $this->Form->create('LicenseModuleEvaluationDetailInfo'); ?>
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="padding-left:15px; text-align:right;">Search by</td>
                        <td>
                            <?php
                                $options = array('BasicModuleBasicInformation.full_name_of_org'=>'Organization\'s Full Name',
                                                    'BasicModuleBasicInformation.short_name_of_org'=>'Organization\'s Short Name',
                                                    'BasicModuleBasicInformation.form_serial_no'=>'Form No.');
                                echo $this->Form->input('search_option', array('label'=>false, 'style'=>'width:215px', 'options'=>$options));
                            ?>
                        </td>
                        <td style="font-weight:bold;">:</td>
                        <td><?php echo $this->Form->input('search_keyword',array('label'=>false,'style'=>'width:250px')); ?></td>
                        <td style="padding-right:5px"><?php echo $this->Js->submit('Search', array_merge($pageLoading, array('class'=>'btnsearch'))); ?></td>
                        <td>
                            <?php
                                if(!empty($opt_all) && $opt_all) {
                                    echo $this->Js->link('View All', array('controller'=>'LicenseModuleEvaluationDetailInfos', 'action'=>'view', 'all'),
                                                array_merge($pageLoading, array('class'=>'mybtns sbtns')));
                                }
                            ?>
                        </td>
                    </tr>
                </table>
            <?php echo $this->Form->end(); 
            } ?>
            
            <?php
                if ((!empty($values_evaluated_passed) && is_array($values_evaluated_passed) && count($values_evaluated_passed) > 0)
                        || (!empty($values_evaluated_watch_out) && is_array($values_evaluated_watch_out) && count($values_evaluated_watch_out) > 0)
                        || (!empty($values_evaluated_failed) && is_array($values_evaluated_failed) && count($values_evaluated_failed) > 0)) {
            ?>
            <fieldset>
                <legend>Evaluation Completed</legend>                
                <?php 
                if (empty($total_marks) || $total_marks < 1) $total_marks = 1;
                
                if(!empty($values_evaluated_passed) && is_array($values_evaluated_passed) && count($values_evaluated_passed) > 0) {
                ?>
                <fieldset>
                    <legend style="padding:5px 15px; font-size:8.75pt;">Passed</legend> 
                    <table class="view">
                        <tr>
                            <?php
                            if(!$this->Paginator->param('options'))
                                echo "<th style='min-width:230px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization', array('class'=>'asc')) . "</th>";
                            else 
                                echo "<th style='min-width:230px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                                echo "<th style='width:120px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                                echo "<th style='width:100px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'Total Marks Obtained').'<br /> <strong style="color:#faf0af;">(out of '.$this->Number->precision($total_marks, 1).')</strong>'. "</th>";
                                echo "<th style='width:75px;'>Action</th>";
                            ?>
                        </tr>
                        
                        <?php 
                        foreach ($values_evaluated_passed as $value) {
                        ?>
                        <tr>
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
                            <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['form_serial_no']; ?></td>
                            <td style="font-weight:bold; text-align:center;">
                                <?php
                                $obtained_marks = $value['LicenseModuleEvaluationDetailInfo']['total_assessment_marks'];
                                echo '<span style="color:#13af24;">' 
                                        . $this->Number->toPercentage(($obtained_marks / $total_marks) * 100) 
                                        . '</span><br/>(' . $this->Number->precision($obtained_marks, 1) . ')';
                                ?>
                            </td>
                            <td style="height:30px; padding: 2px; text-align: center;">
                                <?php
                                echo $this->Js->link('Details', array('controller'=>'LicenseModuleEvaluationDetailInfos','action'=>'preview', $value['BasicModuleBasicInformation']['id']), 
                                                        array_merge($pageLoading, array('class'=>'btnlink', 'update'=>'#popup_div')));
                                ?>
                            </td>
                        </tr>
                      <?php } ?>
                    </table>
                </fieldset>
                <?php } ?>
                
                <?php
                if (!empty($values_evaluated_watch_out) && is_array($values_evaluated_watch_out) && count($values_evaluated_watch_out) > 0) {
                ?>
                <fieldset>
                    <legend style="padding:5px 15px; font-size:8.75pt;">Watch-Out</legend> 
                    <table class="view">
                        <tr>
                            <?php
                            if(!$this->Paginator->param('options'))
                                echo "<th style='min-width:230px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization', array('class'=>'asc')) . "</th>";
                            else 
                                echo "<th style='min-width:230px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                                echo "<th style='width:120px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                                echo "<th style='width:100px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'Total Marks Obtained').'<br /> <strong style="color:#faf0af;">(out of '.$this->Number->precision($total_marks, 1).')</strong>'. "</th>";
                                echo "<th style='width:75px;'>Action</th>";
                            ?>
                        </tr>
                        <?php foreach($values_evaluated_watch_out as $value){ ?>
                        <tr>
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
                            <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['form_serial_no']; ?></td>
                            <td style="font-weight:bold; text-align:center;">
                                <?php
                                $obtained_marks = $value['LicenseModuleEvaluationDetailInfo']['total_assessment_marks'];
                                echo '<span style="color:#faaf13;">' 
                                        . $this->Number->toPercentage(($obtained_marks / $total_marks) * 100) 
                                        . '</span><br/>(' . $this->Number->precision($obtained_marks, 1) . ')';
                                ?>
                            </td>
                            <td style="height:30px; padding: 2px; text-align: center;">
                                <?php
                                
                                if($isAdmin)
                                    echo $this->Js->link('Re-Submit', array('controller'=>'LicenseModuleEvaluationDetailInfos','action'=>'re_evaluate', $value['BasicModuleBasicInformation']['id']), 
                                                array_merge($pageLoading, array('class'=>'btnlink')));
                                
                                echo $this->Js->link('Details', array('controller'=>'LicenseModuleEvaluationDetailInfos','action'=>'preview', $value['BasicModuleBasicInformation']['id']), 
                                                        array_merge($pageLoading, array('class'=>'btnlink', 'update'=>'#popup_div')));
                                ?>
                            </td>
                        </tr>
                      <?php } ?>
                    </table>
                </fieldset>
                <?php } ?>
                
                <?php
                if (!empty($values_evaluated_failed) && is_array($values_evaluated_failed) && count($values_evaluated_failed) > 0) {
                ?>
                <fieldset>
                    <legend style="padding:5px 15px; font-size:8.75pt;">Failed</legend> 
                    <table class="view">
                        <tr>
                            <?php
                            if(!$this->Paginator->param('options'))
                                echo "<th style='min-width:230px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization', array('class'=>'asc')) . "</th>";
                            else 
                                echo "<th style='min-width:230px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                                echo "<th style='width:120px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                                echo "<th style='width:100px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'Total Marks Obtained').'<br /> <strong style="color:#faf0af;">(out of '.$this->Number->precision($total_marks, 1).')</strong>'. "</th>";
                                echo "<th style='width:75px;'>Action</th>";
                            ?>
                        </tr>
                        <?php foreach($values_evaluated_failed as $value){ ?>
                        <tr>
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
                            <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['form_serial_no']; ?></td>
                            <td style="font-weight:bold; text-align:center;">
                                <?php
                                $obtained_marks = $value['LicenseModuleEvaluationDetailInfo']['total_assessment_marks'];
                                echo '<span style="color:#fa2413;">' 
                                        . $this->Number->toPercentage(($obtained_marks / $total_marks) * 100) 
                                        . '</span><br/>(' . $this->Number->precision($obtained_marks, 1) . ')';
                                ?>
                            </td>
                            <td style="height:30px; padding: 2px; text-align: center;">
                                <?php
                                echo $this->Js->link('Details', array('controller'=>'LicenseModuleEvaluationDetailInfos','action'=>'preview', $value['BasicModuleBasicInformation']['id']), 
                                                        array_merge($pageLoading, array('class'=>'btnlink', 'update'=>'#popup_div')));
                                ?>
                            </td>
                        </tr>
                      <?php } ?>
                    </table>
                </fieldset>
                <?php } ?>
                
                <?php
                if ($isAdmin && ((!empty($values_evaluated_watch_out) && is_array($values_evaluated_watch_out) && count($values_evaluated_watch_out) > 0) 
                    || (!empty($values_evaluated_failed) && is_array($values_evaluated_failed) && count($values_evaluated_failed) > 0))) {
                    
                    echo '<div style="text-align:center;">'
                            . $this->Js->link('Application Rejection', array('controller' => 'LicenseModuleEvaluationDetailInfos', 'action' => 'reject_all'), array_merge($pageLoading, array('class' => 'mybtns', 'style' => 'margin:10px auto;')))
                            . '</div>';
                }
                ?>
                
            </fieldset>
            <?php } ?>
            
            <fieldset>
                <legend>Evaluated but not Submit</legend>
                
                <?php
                if (empty($values_evaluated_not_submit) || !is_array($values_evaluated_not_submit) || count($values_evaluated_not_submit) < 1) {
                    echo '<p class="error-message">';
                    echo 'There is no pending evaluation form for submit !';
                    echo '</p>';
                } else {
                ?>
                <table class="view">
                    <tr>
                        <?php
                        if(!$this->Paginator->param('options'))
                            echo "<th style='min-width:200px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization', array('class'=>'asc')) . "</th>";
                        else 
                            echo "<th style='min-width:200px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                            echo "<th style='width:120px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                            echo "<th style='width:100px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'Total Marks Obtained').'<br /> <strong style="color:#faf0af;">(out of '.$this->Number->precision($total_marks, 1).')</strong>'. "</th>";
                            echo "<th style='width:115px;'>Action</th>";
                        ?>
                    </tr>
                    <?php foreach($values_evaluated_not_submit as $value){ ?>
                    <tr>
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
                        <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['form_serial_no']; ?></td>
                        <td style="font-weight:bold; text-align:center;">
                            <?php
                                $obtained_marks = $value['LicenseModuleEvaluationDetailInfo']['total_assessment_marks'];
                                
                                if(!empty($total_marks) && $total_marks!=0 && !empty($assessment_marks)) {
                                    if($obtained_marks>=$assessment_marks['pass_min_marks'])
                                        echo '<span style="color:#13af24;">';
                                    else if($obtained_marks>=$assessment_marks['watchOut_min_marks'])
                                        echo '<span style="color:#faaf13;">';
                                    else
                                        echo '<span style="color:#fa2413;">';

                                    echo $this->Number->toPercentage(($obtained_marks / $total_marks) * 100).'</span><br/>('.
                                            $this->Number->precision($obtained_marks, 1).')';
                                }
                                else
                                    echo $this->Number->precision($obtained_marks, 1);
                            ?>
                        </td>
                        <td style="height:30px; padding:2px; text-align:center;">
                            <?php
                            echo $this->Js->link('Details', array('controller'=>'LicenseModuleEvaluationDetailInfos','action'=>'preview', $value['BasicModuleBasicInformation']['id']), 
                                                    array_merge($pageLoading, array('class'=>'btnlink', 'update'=>'#popup_div')))
                                    .$this->Js->link('Submit', array('controller'=>'LicenseModuleEvaluationDetailInfos','action'=>'re_evaluate', $value['BasicModuleBasicInformation']['id']), 
                                                array_merge($pageLoading, array('class'=>'btnlink')));
                            ?>
                        </td>
                    </tr>
                  <?php } ?>
                </table>
                
                <?php } ?>                
            </fieldset>
            
            <fieldset>
                <legend>Evaluation Pending</legend>
                
                <?php
                    if(empty($values_not_evaluat) || !is_array($values_not_evaluat) || count($values_not_evaluat) < 1) {
                        echo '<p class="error-message">';
                        echo 'There is no pending evaluation form for this assessor !';
                        echo '</p>';
                    }
                    else {
                ?>
                <table class="view">
                    <tr>
                        <?php
                        if(!$this->Paginator->param('options'))
                            echo "<th style='min-width:230px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization', array('class'=>'asc')) . "</th>";
                        else 
                            echo "<th style='min-width:230px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                            echo "<th style='width:150px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                            echo "<th style='width:75px;'>Action</th>";
                        ?>
                    </tr>
                    <?php foreach($values_not_evaluat as $value){ ?>
                    <tr>
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
                        <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['form_serial_no']; ?></td>
                        <td style="height:30px; padding:2px; text-align:center;">
                            <?php
                                echo $this->Js->link('Evaluate', array('controller'=>'LicenseModuleEvaluationDetailInfos','action'=>'evaluate', $value['BasicModuleBasicInformation']['id']), 
                                                    array_merge($pageLoading, array('class'=>'btnlink')));
                            ?>
                        </td>
                    </tr>
                  <?php } ?>
                </table>

                <?php } ?>                
            </fieldset>
            
        </div>
        
        
        <?php //if($values_evaluated && $this->Paginator->param('pageCount')>1) { ?>
<!--        <div class="paginator">-->
          <?php
//            echo $this->Paginator->prev('<<', array('class'=>'prevPg'), null, array('class'=>'prevPg no_link')).
//                 $this->Paginator->numbers(array('class'=>'numbers', 'separator'=>'')).
//                 $this->Paginator->next('>>', array('class'=>'nextPg'), null, array('class'=>'nextPg no_link'));
//          ?>
<!--        </div>-->
        <?php //} ?>
        
    </fieldset>
</div>
<?php } ?>