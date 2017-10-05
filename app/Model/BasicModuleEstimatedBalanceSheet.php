<?php

App::uses('AppModel', 'Model');

class BasicModuleEstimatedBalanceSheet extends AppModel {

    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id, full_name_of_org, short_name_of_org',
            'foreignKey' => 'org_id'
        ),
        'LookupBasicStatementYear' => array(
            'className' => 'LookupBasicStatementYear',
            'foreignKey' => 'statement_year_id'
        )
    );
    
    public $virtualFields = array('total_based_groups' => 'cash_in_hand + cash_at_bank + short_term_investment + loans_to_other_mco + loans_to_member_bad_debt_provision + other_loans_in_asset + other_investments + land_and_building_net_of_depreciation + other_fixed_asset_net_of_depreciation + other_assets',
        'total_liabilities' => 'member_deposits + loans_from_pksf + loans_from_housing_fund + loans_from_other_government_sources + loans_from_other_microcredit_organizations + loans_from_commercial_banks + other_loans_in_liabilities + other_liabilities',
        'total_equity' => 'donor_funds + cumulative_surplus + other_funds');

}
