<?php

App::uses('AppModel', 'Model');

class SupervisionModulePrepareLetterDetail extends AppModel {

    public $belongsTo = array(
        'SupervisionModuleBasicInformation' => array(
            'className' => 'SupervisionModuleBasicInformation',
            'foreignKey' => 'supervision_basic_id'
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
        ),
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'foreignKey' => false,
            'conditions' => array('SupervisionModuleBasicInformation.org_id = BasicModuleBasicInformation.id')
        )
    );

}
