<?php

App::uses('AppModel', 'Model');

class BasicModuleProposedBranchImage extends AppModel {
     public $belongsTo = array(        
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',            
            'foreignKey' => 'org_id'
        ),
        'BasicModuleProposedBranchInfo' => array(
            'className' => 'BasicModuleProposedBranchInfo',            
            'foreignKey' => 'branch_id'
        )
    );
}
