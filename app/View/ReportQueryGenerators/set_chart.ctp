
<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}
?>

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
