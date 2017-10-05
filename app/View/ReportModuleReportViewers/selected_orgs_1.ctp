
<?php

echo $this->Form->input("listOrg", array('id' => 'listOrg', 'type' => 'select', 'class' => 'org-list', 'options' => $org_list, 'empty' => '------- Select Organization -------', 'escape' => false, 'div' => false, 'label' => false));


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
?>
