<?php
if (isset($msg) && !empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } 
    else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}
$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 'class' => 'mybtns',
    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

$user_group_id = $this->Session->read('User.GroupIds');
$isAdmin = !empty($user_group_id) && in_array(1,$user_group_id);
?>
<div id="basicInfo">    
    <fieldset>          
        <?php if (!empty($basicInfoDetails) && !empty($basicInfoDetails['BasicModuleBasicInformation']['id'])) { ?>        
            <div class="dtview">
                <table cellpadding="7" cellspacing="8" border="0" style="width:95%;">
                    <tr>
                        <td style="width:27%;font-weight:bold;">Name of NGO-MFIs</td>
                        <td class="colons">:</td>
                        <td style="width:73%; ">                        
                            <span style="float:left; max-width:87%; margin:3px 0;">
                                <?php echo $basicInfoDetails['BasicModuleBasicInformation']['full_name_of_org']; ?>
                            </span>
                            <span style="float:right;">
                                <?php
                                $isEditable = $this->Session->read('Form.IsEditable');                                
                                if ($isEditable) {
                                    if(($licensing_state_id=='30'&&(empty($basicInfoDetails['BasicModuleBasicInformation']['licensing_year'])||empty($basicInfoDetails['BasicModuleBasicInformation']['license_no'])||empty($basicInfoDetails['BasicModuleBasicInformation']['license_issue_date'])||empty($primary_reg_act_details)))||$licensing_state_id=='0') {
                                       $org_id = $basicInfoDetails['BasicModuleBasicInformation']['id'];
                                       $edit_button = $this->Js->link('Edit', array('controller' => 'BasicModuleBasicInformations', 'action' => 'edit', $org_id), array_merge($pageLoading, array('class' => 'btnlink', 'style' => 'margin:0; padding:4px;')));                                 
                                    }
                                    else {
                                       $edit_button = ""; 
                                    }
                                    echo $edit_button;
                                }
                                ?>
                            </span>
                        </td>
                    </tr>
                    <?php if($licensing_state_id=='0'&&!empty($basicInfoDetails['BasicModuleBasicInformation']['short_name_of_org'])) { ?>
                    <tr>
                        <td  style="font-weight:bold;">Short Name</td>
                        <td class="colons">:</td>
                        <td><?php echo $basicInfoDetails['BasicModuleBasicInformation']['short_name_of_org']; ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($licensing_state_id=='30'&&!empty($basicInfoDetails['BasicModuleBasicInformation']['license_no'])) { ?>
                    <tr>
                        <td  style="font-weight:bold;">License No.</td>
                        <td class="colons">:</td>
                        <td><?php echo $basicInfoDetails['BasicModuleBasicInformation']['license_no']; ?></td>
                    </tr>
                    <?php } 
                    if($licensing_state_id=='30'&&!empty($basicInfoDetails['BasicModuleBasicInformation']['licensing_year'])) { ?>
                    <tr>
                        <td  style="font-weight:bold;">Licensing Year</td>
                        <td class="colons">:</td>
                        <td><?php echo $basicInfoDetails['BasicModuleBasicInformation']['licensing_year']; ?></td>
                    </tr>
                    <?php } 
                    if($licensing_state_id=='30'&&!empty($basicInfoDetails['BasicModuleBasicInformation']['license_issue_date'])) { ?>
                    <tr>
                        <td  style="font-weight:bold;">Date of License Issue</td>
                        <td class="colons">:</td>
                        <td><?php echo $basicInfoDetails['BasicModuleBasicInformation']['license_issue_date']; ?></td>
                    </tr>
                    <?php }  
                    if(!empty($primary_reg_act_details)) { ?>
                    <tr>
                        <td style="vertical-align: top;font-weight:bold;">Primary Registration Act</td>
                        <td class="colons" style="vertical-align: top;">:</td>
                        <td style="padding-left: 15px;vertical-align: top;">
                            <ul style='list-style-type: square;'>
                                <?php
                                foreach($primary_reg_act_details as $details){
                                    echo "<li>".$details['LookupBasicPrimaryRegistrationAct']['primary_registration_act']." - ".$details['LookupBasicPrimaryRegistrationAct']['act_year']."</li>";
                                }
                                ?>
                            </ul>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
            <div class="btns-div"> 
                <table style="margin:0 auto; padding:0;" cellspacing="5">
                    <tr>
                        <td></td>
                        <td>
                            <?php
                            if ($isAdmin) {
                                echo $this->Js->link('Close', array('controller' => 'BasicModuleBasicInformations', 'action' => 'view', 'all'), $pageLoading);
                            }
                            ?>
                        </td>
                        <td>
                           <?php
                            if($user_group_id=='2') {
                                echo $this->Js->link('Next', array('controller' => 'BasicModuleBranchInfos', 'action' => 'view'), array_merge($pageLoading, array('class' => 'mybtns', 'success' => 'msc.next();', 'title' => 'Go to next module.')));
                            }
                            elseif($user_group_id=='5') {
                                echo $this->Js->link('Next', array('controller' => 'BasicModuleProposedAddresses', 'action' => 'view'), array_merge($pageLoading, array('class' => 'mybtns', 'success' => 'msc.next();', 'title' => 'Go to next module.')));
                            }
                            ?>
                        </td>
                        <td></td>
                    </tr>
                </table>
            </div>
        
        <?php
        } 
        else {
            echo 'No data is available !';
        }
        ?>
    </fieldset>
</div>


