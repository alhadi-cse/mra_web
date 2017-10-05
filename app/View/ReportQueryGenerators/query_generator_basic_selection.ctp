
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

    <?php echo $this->Form->create('ReportQueryGeneratorSelect'); ?>

    <fieldset style="margin:0 5px 5px;">
        <legend>Basic Selection</legend>

        <table style="width:100%;">
            <tr>
                <td style="vertical-align:top;">
                    <fieldset style="margin:0 5px 5px;">
                        <legend>Organization List</legend>
                        <div id="divOrg" style="width:auto; height:100%; min-width:150px; min-height:300px; max-height:500px; padding:2px 3px; resize:both; overflow:auto;">
                            <?php
                            echo $this->Form->create('ReportQueryGeneratorOrgSelect');
                            echo $this->Form->input("listOrg", array('id' => 'listOrg', 'type' => 'select', 'multiple' => 'checkbox', 'class' => 'multi-checkbox org-list', 'options' => $org_list, 'escape' => false, 'div' => false, 'label' => false));
                            echo $this->Form->end();
                            ?>
                        </div>
                    </fieldset>
                </td>

                <td style="vertical-align:top;">
                    <fieldset style="margin:0 5px 5px;">
                        <legend>Branch List</legend>
                        <div id="divBranch" style="width:auto; height:100%; min-width:150px; min-height:300px; max-height:500px; padding:2px 3px; resize:both; overflow:auto;">

                        </div>
                    </fieldset>
                </td>
            </tr>
        </table>

    </fieldset>
    
    <?php echo $this->Form->end(); ?>

</div>

<?php
$this->Js->get('.org-list')->event('change', $this->Js->request(
                array('controller' => 'ReportQueryGenerators', 'action' => 'selected_org_branches'), array(
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
