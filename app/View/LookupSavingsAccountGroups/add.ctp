<div id="frmStatus_add">
    <?php 
        $title = "Savings Account Group"; 
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
        $this->Paginator->options($pageLoading);
    ?>
    <fieldset>
        <legend><?php echo $title; ?></legend>         
        <?php  echo $this->Form->create('LookupSavingsAccountGroup'); ?>
        <div class="form">
            <table cellpadding="0" cellspacing="0" border="0">                
                <tr>
                    <td>SL No.</td>
                    <td class="colons">:</td>
                    <td ><?php echo $this->Form->input('serial_no',array('type'=>'text','class'=>'decimals', 'label'=>false)); ?></td>
                </tr>
<!--                <tr>
                    <td>Savings Account Group</td>
                    <td class="colons">:</td>
                    <td><?php //echo $this->Form->input('savings_account_group',array('label'=>false)); ?></td>
                </tr>-->
                <tr>
                    <td>Savings Account Group Lower Limit</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('lower_value',array('label'=>false)); ?></td>
                </tr>
                <tr>
                    <td>Savings Account Group Upper Limit</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('upper_value',array('label'=>false)); ?></td>
                </tr>
            </table>
        </div>
        
        <div class="btns-div">
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller' =>'LookupSavingsAccountGroups', 'action' =>'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
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