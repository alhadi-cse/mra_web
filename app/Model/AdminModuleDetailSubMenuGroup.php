<?php

App::uses('AppModel', 'Model');

class AdminModuleDetailSubMenuGroup extends AppModel {
 /**public $belongsTo = array(
        'AdminModuleUserGroup' => array(
            'className' => 'AdminModuleUserGroup',
            'foreignKey' => 'user_group_id'
        ),        
        'AdminModuleModule' => array(
            'className' => 'AdminModuleModule',
            'foreignKey' => false,
            'conditions' => 'AdminModuleModule.module_id = AdminModuleDetailSubMenuGroup.module_id'
        ),        
        'AdminModuleMenu' => array(
            'className' => 'AdminModuleMenu',
            'foreignKey' => false,
            'conditions' => array('AdminModuleDetailSubMenuGroup.module_id = AdminModuleMenu.module_id', 'AdminModuleDetailSubMenuGroup.menu_id = AdminModuleMenu.menu_id')
        ),        
        'AdminModuleSubMenu' => array(
            'className' => 'AdminModuleSubMenu',
            'foreignKey' => false,
            'conditions' => array('AdminModuleDetailSubMenuGroup.module_id = AdminModuleSubMenu.module_id', 'AdminModuleDetailSubMenuGroup.menu_id = AdminModuleSubMenu.menu_id', 'AdminModuleDetailSubMenuGroup.sub_menu_id = AdminModuleSubMenu.sub_menu_id')
        )
    );**/
    public $validate = array(
        'module_id' => array(
            'required' => true,
            'allowEmpty' => false,
            'rule' => array('notBlank'),
            'on' => 'null',
            'message' => 'A Module Information is required'
        ),
        'menu_id' => array(
            'required' => true,
            'allowEmpty' => false,
            'rule' => array('notBlank'),
            'on' => 'null',
            'message' => 'A Category is required'
        ),
        'sub_menu_id' => array(
            'required' => true,
            'allowEmpty' => false,
            'rule' => array('notBlank'),
            'on' => 'null',
            'message' => 'A Sub-Category is required'
        ),
        'user_group_id' => array(
            'required' => true,
            'allowEmpty' => false,
            'rule' => array('notBlank'),
            'on' => 'null',
            'message' => 'A user group is required'
        )
    );    
}