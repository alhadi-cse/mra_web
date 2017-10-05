
<?php

echo $this->Form->create('ReportModuleReportViewerDivSelect');
echo $this->Form->input("listDiv", array('id' => 'listDiv', 'type' => 'select', 'multiple' => 'checkbox', 'class' => 'multi-checkbox div-list', 'options' => $div_list, 'escape' => false, 'div' => false, 'label' => false));
echo $this->Form->end();


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


<?php

//    $this->Js->get('.all-checked')->event('change', 'CheckAllMultiCheckBox(this);');
//    $this->Js->get('.all-checked')->event('change', $this->Js->request($actionLink, array(
//                'before' => '$("#busy-indicator").fadeIn();',
//                'complete' => '$("#busy-indicator").fadeOut();',
//                'update' => $updateDiv,
//                'async' => true,
//                'method' => 'post',
//                'dataExpression' => true,
//                'data' => '$(this).closest("form").serialize()'
//            ))
//    );
?>
