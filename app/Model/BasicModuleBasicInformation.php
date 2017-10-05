<?php

App::uses('AppModel', 'Model');

class BasicModuleBasicInformation extends AppModel {

    public $belongsTo = array(
        'LicenseModuleStateName' => array(
            'className' => 'LicenseModuleStateName',
            'foreignKey' => 'licensing_state_id'
        ),
        'LicenseModuleCurrentStateHistory' => array(
            'className' => 'LicenseModuleStateHistory',
            'fields' => array('LicenseModuleCurrentStateHistory.date_of_state_update, LicenseModuleCurrentStateHistory.date_of_starting, LicenseModuleCurrentStateHistory.date_of_deadline'),
            'foreignKey' => false,
            'conditions' => array('BasicModuleBasicInformation.id = LicenseModuleCurrentStateHistory.org_id', 'BasicModuleBasicInformation.licensing_state_id = LicenseModuleCurrentStateHistory.state_id'),
            'order' => 'LicenseModuleCurrentStateHistory.date_of_state_update'
        ),
//        'HeadOfficeAddress' => array(
//            'className' => 'BasicModuleBranchInfo',
//            //'fields' => array('BasicModuleBranchInfo.branch_with_address, BasicModuleBranchInfo.office_type_id'),
//            'foreignKey' => false,
//            'conditions' => array('BasicModuleBasicInformation.id = HeadOfficeAddress.org_id', 'HeadOfficeAddress.office_type_id = 1'),
//            'order' => 'HeadOfficeAddress.id'
//        )
//        ,
        'BasicModuleBranchInfo' => array(
            'className' => 'BasicModuleBranchInfo',
            //'fields' => array('BasicModuleBranchInfo.office_type_id, BasicModuleBranchInfo.branch_with_address, BasicModuleBranchInfo.contract_info'),
            'foreignKey' => false,
            'conditions' => array('BasicModuleBasicInformation.id = BasicModuleBranchInfo.org_id', 'BasicModuleBranchInfo.office_type_id = 1'),
            'order' => 'BasicModuleBranchInfo.id'
        )
    );
    
    //public $virtualFields = array('name_of_org' => "CONCAT_WS('', full_name_of_org, CASE WHEN full_name_of_org != '' AND short_name_of_org != '' THEN CONCAT_WS('', ' (<strong>', short_name_of_org, '</strong>)') ELSE short_name_of_org END)");
    public $virtualFields = array('name_of_org' => "CONCAT_WS('', full_name_of_org, CASE WHEN full_name_of_org != '' AND short_name_of_org != '' THEN CONCAT_WS('', ' (<strong>', short_name_of_org, '</strong>)') ELSE short_name_of_org END)",
        'name_of_org_with_licens' => "CONCAT_WS('', full_name_of_org, CASE WHEN full_name_of_org != '' AND short_name_of_org != '' THEN CONCAT_WS('', ' (', short_name_of_org, ')') ELSE short_name_of_org END, CASE WHEN license_no != '' THEN CONCAT_WS('', ' (License No.: ', license_no, ')') ELSE '' END)");
    
