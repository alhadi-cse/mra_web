<?php

App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class AdminModuleMfiSignUpDetail extends AppModel {

    public $actsAs = array(
        'Captcha' => array(
            'field' => array('captcha'),
            'error' => 'Incorrect security code'
        )
    );
    public $belongsTo = array(
        'LookupAdminBoundaryDistrict' => array(
            'className' => 'LookupAdminBoundaryDistrict',
            'foreignKey' => 'district_id'
        )
    );

    public function beforeSave($options = array()) {
        if (!empty($this->data[$this->alias]['user_passwrd'])) {
            $passwordHasher = new SimplePasswordHasher(array('hashType' => 'sha256'));
            $this->data[$this->alias]['user_passwrd'] = $passwordHasher->hash(
                    $this->data[$this->alias]['user_passwrd']
            );
        }
        return true;
    }

    public $validate = array(
        'org_type_id' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Select a Type of Organization'
            )
        ),
        'full_name_of_org' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Full name of MFI is required'
            )
        ),
        'full_name_of_authorized_person' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Name of authorized person is required'
            )
        ),
        'designation_of_authorized_person' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Designation of authorized person is required'
            )
        ),
        'mobile_no' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Mobile no is required'
            )
        ),
        'email' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Email address is required'
            ),
            'email' => array(
                'rule' => array('email'),
                'message' => 'Enter a valid mail address'
            )
        )
    );

}
