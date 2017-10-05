<?php   
    $title = "Initial Evaluation Committee Recommendation";    
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
?> 
<div>
    <fieldset>
        <legend><?php echo $title; ?></legend> 
        <?php 
            echo $this->Form->create('LicenseModuleRecommendationOfEvaluationCommittee');
        ?>
        
        <div class="form">
            
            <p style="border-bottom: 2px solid #137387; padding:0 0 0 15px;">
                Name of Organization: 
                <?php 
                    echo '<strong>'.$orgName.'</strong>';
                    echo $this->Js->link('FieldInspection Details', array('controller'=>'LicenseModuleFieldInspectionDetailInfos','action'=>'preview', $org_id), array_merge($pageLoading, array('class'=>'btnlink', 'style'=>'display:inline-block;', 'update'=>'#popup_div')));
                ?>
            </p>
            
            <?php echo $this->Form->input('id', array('type'=>'hidden', 'label'=>false)).$this->Form->input('org_id', array('type'=>'hidden', 'value'=>$org_id, 'label'=>false)); ?>
            
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td>Recommendation of Evaluation Committee</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('recommendation_status_id', array('type' => 'radio','options' => array('1'=>'Recommended','0'=>'Not Recommended'),'legend' => false)); ?></td>
                </tr>
                <tr>
                    <td colspan="3" id="if_approved">
                        <table cellpadding="0" cellspacing="0" border="0">                     
                            <tr>
                                <td >Date Of Recommendation</td>
                                <td class="colons" style="padding-left: 105px;">:</td>                                
                                <td><?php echo $this->Form->month('recommendation_date', array('empty'=>"--Month--", 'style'=>'width:127px; margin:7px 2px 7px 5px;')).$this->Form->day('approval_date', array('empty'=>"--Day--", 'style'=>'width:127px;margin:7px 2px 7px 0px;')).$this->Form->year('approval_date', date('Y') - 15, date('Y'), array('empty'=>"--Year--", 'style'=>'width:128px;margin:7px 2px 7px 0px;')); ?> </td>
                            </tr>                            
                        </table>
                    </td>
                </tr>                 
                <tr>
                    <td colspan="3" id="if_not_approved">
                        <table cellpadding="0" cellspacing="0" border="0">                     
                            <tr>
                                <td style="vertical-align: top;padding:5px 0px 0px 0px;">Reason (if not approved)</td>
                                <td class="colons" style="vertical-align: top;padding:5px 0px 0px 60px;">:</td>
                                <td style="vertical-align: top;padding:0px 0px 0px 5px;"><?php echo $this->Form->input('reason_if_not_recommended',array('type' => 'textarea', 'escape' => false,'label' => false)); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top;padding:5px 0px 0px 0px;">Comment</td>
                    <td class="colons" style="vertical-align: top;padding:5px 0px 0px 0px;">:</td>                    
                    <td><?php echo $this->Form->input('comment',array('type' => 'textarea', 'escape' => false,'label' => false)); ?></td>
                </tr>
            </table>      
        </div>
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'LicenseModuleRecommendationOfEvaluationCommittees','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
                       ?> 
                    </td>
                    <td style="text-align: center;">
                        <?php                                               
                            echo $this->Js->submit('Save', array_merge($pageLoading, 
                                                    array('success'=>"msg.init('success', '$title', '$title has been added successfully.');", 
                                                          'error'=>"msg.init('error', '$title', 'Insertion failed!');")));
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
        selectedApprovalStatus = $('input[name$="data[LicenseModuleRecommendationOfEvaluationCommittee][recommendation_status_id]"]:checked').val();
        if(selectedApprovalStatus=="1"){
            show();            
        }
        else if(selectedApprovalStatus=="2"){
            hide();             
        }
        else{
            hideAll();
        }

        $('input[name$="data[LicenseModuleRecommendationOfEvaluationCommittee][recommendation_status_id]"]').click(function(){
            if($(this).attr("value")=="1"){
                show();                
            }
            else if($(this).attr("value")=="2"){
                hide();             
            }
            else{
                hideAll();
            }
        });
        
        function hide() {            
            $("#if_approved").hide();
            $("#if_not_approved").show(); 
        }
        function show() {
            $("#if_approved").show();
            $("#if_not_approved").hide(); 
        } 
        function hideAll() {            
            $("#if_approved").hide();
            $("#if_not_approved").hide(); 
        }
        function showAll() {            
            $("#if_approved").show();
            $("#if_not_approved").show(); 
        }
    });    
</script>