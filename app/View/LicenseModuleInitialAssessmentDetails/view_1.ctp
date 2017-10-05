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
        $title = "Initial Assessment Information";
        $isAdmin = !empty($user_group_id) && $user_group_id==1;
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
                echo $this->Form->create('LicenseModuleInitialAssessmentDetail'); ?>
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="padding-left:15px; text-align:right;">Search by</td>
                        <td>
                            <?php
                                $options = array('BasicModuleBasicInformation.full_name_of_org'=>"Organization's Full Name",
                                                    'BasicModuleBasicInformation.short_name_of_org'=>"Organization's Short Name",
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
                                    echo $this->Js->link('View All', array('controller'=>'LicenseModuleInitialAssessmentDetails', 'action'=>'view', 'all'),
                                                array_merge($pageLoading, array('class'=>'mybtns sbtns')));
                                }
                            ?>
                        </td>
                    </tr>
                </table>
            <?php echo $this->Form->end(); 
            } ?>
            
            
            <fieldset>
                <legend>Assessment Completed</legend>
                
                <?php
                    if($values_assessed==null || !is_array($values_assessed) || count($values_assessed)<1) {
                        echo '<p class="error-message">';
                        echo 'Not yet assessed any form !';
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
                            echo "<th style='width:120px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                            echo "<th style='width:100px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'Total Marks Obtained').'<br /><strong style="color:#faf0af;">(out of '.$this->Number->precision($total_marks, 1).')</strong>'. "</th>";
                            echo "<th style='width:75px;'>Action</th>";
                        ?>
                    </tr>
                    <?php foreach($values_assessed as $value){ ?>
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
                            
                            $obtained_marks = $value['LicenseModuleInitialAssessmentMark']['total_assessment_marks'];

                            if (!empty($total_marks) && $total_marks > 0) {
                                $obtained_marks_in_percentage = ($obtained_marks / $total_marks) * 100;

                                if ($obtained_marks_in_percentage > 59)
                                    echo '<span style="color:#13af24;">';
                                else if ($obtained_marks_in_percentage > 49)
                                    echo '<span style="color:#fa8713;">';
                                else
                                    echo '<span style="color:#fa2413;">';

                                echo $this->Number->toPercentage($obtained_marks_in_percentage) 
                                        . '</span><br/>(' . $this->Number->precision($obtained_marks, 1) . ')';
                            }
                            else {
                                echo $this->Number->precision($obtained_marks, 1);
                            }
                            
                            ?>
                        </td>
                        <td style="height:30px; padding: 2px; text-align: center;">
                            <?php
                            echo $this->Js->link('Details', array('controller'=>'LicenseModuleInitialAssessmentDetails','action'=>'preview', $value['LicenseModuleInitialAssessmentDetail']['org_id']), 
                                                    array_merge($pageLoading, array('class'=>'btnlink', 'update'=>'#popup_div')));
                            ?>
                        </td>
                    </tr>
                  <?php } ?>
                </table>
                
                <?php } ?>
            </fieldset>
            
            <fieldset>
                <legend>Assessed but not Submit</legend>
                
                <?php
                    if($values_assessed_not_submit==null || !is_array($values_assessed_not_submit) || count($values_assessed_not_submit)<1)
                    {
                        echo '<p class="error-message">';
                        echo 'Not yet assessed any form !';
                        echo '</p>';
                    }
                    else{
                ?>
                <table class="view">
                    <tr>
                        <?php
                        if(!$this->Paginator->param('options'))
                            echo "<th style='min-width:200px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization', array('class'=>'asc')) . "</th>";
                        else 
                            echo "<th style='min-width:200px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                            echo "<th style='width:120px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                            echo "<th style='width:100px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'Total Marks Obtained').'<br />(out of '.$this->Number->precision($total_marks, 1).')'. "</th>";
                            echo "<th style='width:115px;'>Action</th>";
                        ?>
                    </tr>
                    <?php foreach($values_assessed_not_submit as $value){ ?>
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
                                $obtained_marks = $value['LicenseModuleInitialAssessmentMark']['total_assessment_marks'];

                                if(!empty($total_marks) && $total_marks!=0) {
                                    $obtained_marks_in_percentage = ($obtained_marks / $total_marks) * 100;

                                    if($obtained_marks_in_percentage>59)
                                        echo '<span style="color:#13af24;">';
                                    else if($obtained_marks_in_percentage>49)
                                        echo '<span style="color:#fa8713;">';
                                    else
                                        echo '<span style="color:#fa2413;">';

                                    echo $this->Number->toPercentage($obtained_marks_in_percentage).'</span><br/>('.
                                            $this->Number->precision($obtained_marks, 1).')';
                                }
                                else
                                    echo $this->Number->precision($obtained_marks, 1);
                            ?>
                        </td>
                        <td style="height:30px; padding: 2px; text-align: center;">
                            <?php
                            echo $this->Js->link('Details', array('controller'=>'LicenseModuleInitialAssessmentDetails','action'=>'preview', $value['LicenseModuleInitialAssessmentDetail']['org_id']), 
                                                    array_merge($pageLoading, array('class'=>'btnlink', 'update'=>'#popup_div')))
                                    .$this->Js->link('Submit', array('controller'=>'LicenseModuleInitialAssessmentDetails','action'=>'re_evaluate', $value['LicenseModuleInitialAssessmentDetail']['org_id']), 
                                                array_merge($pageLoading, array('class'=>'btnlink')));
                            ?>
                        </td>
                    </tr>
                  <?php } ?>
                </table>
                
                <?php } ?>                
            </fieldset>
            
            <fieldset>
                <legend>Assessment Pending</legend>
                
                <?php
                    if($values_not_evaluat==null || !is_array($values_not_evaluat) || count($values_not_evaluat)<1) {
                        echo '<p class="error-message">';
                        echo 'There is no pending evaluation form for this assessor !';
                        echo '</p>';
                    }
                    else{
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
                                echo $this->Js->link('Assess', array('controller'=>'LicenseModuleInitialAssessmentDetails','action'=>'evaluate', $value['BasicModuleBasicInformation']['id']), 
                                                    array_merge($pageLoading, array('class'=>'btnlink')));
                            ?>
                        </td>
                    </tr>
                  <?php } ?>
                </table>

                <?php } ?>                
            </fieldset>
            
        </div>
                
        <?php if($values_assessed && $this->Paginator->param('pageCount')>1) { ?>
        <div class="paginator">
          <?php
            echo $this->Paginator->prev('<<', array('class'=>'prevPg'), null, array('class'=>'prevPg no_link')).
                 $this->Paginator->numbers(array('class'=>'numbers', 'separator'=>'')).
                 $this->Paginator->next('>>', array('class'=>'nextPg'), null, array('class'=>'nextPg no_link'));
          ?>
        </div>
        <?php } ?>
        
    </fieldset>
</div>
<?php } ?>