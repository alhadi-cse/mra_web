<?php

App::uses('AppModel', 'Model');

class LookupRejectSuspendCancelStepwiseReason extends AppModel {
    public $belongsTo = array(
        'LookupRejectSuspendCancelHistoryType' => array(
        'className'    => 'LookupRejectSuspendCancelHistoryType',
        'foreignKey'   => 'reject_suspend_cancel_history_type_id'
        ),
        'LookupRejectSuspendCancelStepCategory' => array(
        'className'    => 'LookupRejectSuspendCancelStepCategory',
        'foreignKey'   => 'reject_suspend_cancel_category_id'
        )
    );
}