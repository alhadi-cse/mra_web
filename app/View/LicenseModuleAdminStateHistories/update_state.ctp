<?php
    if(!empty($msg)) {
        if(is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init('.json_encode($msg).');', array('inline'=>true));
        }
        else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline'=>true));
        }
    }
    $title = "Licensing State Histories";
    $pageLoading = array('update'=>'#ajax_div', 'evalScripts'=>true, 'class'=>'mybtns',
                'before'=>$this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer'=>false)),
                'complete'=>$this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer'=>false)));
?>
<?php //echo $this->requestAction(array('controller'=>'LicenseModuleAdminStateHistories', 'action'=>'view'),array('return')); ?>

<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <?php echo $this->Form->create('LicenseModuleAdminStateHistory'); ?>
            <div class="form">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td>Licensing Year</td>
                        <td class="colons">:</td>
                        <td>
                            <?php echo $this->Form->input('licensing_year',array('type'=>'select','options'=>$licensing_year_options, 'id'=>'years', 'empty'=>'---Select---','label'=>false)); ?> 
                        </td>
                    </tr>
                    <tr>
                        <td>Licensing State</td>
                        <td class="colons">:</td>
                        <td>
                            <?php echo $this->Form->input('licensing_state_id',array('type'=>'select','id'=>'states','empty'=>'---Select---','label'=>false)); ?> 
                        </td>
                    </tr>
                    <tr>
                        <td>Days Required to Complete the State</td>
                        <td class="colons">:</td>
                        <td>                            
                            <?php echo $this->Form->input('state_completion_days',array('type'=>'text','class'=>'integers','label'=>false));?>
                        </td>
                    </tr>
                    <tr>
                        <td>Starting Date</td>
                        <td class="colons">:</td>
                        <td>                            
                            <?php echo $this->Form->month('starting_date', array('selected'=>'5','empty'=>"--Month--", 'style'=>'width:127px; margin:7px 2px 7px 5px;')).$this->Form->day('starting_date', array('selected'=>'5','empty'=>"--Day--", 'style'=>'width:127px;margin:7px 2px 7px 0px;')).$this->Form->year('starting_date', date('Y') - 15, date('Y'), array('selected'=>'2012','empty'=>"--Year--", 'style'=>'width:128px;margin:7px 2px 7px 0px;')); ?>
                            <?php //echo $this->Form->input('ending_date',array('value'=>$ending_date,'type'=>'hidden','label'=>false));?>
                        </td>
                    </tr>                    
                    <tr>
                        <td>Status</td>
                        <td class="colons">:</td>
                        <td>
                            <?php echo $this->Form->input('is_current',array('type'=>'select','options'=>$completion_status_options,'empty'=>'---Select---','label'=>false)); ?>
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
                                echo $this->Js->submit('Save', array_merge($pageLoading, 
                                                        array('success'=>"msg.init('success', '$title', '$title has been added successfully.');", 
                                                              'error'=>"msg.init('error', '$title', '$title Insertion failed !');")));                               
                            ?>
                        </td>
                        <td>
                            <?php 
                                echo $this->Js->link('Close', array('controller'=>'LicenseModuleAdminStateHistories', 'action'=>'view'), 
                                                        array_merge($pageLoading, array('confirm'=>'Are you sure to close ?')));
                            ?>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>        
        <?php  echo $this->Form->end(); ?> 
    </fieldset>
</div>

<script>
    
    $(document).ready(function () {
        $('.integers').numeric({ decimal: false, negative: false });
        $('.decimals').numeric({ decimal: ".", negative: false });
    });    
</script>

<?php
$this->Js->get('#years')->event('change', 
    $this->Js->request(array(
        'controller'=>'LicenseModuleAdminStateHistories',
        'action'=>'select_year_wise_state'
        ), array(
        'update'=>'#states',            
        'async'=>true,
        'method'=>'post',
        'dataExpression'=>true,
        'data'=> $this->Js->serializeForm(array(
                'isForm'=>true,
                'inline'=>true
            ))
        ))
    );
?>

<?php
    if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer')) 
        echo $this->Js->writeBuffer();
?>
