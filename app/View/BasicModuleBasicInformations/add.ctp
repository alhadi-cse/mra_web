
<?php 
    if(!empty($msg)) {
        if(is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init('.json_encode($msg).');', array('inline'=>true));
        }
        else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline'=>true));
        }
    }
    
    if (!empty($IsValidUser)) {
        $title = "Name and Registration Information of Organization";        
        $pageLoading = array('update'=>'#ajax_div', 'evalScripts'=>true, 'class'=>'mybtns', 
                    'before'=>$this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer'=>false)), 
                    'complete'=>$this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer'=>false)));

?> 

<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        
        <?php echo $this->Form->create('BasicModuleBasicInformation'); ?>
        
        <div class="form">
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td>Short Name of Organization</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('short_name_of_org',array('label'=>false)); ?></td>
                </tr>
                <tr>
                    <td>Full Name of Organization</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('full_name_of_org',array('label'=>false)); ?></td>
                </tr>                
                <tr>
                    <td>Primary Registration Acts</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('primary_registration_act_id',array('type'=>'select','options'=>$primary_reg_act_options,'empty'=>'---Select---', 'label'=>false)); ?></td>
                </tr>
                <tr>
                    <td>Authorized Person</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('name_of_authorized_person',array('label'=>false)); ?></td>
                </tr>
                <tr>
                    <td>Designation</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('designation_of_authorized_person',array('label'=>false)); ?></td>
                </tr>               
                <tr>
                    <td>Date Of Application</td>
                    <td class="colons">:</td>
                    <td>
                        <?php 
                            echo $this->Form->input('date_of_application', array('type' => 'hidden', 'id' => 'txtSelectedDate_alt', 'label' => false, 'div' => false))
                                .$this->Form->input('',array('type' => 'text','id' => 'txtSelectedDate','class' => 'date_picker'));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Registration Authority</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('registration_authority_id',array('type'=>'select','options'=>$regauthorityoptions,'empty'=>'---Select---', 'label'=>false)); ?></td>
                </tr>
                <tr>
                    <td>Date Of Registration</td>
                    <td class="colons">:</td>
                    <td>
                        <?php 
                            echo $this->Form->input('date_of_registration', array('type' => 'hidden', 'id' => 'txtSelectedDate_alt', 'label' => false, 'div' => false))
                                .$this->Form->input('',array('type' => 'text','id' => 'txtSelectedDate','class' => 'date_picker'));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Expiry Date Of Registration(if any)</td>
                    <td class="colons">:</td>
                    <td>
                        <?php 
                            echo $this->Form->input('expiry_date_of_registration', array('type' => 'hidden', 'id' => 'txtExpiryDate_alt', 'label' => false, 'div' => false))
                                .$this->Form->input('',array('type' => 'text','id' => 'txtExpiryDate','class' => 'date_picker'));
                        ?>
                    </td>
                </tr>
            </table>
        </div>

        <div class="btns-div">
            <table style="margin:0 auto; padding:0;" cellspacing="5">
                <tr>
                    <td></td>
                    <td>
                        <?php 
                        $data_mode = $this->Session->read('Data.Mode');
                        $isNew = empty($data_mode) || $data_mode=='insert';

                        if ($isNew) { 
                            echo $this->Js->submit('Save', array_merge($pageLoading, 
                                                            array('success'=>"msg.init('success', '$title', '$title has been added successfully.');", 
                                                                  'error'=>"msg.init('error', '$title', '$title has been failed to add !');")));
                        } 
                        else {
                            echo $this->Js->submit('Update', array_merge($pageLoading, 
                                                            array('update'=>'#popup_div', 
                                                                'success'=>"msg.init('success', '$title', '$title has been update successfully.');", 
                                                                'error'=>"msg.init('error', '$title', '$title has been failed to update !');")));
                        }
                        ?>
                    </td>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller'=>'BasicModuleBasicInformations', 'action'=>'view', 'all'), 
                                                            array_merge($pageLoading, array('confirm'=>'Are you sure to close ?')));
                        ?>
                    </td>
                    <td>
                        <?php 
                        if (!$isNew) { 
                            echo $this->Js->link('Next', array('controller'=>'BasicModuleBranchInfos', 'action'=>'add'), 
                                                            array_merge($pageLoading, array('success'=>'msc.next();')));
                        }
                        ?>
                    </td>
                    <td></td>   
                </tr>
            </table>
        </div>
            
        <?php echo $this->Form->end(); ?>
        
    </fieldset>
</div>

<?php
    if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer')) 
            echo $this->Js->writeBuffer();
?>

<?php } ?>
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