<?php

App::uses('AppModel', 'Model');

class SupervisionModuleReplyOrExplanationOfMfiDetail extends AppModel {

    public $belongsTo = array(
        'SupervisionModuleIssueLetterToMfiDetail' => array(
            'className' => 'SupervisionModuleIssueLetterToMfiDetail',
            'foreignKey' => 'letter_id'
        ),
        'SupervisionModuleBasicInformation' => array(
            'className' => 'SupervisionModuleBasicInformation',
            'foreignKey' => 'supervision_basic_id'
        ),
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => array('id', 'full_name_of_org', 'short_name_of_org', 'license_no'),
            'foreignKey' => false,
            'conditions' => array('BasicModuleBasicInformation.id = SupervisionModuleBasicInformation.org_id')
        ),
        'SupervisionModuleOrgSelectionDetail' => array(
            'className' => 'SupervisionModuleOrgSelectionDetail',
            'foreignKey' => false,
            'conditions' => array('SupervisionModuleBasicInformation.supervision_case_id = SupervisionModuleOrgSelectionDetail.id',
                'SupervisionModuleOrgSelectionDetail.is_running_case' => 1)
        ),
        'LookupSupervisionCategory' => array(
            'className' => 'LookupSupervisionCategory',
            'foreignKey' => false,
            'conditions' => array('SupervisionModuleOrgSelectionDetail.supervision_category_id = LookupSupervisionCategory.id')
        )
    );

}

