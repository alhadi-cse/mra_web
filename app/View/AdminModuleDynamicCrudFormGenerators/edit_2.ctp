<div id="frmStatus_add">
    <?php
    if (!empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }
    $user_group_ids = $this->Session->read('User.GroupIds');
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading);
    $is_submit = $this->Session->read('SubmissionStatus.IsSubmit');

    if (!empty($field_details_list)) {
        ?>
        <fieldset>
            <legend><?php echo $title; ?></legend> 

            <?php echo $this->Form->create($model_name); ?>

            <div class="form">
                <ul style="padding: 5px 35px; color: #0489B3; font-weight: bold; font-size: 13px; list-style: square;"><li>Fields with <strong style="color: red;">*</strong> marks are mandatory to fill up</li></ul>
                <?php
                $all_script = '';
                $sl_no = 0;
                $all_num_sl_no = (!empty($model_id) && ($model_id == 9 || $model_id == 10));

                foreach ($field_details_list as $group_id => $group_field_list) {
                    $legend_group = empty($legend_groups[$group_id]) ? false : $legend_groups[$group_id];
                    echo $legend_group ? "<fieldset style='padding:0 0 4px 0;'><legend>&#9635; " . $legend_group . "</legend>" : '';

                    foreach ($group_field_list as $sub_group_id => $field_list) {
                        $tbl_width = '95%';
                        $has_slno = !empty($group_id) && $group_id;
                        $sl_alph = false;

                        if (!empty($legend_sub_groups[$group_id][$sub_group_id])) {
                            $tbl_width = '100%';
                            ?>
                            <table style="width:95%;" cellpadding="5" cellspacing="5" border="0">
                                <tr>

                                    <?php
                                    if ($has_slno && !$all_num_sl_no) {
                                        $sl_no++;
                                        echo "<td style='width:25px; padding:10px 0; vertical-align:top; font-weight:bold;'>" .
                                        ($sl_no < 10 ? "0$sl_no." : "$sl_no.") .
                                        "</td>";
                                        echo "<td colspan='3' style='min-width:700px; padding:10px 0 0 0; font-weight:bold; vertical-align:top;'>";
                                    } else {
                                        echo "<td colspan='4' style='min-width:715px; padding:10px 0 0 0; font-size:9.5pt; font-weight:bold; vertical-align:top;'>" .
                                        "&#9899; ";
                                    }

                                    echo htmlspecialchars($legend_sub_groups[$group_id][$sub_group_id]) . "  $colon" . "</td>";

                                    $has_slno = false;
                                    //$sl_no_sub = 0;
                                    $sl_alph = 'a';
                                    ?>


                                </tr>
                                <tr>
                                    <td colspan="4" style="margin:0; padding:0; text-align:left;">
                                    <?php } ?>

                                    <table style="width:<?php echo$tbl_width; ?>;" cellpadding="5" cellspacing="5" border="0">
                                        <?php
                                        $input_control = '';
                                        foreach ($field_list as $field_id => $field_details) {
                                            $colon = ':';
                                            $colon_style = "style='width:5px; padding:10px 0; font-weight:bold;'";

                                            $field_name = $field_details['field_name'];
                                            $field_label = $field_details['field_label'];
                                            $data_type = $field_details['data_type'];
                                            $control_type = $field_details['control_type'];
                                            $child_model_id = $field_details['child_model_id'];
                                            $parent_or_child_control_id = $field_details['parent_or_child_control_id'];
                                            $is_mandatory_for_edit = $field_details['is_mandatory_for_edit'];
                                            $control_id = '';
                                            if ($is_mandatory_for_edit == 1) {
                                                $control_id = $field_name;
                                                if (($control_type == 'select') && !empty($parent_or_child_control_id)) {
                                                    $control_id = 'parent_control_id' . $parent_or_child_control_id;
                                                } elseif ($control_type == 'dependent_dropdown') {
                                                    $control_id = 'control_id_to_be_updated' . $parent_or_child_control_id;
                                                }

                                                if (!((!empty($user_group_ids) && in_array(3, $user_group_ids)) && $control_type == 'label')) {
                                                    $all_script .= "$('#$control_id').parent('div').addClass('required');";
                                                }

//                                                $script = "<script> $(function () { " .
//                                                        "$('#" . $control_id . "').parent('div').addClass( 'required' );" .
//                                                        "}); </script>";
//                                                if ((!empty($user_group_ids) && in_array(3, $user_group_ids)) && $control_type == 'label') {
//                                                    $script = "";
//                                                }
                                            }
                                            $date_value = $field_details['date_value'];
                                            $current_date = $field_details['current_date'];
                                            $field_details_from_session = $field_details['field_value_from_session'];

                                            $has_notes = $field_details['has_notes'];
                                            $is_note_added = $field_details['is_note_added'];

                                            $tr_style = "";
                                            $label = $field_details['label'];
                                            $options = $field_details['options'];

                                            $className = "classGroups_$group_id"
                                                    . ((!empty($sub_group_id) && $sub_group_id > 0) ? "_$sub_group_id" : "");

                                            switch ($control_type) {
                                                case 'calculated_label':
                                                    $str_sum = str_replace(' ', '', $field_name);

                                                    $old_char = array('(', ')', '+', '-', '*', '/', ':', '\\');
                                                    $new_char = array('', '', ', ', ', ', ', ', ', ', ', ', ', ');
                                                    $str_ids = str_replace($old_char, $new_char, $str_sum);
                                                    $ctr_ids = explode(', ', $str_ids);

                                                    foreach ($ctr_ids as $ctr_id) {
                                                        $str_ids = str_replace($ctr_id, "#$ctr_id", $str_ids);
                                                        $str_sum = str_replace($ctr_id, "(parseFloat($('#$ctr_id').val()) || 0)", $str_sum);
                                                    }

                                                    $all_script .= "$('$str_ids').each(function() { " .
                                                            "$(this).keyup(function() { " .
                                                            "$('#sum_$field_id').val($str_sum);" .
                                                            "});" .
                                                            "$(this).change(function() { " .
                                                            "$('#sum_$field_id').val($str_sum);" .
                                                            "});" .
                                                            "});";

                                                    $tr_style = "style='font-weight:bold;'";
                                                    $input_control = $this->Form->input("Calculated.sum_$field_id", array('id' => "sum_$field_id", 'type' => 'text', 'class' => 'readonly', 'style' => 'width:100px; text-align:right;', 'readonly' => 'readonly', 'label' => false));
                                                    break;

                                                case 'aggregate_labelX':
                                                    $all_script .= "var total = 0;" .
                                                            "$('.$sumClassName').each(function () {" .
                                                            "total += parseFloat($(this).val()) || 0;" .
                                                            "});" .
                                                            "$('#$sumIdName').val(total).trigger('change');" .
                                                            "$('.$sumClassName').each(function() { " .
                                                            "$(this).keyup(function() { " .
                                                            "var total = 0;" .
                                                            "$('.$sumClassName').each(function () {" .
                                                            "total += parseFloat($(this).val()) || 0;" .
                                                            "});" .
                                                            "$('#$sumIdName').val(total).trigger('change');" .
                                                            "});" .
                                                            "$(this).change(function() { " .
                                                            "var total = 0;" .
                                                            "$('.$sumClassName').each(function () {" .
                                                            "total += parseFloat($(this).val()) || 0;" .
                                                            "});" .
                                                            "$('#$sumIdName').val(total).trigger('change');" .
                                                            "});" .
                                                            "});";

                                                    $tr_style = "style='font-weight:bold;'";
                                                    $input_control = $this->Form->input("Aggregate.$sumIdName", array('id' => $sumIdName, 'type' => 'text', 'class' => 'readonly', 'style' => 'width:100px; text-align:right;', 'readonly' => 'readonly', 'label' => false));
                                                    break;

                                                case 'aggregate_label':
                                                    if (!empty($sub_group_id) && $sub_group_id > 0) {
                                                        $sumClassName = ".classGroups_$group_id" . "_$sub_group_id";
                                                        $sumIdName = "sum_classGroups_$group_id" . "_$sub_group_id";

                                                        $all_script .= "var total = 0;" .
                                                                "$('$sumClassName').each(function () {" .
                                                                "total += parseFloat($(this).val()) || 0;" .
                                                                "});" .
                                                                "$('#$sumIdName').val(total).trigger('change');" .
                                                                "$('$sumClassName').each(function() { " .
                                                                "$(this).keyup(function() { " .
                                                                "var total = 0;" .
                                                                "$('$sumClassName').each(function () {" .
                                                                "total += parseFloat($(this).val()) || 0;" .
                                                                "});" .
                                                                "$('#$sumIdName').val(total).trigger('change');" .
                                                                "});" .
                                                                "$(this).change(function() { " .
                                                                "var total = 0;" .
                                                                "$('$sumClassName').each(function () {" .
                                                                "total += parseFloat($(this).val()) || 0;" .
                                                                "});" .
                                                                "$('#$sumIdName').val(total).trigger('change');" .
                                                                "});" .
                                                                "});";
                                                    } else {
                                                        $sumClassName = "input[id^=\"sum_classGroups_$group_id\"]";
                                                        $sumIdName = "sum_classGroup_$group_id";

                                                        $all_script .= "var total = 0;" .
                                                                "$('$sumClassName').each(function () {" .
                                                                "total += parseFloat($(this).val()) || 0;" .
                                                                "});" .
                                                                "$('#$sumIdName').val(total);" .
                                                                "$('$sumClassName').each(function(){" .
                                                                "$(this).change(function(){ " .
                                                                "var total = 0;" .
                                                                "$('$sumClassName').each(function(){" .
                                                                "total += parseFloat($(this).val()) || 0;" .
                                                                "});" .
                                                                "$('#$sumIdName').val(total);" .
                                                                "});" .
                                                                "});";
                                                    }

                                                    $tr_style = "style='font-weight:bold;'";
                                                    $input_control = $this->Form->input("Aggregate.$sumIdName", array('id' => $sumIdName, 'type' => 'text', 'class' => 'readonly', 'style' => 'width:100px; text-align:right;', 'readonly' => 'readonly', 'label' => false));
                                                    break;

                                                case 'text':
                                                    switch ($data_type) {
                                                        case 'varchar':
                                                            $input_control = $this->Form->input($field_name, array('id' => $field_name, 'type' => $control_type, 'label' => false));
                                                            break;
                                                        case 'int':
                                                            $input_control = $this->Form->input($field_name, array('id' => $field_name, 'type' => $control_type, 'class' => "integers $className", 'style' => 'width:100px; text-align:right;', 'label' => false));
                                                            break;
                                                        case 'double':
                                                            $input_control = $this->Form->input($field_name, array('id' => $field_name, 'type' => $control_type, 'class' => "decimals $className", 'style' => 'width:100px; text-align:right;', 'label' => false));
                                                            break;
                                                        default:
                                                            break;
                                                    }
                                                    break;

                                                case 'textarea':
                                                    $colon_style = "style='width:5px; padding:10px 0; font-weight:bold; vertical-align:top;'";
                                                    $input_control = $this->Form->input($field_name, array('type' => $control_type, 'id' => $field_name, 'escape' => false, 'label' => false));
                                                    break;

                                                case 'select':
                                                    $input_control = $this->Form->input($field_name, array('type' => $control_type, 'options' => $options, 'id' => $control_id, 'empty' => '---Select---', 'label' => false));
                                                    break;

                                                case 'select_or_label':
                                                    if (!empty($field_details_from_session)) {
                                                        $value = !empty($options[$field_details_from_session]) ? $options[$field_details_from_session] : "";
                                                        $input_control = $this->Form->input('', array('type' => 'text', 'id' => $control_id, 'value' => $value, 'disabled' => 'disabled', 'label' => false)) .
                                                                $this->Form->input($field_name, array('type' => 'hidden', 'value' => $field_details_from_session, 'label' => false));
                                                    } else {
                                                        $input_control = $this->Form->input($field_name, array('type' => 'select', 'options' => $options, 'id' => $control_id, 'empty' => '---Select---', 'label' => false));
                                                    }
                                                    break;

                                                case 'dependent_dropdown':
                                                    //$input_control = "<div id='control_id_to_be_updated$parent_or_child_control_id'></div>";
                                                    $input_control = $this->Form->input($field_name, array('type' => 'select', 'options' => $options, 'id' => 'control_id_to_be_updated' . $parent_or_child_control_id, 'empty' => '---Select---', 'label' => false));
                                                    break;

                                                case 'label':
                                                    $input_control = $this->Form->input($field_name, array('type' => 'text', 'id' => $field_name, 'value' => $label, 'disabled' => 'disabled', 'label' => false));
                                                    break;

                                                case 'radio':
                                                    $input_control = $this->Form->input($field_name, array('type' => $control_type, 'id' => $field_name, 'options' => $options, 'label' => false, 'legend' => false));
                                                    break;

                                                case 'checkbox':
                                                    $options = array();
                                                    $input_control = $this->Form->input($field_name, array('type' => $control_type, 'id' => $field_name, 'multiple' => 'checkbox', 'options' => $options, 'escape' => true, 'label' => false));
                                                    break;

                                                case 'date':
                                                    if (!empty($this->request->data[$model_name])) {
                                                        $date_value = $this->request->data[$model_name][$field_name];
                                                    }
                                                    $date_value = (!empty($date_value) ? date("d-m-Y", strtotime($date_value)) : '');
                                                    $input_control = $this->Form->input($field_name, array('type' => 'hidden', 'id' => $field_name . '_alt', 'label' => false))
                                                            . "<div><input type='text' id='$field_name' value='$date_value' class='date_picker' style='width:100px;!important;' /></div>";
                                                    break;

                                                case 'current_date':
                                                    $colon = "";
                                                    $field_label = "";
                                                    $tr_style = "style='display:none;'";
                                                    $input_control = $this->Form->input($field_name, array('type' => 'hidden', 'id' => $field_name, 'value' => $current_date, 'label' => false));
                                                    break;

                                                case 'year_month':
                                                    $input_control = $this->Form->year("'" . $model_name . "." . $field_name . "'", date('Y') - 15, date('Y'), array('id' => $field_name, 'empty' => "--Select Year--", 'style' => 'width:192px;margin:2px 2px 2px 5px;')) . $this->Form->month("'" . $model_name . "." . $field_name . "'", array('empty' => "--Select Month--", 'style' => 'width:192px;')) . $this->Form->day("'" . $model_name . "." . $field_name . "'", array('empty' => false, 'style' => 'display:none;'));
                                                    break;

                                                case 'date_range':
                                                    $input_control = $this->Form->input('', array('type' => 'text', 'value' => $period, 'disabled' => 'disabled', 'label' => false)) .
                                                            $this->Form->input($field_name, array('type' => 'hidden', 'id' => $field_name, 'value' => $period_id, 'label' => false));
                                                    break;

                                                case 'hidden_date_range':
                                                    $colon = "";
                                                    $field_label = "";
                                                    $tr_style = "style='display:none;'";
                                                    $input_control = $this->Form->input($field_name, array('type' => 'hidden', 'id' => $field_name, 'value' => $date_value, 'label' => false));
                                                    break;

                                                case 'hidden':
                                                    $colon = "";
                                                    $value = ($field_name == 'submission_status') ? '0' : '';
                                                    $field_label = "";
                                                    $tr_style = "style='display:none;'";

                                                    $input_control = $this->Form->input($field_name, array('type' => $control_type, 'id' => $field_name, 'value' => $value, 'label' => false));
                                                    break;

                                                default:
                                                    break;
                                            }
                                            ?>

                                            <tr <?php echo $tr_style; ?>>
                                                <?php
                                                $field_label = htmlspecialchars($field_label);

                                                if ($has_slno) {
                                                    ++$sl_no;
                                                    echo "<td style='width:25px; padding:10px 0; vertical-align:top; text-align:center; font-weight:bold;'>";
                                                    echo $sl_no < 10 ? "0$sl_no." : "$sl_no.";
                                                    echo "</td>";

                                                    $field_label_style = " style='width:244px; padding:10px 0; vertical-align:top;'";
                                                } else if ($sl_alph) {
                                                    echo "<td style='width:25px; padding:10px 3px 10px 7px; vertical-align:top; text-align:right; font-weight:bold;'>";
                                                    if ($all_num_sl_no) {
                                                        ++$sl_no;
                                                        echo $sl_no < 10 ? "0$sl_no." : "$sl_no.";
                                                    } else if ($sl_alph) {
                                                        echo "$sl_alph)";
                                                        ++$sl_alph;
                                                    } else if ($sl_no_sub) {
                                                        ++$sl_no_sub;
                                                        $this->requestAction("/AdminModuleDynamicCrudFormGenerators/intToRoman/$sl_no_sub") . ".";
                                                    }
                                                    echo "</td>";

                                                    $field_label_style = " style='width:230px; padding:10px 0; vertical-align:top;'";
                                                } else {
                                                    $field_label_style = " colspan='2' style='width:250px; padding:10px 0; vertical-align:top;'";
                                                }
                                                ?>
                                                <td <?php echo $field_label_style; ?>><?php echo $field_label; ?></td>
                                                <td <?php echo $colon_style; ?>><?php echo $colon; ?></td>
                                                <td style="min-width:140px;"><?php echo $input_control; ?></td>
                                                <?php
                                                if ($has_notes) {
                                                    ?>
                                                    <td style="min-width:85px;">
                                                        <?php
                                                        $noteLoading = array('id' => "btnNote_$field_id", 'update' => '#note_content', 'class' => 'my-btns sbtns', 'style' => 'width:60px; margin-left:8px;', 'evalScripts' => true,
                                                            'title' => "Add or Edit Notes for '$field_label'.",
                                                            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
                                                            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)) . " note_modal_open('note_opt');");

                                                        $btn_text = ($is_note_added) ? 'Edit Notes' : 'Add Notes';
                                                        echo $this->Js->link($btn_text, array('controller' => 'AdminModuleDynamicCrudFormGenerators', 'action' => 'edit_note', $model_id, $field_id, $child_model_id, $statement_year_period_id, $is_note_added), $noteLoading);
                                                        ?>
                                                    </td>
                                                    <?php
                                                }
                                                ?>
                                            </tr>
                                        <?php } ?>
                                    </table>

                                    <?php
                                    if (!empty($legend_sub_groups[$group_id][$sub_group_id])) {
                                        ?>
                                    </td>
                                </tr>
                            </table>
                            <?php
                        }
                    }
                    echo $legend_group ? "</fieldset>" : '';
                }
                ?>                
            </div>

            <div class="btns-div" style="margin-top:5px;"> 
                <table style="margin:0 auto; padding:5px;" cellspacing="7">
                    <tr>
                        <?php
                        if (!empty($is_submit) && $is_submit == 1) {
                            ?>
                            <td>
                                <?php echo $this->Js->link('Close', array('controller' => 'AdminModuleDynamicCrudFormGenerators', 'action' => "view_for_final_submission?model_id=" . $model_id . "&data_type_id=" . $data_type_id), array_merge($pageLoading, array('class' => 'mybtns', 'confirm' => 'Are you sure to Close ?'))); ?> 
                            </td>
                            <td style="text-align: center;">
                                <?php
                                echo $this->Js->submit('Update', array_merge($pageLoading, array('class' => 'mybtns',
                                    'url' => "/AdminModuleDynamicCrudFormGenerators/edit/$model_id/$unique_data_id/$is_submit",
                                    'confirm' => 'Are you sure to save data ?',
                                    'success' => "msg.init('success', '$title', '$title has been updated successfully.');",
                                    'error' => "msg.init('error', '$title', 'Update failed !');")));
                                ?>
                            </td>
                            <?php
                        } else {
                            ?>
                            <td>
                                <?php echo $this->Js->link('Close', array('controller' => 'AdminModuleDynamicCrudFormGenerators', 'action' => "view?model_id=" . $model_id . "&data_type_id=" . $data_type_id), array_merge($pageLoading, array('class' => 'mybtns', 'confirm' => 'Are you sure to Close ?'))); ?> 
                            </td>
                            <td style="text-align: center;">
                                <?php
                                echo $this->Js->submit('Update', array_merge($pageLoading, array('class' => 'mybtns',
                                    'url' => "/AdminModuleDynamicCrudFormGenerators/edit/$model_id/$unique_data_id/0",
                                    'confirm' => 'Are you sure to save data ?',
                                    'success' => "msg.init('success', '$title', '$title has been updated successfully.');",
                                    'error' => "msg.init('error', '$title', 'Update failed !');")));
                                ?>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                </table>
            </div>
            <?php echo $this->Form->end(); ?>
        </fieldset>
    <?php } ?>
