<?php

App::uses('AppModel', 'Model');

class BasicModuleLicensedOrgConsolidatedFinancialPosition extends AppModel {

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
    
    public $virtualFields = array('total_non_current_assets' => "property_plant_equipment + long_term_investments + loan_to_other_micro_credit_org_long_term + other_loan_long_term",
        'total_current_assets' => 'loan_to_members + short_term_investments + loan_to_other_micro_credit_org_short_term + other_loan_short_term + account_recivables + advance_deposits_and_prepayments + stock_and_stores + cash_in_hand + cash_at_bank',
        'total_capital_funds' => 'donor_found + cumulative_surplus + other_funds',
        'total_non_current_liabilities' => 'loan_from_pksf_non_current + loan_from_housing_fund_long_term + loan_from_other_long_term + loan_from_other_government_sources + loan_from_other_micro_credit_org_long_term + loan_from_commercial_banks_long_term + other_loan_long_term',
        'total_current_liabilities' => 'loan_from_pksf_current + loan_from_housing_fund + loan_from_other_gov_sources_short_term + loan_from_other_micro_credit_org_short_term + loan_from_commercial_banks_short_term + other_loans_short_term + members_savings_deposits + account_payables + loan_loss_provision + gratuity_fund',
        'total_properties_and_assets' => 'property_plant_equipment + long_term_investments + loan_to_other_micro_credit_org_long_term + other_loan_long_term + loan_to_members + short_term_investments + loan_to_other_micro_credit_org_short_term + other_loan_short_term + account_recivables + advance_deposits_and_prepayments + stock_and_stores + cash_in_hand + cash_at_bank',
        'total_capital_fund_and_liabilities' => 'donor_found + cumulative_surplus + other_funds + loan_from_pksf_non_current + loan_from_housing_fund_long_term + loan_from_other_long_term + loan_from_other_government_sources + loan_from_other_micro_credit_org_long_term + loan_from_commercial_banks_long_term + other_loan_long_term + loan_from_pksf_current + loan_from_housing_fund + loan_from_other_gov_sources_short_term + loan_from_other_micro_credit_org_short_term + loan_from_commercial_banks_short_term + other_loans_short_term + members_savings_deposits + account_payables + loan_loss_provision + gratuity_fund');

}
