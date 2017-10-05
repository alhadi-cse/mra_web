<?php   
    $title = "Revoke Show cause or Cancel License";    
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
?> 
<div>
    <fieldset>
        <legend><?php echo $title; ?></legend> 
        <?php 
            echo $this->Form->create('LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail');
        ?> 
        <div class="form">
            <table cellpadding="8" cellspacing="8" border="0">
                <tr>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td style="padding-left:10px">
                        <?php
                            $org_id = $approvalDetails['BasicModuleBasicInformation']['id'];
                            $orgName = $approvalDetails['BasicModuleBasicInformation']['short_name_of_org'];
                            echo (!empty($orgName)? '<strong>' . $orgName . ': </strong>' : '') . $approvalDetails['BasicModuleBasicInformation']['full_name_of_org'];
                            echo $this->Js->link('Details', array('controller'=>'LicenseModuleCancelByMraCancelOrRevokeShowCauseDetails','action'=>'preview', $org_id), array_merge($pageLoading, array('class'=>'btnlink', 'style'=>'display:inline-block; float:right;', 'update'=>'#popup_div')));
                        
                            echo $this->Form->input('org_id', array('type' => 'hidden', 'value' => $org_id, 'label' => false));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td class="colons">:</td>
                    <td>
                        <?php 
                        $approvalStatusId = $approvalDetails['LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail']['showcause_cancel_status_id'];
                        echo $this->Form->input("showcause_cancel_status_id", array('type' => 'radio', 'class' => 'approval_status', 'options' => $approval_status_options, 'default' => $approvalStatusId, 'legend' => false, 'div' => false)); 
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Date </td>
                    <td class="colons">:</td>
                    <td style="padding-left:10px">
                        <?php 
                            echo $this->Time->format($approvalDetails['LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail']['showcause_cancel_date'],'%d-%m-%Y','');
                            echo $this->Form->input('showcause_cancel_date', array('type'=>'hidden', 'value'=>date("Y-m-d"), 'label'=>false));
                        ?>
                    </td>
                </tr>                
                <tr>
                    <td>Comment</td>
                    <td class="colons">:</td>
                    <td style="padding-left:10px"><?php echo $this->Form->input('comment',array('type' => 'textarea', 'value'=>$approvalDetails['LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail']['comment'], 'escape' => false, 'div'=>false, 'label' => false)); ?></td>
                </tr>
            </table>
        </div>
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'LicenseModuleCancelByMraCancelOrRevokeShowCauseDetails','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
                       ?> 
                    </td>
                    <td style="text-align: center;">
                        <?php                                               
                            echo $this->Js->submit('Re-submit', array_merge($pageLoading, 
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
    
    $(document).ready(function(){
        $("input:radio.approval_status").click(function() {
            if($(this).attr("value") != '1') {
                $("#if_not_approved").show();
            }
            else {
                $("#if_not_approved").hide();
            }
        });
    });
    
</script>
