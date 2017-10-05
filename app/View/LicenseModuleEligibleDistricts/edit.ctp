<?php 
    //echo $this->element('contentheader', array("variable_name"=>"current"));
?>
<div id="frmBasicInfo_add">
    <?php 
        $title = "Eligible Districts for License";
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

        $this->Paginator->options($pageLoading);
    ?>
     <fieldset style="border:1px solid #9ebfe8">
        <legend>
          <?php echo $title;?>   
        </legend>           
        <?php
            echo $this->Form->create('LicenseModuleEligibleDistrict');
        ?>
        <div class="form">
            <table cellpadding="0" cellspacing="0" border="0">                
                <tr>
                    <td class="labelTd">Name of District</td>
                    <td class="colons">:</td>
                    <td class="inputTd"><?php echo $this->Form->input('district_id',array('type'=>'select','options'=>$districtsOptions,'id'=>'districts', 'empty'=>'---Select---', 'label'=>false)); ?></td>
                </tr>               
                <tr>
                    <td class="labelTd">Year</td>
                    <td class="colons">:</td>
                    <td class="inputTd"><?php echo $this->Form->input('year',array('label'=>false));?></td>
                </tr>        
            </table>           
        </div>
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'LicenseModuleEligibleDistricts','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
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
<?php
    //echo $this->element('homefooter', array("variable_name"=>"current")); 
?>
<?php if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer')) echo $this->Js->writeBuffer(); // Writes cached scripts ?>
