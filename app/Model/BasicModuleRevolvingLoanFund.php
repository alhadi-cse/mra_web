<?php

App::uses('AppModel', 'Model');

class BasicModuleRevolvingLoanFund extends AppModel {
     public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className'  => 'BasicModuleBasicInformation',
            'fields' => 'id,full_name_of_org,short_name_of_org',
            'foreignKey' => 'org_id'
        ),
        'LookupBasicFundSource' => array(
            'className'  => 'LookupBasicFundSource',
            'foreignKey' => 'fund_source_id'
        ),
        'LookupBasicFundSourceCategory' => array(
            'className'  => 'LookupBasicFundSourceCategory',
            'foreignKey' => 'fund_source_category_id'
        ),
        'LookupBasicFundSourceSubCategory' => array(
            'className'  => 'LookupBasicFundSourceSubCategory',
            'foreignKey' => 'fund_source_sub_category_id'
        )
    );
}