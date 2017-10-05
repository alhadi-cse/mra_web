
<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}
?>


<div id="data_div" style="width:100%; height:auto; overflow-x:auto;">

    <?php echo $this->Form->create('ReportModuleReportViewerSelect'); ?>


    <fieldset style="margin:0 5px 5px;">
        <legend>Admin Boundary</legend>

        <table style="width:100%;">
            <tr>
                <td style="vertical-align:top;">
                    <fieldset style="margin:0 5px 5px;">
                        <legend>Division</legend>
                        <div id="divDiv" style="width:auto; height:100%; min-width:150px; min-height:300px; max-height:500px; padding:2px 3px; resize:both; overflow:auto;">
                            <?php
                            echo $this->Form->create('ReportModuleReportViewerDivSelect');
                            echo $this->Form->input("listDiv", array('id' => 'listDiv', 'type' => 'select', 'multiple' => 'checkbox', 'class' => 'multi-checkbox div-list', 'options' => $div_list, 'escape' => false, 'div' => false, 'label' => false));
                            echo $this->Form->end();
                            ?>
                        </div>
                    </fieldset>
                </td>

                <td style="vertical-align:top;">
                    <fieldset style="margin:0 5px 5px;">
                        <legend>District</legend>
                        <div id="divDist" style="width:auto; height:100%; min-width:150px; min-height:300px; max-height:500px; padding:2px 3px; resize:both; overflow:auto;">
                            <?php
//                                            echo $this->Form->create('ReportModuleReportViewerDistSelect');
//                                            echo $this->Form->input("listDist", array('id' => 'listDist', 'type' => 'select', 'multiple' => 'checkbox', 'class' => 'multi-checkbox dist-list', 'options' => $dist_list, 'escape' => false, 'div' => false, 'label' => false));
//                                            echo $this->Form->end();
                            ?>
                        </div>
                    </fieldset>
                </td>

                <td style="vertical-align:top;">
                    <fieldset style="margin:0 5px 5px;">
                        <legend>Upazila</legend>
                        <div id="divUpaz" style="width:auto; height:100%; min-width:150px; min-height:300px; max-height:500px; padding:2px 3px; resize:both; overflow:auto;">
                            <?php
//                                            echo $this->Form->create('ReportModuleReportViewerUpazSelect');
//                                            echo $this->Form->input("listUpaz", array('id' => 'listUpaz', 'type' => 'select', 'multiple' => 'checkbox', 'class' => 'multi-checkbox upaz-list', 'options' => $upaz_list, 'escape' => false, 'div' => false, 'label' => false));
//                                            echo $this->Form->end();
                            ?>
                        </div>
                    </fieldset>
                </td>
                <td style="vertical-align:top;">

                    <?php //echo $this->requestAction(array('controller' => 'ReportModuleReportViewers', 'action' => 'map'), array('return'));  ?>

                </td>
            </tr>
        </table>

    </fieldset>

    <?php echo $this->Form->end(); ?>

</div>

<?php
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
