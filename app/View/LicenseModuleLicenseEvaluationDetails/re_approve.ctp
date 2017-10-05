<?php   
    $title = "License Evaluation Information";    
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
?> 
<div>
    <fieldset>
        <legend><?php echo $title; ?></legend> 
        <?php                 
            echo $this->Form->create('LicenseModuleLicenseEvaluationDetail');
        ?>            
        <div class="form">
            <table cellpadding="8" cellspacing="8" border="0">                
                <tr>
                    <td>Form No.</td>
                    <td class="colons">:</td>
                    <td style="padding-left:10px">
                        <?php
                            echo $approvalDetails['BasicModuleBasicInformation']['form_serial_no'];
                            
                            echo $this->Form->input('id', array('type'=>'hidden', 'value'=>$approvalDetails['LicenseModuleLicenseEvaluationDetail']['id'], 'label'=>false));
                            echo $this->Form->input('org_id', array('type' => 'hidden', 'value' => $approvalDetails['BasicModuleBasicInformation']['id'], 'label' => false));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td style="padding-left:10px;">
                        <?php
                            $orgName = $approvalDetails['BasicModuleBasicInformation']['short_name_of_org'];
                            echo (!empty($orgName)? '<strong>' . $orgName . ': </strong>' : '') . $approvalDetails['BasicModuleBasicInformation']['full_name_of_org'];
//                            
//                            echo $this->Form->input('id', array('type'=>'hidden', 'value'=>$approvalDetails['LicenseModuleLicenseEvaluationDetail']['id'], 'label'=>false));
//                            echo $this->Form->input('org_id', array('type' => 'hidden', 'value' => $approvalDetails['BasicModuleBasicInformation']['id'], 'label' => false));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Total Marks Obtained</td>
                    <td class="colons">:</td>
                    <td style="font-weight:bold; padding-left:10px;">
                        <?php
                        $obtained_marks = $approvalDetails['LicenseModuleInitialAssessmentMark']['total_assessment_marks'];
                        echo ($obtained_marks >= $pass_min_marks ? '<span style="color:#13af24;">' : '<span style="color:#fa2413;">')
                                . $this->Number->toPercentage(($obtained_marks / $total_marks) * 100)
                                . '</span> (' . $this->Number->precision($obtained_marks, 1) . ' out of ' . $this->Number->precision($total_marks, 1) . ')';
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Administrative Approval Status</td>
                    <td class="colons">:</td>
                    <td>
                        <?php 
                        $approvalStatusId = $approvalDetails['LicenseModuleLicenseEvaluationDetail']['approval_status_id'];
                        echo $this->Form->input("approval_status_id", array('type' => 'radio', 'class' => 'approval_status', 'options' => $approval_status_options, 'default' => $approvalStatusId, 'legend' => false, 'div' => false)); 
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Date of Approval</td>
                    <td class="colons">:</td>                                
                    <td style="padding-left:10px">
                        <?php 
                            echo $this->Time->format($approvalDetails['LicenseModuleLicenseEvaluationDetail']['approval_date'],'%d-%m-%Y','');
                            echo $this->Form->input('approval_date', array('type'=>'hidden', 'value'=>date("Y-m-d"), 'label'=>false));
                        ?>
                    </td>
                </tr>
                <tr id="if_not_approved" <?php if(!empty($approvalStatusId) && $approvalStatusId == 1) echo 'style="display:none;"'; ?>>
                    <td>Reason (if not approved)</td>
                    <td class="colons">:</td>
                    <td style="padding-left:10px">
                        <?php 
                            echo $this->Form->input('reason_if_not_approved',array('type' => 'textarea', 'value'=>$approvalDetails['LicenseModuleLicenseEvaluationDetail']['reason_if_not_approved'], 'escape' => false, 'div'=>false,'label' => false)); 
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Comment</td>
                    <td class="colons">:</td>
                    <td style="padding-left:10px"><?php echo $this->Form->input('comment',array('type' => 'textarea', 'value'=>$approvalDetails['LicenseModuleLicenseEvaluationDetail']['comment'], 'escape' => false, 'div'=>false, 'label' => false)); ?></td>
                </tr>
            </table>
        </div>
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'LicenseModuleLicenseEvaluationDetails','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
                       ?> 
                    </td>
                    <td style="text-align: center;">
                        <?php                                               
                            echo $this->Js->submit('Approve', array_merge($pageLoading, 
                                                    array('success'=>"msg.init('success', '$title', '$title has been update successfully.');", 
                                                          'error'=>"msg.init('error', '$title', '$title update failed!');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div> 
     <?php  echo $this->Form->end(); ?>
    </fieldset>
</div>


<script type="text/javascript">
    
//    function CheckApprovalStatus(opt) {
//        if(opt != '1') {
//            $("#if_not_approved").show();
//            $("#if_not_approved").css("display", "");
//        }
//        else {
//            $("#if_not_approved").hide();
//        }
//    };
    
    $(document).ready(function() {
        
        $("input:radio.approval_status").click(function() {
            if($(this).attr("value") != '1') {
                $("#if_not_approved").show();
                $("#if_not_approved").css("display", "");
            }
            else {
                $("#if_not_approved").hide();
            }
        });
        
        //CheckApprovalStatus($("input:radio.approval_status").attr("value"));
    });
    
</script>
