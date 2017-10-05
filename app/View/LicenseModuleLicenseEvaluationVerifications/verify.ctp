<?php   
    $title = "License Evaluation Committee Verification";    
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading);
    if(!empty($msg)) {
        if(is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init('.json_encode($msg).');', array('inline'=>true));
        }
        else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline'=>true));
        }
    }
?> 
<div>
    <fieldset>
        <legend><?php echo $title; ?></legend> 
        <?php 
            echo $this->Form->create('LicenseModuleLicenseEvaluationVerification');
        ?>
        
        <div class="form">            
            <p style="border-bottom: 2px solid #137387; padding:0 0 0 15px;">
                Name of Organization: 
                <?php 
                    echo '<strong>'.$orgName.'</strong>';                   
                ?>
            </p>
            
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td>Verification Of Cancel Request</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('verification_status_id', array('type' => 'radio','options' => array('1'=>'Verified','0'=>'Not Verified'),'legend' => false)); ?></td>
                </tr>
                <tr>
                    <td style="vertical-align: top;padding:5px 0px 0px 0px;">Comments</td>
                    <td class="colons" style="vertical-align: top;padding:5px 0px 0px 0px;">:</td>
                    <td><?php echo $this->Form->input('comments',array('type' => 'textarea', 'escape' => false,'label' => false)); ?></td>
                </tr>
            </table>
        </div>
        <div class="btns-div">
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php
                            echo $this->Js->link('Close', array('controller' => 'LicenseModuleLicenseEvaluationVerifications','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));
                        ?>
                    </td>
                    <td style="text-align: center;">
                        <?php
                            if(!$is_exists){
                                echo $this->Js->submit('Verify', array_merge($pageLoading, 
                                    array('success'=>"msg.init('success', '$title', 'Cancel request has been verified successfully.');", 
                                          'error'=>"msg.init('error', '$title', 'Verification failed!');")));
                            }
                        ?>
                    </td>
                </tr>
            </table>
        </div>
     <?php  echo $this->Form->end(); ?>
    </fieldset>
</div>