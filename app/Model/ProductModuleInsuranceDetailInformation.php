<?php

App::uses('AppModel', 'Model');

class ProductModuleInsuranceDetailInformation extends AppModel {
    public $belongsTo = array(
        'AdminModulePeriodList' => array(
            'className' => 'AdminModulePeriodList',
            'foreignKey' => 'period_id'
        ),
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id, license_no, full_name_of_org, short_name_of_org, name_of_org',
            'foreignKey' => 'org_id'
        ),
        'LookupProductInsuranceType' => array(
            'className' => 'LookupProductInsuranceType',            
            'foreignKey' => 'insurance_type_id'
        ),
        'LookupProductInsuranceFundSource' => array(
            'className' => 'LookupProductInsuranceFundSource',            
            'foreignKey' => 'source_of_fund_id'
        ),
        'LookupProductInsuranceCompanyName' => array(
            'className' => 'LookupProductInsuranceCompanyName',            
            'foreignKey' => 'ext_insurance_company_id'
        )
    );
}