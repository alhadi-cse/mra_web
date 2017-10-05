<?php

App::uses('AppModel', 'Model');

class CDBNonMfiBasicInfo extends AppModel {

    public $belongsTo = array(
        'LookupCDBNonMfiMinistryAuthorityName' => array(
            'className' => 'LookupCDBNonMfiMinistryAuthorityName',
            'foreignKey' => 'ministry_or_authority_id'
        )
    );

}
