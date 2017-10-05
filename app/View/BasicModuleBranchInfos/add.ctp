<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}

if (!empty($IsValidUser)) {
    $title = "Add Office Information";
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 'class' => 'mybtns',
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    ?>

    <div>
        <fieldset>
            <legend><?php echo $title; ?></legend>       
            <div class="form">
                <?php echo $this->Form->create('BasicModuleBranchInfo'); ?>
                <table cellpadding="0" cellspacing="0" border="0"> 
                    <tr>
                        <td>Name of Organization</td>
                        <td class="colons">:</td>
                        <td style="padding:15px 5px;">
                            <?php
                            if (!empty($org_id))
                                echo $orgNameOptions[$org_id] . $this->Form->input('org_id', array('type' => 'hidden', 'value' => $org_id, 'label' => false));
                            else
                                echo $this->Form->input('org_id', array('type' => 'select', 'options' => $orgNameOptions, 'value' => $org_id, 'empty' => '---Select---', 'escape' => false, 'label' => false));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Office Type</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('office_type_id', array('type' => 'select', 'id' => 'office_types', 'options' => $officeTypeOptions, 'empty' => '---Select---', 'label' => false)); ?>
                        </td>
                    </tr>                
                    <tr>
                        <td>Office Code</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('branch_code', array('id' => 'branch_codes', 'label' => false)); ?></td>
                    </tr>
                    <tr id="office_name">
                        <td>Office Name</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('branch_name', array('id' => 'branch_names', 'label' => false)); ?></td>
                    </tr>
                    <tr>
                        <td style="padding-top:8px; vertical-align:top;">Mailing Address</td>
                        <td class="colons" style="padding-top:8px; vertical-align:top;">:</td>
                        <td style="vertical-align:top;">
                            <?php echo $this->Form->input('mailing_address', array('type' => 'textarea', 'escape' => false, 'rows' => '5', 'cols' => '5', 'label' => false)); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>District</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('district_id', array('type' => 'select', 'options' => $districtsOptions, 'id' => 'districts', 'empty' => '---Select---', 'label' => false)); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Upazila</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('upazila_id', array('type' => 'select', 'options' => 'null', 'id' => 'upazilas', 'empty' => '---Select---', 'label' => false)); ?></td>
                    </tr>
                    <tr>
                        <td>Union</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('union_id', array('type' => 'select', 'options' => 'null', 'id' => 'unions', 'empty' => '---Select---', 'label' => false)); ?></td>
                    </tr>
                    <tr>
                        <td>Mauza</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('mauza_id', array('type' => 'select', 'options' => 'null', 'id' => 'mauzas', 'empty' => '---Select---', 'label' => false)); ?></td>
                    </tr>
                    <tr>
                        <td>Mahalla/Post Office</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('mohalla_or_post_office', array('label' => false)); ?></td>
                    </tr>
                    <tr>
                        <td>Road Name/Village</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('road_name_or_village', array('label' => false)); ?></td>
                    </tr>
                    <tr>
                        <td>Mobile no.</td>
                        <td class="colons">:</td>
                        <td id="mobile_td"><?php echo $this->Form->input('mobile_no', array('label' => false)); ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" id="head_office">
                            <table cellpadding="0" cellspacing="0" border="0">                     
                                <tr>
                                    <td>Phone no.</td>
                                    <td class="colons" style="padding-left: 78px;">:</td>
                                    <td><?php echo $this->Form->input('phone_no', array('label' => false)); ?></td>
                                </tr>                            
                                <tr>
                                    <td>Fax</td>
                                    <td class="colons" style="padding-left: 78px;">:</td>
                                    <td><?php echo $this->Form->input('fax', array('label' => false)); ?></td>
                                </tr>
                                <tr>
                                    <td>E-mail</td>
                                    <td class="colons" style="padding-left: 78px;">:</td>
                                    <td id="email_td"><?php echo $this->Form->input('email_address', array('label' => false)); ?></td>
                                </tr>
                            </table>
                        </td>                        
                    </tr>                
                    <tr>
                        <td>Latitude</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('lat', array('type' => 'text', 'label' => false, 'class' => 'decimals')); ?></td>
                    </tr>
                    <tr>
                        <td>Longitude</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('long', array('type' => 'text', 'label' => false, 'class' => 'decimals')); ?></td>
                    </tr>
                </table>

                <div class="btns-div"> 
                    <table style="margin:0 auto; padding:0;" cellspacing="5">
                        <tr>
                            <td></td>
                            <td><?php echo $this->Js->link('Back to Office List', array('controller' => 'BasicModuleBranchInfos', 'action' => 'view'), $pageLoading); ?></td>
                            <td><?php echo $this->Js->submit('Save & Next', $pageLoading); ?></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>         
        </fieldset>
    </div>


    <?php
    $this->Js->get('#districts')->event('change', $this->Js->request(array(
                'controller' => 'BasicModuleBranchInfos',
                'action' => 'update_upazila_select'
                    ), array(
                'update' => '#upazilas',
                'async' => true,
                'method' => 'post',
                'dataExpression' => true,
                'data' => $this->Js->serializeForm(array(
                    'isForm' => true,
                    'inline' => true
                ))
            ))
    );
    $this->Js->get('#districts')->event('change', $this->Js->request(array(
                'controller' => 'BasicModuleBranchInfos',
                'action' => 'update_union_select'
                    ), array(
                'update' => '#unions',
                'async' => true,
                'method' => 'post',
                'dataExpression' => true,
                'data' => $this->Js->serializeForm(array(
                    'isForm' => true,
                    'inline' => true
                ))
            ))
    );
    $this->Js->get('#districts')->event('change', $this->Js->request(array(
                'controller' => 'BasicModuleBranchInfos',
                'action' => 'update_mauza_select'
                    ), array(
                'update' => '#mauzas',
                'async' => true,
                'method' => 'post',
                'dataExpression' => true,
                'data' => $this->Js->serializeForm(array(
                    'isForm' => true,
                    'inline' => true
                ))
            ))
    );
    $this->Js->get('#upazilas')->event('change', $this->Js->request(array(
                'controller' => 'BasicModuleBranchInfos',
                'action' => 'update_union_select'
                    ), array(
                'update' => '#unions',
                'async' => true,
                'method' => 'post',
                'dataExpression' => true,
                'data' => $this->Js->serializeForm(array(
                    'isForm' => true,
                    'inline' => true
                ))
            ))
    );
    $this->Js->get('#upazilas')->event('change', $this->Js->request(array(
                'controller' => 'BasicModuleBranchInfos',
                'action' => 'update_mauza_select'
                    ), array(
                'update' => '#mauzas',
                'async' => true,
                'method' => 'post',
                'dataExpression' => true,
                'data' => $this->Js->serializeForm(array(
                    'isForm' => true,
                    'inline' => true
                ))
            ))
    );
    $this->Js->get('#unions')->event('change', $this->Js->request(array(
                'controller' => 'BasicModuleBranchInfos',
                'action' => 'update_mauza_select'
                    ), array(
                'update' => '#mauzas',
                'async' => true,
                'method' => 'post',
                'dataExpression' => true,
                'data' => $this->Js->serializeForm(array(
                    'isForm' => true,
                    'inline' => true
                ))
            ))
    );
    ?>

    <?php
    if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
        echo $this->Js->writeBuffer();
    ?>

<?php } ?>

<script>
    $(function () {
        $('.integers').numeric({decimal: false, negative: false});
        $('.decimals').numeric({decimal: ".", negative: false});

        var selectedVal = $("#office_types option:selected").val();
        var selected_value = parseInt(selectedVal);
        //var office_type_id = <?php //echo json_encode($office_type_id);                   ?>;
        if (selected_value == 1) {
            show_head_office();
        } else {
            hide_all();
        }

        $('#office_types').change(function () {
            if ($(this).val() == "1") {
                show_head_office();
                $("#branch_names").val("Head Office");
            } else {
                $("#branch_names").val("");
                hide_all();
            }
        });
    });

    function show_head_office() {
        $("#head_office").show();
        $("#office_name").hide();
        $("#email_td").children("div").addClass("required");
    }

    function hide_all() {
        $("#head_office").hide();
        $("#office_name").show();
    }
</script>
