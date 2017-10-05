<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}

if (!empty($IsValidUser)) {
    $title = "Other immovable property of the organization (Add)";
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 'class' => 'mybtns',
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    ?> 

    <div>
        <fieldset>
            <legend><?php echo $title; ?></legend>         
    <?php echo $this->Form->create('BasicModuleOtherImmovableProperty'); ?>

            <div class="form">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td>Name of Organization</td>
                        <td class="colons">:</td>
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
                        <td>Property Description</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('property_description', array('type' => 'text','label' => false)); ?></td>
                    </tr>                    
                    <tr>
                        <td>Date of Acquiring</td>
                        <td class="colons">:</td>
                        <td style="padding-left: 5px;">
                            <?php
                                echo $this->Form->input('date_of_acquiring', array('type' => 'hidden', 'id' => 'txt_date_of_acquiring_alt', 'label' => false))
                                     ." <input type='text' id='txt_date_of_acquiring' class='date_picker' />";
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Monetary Value</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('monetary_value', array('type' => 'text','label' => false)); ?></td>
                    </tr>
                    <tr>
                        <td>Property Size (decimal)</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('property_size', array('type' => 'text','label' => false)); ?></td>
                    </tr>                                
                    <tr>
                        <td>Holding No.</td>
                        <td class="colons" >:</td>
                        <td><?php echo $this->Form->input('holding_no', array('type' => 'text','label' => false)); ?></td>
                    </tr>
                    <tr>
                        <td>Khatiyan No.</td>
                        <td class="colons" >:</td>
                        <td><?php echo $this->Form->input('khatiyan_no', array('type' => 'text','label' => false)); ?></td>
                    </tr>
                    <tr>
                        <td>District</td>
                        <td class="colons" >:</td>
                        <td><?php echo $this->Form->input('district_id', array('type' => 'select', 'options' => $districtsOptions, 'id' => 'districts', 'empty' => '---Select---', 'label' => false)); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Upazila</td>
                        <td class="colons" >:</td>
                        <td><?php echo $this->Form->input('upazila_id', array('type' => 'select', 'options' => 'null', 'id' => 'upazilas', 'empty' => '---Select---', 'label' => false)); ?></td>
                    </tr>
                    <tr>
                        <td>Union</td>
                        <td class="colons" >:</td>
                        <td><?php echo $this->Form->input('union_id', array('type' => 'select', 'options' => 'null', 'id' => 'unions', 'empty' => '---Select---', 'label' => false)); ?></td>
                    </tr>
                    <tr>
                        <td>Mauza</td>
                        <td class="colons" >:</td>
                        <td><?php echo $this->Form->input('mauza_id', array('type' => 'select', 'options' => 'null', 'id' => 'mauzas', 'empty' => '---Select---', 'label' => false)); ?></td>
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
                            echo $this->Js->link('Close', array('controller' => 'BasicModuleOtherImmovableProperties', 'action' => 'view'), array_merge($pageLoading, array('confirm' => 'Are you sure to close ?')));
                            ?>
                        </td>
                        <td>
                            <?php
                            if (!$isNew) {
                                echo $this->Js->link('Next', array('controller' => 'BasicModuleOtherImmovableProperties', 'action' => 'add'), array_merge($pageLoading, array('success' => 'msc.next();')));
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
                'controller' => 'BasicModuleOtherImmovableProperties',
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
                'controller' => 'BasicModuleOtherImmovableProperties',
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
                'controller' => 'BasicModuleOtherImmovableProperties',
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
                'controller' => 'BasicModuleOtherImmovableProperties',
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
                'controller' => 'BasicModuleOtherImmovableProperties',
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
        array('controller' => 'BasicModuleOtherImmovableProperties', 'action' => 'update_mauza_select'), 
        array('update' => '#mauzas', 'async' => true, 'method' => 'post',
                'dataExpression' => true, 'data' => $this->Js->serializeForm(array('isForm' => true, 'inline' => true)))
    ));
    
    if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
        echo $this->Js->writeBuffer();
    ?>

<?php } ?>

<script>

        $(document).ready(function () {
            $('.integers').numeric({decimal: false, negative: false});
            $('.decimals').numeric({decimal: ".", negative: false});
        });
    
    $(function() {        
        $('#ui-datepicker-div').remove();
        $('#ui-datepicker-div').empty();        
        $('.date_picker').each(function() {
            $(this).datepicker({
                yearRange: 'c-5:c+5',
                dateFormat: 'dd-mm-yy',
                altField: '#' + this.id + '_alt',
                altFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                showOtherMonths: true,
                showOn: 'both',
                buttonImageOnly: true,
                buttonImage: 'img/calendar.gif',
                buttonText: 'Click to show the calendar'
            });
        });        
    });    
</script>