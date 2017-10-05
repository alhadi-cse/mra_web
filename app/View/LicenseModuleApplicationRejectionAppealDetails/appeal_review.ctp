<?php

    if(isset($msg) && !empty($msg)) {
        if(is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init('.json_encode($msg).');', array('inline'=>true));
        }
        else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline'=>true));
        }
    }
    
    $title = 'Appeal Review of Application Rejection';
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
                'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
                'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading);
        
?>

<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>

        <?php
        if (!empty($orgDetail) && is_array($orgDetail)) {
            echo $this->Form->create('LicenseModuleApplicationRejectionAppealDetailReview');
        ?>

        <div class="form">
            <table cellpadding="5" cellspacing="5" border="0" style="width:85%;">

                <tr>
                    <td style="width:160px;">Form No.</td>
                    <td class="colons">:</td>
                    <td>
                        <?php
                            echo '<span style="float:left; margin-top:7px; font-weight:bold;">' . $orgDetail['BasicModuleBasicInformation']['form_serial_no'] . '</span>';
                            echo $this->Form->input("id", array('type' => 'hidden', 'value' => $orgDetail['LicenseModuleApplicationRejectionAppealDetail']['id'], 'label' => false));
                            
                            echo $this->Js->link('Rejection History', array('controller' => 'LicenseModuleApplicationRejectionDetails', 'action' => 'rejection_history_preview', $org_id),
                                    array_merge($pageLoading, array('class' => 'btnlink', 'style' => 'float:right; display:inline-block;', 'update' => '#popup_div')));
                        
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td>
                        <?php
                        $mfiName = $orgDetail['BasicModuleBasicInformation']['short_name_of_org'];
                        $mfiFullName = $orgDetail['BasicModuleBasicInformation']['full_name_of_org'];
                        
                        echo $mfiFullName . ((!empty($mfiFullName) && !empty($mfiName)) ? " (<strong>" . $mfiName . "</strong>)" : $mfiName);
                        ?>
                    </td>
                </tr>

<!--                <tr>
                    <td>Rejection Histories</td>
                    <td class="colons">:</td>
                    <td>
                        <?php //echo $this->requestAction(array('controller' => 'LicenseModuleApplicationRejectionDetails', 'action'=>'rejection_history_details', $org_id), array('return')); ?>
                    </td>
                </tr>-->
                
                <tr id="if_approved">
                    <td>Date of Appeal</td>
                    <td class="colons">:</td>
                    <td>
                        <?php 
                            echo $this->Time->format($orgDetail['LicenseModuleApplicationRejectionAppealDetail']['appeal_date'],'%d-%m-%Y','');
                        ?>
                    </td>
                </tr>
                
                <tr>
                    <td>Appeal Application</td>
                    <td class="colons">:</td>
                    <td>
                        <?php echo $orgDetail['LicenseModuleApplicationRejectionAppealDetail']['application_of_appeal']; ?>
                    </td>
                </tr>
                <tr>
                    <td>Appeal Approval Status</td>
                    <td class="colons">:</td>
                    <td>
                        <?php
                            $approvalStatusId = $orgDetail['LicenseModuleApplicationRejectionAppealDetail']['approval_status_id'];                            
                            echo '<div style="margin-left:-8px; padding:0;">'
                                    . $this->Form->input("approval_status_id", array('type' => 'radio', 'required' => true, 'class' => 'approval_status', 'options' => $approval_status_options, 'default' => $approvalStatusId, 'legend' => false, 'div' => false))
                                    . '</div>';
                        ?>
                    </td>
                </tr>

                <tr id="if_accepted" <?php if (empty($approvalStatusId) || $approvalStatusId != 1) echo 'style="display:none;"'; ?>>
                    <td>Application Resume State</td>
                    <td class="colons">:</td>
                    <td>
                        <?php
                            echo $this->Form->input("resume_state_id", array('type' => 'select', 'options' => $state_list, 'empty' => '-----Select-----', 'label' => false, 'div' => false));
                        ?>
                    </td>
                </tr>
                <tr id="if_not_accepted" <?php if (!empty($approvalStatusId) && $approvalStatusId != 0) echo 'style="display:none;"'; ?>>
                    <td>Final Rejection Reason</td>
                    <td class="colons">:</td>
                    <td>
                        <?php
                            echo $this->Form->input("rejection_option_id", array('type' => 'select', 'options' => $rejection_options, 'empty' => '-----Select-----', 'label' => false, 'div' => false));
                        ?>
                    </td>
                </tr>
                
                <tr>
                    <td>Comment</td>
                    <td class="colons">:</td>
                    <td>
                        <?php echo $this->Form->input("comment", array('type' => 'textarea', 'label' => false, 'div' => false)); ?>
                    </td>
                </tr>
            </table>
        </div>

        <div class="btns-div">
            <table style="margin:0 auto; padding:0;" cellspacing="7">
                <tr>
                    <td style="text-align:right;">
                        <?php
                        echo $this->Js->link('Close', array('controller' => 'LicenseModuleApplicationRejectionAppealDetails', 'action' => 'view'), array_merge($pageLoading, array('class' => 'mybtns')));
                        ?>
                    </td>
                    <td style="text-align:center;">
                        <?php
                        echo $this->Js->submit('Submit', array_merge($pageLoading, array('class' => 'mybtns', 'confirm' => 'Are you sure to submit ?',
                            'success' => "msg.init('success', '$title', '$title has been successfully submited.');",
                            'error' => "msg.init('error', '$title', '$title submition failed !');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div>

        <?php echo $this->Form->end();
        } ?>

    </fieldset>
</div>

<?php //} ?>


<script type="text/javascript">
    $(document).ready(function() {
        $("input:radio.approval_status").click(function() {
            if($(this).attr("value") == '1') {
                $("#if_accepted").show();
                $("#if_not_accepted").hide();
            }
            else {
                $("#if_accepted").hide();
                $("#if_not_accepted").show();
            }
        });
    });
</script>
