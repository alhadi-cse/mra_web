<?php

App::uses('AppController', 'Controller');

class AdminModuleModulesController extends AppController {

    public function module() {

        $user_group_id = $this->Session->read('User.GroupIds');
        $user_group_ids = $this->Session->read('User.GroupIds');
        $allModules = array();
        if (!empty($user_group_ids)) {
        //if (!empty($user_group_id)) {
            $this->loadModel('AdminModuleDetailSubMenuGroup');
            $module_ids = $this->AdminModuleDetailSubMenuGroup->find('list', array('fields' => array('module_id', 'module_id'), 'conditions' => array('user_group_id' => $user_group_ids), 'group' => array('module_id'), 'recursive' => 0));

            if (!empty($module_ids)) {
                $allModules = $this->AdminModuleModule->find('all', array('conditions' => array('AdminModuleModule.module_id' => $module_ids), 'order' => array('AdminModuleModule.module_id' => 'asc')));
            }
        }

        $this->set(compact('allModules'));
    }

}
