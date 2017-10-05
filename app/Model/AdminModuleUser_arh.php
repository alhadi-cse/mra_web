<?php

App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class AdminModuleUser extends AppModel {

    public $actsAs = array(
        'Captcha.Captcha' => array(
            'field' => array('captcha'),
            'error' => 'Answer is not correct'
        )
    );
    public $belongsTo = array(
        'LookupUserCommitteeMemberType' => array(
            'className' => 'LookupUserCommitteeMemberType',
            'foreignKey' => 'committe_member_type_id'
        ),
        'AdminModuleUserGroupDistribution' => array(
            'className' => 'AdminModuleUserGroupDistribution',
            'foreignKey' => false,
            'conditions' => 'AdminModuleUserGroupDistribution.user_id = AdminModuleUser.id'
        ),
        'AdminModuleUserGroup' => array(
            'className' => 'AdminModuleUserGroup',
            'foreignKey' => false,
            'conditions' => 'AdminModuleUserGroup.id = AdminModuleUserGroupDistribution.user_group_id'
        )
    );
    public $hasOne = array(
        'AdminModuleUserProfile' => array(
            'className' => 'AdminModuleUserProfile',
            'foreignKey' => 'user_id'
        )
    );

    public function check_confirmed_passwrd() {
        return $this->data[$this->alias]['user_passwrd'] === $this->data[$this->alias]['confirm_passwrd'];
    }

    public $validate = array(
        'user_name' => array(
            'required' => array(
                'required' => true,
                'allowEmpty' => false,
                'rule' => array('notBlank'),
                'on' => 'null',
                'message' => 'A username is required'
            )
        ),
        'user_passwrd' => array(
            'required' => array(
                'on' => 'null',
                'allowEmpty' => false,
                'rule' => array('notBlank'),
                'message' => 'A password is required'
            ),
            'length' => array(
                'rule' => array('minLength', '4'),
                'message' => 'Password must be at least 4 characters long',
            )
        ),
        'confirm_passwrd' => array(
            'required' => array(
                'on' => 'null',
                'allowEmpty' => false,
                'rule' => array('notBlank'),
                'message' => 'Confirmation of new password is required'
            ),
            'length' => array(
                'rule' => array('minLength', '4'),
                'message' => 'Password must be at least 4 characters long',
            ),
            'compare' => array(
                'rule' => array('check_confirmed_passwrd'),
                'message' => 'New password does not match with confirmed password',
            )
        ),
        'captcha' => array(
            'required' => array(
                'required' => true,
                'allowEmpty' => false,
                'rule' => array('notBlank'),
                'on' => 'null',
                'message' => 'Enter a valid code'
            )
        )
    );

}
