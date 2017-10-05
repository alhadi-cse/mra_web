<?php

App::uses('AppModel', 'Model');

class ViewReportBasicModuleBasicInfo extends AppModel {
    
    public $useTable = 'v_basic_module_basic_info';
   
    public $hasMany = array(
        'BasicModuleAddress' => array(
            'className' => 'BasicModuleAddress',
            'foreignKey' => 'org_id'                
        ),
        'BasicModuleBranchInfo' => array(
            'className' => 'BasicModuleBranchInfo',
            'foreignKey' => 'org_id'                
        ),
        'BasicModuleNonCurrentAsset' => array(
            'className' => 'BasicModuleNonCurrentAsset',
            'foreignKey' => 'org_id'                
        ),
        'BasicModuleRenewableSecurity' => array(
            'className' => 'BasicModuleRenewableSecurity',
            'foreignKey' => 'org_id'                
        ),
        'BasicModuleFundingInstitution' => array(
            'className' => 'BasicModuleFundingInstitution',
            'foreignKey' => 'org_id'                
        ),
        'BasicModuleBankInformation' => array(
            'className' => 'BasicModuleBankInformation',
            'foreignKey' => 'org_id'                
        ),
        'BasicModuleTransactionInfo' => array(
            'className' => 'BasicModuleTransactionInfo',
            'foreignKey' => 'org_id'                
        ),
        'BasicModuleProposedLoanInfo' => array(
            'className' => 'BasicModuleProposedLoanInfo',
            'foreignKey' => 'org_id'                
        ),
        'BasicModuleProposedSavingInfo' => array(
            'className' => 'BasicModuleProposedSavingInfo',
            'foreignKey' => 'org_id'                
        ),
        'BasicModuleOrgIncome' => array(
            'className' => 'BasicModuleOrgIncome',
            'foreignKey' => 'org_id'                
        ),
        'BasicModuleOrgExpenditure' => array(
            'className' => 'BasicModuleOrgExpenditure',
            'foreignKey' => 'org_id'                
        ),
        'BasicModuleOrgBalanceSheet' => array(
            'className' => 'BasicModuleOrgBalanceSheet',
            'foreignKey' => 'org_id'                
        ),
        'BasicModuleIncomeExpenditureStatement' => array(
            'className' => 'BasicModuleIncomeExpenditureStatement',
            'foreignKey' => 'org_id'                
        ),
        'BasicModuleBodyMemberInfo' => array(
            'className' => 'BasicModuleBodyMemberInfo',
            'foreignKey' => 'org_id'                
        ),
        'BasicModuleBodyMemberAffiliationInfo' => array(
            'className' => 'BasicModuleBodyMemberAffiliationInfo',
            'foreignKey' => 'org_id'                
        ),
        'BasicModuleBodyMemberEducationInfo' => array(
            'className' => 'BasicModuleBodyMemberEducationInfo',
            'foreignKey' => 'org_id'                
        ),
        'BasicModuleBranchHRInfo' => array(
            'className' => 'BasicModuleBranchHRInfo',
            'foreignKey' => 'org_id'                
        ),
        'BasicModuleCaseInformation' => array(
            'className' => 'BasicModuleCaseInformation',
            'foreignKey' => 'org_id'                
        ),
        'BasicModuleSisterOrganizationInfo' => array(
            'className' => 'BasicModuleSisterOrganizationInfo',
            'foreignKey' => 'org_id'                
        ),
        'BasicModuleOtherActivity' => array(
            'className' => 'BasicModuleOtherActivity',
            'foreignKey' => 'org_id'                
        ),
        'BasicModuleMCActivitiesPlan' => array(
            'className' => 'BasicModuleMCActivitiesPlan',
            'foreignKey' => 'org_id'                
        )
    );
       
    public $hasOne = array(
        'BasicModuleHumanResourcesInfo' => array(
            'className' => 'BasicModuleHumanResourcesInfo',            
            'foreignKey' => 'org_id'
        ),
        'BasicModuleGoverningBodyInfo' => array(
            'className' => 'BasicModuleGoverningBodyInfo',            
            'foreignKey' => 'org_id'
        ),
        'BasicModuleOrganizationCEO' => array(
            'className' => 'BasicModuleOrganizationCEO',            
            'foreignKey' => 'org_id'
        ),
        'BasicModuleOperationPolicy' => array(
            'className' => 'BasicModuleOperationPolicy',
            'foreignKey' => 'org_id'
        )
    );
}
