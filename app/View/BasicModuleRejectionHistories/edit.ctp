<?php 
    $title = "Rejection History";
?> 
<div id="frmBasicInfo_add">
     <fieldset>
        <legend>
          <?php echo $title; ?>   
        </legend>            
        <div class="form"> 
            <?php                 
                echo $this->Form->create('BasicModuleRejectionHistory');
            ?>
            <table cellpadding="0" cellspacing="0" border="0">                
               <tr>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('org_id',array('type'=>'select','options'=>$orgNameOptions,'empty'=>'---Select---', 'label'=>false)); ?></td>
                </tr>
                <tr>
                    <td>First Rejection Date</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('firstRejectionDate',array('label'=>false, 'dateFormat'=>'DMY', 'class'=>'dateview')); ?></td>
                </tr>
                <tr>
                    <td>Last Final Rejection Date</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('lastFinalRejectionDate',array('label'=>false, 'dateFormat'=>'DMY', 'class'=>'dateview')); ?></td>
                </tr>
                <tr>
                    <td>Rejection Count</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('rejectionCount',array('class'=>'integers', 'label'=>false)); ?></td>
                </tr>
                <tr>
                    <td>Comment on Last Rejection</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('commentOnLastRejection',array('label'=>false)); ?></td>
                </tr>       
                <tr>
                   <td style="text-align: center;">
                        <?php 
                            echo $this->Js->link('Close', array('controller'=>'BasicModuleRejectionHistories','action'=>'view'), 
                                            array('update'=>'#ajax_div', 'class'=>'mybtns', 'evalScripts'=>true, 
                                            'before'=>$this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer'=>false)),
                                            'complete'=>$this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer'=>false))));
                        ?>     
                   </td>
                   <td style="text-align: center;" colspan="2">
                        <?php                                                   
                           echo $this->Js->submit('Save', array('update'=>'#ajax_div', 'evalScripts'=>true, 
                                'before'=>$this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer'=>false)),
                                'complete'=>$this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer'=>false)), 
                                'success'=>"msg.init('success', '$title', '$title has been added successfully.');", 
                                'error'=>"msg.init('error', '$title', '$title has been failed to add !');"));
                        ?>
                   </td>                   
                </tr>
            </table> 
           <?php  echo $this->Form->end(); ?> 
        </div>        
     </fieldset>
</div>

<script>
    
    $(document).ready(function () {
        $('.integers').numeric({ decimal: false, negative: false });
    });
    
</script>

<?php
    //echo $this->element('homefooter', array("variable_name"=>"current")); 
?>