<?php

App::uses('AppModel', 'Model');

class BasicModuleLicensedOrgIncomeAndExpenditure extends AppModel {

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

    public $virtualFields = array('total_income' => 'service_charges_on_loan + bank_interest + bank_interest_on_fdr + membership_fees + other_sales_form + donation + other_income',
        'total_expenditure' => 'service_charge_of_pksf_loan + interest_on_members_savings + other_loan_interest + salaries_and_allowance + office_rent + printing_and_stationary + traveling + telephone_and_postage + repair_and_maintenance + fuel_cost + gas_and_electricity + entertainment + advertisement + newspapers_and_periodicals + bank_charges_or_dd_charges + training_expense + vehicle_maintenance + legal_expenses + registration_fee + meeting_expenses + other_operating_expenses + audit_fees + board_members_honorarium + taxes + llp + dmfe + depreciation + other_expenses'
        );

}
