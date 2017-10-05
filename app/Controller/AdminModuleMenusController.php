<?php

App::uses('AppController', 'Controller');

class AdminModuleMenusController extends AppController {

    public function menu($module_id = null) {
        $user_group_id = $this->Session->read('User.GroupIds');
        $user_group_ids = $this->Session->read('User.GroupIds');

        $allMenus = array();
        if (!empty($user_group_ids)) {
        //if (!empty($user_group_id)) {
            $this->loadModel('AdminModuleDetailSubMenuGroup');
            $menu_ids = $this->AdminModuleDetailSubMenuGroup->find('list', array('fields' => array('menu_id', 'menu_id'), 'conditions' => array('user_group_id' => $user_group_ids, 'module_id' => $module_id), 'group' => array('menu_id'), 'recursive' => 0));

            if (!empty($menu_ids)) {
                $allMenus = $this->AdminModuleMenu->find('all', array('conditions' => array('module_id' => $module_id, 'menu_id' => $menu_ids), 'order' => array('AdminModuleMenu.menu_id' => 'asc')));
            }
        }

        $this->set(compact('allMenus'));
    }

}
