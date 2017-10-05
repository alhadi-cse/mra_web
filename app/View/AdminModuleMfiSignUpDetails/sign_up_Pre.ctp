<?php echo $this->Session->flash(); ?>
<div class="logininfo" style="width:650px;">
    <?php
    $title = "Sign Up";
    $pageloading = array('update' => '#content', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    if (!empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }
    ?>

    <div>
        <div class="logheader">
            <?php echo $title; ?>
        </div>

        <fieldset>
            <?php echo $this->Form->create('AdminModuleMfiSignUpDetail'); ?>
            <div class="form">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td>Type of Organization</td>
                        <td class="colons" style="padding-left: 48px;">:</td>
                        <td><?php echo $this->Form->input('org_type_id', array('type' => 'select', 'options' => $org_type_options, 'id' => 'org_types', 'empty' => '---Select---', 'label' => false)); ?>
                    </tr>
                    <tr>
                        <td colspan="3" id="common_name">
                            <table cellpadding="0" cellspacing="0" border="0">                                
                                <tr>
                                    <td>Name of NGO-MFI</td>
                                    <td class="colons" style="padding-left: 53px;">:</td>
                                    <td><?php echo $this->Form->input('full_name_of_org', array('type' => 'text', 'label' => false)); ?></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:top; padding: 7px 0px;">Primary Registration</td>
                                    <td class="colons" style="vertical-align:top; padding: 7px 0px 7px 50px;">:</td>
                                    <td style="vertical-align:top; max-width: 350px; padding: 7px 0px 7px 5px;">
                                        <?php if (!empty($primary_reg_act_options)) {
                                            foreach ($primary_reg_act_options as $key => $value):
                                                ?>
                                                <input type="checkbox" name="data[BasicModulePrimaryRegActDetail][primary_reg_act_id][]" value="<?php echo $key; ?>" id="PrimaryRegActId<?php echo $key; ?>" /><label for="PrimaryRegActId<?php echo $key; ?>">&nbsp;<?php echo $value; ?>&nbsp;&nbsp;&nbsp;&nbsp;</label>
    <?php endforeach;
} ?>                              
                                    </td>    
                                </tr>
                            </table>
                        </td>                        
                    </tr>
                    <tr>
                        <td colspan="3" id="new_mfi">
                            <table cellpadding="0" cellspacing="0" border="0">                                
                                <tr>
                                    <td>District</td>
                                    <td class="colons" style="padding-left: 135px;">:</td>
                                    <td><?php echo $this->Form->input('district_id', array('type' => 'select', 'options' => $district_options, 'id' => 'districts', 'empty' => '---Select---', 'label' => false)); ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" id="licensed_mfi">
                            <table cellpadding="0" cellspacing="0" border="0">                                                                
                                <tr>
                                    <td>License No.</td>
                                    <td class="colons" style="padding-left: 105px;">:</td>
                                    <td><?php echo $this->Form->input('license_no', array('type' => 'text', 'label' => false)); ?></td>
                                </tr>                        
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" id="common_others">
                            <table cellpadding="0" cellspacing="0" border="0">                              
                                <tr>
                                    <td style="vertical-align:top;">Address</td>
                                    <td class="colons" style="vertical-align:top;">:</td>
                                    <td style="vertical-align:top;">
<?php echo $this->Form->input('address_of_org', array('type' => 'textarea', 'escape' => false, 'rows' => '5', 'cols' => '5', 'label' => false)); ?>
                                    </td>    
                                </tr>                                
                                <tr>
                                    <td>Name of Authorized Person</td>
                                    <td class="colons">:</td>
                                    <td><?php echo $this->Form->input('name_of_authorized_person', array('type' => 'text', 'label' => false)); ?></td>
                                </tr>
                                <tr>
                                    <td>Designation</td>
                                    <td class="colons">:</td>
                                    <td><?php echo $this->Form->input('designation_of_authorized_person', array('type' => 'text', 'label' => false)); ?></td>
                                </tr>
                                <tr>
                                    <td>Mobile No.</td>
                                    <td class="colons">:</td>
                                    <td><?php echo $this->Form->input('mobile_no', array('type' => 'text', 'label' => false)); ?></td>
                                </tr>
                                <tr>
                                    <td>Fax</td>
                                    <td class="colons">:</td>
                                    <td><?php echo $this->Form->input('fax_no', array('type' => 'text', 'label' => false)); ?></td>
                                </tr>
                                <tr>
                                    <td>E-Mail</td>
                                    <td class="colons">:</td>
                                    <td>
                                        <?php
                                        echo $this->Form->input('email', array('type' => 'text', 'label' => false));
                                        echo $this->Form->input('approval_status', array('value' => 0, 'type' => 'hidden', 'label' => false));
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="padding:2px 10px 2px 180px;"><?php $this->Captcha->render(); ?></td>
                                </tr>
                            </table>
                        </td>                        
                    </tr>                    
                </table>
            </div>        
            <div class="btns-div" id="buttons"> 
                <table style="margin:0 auto; padding:5px;" cellspacing="7">
                    <tr>
                        <td style="text-align: center;">
                            <?php
                            echo $this->Js->submit('Save', array_merge($pageloading, array('success' => "msg.init('success', '$title', 'You have Signed Up Successfully.');",
                                'error' => "msg.init('error', '$title', 'Sign Up failed!');")));
                            ?>
                        </td>
                        <td>
<?php echo $this->Js->link('Close', array('controller' => 'AdminModuleUsers', 'action' => 'login'), array_merge($pageloading, array('update' => '#main_content', 'class' => 'mybtns'))); ?>
                        </td>
                    </tr>
                </table>
            </div>
<?php echo $this->Form->end(); ?>
        </fieldset>
    </div>
</div>

<?php
if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
    echo $this->Js->writeBuffer();
?>

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

        selectedVal = $("#org_types option:selected").val();
        var selected_value = parseInt(selectedVal)

        if (selectedVal == "") {
            hide_all();
        } else {
            if (selected_value == 1) {
                show_new_mfi();
            } else if (selected_value == 2) {
                show_licensed_mfi();
            } else {
                hide_all();
            }
        }

        $('#org_types').change(function () {

            if ($(this).val() == "1") {
                show_new_mfi();
            } else if ($(this).val() == "2") {
                show_licensed_mfi();
            } else {
                hide_all();
            }
        });

        function show_new_mfi() {
            $("#new_mfi").show();
            $("#buttons").show();
            $("#common_name").show();
            $("#common_others").show();
            $("#licensed_mfi").hide();
        }

        function show_licensed_mfi() {
            $("#licensed_mfi").show();
            $("#buttons").show();
            $("#common_name").show();
            $("#common_others").show();
            $("#new_mfi").hide();
        }

        function hide_all() {
            $("#new_mfi").hide();
            $("#licensed_mfi").hide();
            $("#buttons").hide();
            $("#common_name").hide();
            $("#common_others").hide();
        }
    });
</script>