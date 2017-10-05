<?php   
    $title = "License Renewal Information";
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
                echo $this->Form->create('LicenseModuleLicenseRenewalInfo');
            ?>           
        <div class="form"> 
            
            <table cellpadding="0" cellspacing="0" border="0">                
                <tr>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('org_id',array('type'=>'select','options'=>$orgNameOptions,'empty' => '---Select---', 'label' => false)); ?></td>
                </tr>       
                <tr>
                    <td>Date Of Renewal</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('renewal_date',array('label' => false, 'empty'=>true,'minYear' => date('Y')-15,'maxYear' => date('Y'), 'class'=>'dateview')); ?></td>
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
                            echo $this->Js->link('Close', array('controller' => 'LicenseModuleLicenseRenewalInfos','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
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