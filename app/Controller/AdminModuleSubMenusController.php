<?php

App::uses('AppController', 'Controller');

class AdminModuleSubMenusController extends AppController {

    public function sub_menu($module_id = null, $menu_id = null) {

        $user_group_id = $this->Session->read('User.GroupIds');
        $user_group_ids = $this->Session->read('User.GroupIds');

        $allSubMenus = array();
        if (!empty($user_group_ids)) {
            $this->loadModel('AdminModuleDetailSubMenuGroup');
            $sub_menu_ids = $this->AdminModuleDetailSubMenuGroup->find('list', array('fields' => array('sub_menu_id', 'sub_menu_id'), 'conditions' => array('user_group_id' => $user_group_ids, 'module_id' => $module_id, 'menu_id' => $menu_id), 'recursive' => 0));

            if (!empty($sub_menu_ids)) {
                $sub_menu_list = array();
                foreach ($sub_menu_ids as $sub_menu_id) {
                    $sub_menu_list = $this->AdminModuleSubMenu->find('all', array('conditions' => array('module_id' => $module_id, 'menu_id' => $menu_id, 'sub_menu_id' => $sub_menu_id), 'order' => array('AdminModuleSubMenu.sub_menu_id' => 'asc')));

                    if (!empty($sub_menu_list)) {
                        $allSubMenus = array_merge($allSubMenus, $sub_menu_list);
                    }
                }
            }
        }

        $this->set(compact('allSubMenus'));
    }

}
