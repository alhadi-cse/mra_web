
<style>

    .allFields fieldset {
        max-height: 250px;
        overflow: auto;
    }

</style>

<?php
if (!empty($model_list) && !empty($model_field_list)) {

    echo $this->Form->create('ReportQueryGenerator');
    
    echo "<div class='allFields' style='width:auto; height:100%; min-width:150px; min-height:280px; max-height:500px; max-height:85vh; padding:3px 3px 8px; resize:both; overflow:auto;'>";

    foreach ($model_list as $model_id => $model_details) {

        if (empty($model_id) || empty($model_details['model_name']) || empty($model_details['model_description']) || empty($model_field_list[$model_id]))
            continue;

        $model_description = $model_details['model_description'];
        $title = "<label for='model_$model_id'>$model_description </label>"
                . "<input type='checkbox' id='model_$model_id' class='all-checked' style='margin:0 1px; vertical-align:text-top;'>";
        $field_list = array($title => $model_field_list[$model_id]);
        echo $this->Form->input($model_details['model_name'], array('type' => 'select', 'multiple' => 'checkbox', 'class' => 'multi-checkbox field-list', 'options' => $field_list, 'escape' => false, 'div' => false, 'label' => false));
    }
    
    echo "</div>";
    
    ?>

    <div class="btns-div" style="margin-top:0;">
        <table style="min-width:170px; margin:0 auto; padding:0;" cellspacing="5">
            <tr>
                <td></td>
                <td>
                    <?php
                    echo $this->Js->submit('ReportA', array(
                        'url' => array('controller' => 'ReportQueryGenerators', 'action' => 'generator_query_by_selected_fields'),
                        'update' => '#geneQueryTest',
                        'async' => true,
                        'method' => 'post',
                        'dataExpression' => true,
                        'data' => '$(this).closest("form").serialize()',
                        'beforeSend' => '$("#busy-indicator").fadeIn();',
                        'complete' => '$("#busy-indicator").fadeOut();', //
                        'error' => "msg.init('error', 'Report Generator', 'Report Generation failed !');"));
                    ?>
                </td>
                <td>
                    <?php
                    echo $this->Js->submit('Report', array(
                        'url' => array('controller' => 'ReportQueryGenerators', 'action' => 'generator_query_by_selected_fields'),
                        'update' => '#geneQuery',
                        'async' => true,
                        'method' => 'post',
                        'dataExpression' => true,
                        'data' => '$(this).closest("form").serialize()',
                        'beforeSend' => '$("#busy-indicator").fadeIn();',
                        'complete' => '$("#busy-indicator").fadeOut(); modal.init("MRA Report", "report_viewer");', //
                        'confirm' => 'Are you sure to Generate Report ?',
                        'success' => "",
                        'error' => "msg.init('error', 'Report Generator', 'Report Generation failed !');"));
                    ?>
                </td>
                <td></td>
            </tr>
        </table>
    </div>

    <?php
    echo $this->Form->end();

//    $actionLink = array('controller' => 'ReportQueryGenerators', 'action' => 'generator_query_by_selected_fields');
//    $updateDiv = '#geneQuery';

    $this->Js->get('.field-list')->event('change', 'CheckMultiCheckBox(this);');
    $this->Js->get('.all-checked')->event('change', 'CheckAllMultiCheckBox(this);');
}
?>

