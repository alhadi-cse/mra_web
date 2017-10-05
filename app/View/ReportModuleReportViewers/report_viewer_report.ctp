
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

    if (empty($main_data_table)) {
        echo '<p class="error">Report generation has been failed !</p><p class="error">Report data not available !</p>';
        return;
    }

    echo $this->Html->script("table-sorter");
    echo $this->Html->script("table-sorter-widgets");
    echo $this->Html->script("table-export-excel");
    
    
    
    echo $this->Html->script("table-export");
    
    

    echo "<div id='report_viewer'>";

    echo $this->Html->css("table-sorter-style", null, array("inline" => true));


    echo "<table id='tbl_report_data' style='margin:0; padding:0;' cellspacing='5' cellpadding='5'>";

    if (!empty($report_title))
        echo "<tr><th style='font-size:15px;'>$report_title</th></tr> <tr><td></td></tr>";

    if (!empty($fixed_data_table))
        echo "<tr><td>$fixed_data_table</td></tr> <tr><td></td></tr>";

    if (!empty($main_data_table))
        echo "<tr><td>$main_data_table</td></tr> <tr><td></td></tr>";

    if (!empty($total_records_count))
        echo "<tr><td style='padding:0 10px; font-weight:bold; font-size:14px; text-align:left;'> # No. of Records: $total_records_count</td></tr>";

    echo "</table>";

    echo "</div>";
    ?>


    <div id="charts"></div>

</div>


<div style="margin: 3px auto;">
    <?php
//    $pageLoading = array('update' => '#charts', 'class' => 'my-btns', 'evalScripts' => true,
//        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
//        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
//
//    echo $this->Js->link('Chart', array('controller' => 'ReportQueryGenerators', 'action' => 'set_chart_opts'), $pageLoading);
//    debug($report_title);
    ?>

    <button id="btnPrint" class="my-btns" onclick="print_report('my_report', 'MF-DBMS Report');">Print</button>
    <button id="myBtn" class="my-btns" onclick="modal.init('MF-DBMS Report', 'report_viewer');">Print</button>
    <button id="btnExportExcel" class="my-btns" onclick="export_to_excel('<?php echo $report_title; ?>');">Export Excel</button>
    <!--<button id="btnExport" class="my-btns">Export Excel 2</button>-->
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

    function export_to_excel(fileName) {
        $("#tbl_report_data").exportToexcel({
            containerid: "tbl_report_data",
            datatype: 'table',
            fileName: fileName,
            worksheetName: "MF-DBMS Data"
        });
    }


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

