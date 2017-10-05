<?php   
    $title = "Recommendation of Evaluation Committee";    
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
            
            <table cellpadding="8" cellspacing="8" border="0">
                <tr>
                    <td>Recommendation of Evaluation Committee</td>
                    <td class="colons">:</td>
                    <td style="padding:5px 0"><?php echo $this->Form->input('recommendation_status_id', array('type' => 'radio', 'class' => 'recommendation_status', 'options' => array('1'=>'Recommended','0'=>'Not Recommended'), 'div' => false, 'legend' => false)); ?></td>
                </tr>
                <tr>
                    <td >Date Of Recommendation</td>
                    <td class="colons">:</td>
                    <td style="padding-left:10px;">
                        <?php 
                            echo $this->Time->format(new DateTime('now'),'%d-%m-%Y','');
                            echo $this->Form->input('recommendation_date', array('type'=>'hidden', 'value'=>date("Y-m-d"), 'div' => false, 'label'=>false));
                        ?>
                    </td>
                </tr>
                <tr id="if_not_recommend" style="display:none;">
                    <td style="vertical-align:top;">Reason (if not recommend)</td>
                    <td class="colons" style="vertical-align:top;">:</td>
                    <td style="vertical-align:top; padding-left:10px"><?php echo $this->Form->input('reason_if_not_recommended',array('type' => 'textarea', 'div' => false, 'label' => false, 'escape' => false)); ?></td>
                            
                </tr>
                <tr>
                    <td style="vertical-align:top;"Comment</td>
                    <td class="colons" style="vertical-align:top;">:</td>                    
                    <td style="vertical-align:top; padding-left:10px"><?php echo $this->Form->input('comment',array('type' => 'textarea', 'div' => false, 'label' => false, 'escape' => false)); ?></td>
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
                            echo $this->Js->submit('Submit', array_merge($pageLoading, 
                                                    array('success'=>"msg.init('success', '$title', '$title has been submit successfully.');", 
                                                          'error'=>"msg.init('error', '$title', 'submission failed!');")));
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
        $("input:radio.recommendation_status").click(function() {
            if($(this).attr("value") != '1') {
                $("#if_not_recommend").show();
            }
            else {
                $("#if_not_recommend").hide();
            }
        });
    });
    
</script>
