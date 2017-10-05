<?php

if (!empty($allSubMenus) && is_array($allSubMenus)) {

    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    echo '<ul style="display:none;">';
    foreach ($allSubMenus as $sub_menu) {
        if (empty($sub_menu["AdminModuleSubMenu"]))
            continue;

        $sub_menu_details = $sub_menu["AdminModuleSubMenu"];
        if (empty($sub_menu_details["sub_menu_title"]) || empty($sub_menu_details["controller"]) || empty($sub_menu_details["controller_action"]))
            continue;

        echo '<li>'
        . (!empty($sub_menu_details["controller_parameters"]) ?
                $this->Js->link($sub_menu_details["sub_menu_title"], array('controller' => $sub_menu_details["controller"], 'action' => $sub_menu_details["controller_action"], '?' => $sub_menu_details["controller_parameters"]), $pageLoading) : $this->Js->link($sub_menu_details["sub_menu_title"], array('controller' => $sub_menu_details["controller"], 'action' => $sub_menu_details["controller_action"]), $pageLoading))
        . '</li>';
    }
    echo '</ul>';

    if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
        echo $this->Js->writeBuffer();
}

?>
