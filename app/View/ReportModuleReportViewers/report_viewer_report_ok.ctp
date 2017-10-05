
<style>

    .tbl-rpt-view {
        width: auto;
        margin: 0;
        padding: 0;
        color: #222;
        font: normal 12px/1.25 Verdana, Helvetica, Arial, sans-serif;
        border-collapse: collapse;
        border-spacing: 0;
    }
    .tbl-rpt-view th, .tbl-rpt-view tr th, 
    .tbl-rpt-view tr.td_header td, .tbl-rpt-view tr td.td_header {
        border: 1px solid #ddd;
        padding: 6px 4px;
        color: #222;
        line-height: 1.5;
        font-weight: bold;

        background: #fafcfd;
        background: -moz-linear-gradient(top,#fafafa 0%,#f3f3f3 25%,#ededed 80%,#e5e5e5 100%);
        background: -webkit-linear-gradient(top,#fafafa 0%,#f3f3f3 25%,#ededed 80%,#e5e5e5 100%);
        background: linear-gradient(to bottom,#fafafa 0%,#f3f3f3 25%,#ededed 80%,#e5e5e5 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fafafa', endColorstr='#e5e5e5',GradientType=0);
    }
    .tbl-rpt-view td, .tbl-rpt-view tr td {
        border: 1px solid #ddd;
        margin: 0;
        padding: 4px;
        color: #222;
        font: normal 13px/1.5 Verdana, Helvetica, Arial, sans-serif;

        background: #fcfdff;
    }
    .tbl-rpt-view tr.odd td {
        background: #eaf8fd;
    }
    .tbl-rpt-view tr:hover td, .tbl-rpt-view tr.odd:hover td {
        color: #fff;
        background: #0073aa;

        -webkit-text-shadow: none;
        -moz-text-shadow: none;
        text-shadow: none;
    }


    .report-container table {
        width: auto;
        margin: 0;
        padding: 0;
        color: #222;
        font: normal 12px/1.25 Verdana, Helvetica, Arial, sans-serif;
        border-collapse: collapse;
        border-spacing: 0;
    }
    .report-container table th, .report-container table td {
        border: 1px solid #ddd;
        padding: 5px;
        color: #222;
        line-height: 1.5;
    }
</style>


<div id="my_report">
    <?php
    if (!empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }

    debug($model_wise_data_details);

    if (empty($model_wise_data_details)) {
        echo '<p class="error">Report generation has been failed !</p><p class="error">Report data not available !</p>';
        return;
    }

    echo $this->Html->script("table-sorter");
    echo $this->Html->script("table-sorter-widgets");
    ?>

    <div id="report_viewer">

        <?php
        echo $this->Html->css("table-sorter-style", null, array("inline" => true));

        $is_sub_title = true;
        foreach ($model_wise_data_details as $model_name => $model_wise_data) {
            $all_data = $model_wise_data['all_data'];
            $selected_field_list = $model_wise_data['selected_field_list'];
            $selected_field_types = $model_wise_data['selected_field_types'];
            $field_group_list = $model_wise_data['field_group_list'];
            $group_wise_field_title = $model_wise_data['group_wise_field_title'];
            $group_wise_field_sub_title = $model_wise_data['group_wise_field_sub_title'];

            $fixed_field_details = $model_wise_data['fixed_field_details'];

            if (!empty($all_data) && !empty($selected_field_list)) {
                $dr_header_group = '';
                $dr_header_title = '';
                $dr_header_sub_title = '';
                $dr_all_data = '';

                $rc = 0;

//                debug($selected_field_types);

                if (!empty($fixed_field_details['fixed_field_list']) && count($fixed_field_details['fixed_field_list']) > 0) {
                    $fixed_field_list = $fixed_field_details['fixed_field_list'];
                    $fixed_field_data = $fixed_field_details['fixed_field_data'];

                    echo "<table class='tbl-rpt-view' style='margin:8px 5px;'>";

                    foreach ($fixed_field_list as $field_id => $fixed_field) {
                        foreach ($group_wise_field_title as $group_id => $field_list) {
                            if (!isset($field_list[$field_id]))
                                continue;

                            $fixed_data_title = $group_wise_field_title[$group_id][$field_id];

                            unset($group_wise_field_title[$group_id][$field_id]);

                            if (empty($group_wise_field_title[$group_id]))
                                unset($group_wise_field_title[$group_id]);

                            try {
                                $fixed_data_field_details = explode('.', $fixed_field);
                                $model_name = $fixed_data_field_details[0];
                                $field_name = $fixed_data_field_details[1];

                                $fixed_data_value = $fixed_field_data[$model_name][$field_name];
                            } catch (Exception $ex) {
                                $fixed_data_value = '';
                            }

                            echo (($rc % 2 == 0) ? "<tr>" : "<tr class='odd'>")
                            . "<td style='min-width:165px; font-weight:bold;'>$fixed_data_title</td>"
                            . "<td style='font-weight:bold;'>:</td>"
                            . "<td style='width:90%;'>$fixed_data_value</td></tr>";
                            ++$rc;

                            break;
                        }
                    }
                    echo "</table>";
                }

//                debug($field_group_list);
//                debug($group_wise_field_title);

                $any_groupe_title = array_filter($field_group_list);
                $is_sub_title = !empty($group_wise_field_sub_title) && count(array_filter($group_wise_field_sub_title));


                foreach ($group_wise_field_title as $group_id => $field_list) {
                    if (empty($field_list) || count($field_list) < 1) {
                        unset($group_wise_field_title[$group_id]);
                        continue;
                    }

                    $no_of_column = count($field_list);
                    $group_title = (!empty($field_group_list[$group_id]) ? $field_group_list[$group_id] : "");

                    if (empty($group_title)) {// && $no_of_column == 1
                        foreach ($field_list as $field_id => $field_title) {
                            if (empty($field_id)) {
                                unset($group_wise_field_title[$group_id][$field_id]);
                                continue;
                            }

                            $dr_header_group .= $any_groupe_title ? "<th rowspan='2'>$field_title</th>" : "<th>$field_title</th>";
//                            $dr_header_title .= "<th class='empty'></th>";
//                            $dr_header_group .= "<th>$field_title</th>";

                            if ($is_sub_title) {
                                $field_sub_title = !empty($group_wise_field_sub_title[$field_id]) ? $group_wise_field_sub_title[$field_id] : '';
                                $dr_header_sub_title .= (!empty($field_sub_title) ? "<th class='sub-title'>$field_sub_title</th>" : "<th class='sub-title empty'></th>");
                            }
                        }

                        continue;
                    }

                    if (!array_filter($field_list)) {
                        if (!empty($group_title) && $no_of_column > 1) {
                            $dr_header_group .= "<th colspan='$no_of_column'>$group_title</th>";
                            $dr_header_title .= "<th class='empty' colspan='$no_of_column'></th>";
                            if ($is_sub_title)
                                $dr_header_sub_title .= "<th class='sub-title empty' colspan='$no_of_column'></th>";
                        } else {
                            $dr_header_group .= "<th>$group_title</th>";
                            $dr_header_title .= "<th class='empty'></th>";
                            if ($is_sub_title)
                                $dr_header_sub_title .= "<th class='sub-title empty'></th>";
                        }
                        continue;
                    }

                    foreach ($field_list as $field_id => $field_title) {
                        if (empty($field_id) || !isset($selected_field_list[$field_id])) {
                            unset($group_wise_field_title[$group_id][$field_id]);
                            --$no_of_column;
                            continue;
                        }

                        $dr_header_title .= (!empty($field_title) ? "<th>$field_title</th>" : "<th class='empty'></th>");

                        if ($is_sub_title) {
                            $field_sub_title = !empty($group_wise_field_sub_title[$field_id]) ? $group_wise_field_sub_title[$field_id] : '';
                            $dr_header_sub_title .= (!empty($field_sub_title) ? "<th class='sub-title'>$field_sub_title</th>" : "<th class='sub-title empty'></th>");
                        }
                    }

                    if ($group_id >= 0 && $no_of_column > 0) {
                        $dr_header_group .= "<th" . ($no_of_column > 1 ? " colspan='$no_of_column'>" : ">") . "$group_title</th>";
                    }
                }

                if (!empty($dr_header_group))
                    $dr_header_group = "<tr>$dr_header_group</tr>";
                if (!empty($dr_header_title))
                    $dr_header_title = "<tr>$dr_header_title</tr>";
                if ($is_sub_title && !empty($dr_header_sub_title))
                    $dr_header_sub_title = "<tr>$dr_header_sub_title</tr>";


                $rc = 0;
                foreach ($all_data as $data) {
                    $dr_data = '';
                    foreach ($group_wise_field_title as $group_id => $field_list) {

                        foreach ($field_list as $field_id => $field) {

                            if (!isset($selected_field_list[$field_id]))
                                continue;

                            try {
                                $data_field_details = $selected_field_list[$field_id];
                                $data_field_details = explode('.', $data_field_details);
                                $model_name = $data_field_details[0];
                                $field_name = $data_field_details[1];

                                $data_value = $data[$model_name][$field_name];
                            } catch (Exception $ex) {
                                $data_value = '';
                            }

                            $text_align = 'left';
                            if ($data_value != '') {
                                $field_type = trim($selected_field_types[$field_id]);
                                switch ($field_type) {
                                    case 'int':
                                        $text_align = 'right';
                                        $data_value = number_format($data_value, 0, '', ',');
                                        break;

                                    case 'double':
                                        $text_align = 'right';
                                        $data_value = number_format($data_value, 2, '.', ',');
                                        break;

                                    case 'date':
                                        $text_align = 'center';
                                        $data_value = $this->Time->format($data_value, '%d-%m-%Y', 'invalid');
                                        break;

                                    case 'int_text':
                                    case 'num_text':
                                    case 'cent_text':
                                    case 'date_text':
                                    case 'date_range':
                                        $text_align = 'center';
                                        break;

                                    default:
                                        break;
                                }

//                                if (!empty($field_type)) {
//                                    if ($field_type == 'int') {
//                                        $text_align = 'right';
//                                        $data_value = number_format($data_value, 0, '', ',');
//                                    } else if ($field_type == 'double') {
//                                        $text_align = 'right';
//                                        $data_value = number_format($data_value, 2, '.', ',');
//                                    } else if ($field_type == 'date') {
//                                        $text_align = 'center';
//                                        $data_value = $this->Time->format($data_value, '%d-%m-%Y', 'invalid');
//                                    } else if ($field_type == 'int_text' || $field_type == 'num_text') {
//                                        $text_align = 'center';
//                                    }
//                                }
//                                if (strpos($field_name, 'form_serial_no') !== false || strpos($field_name, 'license_no') !== false || strpos($field_name, 'year') !== false || strpos($field_name, '_id') !== false) {
//                                    $text_align = 'center';
//                                }
                            }

                            $dr_data .= "<td style='text-align:$text_align;'>$data_value</td>";
                            //$dr_data .= "<td style='text-align:" . (is_numeric(str_replace(array(' ', '%'), array('', ''), $data_value)) ? "right" : "left") . ";'>$data_value</td>";
                        }
                    }

                    if (!empty($dr_data)) {
                        if ($rc % 2 == 0)
                            $dr_all_data .= "<tr>$dr_data</tr>";
                        else
                            $dr_all_data .= "<tr class='odd'>$dr_data</tr>";
                        ++$rc;
                    }
                }

                //for Total fields 
                $dr_total_value = '';
                if ($is_total_show && $rc > 1) {
                    $dr_data = '';
                    $er_count = 0;
                    $total_count = 0;

                    foreach ($group_wise_field_title as $group_id => $field_list) {

                        $data_value = '';
                        foreach ($field_list as $field_id => $field) {

                            if (!isset($selected_field_list[$field_id]) && strpos($selected_field_list[$field_id], '.') === false)
                                continue;

                            $field_type = $selected_field_types[$field_id];
                            if (empty($field_type) || ($field_type != 'int' && $field_type != 'double')) {
                                $data_value = '';
                                if (empty($dr_data)) {
                                    ++$er_count;
                                } else {
                                    $dr_data .= "<td style='text-align:right; font-weight:bold;'>$data_value</td>";
                                }
                                continue;
                            }

                            $data_field_details = explode('.', $selected_field_list[$field_id]);
                            $model_name = $data_field_details[0];
                            $field_name = $data_field_details[1];

                            try {
                                $data_all_value = Hash::extract($all_data, "{n}.$model_name.$field_name");
                                $data_value = array_sum($data_all_value);

                                if ($field_type == 'int') {
                                    $data_value = number_format($data_value, 0, '', ',');
                                } else if ($field_type == 'double') {
                                    $data_value = number_format($data_value, 2, '.', ',');
                                }

                                ++$total_count;
                            } catch (Exception $ex) {
                                $data_value = '';
                            }

                            if (empty($dr_data)) {
                                if ($data_value === '') {
                                    ++$er_count;
                                } else {
                                    $dr_data .= "<td " . ($er_count > 1 ? "colspan='$er_count' " : "") . "style='text-align:right; font-weight:bold;'>Total:</td> <td style='text-align:right; font-weight:bold;'>$data_value</td>";
                                }
                            } else {
                                $dr_data .= "<td style='text-align:right; font-weight:bold;'>$data_value</td>";
                            }
                        }
                    }

                    if ($total_count > 0 && !empty($dr_data)) {
                        $dr_total_value = "<tfoot><tr class='td_header'>$dr_data</tr></tfoot>";
                    }
                }

                if (!empty($dr_all_data)) {
                    echo "<table id='tbl_data' class='tablesorter'>"
                    . "<thead>"
                    . $dr_header_group
                    . $dr_header_title
                    . $dr_header_sub_title
                    . "</thead> <tbody>"
                    . $dr_all_data
                    . "</tbody>"
                    . $dr_total_value
                    . "</table>";

                    echo "<p style='font-weight:bold; text-align:right;'> No. of Records: " . count($all_data) . "</p>";
                }
            }
        }
        ?>

        <div id="charts"></div>

    </div>

</div>


<div style="margin: 3px auto;">
    <?php
//    $pageLoading = array('update' => '#charts', 'class' => 'my-btns', 'evalScripts' => true,
//        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
//        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
//
//    echo $this->Js->link('Chart', array('controller' => 'ReportQueryGenerators', 'action' => 'set_chart_opts'), $pageLoading);
    ?>

    <button id="btnPrint" class="my-btns" onclick="print_report('my_report', 'MF-DBMS Report');">Print</button>
    <button id="myBtn" class="my-btns" onclick="modal.init('MF-DBMS Report', 'report_viewer');">Print</button>
</div>

<script>

    $(function () {
        draggable_modal('report_viewer_title', 'report_viewer', 'report_viewer_bg');

//        $('#tbl_data').tablesorter();
        $('table').tablesorter({
            widgets: ['zebra', 'columns'],
            usNumberFormat: true
        });

    });

    function print_report(report_div_id, report_title) {
        if (!confirm('Are you sure to Print ?'))
            return false;

        if (!report_title)
            report_title = 'MF-DBMS Report';
        var w = 1020;
        var h = 580;
        if (window.screen) {
            w = window.screen.availWidth;
            h = window.screen.availHeight;
        }

        var objWindow = window.open("mra_report", "MF-DBMS Report", "top=20,left=20,width=" + w + ",height=" + h + ",location=0,toolbar=0,statusbar=0,menubar=0,scrollbars=1,resizable=1");
        objWindow.document.write('<html> <head><title>');
        objWindow.document.write(report_title);
        objWindow.document.write('</title></head> <body><div class="report-container">');
        objWindow.document.write($('#' + report_div_id).html());
        objWindow.document.write('</div></body> </html>');
        objWindow.document.close();
        objWindow.focus();
        objWindow.print();
        return false;
    }

</script>

