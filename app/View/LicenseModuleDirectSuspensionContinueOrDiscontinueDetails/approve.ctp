<?php   
    $title = "Suspension Continue or Discontinue";    
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
?> 
<div>
    <fieldset>
        <legend><?php echo $title; ?></legend> 
        <?php echo $this->Form->create('LicenseModuleDirectSuspensionContinueOrDiscontinueDetail'); ?>
        
        <div class="form">
            <table style="width:100%;" cellpadding="8" cellspacing="8" border="0">
                <tr>
                    <td colspan="3" style="border-bottom:2px solid #137387; padding:0;">
                    <?php 
                        echo 'Name of Organization: <strong>' . $orgName . '</strong>' . $this->Form->input('org_id', array('type' => 'hidden', 'value' => $org_id, 'label' => false));
                        
                        echo $this->Js->link('Pre. Details', array('controller'=>'LicenseModuleDirectSuspensionExplanationVerifyDetails','action'=>'preview', $org_id), array_merge($pageLoading, array('class'=>'btnlink', 'style'=>'display:inline-block; float:right;', 'update'=>'#popup_div')));
                    ?>
                    </td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td class="colons">:</td>
                    <td>
                        <?php echo $this->Form->input("status_id", array('type' => 'radio', 'class' => 'approval_status', 'options' => $approval_status_options, 'legend' => false, 'div' => false)); ?>
                    </td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td class="colons">:</td>                                
                    <td style="padding-left:10px">
                        <?php 
                            echo $this->Time->format(new DateTime('now'),'%d-%m-%Y','');
                            echo $this->Form->input('decision_date', array('type'=>'hidden', 'value'=>date("Y-m-d"), 'label'=>false));
                        ?>
                    </td>
                </tr>                
                <tr>
                    <td>Comment</td>
                    <td class="colons">:</td>
                    <td style="padding-left:10px"><?php echo $this->Form->input('comment', array('type' => 'textarea', 'escape' => false, 'div'=>false, 'label' => false)); ?></td>
                </tr>
            </table>
        </div>
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'LicenseModuleDirectSuspensionContinueOrDiscontinueDetails','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
                       ?> 
                    </td>
                    <td style="text-align: center;">
                        <?php
                            echo $this->Js->submit('Submit', array_merge($pageLoading, 
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
                //$("#if_accepted").hide();
                $("#if_not_approved").show();
            }
            else {
                //$("#if_accepted").show();
                $("#if_not_approved").hide();
            }
        });
    });
    
</script>
