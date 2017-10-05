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

    public function check_confirmed_passwrd($user_passwrd = 'user_passwrd', $confirm_passwrd = 'confirm_passwrd') {
        return $this->data[$this->alias][$user_passwrd] === $this->data[$this->alias][$confirm_passwrd];
    }

    public function alphaNumericDashUnderscore($check) {
        $value = array_values($check);
        $value = $value[0];

        return preg_match('|^[0-9a-zA-Z_-]*$|', $value);
    }

    /*
      public $validate = array(
      'user_name' => array(
      'Username required' => array(
      'required' => true,
      'allowEmpty' => false,
      'rule' => array('notBlank'),
      'message' => 'Username is required !'
      ),
      'Username is' => array(
      'rule' => 'alphaNumeric',
      'required' => true,
      'message' => 'Username can only be letters and numbers !'
      //                ,
      //                'rule' => 'alphaNumericDashUnderscore',
      //                'message' => 'Username can only be letters, numbers, dash and underscore !'
      ),
      'Username between' => array(
      'rule' => array('lengthBetween', 5, 20),
      'message' => 'Username must be between 5 to 20 characters !'
      )
      ),
      'user_passwrd' => array(
      'Password required' => array(
      'allowEmpty' => false,
      'rule' => array('notBlank'),
      'message' => 'A password is required !'
      ),
      'Password min length' => array(
      'rule' => array('minLength', '5'),
      'message' => 'Password must be at least 5 characters long.',
      )
      ),
      'confirm_passwrd' => array(
      'Re-password required' => array(
      'allowEmpty' => false,
      'rule' => array('notBlank'),
      'message' => 'Confirmation of new password is required'
      ),
      //            'length' => array(
      //                'rule' => array('minLength', '5'),
      //                'message' => 'Password must be at least 5 characters long',
      //            ),
      'Password compare' => array(
      'rule' => array('check_confirmed_passwrd'),
      'message' => 'New password does not match with confirmed password',
      )
      )
      );
     */

    public $validate = array(
        'user_passwrd' => array(
            'Password required' => array(
                'allowEmpty' => false,
                'rule' => array('notBlank'),
                'message' => 'A password is required !'
            ),
            'Password min length' => array(
                'rule' => array('minLength', '5'),
                'message' => 'Password must be at least 5 characters long.',
            )
        ),
        'confirm_passwrd' => array(
            'Re-password required' => array(
                'allowEmpty' => false,
                'rule' => array('notBlank'),
                'message' => 'Confirmation of new password is required'
            ),
            'Password compare' => array(
                'rule' => array('check_confirmed_passwrd', 'user_passwrd', 'confirm_passwrd'),
                'message' => 'New password does not match with confirmed password',
            )
        ),
        'new_user_passwrd' => array(
            'Password min length' => array(
                'rule' => array('minLength', '5'),
                'message' => 'Password must be at least 5 characters long.',
            )
        ),
        'new_confirm_passwrd' => array(
            'Password compare' => array(
                'rule' => array('check_confirmed_passwrd', 'new_user_passwrd', 'new_confirm_passwrd'),
                'message' => 'New password does not match with confirmed password',
            )
        )
    );
    public $validate_reset = array(
        'user_passwrd' => array(
            'Password min length' => array(
                'rule' => array('minLength', '5'),
                'message' => 'Password must be at least 5 characters long.',
            )
        ),
        'confirm_passwrd' => array(
            'Password compare' => array(
                'rule' => array('check_confirmed_passwrd', 'user_passwrd', 'confirm_passwrd'),
                'message' => 'New password does not match with confirmed password',
            )
        )
    );

}
