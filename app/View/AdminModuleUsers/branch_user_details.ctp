<div>
    <div id="userInfo" title="User Information" style="margin:0px; padding:10px; background-color:#fafdff;">
        <fieldset>
            <legend>
                Details
            </legend>
            <div class="form" style="max-height:430px; overflow-y:auto; color:#232428;">
                <table cellpadding="7" cellspacing="8" border="0">   
                    <tr>
                        <td>User Name</td>
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
                        <td>Date of Last Modification</td>
                        <td class="colons">:</td>
                        <td><?php echo $user_info_details['AdminModuleUser']['modified_date']; ?></td>
                    </tr>
                    <tr>
                        <td>Activation Status</td>
                        <td class="colons">:</td>
                        <td>
                            <?php                            
                                if($user_info_details['AdminModuleUser']['activation_status_id']==1){
                                    echo 'Active';
                                }
                                else{
                                    echo 'Inactive';
                                }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        </fieldset>
    </div>
    <script>
        $(function() {
            $( "#userInfo" ).dialog({
                modal: true, minWidth: 650,
                buttons: {
                    Close: function() {
                        $( this ).dialog( "close" );
                    }
                }
            });
        });
    </script>
</div>