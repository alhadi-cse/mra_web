
<?php

if (!empty($div_list) && !empty($dist_list_all)) {

    echo $this->Form->create('ReportModuleReportViewerDistSelect');
    foreach ($div_list as $div_id => $div_name) {
        if (empty($div_id) || empty($div_name) || empty($dist_list_all[$div_id]))
            continue;

//        $title = $this->Form->input("", array('type' => 'checkbox', 'id' => "div_$div_id", 'label' => "$div_name Division ", 'class' => 'all-checked', 'style' => 'float:right; margin:0 1px; vertical-align:text-top;', 'name' => false, 'hiddenField' => false, 'escape' => false, 'div' => false));

        $title = "<label for='div_$div_id'>$div_name Division </label>"
                . "<input type='checkbox' id='div_$div_id' type='checkbox' class='all-checked' style='margin:0 1px; vertical-align:text-top;'>";
        $dist_list = array($title => $dist_list_all[$div_id]);
        echo $this->Form->input($div_id, array('type' => 'select', 'multiple' => 'checkbox', 'class' => 'multi-checkbox dist-list', 'options' => $dist_list, 'escape' => false, 'div' => false, 'label' => false));
    }

    echo $this->Form->end();


    $actionLink = array('controller' => 'ReportModuleReportViewers', 'action' => 'selected_admin_upzas');
    $updateDiv = '#divUpaz';

    $this->Js->get('.dist-list')->event('change', 'CheckMultiCheckBox(this);');
    $this->Js->get('.dist-list')->event('change', $this->Js->request($actionLink, array(
                'before' => '$("#busy-indicator").fadeIn();',
                'complete' => '$("#busy-indicator").fadeOut();',
                'update' => $updateDiv,
                'async' => true,
                'method' => 'post',
                'dataExpression' => true,
                'data' => '$(this).closest("form").serialize()'
            ))
    );

    $this->Js->get('.dist-list')->event('change', $this->Js->request(
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
                'before' => '$("#busy-indicator").fadeIn();',
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
