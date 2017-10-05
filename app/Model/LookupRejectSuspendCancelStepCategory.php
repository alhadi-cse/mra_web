<?php

App::uses('AppModel', 'Model');

class LookupRejectSuspendCancelStepCategory extends AppModel {
    public $belongsTo = array(
        'LookupRejectSuspendCancelHistoryType' => array(
        'className'    => 'LookupRejectSuspendCancelHistoryType',
        'foreignKey'   => 'reject_suspend_cancel_history_type_id'
        )
    );
}
