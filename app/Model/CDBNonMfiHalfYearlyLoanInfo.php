<?php

App::uses('AppModel', 'Model');

class CDBNonMfiHalfYearlyLoanInfo extends AppModel {

    public $belongsTo = array(
        'AdminModulePeriodList' => array(
            'className' => 'AdminModulePeriodList',
            'foreignKey' => 'period_id'
        ),
        'CDBNonMfiBasicInfo' => array(
            'className' => 'CDBNonMfiBasicInfo',
            'foreignKey' => 'org_id'
        ),
        'LookupLoanCategory' => array(
            'className' => 'LookupLoanCategory',
            'foreignKey' => 'loan_category_id'
        ),
        'LookupLoanSubCategory' => array(
            'className' => 'LookupLoanSubCategory',
            'foreignKey' => 'loan_sub_category_id'
        ),
        'LookupLoanSubSubCategory' => array(
            'className' => 'LookupLoanSubSubCategory',
            'foreignKey' => 'loan_sub_sub_category_id'
        )
    );
    
    public $virtualFields = array('loan_recoverable' => "loan_recoverable_principal + loan_recoverable_service_charge",
        'loan_realization' => "loan_realization_principal + loan_realization_service_charge",
        'total_borrowers' => "no_of_male_borrowers + no_of_female_borrowers"
    );

    //'total_savers' => "no_of_male_savers + no_of_female_savers"
}
