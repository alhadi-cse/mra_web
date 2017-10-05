
<?php echo $this->Session->flash(); ?>

<div>
    <?php
    $title = "User information";
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading);
    if (!empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }
    ?>
    <fieldset>
        <legend><?php echo "$title Edit"; ?></legend> 

        <?php echo $this->Form->create('AdminModuleUser'); ?>
        <div class="form">
            <table style="width:80%; min-width:640px;" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td style="width:175px;">User Id</td>
                    <td class="colons">:</td>
                    <td style="padding-left:5px;"><?php echo $user_name; ?></td>
                </tr>
                <?php
                echo $this->Form->input('AdminModuleUser.created_date', array('type' => 'hidden', 'value' => "'" . date('Y-m-d H:i:s') . "'", 'label' => false));
                echo $this->Form->input('AdminModuleUser.activation_status_id', array('type' => 'hidden', 'value' => '0', 'label' => false));
                if(!empty($created_date)) { ?>
                <tr>
                    <td>Date of Creation</td>
                    <td class="colons">:</td>
                    <td style="padding-left:5px;">
                        <?php echo $created_date;?>
                    </td>
                </tr>
                <?php } if(!empty($created_by)) { ?>
                <tr>
                    <td>Created By</td>
                    <td class="colons">:</td>
                    <td style="padding-left:5px;"><?php echo $created_by; ?></td>
                </tr>
                <?php } ?>
                <tr id="common_basic">
                    <td colspan="3">
                        <table style="width:100%;" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td style="width:175px;">User Group</td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('AdminModuleUserGroupDistribution.user_group_id', array('type' => 'select', 'id' => 'group_names', 'class' => 'medium', 'options' => $user_group_options, 'empty' => '-----Select-----', 'label' => false)); ?></td>                    
                            </tr>
                            <tr>
                                <td>E-Mail</td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('AdminModuleUserProfile.email', array('type' => 'text', 'class' => 'medium required', 'label' => false)); ?></td>
                            </tr>
                            <tr>
                                <td>Mobile No.</td>
                                <td class="colons">:</td>
                                <td><?php
                                    echo $this->Form->input('AdminModuleUserProfile.mobile_no', array('type' => 'text', 'class' => 'medium integers required', 'label' => false));
                                    echo $this->Form->input('AdminModuleUser.created_date', array('type' => 'hidden', 'value' => "'" . date('Y-m-d H:i:s') . "'", 'label' => false));
                                    echo $this->Form->input('AdminModuleUser.activation_status_id', array('type' => 'hidden', 'value' => '1', 'label' => false));
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr id="only_mfi_branch">
                    <td colspan="3">
                        <table style="width:100%;" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td style="width:175px;">Name of MFI</td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('AdminModuleUserProfile.org_id', array('type' => 'select', 'id' => 'organizations', 'class' => 'medium required', 'options' => $org_name_options, 'empty' => '-----Select-----', 'label' => false)); ?></td>
                            </tr>
                            <tr>
                                <td>Branch</td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('AdminModuleUserProfile.branch_id', array('type' => 'select', 'id' => 'branches', 'class' => 'medium required', 'options' => 'null', 'empty' => '-----Select-----', 'label' => false)); ?></td>
                            </tr>                    
                        </table>
                    </td>
                </tr>
                <tr id="only_committee">
                    <td colspan="3" style=" text-align: left;">
                        <table style="width:100%;" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td style="width:175px;">Committee Member Type</td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('AdminModuleUser.committe_member_type_id', array('type' => 'select', 'class' => 'medium required', 'options' => $committe_member_type_options, 'empty' => '-----Select-----', 'label' => false)); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr id="only_mfi">
                    <td colspan="3">
                        <table style="width:100%;" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td style="width:175px;">Short Name of MFI</td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('BasicModuleBasicInformation.short_name_of_org', array('type' => 'text', 'class' => 'medium required', 'label' => false)); ?></td>
                            </tr>
                            <tr>
                                <td>Full Name of MFI</td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('BasicModuleBasicInformation.full_name_of_org', array('type' => 'text', 'class' => 'medium required', 'label' => false)); ?></td>
                            </tr>
                            <tr>
                                <td>Primary Registration</td>
                                <td class="colons">:</td>
                                <td>
                                    <div style="margin:0; padding:2px;">
                                        <?php
                                        if (!empty($primary_reg_act_options)) {
                                            echo $this->Form->input("PrimaryRegistrationActInfo.primary_reg_act_id", array('id' => 'PrimaryRegActId', 'type' => 'select', 'multiple' => 'checkbox', 'class' => 'multi-inline-checkbox', 'options' => $primary_reg_act_options, 'escape' => false, 'div' => false, 'label' => false));
                                        }
                                        ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align:top;">Address</td>
                                <td class="colons" style="vertical-align:top;">:</td>
                                <td style="vertical-align:top;">
                                    <?php echo $this->Form->input('BasicModuleBasicInformation.address_of_org', array('type' => 'textarea', 'class' => 'medium', 'escape' => false, 'rows' => '5', 'cols' => '5', 'label' => false)); ?>
                                </td>    
                            </tr>                              
                            <tr id="only_mfi_licensed">
                                <td style="width:175px;">License No.</td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('BasicModuleBasicInformation.license_no', array('type' => 'text', 'class' => 'medium', 'label' => false)); ?></td>
                            </tr>
                            <tr>
                                <td>Name of Authorized Person</td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('BasicModuleBasicInformation.name_of_authorized_person', array('type' => 'text', 'class' => 'medium', 'label' => false)); ?></td>
                            </tr>
                            <tr>
                                <td>Designation</td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('BasicModuleBasicInformation.designation_of_authorized_person', array('type' => 'text', 'class' => 'medium', 'label' => false)); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr id="only_cdb_non_mfi">
                    <td colspan="3">
                        <table style="width:100%;" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td style="width:175px;">Name of Organization</td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('CDBNonMfiBasicInfo.name_of_org', array('type' => 'text', 'class' => 'medium required', 'label' => false)); ?></td>
                            </tr>                                                       
                            <tr>
                                <td style="vertical-align:top;">Regulatory Ministry</td>
                                <td class="colons" style="vertical-align:top;">:</td>                                
                                <td><?php echo $this->Form->input('CDBNonMfiBasicInfo.regulatory_ministry_id', array('type' => 'select', 'class' => 'medium', 'options' => $regulatory_ministry_options, 'empty' => '-----Select-----', 'label' => false)); ?></td>
                            </tr>                              
                            <tr>
                                <td style="width:175px;">Registration No.</td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('CDBNonMfiBasicInfo.registration_no', array('type' => 'text', 'class' => 'medium', 'label' => false)); ?></td>
                            </tr>
                            <tr>
                                <td>Name of Officer</td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('CDBNonMfiBasicInfo.name_of_officer', array('type' => 'text', 'class' => 'medium', 'label' => false)); ?></td>
                            </tr>
                            <tr>
                                <td>Designation</td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('CDBNonMfiBasicInfo.designation_of_officer', array('type' => 'text', 'class' => 'medium', 'label' => false)); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>                
                <tr id="common_other">
                    <td colspan="3">
                        <table style="width:100%;" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td style="width:175px;">Full Name of User</td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('AdminModuleUserProfile.full_name_of_user', array('type' => 'text', 'class' => 'medium required', 'label' => false)); ?></td>
                            </tr>
                            <tr>
                                <td>Designation of User</td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('AdminModuleUserProfile.designation_of_user', array('type' => 'text', 'class' => 'medium required', 'label' => false)); ?></td>
                            </tr>
                            <tr>
                                <td>Dept. in Office</td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('AdminModuleUserProfile.div_name_in_office', array('type' => 'text', 'class' => 'medium', 'label' => false)); ?></td>
                            </tr>
                            <tr id="only_mra">
                                <td>Name of Organization</td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('AdminModuleUserProfile.org_name', array('type' => 'text', 'class' => 'medium', 'label' => false)); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr id="captcha">
                    <td colspan="2"></td>
                    <td>
                        <div class="captcha medium">
                            <?php $this->Captcha->render(); ?>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php
                        echo $this->Js->link('Close', array('controller' => 'AdminModuleUsers', 'action' => 'view'), array_merge($pageLoading, array('class' => 'mybtns')));
                        ?> 
                    </td>
                    <td style="text-align:center;">
                        <?php
                        echo $this->Js->submit('Update', array_merge($pageLoading, array('success' => "msg.init('success', '$title', '$title has been updated successfully.');",
                            'error' => "msg.init('error', '$title', '$title update failed!');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div> 
        <?php echo $this->Form->end(); ?>
    </fieldset>    
</div>


<script type="text/javascript">

    $(document).ready(function () {
        $('.integers').numeric({decimal: false, negative: false});
        $('.decimals').numeric({decimal: ".", negative: false});

        //show_hide_all(true);
        show_hide_object($('#group_names').val());

        $('input.required, select.required').closest("div").addClass('required');
        $('#group_names').change(function () {
            show_hide_object($(this).val());
        });

        $('.clabel').closest("div").addClass('required');
        $('.creload').on('click', function () {
            $capcha = $(this).prev("img");
            $mySrc = $capcha.attr('src');
            $mySrc = $mySrc +
                    (($mySrc.indexOf('?') > -1) ? '&' : '?') +
                    new Date().getTime();
            $capcha.attr('src', $mySrc);
            return false;
        });
    });

    function show_hide_object(user_group_id) {

        show_hide_all(false);

        user_group_id = parseInt(user_group_id);
        if (!user_group_id || user_group_id < 1)
            return;

        $("#common_basic").show();
        $("#common_other").show();
        $("#only_mra").show();

        $("#captcha").show();
        $("#buttons").show();

        switch (user_group_id)
        {
           case 1:                
                $("#common_basic").show();
                $("#common_other").show();               
                break;
            case 2:
                $("#only_mfi").show();
                $("#only_mfi_licensed").show();
                $("#common_other").hide();
                break;
            case 3:                
                $("#only_mfi_branch").show();
                $("#only_mra").hide();               
                break;    
            case 4:
                $("#only_cdb_non_mfi").show();                
                $("#common_other").hide();
                break;
            case 5:
                $("#only_mfi").show();
                $("#common_other").hide();
                break;
            
            case 11:
            case 12:
            case 13:
            case 14:
            case 100:
            case 200:
            case 201:
                $("#only_committee").show();
                break;

            default :
                break;
        }

        return;
    }

    function show_hide_all(opt) {
        if (opt) {
            $("#common_basic").show();
            $("#common_other").show();

            $("#only_mra").show();
            $("#only_mfi").show();
            $("#only_mfi_branch").show();
            $("#only_mfi_licensed").show();
            $("#only_committee").show();
            $("#only_cdb_non_mfi").show();

            $("#captcha").show();
            $("#buttons").show();
        } else {
            $("#common_basic").hide();
            $("#common_other").hide();

            $("#only_mra").hide();
            $("#only_mfi").hide();
            $("#only_mfi_branch").hide();
            $("#only_mfi_licensed").hide();
            $("#only_cdb_non_mfi").hide();            
            $("#only_committee").hide();
            
            $("#captcha").hide();
            $("#buttons").hide();
        }
    }

</script>

<script type="text/javascript">

    jQuery('.creload').on('click', function () {
        var mySrc = $(this).prev().attr('src');
        var glue = '?';
        if (mySrc.indexOf('?') != -1) {
            glue = '&';
        }
        $(this).prev().attr('src', mySrc + glue + new Date().getTime());
        return false;
    });

    $(document).ready(function () {
        $('.integers').numeric({decimal: false, negative: false});
        $('.decimals').numeric({decimal: ".", negative: false});

        selectedVal = $("#group_names option:selected").val();
        var selected_value = parseInt(selectedVal)

        if (selectedVal == "") {
            hide_all();
        } else {
            if (selected_value == 2) {
                show_mfi();
            } else {
                hide_mfi();
            }
            if (selected_value > 10) {
                show_committee_memeber_type();
            } else {
                hide_committee_memeber_type();
            }
        }

        $('#group_names').change(function () {

            if ($(this).val() == "2") {
                show_mfi();
            } else {
                hide_mfi();
            }

            var group_id = parseInt($(this).val())
            if (group_id > 10) {
                show_committee_memeber_type();
            } else {
                hide_committee_memeber_type();
            }
        });

        function show_mfi() {
            $("#only_mfi").show();
            $("#other").hide();
        }
        function hide_mfi() {
            $("#only_mfi").hide();
            $("#other").show();
        }
        function hide_all() {
            $("#only_mfi").hide();
            $("#other").hide();
            $("#only_committee").hide();
        }
        function show_committee_memeber_type() {
            $("#only_committee").show();
        }
        function hide_committee_memeber_type() {
            $("#only_committee").hide();
        }
    });
</script>