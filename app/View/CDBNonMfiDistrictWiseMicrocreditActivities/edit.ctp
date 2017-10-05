<?php 
    //echo $this->element('contentheader', array("variable_name"=>"current"));
?> 
<div id="frmBasicInfo_add">
    <?php 
        $title = "Districtwise Microcredit Activity Information";   
        $pageLoading = array('update'=>'#ajax_div', 'evalScripts'=>true, 'class'=>'mybtns', 
                    'before'=>$this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer'=>false)), 
                    'complete'=>$this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer'=>false)));

    ?>
     <fieldset>
        <legend>
          <?php echo $title; ?>   
        </legend>
    <?php  echo $this->Form->create('CDBNonMfiDistrictWiseMicrocreditActivity'); ?>
        <div class="form">             
            <table cellpadding="0" cellspacing="0" border="0"> 
                <tr>
                    <td>Name of Organization</td>
                    <td style="width:3px;font-weight: bold;">:</td>
                    <td><?php echo $this->Form->input('org_id',array('type'=>'select','options'=>$orgNameOptions,'empty'=>'---Select---', 'label'=>false)); ?></td>
                </tr>               
                <tr>
                    <td>Year & Month</td>
                    <td style="width:3px;font-weight: bold;">:</td>
                    <td><?php echo $this->Form->year('CDBNonMfiDistrictWiseMicrocreditActivity.year_and_month', date('Y') - 15, date('Y'), array('empty'=>"--Select Year--", 'style'=>'width:192px;margin:2px 2px 2px 5px;')).$this->Form->month('CDBNonMfiDistrictWiseMicrocreditActivity.year_and_month', array('empty'=>"--Select Month--", 'style'=>'width:192px;')).$this->Form->day('CDBNonMfiDistrictWiseMicrocreditActivity.year_and_month', array('empty'=>false, 'style'=>'display:none;')); ?> </td>
                </tr>
                <tr>
                    <td>District</td>
                    <td style="width:3px;font-weight: bold;">:</td>
                    <td><?php echo $this->Form->input('district_id',array('type'=>'select','options'=>$districtsOptions,'id'=>'districts', 'empty'=>'---Select---', 'label'=>false)); ?>
                    </td>
                </tr>               
                <tr>
                    <td>Distribution</td>
                    <td style="width:3px;font-weight: bold;">:</td>
                    <td><?php echo $this->Form->input('distribution',array('type'=>'text', 'class'=>'decimals', 'label'=>false)); ?></td>
                </tr>
                <tr>
                    <td>Recovery</td>
                    <td style="width:3px;font-weight: bold;">:</td>
                    <td><?php echo $this->Form->input('recovery',array('type'=>'text', 'class'=>'decimals', 'label'=>false)); ?></td>
                </tr>                
                <tr>
                    <td>Male borrower</td>
                    <td style="width:3px;font-weight: bold;">:</td>
                    <td><?php echo $this->Form->input('maleBorrower',array('type'=>'text', 'class'=>'integers', 'label'=>false)); ?></td>
                </tr>
                <tr>
                    <td>Female borrower</td>
                    <td style="width:3px;font-weight: bold;">:</td>
                    <td><?php echo $this->Form->input('femaleBorrower',array('type'=>'text', 'class'=>'integers', 'label'=>false)); ?></td>
                </tr>
                <tr>
                    <td>Total borrower</td>
                    <td style="width:3px;font-weight: bold;">:</td>
                    <td><?php echo $this->Form->input('totalBorrower',array('type'=>'text', 'class'=>'integers', 'label'=>false)); ?></td>
                </tr>       
            </table>          
        </div> 
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:0;" cellspacing="5">
                <tr>
                    <td></td>
                    <td>
                        <?php                        
                            echo $this->Js->submit('Save', array_merge($pageLoading, 
                                    array('success'=>"msg.init('success', '$title', '$title has been updated successfully.');", 
                                          'error'=>"msg.init('error', '$title', '$title has been failed to update!');")));
                        
                        ?>
                    </td>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller'=>'CDBNonMfiDistrictWiseMicrocreditActivities', 'action'=>'view', 'all'), 
                                                            array_merge($pageLoading, array('confirm'=>'Are you sure to close ?')));
                        ?>
                    </td>                    
                    <td></td>   
                </tr>
            </table>
        </div>            
    <?php echo $this->Form->end(); ?>
     </fieldset>
</div>
<?php
    //echo $this->element('homefooter', array("variable_name"=>"current")); 
?>
<script>
    
    $(document).ready(function () {
        $('.integers').numeric({ decimal: false, negative: false });
        $('.decimals').numeric({ decimal: ".", negative: false });
    });
    
</script>