</div>


<script>

    $(document).ready(function () {
        draggable_modal('note_title', 'note_opt', 'note_opt_bg');
    });


    function note_modal_open(content) {
        modal_open(content, 30);
    }

    function note_modal_close(content) {
        modal_close(content);
    }

</script>


<div id="note_opt_bg" class="modal-bg">

    <div id="note_opt" class="modal-content" style="width:780px; margin:75px auto;">

        <div id="note_title" class="modal-title">
            <span class="modal-title-txt">Add Note</span>
            <button class="close" onclick="if (confirm('Are you sure to Cancel ?'))
                        note_modal_close('note_opt');
                    return false;">✖</button>
        </div>

        <div id="note_content" style="width:auto; height:auto; max-height:470px; max-height:75vh; margin:0; padding:7px; overflow:auto; cursor:default;">

        </div>

    </div>

</div>

<?php
$this->Js->get('#parent_control_id1')->event('change', $this->Js->request(array(
            'controller' => 'AdminModuleDynamicCrudFormGenerators',
            'action' => 'dependent_option_select'), array(
            'update' => '#control_id_to_be_updated1',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'data' => $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))
);

$this->Js->get('#parent_control_id2')->event('change', $this->Js->request(array(
            'controller' => 'AdminModuleDynamicCrudFormGenerators',
            'action' => 'dependent_option_select'), array(
            'update' => '#control_id_to_be_updated2',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'data' => $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))
);

