<?php

App::uses('AppModel', 'Model');

class LookupBasicFundSourceSubCategory extends AppModel {
    public $belongsTo = array(        
        'LookupBasicFundSource' => array(
            'className'  => 'LookupBasicFundSource',            
            'foreignKey' => 'fund_source_id'
        ),
        'LookupBasicFundSourceCategory' => array(
            'className'  => 'LookupBasicFundSourceCategory',            
            'foreignKey' => 'fund_source_category_id'
        )
    );
}
