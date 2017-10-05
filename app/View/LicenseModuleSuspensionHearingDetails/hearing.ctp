<?php   
    $title = "Hearing Information";
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
            echo $this->Form->create('LicenseModuleSuspensionHearingDetail');
        ?>           
        <div class="form">            
            <table cellpadding="5" cellspacing="7" border="0">
                <tr>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td style="font-weight: bold;"><?php echo $orgName;?></td>
                </tr>
                <tr>
                    <td>Hearing Date</td>
                    <td class="colons">:</td>
                    <td>
                        <?php echo $this->Form->input('hearing_date',array('type'=>'text', 'class'=>'date_picker', 'div'=>false, 'label'=>false));?>                        
                    </td>
                </tr>                
                <tr>
                    <td style="width:200px;" valign="top">Hearing Details</td>
                    <td style="font-weight:bold; padding-left: 7px;" valign="top">:</td>
                    <td><?php echo $this->Form->input('hearing_details',array('type'=>'textarea', 'escape' => false, 'div'=>false, 'label'=>false)); ?></td>
                </tr>                
            </table>
        </div>
        <div class="btns-div">
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php echo $this->Js->link('Close', array('controller' => 'LicenseModuleSuspensionHearingDetails','action' => 'view?this_state_ids='.$this_state_ids),array_merge($pageLoading, array('class'=>'mybtns'))); ?>                        
                    </td>
                    <td style="text-align: center;">
                        <?php
                            echo $this->Js->submit('Send', array_merge($pageLoading, 
                                                    array('success'=>"msg.init('success', '$title', 'Hearing information has been submitted successfully.');", 
                                                          'error'=>"msg.init('error', '$title', 'Submission failed!');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div>
     <?php  echo $this->Form->end(); ?>
    </fieldset>
</div>
<script>    
    $(function() {        
        $('#ui-datepicker-div').remove();
        $('#ui-datepicker-div').empty();        
        $('.date_picker').each(function() {
            $(this).datepicker({
                yearRange: 'c-5:c+5',
                dateFormat: 'dd-mm-yy',
                altField: '#' + this.id + '_alt',
                altFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                showOtherMonths: true,
                showOn: 'both',
                buttonImageOnly: true,
                buttonImage: 'img/calendar.gif',
                buttonText: 'Click to show the calendar'
            });
        });        
    });    
</script>