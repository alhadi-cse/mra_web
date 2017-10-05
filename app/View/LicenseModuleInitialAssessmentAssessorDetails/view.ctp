<div>    
    <?php
    $title = "Assessor for Initial Assessment";
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading);
    $assessor_group_id = $this->Session->read('Assessor.GroupId');
    ?>

    <fieldset>
        <legend><?php echo $title; ?></legend>

        <?php if (!empty($values_assigned)) { ?>

            <?php echo $this->Form->create('LicenseModuleInitialAssessmentAssessorDetail'); ?>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td style="padding-left:15px; text-align:right;">Search Option</td>
                    <td>
                        <?php
                        echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:200px',
                            'options' => array('AdminModuleUserProfile.full_name_of_user' => 'Name of Assessor',
                                'AdminModuleUserProfile.designation_of_user' => 'Assessor Designation',
                                'AdminModuleUserProfile.div_name_in_office' => 'Assessor Department',
                                'LicenseModuleInitialAssessmentAssessorDetail.from_form_no' => 'From Starting Serial No.',
                                'LicenseModuleInitialAssessmentAssessorDetail.to_form_no' => 'Form Ending Serial No.')
                        ));
                        ?>
                    </td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('search_keyword', array('label' => false, 'style' => 'width:250px')); ?></td>
                    <td style="text-align:left;">
                        <?php
                        echo $this->Js->submit('Search', array_merge($pageLoading, array('class' => 'btnsearch')));
                        ?>
                    </td>
                </tr>
            </table>
            <?php echo $this->Form->end(); ?>

            <fieldset>
                <legend>Assessor Assigned</legend>
                <table cellpadding="0" cellspacing="0" border="0" class="view">
                    <tr>
                        <th style="width:25px;" rowspan="2">Sl. No.</th>
                        <th style="width:auto;" rowspan="2">Assessors</th>
                        <th style="width:200px;" colspan="2">Form No.</th>
                        <th style="width:85px;" rowspan="2">Action</th>
                    </tr>
                    <tr>
                        <th style="width:100px;">From</th>
                        <th style="width:100px;">To</th>
                    </tr>
                    <tr>
                        <?php
//                        echo "<th style='width:120px;'>" . $this->Paginator->sort('AdminModuleUserProfile.full_name_of_user', 'Name of Assessor') . "</th>";
//                        echo "<th style='width:170px;'>" . $this->Paginator->sort('AdminModuleUserProfile.designation_of_user', 'Designation & Department') . "</th>";
//                        echo "<th style='width:70px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentAssessorDetail.from_form_no', 'From Form No.') . "</th>";
//                        echo "<th style='width:70px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentAssessorDetail.to_form_no', 'To Form No.') . "</th>";                                        
//                        echo "<th style='width:75px;'>Action</th>";
                        ?>
                    </tr>
                    <?php
                    $rc = 0;
                    foreach ($values_assigned as $value) {
                        ?>
                        <tr <?php echo ($rc % 2 != 0) ? ' class="alt"' : ''; ?>>
                            <td style="text-align:center;">
                                <?php
                                ++$rc;
                                echo "$rc.";
                                ?>
                            </td>
                            <td><?php echo $value['LicenseModuleInitialAssessmentAssessorDetail']['name_with_designation_and_dept']; ?></td>
                            <td style="text-align:center;"><?php echo $value['LicenseModuleInitialAssessmentAssessorDetail']['from_form_no']; ?></td>  
                            <td style="text-align:center;"><?php echo $value['LicenseModuleInitialAssessmentAssessorDetail']['to_form_no']; ?></td>  
                            <td style="text-align:center; padding:2px; height:30px;">                                        
                                <?php
                                echo $this->Js->link('Delete', array('controller' => 'LicenseModuleInitialAssessmentAssessorDetails', 'action' => 'delete', $value['LicenseModuleInitialAssessmentAssessorDetail']['id'], "?" => array("this_state_ids" => "1_2")), array_merge($pageLoading, array('confirm' => 'Are you sure to delete?', 'success' => "msg.init('success', '$title', '$title has been deleted successfully.');", 'error' => "msg.init('error', '$title', 'Deletion failed!');", 'class' => 'btnlink')));
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </fieldset>

            <?php
        } else {
            echo "<p style='font-weight:bold; color:red;'>Not yet assigned any form !</p>";
        }
        ?>

        <div class="btns-div">                
            <table style="margin:0 auto; padding:0;" cellspacing="7">
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <?php
                        echo $this->Js->link('Assign', array('controller' => 'LicenseModuleInitialAssessmentAssessorDetails', 'action' => 'assign'), array_merge($pageLoading, array('class' => 'mybtns')));
                        ?>
                    </td>
                    <td> 
                        <?php
                        echo $this->Js->link('Re-assign', array('controller' => 'LicenseModuleInitialAssessmentAssessorDetails', 'action' => 're_assign'), array_merge($pageLoading, array('class' => 'mybtns')));
                        ?>
                    </td>
                    <td></td>   
                </tr>
            </table>
        </div>
    </fieldset>
</div>
