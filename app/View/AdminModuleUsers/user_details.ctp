<?php if (!empty($user_id)) { 
    $user_group_ids = $this->Session->read('User.GroupIds');    
?>
<div class="user_details">    
    <fieldset>
        <legend>Instructions</legend>
        <table cellpadding="8" cellspacing="15">
            <tr>
                <td>
                    <ul style="list-style: square; font-size: 13px;">
                        <?php if(!empty($user_group_ids)&&(in_array(2, $user_group_ids))) { ?>
                        <li style="padding: 1px 2px;"><strong style="color:#000080;">Login with the User Name and Password of head office </strong>.</li>
                        <li style="padding: 6px 2px;">Go to <strong style="color:#000080;">Basic Information --> Details --> Operational Management Related Statement</strong> and Click <strong>Add New </strong> to submit relevant information.</li>
                        <li style="padding: 6px 2px;">Go to <strong style="color:#000080;">Basic Information --> Details --> Working Area</strong> and Click <strong>Add New </strong> to submit relevant information.</li>
                        <li style="padding: 6px 2px;">Go to <strong style="color:#000080;">Basic Information --> Details --> MFI Monthly Summary</strong> and Click <strong>Add New </strong> to submit relevant information.</li>
                        <li style="padding: 6px 2px;">Go to <strong style="color:#000080;">Loan Information --> Information from Branch --> Branch wise Loan Information</strong> and Click <strong>Add New </strong> to submit relevant information.</li>
                        <li style="padding: 6px 2px;">Go to <strong style="color:#000080;">Loan Information --> Information from Branch --> Classification of Loan Disbursement and LO by Size</strong> and Click <strong>Add New </strong> to submit relevant information.</li>
                        <li style="padding: 6px 2px;">Go to <strong style="color:#000080;">Loan Information --> Information from Branch --> Different Types of Loan and Their Service Charges</strong> and Click <strong>Add New </strong> to submit relevant information.</li>
                        <li style="padding: 6px 2px;">Go to <strong style="color:#000080;">Loan Information --> Information from Head Office --> Consolidated Statement of Financial Position</strong> and Click <strong>Add New </strong> to submit relevant information.</li>
                        <li style="padding: 6px 2px;">Go to <strong style="color:#000080;">Loan Information --> Information from Head Office --> Statement of Income & Expenditure</strong> and Click <strong>Add New </strong> to submit relevant information.</li>                        
                        <li style="padding: 6px 2px;">Go to <strong style="color:#000080;">My Account --> Details --> Change Password</strong> to change head office password.</li>
                        <li style="padding: 6px 2px;">Go to <strong style="color:#000080;">My Account --> Details --> Branch Users</strong> and Click <strong>Add New </strong> to add a branch user.</li>
                        <li style="padding: 6px 2px;">Fields with <strong style="color: red;">*</strong> marks are mandatory to fill up.</li>
                        <?php } ?>
                        <?php if(!empty($user_group_ids)&&(in_array(3, $user_group_ids))) { ?>
                        <li style="padding: 6px 2px;"><strong style="color:#000080;">Login with the User Name and Password of branch/area office </strong>.</li>
                        <li style="padding: 6px 2px;">Go to <strong style="color:#000080;">Loan Information --> Information from Branch --> Branch wise Loan Information</strong> and Click <strong>Add New </strong> to submit relevant information.</li>
                        <li style="padding: 6px 2px;">Go to <strong style="color:#000080;">Loan Information --> Information from Branch --> Classification of Loan Disbursement and LO by Size</strong> and Click <strong>Add New </strong> to submit relevant information.</li>
                        <li style="padding: 6px 2px;">Go to <strong style="color:#000080;">Loan Information --> Information from Branch --> Different Types of Loan and Their Service Charges</strong> and Click <strong>Add New </strong> to submit relevant information.</li>
                        <li style="padding: 6px 2px;">Go to <strong style="color:#000080;">Savings Information --> Details --> Branch wise Savings Information</strong> and Click <strong>Add New </strong> to submit relevant information.</li>
                        <li style="padding: 6px 2px;">Go to <strong style="color:#000080;">Savings Information --> Details --> Savings Information on Savings Size</strong> and Click <strong>Add New </strong> to submit relevant information.</li>
                        <li style="padding: 6px 2px;">Go to <strong style="color:#000080;">My Account --> Details --> Change Password</strong> to change branch/area office password.</li>                       
                        <li style="padding: 6px 2px;">Fields with <strong style="color: red;">*</strong> marks are mandatory to fill up.</li>
                        <?php } ?>
                                                
                    </ul>
                </td>                
            </tr>
        </table>        
    </fieldset>
    
    <?php if (!empty($user_group_ids)&&(in_array(2, $user_group_ids)|| in_array(3, $user_group_ids) || in_array(5, $user_group_ids))&&!empty($orgDetails)) { ?>
    <fieldset>
        <legend>Organization Details</legend>
        <table style="width:87%;" cellpadding="8" cellspacing="5">
            <tr>
                <td style="width:20%;">Name</td>
                <td class="colons">:</td>
                <td style="width:80%;">
                            <?php
                            echo $orgDetails['BasicModuleBasicInformation']['full_name_of_org'];
                            if (!empty($orgDetails['BasicModuleBasicInformation']['short_name_of_org']))
                                echo ' (' . $orgDetails['BasicModuleBasicInformation']['short_name_of_org'] . ')';
                            ?>
                </td>
            </tr>
                    <?php if (!empty($orgDetails['BasicModuleBasicInformation']['license_no'])) { ?>
            <tr>
                <td>License No.</td>
                <td class="colons">:</td>
                <td><?php echo $orgDetails['BasicModuleBasicInformation']['license_no']; ?></td>
            </tr>
            <?php } else { ?>
            <tr>
                <td>Form No.</td>
                <td class="colons">:</td>
                <td><?php echo $orgDetails['BasicModuleBasicInformation']['form_serial_no']; ?></td>
            </tr>
            <tr>
                <td>Date of Application</td>
                <td class="colons">:</td>
                <td><?php echo $orgDetails['BasicModuleBasicInformation']['date_of_application']; ?></td>
            </tr>
            <?php } ?>
        </table>
    </fieldset>
    <?php }
    if (!empty($user_group_ids)&&(in_array(4, $user_group_ids))&&!empty($orgDetails)) { ?>
    <fieldset>
        <legend>Organization Details</legend>
        <table style="width:87%;" cellpadding="8" cellspacing="5">
            <tr>
                <td style="width:20%;">Name</td>
                <td class="colons">:</td>
                <td style="width:80%;"><?php echo $orgDetails['CDBNonMfiBasicInfo']['name_of_org']; ?></td>
            </tr>            
            <tr>
                <td>Regulatory Ministry</td>
                <td class="colons">:</td>
                <td><?php echo $orgDetails['LookupCDBNonMfiMinistryAuthorityName']['name_of_ministry_or_authority']; ?></td>
            </tr>
            <tr>
                <td>Type of Agency</td>
                <td class="colons">:</td>
                <td><?php echo $orgDetails['LookupCDBNonMfiType']['type_name']; ?></td>
            </tr>
            <tr>
                <td>Name of Officer</td>
                <td class="colons">:</td>
                <td><?php echo $orgDetails['CDBNonMfiBasicInfo']['name_of_officer']; ?></td>
            </tr>
            <tr>
                <td>Designation</td>
                <td class="colons">:</td>
                <td><?php echo $orgDetails['CDBNonMfiBasicInfo']['designation_of_officer']; ?></td>
            </tr> 
        </table>
    </fieldset>
    <?php } ?>
    <fieldset>
        <legend>User Details</legend>

        <table style="width:87%;" cellpadding="8" cellspacing="5">
            <tr>
                <td style="width:130px;">User Id</td>
                <td class="colons">:</td>
                <td style="width:85%;"><?php echo $user_infos['AdminModuleUser']['user_name']; ?></td>
            </tr>
            <tr>
                <td>Name</td>
                <td class="colons">:</td>
                <td><?php echo $user_infos['AdminModuleUserProfile']['full_name_of_user']; ?></td>
            </tr>
            <tr>
                <td>Designation</td>
                <td class="colons">:</td>
                <td><?php echo $user_infos['AdminModuleUserProfile']['designation_of_user']; ?></td>
            </tr>
            <tr>
                <td>Section/Department</td>
                <td class="colons">:</td>
                <td>
                    <?php
                    echo $user_infos['AdminModuleUserProfile']['div_name_in_office'];

                    if (!empty($user_infos['AdminModuleUserProfile']['div_name_in_office']) && !empty($user_infos['AdminModuleUserProfile']['org_name']))
                        echo ', ';

                    echo $user_infos['AdminModuleUserProfile']['org_name'];
                    ?>
                </td>
            </tr>
            <tr>
                <td>Mobile No.</td>
                <td class="colons">:</td>
                <td><?php echo $user_infos['AdminModuleUserProfile']['mobile_no']; ?></td>
            </tr>
            <tr>
                <td>E-mail</td>
                <td class="colons">:</td>
                <td><?php echo $user_infos['AdminModuleUserProfile']['email']; ?></td>
            </tr>                
        </table>
    </fieldset>

</div>

<?php } ?>
