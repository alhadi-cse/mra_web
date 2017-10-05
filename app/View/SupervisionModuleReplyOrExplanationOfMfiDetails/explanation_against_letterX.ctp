<?php
    $title = "MFI explanation against Letter";
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
            echo $this->Form->create('SupervisionModuleReplyOrExplanationOfMfiDetail');
        ?>           
        <div class="form">            
            <table cellpadding="4" cellspacing="6" border="0">
                <tr>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td style="font-weight: bold;"><?php echo $orgName;?></td>
                </tr>
                <tr>
                    <td>Explanation Giving Date</td>
                    <td class="colons">:</td>
                    <td>
                        <?php 
                            echo $this->Time->format(new DateTime('now'),'%d-%m-%Y',''); 
                            $date_value = $this->Time->format(new DateTime('now'),'%Y-%m-%d',''); 
                            echo $this->Form->input('explanation_giving_date', array('type'=>'hidden', 'value'=>$date_value, 'label'=>false));
                        ?>
                    </td>
                </tr>                
                <tr>
                    <td>Letter Subjects</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('letter_id', array('type' => 'select', 'options' => $letter_subjects, 'empty' => '---Select---', 'label' => false, 'div' => false)); ?></td>
                </tr>
                <tr>
                    <td style="width:200px;" valign="top">Explanation Details</td>
                    <td style="font-weight:bold; padding-left: 7px;" valign="top">:</td>
                    <td><?php echo $this->Form->input('explanation_details',array('type'=>'textarea', 'escape' => false, 'div'=>false, 'label'=>false)); ?></td>
                </tr>                
            </table>                       
        </div> 
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'SupervisionModuleReplyOrExplanationOfMfiDetails','action' => 'view?this_state_ids='.$this_state_ids),array_merge($pageLoading, array('class'=>'mybtns')));  
                       ?> 
                    </td>
                    <td style="text-align: center;">
                        <?php                                               
                            echo $this->Js->submit('Submit', array_merge($pageLoading, 
                                                    array('success'=>"msg.init('success', '$title', 'MFI Explanation against letter has been given successfully.');", 
                                                          'error'=>"msg.init('error', '$title', 'Insertion failed!');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div> 
     <?php  echo $this->Form->end(); ?>
    </fieldset>
</div>

<script>
   
    $(function () {
        $('.date_picker').each(function () {
            $(this).datepicker({
                showAnim: 'show',
                dateFormat: 'dd-mm-yy',
                altField: '#' + this.id + '_alt',
                altFormat: "yy-mm-dd",
                minDate: '0',
                yearRange: '-0:+1',
                showOtherMonths: true,
                showOn: 'both',
                buttonImageOnly: true,
                buttonImage: 'img/calendar.gif',
                buttonText: 'Click to show the calendar'
            });
        });
    });

</script>
