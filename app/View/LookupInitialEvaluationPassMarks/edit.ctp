<div id="frmStatus_add">
    <?php 
        $title = "Initial Evaluation Pass Mark Setting";     
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
                    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
                    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    ?>
    <fieldset>
        <legend>
            <?php echo $title; ?>            
        </legend> 
        
        <?php  echo $this->Form->create('LookupInitialEvaluationPassMark'); ?>
        <div class="form">           
            <table cellpadding="0" cellspacing="0" border="0">
               <tr>
                    <td>Type</td>
                    <td class="colons">:</td>                    
                    <td><?php echo $this->Form->input('initial_evaluation_pass_mark_type_id',array('type'=>'select','options'=>$initial_evaluation_pass_mark_type_options,'empty' => '-----Select-----', 'label' => false)); ?></td>
                </tr>
                <tr>
                    <td>Pass Marks</td>
                    <td class="colons">:</td>
                    <td ><?php echo $this->Form->input('marks',array('type'=>'text','label' => false)); ?></td>
                </tr>              
            </table>
        </div>        
       <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'LookupInitialEvaluationPassMarks','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
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