
<?php

if (!empty($dist_list) && !empty($upaz_list_all)) {

    echo $this->Form->create('ReportModuleReportViewerUpazSelect');
    foreach ($dist_list as $dist_id => $dist_name) {
        if (empty($dist_id) || empty($upaz_list_all[$dist_id]))
            continue;

        $title = "<label for='dist_$dist_id'>$dist_name District </label>"
                . "<input type='checkbox' id='dist_$dist_id' type='checkbox' class='all-checked' style='margin:0 1px; vertical-align:text-top;'>";
        $upaz_list = array($title => $upaz_list_all[$dist_id]);
        echo $this->Form->input($dist_id, array('type' => 'select', 'multiple' => 'checkbox', 'class' => 'multi-checkbox upaz-list', 'options' => $upaz_list, 'escape' => false, 'div' => false, 'label' => false));
    }

    echo $this->Form->end();


    $actionLink = array('controller' => 'ReportModuleReportViewers', 'action' => 'selected_admin_unions');
    $updateDiv = '#geneQuery';

    $this->Js->get('.upaz-list')->event('change', 'CheckMultiCheckBox(this);');
    $this->Js->get('.upaz-list')->event('change', $this->Js->request($actionLink, array(
                'beforeSend' => '$("#busy-indicator").fadeIn();',
                'complete' => '$("#busy-indicator").fadeOut();',
                'update' => $updateDiv,
                'async' => true,
                'method' => 'post',
                'dataExpression' => true,
                'data' => '$(this).closest("form").serialize()'
            ))
    );

    $this->Js->get('.upaz-list')->event('change', $this->Js->request(
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

    $this->Js->get('.all-checked')->event('change', 'CheckAllMultiCheckBox(this);');
    $this->Js->get('.all-checked')->event('change', $this->Js->request($actionLink, array(
                'beforeSend' => '$("#busy-indicator").fadeIn();',
                'complete' => '$("#busy-indicator").fadeOut();',
                'update' => $updateDiv,
                'async' => true,
                'method' => 'post',
                'dataExpression' => true,
                'data' => '$(this).closest("form").serialize()'
            ))
    );
}
?>
