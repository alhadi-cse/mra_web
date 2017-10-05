<?php

App::uses('AppModel', 'Model');

class LookupLoanSubSubCategory extends AppModel {    
    public $belongsTo = array(        
        'LookupLoanSubCategory' => array(
            'className'  => 'LookupLoanSubCategory',            
            'foreignKey' => 'loan_sub_category_id'
        )
    );
}
