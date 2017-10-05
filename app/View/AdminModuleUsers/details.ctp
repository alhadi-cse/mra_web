
<?php ?>

<div>
    <div id="userInfo" title="User Information" style="margin:0px; padding:10px; background-color:#fafdff;">
        <fieldset>
            <legend>
                User Information Details
            </legend>
            <div class="form" style="max-height:430px; overflow-y:auto; color:#232428;">
                <table style="width:90%;" cellpadding="7" cellspacing="8" border="0">
                    <tr>
                        <td style="width:130px;">User Name</td>
                        <td class="colons">:</td>
                        <td><?php echo $user_info_details['AdminModuleUser']['user_name']; ?></td>
                    </tr>
                    <tr>
                        <td>User Group</td>
                        <td class="colons">:</td>
                        <td><?php echo $user_info_details['AdminModuleUserGroup']['group_name']; ?></td>
                    </tr>
                    <tr>
                        <td>Full Name</td>
                        <td class="colons">:</td>
                        <td><?php echo $user_info_details['AdminModuleUserProfile']['full_name_of_user']; ?></td>
                    </tr>
                    <tr>
                        <td>Designation</td>
                        <td class="colons">:</td>
                        <td><?php echo $user_info_details['AdminModuleUserProfile']['designation_of_user']; ?></td>
                    </tr>
                    <tr>
                        <td>Division</td>
                        <td class="colons">:</td>
                        <td><?php echo $user_info_details['AdminModuleUserProfile']['div_name_in_office']; ?></td>
                    </tr>
                    <tr>
                        <td>Organization</td>
                        <td class="colons">:</td>
                        <td><?php echo $user_info_details['AdminModuleUserProfile']['org_name']; ?></td>
                    </tr>
                    <tr>
                        <td>Mobile No.</td>
                        <td class="colons">:</td>
                        <td><?php echo $user_info_details['AdminModuleUserProfile']['mobile_no']; ?></td>
                    </tr>
                    <tr>
                        <td>E-Mail</td>
                        <td class="colons">:</td>
                        <td><?php echo $user_info_details['AdminModuleUserProfile']['email']; ?></td>
                    </tr>
                    <tr>
                        <td>Created By</td>
                        <td class="colons">:</td>
                        <td><?php echo $user_info_details['AdminModuleUser']['created_by']; ?></td>
                    </tr>
                    <tr>
                        <td>Date of Creation</td>
                        <td class="colons">:</td>
                        <td><?php echo $user_info_details['AdminModuleUser']['created_date']; ?></td>
                    </tr>
                    <tr>
                        <td>Last Modified By</td>
                        <td class="colons">:</td>
                        <td><?php echo $user_info_details['AdminModuleUser']['modified_by']; ?></td>
                    </tr>
                    <tr>
                        <td>Last Modification</td>
                        <td class="colons">:</td>
                        <td><?php echo $user_info_details['AdminModuleUser']['modified_date']; ?></td>
                    </tr>
                    <tr>
                        <td>
                            <?php
                            $colums_name = '';
                            if ($is_committee_group == '0') {
                                $colums_name = 'Activation Status';
                            } else if ($is_committee_group == '1') {
                                $colums_name = 'Member Type';
                            }
                            echo $colums_name;
                            ?>
                        </td>
                        <td class="colons">:</td>
                        <td>
                            <?php
                            if ($is_committee_group == '0') {
                                if ($user_info_details['AdminModuleUser']['activation_status_id'] == 1) {
                                    echo 'Active';
                                } else {
                                    echo 'Inactive';
                                }
                            } else if ($is_committee_group == '1') {
                                $member_type_title = $user_info_details['LookupUserCommitteeMemberType']['committee_member_type'];
                                echo $member_type_title;
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        </fieldset>
    </div>
    <script>
        $(function () {
            $("#userInfo").dialog({
                modal: true, minWidth: 870,
                buttons: {
                    Close: function () {
                        $(this ).dialog("close");
                    }
                }
            });
        });
    </script>
</div>