<div id="frmStatus_add">
    <?php 
        $title = "Savings Specification"; 
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
        $this->Paginator->options($pageLoading);
    ?>
    <fieldset>
        <legend>
            <?php echo $title; ?>            
        </legend>         
        <?php  echo $this->Form->create('LookupSavingsSpecification'); ?>
        <div class="form">           
            <table cellpadding="0" cellspacing="0" border="0">                
                <tr>
                    <td>SL No.</td>
                    <td class="colons">:</td>
                    <td ><?php echo $this->Form->input('serial_no',array('type'=>'text','class'=>'integers', 'label'=>false)); ?></td>
                </tr>
                <tr>
                    <td>Savings Specification</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('name_of_scheme',array('label'=>false)); ?></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('description',array('label'=>false)); ?></td>
                </tr>
                <tr>
                    <td>Type of Installment</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('type_of_installment_id',array('type'=>'select','options'=>$type_of_installment_options,'empty'=>'---Select---','label'=>false));?></td>
                </tr>
                <tr>
                    <td>Rate of Interest</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('rate_of_interest',array('label'=>false, 'class'=>'decimals')); ?></td>
                </tr>
            </table>
        </div>        
       <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'LookupSavingsSpecifications','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
                       ?> 
                    </td>
                    <td style="text-align: center;">
                        <?php                                               
                            echo $this->Js->submit('Save', array_merge($pageLoading, 
                                                    array('success'=>"msg.init('success', '$title', '$title has been updated successfully.');", 
                                                          'error'=>"msg.init('error', '$title', 'Update failed!');")));
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
        $('.decimals').numeric({ decimal: ".", negative: false });
    });    
</script>