
<style>

    .basic-form {
        clear: both;
        width: auto;
        height: auto;
        margin: 10px;
        padding: 0;
        min-width: 50%;
        max-width: 100%;
        color: #071315;
        font: normal 13px/1.5 Arial, Helvetica, Sans-serif;
        border-collapse: collapse;
        overflow: visible;
    }
    .basic-form td {
        padding: 6px;
        font-weight: bold;
    }
    .basic-form td div {
        display: inline-block;
        margin: 0;
        padding: 0;
    }

    .multi_tbl_wrapper {
/*        width: auto;
        height: auto;*/
        width: 830px;
        height: 350px;
        max-width: 830px;
        min-height: 350px;
        max-width: 80vw;
        min-height: 40vh;
        margin: 0 auto;
        padding: 0;
        overflow: auto;
    }
    .multi_tbl_view {
        width: auto;
        margin: 0;
        padding: 0;
        color: #222;
        font: normal 12px/1.3 Arial, Helvetica, Sans-serif;
        border-collapse: collapse;
        border-spacing: 0;
    }
    .multi_tbl_wrapper div, .multi_tbl_view div {
        margin: 0;
        padding: 0;
    }
    .multi_tbl_view div.input, .multi_tbl_view div.select {
        text-align: center;
    }
    .multi_tbl_view div.required::after {
        content: "";
        display: none;
        margin: 0;
        padding: 0;
    }
    .multi_tbl_view input, .multi_tbl_view select {
        width: 150px;
        margin: 0 auto;
        padding: 3px 5px;
    }
    .multi_tbl_view textarea {
        width: 150px;
        height: 30px;
        margin: 0 auto;
        padding: 4px 5px;
    }
    .multi_tbl_view thead tr th,
    .multi_tbl_view thead tr td,
    .multi_tbl_view tfoot tr th, 
    .multi_tbl_view tfoot tr td, .multi_tbl_view tr td.td_header {
        line-height: 1.5;
        padding: 5px;
        color: #222;
        font-weight: bold;

        background: #fafafa;
        background: -moz-linear-gradient(top,#fafafa 0%,#f3f3f3 25%,#ededed 80%,#e5e5e5 100%);
        background: -webkit-linear-gradient(top,#fafafa 0%,#f3f3f3 25%,#ededed 80%,#e5e5e5 100%);
        background: linear-gradient(to bottom,#fafafa 0%,#f3f3f3 25%,#ededed 80%,#e5e5e5 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fafafa', endColorstr='#e5e5e5',GradientType=0);
    }
    .multi_tbl_view tr td.td_header , .td_header {
        line-height: 1.3;
    }
    .multi_tbl_view td, .multi_tbl_view th,
    .multi_tbl_view tbody tr td, .multi_tbl_view tr th {
        border: 1px solid #ddd;
        margin: 0;
        padding: 5px;
        color: #222;
        font: normal 12px/1.3 Arial, Helvetica, Sans-serif;
    }
    .multi_tbl_view thead,
    .multi_tbl_view tfoot {
        background: #dedede;
    }
    .multi_tbl_view tbody tr td {
        background: #eff3f7;
        background-image: -moz-linear-gradient(
            top,
            rgba(255,255,255,0.8) 0%,
            rgba(255,255,255,0.7) 50%,
            rgba(255,255,255,0.7) 51%,
            rgba(255,255,255,0.4) 100%);

        background-image: -webkit-gradient(
            linear, left top, left bottom,
            color-stop(0%,rgba(255,255,255,0.8)),
            color-stop(50%,rgba(255,255,255,0.7)),
            color-stop(51%,rgba(255,255,255,0.7)),
            color-stop(100%,rgba(255,255,255,0.5)));
    }
    .multi_tbl_view tbody tr.odd td {
        background: #e2e3e5;
        background-image: -moz-linear-gradient(
            top,
            rgba(255,255,255,0.7) 0%,
            rgba(255,255,255,0.6) 50%,
            rgba(255,255,255,0.6) 51%,
            rgba(255,255,255,0.3) 100%);

        background-image: -webkit-gradient(
            linear, left top, left bottom,
            color-stop(0%,rgba(255,255,255,0.7)),
            color-stop(50%,rgba(255,255,255,0.6)),
            color-stop(51%,rgba(255,255,255,0.6)),
            color-stop(100%,rgba(255,255,255,0.3)));
    }

</style>

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

if (!empty($field_details_list)) {

    echo $this->Html->css("fixedtablestyle", null, array("inline" => true));
    ?>
    <fieldset>
        <legend><?php echo $title; ?></legend> 

        <ul style="padding:5px 35px; color:#0387B5; font-weight:bold; font-size:13px; list-style:square;"><li>Fields with <strong style="color: red;">*</strong> marks are mandatory to fill up</li></ul>

        <?php
        echo $this->Form->create($model_name);

        $all_script = '';
        if ($field_details_list[0]) {
            $basic_field_details_list = $field_details_list[0];
            unset($field_details_list[0]);

            echo '<table class="basic-form" cellpadding="0" cellspacing="0" border="0">';

            $sl_no = 0;
            $input_control = '';
            foreach ($basic_field_details_list as $field_id => $field_details) {
                $field_name = $field_details['field_name'];
                $field_label = $field_details['field_label'];
                $data_type = $field_details['data_type'];
                $control_type = $field_details['control_type'];
                $is_mandatory_for_add = $field_details['is_mandatory_for_add'];
                $child_model_id = $field_details['child_model_id'];
                $parent_or_child_control_id = $field_details['parent_or_child_control_id'];
                $control_id = '';

                if ($is_mandatory_for_add == 1) {
                    $control_id = $field_name;
                    if (($control_type == 'select' || $control_type == 'select_or_label') && !empty($parent_or_child_control_id)) {
                        $control_id = 'parent_control_id' . $parent_or_child_control_id;
                    } elseif ($control_type == 'dependent_dropdown') {
                        $control_id = 'control_id_to_be_updated' . $parent_or_child_control_id;
                    }
                    //$all_script .= "$('#$control_id').parent('div').addClass('required');";
                }

                $field_name = "basicInfo.$field_name";
                $date_value = $field_details['date_value'];
                $current_date = $field_details['current_date'];
                $field_value_from_session = $field_details['field_value_from_session'];

                $tr_style = 'font-weight:bold;';
                $label = $field_details['label'];
                $options = $field_details['options'];

                switch ($control_type) {
                    case 'text':
                        switch ($data_type) {
                            case 'varchar':
                                $input_control = $this->Form->input($field_name, array('id' => $field_name, 'type' => $control_type, 'style' => 'width:430px; text-align:right;', 'label' => false));
                                break;
                            case 'int':
                                $input_control = $this->Form->input($field_name, array('id' => $field_name, 'type' => $control_type, 'class' => "integers", 'style' => 'width:100px; text-align:right;', 'label' => false));
                                break;
                            case 'double':
                                $input_control = $this->Form->input($field_name, array('id' => $field_name, 'type' => $control_type, 'class' => "decimals", 'style' => 'width:100px; text-align:right;', 'label' => false));
                                break;
                            default:
                                break;
                        }
                        break;

                    case 'textarea':
                        $colon_style = "style='width:5px; padding:5px; font-weight:bold; vertical-align:top;'";
                        $input_control = $this->Form->input($field_name, array('type' => $control_type, 'id' => $field_name, 'escape' => false, 'label' => false));
                        break;

                    case 'select':
                        $input_control = $this->Form->input($field_name, array('type' => $control_type, 'options' => $options, 'id' => $control_id, 'empty' => '---Select---', 'label' => false));

                        if (!empty($options) && count($options) > 15) {
                            $filter_control = "<div style='margin-left:10px;'>filter:<input type='text' id='txtFilter_$control_id' style='width:100px !important; margin-left:5px;'></div>";
                            $all_script .= "$('#$control_id').filterByText($('#txtFilter_$control_id'), true);";

                            $input_control .= $filter_control;
                        }
                        break;

                    case 'select_or_label':
                        if (!empty($field_value_from_session)) {
                            $value = !empty($options[$field_value_from_session]) ? $options[$field_value_from_session] : '';
                            $input_control = $value . $this->Form->input($field_name, array('type' => 'hidden', 'id' => $field_name, 'value' => $field_value_from_session, 'label' => false));
                        } else {
                            $input_control = $this->Form->input($field_name, array('type' => 'select', 'options' => $options, 'id' => $control_id, 'empty' => '---Select---', 'label' => false));
                        }

                        if (!empty($options) && count($options) > 15) {
                            $filter_control = "<div style='margin-left:10px;'>filter:<input type='text' id='txtFilter_$control_id' style='width:100px !important; margin-left:5px;'></div>";
                            $all_script .= "$('#$control_id').filterByText($('#txtFilter_$control_id'), true);";

                            $input_control .= $filter_control;
                        }
                        break;

                    case 'dependent_dropdown':
                        $input_control = $this->Form->input($field_name, array('type' => 'select', 'options' => $options, 'id' => 'control_id_to_be_updated' . $parent_or_child_control_id, 'empty' => '---Select---', 'label' => false));

                        if (!empty($options) && count($options) > 15) {
                            $filter_control = "<div style='margin-left:10px;'>filter:<input type='text' id='txtFilter_$control_id' style='width:100px !important; margin-left:5px;'></div>";
                            $all_script .= "$('#$control_id').filterByText($('#txtFilter_$control_id'), true);";

                            $input_control .= $filter_control;
                        }
                        break;

                    case 'label':
                        $input_control = $label . $this->Form->input($field_name, array('type' => 'hidden', 'id' => $field_name, 'value' => $label, 'label' => false));
                        break;

                    case 'radio':
                        $input_control = $this->Form->input($field_name, array('type' => $control_type, 'id' => $field_name, 'options' => $options, 'label' => false, 'legend' => false));
                        break;

                    case 'checkbox':
                        $options = array();
                        $input_control = $this->Form->input($field_name, array('type' => $control_type, 'id' => $field_name, 'multiple' => 'checkbox', 'options' => $options, 'escape' => true, 'label' => false));
                        break;

                    case 'date':
                        $input_control = $this->Form->input($field_name, array('type' => 'hidden', 'id' => $field_name . '_alt', 'label' => false))
                                . "<div><input type='text' id='$field_name' class='date_picker' style='width:100px !important;' /></div>";
                        break;

                    case 'current_date':
                        $tr_style = "style='display:none;'";
                        $input_control = $this->Form->input($field_name, array('type' => 'hidden', 'id' => $field_name, 'value' => $current_date, 'label' => false));
                        break;

                    case 'year_month':
                        $input_control = $this->Form->year("'" . $model_name . "." . $field_name . "'", date('Y') - 15, date('Y'), array('id' => $field_name, 'empty' => "--Select Year--", 'style' => 'width:192px;margin:2px 2px 2px 5px;')) . $this->Form->month("'" . $model_name . "." . $field_name . "'", array('empty' => "--Select Month--", 'style' => 'width:192px;')) . $this->Form->day("'" . $model_name . "." . $field_name . "'", array('empty' => false, 'style' => 'display:none;'));
                        break;

                    case 'date_range':
                        if (!empty($period) && !empty($period_id))
                            $input_control = $period . $this->Form->input($field_name, array('type' => 'hidden', 'id' => $field_name, 'value' => $period_id, 'label' => false));
                        break;

                    case 'hidden_date_range':
                        $tr_style = "style='display:none;'";
                        $input_control = $this->Form->input($field_name, array('type' => 'hidden', 'id' => $field_name, 'value' => $date_value, 'label' => false));
                        break;

                    case 'hidden':
                        $tr_style = "style='display:none;'";
                        $value = ($field_name == "basicInfo.submission_status") ? '0' : '';
                        $input_control = $this->Form->input($field_name, array('type' => $control_type, 'id' => $field_name, 'value' => $value, 'label' => false));
                        break;

                    default:
                        break;
                }

                ++$sl_no;
                $field_label = htmlspecialchars($field_label);
                echo "<tr $tr_style>"
                . "<td style='width:25px; text-align:center;'>$sl_no.</td>"
                . "<td style='width:150px;'>$field_label</td>"
                . "<td style='width:10px;'>:</td>"
                . "<td style='min-width:530px;'>$input_control</tr>";
            }
            echo "</table>";
        }

        $dr_header_group = '';
        $dr_header_title = '';
        $dr_header_sub_title = '';

        foreach ($field_details_list as $group_id => $field_list) {

            if (empty($group_id) || empty($field_list) || count($field_list) < 1) {
                unset($field_details_list[$group_id]);
                continue;
            }

            $no_of_column = count($field_list);
            $group_title = (isset($field_groups[$group_id]) ? $field_groups[$group_id] : "");

            if (empty($group_title)) {// && $no_of_column == 1
                foreach ($field_list as $field_id => $field_detail) {
                    if (empty($field_id)) {
                        unset($field_details_list[$group_id][$field_id]);
                        continue;
                    }

                    $field_title = $field_detail["field_label"];
                    $is_mandatory = $field_detail['is_mandatory_for_add'];
                    $field_title = ($is_mandatory && $is_mandatory == 1) ? "$field_title (<span style='color:red;'>*</span>)" : $field_title;

                    $dr_header_group .= "<th rowspan='2'>$field_title</th>";

//                        if ($is_sub_title) {
//                            $field_sub_title = $field_detail["field_sub_title"];
//                            //$field_sub_title = !empty($field_sub_groups[$field_id]) ? $field_sub_groups[$field_id] : '';
//                            $dr_header_sub_title .= (!empty($field_sub_title) ? "<th class='sub-title'>$field_sub_title</th>" : "<th class='sub-title empty'></th>");
//                        }
                }

                continue;
            }

            if (!array_filter($field_list)) {
                if ($no_of_column > 1) {
                    $dr_header_group .= "<th colspan='$no_of_column'>$group_title</th>";
                    $dr_header_title .= "<th class='empty' colspan='$no_of_column'></th>";
//                        if ($is_sub_title)
//                            $dr_header_sub_title .= "<th class='sub-title empty' colspan='$no_of_column'></th>";
                } else {
                    $dr_header_group .= "<th>$group_title</th>";
                    $dr_header_title .= "<th class='empty'></th>";
//                        if ($is_sub_title)
//                            $dr_header_sub_title .= "<th class='sub-title empty'></th>";
                }
                continue;
            }

            foreach ($field_list as $field_id => $field_detail) {
                if (empty($field_id)) {
                    unset($field_details_list[$group_id][$field_id]);
                    --$no_of_column;
                    continue;
                }

                $field_title = $field_detail["field_label"];
                $is_mandatory = $field_detail['is_mandatory_for_add'];
                $field_title = ($is_mandatory && $is_mandatory == 1) ? "$field_title (<span style='color:red;'>*</span>)" : $field_title;

                $dr_header_title .= (!empty($field_title) ? "<th>$field_title</th>" : "<th class='empty'></th>");

//                    if ($is_sub_title) {
//                        $field_sub_title = $field_detail["field_sub_title"];
//                        $dr_header_sub_title .= (!empty($field_sub_title) ? "<th class='sub-title'>$field_sub_title</th>" : "<th class='sub-title empty'></th>");
//                    }
            }

            if ($group_id > 0 && $no_of_column > 0) {
                $dr_header_group .= "<th" . ($no_of_column > 1 ? " colspan='$no_of_column'>" : ">") . "$group_title</th>";
            }
        }

        if (!empty($dr_header_title))
            $dr_header_title = "<tr>$dr_header_title</tr>";
        else {
            $dr_header_group = str_replace(" rowspan='2'", "", $dr_header_group);
        }
        if (!empty($dr_header_group))
            $dr_header_group = "<tr>$dr_header_group</tr>";

//        $cat_group_id = $model_id . '01';

        $cat_group_list = $field_details_list[$cat_group_id];
        unset($field_details_list[$cat_group_id]);

//        debug($cat_group_list);

        $cat_level = 0;
        $cat_field_list = array();
        foreach ($cat_group_list as $cat_field_id => $cat_field_details) {
            ++$cat_level;
            $cat_field_list[] = $cat_field_details;
        }
        //$cat_level = count($cat_field_list);
        echo $this->Form->input("FormInfo.cat_level", array('type' => 'hidden', 'value' => $cat_level, 'label' => false, 'div' => false));

        //if (!empty($cat_level) && !empty($cat_field_list[0]['options'])) {
        $dr_all_controls = '';
        $dr_all_controls = '';
        if (!empty($cat_level) && !empty($cat_field_list[0]['options'][$cat_id])) {

            $cat_field = $cat_field_list[0];
            $cat_title = $cat_field['field_label'];
            $cat_field_name = $cat_field['field_name'];
            $cat_title = $cat_field['options'][$cat_id];

            $dr_controls = '';
            $dr_cat_controls = '';

            $control_cat_ids = $cat_id;
            $control_cat_name = "$model_name.$control_cat_ids";

            $dr_sub_controls = '';
            $all_sub_rowspan = 0;

            if ($cat_level > 1 && !empty($cat_field_list[1]['options'])) {
                $sub_cat_field = $cat_field_list[1];

                $sub_cat_title = $sub_cat_field['field_label'];
                $sub_cat_field_name = $sub_cat_field['field_name'];
                $sub_cat_options = $sub_cat_field['options'];

                if ($sub_cat_field['control_type'] == 'dependent_dropdown') {
                    if (!empty($sub_cat_options[$cat_id]))
                        $sub_cat_options = $sub_cat_options[$cat_id];
//                    else
//                        continue;
                }

                $dr_sub_controls = '';
                foreach ($sub_cat_options as $sub_cat_id => $sub_cat_title) {

                    $control_cat_ids = $cat_id . $sub_cat_id;
                    $control_cat_name = "$model_name.$control_cat_ids";

                    $dr_sub_sub_controls = '';
                    if ($cat_level > 2 && !empty($cat_field_list[2]['options'])) {
                        $sub_sub_cat_field = $cat_field_list[2];

                        $sub_sub_cat_title = $sub_sub_cat_field['field_label'];
                        $sub_sub_cat_field_name = $sub_sub_cat_field['field_name'];
                        $sub_sub_cat_options = $sub_sub_cat_field['options'];

                        if ($sub_sub_cat_field['control_type'] == 'dependent_dropdown') {
                            if (!empty($sub_sub_cat_options[$cat_id]))
                                $sub_sub_cat_options = $sub_sub_cat_options[$sub_cat_id];
                            else
                                continue;
                        }

                        foreach ($sub_sub_cat_options as $sub_sub_cat_id => $sub_sub_cat_title) {
                            $control_cat_ids = $cat_id . $sub_cat_id . $sub_sub_cat_id;
                            $control_cat_name = "$model_name.$control_cat_ids";

                            if (!empty($dr_sub_sub_controls))
                                $dr_sub_sub_controls .= '<tr>';

                            $cat_controls = "<strong>$sub_sub_cat_title</strong>"
                                    . $this->Form->input("$control_cat_name.$cat_field_name", array('type' => 'hidden', 'value' => $cat_id, 'label' => false))
                                    . $this->Form->input("$control_cat_name.$sub_cat_field_name", array('type' => 'hidden', 'value' => $sub_cat_id, 'label' => false))
                                    . $this->Form->input("$control_cat_name.$sub_sub_cat_field_name", array('type' => 'hidden', 'value' => $sub_sub_cat_id, 'label' => false));

                            $dr_sub_sub_controls .= "<td>$cat_controls</td>";

                            $common_controls = '';
                            foreach ($field_details_list as $group_id => $field_list) {
                                foreach ($field_list as $field_id => $field_details) {

                                    $field_name = $field_details['field_name'];
                                    $data_type = $field_details['data_type'];
                                    $control_type = $field_details['control_type'];
                                    $is_mandatory_for_add = $field_details['is_mandatory_for_add'];
                                    $child_model_id = $field_details['child_model_id'];
                                    $parent_or_child_control_id = $field_details['parent_or_child_control_id'];

                                    $control_id = $control_cat_ids . '_' . $field_name;
                                    $control_name = "$control_cat_name.$field_name";

                                    $date_value = $field_details['date_value'];
                                    $current_date = $field_details['current_date'];
                                    $field_value_from_session = $field_details['field_value_from_session'];
                                    $has_notes = $field_details['has_notes'];

                                    $options = $field_details['options'];

                                    $className = "clsGroup_" . $group_id . "_" . $control_cat_ids;
                                    $dynamic_control = '';

                                    switch ($control_type) {
                                        case 'calculated_label':
                                            $field_names = explode(" as ", $field_name);
                                            $field_name = count($field_names) > 1 ? $field_names[1] : $field_names[0];
                                            $str_sum = str_replace(' ', '', $field_names[0]);

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

                                            $control_name = "$control_cat_name.$field_name";
                                            $dynamic_control = $this->Form->input($control_name, array('id' => "sum_$field_id", 'type' => 'text', 'class' => 'readonly', 'style' => 'width:100px; text-align:right;', 'readonly' => 'readonly', 'label' => false));
                                            break;

                                        case 'aggregate_label':
                                            $field_names = explode(" as ", $field_name);
                                            $field_name = count($field_names) > 1 ? $field_names[1] : $field_names[0];
                                            $all_script .= "$('.$className').each(function() { " .
                                                    "$(this).keyup(function() { " .
                                                    "var total = 0;" .
                                                    "$('.$className').each(function () {" .
                                                    "total += parseFloat($(this).val()) || 0;" .
                                                    "});" .
                                                    "$('#sum_$className').val(total);" .
                                                    "});" .
                                                    "$(this).change(function() { " .
                                                    "var total = 0;" .
                                                    "$('.$className').each(function () {" .
                                                    "total += parseFloat($(this).val()) || 0;" .
                                                    "});" .
                                                    "$('#sum_$className').val(total);" .
                                                    "});" .
                                                    "});";

                                            $control_name = "$control_cat_name.$field_name";
                                            $dynamic_control = $this->Form->input($control_name, array('id' => "sum_$className", 'type' => 'text', 'class' => 'readonly', 'style' => 'width:100px; text-align:right;', 'readonly' => 'readonly', 'label' => false));
                                            break;

                                        case 'text':
                                            switch ($data_type) {
                                                case 'varchar':
                                                    $dynamic_control = $this->Form->input($control_name, array('id' => $control_id, 'type' => $control_type, 'label' => false));
                                                    break;
                                                case 'int':
                                                    $dynamic_control = $this->Form->input($control_name, array('id' => $control_id, 'type' => $control_type, 'class' => "integers $className", 'style' => 'width:100px; text-align:right;', 'label' => false));
                                                    break;
                                                case 'double':
                                                    $dynamic_control = $this->Form->input($control_name, array('id' => $control_id, 'type' => $control_type, 'class' => "decimals $className", 'style' => 'width:100px; text-align:right;', 'label' => false));
                                                    break;
                                                default:
                                                    break;
                                            }
                                            break;

                                        case 'textarea':
                                            $dynamic_control = $this->Form->input($control_name, array('type' => $control_type, 'id' => $control_id, 'escape' => false, 'label' => false));
                                            break;

                                        case 'select':
                                            $dynamic_control = $this->Form->input($control_name, array('type' => $control_type, 'options' => $options, 'id' => $control_id, 'empty' => '---Select---', 'label' => false));
                                            break;

                                        case 'select_or_label':
                                            if (!empty($field_value_from_session)) {
                                                $value = !empty($options[$field_value_from_session]) ? $options[$field_value_from_session] : '';
                                                $dynamic_control = $this->Form->input('', array('type' => 'text', 'id' => $control_id, 'value' => $value, 'disabled' => 'disabled', 'label' => false)) .
                                                        $this->Form->input($control_name, array('type' => 'hidden', 'id' => $control_id, 'value' => $field_value_from_session, 'label' => false));
                                            } else {
                                                $dynamic_control = $this->Form->input($control_name, array('type' => 'select', 'options' => $options, 'id' => $control_id, 'empty' => '---Select---', 'label' => false));
                                            }
                                            break;

                                        case 'dependent_dropdown':
                                            //$dynamic_control = "<div id='control_id_to_be_updated$parent_or_child_control_id'></div>";
                                            $dynamic_control = $this->Form->input($control_name, array('type' => 'select', 'options' => $options, 'id' => 'control_id_to_be_updated' . $parent_or_child_control_id, 'empty' => '---Select---', 'label' => false));
                                            break;

                                        case 'label':
                                            $dynamic_control = $this->Form->input($control_name, array('type' => 'text', 'id' => $control_id, 'value' => $label, 'disabled' => 'disabled', 'label' => false));
                                            break;

                                        case 'radio':
                                            $dynamic_control = $this->Form->input($control_name, array('type' => $control_type, 'id' => $control_id, 'options' => $options, 'label' => false, 'legend' => false));
                                            break;

                                        case 'checkbox':
                                            $options = array();
                                            $dynamic_control = $this->Form->input($control_name, array('type' => $control_type, 'id' => $control_id, 'multiple' => 'checkbox', 'options' => $options, 'escape' => true, 'label' => false));
                                            break;

                                        case 'date':
                                            $dynamic_control = $this->Form->input($control_name, array('type' => 'hidden', 'id' => $control_id . '_alt', 'label' => false))
                                                    . "<div><input type='text' id='$control_id' class='date_picker' style='width:100px !important;' /></div>";
                                            break;

                                        case 'current_date':
                                            $dynamic_control = $this->Form->input($control_name, array('type' => 'hidden', 'id' => $control_id, 'value' => $current_date, 'label' => false));
                                            break;

                                        case 'year_month':
                                            $dynamic_control = $this->Form->year("'" . $model_name . "." . $control_id . "'", date('Y') - 15, date('Y'), array('id' => $control_id, 'empty' => "--Select Year--", 'style' => 'width:192px;margin:2px 2px 2px 5px;')) . $this->Form->month("'" . $model_name . "." . $control_id . "'", array('empty' => "--Select Month--", 'style' => 'width:192px;')) . $this->Form->day("'" . $model_name . "." . $control_id . "'", array('empty' => false, 'style' => 'display:none;'));
                                            break;

                                        case 'date_range':
                                            $dynamic_control = $this->Form->input('', array('type' => 'text', 'id' => $control_id, 'value' => $period, 'disabled' => 'disabled', 'label' => false)) .
                                                    $this->Form->input($control_name, array('type' => 'hidden', 'id' => $control_id, 'value' => $period_id, 'label' => false));
                                            break;

                                        case 'hidden_date_range':
                                            $dynamic_control = $this->Form->input($control_name, array('type' => 'hidden', 'id' => $control_id, 'value' => $date_value, 'label' => false));
                                            break;

                                        case 'hidden':
                                            $value = ($field_name == 'submission_status') ? '0' : '';
                                            $field_label = '';

                                            $dynamic_control = $this->Form->input($control_name, array('type' => $control_type, 'id' => $control_id, 'value' => $value, 'label' => false));
                                            break;

                                        default:
                                            break;
                                    }

                                    $common_controls .= "<td>$dynamic_control</td>";
                                }
                            }

                            $dr_sub_sub_controls .= $common_controls;
                            $dr_sub_sub_controls .= '</tr>';
                        }

                        if (!empty($dr_sub_controls))
                            $dr_sub_controls .= '<tr>';

                        $rowspan = count($sub_sub_cat_options);
                        $dr_sub_controls .= "<td rowspan='$rowspan' class='td_header'>$sub_cat_title</td>";
                        $dr_sub_controls .= $dr_sub_sub_controls;

                        $all_sub_rowspan += $rowspan;
                    } else {
                        if (!empty($dr_sub_controls))
                            $dr_sub_controls .= '<tr>';

                        $cat_controls = "<strong>$sub_cat_title</strong>"
                                . $this->Form->input("$control_cat_name.$cat_field_name", array('type' => 'hidden', 'value' => $cat_id, 'label' => false))
                                . $this->Form->input("$control_cat_name.$sub_cat_field_name", array('type' => 'hidden', 'value' => $sub_cat_id, 'label' => false));

                        $dr_sub_controls .= "<td>$cat_controls</td>";

//                            $dr_sub_controls .= str_replace($control_common_id, $control_cat_ids, $all_common_controls);
//                            $dr_sub_controls .= '</tr>';
                        //$dr_sub_controls .= $dr_sub_sub_controls;
                        //$all_sub_rowspan += 1;

                        $common_controls = '';
                        foreach ($field_details_list as $group_id => $field_list) {
                            foreach ($field_list as $field_id => $field_details) {

                                $field_name = $field_details['field_name'];
                                $data_type = $field_details['data_type'];
                                $control_type = $field_details['control_type'];
                                $is_mandatory_for_add = $field_details['is_mandatory_for_add'];
                                $child_model_id = $field_details['child_model_id'];
                                $parent_or_child_control_id = $field_details['parent_or_child_control_id'];

                                $control_id = $control_cat_ids . '_' . $field_name;
                                $control_name = "$control_cat_name.$field_name";

                                $date_value = $field_details['date_value'];
                                $current_date = $field_details['current_date'];
                                $field_value_from_session = $field_details['field_value_from_session'];
                                $has_notes = $field_details['has_notes'];

                                $options = $field_details['options'];

                                $className = "clsGroup_" . $group_id . "_" . $control_cat_ids;
                                $dynamic_control = '';

                                switch ($control_type) {
                                    case 'calculated_label':
                                        $field_names = explode(" as ", $field_name);
                                        $field_name = count($field_names) > 1 ? $field_names[1] : $field_names[0];
                                        $str_sum = str_replace(' ', '', $field_names[0]);

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

                                        $control_name = "$control_cat_name.$field_name";
                                        $dynamic_control = $this->Form->input($control_name, array('id' => "sum_$field_id", 'type' => 'text', 'class' => 'readonly', 'style' => 'width:100px; text-align:right;', 'readonly' => 'readonly', 'label' => false));
                                        break;

                                    case 'aggregate_label':
                                        $field_names = explode(" as ", $field_name);
                                        $field_name = count($field_names) > 1 ? $field_names[1] : $field_names[0];
                                        $all_script .= "$('.$className').each(function() { " .
                                                "$(this).keyup(function() { " .
                                                "var total = 0;" .
                                                "$('.$className').each(function () {" .
                                                "total += parseFloat($(this).val()) || 0;" .
                                                "});" .
                                                "$('#sum_$className').val(total);" .
                                                "});" .
                                                "$(this).change(function() { " .
                                                "var total = 0;" .
                                                "$('.$className').each(function () {" .
                                                "total += parseFloat($(this).val()) || 0;" .
                                                "});" .
                                                "$('#sum_$className').val(total);" .
                                                "});" .
                                                "});";

                                        $control_name = "$control_cat_name.$field_name";
                                        $dynamic_control = $this->Form->input($control_name, array('id' => "sum_$className", 'type' => 'text', 'class' => 'readonly', 'style' => 'width:100px; text-align:right;', 'readonly' => 'readonly', 'label' => false));
                                        break;

                                    case 'text':
                                        switch ($data_type) {
                                            case 'varchar':
                                                $dynamic_control = $this->Form->input($control_name, array('id' => $control_id, 'type' => $control_type, 'label' => false));
                                                break;
                                            case 'int':
                                                $dynamic_control = $this->Form->input($control_name, array('id' => $control_id, 'type' => $control_type, 'class' => "integers $className", 'style' => 'width:100px; text-align:right;', 'label' => false));
                                                break;
                                            case 'double':
                                                $dynamic_control = $this->Form->input($control_name, array('id' => $control_id, 'type' => $control_type, 'class' => "decimals $className", 'style' => 'width:100px; text-align:right;', 'label' => false));
                                                break;
                                            default:
                                                break;
                                        }
                                        break;

                                    case 'textarea':
                                        $dynamic_control = $this->Form->input($control_name, array('type' => $control_type, 'id' => $control_id, 'escape' => false, 'label' => false));
                                        break;

                                    case 'select':
                                        $dynamic_control = $this->Form->input($control_name, array('type' => $control_type, 'options' => $options, 'id' => $control_id, 'empty' => '---Select---', 'label' => false));
                                        break;

                                    case 'select_or_label':
                                        if (!empty($field_value_from_session)) {
                                            $value = !empty($options[$field_value_from_session]) ? $options[$field_value_from_session] : '';
                                            $dynamic_control = $this->Form->input('', array('type' => 'text', 'id' => $control_id, 'value' => $value, 'disabled' => 'disabled', 'label' => false)) .
                                                    $this->Form->input($control_name, array('type' => 'hidden', 'id' => $control_id, 'value' => $field_value_from_session, 'label' => false));
                                        } else {
                                            $dynamic_control = $this->Form->input($control_name, array('type' => 'select', 'options' => $options, 'id' => $control_id, 'empty' => '---Select---', 'label' => false));
                                        }
                                        break;

                                    case 'dependent_dropdown':
                                        //$dynamic_control = "<div id='control_id_to_be_updated$parent_or_child_control_id'></div>";
                                        $dynamic_control = $this->Form->input($control_name, array('type' => 'select', 'options' => $options, 'id' => 'control_id_to_be_updated' . $parent_or_child_control_id, 'empty' => '---Select---', 'label' => false));
                                        break;

                                    case 'label':
                                        $dynamic_control = $this->Form->input($control_name, array('type' => 'text', 'id' => $control_id, 'value' => $label, 'disabled' => 'disabled', 'label' => false));
                                        break;

                                    case 'radio':
                                        $dynamic_control = $this->Form->input($control_name, array('type' => $control_type, 'id' => $control_id, 'options' => $options, 'label' => false, 'legend' => false));
                                        break;

                                    case 'checkbox':
                                        $options = array();
                                        $dynamic_control = $this->Form->input($control_name, array('type' => $control_type, 'id' => $control_id, 'multiple' => 'checkbox', 'options' => $options, 'escape' => true, 'label' => false));
                                        break;

                                    case 'date':
                                        $dynamic_control = $this->Form->input($control_name, array('type' => 'hidden', 'id' => $control_id . '_alt', 'label' => false))
                                                . "<div><input type='text' id='$control_id' class='date_picker' style='width:100px !important;' /></div>";
                                        break;

                                    case 'current_date':
                                        $dynamic_control = $this->Form->input($control_name, array('type' => 'hidden', 'id' => $control_id, 'value' => $current_date, 'label' => false));
                                        break;

                                    case 'year_month':
                                        $dynamic_control = $this->Form->year("'" . $model_name . "." . $control_id . "'", date('Y') - 15, date('Y'), array('id' => $control_id, 'empty' => "--Select Year--", 'style' => 'width:192px;margin:2px 2px 2px 5px;')) . $this->Form->month("'" . $model_name . "." . $control_id . "'", array('empty' => "--Select Month--", 'style' => 'width:192px;')) . $this->Form->day("'" . $model_name . "." . $control_id . "'", array('empty' => false, 'style' => 'display:none;'));
                                        break;

                                    case 'date_range':
                                        $dynamic_control = $this->Form->input('', array('type' => 'text', 'id' => $control_id, 'value' => $period, 'disabled' => 'disabled', 'label' => false)) .
                                                $this->Form->input($control_name, array('type' => 'hidden', 'id' => $control_id, 'value' => $period_id, 'label' => false));
                                        break;

                                    case 'hidden_date_range':
                                        $dynamic_control = $this->Form->input($control_name, array('type' => 'hidden', 'id' => $control_id, 'value' => $date_value, 'label' => false));
                                        break;

                                    case 'hidden':
                                        $value = ($field_name == 'submission_status') ? '0' : '';
                                        $field_label = '';

                                        $dynamic_control = $this->Form->input($control_name, array('type' => $control_type, 'id' => $control_id, 'value' => $value, 'label' => false));
                                        break;

                                    default:
                                        break;
                                }

                                $common_controls .= "<td>$dynamic_control</td>";
                            }
                        }

                        $dr_sub_controls .= $common_controls;
                        $dr_sub_controls .= '</tr>';

                        $all_sub_rowspan += 1;
                    }
                }

                if (!empty($dr_cat_controls))
                    $dr_cat_controls .= '<tr>';

                $dr_cat_controls .= "<td rowspan='$all_sub_rowspan' class='td_header'>$cat_title</td>";
                $dr_cat_controls .= $dr_sub_controls;
//                    $dr_all_controls .= $dr_cat_controls;
            } else {
                $dr_cat_controls = '<tr>';
                $cat_controls = "<strong>$cat_title</strong>" . $this->Form->input("$control_cat_name.$cat_field_name", array('type' => 'hidden', 'value' => $cat_id, 'label' => false));

                $dr_cat_controls .= "<td>$cat_controls</td>";

                $common_controls = '';
                foreach ($field_details_list as $group_id => $field_list) {
                    foreach ($field_list as $field_id => $field_details) {

                        $field_name = $field_details['field_name'];
                        $data_type = $field_details['data_type'];
                        $control_type = $field_details['control_type'];
                        $is_mandatory_for_add = $field_details['is_mandatory_for_add'];
                        $child_model_id = $field_details['child_model_id'];
                        $parent_or_child_control_id = $field_details['parent_or_child_control_id'];

                        $control_id = $control_cat_ids . '_' . $field_name;
                        $control_name = "$control_cat_name.$field_name";

                        $date_value = $field_details['date_value'];
                        $current_date = $field_details['current_date'];
                        $field_value_from_session = $field_details['field_value_from_session'];
                        $has_notes = $field_details['has_notes'];

                        $options = $field_details['options'];

                        $className = "clsGroup_" . $group_id . "_" . $control_cat_ids;
                        $dynamic_control = '';

                        switch ($control_type) {
                            case 'calculated_label':
                                $field_names = explode(" as ", $field_name);
                                $field_name = count($field_names) > 1 ? $field_names[1] : $field_names[0];
                                $str_sum = str_replace(' ', '', $field_names[0]);

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

                                $control_name = "$control_cat_name.$field_name";
                                $dynamic_control = $this->Form->input($control_name, array('id' => "sum_$field_id", 'type' => 'text', 'class' => 'readonly', 'style' => 'width:100px; text-align:right;', 'readonly' => 'readonly', 'label' => false));
                                break;

                            case 'aggregate_label':
                                $field_names = explode(" as ", $field_name);
                                $field_name = count($field_names) > 1 ? $field_names[1] : $field_names[0];
                                $all_script .= "$('.$className').each(function() { " .
                                        "$(this).keyup(function() { " .
                                        "var total = 0;" .
                                        "$('.$className').each(function () {" .
                                        "total += parseFloat($(this).val()) || 0;" .
                                        "});" .
                                        "$('#sum_$className').val(total);" .
                                        "});" .
                                        "$(this).change(function() { " .
                                        "var total = 0;" .
                                        "$('.$className').each(function () {" .
                                        "total += parseFloat($(this).val()) || 0;" .
                                        "});" .
                                        "$('#sum_$className').val(total);" .
                                        "});" .
                                        "});";

                                $control_name = "$control_cat_name.$field_name";
                                $dynamic_control = $this->Form->input($control_name, array('id' => "sum_$className", 'type' => 'text', 'class' => 'readonly', 'style' => 'width:100px; text-align:right;', 'readonly' => 'readonly', 'label' => false));
                                break;

                            case 'text':
                                switch ($data_type) {
                                    case 'varchar':
                                        $dynamic_control = $this->Form->input($control_name, array('id' => $control_id, 'type' => $control_type, 'label' => false));
                                        break;
                                    case 'int':
                                        $dynamic_control = $this->Form->input($control_name, array('id' => $control_id, 'type' => $control_type, 'class' => "integers $className", 'style' => 'width:100px; text-align:right;', 'label' => false));
                                        break;
                                    case 'double':
                                        $dynamic_control = $this->Form->input($control_name, array('id' => $control_id, 'type' => $control_type, 'class' => "decimals $className", 'style' => 'width:100px; text-align:right;', 'label' => false));
                                        break;
                                    default:
                                        break;
                                }
                                break;

                            case 'textarea':
                                $dynamic_control = $this->Form->input($control_name, array('type' => $control_type, 'id' => $control_id, 'escape' => false, 'label' => false));
                                break;

                            case 'select':
                                $dynamic_control = $this->Form->input($control_name, array('type' => $control_type, 'options' => $options, 'id' => $control_id, 'empty' => '---Select---', 'label' => false));
                                break;

                            case 'select_or_label':
                                if (!empty($field_value_from_session)) {
                                    $value = !empty($options[$field_value_from_session]) ? $options[$field_value_from_session] : '';
                                    $dynamic_control = $this->Form->input('', array('type' => 'text', 'id' => $control_id, 'value' => $value, 'disabled' => 'disabled', 'label' => false)) .
                                            $this->Form->input($control_name, array('type' => 'hidden', 'id' => $control_id, 'value' => $field_value_from_session, 'label' => false));
                                } else {
                                    $dynamic_control = $this->Form->input($control_name, array('type' => 'select', 'options' => $options, 'id' => $control_id, 'empty' => '---Select---', 'label' => false));
                                }
                                break;

                            case 'dependent_dropdown':
                                //$dynamic_control = "<div id='control_id_to_be_updated$parent_or_child_control_id'></div>";
                                $dynamic_control = $this->Form->input($control_name, array('type' => 'select', 'options' => $options, 'id' => 'control_id_to_be_updated' . $parent_or_child_control_id, 'empty' => '---Select---', 'label' => false));
                                break;

                            case 'label':
                                $dynamic_control = $this->Form->input($control_name, array('type' => 'text', 'id' => $control_id, 'value' => $label, 'disabled' => 'disabled', 'label' => false));
                                break;

                            case 'radio':
                                $dynamic_control = $this->Form->input($control_name, array('type' => $control_type, 'id' => $control_id, 'options' => $options, 'label' => false, 'legend' => false));
                                break;

                            case 'checkbox':
                                $options = array();
                                $dynamic_control = $this->Form->input($control_name, array('type' => $control_type, 'id' => $control_id, 'multiple' => 'checkbox', 'options' => $options, 'escape' => true, 'label' => false));
                                break;

                            case 'date':
                                $dynamic_control = $this->Form->input($control_name, array('type' => 'hidden', 'id' => $control_id . '_alt', 'label' => false))
                                        . "<div><input type='text' id='$control_id' class='date_picker' style='width:100px !important;' /></div>";
                                break;

                            case 'current_date':
                                $dynamic_control = $this->Form->input($control_name, array('type' => 'hidden', 'id' => $control_id, 'value' => $current_date, 'label' => false));
                                break;

                            case 'year_month':
                                $dynamic_control = $this->Form->year("'" . $model_name . "." . $control_id . "'", date('Y') - 15, date('Y'), array('id' => $control_id, 'empty' => "--Select Year--", 'style' => 'width:192px;margin:2px 2px 2px 5px;')) . $this->Form->month("'" . $model_name . "." . $control_id . "'", array('empty' => "--Select Month--", 'style' => 'width:192px;')) . $this->Form->day("'" . $model_name . "." . $control_id . "'", array('empty' => false, 'style' => 'display:none;'));
                                break;

                            case 'date_range':
                                $dynamic_control = $this->Form->input('', array('type' => 'text', 'id' => $control_id, 'value' => $period, 'disabled' => 'disabled', 'label' => false)) .
                                        $this->Form->input($control_name, array('type' => 'hidden', 'id' => $control_id, 'value' => $period_id, 'label' => false));
                                break;

                            case 'hidden_date_range':
                                $dynamic_control = $this->Form->input($control_name, array('type' => 'hidden', 'id' => $control_id, 'value' => $date_value, 'label' => false));
                                break;

                            case 'hidden':
                                $value = ($field_name == 'submission_status') ? '0' : '';
                                $field_label = '';

                                $dynamic_control = $this->Form->input($control_name, array('type' => $control_type, 'id' => $control_id, 'value' => $value, 'label' => false));
                                break;

                            default:
                                break;
                        }

                        $common_controls .= "<td>$dynamic_control</td>";
                    }
                }

                $dr_cat_controls .= $common_controls;
                $dr_cat_controls .= '</tr>';

                $dr_all_controls .= $dr_cat_controls;
            }
            $dr_all_controls .= $dr_cat_controls;
        }

        if (!empty($dr_all_controls)) {
            echo "<div class='multi_tbl_wrapper'>"
            . "<table id='multi_tbl_view' class='multi_tbl_view'>"
            . "<thead>$dr_header_group $dr_header_title</thead>"
            . "<tbody>$dr_all_controls</tbody>"
            //. "<tfoot></tfoot>"
            . "</table></div>";

            $all_script .= "$('#multi_tbl_view').fixedHeaderTable({altClass: 'odd', footer: false, cloneHeadToFoot: false, autoShow: true, autoResize: true});";
        
            ////$all_script .= "$('#multi_tbl_view').fixedHeaderTable({altClass: 'odd', footer: false, cloneHeadToFoot: false, autoShow: true, autoResize: true});";
            //$all_script .= !empty($cat_level) ? "$('#multi_tbl_view').fixedHeaderTable({altClass: 'odd', footer: false, cloneHeadToFoot: false, autoShow: true, fixedColumns: 1, autoResize: true});" : "$('#multi_tbl_view').fixedHeaderTable({altClass: 'odd', footer: false, cloneHeadToFoot: false, autoShow: true, fixedColumns: 0, autoResize: true});";
        }
        ?>

        <div class="btns-div" style="margin-top:5px;"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php
                        $close_opt = array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'view?model_id=' . $model_id . '&data_type_id=' . $data_type_id);

                        $is_submit = $this->Session->read('SubmissionStatus.IsSubmit');
                        if (!empty($is_submit) && $is_submit == '1') {
                            $close_opt = array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'view_for_final_submission?model_id=' . $model_id . '&data_type_id=' . $data_type_id . '&is_submit=' . $is_submit);
                        }

                        echo $this->Js->link('Close', $close_opt, array_merge($pageLoading, array('class' => 'mybtns', 'confirm' => 'Are you sure to Close ?')));
                        //echo $this->Js->link('Close', array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => "view?model_id=" . $model_id . "&data_type_id=" . $data_type_id), array_merge($pageLoading, array('class' => 'mybtns', 'confirm' => 'Are you sure to Close ?'))); 
                        ?>
                    </td>
                    <td style="text-align: center;">
                        <?php
                        echo $this->Js->submit($is_edit ? 'Update' : 'Save', array_merge($pageLoading, array('class' => 'mybtns', 'confirm' => 'Are you sure to save data ?', 'success' => "msg.init('success', '$title', '$title has been added successfully.');",
                            'error' => "msg.init('error', '$title', 'Insertion failed !');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <?php echo $this->Form->end(); ?>
    </fieldset>


    <script type="text/javascript">

        $(function () {

            $('.integers').numeric({decimal: false, negative: false});
            $('.decimals').numeric({decimal: ".", negative: true});

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

    <?php echo $all_script; ?>

        });

    </script>

<?php } ?>