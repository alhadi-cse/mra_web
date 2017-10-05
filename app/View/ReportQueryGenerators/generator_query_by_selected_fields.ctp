
<div id="my_report">
    <?php
    if (!empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }

    if (empty($model_wise_data_details))
        return;

    echo $this->Html->css("report-template", null, array("inline" => true));
    ?>

    <div id="report_viewer">

        <?php
        $is_sub_title = true;
        foreach ($model_wise_data_details as $model_name => $model_wise_data) {
            $all_data = $model_wise_data['all_data'];
            $selected_field_list = $model_wise_data['selected_field_list'];
            $field_group_detail_list = $model_wise_data['field_group_detail_list'];
            $group_wise_field_title = $model_wise_data['group_wise_field_title'];
            $group_wise_field_sub_title = $model_wise_data['group_wise_field_sub_title'];

            $is_sub_title = !empty($group_wise_field_sub_title) && count(array_filter($group_wise_field_sub_title));

            if (!empty($all_data) && !empty($selected_field_list)) {
                $dr_header_group = '';
                $dr_header_title = '';
                $dr_header_sub_title = '';
                $dr_all_data = '';

                if (isset($group_wise_field_title[0])) {
                    $basic_field_list = $group_wise_field_title[0];
                    unset($group_wise_field_title[0]);

                    if (count($basic_field_list) > 0) {
                        echo "<table class='tbl-report'>";
                        foreach ($basic_field_list as $field_id => $field_title) {
                            $basic_field_details = $selected_field_list[$field_id];
                            $basic_field_details = explode('.', $basic_field_details);
                            $model_name = $basic_field_details[0];
                            $field_name = $basic_field_details[1];
                            $basic_value = $all_data[0][$model_name][$field_name];

                            echo "<tr><td style='min-width:150px; font-weight:bold;'>$field_title</td>"
                            . "<td style='font-weight:bold;'>:</td>"
                            . "<td style='width:90%;'>$basic_value</td></tr>";
                        }
                        echo "</table>";
                    }
                }


                foreach ($group_wise_field_title as $group_id => $field_list) {
                    if (empty($group_id) || empty($field_list) || count($field_list) < 1) {
                        unset($group_wise_field_title[$group_id]);
                        continue;
                    }

                    $no_of_column = count($field_list);

                    $group_title = (!empty($field_group_detail_list[$group_id]) ? $field_group_detail_list[$group_id] : "");

                    if (empty($group_title) && $no_of_column == 1) {
                        foreach ($field_list as $field_id => $field_title) {
                            if (empty($field_id)) {
                                unset($group_wise_field_title[$group_id][$field_id]);
                                continue;
                            }

                            $dr_header_group .= "<th rowspan='2'>$field_title</th>";
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
                        if ($no_of_column > 1) {
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
                        if (empty($field_id)) {
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

                    if ($group_id > 0 && $no_of_column > 0) {
                        $dr_header_group .= "<th" . ($no_of_column > 1 ? " colspan='$no_of_column'>" : ">") . "$group_title</th>";
                    }
                }

                if (!empty($dr_header_group))
                    $dr_header_group = "<tr>$dr_header_group</tr>";
                if (!empty($dr_header_title))
                    $dr_header_title = "<tr>$dr_header_title</tr>";
                if ($is_sub_title && !empty($dr_header_sub_title))
                    $dr_header_sub_title = "<tr>$dr_header_sub_title</tr>";

//    debug($dr_header_group);
//    debug($dr_header_title);

                foreach ($all_data as $data) {
                    $dr_data = '';
                    foreach ($group_wise_field_title as $group_id => $field_list) {

                        foreach ($field_list as $field_id => $field) {
                            $data_field_details = $selected_field_list[$field_id];
                            $data_field_details = explode('.', $data_field_details);
                            $model_name = $data_field_details[0];
                            $field_name = $data_field_details[1];

                            $data_value = $data[$model_name][$field_name];
                            $dr_data .= "<td style='text-align:" . (is_numeric($data_value) ? "right" : "center") . ";'>$data_value</td>";
                        }
                    }

                    if (!empty($dr_data))
                        $dr_all_data .= "<tr>$dr_data</tr>";
                }

//    debug($dr_all_data);
                //for Total fields  
                //true

                if (true) {
                    $dr_data = '';
                    $data_value = '';

                    foreach ($group_wise_field_title as $group_id => $field_list) {

                        if (empty($dr_data) && !array_filter($field_list)) {
                            $no_of_column = count($field_list);
                            $dr_data .= "<td " . ($no_of_column > 1 ? "colspan='$no_of_column' " : "")
                                    . "style='text-align:center; font-weight:bold;'>Total:</td>";

                            continue;
                        }

                        foreach ($field_list as $field_id => $field) {
                            $data_field_details = $selected_field_list[$field_id];
                            $data_field_details = explode('.', $data_field_details);
                            $model_name = $data_field_details[0];
                            $field_name = $data_field_details[1];

                            try {
                                $data_all_value = Hash::extract($all_data, "{n}.$model_name.$field_name");
                                $is_num = true;
                                foreach ($data_all_value as $key => $value) {
                                    if (!empty($value) && !is_numeric($value)) {
                                        $is_num = false;
                                        break;
                                    }
                                }

                                $data_value = $is_num ? array_sum($data_all_value) : (empty($dr_data) ? 'Total:' : '');
                            } catch (Exception $ex) {
                                $data_value = '';
                            }

                            $dr_data .= "<td style='text-align:right; font-weight:bold;'>$data_value</td>";
                        }
                    }

                    if (!empty($dr_data))
                        $dr_all_data .= "<tr>$dr_data</tr>";
                }

                if (!empty($dr_all_data)) {
                    echo "<table class='tbl-report'>"
                    . $dr_header_group
                    . $dr_header_title
                    . $dr_header_sub_title
                    . $dr_all_data
                    . "</table>";
                }
            }
        }
        ?>

        <?php
        $pageLoading = array('update' => '#charts', 'class' => 'my-btns', 'evalScripts' => true,
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

        echo $this->Js->link('Chart', array('controller' => 'ReportQueryGenerators', 'action' => 'set_chart_opts'), $pageLoading);
        ?>

        <div id="charts">
        </div>

    </div>

</div>


<div>
    <button id="btnPrint" class="my-btns" onclick="print_report('my_report', 'Print Report');">Print</button>
    <button id="myBtn" class="my-btns" onclick="modal.init('Print Report', 'report_viewer');">Print</button>
</div>

<script>

    function print_report(report_div_id, report_title) {
        if (!confirm('Are you sure to Print ?'))
            return false;

        if (!report_title)
            report_title = 'MRA Report';
        var w = 1020;
        var h = 580;
        if (window.screen) {
            w = window.screen.availWidth;
            h = window.screen.availHeight;
        }

        var objWindow = window.open("mra_report", "PrintWindow", "top=20,left=20,width=" + w + ",height=" + h + ",location=0,toolbar=0,statusbar=0,menubar=0,scrollbars=1,resizable=1");
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

