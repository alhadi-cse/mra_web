<div>    
     <?php 
        $title = "Assign Officer for Follow Up"; 
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
                    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
                    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

        $this->Paginator->options($pageLoading);
        $assessor_group_id = $this->Session->read('Officer.GroupId');
        
    ?>
    
    <fieldset>
        <legend><?php echo $title; ?></legend>
        
        <?php if(!empty($values_assigned)) { ?>
        
        <?php
            echo $this->Form->create('SupervisionModuleAssignOfficerForFollowUpDetail');
        ?>
        <table cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td style="padding-left:15px; text-align:right;">Search Option</td>
                <td>
                    <?php
                        echo $this->Form->input('search_option',
                                                    array('label' => false, 'style' => 'width:200px',
                                                            'options' => array('AdminModuleUserProfile.full_name_of_user' => 'Name of Officer',
                                                                'AdminModuleUserProfile.designation_of_user' => 'Officer Designation',
                                                                'AdminModuleUserProfile.div_name_in_office' => 'Officer Department',
                                                                'BasicModuleBasicInformation.full_name_of_org' => 'Name of Organization',
                                                                'BasicModuleBasicInformation.license_no' => 'License No.')
                                                        ));
                    ?>
                </td>
                <td class="colons">:</td>
                <td><?php echo $this->Form->input('search_keyword',array('label' => false,'style'=>'width:250px')); ?></td>
                <td style="text-align:left;">
                   <?php
                       echo $this->Js->submit('Search', array_merge($pageLoading, array('class'=>'btnsearch')));
                    ?>
               </td>
            </tr>
        </table>
        <?php echo $this->Form->end(); ?>        
        <fieldset>
            <legend>Officer Assigned</legend>
            <table cellpadding="0" cellspacing="0" border="0" class="view">
                <tr>
                    <?php
                        echo "<th style='width:120px;'>" . $this->Paginator->sort('AdminModuleUserProfile.full_name_of_user', 'Name of Officer') . "</th>";
                        echo "<th style='width:170px;'>" . $this->Paginator->sort('AdminModuleUserProfile.designation_of_user', 'Designation & Department') . "</th>";
                        echo "<th style='width:100px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:100px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";                                        
                        echo "<th style='width:75px;'>Action</th>";
                    ?>
                </tr>
                <?php foreach($values_assigned as $value) { ?>
                <tr>
                    <td style="padding:7px;"><?php echo $value['AdminModuleUserProfile']['full_name_of_user']; ?></td>
                    <td style="text-align:justify;">
                        <?php 
                            echo $value['AdminModuleUserProfile']['designation_of_user']
                                    . ((!empty($value['AdminModuleUserProfile']['designation_of_user']) && !empty($value['AdminModuleUserProfile']['div_name_in_office'])) ? ', ' : '')
                                    . $value['AdminModuleUserProfile']['div_name_in_office'];
                        ?>
                    </td>
                    <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['full_name_of_org']; ?></td>  
                    <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['license_no']; ?></td>  
                    <td style="text-align:center; padding:2px; height:30px;">                                        
                        <?php 
                            echo $this->Js->link('Delete', array('controller' => 'SupervisionModuleAssignOfficerForFollowUpDetails','action' => 'delete', $value['SupervisionModuleAssignOfficerForFollowUpDetail']['id'], "?" => array("this_state_ids" => "30_700")), 
                                    array_merge($pageLoading, array('confirm' => 'Are you sure to delete?', 'success' => "msg.init('success', '$title', 'Assigned Officer for this organization has been deleted successfully.');", 'error' => "msg.init('error', '$title', 'Deletion failed!');",'class'=>'btnlink')));
                        ?>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </fieldset>
        
        <?php 
        } else {
            echo "<p style='font-weight:bold; color:red;'>No data is available !</p>";
        }
        ?>
        
        <div class="btns-div">                
            <table style="margin:0 auto; padding:0;" cellspacing="7">
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <?php
                            if(empty($values_assigned)) {
                                echo $this->Js->link('Assign', array('controller'=>'SupervisionModuleAssignOfficerForFollowUpDetails', 'action'=>'assign'), 
                                                    array_merge($pageLoading, array('class'=>'mybtns')));
                            }
                            elseif (!empty($values_assigned)) {
                                echo $this->Js->link('Re-assign', array('controller'=>'SupervisionModuleAssignOfficerForFollowUpDetails', 'action'=>'re_assign'), 
                                                    array_merge($pageLoading, array('class'=>'mybtns')));
                            }                            
                        ?>
                    </td>
                    <td></td>
                    <td></td>   
                </tr>
            </table>
        </div>
    </fieldset>
</div>
