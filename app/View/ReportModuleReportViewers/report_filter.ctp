
<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}

$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

?>

<table style="width:100%;">
    <tr>
        <td style="vertical-align:top;">
            <fieldset style="margin: 0 7px; padding: 5px 12px;">
                <legend>▣ Basic Options</legend>

                <div style="margin: 0; padding: 0; max-height: 73vh; overflow: auto;">

                    <fieldset style="padding:3px 5px 1px 5px;">
                        <legend>▣ Select :</legend>
                        <?php echo $this->Form->create('ReportModuleReportOptionsSet'); ?>
                        <table cellpadding="5" cellspacing="5" border="0">
<!--                                            <tr>
                                <td colspan="3">
                                    Period Type : 
                            <?php //echo $this->Form->input('period_type_id', array('id' => 'period_type_id', 'type' => 'select', 'options' => $period_type_list, 'label' => false, 'div' => false, 'style' => 'width:100px;'));   ?>
                                    Data Period : 
                            <?php //echo $this->Form->input('period_id', array('id' => 'period_id', 'type' => 'select', 'options' => $group_wise_period_list, 'label' => false, 'div' => false, 'style' => 'width:100px;'));   ?>
                                </td>
                            </tr>-->
                            <tr>
                                <td style="width:100px;"><?php echo $this->Form->input('order_dir', array('type' => 'select', 'options' => array('desc' => 'Top', 'asc' => 'Last'), 'label' => false, 'div' => false, 'style' => 'width:100px;')); ?></td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('limit', array('length' => 5, 'class' => 'integers', 'style' => 'width:80px;', 'label' => false, 'div' => false)); ?></td>
                            </tr>
                            <tr>
                                <td>Data Period</td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('period_id', array('id' => 'period_id', 'type' => 'select', 'options' => $group_wise_period_list, 'empty' => '---- Select Period ----', 'label' => false, 'div' => false, 'style' => 'width:165px;')); ?></td>
                            </tr>
                            <tr>
                                <td>From Date</td>
                                <td class="colons">:</td>
                                <td>
                                    <?php
                                    echo $this->Form->input("from_date", array('type' => 'hidden', 'id' => 'txtReportFromDate_alt', 'label' => false, 'div' => false))
                                    . "<input type='text' id='txtReportFromDate' class='date_picker' />";
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>To Date</td>
                                <td class="colons">:</td>
                                <td>
                                    <?php
                                    echo $this->Form->input("to_date", array('type' => 'hidden', 'id' => 'txtReportToDate_alt', 'label' => false, 'div' => false))
                                    . "<input type='text' id='txtReportToDate' class='date_picker' />";
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                </td>
                                <td style="text-align: left;">
                                    <?php
                                    echo $this->Js->submit('Set', array_merge($pageLoading, array('update' => '#', 'class' => 'modal-btns modal-close', 'div' => false,
                                        'url' => array('controller' => 'ReportModuleReportViewers', 'action' => 'report_options_set', 1),
                                        'confirm' => 'Are you sure to set options ?',
                                        'success' => "msg.init('success', 'Report Options', 'Setting has been completed.');", //modal_close('report_opt');
                                        'error' => "msg.init('error', 'Report Options', 'Setting failed !');")));
                                    ?>
                                </td>
                            </tr>
                        </table>
                        <?php echo $this->Form->end(); ?>
                    </fieldset>

                    <fieldset>
                        <legend>▣ Organization :</legend>

                        <div id="divOrgs" style="clear: both; border: 0 none; width:auto; height:auto; margin:0; padding:0;">
                            <?php echo $this->Form->create('ReportModuleReportViewerOrgSelect'); ?>
                            <!--<div id="divOrgs"  style="clear: both; padding: 0 0 7px 7px;"></div>-->
                            <?php
                            echo $this->Form->input("listOrg", array('id' => 'listOrg', 'type' => 'select', 'class' => 'org-list', 'options' => $org_list, 'empty' => '------- Select Organization -------', 'escape' => false, 'div' => false, 'label' => false));

                            $this->Js->get('.field-list')->event('change', 'CheckMultiCheckBox(this);');
                            $this->Js->get('.all-checked')->event('change', 'CheckAllMultiCheckBox(this);');

                            $this->Js->get('.org-list')->event('change', $this->Js->request(
                                            array('controller' => 'ReportModuleReportViewers', 'action' => 'selected_admin_divs'), array(
                                        'beforeSend' => '$("#busy-indicator").fadeIn();',
                                        'complete' => '$("#busy-indicator").fadeOut();',
                                        'update' => '#divDiv',
                                        'async' => true,
                                        'method' => 'post',
                                        'dataExpression' => true,
                                        'data' => '$(this).closest("form").serialize()'
                                    ))
                            );
                            $this->Js->get('.org-list')->event('change', $this->Js->request(
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
                            $this->Js->get('.org-list')->event('change', $this->Js->request(
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
                            $this->Js->get('.org-list')->event('change', $this->Js->request(
                                            array('controller' => 'ReportModuleReportViewers', 'action' => 'selected_org_branches'), array(
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
                            <div id="divBranch" style="clear: both; width:auto; height:auto; max-height:200px; max-height:45vh; margin:0; padding:2px 3px; overflow:auto;">
                            </div>
                            <div style="clear: both; padding: 0; text-align: center;">
                                <?php
                                echo $this->Js->submit('Set', array_merge($pageLoading, array('update' => '#', 'class' => 'modal-btns modal-close', 'div' => false,
                                    'url' => array('controller' => 'ReportModuleReportViewers', 'action' => 'basic_selection', 1),
                                    'confirm' => 'Are you sure to Selection ?',
                                    'success' => "msg.init('success', 'Basic Selection', 'Selection has been completed.');",
                                    'error' => "msg.init('error', 'Basic Selection', 'Selection failed !');")));
                                ?>
                            </div>

                            <?php echo $this->Form->end(); ?>
                        </div>
                    </fieldset>

                </div>
            </fieldset>

        </td>

        <td style="vertical-align:top;">
            <fieldset style="margin: 0 7px;">
                <legend>▣ Admin Boundary</legend>

                <table>
                    <tr>
                        <td style="vertical-align:top;">
                            <fieldset style="margin:0 5px 5px;">
                                <legend>▣ Division :</legend>
                                <div id="divDiv" style="width:auto; height:100%; min-width:150px; min-height:370px; max-height:500px; max-height:70vh; padding:2px 3px; resize:both; overflow:auto;">
                                    <?php
                                    echo $this->Form->create('ReportModuleReportViewerDivSelect');
                                    echo $this->Form->input("listDiv", array('id' => 'listDiv', 'type' => 'select', 'multiple' => 'checkbox', 'class' => 'multi-checkbox div-list', 'options' => $div_list, 'escape' => false, 'div' => false, 'label' => false));
                                    echo $this->Form->end();

                                    $this->Js->get('.field-list')->event('change', 'CheckMultiCheckBox(this);');
                                    $this->Js->get('.all-checked')->event('change', 'CheckAllMultiCheckBox(this);');

                                    $this->Js->get('.div-list')->event('change', $this->Js->request(
                                                    array('controller' => 'ReportModuleReportViewers', 'action' => 'selected_orgs'), array(
                                                'beforeSend' => '$("#busy-indicator").fadeIn();',
                                                'complete' => '$("#busy-indicator").fadeOut();',
                                                'update' => '#divOrgs',
                                                'async' => true,
                                                'method' => 'post',
                                                'dataExpression' => true,
                                                'data' => '$(this).closest("form").serialize()'
                                            ))
                                    );

                                    $this->Js->get('.div-list')->event('change', $this->Js->request(
                                                    array('controller' => 'ReportModuleReportViewers', 'action' => 'selected_org_branches'), array(
                                                'beforeSend' => '$("#busy-indicator").fadeIn();',
                                                'complete' => '$("#busy-indicator").fadeOut();',
                                                'update' => '#divBranch',
                                                'async' => true,
                                                'method' => 'post',
                                                'dataExpression' => true,
                                                'data' => '$(this).closest("form").serialize()'
                                            ))
                                    );

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
                                </div>
                            </fieldset>
                        </td>

                        <td style="vertical-align:top;">
                            <fieldset style="margin:0 5px 5px;">
                                <legend>▣ District :</legend>
                                <div id="divDist" style="width:auto; height:100%; min-width:150px; min-height:370px; max-height:500px; max-height:70vh; padding:2px 3px; resize:both; overflow:auto;">
                                </div>
                            </fieldset>
                        </td>

                        <td style="vertical-align:top;">
                            <fieldset style="margin:0 5px 5px;">
                                <legend>▣ Upazila :</legend>
                                <div id="divUpaz" style="width:auto; height:100%; min-width:150px; min-height:370px; max-height:500px; max-height:70vh; padding:2px 3px; resize:both; overflow:auto;">
                                </div>
                            </fieldset>
                        </td>
                    </tr>
                </table>

            </fieldset>

        </td>
    </tr>

</table>