$this->Js->get('#parent_control_id3')->event('change', $this->Js->request(array(
            'controller' => 'AdminModuleDynamicCrudFormGenerators',
            'action' => 'dependent_option_select'), array(
            'update' => '#control_id_to_be_updated3',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'data' => $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))
);

$this->Js->get('#parent_control_id4')->event('change', $this->Js->request(array(
            'controller' => 'AdminModuleDynamicCrudFormGenerators',
            'action' => 'dependent_option_select'), array(
            'update' => '#control_id_to_be_updated4',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'data' => $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))
);

$this->Js->get('#parent_control_id5')->event('change', $this->Js->request(array(
            'controller' => 'AdminModuleDynamicCrudFormGenerators',
            'action' => 'dependent_option_select'), array(
            'update' => '#control_id_to_be_updated5',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'data' => $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))
);

if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
    echo $this->Js->writeBuffer();
?>

<script type="text/javascript">
    $(document).ready(function () {

        $('.integers').numeric({decimal: false, negative: false});
        $('.decimals').numeric({decimal: ".", negative: false});

        selectedVal = $("#usage_types option:selected").val();
        var selected_value = parseInt(selectedVal);

        if (selectedVal == "") {
            hide_all();
        } else {
            if (selected_value == 1) {
                show_rented();
            } else if (selected_value == 2) {
                show_ownership();
            } else {
                hide_all();
            }
        }

        $('#usage_types').change(function () {

            if ($(this).val() == "1") {
                show_rented();
            } else if ($(this).val() == "2") {
                show_ownership();
            } else {
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

<?php echo $all_script; ?>
    });


    $(function () {
        $('#ui-datepicker-div').remove();
        $('#ui-datepicker-div').empty();
        $('.date_picker').each(function () {
            $(this).datepicker({
                yearRange: 'c-100:c+1',
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