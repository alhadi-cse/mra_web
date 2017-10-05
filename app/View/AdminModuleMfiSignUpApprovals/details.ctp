<?php                
    if(isset($msg) && !empty($msg)) {
        if(is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init('.json_encode($msg).');', array('inline'=>true));
        }
        else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline'=>true));
        }
    }
    else {
        $org_type_id = $this->Session->read('SignUpDetail.OrgTypeId');         
?>

<div id="basicInfo" title="Detail Information of Organization" style="margin:0px; padding:10px; background-color:#fafdff;"> 
    <fieldset>
        <div class="datagrid" style="max-height:430px; overflow-y:auto; color:#232428;">
            <table cellpadding="7" cellspacing="8" border="0">                
                <tr>
                    <td>Organization's Full Name</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfi_sign_up_details['AdminModuleMfiSignUpDetail']['full_name_of_org']; ?></td>
                </tr>
                <tr>
                    <td>Primary Registration</td>
                    <td class="colons">:</td>
                    <td style="padding-left: 15px;">
                        <ul style='list-style-type: square;'>
                            <?php
                            foreach($primary_reg_act_details as $details){
                                echo "<li>".$details['LookupBasicPrimaryRegistrationAct']['primary_registration_act']." - ".$details['LookupBasicPrimaryRegistrationAct']['act_year']."</li><br />";
                            }
                            ?>
                        </ul>
                    </td>
                </tr>
                <?php if($org_type_id=='1') { ?>
                <tr>
                    <td>District</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfi_sign_up_details['LookupAdminBoundaryDistrict']['district_name']; ?></td>
                </tr>    
                <?php } elseif($org_type_id=='2') { ?>
                <tr>
                    <td>License No.</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfi_sign_up_details['AdminModuleMfiSignUpDetail']['license_no']; ?></td>
                </tr>    
                <?php } ?>
                <tr>
                    <td>Address</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfi_sign_up_details['AdminModuleMfiSignUpDetail']['address_of_org']; ?></td>
                </tr>                
                <tr>
                    <td>Name of Authorized Person</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfi_sign_up_details['AdminModuleMfiSignUpDetail']['name_of_authorized_person']; ?></td>
                </tr>
                <tr>
                    <td>Designation of Authorized Person</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfi_sign_up_details['AdminModuleMfiSignUpDetail']['designation_of_authorized_person']; ?></td>
                </tr>               
                <tr>
                    <td>Mobile No.</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfi_sign_up_details['AdminModuleMfiSignUpDetail']['mobile_no']; ?></td>
                </tr>
                <tr>
                    <td>Fax No.</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfi_sign_up_details['AdminModuleMfiSignUpDetail']['fax_no']; ?></td>
                </tr>
                <tr>
                    <td>E-Mail</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfi_sign_up_details['AdminModuleMfiSignUpDetail']['email']; ?></td>
                </tr>
                <tr>
                    <td>Approval Status</td>
                    <td class="colons">:</td>
                    <td> 
                    <?php                         
                        if($mfi_sign_up_details['AdminModuleMfiSignUpDetail']['approval_status']=='0') {
                            echo "Not Yet Approved"; 
                        }
                        elseif($mfi_sign_up_details['AdminModuleMfiSignUpDetail']['approval_status']=='1') {
                            echo "Approved";
                        }                            
                    ?>
                    </td>
                </tr>
            </table>
        </div>
    </fieldset>
</div>

<?php } ?>
