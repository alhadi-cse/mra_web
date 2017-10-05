<div id="frmStatus_add">
    <?php
    $title = "Assign Inspector for Field Inspection";
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    if (!empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }
    ?>
    <fieldset>
        <legend><?php echo $title; ?></legend>

        <?php echo $this->Form->create('LicenseModuleSuspensionFieldInspectorDetail'); ?>
        <div class="form">

            <strong style="padding-left:25px;">District : </strong>
            <?php echo $this->Form->input('district_id', array('type' => 'select', 'options' => $dist_list, 'id' => 'districts', 'empty' => '-----Select-----', 'div' => false, 'label' => false)); ?>

            <fieldset>
                <legend>Organization List</legend>
                <div id="organizations"></div>
            </fieldset> 

            <fieldset>
                <legend>Inspector List</legend>
                <div id="inspectors"></div>
            </fieldset>

        </div>

        <div class="btns-div">
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php
                        echo $this->Js->link('Close', array('controller' => 'LicenseModuleSuspensionFieldInspectorDetails', 'action' => 'view', '?' => array('inspector_group_id' => $inspector_group_id)), array_merge($pageLoading, array('class' => 'mybtns')));
                        ?> 
                    </td>
                    <td style="text-align: center;">
                        <?php
                        echo $this->Js->submit('Save', array_merge($pageLoading, array('success' => "msg.init('success', '$title', '$title has been updated successfully.');",
                            'error' => "msg.init('error', '$title', 'Insertion failed!');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>

<?php

    $this->Js->get('#districts')->event('change', $this->Js->request(array(
            'controller' => 'LicenseModuleSuspensionFieldInspectorDetails',
            'action' => 'organization_select_edit'), array(
            'update' => '#organizations',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'data' => $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))
    );

    $this->Js->get('#districts')->event('change', $this->Js->request(array(
            'controller' => 'LicenseModuleSuspensionFieldInspectorDetails',
            'action' => 'inspector_select_edit'), array(
            'update' => '#inspectors',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'data' => $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))
    );
?>
<?php
if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
    echo $this->Js->writeBuffer();
?>