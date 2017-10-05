<?php

App::uses('AppModel', 'Model');

class LicenseModuleRecommendationOfEvaluationCommittee extends AppModel {

    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id,  full_name_of_org, short_name_of_org',
            'foreignKey' => 'org_id'
        ),
        'LookupLicenseRecommendationStatus' => array(
            'className' => 'LookupLicenseRecommendationStatus',
            'foreignKey' => 'recommendation_status_id'
        )
    );
    public $validate = array(
        'org_id' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Select an Organization',
                'required' => 'true',
                'allowEmpty' => false
            )
        )
    );

}
