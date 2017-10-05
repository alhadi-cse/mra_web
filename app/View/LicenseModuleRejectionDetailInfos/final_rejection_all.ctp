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
        $title = 'Final Rejection';
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
                    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
                    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

        $this->Paginator->options($pageLoading);
?>

<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        
        <?php
        echo $this->Form->create('LicenseModuleApplicationFinalRejectAll');
        $rc=-1;
        
        if (!empty($values_not_appealed) && is_array($values_not_appealed) && count($values_not_appealed) > 0) {
            
        ?>
        
        <fieldset>
            <legend>Rejected and Not Appealed</legend>

            <table class="view">
                <tr>
                    <?php

                        echo "<th style='width:120px;'>"
                            . (!$this->Paginator->param('options')? $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.', array('class'=>'asc'))
                                :$this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.'))
                            . (count($values_not_appealed) > 1 ? " <br /><span style='padding:0; color:#fa8713;'>Select All <input type='checkbox' id='chkbSelectAllRNA'/></span>" : "")
                            . "</th>";

                        echo "<th style='min-width:150px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:100px;'>" . $this->Paginator->sort('LicenseModuleStateHistory.date_of_deadline', 'Date of Deadline') . "</th>";
                        echo "<th style='width:130px;'>Reason" 
                                .(count($values_not_appealed) > 1 ? " <br /><span style='padding:0; color:#fa8713;'>Same For All <input type='checkbox' id='chkbForAllRNA'/></span>" : "")
                                ."</th>";
                        echo "<th style='width:70px;'>Action</th>";
                    ?>
                </tr>
                <?php
                    foreach($values_not_appealed as $orgDetail) { 
                        ++$rc;
                ?>
                <tr>
                    <td style="text-align:center;">
                        <?php 
                            echo $this->Form->input("$rc.org_id", array('type'=>'checkbox', 'class' => 'orgIdsRNA', 'value'=>$orgDetail['BasicModuleBasicInformation']['id'], 'label'=>$orgDetail['BasicModuleBasicInformation']['form_serial_no'])); 
                            //echo $orgDetail['BasicModuleBasicInformation']['form_serial_no']; 
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
                    <td style="font-weight:bold; text-align:center; color:#fa2413;">
                        <?php echo $orgDetail['LicenseModuleStateHistory']['date_of_deadline']; ?>
                    </td>
                    <td>
                        <?php echo $this->Form->input("$rc.rejection_option_id", array('type' => 'select', 'class' => 'rejOptionRNA', 'style' => 'width:150px', 'options' => $rejection_options, 'empty' => '-----Select-----', 'label' => false)); ?>
                    </td>

                    <td style="height:30px; padding:2px; text-align:center;">
                        <?php 
                            echo $this->Js->link('Details', array('controller'=>'LicenseModuleEvaluationDetailInfos','action'=>'preview', $orgDetail['BasicModuleBasicInformation']['id']), 
                                                            array_merge($pageLoading, array('class'=>'btnlink', 'update'=>'#popup_div')));
                        ?>
                    </td>
                </tr>
                <?php } ?>
            </table>
            
        </fieldset>

        <?php
        }
            
        if (!empty($values_appeal_but_failed) && is_array($values_appeal_but_failed) && count($values_appeal_but_failed) > 0) {
        ?>
        <fieldset>
            <legend>Appeal but Fail again</legend>

            <table class="view">
                <tr>
                    <?php

                        echo "<th style='width:120px;'>"
                            . (!$this->Paginator->param('options')? $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.', array('class'=>'asc'))
                                :$this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.'))
                            . (count($values_appeal_but_failed) > 1 ? " <br /><span style='padding:0; color:#fa8713;'>Select All <input type='checkbox' id='chkbSelectAllABF'/></span>" : "")
                            . "</th>";

                        echo "<th style='min-width:150px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:100px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'Total Marks Obtained').'<br /> <strong style="color:#faf0af;">(out of '.$this->Number->precision($total_marks, 1).')</strong>'. "</th>";
                        echo "<th style='width:130px;'>Reason" 
                                .(count($values_appeal_but_failed) > 1 ? " <br /><span style='padding:0; color:#fa8713;'>Same For All <input type='checkbox' id='chkbForAllABF'/></span>" : "")
                                ."</th>";
                        echo "<th style='width:70px;'>Action</th>";
                    ?>
                </tr>
                <?php
                    foreach($values_appeal_but_failed as $orgDetail) { 
                        ++$rc;
                ?>
                <tr>
                    <td style="text-align:center;">
                        <?php 
                            echo $this->Form->input("$rc.org_id", array('type'=>'checkbox', 'class' => 'orgIdsABF', 'value'=>$orgDetail['BasicModuleBasicInformation']['id'], 'label'=>$orgDetail['BasicModuleBasicInformation']['form_serial_no'])); 
                            //echo $orgDetail['BasicModuleBasicInformation']['form_serial_no']; 
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
                        $obtained_marks = $orgDetail['LicenseModuleEvaluationDetailInfo']['total_assessment_marks'];

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
                    <td>
                        <?php echo $this->Form->input("$rc.rejection_option_id", array('type' => 'select', 'class' => 'rejOptionABF', 'style' => 'width:150px', 'options' => $rejection_options, 'empty' => '-----Select-----', 'label' => false)); ?>
                    </td>

                    <td style="height:30px; padding:2px; text-align:center;">
                        <?php 
                            echo $this->Js->link('Details', array('controller'=>'LicenseModuleEvaluationDetailInfos','action'=>'preview', $orgDetail['BasicModuleBasicInformation']['id']), 
                                                            array_merge($pageLoading, array('class'=>'btnlink', 'update'=>'#popup_div')));
                        ?>
                    </td>
                </tr>
                <?php } ?>
            </table>
            
        </fieldset>
        
        <?php } ?>
                
        <div class="btns-div">
            <table style="margin:0 auto; padding:0;" cellspacing="7">
                <tr>
                    <td style="text-align:right;">
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'LicenseModuleRejectionDetailInfos', 'action' => 'view', 4, 21, 50), array_merge($pageLoading, array('class' => 'mybtns')));
                        ?> 
                    </td>
                    <td style="text-align:center;">
                        <?php                                               
                            echo $this->Js->submit('Reject and Notify to selected MFI', array_merge($pageLoading, 
                                                    array('class'=>'mybtns', 'confirm'=>'Are you sure to Final Reject all the selected MFI ?', 
                                                            'success'=>"msg.init('success', '$title', '$title has been successfully completed.');",
                                                            'error'=>"msg.init('error', '$title', '$title failed !');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>


<script>
    $(document).ready(function() {
        
        $("#chkbSelectAllRNA").on("change", function () {
            if($(".orgIdsRNA").length > 1) {
                $(".orgIdsRNA").prop('checked', this.checked);
            }
        });
        $("#chkbForAllRNA").on("change", function () {
            if($(".rejOptionRNA").length > 1) {
                var selVal = $(".rejOptionRNA")[0].value;
                if(this.checked) {
                    $(".rejOptionRNA").prop('value', selVal);
                    $(".rejOptionRNA").on("change", function () {
                        if($("#chkbForAllRNA").prop('checked') && $(".rejOptionRNA")[0].id === this.id) {
                            $(".rejOptionRNA").prop('value', $(".rejOptionRNA")[0].value);
                        }
                    });
                }
                else {
                    $(".rejOptionRNA").prop('value', '');
                    $(".rejOptionRNA")[0].value = selVal;
                    $(".rejOptionRNA").off("change");
                }
            }
        });
        
        
        $("#chkbSelectAllABF").on("change", function () {
            if($(".orgIdsABF").length > 1) {
                $(".orgIdsABF").prop('checked', this.checked);
            }
        });
        $("#chkbForAllABF").on("change", function () {
            if($(".rejOptionABF").length > 1) {
                var selVal = $(".rejOptionABF")[0].value;
                if(this.checked) {
                    $(".rejOptionABF").prop('value', selVal);                    
                    $(".rejOptionABF").on("change", function () {
                        if($("#chkbForAllABF").prop('checked') && $(".rejOptionABF")[0].id === this.id) {
                            $(".rejOptionABF").prop('value', $(".rejOptionABF")[0].value);
                        }
                    });
                }
                else {
                    $(".rejOptionABF").prop('value', '');
                    $(".rejOptionABF")[0].value = selVal;
                    $(".rejOptionABF").off("change");
                }
            }
        });
        
    });
</script>

<?php } ?>