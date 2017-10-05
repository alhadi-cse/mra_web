
<div id="my_report">
    <?php
    //debug($report_details);
    //debug($report_details);
    //    return;

    if (!empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }

    if (empty($report_details))
        return;

    echo $this->Html->css("report-template", null, array("inline" => true));
    ?>

    <div id="report_viewer">

        <?php
        $report_header_list = $report_details['report_header_list'];
        $report_data = $report_details['report_data'];

        $sl_no = 0;

        echo "<table class='tbl-report'>";

        echo "<tr>";
        foreach ($report_header_list as $report_header) {
            echo "<th>$report_header</th>";
        }
        echo "</tr>";

        foreach ($report_data as $field_id => $data) {
            ++$sl_no;

            foreach ($data as $data_title => $data_value) {
//            debug($data);
//            $data_title = $data_value = key($data);
//            $data_title = array_key($data);
//            $data_value = array_value($data);

                echo "<tr>"
                . "<td style='width:45px; font-weight:bold; text-align:center;'>$sl_no.</td>"
                . "<td style='width:70%;font-weight:bold;'>$data_title:</td>"
                //. "<td style='font-weight:bold;'>:</td>"
                . "<td style='width:25%;'>$data_value</td>"
                . "</tr>";

                break;
            }
        }
        echo "</table>";
        ?>

        <?php
        $pageLoading = array('update' => '#charts', 'class' => 'my-btns', 'evalScripts' => true,
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

        //echo $this->Js->link('Chart', array('controller' => 'ReportModuleReportViewers', 'action' => 'set_chart_opts'), $pageLoading);
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

