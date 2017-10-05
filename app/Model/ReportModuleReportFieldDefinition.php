<?php

App::uses('AppModel', 'Model');

class ReportModuleReportFieldDefinition extends AppModel {

    public $belongsTo = array(
        'ReportModuleReportDefinition' => array(
            'className' => 'ReportModuleReportDefinition',
            'foreignKey' => 'report_id'
        ),
        'ReportModuleReportFieldGroup' => array(
            'className' => 'ReportModuleReportFieldGroup',
            //'foreignKey' => 'field_group_id'
            'foreignKey' => false,
            'conditions' => 'ReportModuleReportFieldGroup.field_group_id = ReportModuleReportFieldDefinition.field_group_id'
        )
    );

}
