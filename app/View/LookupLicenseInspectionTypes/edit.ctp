<div id="frmStatus_add">
    <?php 
        $title = "Inspection type information";        
        
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

        $this->Paginator->options($pageLoading);
    ?>
    <fieldset>
        <legend><?php echo $title; ?></legend> 
        
        <?php  echo $this->Form->create('LookupLicenseInspectionType'); ?>
        <div class="form">
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td>Sl No.</td>
                    <td class="colons">:</td>
                    <td ><?php echo $this->Form->input('serial_no', array('type' => 'text', 'class' => 'decimals', 'label' => false)); ?></td>
                </tr>
                <tr>
                    <td>Inspection Type Name</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('inspection_type', array('label' => false)); ?></td>
                </tr>
                <tr>
                    <td>Inspection Entry Level</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('inspection_is_multiple', array('type' => 'radio', 'options' => array(0 => 'Single', 1 => 'Multiple'), 'div' => false, 'legend' => false)); ?></td>
                </tr>
            </table>
        </div>        
       <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'LookupLicenseInspectionTypes','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
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
