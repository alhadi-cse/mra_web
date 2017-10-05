<?php

App::uses('AppModel', 'Model');

class BasicModuleLicensedOrgMonthlySummaryInformation extends AppModel {

    public $belongsTo = array(
        'AdminModulePeriodList' => array(
            'className' => 'AdminModulePeriodList',
            'foreignKey' => 'period_id'
        ),
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id, license_no, full_name_of_org, short_name_of_org, name_of_org',
            'foreignKey' => 'org_id'
        )
    );
    
    public $virtualFields = array('total_borrowers' => 'no_of_male_borrowers + no_of_female_borrowers',
        'total_members' => 'no_of_male_members + no_of_female_members',
        'total_savings' => 'savings_compulsory + savings_voluntary + savings_term',
        'total_loan_disbursement_general_microcredit' => 'loan_disbursement_general_microcredit_agri + loan_disbursement_general_microcredit_others',
        'total_loan_disbursement_micro_enterprise' => 'loan_disbursement_micro_enterprise_agri + loan_disbursement_micro_enterprise_others',
        'total_loan_disbursement_upp' => 'loan_disbursement_upp_agri + loan_disbursement_upp_others',
        'total_loan_disbursement_others' => 'loan_disbursement_others_agri + loan_disbursement_others_other',
        'total_loan_recovery_general_microcredit' => 'loan_recovery_general_microcredit_agri + loan_recovery_general_microcredit_others',
        'total_loan_recovery_micro_enterprise' => 'loan_recovery_micro_enterprise_agri + loan_recovery_micro_enterprise_others',
        'total_loan_recovery_upp' => 'loan_recovery_upp_agri + loan_recovery_upp_others',
        'total_loan_recovery_others' => 'loan_recovery_others_agri + loan_recovery_others_other',
        'total_loan_outstanding_general_microcredit' => 'loan_outstanding_general_microcredit_agri + loan_outstanding_general_microcredit_others',
        'total_loan_outstanding_micro_enterprise' => 'loan_outstanding_micro_enterprise_agri + loan_outstanding_micro_enterprise_others',
        'total_loan_outstanding_upp' => 'loan_outstanding_upp_agri + loan_outstanding_upp_others',
        'total_loan_outstanding_others' => 'loan_outstanding_others_agri + loan_outstanding_others_other');

}
