<?php 
    $title = "Rejection/Suspension/Cancellation Histories";
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading);
?>
<div id="frmBasicInfo_add">
     <fieldset>
        <legend>
          <?php echo $title; ?> 
        </legend> 
        <?php
            echo $this->Form->create('LicenseModuleRejectSuspendCancelHistory');
        ?>
        <div class="form">            
            <table cellpadding="0" cellspacing="0" border="0">              
                <tr>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('org_id',array('type'=>'select','options'=>$orgNameOptions,'empty' => '---Select---', 'label' => false)); ?></td>
                </tr>
                <tr>
                    <td>History Type</td>
                    <td class="colons">:</td>                    
                    <td><?php echo $this->Form->input('reject_suspend_cancel_history_type_id',array('type'=>'select','options'=>$reject_suspend_cancel_history_type_options,'id'=>'reject_suspend_cancel_history_types','empty' => '---Select---', 'label' => false)); ?></td>
                </tr>
                <tr>
                    <td>Category</td>
                    <td class="colons">:</td>                    
                    <td><?php echo $this->Form->input('reject_suspend_cancel_category_id',array('type'=>'select','options'=>'null', 'id'=>'reject_suspend_cancel_categories','empty' => '---Select---', 'label' => false)); ?></td>
                </tr> 
                <tr>
                    <td>Reason</td>
                    <td class="colons">:</td>                    
                    <td><?php echo $this->Form->input('reject_suspend_cancel_reason_id',array('type'=>'select','options'=>'null', 'id'=>'reject_suspend_cancel_reasons','empty' => '---Select---', 'label' => false)); ?></td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('reject_suspend_cancel_date',array('label' => false,'empty'=>true,'minYear' => date('Y')-15,'maxYear' => date('Y'), 'class'=>'dateview')); ?></td>
                </tr>                
                <tr>
                    <td style="width:200px;">Comments</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('comment',array('label' => false)); ?></td>
                </tr>            
            </table>           
        </div>
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'LicenseModuleRejectSuspendCancelHistories','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
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

<script>
    
    $(document).ready(function () {
        $('.integers').numeric({ decimal: false, negative: false });
    });
    
</script>

<?php
$this->Js->get('#reject_suspend_cancel_history_types')->event('change', 
    $this->Js->request(array(
        'controller'=>'LicenseModuleRejectSuspendCancelHistories',
        'action'=>'category_select'
        ), array(
        'update'=>'#reject_suspend_cancel_categories',            
        'async' => true,
        'method' => 'post',
        'dataExpression'=>true,
        'data'=> $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))
    );  
$this->Js->get('#reject_suspend_cancel_history_types')->event('change', 
    $this->Js->request(array(
        'controller'=>'LicenseModuleRejectSuspendCancelHistories',
        'action'=>'reason_select'
        ), array(
        'update'=>'#reject_suspend_cancel_reasons',            
        'async' => true,
        'method' => 'post',
        'dataExpression'=>true,
        'data'=> $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))
    );
$this->Js->get('#reject_suspend_cancel_categories')->event('change', 
    $this->Js->request(array(
        'controller'=>'LicenseModuleRejectSuspendCancelHistories',
        'action'=>'reason_select'
        ), array(
        'update'=>'#reject_suspend_cancel_reasons',            
        'async' => true,
        'method' => 'post',
        'dataExpression'=>true,
        'data'=> $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))
    );
?>
<?php
    if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer')) echo $this->Js->writeBuffer();
    // Writes cached scripts
?>