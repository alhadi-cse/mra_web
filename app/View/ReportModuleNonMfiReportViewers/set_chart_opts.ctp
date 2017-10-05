
<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}
?>

<fieldset class="not_for_print">
    <legend>Chart Options</legend>

    <?php echo $this->Form->create('ReportChartGeneratorOptions'); ?>

    <div style="width:auto; height:auto; padding:3px 20px;">
        <?php
        //$chart_types = array('all_chart' => 'All Charts', 'line_chart' => 'Line Chart', 'bar_chart' => 'Bar Chart', 'pie_chart' => 'Pie Chart');
        $chart_types = array('bar_chart' => 'Bar Chart', 'line_chart' => 'Line Chart', 'pie_chart' => 'Pie Chart');
        echo $this->Form->input('chart_type', array('type' => 'select', 'onchange' => "set_chart(this.value)", 'options' => $chart_types, 'default' => 'bar_chart', 'label' => '<strong>Chart Type : </strong>', 'div' => false, 'style' => 'width:120px;'));
        ?>
        <div id="chart_opt_header" class="menu-img" style="display: inline;">Chart Series/Fields Option</div>
    </div>

    <!--<div id="chart_opt" style="clear:both;margin:0;padding:0;"></div>-->
    <!--<div data-role="main" class="ui-content"></div>-->

    <div id="chart_opt" style="clear:both; width: auto; margin: 0; padding: 0;">
        <div id="divField" style="float:left; width:48%; height:100%; padding:5px 5px 0; overflow:auto;">
            <?php
            $title = "<label for='all_series'>Chart Series </label>"
                    . "<input type='checkbox' id='all_series' class='all-checked' checked='checked' style='margin:2px 5px; vertical-align:text-top;'>";
            echo $this->Form->input("fieldSeries", array('type' => 'select', 'multiple' => 'checkbox', 'class' => 'multi-checkbox chart-series', 'options' => array($title => $chartSeries), 'escape' => false, 'div' => false, 'label' => false));

            $this->Js->get('#all_series')->event('change', 'CheckAllMultiCheckBox(this);');
            $this->Js->get('.chart-series')->event('change', 'CheckMultiCheckBox(this);');
            ?>
        </div>

        <div id="divField" style="float:right; width:48%; height:100%; padding:5px 5px 0; overflow:auto;">
            <?php
            $title = "<label for='all_fields'>Chart Fields  </label>"
                    . "<input type='checkbox' id='all_fields' class='all-checked' checked='checked' style='margin:2px 5px; vertical-align:text-top;'>";
            echo $this->Form->input("fieldList", array('type' => 'select', 'multiple' => 'checkbox', 'class' => 'multi-checkbox chart-fields', 'options' => array($title => $chartFields), 'escape' => false, 'div' => false, 'label' => false));

            $this->Js->get('#all_fields')->event('change', 'CheckAllMultiCheckBox(this);');
            $this->Js->get('.chart-fields')->event('change', 'CheckMultiCheckBox(this);');
            ?>
        </div>

    </div>

    <?php echo $this->Form->end(); ?>

</fieldset>

<style>
    .chart {
        display:block;
        clear:both;
        width:auto;
        height:auto;
        margin:5px auto;
        margin-bottom:7px;
        padding:5px;
    }
    
/*    .chart_opt_menu
    {
        background-image:      menu_opt.png;
    }*/
</style>

<div id="my_charts" style="border:1px solid #ddd; clear:both; width:auto; height:auto; margin:0 auto; padding:0;">

    <div class="chart">
        <div id="bar_chart"></div>
        <?php if (!empty($chartNameColumn)) echo $this->Highcharts->render($chartNameColumn); ?>
    </div>

    <div class="chart">
        <div id="line_chart"></div>
        <?php if (!empty($chartNameLine)) echo $this->Highcharts->render($chartNameLine); ?>
    </div>

    <div class="chart">
        <div id="pie_chart"></div>
        <?php if (!empty($chartNamePie)) echo $this->Highcharts->render($chartNamePie); ?>
    </div>

</div>

<?php
$this->Js->get('#all_series, .chart-series, #all_fields, .chart-fields')->event('change', $this->Js->request(
        array('controller' => 'ReportModuleReportViewers', 'action' => 'set_chart_data'), 
        array('beforeSend' => '$("#busy-indicator").fadeIn();',
            'complete' => '$("#busy-indicator").fadeOut();',
            'update' => '#my_charts',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'data' => '$(this).closest("form").serialize()'
        ))
);
?>

<?php
//echo $this->Html->scriptBlock("set_chart('bar_chart');", array('inline' => true));
?>

<script>

    $(function () {
        set_chart('bar_chart');

        $("#chart_opt_header").click(function () {
            $header = $(this);
            $content = $("#chart_opt");
            $content.slideToggle(500, function () {
                
                $header.css('ba')
                
                $header.text(function () {
                    return $content.is(":visible") ? "Chart Series/Fields Option" : "Chart Series/Fields Option";
                });
            });

        });

    });

    function set_chart(chart_name) {

//        if (chart_name && chart_name == "all_chart") {
//            $("#line_chart").closest("div[class^='chart']").css('display', 'block');
//            $("#bar_chart").closest("div[class^='chart']").css('display', 'block');
//            $("#pie_chart").closest("div[class^='chart']").css('display', 'block');
//
//            return;
//        }

        $("#line_chart").closest("div[class^='chart']").css('display', 'none');
        $("#bar_chart").closest("div[class^='chart']").css('display', 'none');
        $("#pie_chart").closest("div[class^='chart']").css('display', 'none');

        $("#" + chart_name).closest("div[class^='chart']").css('display', 'block');

        return;
    }

    function CheckAllMultiCheckBox(obj) {
        $(obj).closest("fieldset").find(".multi-checkbox input:checkbox").prop("checked", $(obj).prop("checked"));
    }

    function CheckMultiCheckBox(obj) {
        var $chkbParent = $(obj).closest("fieldset");
        var noOfCheckBoxes = $chkbParent.find(".multi-checkbox input:checkbox").length;
        if (noOfCheckBoxes === 0)
            return false;
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
        return false;
    }

</script>

