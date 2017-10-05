<?php

if (!empty($org_id) && !empty($org_name) && !empty($branch_list)) {

    $no_of_branches = count($branch_list);
    $title = "<label for='org_$org_id'>▣ $org_name - Branch List ($no_of_branches) </label>"
            . "<input type='checkbox' id='org_$org_id' type='checkbox' class='all-checked' style='margin:0 1px 0 2px; vertical-align:text-top;'>";

    $org_branch_list = array($title => $branch_list);
    echo $this->Form->input("ReportModuleReportViewerOrgSelect.branches_id.$org_id", array('type' => 'select', 'multiple' => 'checkbox', 'class' => 'multi-checkbox org_branch-list', 'options' => $org_branch_list, 'escape' => false, 'div' => false, 'label' => false));

    $this->Js->get('.all-checked')->event('change', 'CheckAllMultiCheckBox(this);');
    $this->Js->get('.org_branch-list')->event('change', 'CheckMultiCheckBox(this);');
}
?>
