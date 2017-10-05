<?php 
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
    <?php
        if (!empty($payment_type_id)) {
            if ($payment_type_id == 1) {
                $title = "License Fee Payment Information";
            } else if ($payment_type_id == 2) {
                $title = "Annual Fee Payment Information";
            } else if ($payment_type_id == 3) {
                $title = "Renewal Fee Payment Information";
            } else if ($payment_type_id == 4) {
                $title = "Others Type of Payment Information";
            }
        }
        else {
            $title = "Payment Information";
        }
        
        $existing_data = $this->request->data;
        
        $pageLoading = array('update'=>'#ajax_div', 'evalScripts'=>true, 'class'=>'mybtns', 
                    'before'=>$this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer'=>false)), 
                    'complete'=>$this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer'=>false)));
    ?>
    
     <fieldset>
        <legend><?php echo $title; ?></legend>
        <?php echo $this->Form->create('GeneralModulePaymentInfo'); ?>
        <div class="form">           
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td>
                        <?php
                        echo '<strong style="padding-left:5px;">' . $existing_data['BasicModuleBasicInformation']['short_name_of_org'] . ': </strong>' 
                                . $existing_data['BasicModuleBasicInformation']['full_name_of_org']
                                . $this->Form->input('org_id', array('type' => 'hidden', 'value' => $org_id, 'div' => false, 'label' => false));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="width:200px;">Payment Type</td>
                    <td class="colons">:</td>
                    <td>
                        <?php 
                        if (!empty($payment_type_id)) {
                            echo '<strong style="padding-left:5px;">' . $existing_data['LookupPaymentType']['payment_type'] . '</strong>'
                            . $this->Form->input('payment_type_id', array('type' => 'hidden', 'value' => $payment_type_id, 'div' => false, 'label' => false));
                        } else {
                            echo $this->Form->input('payment_type_id', array('type' => 'select', 'options' => $paymentTypeOptions, 'id' => 'payment_type', 'empty' => '-----Select-----', 'label' => false));
                        }
                        ?>
                    </td>
                </tr>
                <?php if(empty($payment_type_id)) { ?>
                <tr>
                    <td>Description of Other Types</td>
                    <td class="colons" style="padding-left: 35px;">:</td>                                
                    <td><?php echo $this->Form->input('payment_reason',array('label' => false)); ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td>Payment Amount (BDT.)</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('payment_amount',array('type'=>'text', 'class' => 'decimals', 'label' => false)); ?></td>
                </tr>
                <tr>
                    <td>Delay Fine (if applicable)</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('delay_fine_amount',array('type'=>'text', 'class' => 'decimals', 'label' => false)); ?></td>
                </tr>                                 
                <tr>
                    <td>Date Of Payment</td>
                    <td class="colons">:</td>
                    <td>
                        <?php 
                            echo '<span style="padding-left:5px;">' .$this->Time->format(new DateTime('now'),'%d-%m-%Y',''). '</span>';
                            echo $this->Form->input('date_of_payment', array('type'=>'hidden', 'value'=>date("Y-m-d"), 'label'=>false));
                        ?>
                    </td>                    
                </tr>                
                <tr>
                    <td>Payment Doc Number</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('payment_document_no',array('label' => false)); ?></td>
                </tr>          
            </table>           
        </div>
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>                        
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'GeneralModulePaymentInfos', 'action' => 'view', $payment_type_id), array_merge($pageLoading, array('confirm' => 'Are you sure to close ?')));
                        ?>
                    </td>
                    <td>
                        <?php                        
                            echo $this->Js->submit('Update', array_merge($pageLoading, array('success'=>"msg.init('success', '$title', '$title has been updated successfully.');", 
                                                      'error'=>"msg.init('error', '$title', '$title has been failed to add !');")));                       
                        ?>
                    </td>
                </tr>
            </table>
        </div>             
        <?php echo $this->Form->end(); ?>
     </fieldset>
</div>

<script>
    
    $(document).ready(function () {
        $('.integers').numeric({ decimal: false, negative: false });
        $('.decimals').numeric({ decimal: ".", negative: false });
    });
    
</script>
