<?php

App::uses('AppController', 'Controller');

class AdminModuleDetailSubMenuGroupsController extends AppController {
    public $helpers = array('Form', 'Html', 'Js');
    var $components = array('Paginator');

    public function view($opt = null) {
        $menu_options = $sub_menu_options = array();
        $this->loadModel('AdminModuleModule');
        $module_options = $this->AdminModuleModule->find('list', array('fields' => array('AdminModuleModule.module_id', 'AdminModuleModule.module_name'),'order'=>array('AdminModuleModule.module_name'=>'ASC')));
        
        $options['limit'] = 8;
        $options['order'] = array('AdminModuleUserGroup.group_name' => 'ASC');

        $options['joins'] = array(             
            array('table' => 'admin_module_modules',
                'alias' => 'AdminModuleModule',
                'type' => 'LEFT',
                'conditions' => array(
                    'AdminModuleDetailSubMenuGroup.module_id = AdminModuleModule.module_id'
                )
            ),
            array('table' => 'admin_module_menus',
                'alias' => 'AdminModuleMenu',
                'type' => 'LEFT',
                'conditions' => array('AdminModuleDetailSubMenuGroup.module_id = AdminModuleMenu.module_id', 'AdminModuleDetailSubMenuGroup.menu_id = AdminModuleMenu.menu_id')
            ),
            array('table' => 'admin_module_sub_menus',
                'alias' => 'AdminModuleSubMenu',
                'type' => 'LEFT',
                'conditions' => array('AdminModuleDetailSubMenuGroup.module_id = AdminModuleSubMenu.module_id', 'AdminModuleDetailSubMenuGroup.menu_id = AdminModuleSubMenu.menu_id', 'AdminModuleDetailSubMenuGroup.sub_menu_id = AdminModuleSubMenu.sub_menu_id')
            ),
            array('table' => 'admin_module_user_groups',
                'alias' => 'AdminModuleUserGroup',
                'type' => 'LEFT',
                'conditions' => array(
                    'AdminModuleDetailSubMenuGroup.user_group_id = AdminModuleUserGroup.id'
                )
            )
        );
        $conditions = array();
        $options['fields'] = array('AdminModuleDetailSubMenuGroup.*','AdminModuleModule.module_name','AdminModuleMenu.menu_title','AdminModuleSubMenu.sub_menu_title','AdminModuleUserGroup.group_name');

        if ($this->request->is('post')) {
            if ($opt=='custom') {
                $module_id = $this->request->data['AdminModuleDetailSubMenuGroup']['module_id'];
                if(!empty($module_id)) {
                    $this->Session->write('Module.Id',$module_id);
                }
                $menu_id = $this->request->data['AdminModuleDetailSubMenuGroup']['menu_id'];
                $sub_menu_id = $this->request->data['AdminModuleDetailSubMenuGroup']['sub_menu_id'];
                $conditions = array('AdminModuleDetailSubMenuGroup.module_id'=>$module_id,
                                    'AdminModuleDetailSubMenuGroup.menu_id'=>$menu_id,
                                    'AdminModuleDetailSubMenuGroup.sub_menu_id'=>$sub_menu_id);
            }
            $options['conditions'] = $conditions;
        }
        $this->paginate = $options;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('AdminModuleDetailSubMenuGroup');        
        $this->set(compact('values','module_options','menu_options','sub_menu_options'));
    }

