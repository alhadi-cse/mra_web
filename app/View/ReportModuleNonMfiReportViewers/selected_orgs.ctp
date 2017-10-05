<?php
echo $this->Form->create('ReportModuleReportViewerOrgSelect');

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
<div id="divBranch" style="clear: both; width:auto; height:auto; max-height:200px; max-height:25vh; margin:0; padding:2px 3px; overflow:auto;">
</div>
<div style="clear: both; padding: 0; text-align: center;">
    <?php
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    echo $this->Js->submit('Set', array_merge($pageLoading, array('update' => '#', 'class' => 'modal-btns modal-close', 'div' => false,
        'url' => array('controller' => 'ReportModuleReportViewers', 'action' => 'basic_selection', 1),
        'confirm' => 'Are you sure to Selection ?',
        'success' => "msg.init('success', 'Basic Selection', 'Selection has been completed.');",
        'error' => "msg.init('error', 'Basic Selection', 'Selection failed !');")));
    ?>
</div>

<?php echo $this->Form->end(); ?>