<div id="frmStatus_add">
    <?php 
        $title = "Rejection/Suspension/Cancellation Category";     
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

        $this->Paginator->options($pageLoading);
    ?>
    <fieldset>
        <legend>
            <?php echo $title; ?>
        </legend> 
        
        <?php  echo $this->Form->create('LookupRejectSuspendCancelStepCategory'); ?>
        <div class="form">           
            <table cellpadding="0" cellspacing="0" border="0"> 
                <tr>
                    <td>History Type</td>
                    <td class="colons">:</td>                    
                    <td><?php echo $this->Form->input('reject_suspend_cancel_history_type_id',array('type'=>'select','options'=>$reject_suspend_cancel_history_type_options,'empty' => '-----Select-----', 'label' => false)); ?></td>
                </tr>
                <tr>
                    <td>Serial no</td>
                    <td class="colons">:</td>
                    <td ><?php echo $this->Form->input('serial_no',array('type'=>'text','class' => 'integers','label' => false)); ?></td>
                </tr> 
                <tr>
                    <td>Category</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('reject_suspend_cancel_category',array('type'=>'text','label' => false)); ?></td>
                </tr>
            </table>
        </div>        
       <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'LookupRejectSuspendCancelStepCategories','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
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
        $('.decimals').numeric({ decimal: ".", negative: false });
    });    
</script>