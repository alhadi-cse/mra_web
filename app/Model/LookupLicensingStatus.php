<?php

App::uses('AppModel', 'Model');

class LookupLicensingStatus extends AppModel {

    public $validate = array(
        'serialNo' => array(
            'rule' => 'notBlank',
            'message' => 'Enter a valid serial no.'
        )
    );

}
