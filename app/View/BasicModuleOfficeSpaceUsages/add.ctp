<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}

if (!empty($IsValidUser)) {
    $title = "Usage of Office Space (Add)";
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 'class' => 'mybtns',
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    ?> 

    <div>
        <fieldset>
            <legend><?php echo $title; ?></legend>         
    <?php echo $this->Form->create('BasicModuleOfficeSpaceUsage'); ?>

            <div class="form">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td>Name of Organization</td>
                        <td class="colons" style="padding-left: 110px;">:</td>
                        <td>
                            <?php
                            if (!empty($org_id))
                                echo $this->Form->input('org_id', array('type' => 'text', 'value' => $orgNameOptions[$org_id], 'disabled' => 'disabled', 'label' => false));
                            else
                                echo $this->Form->input('org_id', array('type' => 'select', 'options' => $orgNameOptions, 'value' => $org_id, 'empty' => '---Select---', 'label' => false));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Usage Type</td>
                        <td class="colons" style="padding-left: 110px;">:</td>
                        <td><?php echo $this->Form->input('usage_type_id', array('type' => 'select',  'id' => 'usage_types', 'options' => $usage_type_options, 'empty' => '---Select---', 'label' => false)); ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" id="rented">
                            <table cellpadding="0" cellspacing="0" border="0">                                
                                <tr>
                                    <td>Duration of proposed Rent Agreement</td>
                                    <td class="colons">:</td>
                                    <td><?php echo $this->Form->input('duration_of_proposed_rent_agreement', array('type' => 'text','label' => false)); ?></td>
                                </tr>
                                <tr>
                                    <td>Proposed monthly rent</td>
                                    <td class="colons">:</td>
                                    <td><?php echo $this->Form->input('proposed_monthly_rent', array('type' => 'text','label' => false)); ?></td>
                                </tr>
                            </table>
                        </td>                        
                    </tr>
                    <tr>
                        <td colspan="3" id="ownership">
                            <table cellpadding="0" cellspacing="0" border="0">                                
                                <tr>
                                    <td>Holding No.</td>
                                    <td class="colons" style="padding-left: 165px;">:</td>
                                    <td><?php echo $this->Form->input('holding_no', array('type' => 'text','label' => false)); ?></td>
                                </tr>
                                <tr>
                                    <td>Khatiyan No.</td>
                                    <td class="colons" style="padding-left: 165px;">:</td>
                                    <td><?php echo $this->Form->input('khatiyan_no', array('type' => 'text','label' => false)); ?></td>
                                </tr>
                                <tr>
                                    <td>District</td>
                                    <td class="colons" style="padding-left: 165px;">:</td>
                                    <td><?php echo $this->Form->input('district_id', array('type' => 'select', 'options' => $districtsOptions, 'id' => 'districts', 'empty' => '---Select---', 'label' => false)); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Upazila</td>
                                    <td class="colons" style="padding-left: 165px;">:</td>
                                    <td><?php echo $this->Form->input('upazila_id', array('type' => 'select', 'options' => 'null', 'id' => 'upazilas', 'empty' => '---Select---', 'label' => false)); ?></td>
                                </tr>
                                <tr>
                                    <td>Union</td>
                                    <td class="colons" style="padding-left: 165px;">:</td>
                                    <td><?php echo $this->Form->input('union_id', array('type' => 'select', 'options' => 'null', 'id' => 'unions', 'empty' => '---Select---', 'label' => false)); ?></td>
                                </tr>
                                <tr>
                                    <td>Mauza</td>
                                    <td class="colons" style="padding-left: 165px;">:</td>
                                    <td><?php echo $this->Form->input('mauza_id', array('type' => 'select', 'options' => 'null', 'id' => 'mauzas', 'empty' => '---Select---', 'label' => false)); ?></td>
                                </tr>                               
                            </table>
                        </td>
                    </tr>
                    
                </table>
            </div>

            <div class="btns-div"> 
                <table style="margin:0 auto; padding:0;" cellspacing="5">
                    <tr>
                        <td></td>
                        <td>
                            <?php
                            $data_mode = $this->Session->read('Data.Mode');
                            $isNew = empty($data_mode) || $data_mode == 'insert';

                            if ($isNew) {
                                echo $this->Js->submit('Save', array_merge($pageLoading, array('success' => "msg.init('success', '$title', '$title has been added successfully.');",
                                    'error' => "msg.init('error', '$title', '$title has been failed to add !');")));
                            } else {
                                echo $this->Js->submit('Update', array_merge($pageLoading, array('update' => '#popup_div',
                                    'success' => "msg.init('success', '$title', '$title has been update successfully.');",
                                    'error' => "msg.init('error', '$title', '$title has been failed to update !');")));
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $this->Js->link('Close', array('controller' => 'BasicModuleOfficeSpaceUsages', 'action' => 'view'), array_merge($pageLoading, array('confirm' => 'Are you sure to close ?')));
                            ?>
                        </td>
                        <td>
                            <?php
                            if (!$isNew) {
                                echo $this->Js->link('Next', array('controller' => 'BasicModuleOfficeSpaceUsages', 'action' => 'add'), array_merge($pageLoading, array('success' => 'msc.next();')));
                            }
                            ?>
                        </td>
                        <td></td>
                    </tr>
                </table>
            </div>

        <?php echo $this->Form->end(); ?>
        </fieldset>

    </div>

    <script>

        $(document).ready(function () {
            $('.integers').numeric({decimal: false, negative: false});
            $('.decimals').numeric({decimal: ".", negative: false});
        });

    </script>

    <?php
    $this->Js->get('#districts')->event('change', $this->Js->request(array(
                'controller' => 'BasicModuleOfficeSpaceUsages',
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
                'controller' => 'BasicModuleOfficeSpaceUsages',
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
                'controller' => 'BasicModuleOfficeSpaceUsages',
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
                'controller' => 'BasicModuleOfficeSpaceUsages',
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
                'controller' => 'BasicModuleOfficeSpaceUsages',
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

    $this->Js->get('#unions')->event('change', $this->Js->request(
        array('controller' => 'BasicModuleOfficeSpaceUsages', 'action' => 'update_mauza_select'), 
        array('update' => '#mauzas', 'async' => true, 'method' => 'post',
                'dataExpression' => true, 'data' => $this->Js->serializeForm(array('isForm' => true, 'inline' => true)))
    ));
    
    if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
        echo $this->Js->writeBuffer();
    ?>

<?php } ?>

<script type="text/javascript">
    jQuery('.creload').on('click', function() {
        var mySrc = $(this).prev().attr('src');
        var glue = '?';
        if(mySrc.indexOf('?')!=-1)  {
            glue = '&';
        }
        $(this).prev().attr('src', mySrc + glue + new Date().getTime());
        return false;
    });
    $(document).ready(function(){

        selectedVal = $( "#usage_types option:selected" ).val();
        var selected_value = parseInt(selectedVal)

        if(selectedVal==""){
           hide_all();
        }
        else{
            if (selected_value==1){
               show_rented();
            }
            else if(selected_value==2){
               show_ownership();
            }
            else {
               hide_all();
            }
        }

        $('#usage_types').change(function(){

            if ($(this).val()=="1"){
               show_rented();
            }
            else if($(this).val()=="2"){
               show_ownership();
            }
            else {
               hide_all();
            }
        });

        function show_rented() {
            $("#rented").show();                       
            $("#ownership").hide();
        }

        function show_ownership() {
            $("#rented").hide();                       
            $("#ownership").show();
        }

        function hide_all() {
            $("#rented").hide();
            $("#ownership").hide();
        }
    });
</script>
