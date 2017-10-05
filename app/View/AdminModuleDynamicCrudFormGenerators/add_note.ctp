
<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}

if (!empty($field_details_list)) {
    $noteLoading = array('update' => '#note_content', 'evalScripts' => true, 'class' => 'mybtns', //'my-btns',
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    ?>

    <fieldset>
        <legend><?php echo $title; ?></legend>

        <?php echo $this->Form->create($note_model_name); ?>
        <div style="max-height:475px; overflow-y:auto;">
            <?php
            $sl_no = 0;

            foreach ($field_details_list as $group_id => $group_field_list) {
                $legend_group = empty($legend_groups[$group_id]) ? false : $legend_groups[$group_id];
                echo $legend_group ? "<fieldset><legend>" . $legend_group . "</legend>" : '';

                foreach ($group_field_list as $sub_group_id => $field_list) {
                    $legend_sub_group = empty($legend_sub_groups[$group_id][$sub_group_id]) ? false : $legend_sub_groups[$group_id][$sub_group_id];
                    echo $legend_sub_group ? "<fieldset><legend>" . $legend_sub_group . "</legend>" : '';
                    ?>

                    <table style="width:90%;" cellpadding="5" cellspacing="7" border="0"> 
                        <?php
                        $input_control = "";
                        foreach ($field_list as $field_id => $field_details) {

                            $colon = ':';
                            $colon_style = "style='width:5px; padding:5px 0; font-weight:bold; vertical-align:middle;'";
                            $field_label_style = 'padding:5px 0; vertical-align:middle;';

                            $field_name = $field_details['field_name'];
                            $field_label = $field_details['field_label'];
                            $data_type = $field_details['data_type'];
                            $control_type = $field_details['control_type'];

                            $control_id = $field_name;

                            $date_value = $field_details['date_value'];
                            $current_date = $field_details['current_date'];
                            $field_value_from_session = $field_details['field_value_from_session'];

                            $tr_style = '';
                            $label = $field_details['label'];
                            $options = $field_details['options'];

                            switch ($control_type) {
                                case 'text':
                                    switch ($data_type) {
                                        case 'varchar':
                                            $input_control = $this->Form->input($field_name, array('id' => $field_name, 'type' => $control_type, 'label' => false, 'div' => false));
                                            break;
                                        case 'int':
                                            $input_control = $this->Form->input($field_name, array('id' => $field_name, 'type' => $control_type, 'class' => "integers", 'style' => 'width:100px; text-align:right;', 'label' => false, 'div' => false));
                                            break;
                                        case 'double':
                                            $input_control = $this->Form->input($field_name, array('id' => $field_name, 'type' => $control_type, 'class' => "decimals", 'style' => 'width:100px; text-align:right;', 'label' => false, 'div' => false));
                                            break;
                                        default:
                                            break;
                                    }
                                    break;

                                case 'textarea':
                                    $colon_style = "style='width:5px; padding:10px 0; font-weight:bold; vertical-align:top;'";
                                    $field_label_style = 'padding:10px 0 0 0; vertical-align:top;';
                                    $input_control = $this->Form->input($field_name, array('type' => $control_type, 'id' => $field_name, 'escape' => false, 'label' => false, 'div' => false));
                                    break;

                                case 'select':
                                    $input_control = $this->Form->input($field_name, array('type' => $control_type, 'options' => $options, 'id' => $control_id, 'empty' => '---Select---', 'label' => false, 'div' => false));
                                    break;

                                case 'select_or_label':
                                    if (!empty($field_value_from_session)) {
                                        $value = !empty($options[$field_value_from_session]) ? $options[$field_value_from_session] : '';
                                        $input_control = "<label id='$control_id' class='label_disabled'>$value</label>"
                                                . $this->Form->input($field_name, array('type' => 'hidden', 'id' => $field_name, 'value' => $field_value_from_session, 'label' => false, 'div' => false));
                                    } else {
                                        $input_control = $this->Form->input($field_name, array('type' => 'select', 'options' => $options, 'id' => $control_id, 'empty' => '---Select---', 'label' => false, 'div' => false));
                                    }
                                    break;

                                case 'label':
                                    $input_control = $this->Form->input($field_name, array('type' => 'text', 'id' => $field_name, 'value' => $label, 'disabled' => 'disabled', 'label' => false, 'div' => false));
                                    break;

                                case 'radio':
                                    $input_control = $this->Form->input($field_name, array('type' => $control_type, 'id' => $field_name, 'options' => $options, 'label' => false, 'div' => false, 'legend' => false));
                                    break;

                                case 'checkbox':
                                    $options = array();
                                    $input_control = $this->Form->input($field_name, array('type' => $control_type, 'id' => $field_name, 'multiple' => 'checkbox', 'options' => $options, 'escape' => true, 'label' => false, 'div' => false));
                                    break;

                                case 'date':
                                    $input_control = $this->Form->input($field_name, array('type' => 'hidden', 'id' => $field_name . '_alt', 'label' => false, 'div' => false))
                                            . "<input type='text' id='$field_name' class='date_picker' style='width:100px !important;' />";
                                    break;

                                case 'current_date':
                                    $colon = '';
                                    $field_label = '';
                                    $tr_style = "style='display:none;'";
                                    $input_control = $this->Form->input($field_name, array('type' => 'hidden', 'id' => $field_name, 'value' => $current_date, 'label' => false, 'div' => false));
                                    break;

                                case 'year_month':
                                    $input_control = $this->Form->year("'" . $model_name . "." . $field_name . "'", date('Y') - 15, date('Y'), array('id' => $field_name, 'empty' => "--Select Year--", 'style' => 'width:192px;margin:2px 2px 2px 5px;')) . $this->Form->month("'" . $model_name . "." . $field_name . "'", array('empty' => "--Select Month--", 'style' => 'width:192px;')) . $this->Form->day("'" . $model_name . "." . $field_name . "'", array('empty' => false, 'style' => 'display:none;'));
                                    break;

                                case 'date_range':
                                    if (!isset($period) || !isset($period_id)) {
                                        $period_id = $period = "";
                                        echo $this->Html->scriptBlock("msg.init('error', 'Error...', 'Data period not set !');", array('inline' => true));
                                    }

                                    $input_control = "<label id='$control_id' class='label_disabled'>$period</label>" //$this->Form->input('', array('type' => 'text', 'id' => $field_name, 'value' => $period, 'disabled' => 'disabled', 'label' => false, 'div' => false))
                                            . $this->Form->input($field_name, array('type' => 'hidden', 'id' => $field_name, 'value' => $period_id, 'label' => false, 'div' => false));
                                    break;

                                case 'hidden_date_range':
                                    $colon = '';
                                    $field_label = '';
                                    $tr_style = "style='display:none;'";
                                    $input_control = $this->Form->input($field_name, array('type' => 'hidden', 'id' => $field_name, 'value' => $date_value, 'label' => false, 'div' => false));
                                    break;

                                case 'hidden':
                                    $colon = '';
                                    $value = ($field_name == 'submission_status') ? '0' : '';
                                    $field_label = '';
                                    $tr_style = "style='display:none;'";

                                    $input_control = $this->Form->input($field_name, array('type' => $control_type, 'id' => $field_name, 'value' => $value, 'label' => false, 'div' => false));
                                    break;

                                default:
                                    break;
                            }
                            ?>

                            <tr <?php echo $tr_style; ?>>
                                <td style="padding:5px 0; vertical-align:top; font-weight:bold;">
                                    <?php
                                    if (!empty($field_label)) {
                                        $sl_no++;
                                        echo $sl_no < 10 ? "0$sl_no." : "$sl_no.";
                                    }
                                    ?>
                                </td>
                                <td style="max-width:170px; padding:5px 0; vertical-align:top;"><?php echo htmlspecialchars($field_label); ?></td>
                                <td style="width:10px; padding:5px 0; vertical-align:top; font-weight:bold;"><?php echo $colon; ?></td>
                                <td style="min-width:70%; min-width:385px; vertical-align:top;"><?php echo $input_control; ?></td>
                            </tr>
                        <?php } ?>
                    </table>

                    <?php
                    echo $legend_sub_group ? "</fieldset>" : '';
                }
                ?>

                <?php
                echo $legend_group ? "</fieldset>" : '';
            }
            ?>
        </div>

        <div class="btns-div" style="margin-top:0;">
            <table style="min-width:200px; margin:0 auto; padding:0;" cellspacing="5">
                <tr>
                    <td></td>
                    <td>
                        <?php
                        echo $this->Js->submit('Close', array_merge($noteLoading, array('url' => array('controller' => 'AdminModuleDynamicCrudFormGenerators', 'action' => 'add_note', 0), 'confirm' => 'Are you sure to Cancel the Add Note ?', 'success' => "note_modal_close('note_opt');")));
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $this->Js->submit('Done', array_merge($noteLoading, array('confirm' => 'Are you sure to Add Note ?',
                            'success' => "msg.init('success', 'Add Note', 'Add Note has been completed.'); note_modal_close('note_opt'); $('#btnNote_$parent_field_id').text('Edit Notes');",
                            'error' => "msg.init('error', 'Add Note', 'Add Note failed !');")));
                        ?>
                    </td>
                    <td></td>
                </tr>
            </table>
        </div>        
        <?php echo $this->Form->end(); ?>
    </fieldset>

    <script>
        $(function () {
            $('.integers').numeric({decimal: false, negative: false});
            $('.decimals').numeric({decimal: ".", negative: true});
        });

        $(function () {
            $('#ui-datepicker-div').remove();
            $('#ui-datepicker-div').empty();
            $('.date_picker').each(function () {
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

<?php } ?>

