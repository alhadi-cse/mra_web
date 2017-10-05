
<style>

    .all_fields {
        margin: 0 0 3px;
        padding: 0 0 0 5px;
        width: auto;
        max-height: 30vh;
        overflow: auto;
    }
    #divUpaz fieldset {
        -moz-column-count: 2;
        -moz-column-gap: 3px;
    }
    #all_filtering_opt input[type="text"], #all_filtering_opt select {
        height: 28px;
        line-height: 21px;
    }
    #all_filtering_opt input[type="text"] {
        padding: 0 5px;
    }
    #all_filtering_opt select {
        padding: 0 3px;
    }
    .inline-checkbox {
        float: left;
        margin: 3px 5px;
        width: auto;
    }
    .div-list, .dist-list, .upaz-list {
        height: auto;
        min-width: 125px !important;
        width: auto !important;
    }

    .tbl-opt-view    {
        border-collapse: collapse;
        border-spacing: 0;
        width: auto;
        height: auto;
        margin: 0 5px 5px 5px;
        padding: 0;
        color: #222;
        font: normal 12px/1.25 Verdana, Helvetica, Arial, sans-serif;
        overflow: auto;
    }    
    .tbl-opt-view th  {
        border: 1px solid #ddd;
        padding: 6px 3px;
        background: rgba(0, 0, 0, 0) linear-gradient(to bottom, #f8fafc, #f3f5f7) repeat scroll 0 0;
    }
    .tbl-opt-view td  {
        border: 1px solid #ddd;
        padding: 4px;
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

//debug($model_name);
//debug($report_list);

if (!empty($report_list) && !empty($report_fields_list)) {

    $scripts = '';
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    ?>

    <div style="width:auto; height:auto; margin:0; padding:2px 10px 7px 10px; text-align:right;">
        <button id="report_filtering" class="mybtns" onclick="modal_open('report_opt'); return false;">Report >></button>
    </div>

    <div id="report_opt_bg" class="modal-bg" style="z-index:199;">
        <div id="report_opt" class="modal-content" style="margin:10px 15px;">

            <div id="report_opt_title" class="modal-title">
                <span class="modal-title-txt">▣ Report Options</span>
                <button class="close" onclick="if (confirm('Are you sure to Close ?'))
                            modal_close('report_opt');
                        return false;">✖</button>
            </div>


            <?php echo $this->Form->create('ReportModuleReportViewer', array('id' => 'rptViewerForm')); ?>

            <div id="all_filtering_opt" style="width:auto; height:auto; max-height:500px; max-height:82vh; margin:0; padding:5px; overflow:auto; cursor:default;">


                <table style="width:100%;">
                    <tr>
                        <td style="vertical-align:top;">

                            <table style="width:100%;">
                                <tr>
                                    <td style="vertical-align:top;">
                                        <fieldset style="margin:0 4px; padding:5px 10px;">
                                            <legend>▣ Basic Options</legend>

                                            <?php
                                            foreach ($report_list as $rpt_id => $report_title) {
                                                if (empty($rpt_id) || empty($report_title))
                                                    continue;

                                                $legend_title = "<label for='rpt_$rpt_id'>▣ $report_title</label>"
                                                        . "<input type='checkbox' id='rpt_$rpt_id' type='checkbox' class='all-checked' style='margin:0 0 0 4px; vertical-align:middle;'>";

                                                echo "<fieldset style='margin-top:2px;'>"
                                                . "<legend>$legend_title</legend>"
                                                . "<div id='all_fields' class='all_fields'>"
                                                . $this->Form->input("ReportFieldList.$model_id", array('type' => 'select', 'multiple' => 'checkbox', 'class' => 'multi-checkbox field-list', 'options' => $report_fields_list, 'escape' => false, 'div' => false, 'label' => false))
                                                . "</div>"
                                                . "</fieldset>";
                                            }
                                            ?>

                                            <fieldset style="margin-top:3px;">
                                                <legend>▣ Select:</legend>

                                                <table cellpadding="5" cellspacing="5" border="0">
                                                    <tr>
                                                        <td style="width:100px;"><?php echo $this->Form->input("BasicOpt.order_dir", array('type' => 'select', 'options' => array('desc' => 'Top', 'asc' => 'Last'), 'style' => 'width:100px;', 'label' => false, 'div' => false)); ?></td>
                                                        <td class="colons">:</td>
                                                        <td><?php echo $this->Form->input("BasicOpt.limit", array('length' => 5, 'class' => 'integers', 'style' => 'width:80px;', 'label' => false, 'div' => false)); ?></td>
                                                    </tr>
                                                    <?php if (!empty($group_wise_period_list) && count($group_wise_period_list) > 0) { ?>
                                                        <tr>
                                                            <td>Data Period</td>
                                                            <td class="colons">:</td>
                                                            <td><?php echo $this->Form->input("BasicOpt.period_id", array('id' => 'period_id', 'type' => 'select', 'options' => $group_wise_period_list, 'empty' => '---- Select Period ----', 'style' => 'width:150px;', 'label' => false, 'div' => false)); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>From Date</td>
                                                            <td class="colons">:</td>
                                                            <td>
                                                                <?php
                                                                echo $this->Form->input("BasicOpt.from_date", array('type' => 'hidden', 'id' => 'txtReportFromDate_alt', 'label' => false, 'div' => false))
                                                                . "<input type='text' id='txtReportFromDate' class='date_picker' style='width:100px !important;' />";
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>To Date</td>
                                                            <td class="colons">:</td>
                                                            <td>
                                                                <?php
                                                                echo $this->Form->input("BasicOpt.to_date", array('type' => 'hidden', 'id' => 'txtReportToDate_alt', 'label' => false, 'div' => false))
                                                                . "<input type='text' id='txtReportToDate' class='date_picker' style='width:100px !important;' />";
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </table>
                                            </fieldset>

                                            <fieldset style="margin-bottom:5px;">
                                                <legend>▣ Organization:</legend>

                                                <div id="divOrgs" style="clear: both; border: 0 none; width:auto; height:auto; margin:0; padding:0;">
                                                    <?php
                                                    echo "<div style='width:275px; margin:0; padding:0; text-align:left;'><div style='width:auto; margin:0; padding:0;'>"
                                                    . $this->Form->input("BasicOpt.org_id", array('id' => 'listOrg', 'type' => 'select', 'class' => 'org-list', 'style' => 'position:static; left:0; width:270px;', 'options' => $org_list, 'empty' => '------- Select Organization -------', 'escape' => false, 'div' => false, 'label' => false))
                                                    . "</div>"
                                                    . "<span style='font-weight:bold;'>filter:<input type='text' id='txtFilter_listOrg' style='width:100px !important; margin-left:5px;'></span></div>";

                                                    $this->Js->get('.field-list')->event('change', 'CheckMultiCheckBox(this);');
                                                    $this->Js->get('.all-checked')->event('change', 'CheckAllMultiCheckBox(this);');

                                                    $this->Js->get('.org-list')->event('change', $this->Js->request(
                                                                    array('controller' => 'ReportModuleReportViewers', 'action' => 'selected_admin_divs'), array(
                                                                'beforeSend' => '$("#busy-indicator").fadeIn();',
                                                                'complete' => '$("#busy-indicator").fadeOut();',
                                                                'update' => '#divDiv',
                                                                'async' => true,
                                                                'method' => 'post',
                                                                'dataExpression' => true,
                                                                'data' => '$(this).closest("form").serialize()'
                                                            ))
                                                    );
                                                    $this->Js->get('.org-list')->event('change', $this->Js->request(
                                                                    array('controller' => 'ReportModuleReportViewers', 'action' => 'selected_admin_dists'), array(
                                                                'beforeSend' => '$("#busy-indicator").fadeIn();',
                                                                'complete' => '$("#busy-indicator").fadeOut();',
                                                                'update' => '#divDist',
                                                                'async' => true,
                                                                'method' => 'post',
                                                                'dataExpression' => true,
                                                                'data' => '$(this).closest("form").serialize()'
                                                            ))
                                                    );
                                                    $this->Js->get('.org-list')->event('change', $this->Js->request(
                                                                    array('controller' => 'ReportModuleReportViewers', 'action' => 'selected_admin_upzas'), array(
                                                                'beforeSend' => '$("#busy-indicator").fadeIn();',
                                                                'complete' => '$("#busy-indicator").fadeOut();',
                                                                'update' => '#divUpaz',
                                                                'async' => true,
                                                                'method' => 'post',
                                                                'dataExpression' => true,
                                                                'data' => '$(this).closest("form").serialize()'
                                                            ))
                                                    );

                                                    $this->Js->get('.org-list')->event('change', $this->Js->request(
                                                                    array('controller' => 'ReportModuleReportViewers', 'action' => 'selected_org_branches'), array(
                                                                'beforeSend' => '$("#busy-indicator").fadeIn();',
                                                                'complete' => '$("#busy-indicator").fadeOut();',
                                                                'update' => '#divBranch',
                                                                'async' => true,
                                                                'method' => 'post',
                                                                'dataExpression' => true,
                                                                'data' => '$(this).closest("form").serialize()'
                                                            ))
                                                    );
                                                    ?>
                                                    <div id="divBranch" style="clear: both; width:auto; height:auto; max-height:200px; max-height:45vh; margin:0; padding:2px 3px; overflow:auto;">
                                                    </div>
                                                </div>
                                            </fieldset>

                                        </fieldset>
                                    </td>

                                    <td style="vertical-align:top;">

                                        <?php
                                        echo '<fieldset style="min-width:280px; margin:0 4px; padding:5px 10px;">' .
                                        '<legend>▣ Filtering</legend>';

                                        echo '<table cellpadding="0" cellspacing="0" border="0">';

                                        if (!empty($cat_fields_list) || !empty($filter_fields)) {
                                            $scripts = '';
                                            $operators = array('=' => '=', '<>' => '<>', '<' => '<', '>' => '>', 'LIKE' => 'Like');

                                            echo '<tr> <td>'
                                            . '<fieldset style="margin-top:2px;">'
                                            . '<legend>▣ Filter Fields:</legend>';

                                            echo '<table class="tbl-opt-view" cellpadding="0" cellspacing="0" border="0">'
                                            . '<tr> <th>Data Field</th> <th>Condition</th> <th>Value</th> </tr>';

                                            $total_opts = 5;
                                            $operation_count = 0;
                                            if (!empty($cat_fields_list) && count($cat_fields_list) > 0) {
                                                foreach ($cat_fields_list as $field_id => $cat_field_details) {

                                                    $field_name = $cat_field_details['field_name'];
                                                    $field_label = $cat_field_details['field_label'];
                                                    $control_type = $cat_field_details['control_type'];
                                                    $child_model_id = $cat_field_details['child_model_id'];
                                                    $parent_control_id = $cat_field_details['parent_control_id'];
                                                    $parent_or_child_control_id = $cat_field_details['parent_or_child_control_id'];
                                                    $options = $cat_field_details['options'];

                                                    $control_id = "ctlId_$field_name";
                                                    $input_control = $this->Form->input("FilterFieldList.$operation_count.field", array('type' => 'hidden', 'value' => "$model_name.$field_name", 'style' => 'width:0; margin:0; padding:0;', 'div' => false, 'label' => false))
                                                            . $this->Form->input("FilterFieldList.$operation_count.operator", array('type' => 'hidden', 'value' => '=', 'style' => 'width:0; margin:0; padding:0;', 'div' => false, 'label' => false));

                                                    $data_id = "FilterFieldList.$operation_count.value";
                                                    switch ($control_type) {
                                                        case 'select':
                                                            $input_control .= $this->Form->input($data_id, array('id' => $control_id, 'type' => 'select', 'options' => $options, 'style' => 'width:150px;', 'empty' => '---- select value ----', 'div' => false, 'label' => false));
                                                            break;

                                                        case 'dependent_dropdown':
                                                            $parent_control_id = "ctlId_$parent_control_id";
                                                            $scripts .= " $('#$control_id').filterGroups({groupSelector: '#$parent_control_id', emptyValue: true}); ";
                                                            $input_control .= $this->Form->input($data_id, array('id' => $control_id, 'type' => 'select', 'options' => $options, 'style' => 'width:150px;', 'empty' => '---- select value ----', 'div' => false, 'label' => false));
                                                            break;

                                                        default:
                                                            continue;
                                                            break;
                                                    }
                                                    ++$operation_count;

                                                    echo "<tr> <td style='width:150px;'>$field_label</td>"
                                                    . "<td style='width:50px; text-align:center; font-weight:600;'>=</td>"
                                                    . "<td style='width:170px;'>$input_control</tr>";
                                                }
                                            }

                                            if (!empty($filter_fields) && count($filter_fields) > 0) {
                                                for (; $operation_count < $total_opts; ++$operation_count) {
                                                    echo '<tr> <td>'
                                                    . $this->Form->input("FilterFieldList.$operation_count.field", array('type' => 'select', 'style' => 'width:153px;', 'options' => $filter_fields, 'empty' => '---- select field ----', 'escape' => false, 'div' => false, 'label' => false))
                                                    . '</td> <td style="text-align:center;">'
                                                    . $this->Form->input("FilterFieldList.$operation_count.operator", array('type' => 'select', 'style' => 'width:60px;', 'options' => $operators, 'empty' => '', 'escape' => false, 'div' => false, 'label' => false))
                                                    . '</td> <td>'
                                                    . $this->Form->input("FilterFieldList.$operation_count.value", array('type' => 'text', 'style' => 'width:138px;', 'escape' => false, 'div' => false, 'label' => false))
                                                    . '</td> </tr>';
                                                }
                                            }

                                            echo '</table> </fieldset>'
                                            . '</td> </tr>';
                                        }

                                        if (!empty($group_by_fields) && count($group_by_fields) > 0) {
                                            echo '<tr> <td>'
                                            . '<fieldset style="min-width:280px; margin-bottom:5px;">'
                                            . '<legend>▣ Group by Fields:</legend>'
                                            . $this->Form->input("GroupByFieldList.fields", array('type' => 'select', 'multiple' => 'checkbox', 'class' => 'multi-checkbox inline-checkbox', 'style' => 'margin:3px 8px;', 'options' => $group_by_fields, 'escape' => false, 'legend' => false, 'div' => false, 'label' => false))
                                            . '</fieldset>'
                                            . '</td> </tr>';
                                        }

                                        if (!empty($order_by_fields) && count($order_by_fields) > 0) {
                                            $orders = array('ASC' => 'Ascending', 'DESC' => 'Descending');

                                            echo '<tr> <td>'
                                            . '<fieldset style="min-width:280px; margin-bottom:5px;">'
                                            . '<legend>▣ Order by Fields:</legend>';
                                            for ($order_count = 0; $order_count < 3; ++$order_count) {
                                                //$order_by_fields = $report_fields_list;
                                                echo $this->Form->input("OrderByFieldList.$order_count.field", array('type' => 'select', 'style' => 'width:153px; margin:3px 8px;', 'options' => $order_by_fields, 'empty' => '---- select field ----', 'escape' => false, 'div' => false, 'label' => false))
                                                . $this->Form->input("OrderByFieldList.$order_count.order", array('type' => 'select', 'style' => 'width:105px;', 'options' => $orders, 'escape' => false, 'div' => false, 'label' => false))
                                                . '<br />';
                                            }
                                            echo '</fieldset>'
                                            . '</td> </tr>';
                                        }

                                        echo '</table>';
                                        echo '</fieldset>';
                                        ?>

                                    </td>
                                </tr>
                            </table>

                        </td>

                        <td style="vertical-align:top;">
                            <fieldset style="margin:2px 4px; padding:5px 10px;">
                                <legend>▣ Admin Boundary</legend>
                                <table>
                                    <tr>
                                        <td style="vertical-align:top;">
                                            <fieldset style="margin:0 3px 3px;">
                                                <legend>▣ Division:</legend>
                                                <div id="divDiv" style="width:auto; height:100%; min-width:120px; min-height:400px; max-height:500px; max-height:70vh; padding:2px 3px; resize:both; overflow:auto;">
                                                    <?php
                                                    echo $this->Form->create('ReportModuleReportViewerDivSelect');
                                                    echo $this->Form->input("listDiv", array('id' => 'listDiv', 'type' => 'select', 'multiple' => 'checkbox', 'class' => 'multi-checkbox div-list', 'options' => $div_list, 'escape' => false, 'div' => false, 'label' => false));
                                                    echo $this->Form->end();

                                                    $this->Js->get('.field-list')->event('change', 'CheckMultiCheckBox(this);');
                                                    $this->Js->get('.all-checked')->event('change', 'CheckAllMultiCheckBox(this);');

                                                    $this->Js->get('.div-list')->event('change', $this->Js->request(
                                                                    array('controller' => 'ReportModuleReportViewers', 'action' => 'selected_orgs'), array(
                                                                'beforeSend' => '$("#busy-indicator").fadeIn();',
                                                                'complete' => '$("#busy-indicator").fadeOut();',
                                                                'update' => '#divOrgs',
                                                                'async' => true,
                                                                'method' => 'post',
                                                                'dataExpression' => true,
                                                                'data' => '$(this).closest("form").serialize()'
                                                            ))
                                                    );

                                                    $this->Js->get('.div-list')->event('change', $this->Js->request(
                                                                    array('controller' => 'ReportModuleReportViewers', 'action' => 'selected_org_branches'), array(
                                                                'beforeSend' => '$("#busy-indicator").fadeIn();',
                                                                'complete' => '$("#busy-indicator").fadeOut();',
                                                                'update' => '#divBranch',
                                                                'async' => true,
                                                                'method' => 'post',
                                                                'dataExpression' => true,
                                                                'data' => '$(this).closest("form").serialize()'
                                                            ))
                                                    );

                                                    $this->Js->get('.div-list')->event('change', $this->Js->request(
                                                                    array('controller' => 'ReportModuleReportViewers', 'action' => 'selected_admin_dists'), array(
                                                                'beforeSend' => '$("#busy-indicator").fadeIn();',
                                                                'complete' => '$("#busy-indicator").fadeOut();',
                                                                'update' => '#divDist',
                                                                'async' => true,
                                                                'method' => 'post',
                                                                'dataExpression' => true,
                                                                'data' => '$(this).closest("form").serialize()'
                                                            ))
                                                    );

                                                    $this->Js->get('.div-list')->event('change', $this->Js->request(
                                                                    array('controller' => 'ReportModuleReportViewers', 'action' => 'selected_admin_upzas'), array(
                                                                'beforeSend' => '$("#busy-indicator").fadeIn();',
                                                                'complete' => '$("#busy-indicator").fadeOut();',
                                                                'update' => '#divUpaz',
                                                                'async' => true,
                                                                'method' => 'post',
                                                                'dataExpression' => true,
                                                                'data' => '$(this).closest("form").serialize()'
                                                            ))
                                                    );
                                                    ?>
                                                </div>
                                            </fieldset>
                                        </td>

                                        <td style="vertical-align:top;">
                                            <fieldset style="margin:0 3px 3px;">
                                                <legend>▣ District:</legend>
                                                <div id="divDist" style="width:auto; height:100%; min-width:125px; min-height:400px; max-height:500px; max-height:70vh; padding:2px 3px; resize:both; overflow:auto;">
                                                </div>
                                            </fieldset>
                                        </td>

                                        <td style="vertical-align:top;">
                                            <fieldset style="margin:0 3px 3px;">
                                                <legend>▣ Upazila:</legend>
                                                <div id="divUpaz" style="width:auto; height:100%; min-width:130px; min-height:400px; max-height:500px; max-height:70vh; padding:2px 3px; resize:both; overflow:auto;">
                                                </div>
                                            </fieldset>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </td>
                    </tr>

                </table>

            </div>

            <?php echo $this->Form->end(); ?>


            <div class="btns-div" style="margin-top:0; padding:5px; text-align: center;">

                <input type="submit" class="modal-close" value="Close" onclick="if (confirm('Are you sure to Close ?'))
                            modal_close('report_opt');
                        return false;">

                <?php
                echo $this->Js->submit('Report', array(
                    'id' => 'btn_report',
                    'url' => array('controller' => 'ReportModuleReportViewers', 'action' => 'report_viewer_report'),
                    'update' => '#report_content',
                    'class' => 'modal-close',
                    'div' => false,
                    'async' => true,
                    'method' => 'post',
                    'dataExpression' => true,
                    'data' => '$("#rptViewerForm").serialize()',
                    //'data' => '$(this).closest("form").serialize()',
                    'beforeSend' => 'if(!any_field_selected()){msg.init("warning", "Report Generator", "Select fields for Report !"); return false;} $("#busy-indicator").fadeIn();',
                    'complete' => '$("#busy-indicator").fadeOut();', //modal.init("Report", "report_view"); modal.init("$report_title Report", "report_viewer"); 'complete' => '$("#busy-indicator").fadeOut(); modal.init("$report_title Report", "report_viewer");',
                    'confirm' => 'Are you sure to view Report ?',
                    'success' => "modal_close('report_opt'); modal_open('report_viewer', 0 ,'$report_title Report');",
                    'error' => 'msg.init("error", "Report Generator", "Report Generation failed !");'));

                echo $this->Js->submit('Reset All', array(
                    'id' => 'btn_reset_all',
                    'url' => array('controller' => 'ReportModuleReportViewers', 'action' => 'report_viewer_reset_filter', $model_id),
                    'update' => '#all_filtering_opt',
                    'class' => 'modal-close',
                    'div' => false,
                    'async' => true,
                    'method' => 'post',
                    'dataExpression' => true,
                    'confirm' => 'Reset all the Options ?',
                    'beforeSend' => '$("#busy-indicator").fadeIn();',
                    'complete' => '$("#busy-indicator").fadeOut();'
                ));
                ?>
            </div>
        </div>
    </div>

    <div id="report_viewer_bg" class="modal-bg">
        <div id="report_viewer" class="modal-content">

            <div id="report_viewer_title" class="modal-title">
                <span class="modal-title-txt modal-title-report-bg"><?php echo "$report_title Report"; ?></span>
                <button class="close" onclick="if (confirm('Are you sure to Close ?'))
                            modal_close('report_viewer');
                        return false;">✖</button>
            </div>

            <div id="report_content" style="width:auto; height:auto; max-height:500px; max-height:80vh; margin:0; padding:5px; overflow:auto; cursor:default;">

            </div>

            <div class="btns-div" style="margin-top:0; padding:5px; text-align: center;">
                <button class="modal-close" onclick="if (confirm('Are you sure to Close ?'))
                            modal_close('report_viewer');
                        return false;" title="Close Report Viewer.">Close</button>
                <button class="modal-close" onclick="if (confirm('Are you sure to Back to Report Options ?')) {
                            modal_close('report_viewer');
                            modal_open('report_opt');
                        }
                        return false;" title="Back to Report Options.">Back</button>
            </div>

        </div>
    </div>

    <?php
    //    $this->Js->get('.field-list')->event('change', 'CheckMultiCheckBox(this);');
    //    $this->Js->get('.all-checked')->event('change', 'CheckAllMultiCheckBox(this);');

    $this->Js->get('#period_type_id')->event('change', $this->Js->request(
                    array('controller' => 'ReportModuleReportViewers', 'action' => 'selected_data_periods'), array(
                'beforeSend' => '$("#busy-indicator").fadeIn();',
                'complete' => '$("#busy-indicator").fadeOut();',
                'update' => '#period_id',
                'async' => true,
                'method' => 'post',
                'dataExpression' => true,
                'data' => '$(this).closest("form").serialize()'
            ))
    );
    ?>

    <script type="text/javascript">

        $(function () {

            $('#org_list').filterByText($('#txtFilter_listOrg'), true);
            //        draggable_modal('report_opt_title', 'report_opt', 'report_opt_bg');
            //        draggable_modal('report_viewer_title', 'report_viewer', 'report_viewer_bg');

            //$('#btn_report').prop('disabled', 'disabled');
            //            $('#report_filtering').prop('disabled', 'disabled');


            //            $("#btn_close").bind("click", function (event) {
            //                var _confirm = confirm("Are you sure to Close ?");
            //                if (!_confirm) {
            //                    return false;
            //                }
            //                $.ajax({
            //                    beforeSend: function (XMLHttpRequest) {
            //                        $("#busy-indicator").fadeIn();
            //                    }, 
            //                    complete: function (XMLHttpRequest, textStatus) {
            //                        $("#busy-indicator").fadeOut();
            //                    }, 
            //                    data: $("#btn_close").closest("form").serialize(), 
            //                    dataType: "html", 
            //                    success: function (data, textStatus) {
            //                        modal_close('report_opt');
            //                        return false;
            //                        $("#").html(data);
            //                    }, 
            //                    type: "post", 
            //                    url: "\/mra_web\/ReportModuleReportViewers\/report_viewer_selected_fields"}
            //                        );
            //                return false;
            //            });


    <?php if (!empty($scripts)) echo $scripts; ?>

            $('#ui-datepicker-div').remove();
            $('#ui-datepicker-div').empty();
            $('.date_picker').each(function () {
                $(this).datepicker({
                    showAnim: 'show',
                    dateFormat: 'dd-mm-yy',
                    altField: '#' + this.id + '_alt',
                    altFormat: "yy-mm-dd",
                    showOtherMonths: true,
                    showOn: 'both',
                    buttonImageOnly: true,
                    buttonImage: 'img/calendar.gif',
                    buttonText: 'Click to show the calendar'
                });
            }).on('change', function (e) {
                if (!$(this).val())
                    $('#' + this.id + '_alt').val('');
            });

        });


        function any_field_selected() {
            return $("#all_fields").find(".multi-checkbox input:checkbox:checked").length > 0;
            //var noOfSelectedFields = $chkbParent.find(".multi-checkbox input:checkbox:checked").length;
        }


        function get_total_by_class(className) {
            var total = 0;
            $('.' + className).each(function () {
                total += parseFloat($(this).val()) || 0;
            });
            return total;
        }

        function set_total_by_class(sumFieldId, className) {
            var total = 0;
            $('.' + className).each(function () {
                total += parseFloat($(this).val()) || 0;
            });

            $('#' + sumFieldId).val(total);
            return;
        }

        function CheckAllMultiCheckBox(obj) {
            $(obj).closest("fieldset").find(".multi-checkbox input:checkbox").prop("checked", $(obj).prop("checked"));

            //$('#btn_report').prop('disabled', $(obj).prop("checked") === false ? 'disabled' : '');
            //            $('#report_filtering').prop('disabled', $(obj).prop("checked") === false ? 'disabled' : '');
        }

        function CheckMultiCheckBox(obj) {
            var $chkbParent = $(obj).closest("fieldset");
            var noOfCheckBoxes = $chkbParent.find(".multi-checkbox input:checkbox").length;

            var optChecked = null;
            if ($(this).prop("checked")) {
                var noOfCheckedBoxes = $chkbParent.find(".multi-checkbox input:checkbox:checked").length;
                if (noOfCheckedBoxes === 0) {
                    optChecked = false;
                }
                if (noOfCheckedBoxes === noOfCheckBoxes) {
                    optChecked = true;
                }
            } else {
                var noOfUnCheckedBoxes = $chkbParent.find(".multi-checkbox input:checkbox:not(:checked)").length;
                if (noOfUnCheckedBoxes === 0) {
                    optChecked = true;
                }
                if (noOfUnCheckedBoxes === noOfCheckBoxes) {
                    optChecked = false;
                }
            }

            $chkbParent.find("input:checkbox.all-checked").prop("indeterminate", optChecked === null);
            $chkbParent.find("input:checkbox.all-checked").prop("checked", optChecked);

            //$('#btn_report').prop('disabled', optChecked === false ? 'disabled' : '');
            //            $('#report_filtering').prop('disabled', optChecked === false ? 'disabled' : '');

            return false;
        }


        function report_options_set(evt) {
            if (!confirm("Are you sure to Report Options Setting ?"))
                return false;

            $.ajax({
                type: "post",
                url: "\/ReportModuleReportViewers\/report_options_set\/1",
                beforeSend: function (XMLHttpReq) {
                    $("#busy-indicator").fadeIn();
                },
                complete: function (XMLHttpReq, textStatus) {
                    $("#busy-indicator").fadeOut();
                },
                data: $(this).closest("form").serialize(),
                dataType: "html", error: function (XMLHttpReq, textStatus, errThrown) {
                    msg.init('error', 'Report Options', 'Setting failed !');
                },
                evalScripts: true, success: function (data, textStatus) {
                    msg.init('success', 'Report Options', 'Setting has been completed.');
                    modal_close('report_opt');
                    $("#").html(data);
                }
            });
            return false;
        }

    </script>

    <?php
} else {
    echo '<p class="error">Model Fields not found !</p>';
}
?>