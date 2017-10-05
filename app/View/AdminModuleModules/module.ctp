
<ul>
    <?php
    if (!empty($allModules) && is_array($allModules)) {
        foreach ($allModules as $module) {
            echo "<li><h3><a href='#'><div class='updown_arrow'></div>" . $module['AdminModuleModule']['module_name'] . "</a></h3>"
            . $this->requestAction(array('controller' => 'AdminModuleMenus', 'action' => 'menu', $module['AdminModuleModule']['module_id']), array('return'))
            . "</li>";
        }
    }
    ?>
</ul>

