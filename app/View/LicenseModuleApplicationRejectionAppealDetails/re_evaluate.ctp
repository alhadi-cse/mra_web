<?php 
    $title = "Initial Evaluation"; 
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
?>

<div>
    <fieldset style="padding:5px 0;">
        <legend style="margin-left:15px;"><?php echo $title.' (Re-Evaluate)'; ?></legend>
        <?php echo $this->Form->create('LicenseModuleEvaluationDetailInfo'); ?>
        
        <div class="form">
            
            <p style="padding:0 0 10px 15px;">Name of Organization: <strong><?php echo $orgName; ?></strong></p>
            <?php 
            if(!empty($org_id) && !empty($parameterNewList)) {

                echo '<table class="view" cellpadding="0" cellspacing="0" border="0">'.
                        '<tr>'.
                            '<th style="min-width:25px;">Sl. No.</th>'.
                            '<th style="min-width:200px;">Parameters</th>'.
                            '<th></th>'.
                            '<th style="min-width:200px;">Values</th>'.
                            '<th>Marks</th>'.
                        '</tr>'; 

                $rc = 1;
                foreach($parameterNewList as $parameterDetails) {
                    $parameter_id = $parameterDetails['parameterId'];
                    $parameter_title = $parameterDetails['parameterTitle'];                        
                    $parameter_value = $parameterDetails['parameterValue'];
                    $parameter_options = $parameterDetails['parameterOptions'];
                    $parameter_selected_option = $parameterDetails['parameterSelectedOption'];
            ?>
                <tr <?php if ($rc%2==0) { echo ' class="alt"'; } ?>>
                    <td style="font-weight:bold; text-align:right; padding-right:5px;">
                        <?php if($rc<10) echo '0'; echo $rc.'.'; ?>
                    </td>
                    <td><?php echo $parameter_title; ?></td>
                    <td class="colons">:</td>
                    <td><?php echo $parameter_value; ?></td>
                    <td style="display:none;"><?php echo $this->Form->input("LicenseModuleEvaluationDetailInfo.$rc.parameter_id", array('type'=>'hidden', 'value'=>$parameter_id, 'label'=>false)); ?></td>
                    <td><?php echo $this->Form->input("LicenseModuleEvaluationDetailInfo.$rc.assess_parameter_option_id", array('type'=>'select', 'options'=>$parameter_options, 'value'=>$parameter_selected_option, 'empty'=>'---Select---', 'label'=>false, 'style'=>'width:240px;')); ?></td>
                </tr>
            <?php 
                $rc++;
                }
                echo '</table>';
            }
            ?>
        
        </div>
        
        <div class="btns-div">
            <table style="margin:0 auto; padding:0;" cellspacing="7">
                <tr>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'LicenseModuleEvaluationDetailInfos','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
                       ?> 
                    </td>
                    <td style="text-align:center;">
                        <?php
                            echo $this->Js->submit('Update', array_merge($pageLoading, 
                                                    array('url'=>"/LicenseModuleEvaluationDetailInfos/re_evaluate/$org_id/0", 
                                                          'success'=>"msg.init('success', '$title', '$title has been updated successfully.');", 
                                                          'error'=>"msg.init('error', '$title', 'Update failed!');")));
                        ?>
                    </td>
                    <td style="text-align:center;">
                        <?php
                            echo $this->Js->submit('Submit', array_merge($pageLoading, 
                                                    array('url'=>"/LicenseModuleEvaluationDetailInfos/re_evaluate/$org_id/1", 
                                                          'confirm'=>"Are you sure to Submit ?",
                                                          'success'=>"msg.init('success', '$title', '$title has been submit successfully.');", 
                                                          'error'=>"msg.init('error', '$title', 'submit failed!');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div>
     <?php echo $this->Form->end(); ?>
    </fieldset>
</div>