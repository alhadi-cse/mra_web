<?php

App::uses('AppModel', 'Model');

class LicenseModuleRejectSuspendCancelHistory extends AppModel {
    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id, form_serial_no, full_name_of_org, short_name_of_org, licensing_year',
            'foreignKey' => 'org_id'
        ),
        'LookupRejectSuspendCancelHistoryType' => array(
            'className' => 'LookupRejectSuspendCancelHistoryType',
            'foreignKey' => 'reject_suspend_cancel_history_type_id'
        ),
        'LookupRejectSuspendCancelStepCategory' => array(
            'className' => 'LookupRejectSuspendCancelStepCategory',
            'foreignKey' => 'reject_suspend_cancel_category_id'
        ),
        'LookupRejectSuspendCancelStepwiseReason' => array(
            'className' => 'LookupRejectSuspendCancelStepwiseReason',
            'foreignKey' => 'reject_suspend_cancel_reason_id'
        )
    );

}
