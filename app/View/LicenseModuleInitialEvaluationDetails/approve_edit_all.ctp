<?php
    $title = 'Initial Evaluation Information';
    
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
                'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
                'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading); ?>

<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <?php echo $this->Form->create('LicenseModuleInitialEvaluationDetailAll'); ?>
        
        <table>
            <tr>
                <td>
                    <div style="width:780px; height:auto; padding:0; overflow-x:auto;">
                        <?php
                        if ($orgDetails == null || !is_array($orgDetails) || count($orgDetails) < 1) {
                            echo '<p class="error-message">';
                            echo 'No data is available!';
                            echo '</p>';

                            echo $this->Js->link('Back', array('controller' => 'LicenseModuleInitialEvaluationDetails', 'action' => 'view'), array_merge($pageLoading, array('class' => 'mybtns')));
                        } else {
                        ?>

                        <table class="view">
                            <tr>
                                <?php
                                if (!$this->Paginator->param('options'))
                                    echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.', array('class' => 'asc')) . "</th>";
                                else
                                    echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                                echo "<th style='width:180px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                                echo "<th style='width:100px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'Total Marks Obtained').'<br /> <strong style="color:#faf0af;">(out of '.$this->Number->precision($total_marks, 1).')</strong>'. "</th>";
                                echo "<th style='width:120px;'>Approve All <input type='checkbox' id='chkbApprovalAll'/> </th>";
                                echo "<th style='width:70px;'>Date Of Approval</th>";
                                echo "<th style='width:130px;'>Reason <br /><span style='padding:0; color:#fa8713;'>(if not approved)</span></th>";
                                echo "<th style='width:130px;'>Comment</th>";
                                ?>
                            </tr>
                            <?php 
                                $rc=-1;
                                foreach($orgDetails as $orgDetail) { 
                                    ++$rc;
                            ?>
                            <tr>
                                <td>
                                    <?php 
                                        echo $orgDetail['BasicModuleBasicInformation']['form_serial_no'];
                                        echo $this->Form->input("$rc.id", array('type'=>'hidden', 'value'=>$orgDetail['LicenseModuleInitialEvaluationDetail']['id'], 'label'=>false));    
                                        echo $this->Form->input("$rc.org_id", array('type'=>'hidden', 'value'=>$orgDetail['BasicModuleBasicInformation']['id'], 'label'=>false)); 
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        $mfiName = $orgDetail['BasicModuleBasicInformation']['short_name_of_org'];
                                        $mfiFullName = $orgDetail['BasicModuleBasicInformation']['full_name_of_org'];

                                        if (!empty($mfiName))
                                            $mfiName = "<strong>".$mfiName.":</strong> ";

                                        if (!empty($mfiFullName))
                                            $mfiName = $mfiName.$mfiFullName;        

                                        echo $mfiName;
                                    ?>
                                </td>
                                <td style="font-weight:bold; text-align:center;">
                                    <?php
                                    $obtained_marks = $orgDetail['LicenseModuleInitialAssessmentMark']['total_assessment_marks'];
                                    echo ($obtained_marks >= $pass_min_marks ? '<span style="color:#13af24;">' : '<span style="color:#fa2413;">')
                                            . $this->Number->toPercentage(($obtained_marks / $total_marks) * 100)
                                            . '</span><br/>(' . $this->Number->precision($obtained_marks, 1) . ')';
                                    ?>
                                </td>
                                <td>
                                    <div>
                                    <?php 

                                        foreach ($approval_status_options as $value => $text) {
                                            echo "<input type='radio' style='margin:2px;' name='data[LicenseModuleInitialEvaluationDetailAll][$rc][approval_status_id]' ";
                                            if($value==$orgDetail['LicenseModuleInitialEvaluationDetail']['approval_status_id']){ echo 'checked'; }
                                            echo " value='$value'/>$text<br/>";
                                        }
                                    ?>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                        echo $this->Time->format($orgDetail['LicenseModuleInitialEvaluationDetail']['approval_date'],'%d-%m-%Y','');
                                        echo $this->Form->input("$rc.approval_date", array('type'=>'hidden', 'value'=>date("Y-m-d"), 'label'=>false));
                                    ?>
                                </td>
                                <td>
                                    <?php echo $this->Form->input("$rc.reason_if_not_approved",array('type'=>'text', 'value'=>$orgDetail['LicenseModuleInitialEvaluationDetail']['reason_if_not_approved'], 'style'=>'width:120px; padding:5px;', 'escape'=>false, 'div'=>false, 'label'=>false)); ?>
                                </td>
                                <td>
                                    <?php echo $this->Form->input("$rc.comment",array('type'=>'text', 'value'=>$orgDetail['LicenseModuleInitialEvaluationDetail']['comment'], 'style'=>'width:120px; padding:5px;', 'escape'=>false, 'div'=>false, 'label'=>false)); ?>
                                </td>

                            </tr>
                            <?php  } ?>
                        </table> 

                    </div>
                </td>                
            </tr>
        </table>

        
        <?php if($orgDetails && $this->Paginator->param('pageCount')>1) { ?>
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
        <?php } ?>
        
        <div class="btns-div">                
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'LicenseModuleInitialEvaluationDetails','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
                       ?> 
                    </td>
                    <td style="text-align: center;">
                        <?php                                               
                            echo $this->Js->submit('Update All', array_merge($pageLoading, 
                                                    array('class'=>'mybtns','success'=>"msg.init('success', '$title', '$title has been update successfully.');", 
                                                          'error'=>"msg.init('error', '$title', 'Insertion failed!');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        
        <?php echo $this->Form->end(); } ?>
    </fieldset>
</div>

<script>
    $(document).ready(function() {
        $("#chkbApprovalAll").on("change", function () {
            if(this.checked) {
                $(":radio[value=1]").prop('checked', true);
                $(":radio[value=2]").prop('checked', false);
            }
            else {
                $(":radio[value=1]").prop('checked', false);
                $(":radio[value=2]").prop('checked', true);
            }
        });
    });    
</script>
