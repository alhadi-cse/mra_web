<?php
    $title = 'License Evaluation Verification';
    
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
                'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
                'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading); ?>

<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <?php echo $this->Form->create('LicenseModuleLicenseEvaluationVerificationDetailAll'); ?>
        
        <div style="width:780px; height:auto; padding:0; overflow-x:auto;">
            <?php 
                if($orgDetails==null || !is_array($orgDetails) || count($orgDetails)<1) {
                    echo '<p class="error-message">';
                    echo 'No data is available!';
                    echo '</p>';

                    echo $this->Js->link('Back', array('controller' => 'LicenseModuleLicenseEvaluationVerificationDetails','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));
                }
                else {
                    if (empty($total_marks) || $total_marks < 1) $total_marks = 100;
            ?>

            <table class="view" style="min-width:1000px;">
                <tr>
                    <?php 
                    if(!$this->Paginator->param('options'))
                        echo "<th style='width:75px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.', array('class'=>'asc')) . "</th>";
                    else 
                        echo "<th style='width:75px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                    echo "<th style='width:180px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                    echo "<th style='width:100px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'Total Marks Obtained').'<br /> <strong style="color:#faf0af;">(out of '.$this->Number->precision($total_marks, 1).')</strong>'. "</th>";
                    echo "<th style='width:125px;'>Verify All <input type='checkbox' id='chkbVerificationAll'/> </th>";
                    echo "<th style='width:130px;'>Reason <br /><span style='padding:0; color:#fa8713;'>(if not approved)</span></th>";
                    echo "<th style='width:130px;'>Comment</th>";
                    echo "<th style='width:85px;'>Action</th>";
                    ?>
                </tr>
                <?php
                    $rc=-1;
                    foreach($orgDetails as $orgDetail) { 
                        ++$rc;
                ?>
                <tr>
                    <td style="text-align:center;">
                        <?php 
                        echo $orgDetail['BasicModuleBasicInformation']['form_serial_no']
                                . $this->Form->input("$rc.org_id", array('type'=>'hidden', 'value'=>$orgDetail['BasicModuleBasicInformation']['id'], 'label'=>false)); 
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
                        <?php 

                            foreach ($verification_status_options as $value => $text) {
                                echo "<input type='radio' style='margin:2px;' name='data[LicenseModuleLicenseEvaluationVerificationDetailAll][$rc][verification_status_id]' ";
                                if (strpos($text, 'Not') !== false) { echo 'checked'; } //if($value=='2'){ echo 'checked'; }
                                echo " value='$value'/>$text<br/>";
                            }

                            echo $this->Form->input("$rc.verification_date", array('type'=>'hidden', 'value'=>date("Y-m-d"), 'label'=>false));
                        ?>                                    
                    </td>
                    <td>
                        <?php echo $this->Form->input("$rc.reason_if_not_approved",array('type'=>'text', 'style'=>'width:125px; padding:5px;', 'escape'=>false, 'div'=>false, 'label'=>false)); ?>
                    </td>
                    <td>
                        <?php echo $this->Form->input("$rc.verification_comment",array('type'=>'text', 'style'=>'width:125px; padding:5px;', 'escape'=>false, 'div'=>false, 'label'=>false)); ?>
                    </td>
                    <td style="height:30px; padding:2px; text-align:center;"> 
                        <?php 
                            echo $this->Js->link('Pre. Details', array('controller' => 'LicenseModuleFieldInspectionDetails','action' => 'preview', $orgDetail['BasicModuleBasicInformation']['id']), 
                                                            array_merge($pageLoading, array('class'=>'btnlink', 'update' => '#popup_div')));
                        ?>
                    </td>

                </tr>
                <?php } ?>
            </table> 

        </div>
        
        <div class="btns-div">                
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'LicenseModuleLicenseEvaluationVerificationDetails', 'action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
                       ?> 
                    </td>
                    <td style="text-align:center;">
                        <?php                                               
                            echo $this->Js->submit('Verify All', array_merge($pageLoading, 
                                                    array('class'=>'mybtns', 'confirm'=>'Are you sure to verify all ?',
                                                            'success'=>"msg.init('success', '$title', '$title has been added successfully.');", 
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
        $("#chkbVerificationAll").on("change", function () {
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
