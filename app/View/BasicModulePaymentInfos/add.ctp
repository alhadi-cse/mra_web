
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
        $title = "Payment Information (Add)";
        $pageLoading = array('update'=>'#ajax_div', 'evalScripts'=>true, 'class'=>'mybtns', 
                    'before'=>$this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer'=>false)), 
                    'complete'=>$this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer'=>false)));

?>

<div>
     <fieldset>
        <legend><?php echo $title; ?></legend>       
        <div class="form">
            <?php echo $this->Form->create('BasicModulePaymentInfo'); ?>
            <table cellpadding="0" cellspacing="0" border="0"> 
                <tr>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td>
                        <?php 
                            if(!empty($org_id))
                                echo $this->Form->input('org_id', array('type'=>'text', 'value'=>$orgNameOptions[$org_id], 'disabled'=>'disabled', 'label'=>false));
                            else
                                echo $this->Form->input('org_id', array('type'=>'select', 'options'=>$orgNameOptions, 'value'=>$org_id, 'empty'=>'---Select---', 'label'=>false));
                       ?>
                    </td>
                </tr>
                <tr>
                    <td>Payment Type</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('BasicModulePaymentInfo.paymentType_id',array('type'=>'select','options'=>$paymentTypeOptions,'empty'=>'---Select---','label'=>false)); ?></td>
                </tr>
                <tr>
                    <td>Payment No.</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('BasicModulePaymentInfo.payment_no',array('class'=>'integers', 'label'=>false)); ?></td>
                </tr>
                <tr>
                    <td>Payment Amount</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('BasicModulePaymentInfo.paymentAmount',array('type'=>'text', 'class'=>'decimals', 'label'=>false)); ?></td>
                </tr>
                <tr>
                    <td>Date Of Payment</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('BasicModulePaymentInfo.dateOfPayment',array('label'=>false, 'dateFormat'=>'DMY', 'class'=>'dateview')); ?></td>
                </tr>                
                 <tr>
                    <td>Payment Doc Number</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('BasicModulePaymentInfo.paymentDocNumber',array('class'=>'integers', 'label'=>false)); ?></td>
                </tr>
            </table>
                                      
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
                                echo $this->Js->submit('Update', array_merge($pageLoading, array('update'=>'#popup_div', 
                                                                    'success'=>"msg.init('success', '$title', '$title has been update successfully.');", 
                                                                    'error'=>"msg.init('error', '$title', '$title has been failed to update !');")));
                            }
                            ?>
                        </td>
                        <td>
                            <?php 
                                echo $this->Js->link('Close', array('controller'=>'BasicModulePaymentInfos', 'action'=>'view', $org_id), 
                                                                array_merge($pageLoading, array('confirm'=>'Are you sure to close ?')));
                            ?>
                        </td>
                        <td>
                            <?php 
                            if (!$isNew) { 
                                echo $this->Js->link('Next', array('controller'=>'BasicModuleTransactionInfos', 'action'=>'add'), 
                                                                array_merge($pageLoading, array('success'=>'msc.next();')));
                            }
                            ?>
                        </td>
                        <td></td>   
                    </tr>
                </table>
            </div>
            
           <?php  echo $this->Form->end(); ?>
        </div>
     </fieldset>
</div>
<script>
    
    $(document).ready(function () {
        $('.integers').numeric({ decimal: false, negative: false });
        $('.decimals').numeric({ decimal: ".", negative: false });
    });
    
</script>

<?php } ?>