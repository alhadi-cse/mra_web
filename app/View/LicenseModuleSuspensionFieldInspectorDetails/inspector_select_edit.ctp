<?php
    if (!empty($inspector_list)) {
        echo $this->Form->input('LicenseModuleSuspensionFieldInspectorDetail.inspector_id', 
                array('type' => 'select', 'multiple' => 'checkbox', 'options' => $inspector_list, 
                    'selected' => $selected_inspector_list, 'label' => false));
    } else {
        echo '<p class="error-message">' . 'Inspector information not found !' . '</p>';
    }
    
?>