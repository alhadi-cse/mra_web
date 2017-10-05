
<?php

if (!empty($org_list) && !empty($branch_list_all)) {
    foreach ($org_list as $org_id => $org_name) {
        if (empty($org_id) || empty($org_name) || empty($branch_list_all[$org_id]))
            continue;

        $title = "<label for='org_$org_id'>$org_name </label>"
                . "<input type='checkbox' id='org_$org_id' class='all-checked' style='margin:0 1px; vertical-align:text-top;'>";
        $org_branch_list = array($title => $branch_list_all[$org_id]);
        echo $this->Form->input("ReportQueryGeneratorOrgSelect.listOrgBranch.$org_id", array('type' => 'select', 'multiple' => 'checkbox', 'class' => 'multi-checkbox org_branch-list', 'options' => $org_branch_list, 'escape' => false, 'div' => false, 'label' => false));
    }

    $this->Js->get('.all-checked')->event('change', 'CheckAllMultiCheckBox(this);');
    $this->Js->get('.org_branch-list')->event('change', 'CheckMultiCheckBox(this);');
}

?>
