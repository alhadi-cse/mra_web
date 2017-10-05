
<div style="border:1px solid #ddd; padding:5px;">

    <div class="chart_options" style="border:1px solid #ddd; padding:5px;">

        <?php echo $this->Form->create('ReportChartGeneratorOptions'); ?>
        <div id="divField" style="float:left; width:48%; height:100%; padding:5px 5px 0; overflow:auto;">
            <?php
            $title = "<label for='all_series'>Chart All Series  </label>"
                    . "<input type='checkbox' id='all_series' class='all-checked' checked='checked' style='margin:2px 5px; vertical-align:text-top;'>";
            echo $this->Form->input("fieldSeries", array('type' => 'select', 'multiple' => 'checkbox', 'class' => 'multi-checkbox chart-series', 'options' => array($title => $chartSeries), 'escape' => false, 'div' => false, 'label' => false));

            $this->Js->get('#all_series')->event('change', 'CheckAllMultiCheckBox(this);');
            $this->Js->get('.chart-series')->event('change', 'CheckMultiCheckBox(this);');
            ?>
        </div>

        <div id="divField" style="float:right; width:48%; height:100%; padding:5px 5px 0; overflow:auto;">
            <?php
            $title = "<label for='all_fields'>Chart All Fields  </label>"
                    . "<input type='checkbox' id='all_fields' class='all-checked' checked='checked' style='margin:2px 5px; vertical-align:text-top;'>";
            echo $this->Form->input("fieldList", array('type' => 'select', 'multiple' => 'checkbox', 'class' => 'multi-checkbox chart-fields', 'options' => array($title => $chartFields), 'escape' => false, 'div' => false, 'label' => false));

            $this->Js->get('#all_fields')->event('change', 'CheckAllMultiCheckBox(this);');
            $this->Js->get('.chart-fields')->event('change', 'CheckMultiCheckBox(this);');
            ?>
        </div>
        <div style="clear:both;margin:0;padding:0;"></div>
        <?php echo $this->Form->end(); ?>

    </div>

    <style>
        .chart {
            display:block;
            clear:both;
            width:98%;
            height:auto;
            margin-bottom:8px;
            padding:5px;
        }
    </style>

    <div id="my_charts" style="clear:both; width:auto; height:auto; margin:0 auto; padding:0;">

        <div class="chart">
            <div id="line_chart"></div>
            <?php if (!empty($chartNameLine)) echo $this->Highcharts->render($chartNameLine); ?>
        </div>

        <div class="chart">
            <div id="bar_chart"></div>
            <?php if (!empty($chartNameColumn)) echo $this->Highcharts->render($chartNameColumn); ?>
        </div>

        <div class="chart">
            <div id="pie_chart"></div>
            <?php if (!empty($chartNamePie)) echo $this->Highcharts->render($chartNamePie); ?>
        </div>

    </div>


    <?php
//    $this->Js->get('#fieldGroupList')->event('change', 'CheckMultiCheckBox(this);');
//    $this->Js->get('#fieldGroupList')->event('change', $this->Js->request(
//                    array('controller' => 'ReportQueryGenerators', 'action' => 'set_chart'), array(
//                'beforeSend' => '$("#busy-indicator").fadeIn();',
//                'complete' => '$("#busy-indicator").fadeOut();',
//                'update' => '#my_charts',
//                'async' => true,
//                'method' => 'post',
//                'dataExpression' => true,
//                'data' => '$(this).closest("form").serialize()'
//            ))
//    );
//    $this->Js->get('#fieldGroupList, #all_fields, .chart-fields')->event('change', $this->Js->request(
//                    array('controller' => 'ReportQueryGenerators', 'action' => 'set_chart'), array(
//                'beforeSend' => '$("#busy-indicator").fadeIn();',
//                'complete' => '$("#busy-indicator").fadeOut();',
//                'update' => '#my_charts',
//                'async' => true,
//                'method' => 'post',
//                'dataExpression' => true,
//                'data' => '$(this).closest("form").serialize()'
//            ))
//    );

    $this->Js->get('#all_series, .chart-series, #all_fields, .chart-fields')->event('change', $this->Js->request(
                    array('controller' => 'ReportQueryGenerators', 'action' => 'set_chart'), array(
                'beforeSend' => '$("#busy-indicator").fadeIn();',
                'complete' => '$("#busy-indicator").fadeOut();',
                'update' => '#my_charts',
                'async' => true,
                'method' => 'post',
                'dataExpression' => true,
                'data' => '$(this).closest("form").serialize()'
            ))
    );
    ?>

    <script>

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

</div>