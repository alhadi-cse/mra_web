<?php

App::uses('AppModel', 'Model');

class SupervisionModuleFieldInspectionDetail extends AppModel {

    public $belongsTo = array(
        'SupervisionModuleBasicInformation' => array(
            'className' => 'SupervisionModuleBasicInformation',
            'foreignKey' => 'supervision_basic_id'
        ),
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id, full_name_of_org, short_name_of_org, license_no',
            'foreignKey' => false,
            'conditions' => 'BasicModuleBasicInformation.id = SupervisionModuleBasicInformation.org_id'
        ),
        'SupervisionModuleOrgSelectionDetail' => array(
            'className' => 'SupervisionModuleOrgSelectionDetail',
            'foreignKey' => false,
            'conditions' => array('SupervisionModuleBasicInformation.supervision_case_id = SupervisionModuleOrgSelectionDetail.id')
        ),
        'LookupSupervisionCategory' => array(
            'className' => 'LookupSupervisionCategory',
            'foreignKey' => false,
            'conditions' => array('SupervisionModuleOrgSelectionDetail.supervision_category_id = LookupSupervisionCategory.id')
        )
    );

}
