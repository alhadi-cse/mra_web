<?php   
    $title = "Review application of MFI against suspend order";
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading);
$this_state_ids = $this->Session->read('Current.StateIds');
    if(!empty($msg)) {
        if(is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init('.json_encode($msg).');', array('inline'=>true));
        }
        else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline'=>true));
        }
    }
?> 
<div id="frmBasicInfo_add">
    <fieldset>
        <legend>
          <?php echo $title; ?>   
        </legend>
        <?php                 
            echo $this->Form->create('LicenseModuleSuspensionReviewApplicationDetail');
        ?>           
        <div class="form">            
            <table cellpadding="5" cellspacing="7" border="0">
                <tr>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td style="font-weight: bold;"><?php echo $orgName;?></td>
                </tr>
                <tr>
                    <td>Apply Date</td>
                    <td class="colons">:</td>
                    <td>
                        <?php 
                            echo $this->Time->format(new DateTime('now'),'%d-%m-%Y','');                            
                            echo $this->Form->input('apply_date', array('type'=>'hidden', 'div'=>false, 'label'=>false));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="width:200px;" valign="top">Review Details</td>
                    <td style="font-weight:bold; padding-left: 7px;" valign="top">:</td>
                    <td><?php echo $this->Form->input('review_application_details',array('type'=>'textarea', 'escape' => false, 'div'=>false, 'label'=>false)); ?></td>
                </tr>
            </table>                       
        </div> 
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'LicenseModuleSuspensionReviewApplicationDetails','action' => 'view?this_state_ids='.$this_state_ids),array_merge($pageLoading, array('class'=>'mybtns')));  
                       ?> 
                    </td>
                    <td style="text-align: center;">
                        <?php                                               
                            echo $this->Js->submit('Submit', array_merge($pageLoading, 
                                                    array('success'=>"msg.init('success', '$title', 'Review application has been submitted successfully.');", 
                                                          'error'=>"msg.init('error', '$title', 'Insertion failed!');")));  ?>
                    </td>
                </tr>
            </table>
        </div> 
     <?php  echo $this->Form->end(); ?>
    </fieldset>
</div>