    public $hasOne = array(
        'BasicModuleProposedDateOfCommencementMcOperation' => array(
            'className' => 'BasicModuleProposedDateOfCommencementMcOperation',
            'foreignKey' => 'org_id'
        ),
        'BasicModuleAuditInformation' => array(
            'className' => 'BasicModuleAuditInformation',
            'foreignKey' => 'org_id'
        ),
        'BasicModuleRejectionInformation' => array(
            'className' => 'BasicModuleRejectionInformation',
            'foreignKey' => 'org_id'
        ),
        'BasicModuleEmployeeInformation' => array(
            'className' => 'BasicModuleEmployeeInformation',
            'foreignKey' => 'org_id'
        )
    );
    public $hasMany = array(
        'LicenseModuleStateHistory' => array(
            'className' => 'LicenseModuleStateHistory',
            'foreignKey' => 'org_id',
            'order' => 'date_of_state_update'
        ),
        'BasicModuleProposedAddress' => array(
            'className' => 'BasicModuleProposedAddress',
            'foreignKey' => 'org_id',
            'order' => 'address_type_id'
        ),
        'BasicModuleRegistrationDetail' => array(
            'className' => 'BasicModuleRegistrationDetail',
            'foreignKey' => 'org_id'
        ),
        'BasicModuleProposedBranchInfo' => array(
            'className' => 'BasicModuleProposedBranchInfo',
            'foreignKey' => 'org_id'
        ),
        'BasicModuleBankInfoForTransaction' => array(
            'className' => 'BasicModuleBankInfoForTransaction',
            'foreignKey' => 'org_id'
        ),
        'BasicModuleRevolvingLoanFund' => array(
            'className' => 'BasicModuleRevolvingLoanFund',
            'foreignKey' => 'org_id'
        ),
        'BasicModuleProposedSavingsOrDepositInfo' => array(
            'className' => 'BasicModuleProposedSavingsOrDepositInfo',
            'foreignKey' => 'org_id'
        ),
        'BasicModuleProposedLoanInfo' => array(
            'className' => 'BasicModuleProposedLoanInfo',
            'foreignKey' => 'org_id'
        ),
        'BasicModuleEstimatedIncomeExpenditureStatement' => array(
            'className' => 'BasicModuleEstimatedIncomeExpenditureStatement',
            'foreignKey' => 'org_id'
        ),
        'BasicModuleEstimatedBalanceSheet' => array(
            'className' => 'BasicModuleEstimatedBalanceSheet',
            'foreignKey' => 'org_id'
        ),
        'BasicModulePlanForMicroCreditActivity' => array(
            'className' => 'BasicModulePlanForMicroCreditActivity',
            'foreignKey' => 'org_id'
        ),
        'BasicModuleOfficeSpaceUsage' => array(
            'className' => 'BasicModuleOfficeSpaceUsage',
            'foreignKey' => 'org_id'
        ),
        'BasicModuleOtherImmovableProperty' => array(
            'className' => 'BasicModuleOtherImmovableProperty',
            'foreignKey' => 'org_id'
        ),
        'BasicModuleGeneralBodyMemberInfo' => array(
            'className' => 'BasicModuleGeneralBodyMemberInfo',
            'foreignKey' => 'org_id'
        ),
        'BasicModuleGeneralBodyMembersEducationDetail' => array(
            'className' => 'BasicModuleGeneralBodyMembersEducationDetail',
            'foreignKey' => 'org_id'
        ),
        'BasicModuleGeneralBodyMembersFinancialInvolvement' => array(
            'className' => 'BasicModuleGeneralBodyMembersFinancialInvolvement',
            'foreignKey' => 'org_id'
        ),
        'BasicModulGeneralBodyMembersCaseOrSuitInfo' => array(
            'className' => 'BasicModuleGeneralBodyMembersCaseOrSuitInfo',
            'foreignKey' => 'org_id'
        ),
        'BasicModulGeneralBodyMembersOtherBusinessInvolvment' => array(
            'className' => 'BasicModuleGeneralBodyMembersOtherBusinessInvolvment',
            'foreignKey' => 'org_id'
        ),
        'BasicModulMembersOfCouncilDirectorsInformation' => array(
            'className' => 'BasicModuleMembersOfCouncilDirectorsInformation',
            'foreignKey' => 'org_id'
        ),
        'BasicModuleProposedOrActiveCeoInformation' => array(
            'className' => 'BasicModuleProposedOrActiveCeoInformation',
            'foreignKey' => 'org_id'
        ),
        'BasicModuleSisterOrganizationInformation' => array(
            'className' => 'BasicModuleSisterOrganizationInformation',
            'foreignKey' => 'org_id'
        ),
        'BasicModuleOtherProgramsInformation' => array(
            'className' => 'BasicModuleOtherProgramsInformation',
            'foreignKey' => 'org_id'
        ),
        'BasicModuleFileAttachment' => array(
            'className' => 'BasicModuleFileAttachment',
            'foreignKey' => 'org_id'
        )
    );

}
