
<?php
//echo $this->Html->script("map/high-maps");
//echo $this->Html->script("chart/high-charts");

if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}

$isAdmin = (!empty($user_group_id) && $user_group_id == 1);

$title = "Report Viewer";
?>

<fieldset>
    <legend><?php echo $title; ?></legend>

    <div id="data_div" style="width:100%; height:auto; overflow-x:auto;">

        <table style="width:100%;">
            <tr>
                <td style="vertical-align:top;">
                    <fieldset style="margin:0 5px 5px;">
                        <legend>▣ <?php echo $report_title; ?></legend>
                        <div id="divReport" style="width:auto; height:100%; min-width:300px; min-height:300px; max-height:485px; max-height:58vh; padding:2px 2px 5px 7px; line-height:1.8; overflow:auto;">
                            <style>
                                .rpt-list {
                                    margin: 1px 3px !important;
                                }
                            </style>

                            <?php
                            echo $this->Form->create('ReportModuleReportViewerReportSelect');

                            echo $this->Form->input("ModelList", array('id' => 'modelId', 'type' => 'radio', 'class' => 'multi-radio rpt-list', 'options' => $model_list, 'separator' => '<br />', 'empty' => 'None', 'escape' => false, 'legend' => false, 'div' => false, 'label' => true));

                            echo $this->Form->end();
                            ?>
                        </div>
                    </fieldset>
                </td>
                <td style="vertical-align:top;">
                    <fieldset style="margin:0 5px 5px;">
                        <legend>▣ Report Fields</legend>
                        <div id="divField" style="width:auto; height:100%; min-width:300px; padding:2px 3px;"></div>
                    </fieldset>
                </td>
            </tr>

        </table>

        <?php
        $this->Js->get('.rpt-list')->event('change', $this->Js->request(
                        array('controller' => 'ReportModuleReportViewers', 'action' => 'report_viewer_selected_fields'), array(
                    'beforeSend' => '$("#busy-indicator").fadeIn();',
                    'complete' => '$("#busy-indicator").fadeOut();',
                    'update' => '#divField',
                    'async' => true,
                    'method' => 'post',
                    'dataExpression' => true,
                    'data' => '$(this).closest("form").serialize()'
                ))
        );
        ?>

    </div>

    <?php
//    echo $this->Js->link('Bangladesh Bank-1', array('controller' => 'ReportModuleReportViewers', 'action' => 'report_viewer_bb1'), array('update' => '#report_bb_content', 'class' => 'btnlink',
//        'beforeSend' => '$("#busy-indicator").fadeIn();',
//        'complete' => '$("#busy-indicator").fadeOut(); modal_close("report_opt"); modal_open("report_bb_viewer", 0, "Bangladesh Bank Report"); ', //modal.init("Report", "report_view"); modal.init("MRA Report", "report_bb_viewer"); 'complete' => '$("#busy-indicator").fadeOut(); modal.init("MRA Report", "report_bb_viewer");',
//        'confirm' => 'Are you sure to Generate Report ?',
//        'success' => "modal_close('report_opt'); modal_open('report_bb_viewer', 0, 'Bangladesh Bank Report'); ",
//        'error' => "msg.init('error', 'Report Generator', 'Report Generation failed !');"));
    ?>

    <?php
//    echo $this->Js->link('Bangladesh Bank-2', array('controller' => 'ReportModuleReportViewers', 'action' => 'report_viewer_bb2'), array('update' => '#report_bb_content', 'class' => 'btnlink',
//        'beforeSend' => '$("#busy-indicator").fadeIn();',
//        'complete' => '$("#busy-indicator").fadeOut(); modal_close("report_opt"); modal_open("report_bb_viewer", 0, "Bangladesh Bank Report"); ', //modal.init("Report", "report_view"); modal.init("MRA Report", "report_bb_viewer"); 'complete' => '$("#busy-indicator").fadeOut(); modal.init("MRA Report", "report_bb_viewer");',
//        'confirm' => 'Are you sure to Generate Report ?',
//        'success' => "modal_close('report_opt'); modal_open('report_bb_viewer', 0, 'Bangladesh Bank Report'); ",
//        'error' => "msg.init('error', 'Report Generator', 'Report Generation failed !');"));
//    ?>

    <?php
//    echo $this->Js->link('Map Test', array('controller' => 'ReportModuleReportViewers', 'action' => 'branches_location_map_new'), array('update' => '#report_map', 'class' => 'btnlink',
//        'beforeSend' => '$("#report_map div").remove(); $("#busy-indicator").fadeIn();',
//        'complete' => '$("#busy-indicator").fadeOut();'));
//    ?>

    <?php
//    echo $this->Js->link('Map', array('controller' => 'ReportModuleReportViewers', 'action' => 'report_map_viewer'), array('update' => '#report_map', 'class' => 'btnlink',
//        'beforeSend' => '$("#report_map div").remove(); $("#busy-indicator").fadeIn();',
//        'complete' => '$("#busy-indicator").fadeOut();'));
    ?>

    <div id="report_map" style="width:100%; height:350px;">
    </div>

    <div id="report_bb_viewer_bg" class="modal-bg">
        <div id="report_bb_viewer" class="modal-content">

            <div id="report_bb_viewer_title" class="modal-title">
                <span class="modal-title-txt">Bangladesh Bank Report</span>
                <button class="close" onclick="if (confirm('Are you sure to Close ?'))
                            modal_close('report_bb_viewer');
                        return false;">✖</button>
            </div>

            <div style="float: right; border: 0 none; width: auto; margin: 0; padding: 0;">
                <button id="btnPrint" class="my-btns" style="margin: 0; font: bold 13px/1.3 Helvetica,Arial,sans-serif;" onclick="print_report('report_bb_content', 'Bangladesh Bank Report'); return false;">Print</button>
            </div>

            <div id="report_bb_content" style="width:auto; height:auto; max-height:500px; max-height:80vh; margin:0; padding:5px; overflow:auto; cursor:default;">

            </div>

            <div class="btns-div" style="margin-top:0; padding: 5px; text-align: center;">
                <button class="modal-close" onclick="if (confirm('Are you sure to Close ?'))
                            modal_close('report_bb_viewer');
                        return false;">Close</button>
            </div>

        </div>
    </div>

</fieldset>


<script>

    $(function () {
        //draggable_modal('report_opt_title', 'report_opt', 'report_opt_bg');
        draggable_modal('report_bb_viewer_title', 'report_bb_viewer', 'report_bb_viewer_bg');
    });

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
