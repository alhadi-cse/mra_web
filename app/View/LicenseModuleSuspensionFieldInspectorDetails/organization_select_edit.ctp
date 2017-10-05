<?php
    if (!empty($organization_list)) {
        echo $this->Form->input('LicenseModuleSuspensionFieldInspectorDetail.org_id', array('type' => 'select', 'multiple' => 'checkbox', 'options' => $organization_list, 'selected' => $selected_org_list, 'label' => false, 'escape' => false));
    } else {
        echo '<p class="error-message">' . 'Organization information not found !' . '</p>';
    }
?>