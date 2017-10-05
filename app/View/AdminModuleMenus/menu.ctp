
<?php

if (!empty($allMenus) && is_array($allMenus)) {

    echo '<ul style="display:none;">';
    foreach ($allMenus as $menu) {
        echo '<li><a href="#">' . $menu["AdminModuleMenu"]["menu_title"] . '</a>'
        . $this->requestAction(array('controller' => 'AdminModuleSubMenus', 'action' => 'sub_menu', $menu["AdminModuleMenu"]["module_id"], $menu["AdminModuleMenu"]["menu_id"]), array('return'))
        . '</li>';
    }
    echo '</ul>';
}

?>
