<?php 
    $title = "Initial Re-Assessment"; 
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    
    $is_review = (!empty($is_review) && $is_review == 1);
?>

<div>
    <fieldset style="padding:5px 0;">
        <legend style="margin-left:15px;"><?php echo $title.' (Re-Assess)'; ?></legend>
        <?php echo $this->Form->create('LicenseModuleInitialAssessmentDetail'); ?>
        <p style="padding:10px 0 0 15px;">Name of Organization: <strong><?php echo $orgName; ?></strong></p>
        <div class="form" style="max-width:780px; margin:0 auto; overflow-x:auto;">
            
            <?php 
//            if(!empty($org_id) && !empty($parameterNewList)) {
//
//                echo '<table class="view" cellpadding="0" cellspacing="0" border="0">'.
//                        '<tr>'.
//                            '<th style="min-width:25px;">Sl. No.</th>'.
//                            '<th style="min-width:200px;">Parameters</th>'.
//                            '<th></th>'.
//                            '<th style="min-width:200px;">Values</th>'.
//                            '<th style="min-width:200px;">Assess Marks</th>'.
//                            ($is_review ? '<th style="min-width:245px;">Review Marks</th>' : '').
//                        '</tr>'; 
//
//                $rc = 1;
//                foreach($parameterNewList as $parameterDetails) {
//                    $parameter_id = $parameterDetails['parameterId'];
//                    $parameter_title = $parameterDetails['parameterTitle'];                        
//                    $parameter_value = $parameterDetails['parameterValue'];
//                    $parameter_options = $parameterDetails['parameterOptions'];
//                    $parameter_selected_option = $parameterDetails['parameterSelectedOption'];
            ?>
            
            <?php 
            if(!empty($org_id) && !empty($parameterNewList)) {
            ?>
            
            <table class="view" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <th style="min-width:25px;">Sl. No.</th>
                    <th style="min-width:200px;">Parameters</th>
                    <th></th>
                    <th style="min-width:200px;">Values</th>
                    <th style="min-width:200px;">Assess Marks</th>
                    <?php if ($is_review) echo '<th style="min-width:245px;">Review Marks</th>'; ?>
                </tr>
                
                <?php
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
                    <td style="display:none;"><?php echo $this->Form->input("LicenseModuleInitialAssessmentDetail.$rc.parameter_id", array('type'=>'hidden', 'value'=>$parameter_id, 'label'=>false)); ?></td>
                    <td>
                        <?php 
                        if($is_review) {
                            try{
                                if(!empty($parameter_options) && !empty($parameter_selected_option))
                                    echo $parameter_options[$parameter_selected_option];
                            } catch (Exception $ex) {
                            }
                            //echo $this->Form->input("LicenseModuleInitialAssessmentDetail.$rc.parameter_option_id", array('type'=>'hidden', 'value'=>$parameter_selected_option, 'label'=>false));
                        }
                        else 
                            echo $this->Form->input("LicenseModuleInitialAssessmentDetail.$rc.assessors_parameter_option_id", array('type'=>'select', 'options'=>$parameter_options, 'value'=>$parameter_selected_option, 'empty'=>'---Select---', 'label'=>false, 'style'=>'width:240px;')); 
                        ?>
                    </td>
                    
                    <?php 
                    if($is_review) 
                        echo'<td>'.
                            $this->Form->input("LicenseModuleInitialAssessmentDetail.$rc.assess_parameter_option_id", array('type'=>'select', 'options'=>$parameter_options, 'value'=>$parameter_selected_option, 'empty'=>'---Select---', 'label'=>false, 'style'=>'width:240px;')).
                            '</td>';
                    ?>
                </tr>
            <?php 
                $rc++;
                }
            }
            ?>
            </table>
        </div>
        
        <div class="btns-div">
            <table style="margin:0 auto; padding:0;" cellspacing="7">
                <tr>
                    <td>
                        <?php //'controller' => 'LicenseModuleInitialAssessmentReviewVerificationDetails', 
                            echo $this->Js->link('Close', array(
                                'controller' => ((!empty($is_review) && $is_review == 1) ? 'LicenseModuleInitialAssessmentReviewVerificationDetails' : 'LicenseModuleInitialAssessmentDetails'),
                                'action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
                       ?> 
                    </td>
                    <td style="text-align:center;">
                        <?php
                        if(!empty($is_review) && $is_review == 1)
                            echo $this->Js->submit('Update', array_merge($pageLoading, 
                                                    array('url'=>"/LicenseModuleInitialAssessmentDetails/re_assess/$org_id/1/1", 
                                                          'success'=>"msg.init('success', '$title', '$title has been updated successfully.');", 
                                                          'error'=>"msg.init('error', '$title', 'Update failed!');")));
                        else
                            echo $this->Js->submit('Update', array_merge($pageLoading, 
                                                    array('url'=>"/LicenseModuleInitialAssessmentDetails/re_assess/$org_id/0", 
                                                          'success'=>"msg.init('success', '$title', '$title has been updated successfully.');", 
                                                          'error'=>"msg.init('error', '$title', 'Update failed!');")));
                        ?>
                    </td>
                    <td style="text-align:center;">
                        <?php
                        if(empty($is_review) || $is_review != 1)
                            echo $this->Js->submit('Submit', array_merge($pageLoading, 
                                                    array('url'=>"/LicenseModuleInitialAssessmentDetails/re_assess/$org_id/1", 
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