    public function assign_role() {
        $this->loadModel('AdminModuleModule');        
        $this->loadModel('AdminModuleUserGroup');
        $module_options = $this->AdminModuleModule->find('list', array('fields' => array('AdminModuleModule.module_id', 'AdminModuleModule.module_name'),'order'=>array('AdminModuleModule.module_name'=>'ASC')));
        $menu_options = $sub_menu_options = $user_group_options = array();
        $this->set(compact('module_options','menu_options','sub_menu_options','user_group_options'));
        if ($this->request->is('post')) {                                
            $module_id = $this->request->data['AdminModuleDetailSubMenuGroup']['module_id'];
            $menu_id = $this->request->data['AdminModuleDetailSubMenuGroup']['menu_id'];
            $sub_menu_id = $this->request->data['AdminModuleDetailSubMenuGroup']['sub_menu_id'];
            $user_group_id = $this->request->data['AdminModuleDetailSubMenuGroup']['user_group_id'];
            $existing_values = $this->AdminModuleDetailSubMenuGroup->find('first',array('conditions' => array('AdminModuleDetailSubMenuGroup.module_id' => $module_id,'AdminModuleDetailSubMenuGroup.menu_id' => $menu_id,'AdminModuleDetailSubMenuGroup.sub_menu_id' => $sub_menu_id,'AdminModuleDetailSubMenuGroup.user_group_id' => $user_group_id)));
            if(!empty($existing_values)) {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => 'Group Role is already Assigned!'
                );
                $this->set(compact('msg'));
                return;
            }
            $this->AdminModuleDetailSubMenuGroup->create();
            $savedData = $this->AdminModuleDetailSubMenuGroup->save($this->request->data);
            if($savedData) {
                $this->redirect(array('action' => 'view'));
            }
        }
    }
    
    public function re_assign_role($module_id=null, $menu_id=null, $sub_menu_id=null, $user_group_id=null) {
        $this->loadModel('AdminModuleModule');
        $this->loadModel('AdminModuleMenu'); 
        $this->loadModel('AdminModuleSubMenu');
        $this->loadModel('AdminModuleUserGroup');
        $module_options = $this->AdminModuleModule->find('list', array('fields' => array('AdminModuleModule.module_id', 'AdminModuleModule.module_name'),'order'=>array('AdminModuleModule.module_name'=>'ASC')));
        $menu_options = $this->AdminModuleMenu->find('list', array('fields' => array('AdminModuleMenu.menu_id', 'AdminModuleMenu.menu_title'),'conditions' => array('AdminModuleMenu.module_id' => $module_id)));
        $sub_menu_options = $this->AdminModuleSubMenu->find('list', array('fields' => array('AdminModuleSubMenu.sub_menu_id', 'AdminModuleSubMenu.sub_menu_title'),'conditions' => array('AdminModuleSubMenu.module_id' => $module_id,'AdminModuleSubMenu.menu_id' => $menu_id)));
        $user_group_options = $this->AdminModuleUserGroup->find('list', array('fields' => array('AdminModuleUserGroup.id', 'AdminModuleUserGroup.group_name')));
        $this->set(compact('module_options','menu_options','sub_menu_options','user_group_options'));
        $conditions = array('AdminModuleDetailSubMenuGroup.module_id' => $module_id,'AdminModuleDetailSubMenuGroup.menu_id' => $menu_id,'AdminModuleDetailSubMenuGroup.sub_menu_id' => $sub_menu_id,'AdminModuleDetailSubMenuGroup.user_group_id' => $user_group_id);
        if ($this->request->is(array('post', 'put'))) {    
            $savedData = $this->AdminModuleDetailSubMenuGroup->updateAll(array('user_group_id' =>$this->request->data['AdminModuleDetailSubMenuGroup']['user_group_id']), $conditions);
            if($savedData) {
                $this->redirect(array('action' => 'view'));
            }
        }
        $post = $this->AdminModuleDetailSubMenuGroup->find('first',array('conditions' =>$conditions ));
        if (empty($this->request->data)) {
            $this->request->data = $post;
        }
    }
    
    function update_menu_options() {
        $this->loadModel('AdminModuleMenu');
        $module_id = $this->request->data['AdminModuleDetailSubMenuGroup']['module_id'];
        if(!empty($module_id)) {
            $this->Session->write('Module.Id',$module_id);
        }
        else{
            $module_id = $this->Session->read('Module.Id');
        }        
        $menu_options = $this->AdminModuleMenu->find('list', array(
            'fields' => array('AdminModuleMenu.menu_id', 'AdminModuleMenu.menu_title'),
            'conditions' => array('AdminModuleMenu.module_id' => $module_id),
            'recursive' => -1
        ));

        $this->set(compact('menu_options'));
        $this->layout = 'ajax';
    }

    function update_sub_menu_options() {
        $this->loadModel('AdminModuleSubMenu');
        $module_id = $this->Session->read('Module.Id');        
        $menu_id = $this->request->data['AdminModuleDetailSubMenuGroup']['menu_id']; 
        if(!empty($menu_id)) {
            $this->Session->write('Menu.Id',$menu_id);
        }
        $sub_menu_options = $this->AdminModuleSubMenu->find('list', array(
            'fields' => array('AdminModuleSubMenu.sub_menu_id', 'AdminModuleSubMenu.sub_menu_title'),
            'conditions' => array('AdminModuleSubMenu.module_id' => $module_id,'AdminModuleSubMenu.menu_id' => $menu_id),
            'recursive' => -1
        ));

        $this->set(compact('sub_menu_options'));
        $this->layout = 'ajax';
    }

    function update_user_group_list() {
        $this->loadModel('AdminModuleUserGroup');
        $module_id = $this->Session->read('Module.Id');
        $menu_id = $this->Session->read('Menu.Id');
        
        if(!empty($this->request->data['AdminModuleDetailSubMenuGroup']['sub_menu_id'])) {
            $sub_menu_id = $this->request->data['AdminModuleDetailSubMenuGroup']['sub_menu_id'];
            $user_group_ids = $this->AdminModuleDetailSubMenuGroup->find('list', array(
                'fields' => array('AdminModuleDetailSubMenuGroup.user_group_id','AdminModuleDetailSubMenuGroup.user_group_id'),
                'conditions' => array('AdminModuleDetailSubMenuGroup.module_id' => $module_id,'AdminModuleDetailSubMenuGroup.menu_id' => $menu_id,'AdminModuleDetailSubMenuGroup.sub_menu_id' => $sub_menu_id),
                'recursive' => -1
            ));            
            $user_group_lists = $this->AdminModuleUserGroup->find('list', array('fields' => array('AdminModuleUserGroup.id', 'AdminModuleUserGroup.group_name'),'conditions' => array('AdminModuleUserGroup.id' => $user_group_ids)));
            $this->set(compact('user_group_lists'));
        }
        $this->layout = 'ajax';
    }
    
    function update_user_group_options() {
        $this->loadModel('AdminModuleUserGroup');
        $module_id = $this->Session->read('Module.Id');
        $menu_id = $this->Session->read('Menu.Id');
        
        if(!empty($this->request->data['AdminModuleDetailSubMenuGroup']['sub_menu_id'])) {
            $sub_menu_id = $this->request->data['AdminModuleDetailSubMenuGroup']['sub_menu_id'];
            $user_group_ids = $this->AdminModuleDetailSubMenuGroup->find('list', array(
                'fields' => array('AdminModuleDetailSubMenuGroup.user_group_id','AdminModuleDetailSubMenuGroup.user_group_id'),
                'conditions' => array('AdminModuleDetailSubMenuGroup.module_id' => $module_id,'AdminModuleDetailSubMenuGroup.menu_id' => $menu_id,'AdminModuleDetailSubMenuGroup.sub_menu_id' => $sub_menu_id),
                'recursive' => -1
            ));            
            $user_group_options = $this->AdminModuleUserGroup->find('list', array('fields' => array('AdminModuleUserGroup.id', 'AdminModuleUserGroup.group_name'),'conditions' => array('NOT' => array('AdminModuleUserGroup.id' => $user_group_ids))));
            $this->set(compact('user_group_options'));
        }
        $this->layout = 'ajax';
    }

    function delete($module_id = null,$menu_id = null,$sub_menu_id = null,$user_group_id = null) {
        $conditions = array('AdminModuleDetailSubMenuGroup.module_id' => $module_id,'AdminModuleDetailSubMenuGroup.menu_id' => $menu_id,'AdminModuleDetailSubMenuGroup.sub_menu_id' => $sub_menu_id,'AdminModuleDetailSubMenuGroup.user_group_id' => $user_group_id);
        $sub_menu_group_deleted = $this->AdminModuleDetailSubMenuGroup->deleteAll($conditions, false);
        if($sub_menu_group_deleted) {
            return $this->redirect(array('action' => 'view'));
        }
    }
}